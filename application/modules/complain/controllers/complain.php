<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); require_once 'definer.php';

class Complain extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        
        $this->load->model('Complain_model', '', TRUE);

        $this->properti = $this->property->get();
//        $this->acl->otentikasi();

        $this->modul = $this->components->get(strtolower(get_class($this)));
        $this->title = strtolower(get_class($this));
        $this->role = new Role_lib();
        $this->city = new City_lib();
        $this->damage = new Damage_lib();
        $this->api = new Api_lib();
        $this->category = new Category_lib();
        $this->api = new Api_lib();
        $this->notif = new Notif_lib();
        
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token'); 
        
    }

    private $properti, $modul, $title, $api,$notif;
    private $role, $damage, $category;
    
    function index()
    {   
       $this->session->unset_userdata('start'); 
       $this->session->unset_userdata('end');
       $this->get_last(); 
    }

//     ============== ajax ===========================
     
    public function getdatatable($search=null,$ticket='null',$customer='null',$category='null',$phone='null')
    {
        if(!$search){ $result = $this->Complain_model->get_last($this->modul['limit'])->result(); }
        else {$result = $this->Complain_model->search($ticket,$customer,$category,$phone)->result(); }
	
        $output = null;
        if ($result){
                
        foreach($result as $res)
	{
           if ($res->status == 0){ $stts = 'N'; }else{ $stts = 'Y'; }
           
	   $output[] = array ($res->id, $res->ticketno, tglincompletetime($res->dates), $res->cust_id, 
                              'DM-0'.$res->damage.' <br> '.strtoupper($this->damage->get_name($res->damage)),
                              $res->description, $res->status, $this->damage->get_status($res->damage), $res->log, $res->type,
                              $res->name, $res->phone, $res->district
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

    function get_last()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords('Complain Order');
        $data['h2title'] = 'Complain Order';
        $data['main_view'] = 'complain_view';
	$data['form_action'] = site_url($this->title.'/add_process');
        $data['form_action_update'] = site_url($this->title.'/update_process');
        $data['form_action_del'] = site_url($this->title.'/delete_all/hard');
        $data['form_action_report'] = site_url($this->title.'/report_process');
        $data['form_action_confirmation'] = site_url($this->title.'/payment_confirmation');
        $data['link'] = array('link_back' => anchor('main/','Back', array('class' => 'btn btn-danger')));

        $data['damage'] = $this->damage->combo_id();
        $data['category'] = $this->category->combo();
        $data['category_child'] = $this->category->combo_child();
        $data['array'] = array('','');
        $data['month'] = combo_month();
        $data['year'] = date('Y');
        $data['default']['month'] = date('n');
        
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
        $this->table->set_heading('#','No', 'Ticket No', 'Date', 'Reporter', 'Damage', 'Description', 'Status', 'Action');

        $data['table'] = $this->table->generate();
        $data['source'] = site_url($this->title.'/getdatatable/');
        $data['graph'] = site_url()."/complain/chart/".$this->input->post('cmonth').'/'.$this->input->post('tyear');
            
        // Load absen view dengan melewatkan var $data sbgai parameter
	$this->load->view('template', $data);
    }
    
    function chart($month=null,$year=null)
    {   
        $data = $this->category->get();
        $datax = array();
        foreach ($data as $res) 
        {  
           $tot = $this->Complain_model->get_complain_qty_based_category($res->id,$month,$year); 
           $point = array("label" => $res->name , "y" => $tot);
           array_push($datax, $point);      
        }
        echo json_encode($datax, JSON_NUMERIC_CHECK);
    }
    
    function publish($uid = null)
    {
       if ($this->acl->otentikasi2($this->title,'ajax') == TRUE){   
        try {
            $val = $this->Complain_model->get_by_id($uid)->row();
              if ($val->amount > 0){
                if ($val->approved == 0){   
                   if ($val->payment_id == 5){ $lng = array('approved' => 1, 'paid_date' => date('Y-m-d H:i:s'));}else{ $lng = array('approved' => 1);}
                   $this->Complain_model->update($uid,$lng);
                   $this->create_journal($uid);
                   echo 'true|Status Changed...!';
                }
                else { echo 'warning|Transaction has been posted...!'; }
            }else{ echo "error|Invalid Amount..!"; }  
        }
        catch(Exception $e) {
          echo 'error'.$e->getMessage();
        }
       }else{ echo "error|Sorry, you do not have the right to change publish status..!"; }
    }
    
    function delete_all($type='hard')
    {
      if ($this->acl->otentikasi_admin($this->title,'ajax') == TRUE){
      
        $cek = $this->input->post('cek');
        $jumlah = count($cek);

        if($cek)
        {
          $jumlah = count($cek);
          $x = 0;
          for ($i=0; $i<$jumlah; $i++)
          {
             if ($type == 'soft') { $this->Complain_model->delete($cek[$i]); }
             else { $this->shipping->delete_by_complain($cek[$i]);
                    $this->Complain_model->force_delete($cek[$i]);  
             }
             $x=$x+1;
          }
          $res = intval($jumlah-$x);
          //$this->session->set_flashdata('message', "$res $this->title successfully removed &nbsp; - &nbsp; $x related to another component..!!");
          $mess = "$res $this->title successfully removed &nbsp; - &nbsp; $x related to another component..!!";
          echo 'true|'.$mess;
        }
        else
        { //$this->session->set_flashdata('message', "No $this->title Selected..!!"); 
          $mess = "No $this->title Selected..!!";
          echo 'false|'.$mess;
        }
      }else{ echo "error|Sorry, you do not have the right to edit $this->title component..!"; }
      
    }

    function delete($uid)
    {
        if ($this->acl->otentikasi_admin($this->title,'ajax') == TRUE){
           
            $complain = $this->Complain_model->get_by_id($uid)->row();
            
            if ($this->damage->valid_damage($complain->damage) == TRUE){
                $this->Complain_model->delete($uid);
                echo "true|1 $this->title successfully removed..!";    
            }else{
                echo "error|Damage has been completed, can't removed..!";    
            }
        }else { echo "error|Sorry, you do not have the right to edit $this->title component..!"; }
        
    }
    
    function request($type=null){
        
        if (!$type){
          $nama = $this->input->post('nama');
          $no = $this->input->post('no');
          $id = $this->input->post('id');
          $meter = $this->input->post('meter');
          $nilai = '{ "no_pelanggan":"'.$no.'", "nama_pelanggan":"'.$nama.'", "id_pelanggan":"'.$id.'", "no_meter":"'.$meter.'" }';
        }else{ $nilai = '{ "no_pelanggan":"", "nama_pelanggan":"", "no_meter":"", "id_pelanggan":"'.$type.'" }'; }
        
        $url = constant("API").'customers';
        $response = $this->api->request($url, $nilai);
        $datax = (array) json_decode($response, true);
        if ($datax){ 
            if (!$type){ echo $datax[0]['No_Pelanggan'].'|'.$datax[0]['Nama_Pelanggan'].'|'.$datax[0]['ID_Pelanggan'].'|'.$datax[0]['No_Meter'].'|'.$datax[0]['Alamat'].' - '.$datax[0]['No_Rumah'];    
            }else{ return $datax[0]['No_Pelanggan'].'|'.$datax[0]['Nama_Pelanggan'].'|'.$datax[0]['ID_Pelanggan'].'|'.$datax[0]['Alamat'].'|'.$datax[0]['No_Rumah']; }
        }
    }
    
    function request_invoice($type=null){
        
        if (!$type){
          $nama = $this->input->post('nama');
          $no = $this->input->post('no');
          $id = $this->input->post('id');
          $meter = $this->input->post('meter');
          $nilai = '{ "no_pelanggan":"'.$no.'", "nama_pelanggan":"'.$nama.'", "id_pelanggan":"'.$id.'", "no_meter":"'.$meter.'" }';
        }else{ $nilai = '{ "no_pelanggan":"", "nama_pelanggan":"", "no_meter":"", "id_pelanggan":"'.$type.'" }'; }
        
        $url = constant("API").'customers';
        $response = $this->api->request($url, $nilai);
        $datax = (array) json_decode($response, true);
        if ($datax){ 
           return $datax[0]['No_Pelanggan'].'|'.$datax[0]['Nama_Pelanggan'].'|'.$datax[0]['ID_Pelanggan'].'|'.$datax[0]['Alamat'].'|'.$datax[0]['No_Rumah'];
        }
    }
    
    function cek_user($type=null){
        
        if ($type){ 
           $nilai = '{ "no_pelanggan":"", "nama_pelanggan":"", "no_meter":"", "id_pelanggan":"'.$type.'" }';
           $url = constant("API").'customers';
           $response = $this->api->request($url, $nilai);
           $datax = (array) json_decode($response, true);
           if ($datax){ 
              echo $datax[0]['No_Pelanggan'].'|'.$datax[0]['Nama_Pelanggan'].'|'.$datax[0]['ID_Pelanggan'].'|'.$datax[0]['No_Meter'].'|'.$datax[0]['Alamat'].' - '.$datax[0]['No_Rumah'];
           }
       }
    }
    
    function combo_damage($category){
        
        $damage = $this->damage->combo_category($category);
        $js = "class='form-control' id='cdamage' tabindex='-1' style='min-width:260px; margin-top:5px;' "; 
        echo form_dropdown('cdamage', $damage, isset($default['damage']) ? $default['damage'] : '', $js);
    }
    
    function get_address($uid){
        echo $this->damage->get_dates($uid).'  ::  '.$this->damage->get_address($uid);
    }
    
    function add()
    {
        $this->acl->otentikasi2($this->title);
         
        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = 'Create New '.$this->modul['title'];
        $data['main_view'] = 'complain_form';
        $data['form_action'] = site_url($this->title.'/add_process');
        $data['form_action_damage'] = site_url('damage/add_process');

        $data['damage'] = $this->damage->combo_id();
        $data['category'] = $this->category->combo_child();
        
        $data['source'] = site_url($this->title.'/getdatatable');
        $data['graph'] = site_url()."/complain/chart/";
        $data['default']['dates'] = date("Y/m/d");

        $this->load->view('template', $data);
    }
    
    private function valid_json($param){
        
      if (isset($param['custid']) && isset($param['name']) && isset($param['phone']) && isset($param['description']))
      { return TRUE; }else{ return FALSE; }
    }
    
    function add_json()
    {
        $datax = (array)json_decode(file_get_contents('php://input'));  
        $status = 200;
        $error = null;
        $mess = null;
        if ($this->valid_json($datax) == FALSE){ $mess = 'JSON Invalid Format'; }
        if (!$mess){ if ($this->Complain_model->valid_ticket($datax['custid']) == FALSE){ $mess = 'Anda masih memiliki ticket aktif'; } }
        
        if (!$mess)
        { 
            $val = explode('.', $datax['custid']);
            if ($val[0] == '07'){$dictrict = 1;}else{ $district = 0; }
            $ticket = '0'.$this->Complain_model->counter().date("mdHi");
            $complain = array('cust_id' => $datax['custid'], 'dates' => date("Y-m-d H:i:s"),
                              'type' => 0, 'name' => $datax['name'], 'phone' => $datax['phone'],
                              'ticketno' => $ticket, 'category' => 0, 'district' => $district,
                              'description' => $datax['description'], 'damage' => 0, 
                              'status' => 0, 
                              'created' => date('Y-m-d H:i:s'), 'log' => $this->session->userdata('log'));

            $this->Complain_model->add($complain);
            $error = $ticket;
        }
        else{ $error = $mess; $status = 401; }
        $response = array('error' => $error); 
        $this->api->response($response, $status);
    }
    
    function get_complain_by_customer_json(){
        
        $datax = (array)json_decode(file_get_contents('php://input'));
        $status = 200;
        $error = null;
        $content = null;
        
        if ($datax['custid'] != null && $datax['limit'] != null && $datax['start'] != null){
            
            $output = null;
            $result = $this->Complain_model->search_json($datax['custid'],$datax['limit'], $datax['start'])->result();
            $num = $this->Complain_model->search_json($datax['custid'],$datax['limit'], $datax['start'])->num_rows();

            foreach ($result as $res){
               
                if ($res->status == 0){ $stts = 'Pending'; }else{ $stts = 'Progress'; }
                $output[] = array ("id" => $res->id, "ticketno" => $res->ticketno, "dates" => tglincompletetime($res->dates), 
                                   "description" => $res->description, "status" => $stts,
                                   "reporter" => $res->name, "reporter_phone" => $res->phone
                             );
            }

            if ($num > 0){ $content = $output; }else{ $content = 'reachedMax'; }
        }else{ $error = "JSON Invalid Format"; $status = 401; }
        $response = array('content' => $content, 'error' => $error); 
        $this->api->response($response, $status);
    }

    function add_process()
    {
        if ($this->acl->otentikasi2($this->title,'ajax') == TRUE){

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'category_view';
	$data['form_action'] = site_url($this->title.'/add_process');
	$data['link'] = array('link_back' => anchor('category/','<span>back</span>', array('class' => 'back')));

	// Form validation
        $this->form_validation->set_rules('ctype', 'Jenis Pelapor', 'required');
        $this->form_validation->set_rules('hcust', 'Customer Required', 'callback_valid_customer');
        $this->form_validation->set_rules('ccategory', 'Category', 'required|callback_valid_category');
        $this->form_validation->set_rules('tdescription', 'Description', 'required');
        $this->form_validation->set_rules('tname', 'Nama Pelapor', 'required');
        $this->form_validation->set_rules('tphone', 'No HP Pelapor', 'numeric');

        if ($this->form_validation->run($this) == TRUE)
        {   
            $ticket = '0'.$this->Complain_model->counter().date("mdHi");
            $complain = array('cust_id' => $this->input->post('hcust'), 'dates' => date("Y-m-d H:i:s"),
                           'type' => $this->input->post('ctype'), 'name' => $this->input->post('tname'), 'phone' => $this->input->post('tphone'),
                           'ticketno' => $ticket, 'category' => $this->input->post('ccategory'), 'district' => $this->input->post('cdistrict'),
                           'description' => $this->input->post('tdescription'), 'damage' => $this->input->post('cdamage'), 
                           'status' => 1, 
                           'created' => date('Y-m-d H:i:s'), 'log' => $this->session->userdata('log'));

            $this->Complain_model->add($complain);
            echo "true|One $this->title data successfully saved!|".$this->Complain_model->counter(1).'|'.$ticket;
           // $this->session->set_flashdata('message', "One $this->title data successfully saved!");
//            redirect($this->title.'/update/'.$this->Complain_model->counter(1));
        }
        else{ $data['message'] = validation_errors(); echo "error|".validation_errors(); }
        }else { echo "error|Sorry, you do not have the right to edit $this->title component..!"; }
    }
    
    private function split_array($val)
    { return implode(",",$val); }
    
    function update_item($uid)
    {
        $acc = $this->sitem->get_by_id($uid)->row();
        echo $acc->id.'|'.$acc->complain_id.'|'.$acc->passenger.'|'.$acc->idcard.'|'.$acc->source.'|'.$acc->dates.'|'.$acc->source_desc.'|'.$acc->destination.'|'.$acc->destination_desc.'|'.$acc->return_dates.'|'.$acc->ticketno.'|'.
             $acc->bookcode.'|'.$acc->airline.'|'.$acc->vendor.'|'.$acc->price.'|'.$acc->amount.'|'.$acc->hpp.'|'.$acc->discount.'|'.$acc->tax.'|'.$acc->returns;
    }
    
    // Fungsi update untuk menset texfield dengan nilai dari database
    function update($param=0)
    {
        $complain = $this->Complain_model->get_by_id($param)->row();
        $customer = explode('|', $this->request($complain->cust_id));
        echo $complain->id.'|'.$complain->name.'|'.$complain->phone.'|'.$complain->ticketno.'|'.$complain->dates.'|'.$complain->cust_id.'|'.$complain->category.'|'. $complain->damage.'|'.
             $customer[1].'|'.$customer[0].'|'.$customer[3].' - '.$customer[4].'|'.$complain->description;
    }
    
    function confirmation()
    {
        if ($this->acl->otentikasi2($this->title,'ajax') == TRUE){

	// Form validation
        $this->form_validation->set_rules('ccategory', 'Category', 'required');
        $this->form_validation->set_rules('cdamage', 'Damage', 'required');
        $this->form_validation->set_rules('taddress', 'Address', 'required');

        if ($this->form_validation->run($this) == TRUE)
        {     
            $complain = array('category' => $this->input->post('ccategory'), 'status' => 1,
                              'damage' => $this->input->post('cdamage'),'log' => $this->session->userdata('log'));
            
            $this->Complain_model->update($this->input->post('tid'), $complain);
            $this->send_notif($this->input->post('tid'));
            echo "true|One $this->title data successfully saved!|";
        }
        else{ echo "error|".validation_errors(); }
        }else { echo "error|Sorry, you do not have the right to edit $this->title component..!"; }
    }
    
   private function send_notif($uid){
       $complain = $this->Complain_model->get_by_id($uid)->row();
       $content = 'Keluhan Ticket : '.$complain->ticketno.' sedang di proses. Terima kasih, PDAM Tirtauli';
       $this->notif->send($complain->ticketno,$content);
   } 
    
   function invoice($sid=null,$type=null)
   {
       $this->acl->otentikasi2($this->title);

       $data['h2title'] = 'Print Tax Invoice'.$this->modul['title'];
       
       if ($type == 'code'){ $complain = $this->Complain_model->get_by_code($sid)->row();
       }else{ $complain = $this->Complain_model->get_by_id($sid)->row(); }
       
       // customer
       
       $customer = explode('|', $this->request_invoice($complain->cust_id));
       $data['customer'] = $complain->cust_id;
       $data['custname'] = $customer[1];
       $data['custno'] = $customer[0];
       $data['custaddress'] = $customer[3].' - '.$customer[4];
       $data['r_name'] = $complain->name;
       $data['r_phone'] = $complain->phone;

       //complain
       $data['pono'] = $complain->ticketno;
       $data['code'] = $complain->ticketno;
       $data['podate'] = tglincompletetime($complain->dates);
       $data['category'] = $this->category->get_name($complain->category);
       $data['damage'] = 'DM-0'.$complain->damage.' : '.$this->damage->get_name($complain->damage);
       $data['desc'] = $complain->description;
       $data['user'] = $this->session->userdata('username');
       $data['log'] = $this->session->userdata('log');

       // property display
       $data['logo'] = $this->properti['logo'];
       $data['paddress'] = $this->properti['address'];
       $data['p_phone1'] = $this->properti['phone1'];
       $data['p_phone2'] = $this->properti['phone2'];
       $data['p_city'] = ucfirst($this->properti['city']);
       $data['p_zip'] = $this->properti['zip'];
       $data['p_npwp'] = '';
       $data['p_sitename'] = $this->properti['sitename'];
       $data['p_email'] = $this->properti['email'];

        $this->load->view('complain_invoice', $data);
   }
    
    function update_process($param)
    {
        if ($this->acl->otentikasi2($this->title,'ajax') == TRUE){

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'complain_form';
        $data['form_action'] = site_url($this->title.'/update_process/'.$param); 
	$data['link'] = array('link_back' => anchor('category/','<span>back</span>', array('class' => 'back')));

	// Form validation
        $this->form_validation->set_rules('ccustomer', 'Customer', 'required');
        $this->form_validation->set_rules('tdates', 'Transaction Date', 'required');
        $this->form_validation->set_rules('tduedates', 'Transaction Due Date', 'required');
        $this->form_validation->set_rules('tcode', 'Transaction Code', 'required');
        $this->form_validation->set_rules('cpayment', 'Payment Type', 'required');
        $this->form_validation->set_rules('tcosts', 'Landed Cost', 'numeric');

        if ($this->form_validation->run($this) == TRUE && $this->valid_confirm($param) == TRUE)
        {     
            if ($this->input->post('cpayment') == 5){ $acc = $this->input->post('caccount');}else{ $acc = 0; }
            
            $complain = array('cust_id' => $this->input->post('ccustomer'), 'dates' => date("Y-m-d H:i:s"), 
                           'cost' => $this->input->post('tcosts'), 'code' => $this->input->post('tcode'), 'account' => $acc,
                           'due_date' => $this->input->post('tduedates'), 'payment_id' => $this->input->post('cpayment'),
                           'log' => $this->session->userdata('log'));
            
            $this->Complain_model->update($param, $complain);
            $this->update_trans($param);

            $this->session->set_flashdata('message', "One $this->title data successfully saved!");
            echo "true|One $this->title data successfully saved!|".$param;
        }
        elseif ($this->valid_confirm($param) != TRUE){ echo "error|Complain Already Confirmed..!"; }
        elseif ($this->valid_items($param) != TRUE){ echo "error|Complain Already Confirmed..!"; }
        else{ echo "error|".validation_errors(); }
        }else { echo "error|Sorry, you do not have the right to edit $this->title component..!"; }
        //redirect($this->title.'/update/'.$param);
    }
    
    function valid_customer($val){
        if ($this->input->post('ctype') == 0){
           if (!$val){
            $this->form_validation->set_message('valid_customer', "Customer Required..!"); return FALSE;
           }else{ return TRUE; }
        }else{ return TRUE; }
    }
    
    function valid_category($val){
        if ($val == 0){
            $this->form_validation->set_message('valid_category', "Category Required..!"); return FALSE;
        }else{ return TRUE; }
    }
    
    function valid_return($val)
    {
        $return = $this->input->post('ckreturn');
        $departdates = $this->input->post('tdepartdates');
        $arriveddates = $this->input->post('tarrivedates');
        
        if ($return == 1){
            if (!$val){
              $this->form_validation->set_message('valid_return', "Return Date Required..!"); return FALSE;
            }else{ 
                if ($departdates > $arriveddates){ $this->form_validation->set_message('valid_return', "Invalid Return Date..!"); return FALSE; }
                else{ return TRUE; }
            }
        }else{ return TRUE;  }
    }
    
    function valid_required($val)
    {
        $stts = $this->input->post('cstts');
        if ($stts == 1){
            if (!$val){
              $this->form_validation->set_message('valid_required', "Field Required..!"); return FALSE;
            }else{ return TRUE; }
        }else{ return TRUE;  }
    }
    
    function valid_login()
    {
        if (!$this->session->userdata('username')){
            $this->form_validation->set_message('valid_login', "Transaction rollback relogin to continue..!");
            return FALSE;
        }else{ return TRUE; }
    }
    
    function valid_name($val)
    {
        if ($this->Complain_model->valid('name',$val) == FALSE)
        {
            $this->form_validation->set_message('valid_name','Name registered..!');
            return FALSE;
        }
        else{ return TRUE; }
    }
    
    function valid_confirm($sid)
    {
        if ($this->Complain_model->valid_confirm($sid) == FALSE)
        {
            $this->form_validation->set_message('valid_confirm','Complain Already Confirmed..!');
            return FALSE;
        }
        else{ return TRUE; }
    }
    
    function valid_approval($sid)
    {
        $complain = $this->Complain_model->get_by_id($sid)->row();
        if ($complain->approved == 0)
        {
            $this->form_validation->set_message('valid_approval','Complain Already Not Approved..!');
            return FALSE;
        }
        else{ return TRUE; }
    }
   
    
    function report_process()
    {
        $this->acl->otentikasi2($this->title);
        $data['title'] = $this->properti['name'].' | Report '.ucwords($this->modul['title']);

        $data['rundate'] = tglin(date('Y-m-d'));
        $data['log'] = $this->session->userdata('log');
        $period = $this->input->post('reservation');  
        $start = picker_between_split($period, 0);
        $end = picker_between_split($period, 1);
        $confirm = $this->input->post('cconfirm');

        $data['start'] = tglin($start);
        $data['end'] = tglin($end);
        
//        Property Details
        $data['company'] = $this->properti['name'];
        $data['reports'] = $this->Complain_model->report($start,$end)->result();
//        
        $type = $this->input->post('ctype');
        if ($type == 0){ $this->load->view('complain_report', $data); }
        elseif($type == 1) { $this->load->view('complain_pivot', $data); }
    } 

}

?>