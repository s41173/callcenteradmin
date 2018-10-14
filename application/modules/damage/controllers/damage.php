<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Damage extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        
        $this->load->model('Damage_model', '', TRUE);

        $this->properti = $this->property->get();
        $this->acl->otentikasi();

        $this->modul = $this->components->get(strtolower(get_class($this)));
        $this->title = strtolower(get_class($this));

        $this->user = new Admin_lib();
        $this->load->library('terbilang');
        $this->category = new Category_lib();
        $this->complain = new Complain_lib();
    }

    private $properti, $modul, $title,$category;
    private $user, $complain;

    private  $atts = array('width'=> '800','height'=> '600',
                      'scrollbars' => 'yes','status'=> 'yes',
                      'resizable'=> 'yes','screenx'=> '0','screenx' => '\'+((parseInt(screen.width) - 800)/2)+\'',
                      'screeny'=> '0','class'=> 'print','title'=> 'print', 'screeny' => '\'+((parseInt(screen.height) - 600)/2)+\'');

    function index()
    {
       $this->get_last_damage();
    }
    
    public function getdatatable($search=null,$category='null',$status='null')
    {
        if(!$search){ $result = $this->Damage_model->get_last($this->modul['limit'])->result(); }
        else{ $result = $this->Damage_model->search($category,$status)->result(); }
        
        if ($result){
	foreach($result as $res)
	{
           if ($res->status == 0){ $stts = 'Progress'; }else{ $stts = 'Completed'; }
	   $output[] = array ($res->id, tglincompletetime($res->dates), tglincompletetime($res->due), strtoupper($this->category->get_name($res->category)), $res->description, $res->address, $res->coordinate, $stts, 
                              $res->approved, $this->complain->total_complain($res->id)
                             );
	}
            $this->output
            ->set_status_header(200)
            ->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode($output))
            ->_display();
            exit; 
        }
    }
    
    // ======= ajax =======================
    // fungsi untuk mendapatkan semua lokasi user yang tidak terkait booking
    function get_loc_all(){

        $output = null;
        $result = $this->Damage_model->get_coordinate()->result();
        foreach($result as $res){    
           $output[] = array ("description" => $res->description, "coordinate" => $res->coordinate);     
	} 

       echo json_encode($output, 128); 
    }
    
    // ======= ajax =======================
    
    function get_last_damage()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'damage_view';
	$data['form_action'] = site_url($this->title.'/add_process');
        $data['form_action_update'] = site_url($this->title.'/update_process');
        $data['form_action_del'] = site_url($this->title.'/delete_all');
        $data['form_action_report'] = site_url($this->title.'/report_process');
        $data['link'] = array('link_back' => anchor('main/','Back', array('class' => 'btn btn-danger')));
        
        $data['category'] = $this->category->combo_all();
	// ---------------------------------------- //
 
        $config['first_tag_open'] = $config['last_tag_open']= $config['next_tag_open']= $config['prev_tag_open'] = $config['num_tag_open'] = '<li>';
        $config['first_tag_close'] = $config['last_tag_close']= $config['next_tag_close']= $config['prev_tag_close'] = $config['num_tag_close'] = '</li>';

        $config['cur_tag_open'] = "<li><span><b>";
        $config['cur_tag_close'] = "</b></span></li>";

        // library HTML table untuk membuat template table class zebra
        $tmpl = array('table_open' => '<table id="datatable-buttons" class="table table-striped table-bordered">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('#','No', 'Code', 'Category', 'Date', 'Due', 'Description', 'Status', '#', 'Action');

        $data['table'] = $this->table->generate();
        $data['source'] = site_url($this->title.'/getdatatable');
            
        // Load absen view dengan melewatkan var $data sbgai parameter
	$this->load->view('template', $data);
    }
    
    private function get_acc($acc)
    {
        return $this->account->get_code($acc).' : '.$this->account->get_name($acc);
    }

    function confirmation($pid)
    {
        if ($this->acl->otentikasi3($this->title,'ajax') == TRUE){
        $damage = $this->Damage_model->get_by_id($pid)->row();

        if ($damage->approved == 1) { echo "warning|$this->title already approved..!"; }
        else
        {
            try {      
                $value = array('approved' => 1);
                $this->Damage_model->update($pid, $value);
                echo "true|DM-0$pid confirmed..!";
            }
            catch(Exception $e) { echo "error|".$e->getMessage(); }
        }
        }else { echo "error|Sorry, you do not have the right to edit $this->title component..!"; }
    }


//    ===================== approval ===========================================


    function delete($uid)
    {
        if ($this->acl->otentikasi_admin($this->title,'ajax') == TRUE){
        $val = $this->Damage_model->get_by_id($uid)->row();
            
        if ($val->approved == 1)
        { $value = array('approved' => 0, 'status' => 0,); $this->Damage_model->update($uid, $value);             
           echo "warning|1 $this->title successfully rollback..!";
        }
        else{ $this->Damage_model->delete($uid);
              echo "true|1 $this->title successfully soft removed..!";
        }        
        }else { echo "error|Sorry, you do not have the right to edit $this->title component..!"; }
    }
    
    
    function add_process()
    {
         if ($this->acl->otentikasi2($this->title,'ajax') == TRUE){

	// Form validation
        $this->form_validation->set_rules('tdates', 'Transaction Date', 'required');
        $this->form_validation->set_rules('ccategory', 'Category Type', 'required');
        $this->form_validation->set_rules('tdesc', 'Description', 'required');
        $this->form_validation->set_rules('taddress', 'Address Location', 'required');

        if ($this->form_validation->run($this) == TRUE)
        {
            $damage = array('dates' => $this->input->post('tdates'), 'category' => $this->input->post('ccategory'),
                            'description' => $this->input->post('tdesc'), 'address' => $this->input->post('taddress'), 
                            'coordinate' => $this->input->post('tccordinate'),
                            'log' => $this->session->userdata('log'), 'created' => date('Y-m-d H:i:s'));
            
            $this->Damage_model->add($damage);
            echo 'true|'.$this->title.' successfully saved..!';
        }
        else{ echo "error|".validation_errors(); }
        }else { echo "error|Sorry, you do not have the right to edit $this->title component..!"; }

    }

    function update($uid)
    {
        $acc = $this->Damage_model->get_by_id($uid)->row();
        echo $acc->id.'|'.$acc->dates.'|'.$acc->due.'|'.$acc->category.'|'.$acc->description.'|'.$acc->address.'|'.$acc->coordinate.'|'.$acc->status.'|'.$acc->approved.'|'. $acc->staff.'|'.$acc->log;
    }
    
    // Fungsi update untuk mengupdate db
    function update_process()
    {
        if ($this->acl->otentikasi2($this->title,'ajax') == TRUE){

	// Form validation
        $this->form_validation->set_rules('tdates', 'Transaction Date', 'required');
        $this->form_validation->set_rules('ccategory', 'Category Type', 'required');
        $this->form_validation->set_rules('tdesc', 'Description', 'required');
        $this->form_validation->set_rules('taddress', 'Address Location', 'required');

        if ($this->form_validation->run($this) == TRUE && $this->valid_confirmation($this->input->post('tid')) == TRUE)
        {
           $damage = array('dates' => $this->input->post('tdates'), 'due' => $this->input->post('tduedates'), 'category' => $this->input->post('ccategory'),
                           'description' => $this->input->post('tdesc'), 'address' => $this->input->post('taddress'),
                           'coordinate' => $this->input->post('tccordinate'),
                           'staff' => $this->input->post('tstaff'), 'log' => $this->session->userdata('log'));

            $this->Damage_model->update($this->input->post('tid'), $damage);
            echo 'true|Data successfully saved..!';
        }
        elseif ($this->valid_confirmation($this->input->post('tid')) != TRUE){ echo "warning|Journal approved, can't updated..!"; }
        else{ echo 'error|'.validation_errors(); }
        }else { echo "error|Sorry, you do not have the right to edit $this->title component..!"; }
    }
    
    function confirmation_process()
    {
        if ($this->acl->otentikasi2($this->title,'ajax') == TRUE){

	// Form validation
        $this->form_validation->set_rules('tduedates', 'Due Date', 'required');
        $this->form_validation->set_rules('tstaff', 'User In Charge', 'required');

        if ($this->form_validation->run($this) == TRUE && $this->valid_confirmation($this->input->post('tid')) == FALSE && $this->valid_status($this->input->post('tid')) == TRUE)
        {
           $damage = array('status' => 1, 'due' => $this->input->post('tduedates'),
                           'staff' => $this->input->post('tstaff'), 'log' => $this->session->userdata('log'));

            $this->Damage_model->update($this->input->post('tid'), $damage);
            echo 'true|Data successfully saved..!';
        }
        elseif ($this->valid_confirmation($this->input->post('tid')) != FALSE){ echo "warning|Journal not approved, can't updated..!"; }
        elseif ($this->valid_status($this->input->post('tid')) != TRUE){ echo "warning|Status already confirmed, can't updated..!"; }
        else{ echo 'error|'.validation_errors(); }
        }else { echo "error|Sorry, you do not have the right to edit $this->title component..!"; }
    }


    public function valid_period($date=null)
    {
        $p = new Period();
        $p->get();

        $month = date('n', strtotime($date));
        $year = date('Y', strtotime($date));

        if ( intval($p->month) != intval($month) || intval($p->year) != intval($year) )
        {
            $this->form_validation->set_message('valid_period', "Invalid Period.!");
            return FALSE;
        }
        else {  return TRUE; }
    }

    public function valid_confirmation($id)
    {
        $val = $this->Damage_model->get_by_id($id)->row();

        if ($val->approved == 1)
        {
            $this->form_validation->set_message('valid_confirmation', "Can't change value - Journal approved..!.!");
            return FALSE;
        }
        else {  return TRUE; }
    }
    
    public function valid_status($id)
    {
        $val = $this->Damage_model->get_by_id($id)->row();

        if ($val->status == 1)
        {
            $this->form_validation->set_message('valid_status', "Can't change value - Status confirmed..!.!");
            return FALSE;
        }
        else {  return TRUE; }
    }

// ===================================== PRINT ===========================================

   function invoice($pid=null)
   {
       $this->acl->otentikasi2($this->title);
       $damage = $this->Damage_model->get_by_id($pid)->row();

       $data['h2title'] = 'Print Invoice'.$this->modul['title'];
       if ($damage->status == 0){ $stts = 'Progress'; }else{ $stts = 'Completed'; }
       
       $data['p_name'] = $this->properti['name'];
       $data['pid'] = 'DM-0'.$pid;
       $data['date'] = tglincompletetime($damage->dates);
       $data['due'] = tglincompletetime($damage->due);
       $data['category'] = strtoupper($this->category->get_name($damage->category));
       $data['description'] = $damage->description;
       $data['address'] = $damage->address;
       $data['coordinate'] = $damage->coordinate;
       $data['status'] = $stts;
       $data['staff'] = $damage->staff;
       
       if ($damage->approved == 1){ $approval = 'Y'; }else{ $approval = 'N'; }
       $data['approved'] = $approval;
       $data['log'] = $this->session->userdata('log');
       $data['user'] = $this->session->userdata('username');

       $this->load->view('damage_invoice', $data);
   }
  
// ====================================== REPORT =========================================

    function report()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator Report '.ucwords($this->modul['title']);
        $data['h2title'] = 'Report '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/report_process');
        $data['link'] = array('link_back' => anchor('journal/','<span>back</span>', array('class' => 'back')));

        $data['currency'] = $this->currency->combo();
        
        $this->load->view('damage_report_panel', $data);
    }

    function report_process()
    {
        $this->acl->otentikasi2($this->title);
        $data['title'] = $this->properti['name'].' | Report '.ucwords($this->modul['title']);

        $period = $this->input->post('reservation');  
        $start = picker_between_split($period, 0);
        $end = picker_between_split($period, 1);

        $data['start'] = $start;
        $data['end'] = $end;
        $data['rundate'] = tglin(date('Y-m-d'));
        $data['log'] = $this->session->userdata('log');

//        Property Details
        $data['company'] = $this->properti['name'];
        $data['reports'] = $this->Damage_model->report($start,$end)->result();
        
        if ($this->input->post('ctype')==0){ $this->load->view('damage_report', $data);     
        }else{ $this->load->view('damage_pivot', $data);  }
    }

// ====================================== REPORT =========================================
    
}

?>