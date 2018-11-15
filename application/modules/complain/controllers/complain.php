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
    }

    private $properti, $modul, $title;
    private $role, $damage, $api, $category;
    
    function index()
    {   
       $this->session->unset_userdata('start'); 
       $this->session->unset_userdata('end');
       $this->get_last(); 
    }

//     ============== ajax ===========================
     
    public function getdatatable($search=null,$ticket='null',$customer='null',$category='null')
    {
        if(!$search){ $result = $this->Complain_model->get_last($this->modul['limit'])->result(); }
        else {$result = $this->Complain_model->search($ticket,$customer,$category)->result(); }
	
        $output = null;
        if ($result){
                
        foreach($result as $res)
	{
           if ($res->status == 0){ $stts = 'N'; }else{ $stts = 'Y'; }
           
	   $output[] = array ($res->id, $res->ticketno, tglincompletetime($res->dates), $res->cust_id, 
                              'DM-0'.$res->damage.' <br> '.strtoupper($this->damage->get_name($res->damage)),
                              $res->description, $stts, $this->damage->get_status($res->damage), $res->log
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
        $this->table->set_heading('#','No', 'Ticket No', 'Date', 'Customer', 'Damage', 'Description', 'Status', 'Action');

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
            if (!$type){ echo $datax[0]['No_Pelanggan'].'|'.$datax[0]['Nama_Pelanggan'].'|'.$datax[0]['ID_Pelanggan'].'|'.$datax[0]['No_Meter'];    
            }else{ return $datax[0]['No_Pelanggan'].'|'.$datax[0]['Nama_Pelanggan'].'|'.$datax[0]['ID_Pelanggan'].'|'.$datax[0]['Alamat'].'|'.$datax[0]['No_Rumah']; }
        }
    }
    
    function combo_damage($category){
        
        $damage = $this->damage->combo_category($category);
        $js = "class='form-control' id='cdamage' tabindex='-1' style='min-width:260px; margin-top:5px;' "; 
        echo form_dropdown('cdamage', $damage, isset($default['damage']) ? $default['damage'] : '', $js);
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
        $data['category'] = $this->category->combo();
        
        $data['source'] = site_url($this->title.'/getdatatable');
        $data['graph'] = site_url()."/complain/chart/";
        $data['default']['dates'] = date("Y/m/d");

        $this->load->view('template', $data);
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
        $this->form_validation->set_rules('hcust', 'Customer Required', 'required');
        $this->form_validation->set_rules('ccategory', 'Category', 'required');
        $this->form_validation->set_rules('tdescription', 'Description', 'required');

        if ($this->form_validation->run($this) == TRUE)
        {   
            $ticket = '0'.$this->Complain_model->counter().date("mdHi");
            $complain = array('cust_id' => $this->input->post('hcust'), 'dates' => date("Y-m-d H:i:s"), 
                           'ticketno' => $ticket, 'category' => $this->input->post('ccategory'),
                           'description' => $this->input->post('tdescription'), 'damage' => $this->input->post('cdamage'), 
                           'status' => 1, 
                           'created' => date('Y-m-d H:i:s'), 'log' => $this->session->userdata('log'));

            $this->Complain_model->add($complain);
            echo "true|One $this->title data successfully saved!|".$this->Complain_model->counter(1);
           // $this->session->set_flashdata('message', "One $this->title data successfully saved!");
//            redirect($this->title.'/update/'.$this->Complain_model->counter(1));
        }
        else{ $data['message'] = validation_errors(); echo "error|".validation_errors(); }
        }else { echo "error|Sorry, you do not have the right to edit $this->title component..!"; }

    }
    
    function add_item($sid=0)
    { 
       if ($this->acl->otentikasi2($this->title,'ajax') == TRUE){ 
       if ($sid == 0){ echo 'error|Complain ID not saved'; }
       else {
       
         // Form validation
        $this->form_validation->set_rules('tpassenger', 'Passenger', 'required');
        $this->form_validation->set_rules('tidcard', 'ID Card', 'required');
        $this->form_validation->set_rules('cdepart', 'Depart', 'required|callback_valid_depart');
        $this->form_validation->set_rules('tdepartdesc', 'Depart Description', '');
        $this->form_validation->set_rules('carrived', 'Arrived', 'required');
        $this->form_validation->set_rules('tarriveddesc', 'Arrived Description', '');
        $this->form_validation->set_rules('tdepartdates', 'Depart Dates', 'required');
        $this->form_validation->set_rules('tarrivedates', 'Arrived Dates', 'callback_valid_return');
        $this->form_validation->set_rules('cairline', 'Airline', 'required');
        $this->form_validation->set_rules('tbook', 'Book Code', 'required');
        $this->form_validation->set_rules('tticketno', 'Ticket No', 'required');
        $this->form_validation->set_rules('tcapital', 'Capital Price', 'required|numeric');
        $this->form_validation->set_rules('tprice', 'Price', 'required|numeric');
        $this->form_validation->set_rules('tdiscount', 'Discount', 'required|numeric');
        $this->form_validation->set_rules('ctax', 'Tax Type', 'required');

            if ($this->form_validation->run($this) == TRUE && $this->valid_confirm($sid) == TRUE)
            {
                // start transaction 
                $this->db->trans_start(); 
                
                $amt = floatval($this->input->post('tprice')-$this->input->post('tdiscount'));
                $tax = floatval($this->input->post('ctax')*$amt);
                $id = $this->sitem->counter();
                
                if ($this->airport->get_country($this->input->post('cdepart')) == $this->airport->get_country($this->input->post('carrived'))){
                    if ($this->airport->get_country($this->input->post('cdepart')) == 'id'){
                        $country = 'id';
                    }else{ $country = 'int'; }
                }else{ $country = 'int'; }
                if ($this->input->post('ckreturn') == 0){ $return = 'FALSE'; }else{ $return = 'TRUE'; }
                
                $complain = array('id' => $id, 'complain_id' => $sid, 'passenger' => $this->input->post('tpassenger'), 'idcard' => $this->input->post('tidcard'),
                               'source' => $this->input->post('cdepart'), 'dates' => $this->input->post('tdepartdates'), 'source_desc' => $this->input->post('tdepartdesc'),
                               'destination' => $this->input->post('carrived'), 'return_dates' => setnull($this->input->post('tarrivedates')), 'destination_desc' => $this->input->post('tarriveddesc'),
                               'returns' => $return, 'ticketno' => $this->input->post('tticketno'), 'bookcode' => $this->input->post('tbook'), 'airline' => $this->input->post('cairline'), 'vendor' => setnull($this->input->post('cvendor')),
                               'tax' => $tax, 'discount' => $this->input->post('tdiscount'), 'country' => $country,
                               'hpp' => $this->input->post('tcapital'), 'price' => $this->input->post('tprice'), 'amount' => floatval($amt+$tax));
//
                $this->sitem->add($complain);
                $this->update_trans($sid);
                echo "true|Complain Transaction data successfully saved!|";
                
                $this->db->trans_complete();
                // end transaction
            }
            else{ echo "error|".validation_errors(); }  
        }
       }else { echo "error|Sorry, you do not have the right to edit $this->title component..!"; }
    }
    
    
    function update_item_process()
    { 
       if ($this->acl->otentikasi2($this->title,'ajax') == TRUE){ 
       
         // Form validation
        $this->form_validation->set_rules('tpassenger', 'Passenger', 'required');
        $this->form_validation->set_rules('tidcard', 'ID Card', 'required');
        $this->form_validation->set_rules('cdepart', 'Depart', 'required|callback_valid_depart');
        $this->form_validation->set_rules('tdepartdesc', 'Depart Description', '');
        $this->form_validation->set_rules('carrived', 'Arrived', 'required');
        $this->form_validation->set_rules('tarriveddesc', 'Arrived Description', '');
        $this->form_validation->set_rules('tdepartdates', 'Depart Dates', 'required');
        $this->form_validation->set_rules('tarrivedates', 'Arrived Dates', 'callback_valid_return');
        $this->form_validation->set_rules('cairline', 'Airline', 'required');
        $this->form_validation->set_rules('tbook', 'Book Code', 'required');
        $this->form_validation->set_rules('tticketno', 'Ticket No', 'required');
        $this->form_validation->set_rules('tcapital', 'Capital Price', 'required|numeric');
        $this->form_validation->set_rules('tprice', 'Price', 'required|numeric');
        $this->form_validation->set_rules('tdiscount', 'Discount', 'required|numeric');
        $this->form_validation->set_rules('ctax', 'Tax Type', 'required');

            if ($this->form_validation->run($this) == TRUE && $this->valid_confirm($this->input->post('tsid')) == TRUE)
            {
                // start transaction 
                $this->db->trans_start(); 
                
                $amt = floatval($this->input->post('tprice')-$this->input->post('tdiscount'));
                $tax = floatval($this->input->post('ctax')*$amt);
                $id = $this->sitem->counter();
                
                if ($this->airport->get_country($this->input->post('cdepart')) == $this->airport->get_country($this->input->post('carrived'))){
                    if ($this->airport->get_country($this->input->post('cdepart')) == 'id'){
                        $country = 'id';
                    }else{ $country = 'int'; }
                }else{ $country = 'int'; }
                if ($this->input->post('ckreturn') == 0){ $return = 'FALSE'; }else{ $return = 'TRUE'; }
                
                $complain = array('passenger' => $this->input->post('tpassenger'), 'idcard' => $this->input->post('tidcard'),
                               'source' => $this->input->post('cdepart'), 'dates' => $this->input->post('tdepartdates'), 'source_desc' => $this->input->post('tdepartdesc'),
                               'destination' => $this->input->post('carrived'), 'return_dates' => setnull($this->input->post('tarrivedates')), 'destination_desc' => $this->input->post('tarriveddesc'),
                               'returns' => $return, 'ticketno' => $this->input->post('tticketno'), 'bookcode' => $this->input->post('tbook'), 'airline' => $this->input->post('cairline'), 'vendor' => setnull($this->input->post('cvendor')),
                               'tax' => $tax, 'discount' => $this->input->post('tdiscount'), 'country' => $country,
                               'hpp' => $this->input->post('tcapital'), 'price' => $this->input->post('tprice'), 'amount' => floatval($amt+$tax));

                $this->sitem->update_id($this->input->post('tid'), $complain);
                $this->update_trans($this->input->post('tsid'));
                echo "true|Complain Transaction data successfully saved!|";
                
                $this->db->trans_complete();
                // end transaction
            }
            else{ echo "error|".validation_errors(); }  
        
       }else { echo "error|Sorry, you do not have the right to edit $this->title component..!"; }
    }
    
    private function update_trans($sid)
    {
        $totals = $this->sitem->total($sid);
        $price = $totals['price'];
        
        $complain = $this->Complain_model->get_by_id($sid)->row();
        $cost = $complain->cost;
        
        // total        
        $transaction = array('tax' => $totals['tax'], 'total' => $price, 'discount' => $totals['discount'], 
                             'amount' => intval($totals['amount']+$cost), 'cost' => $cost);
	$this->Complain_model->update($sid, $transaction);
    }
    
    private function split_array($val)
    { return implode(",",$val); }
   
    function shipping($sid=0)
    { 
       if ($this->acl->otentikasi2($this->title,'ajax') == TRUE){ 
       if ($sid == 0){ echo 'error|Complain ID not saved'; }
       else {
       
        $complain = $this->Complain_model->get_by_id($sid)->row();
           
         // Form validation
        $this->form_validation->set_rules('ccity', 'City', 'required');
        $this->form_validation->set_rules('tshipaddkurir', 'Shipping Address', 'required');
        $this->form_validation->set_rules('ccourier', 'Courier Service', 'required');
        $this->form_validation->set_rules('cpackage', 'Package Type', '');
        $this->form_validation->set_rules('tweight', 'Weight', 'required|numeric');

            if ($this->form_validation->run($this) == TRUE && $this->valid_confirm($sid) == TRUE)
            {
                $city = explode('|', $this->input->post('ccity'));
                $package = explode('|', $this->input->post('cpackage'));
                $param = array('complain_id' => $sid, 'shipdate' => null,
                               'courier' => $this->input->post('ccourier'), 'dest' => $city[1], 'dest_id' => $city[0],
                               'dest_desc' => $this->input->post('tshipaddkurir'), 'package' => $package[0],
                               'weight' => $this->input->post('tweight'), 'rate' => $this->input->post('rate'),
                               'amount' => intval($this->input->post('rate')*$this->input->post('tweight')));
                
                $this->shipping->create($sid, $param);
                $this->update_trans($sid);
                echo "true|Shipping Transaction data successfully saved!|";
            }
            else{ echo "error|".validation_errors(); }
        }
       }else { echo "error|Sorry, you do not have the right to edit $this->title component..!"; }
    }
    
    function update_item($uid)
    {
        $acc = $this->sitem->get_by_id($uid)->row();
        echo $acc->id.'|'.$acc->complain_id.'|'.$acc->passenger.'|'.$acc->idcard.'|'.$acc->source.'|'.$acc->dates.'|'.$acc->source_desc.'|'.$acc->destination.'|'.$acc->destination_desc.'|'.$acc->return_dates.'|'.$acc->ticketno.'|'.
             $acc->bookcode.'|'.$acc->airline.'|'.$acc->vendor.'|'.$acc->price.'|'.$acc->amount.'|'.$acc->hpp.'|'.$acc->discount.'|'.$acc->tax.'|'.$acc->returns;
    }
    
    // Fungsi update untuk menset texfield dengan nilai dari database
    function update($param=0)
    {
        $this->acl->otentikasi2($this->title);
        
        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = 'Update '.$this->modul['title'];
        $data['main_view'] = 'complain_form';
        $data['form_action'] = site_url($this->title.'/update_process/'.$param); 
        $data['form_action_trans'] = site_url($this->title.'/add_item/'.$param); 
        $data['form_action_shipping'] = site_url($this->title.'/shipping/'.$param); 
        $data['counter'] = $param; 
	
        $data['link'] = array('link_back' => anchor($this->title,'Back', array('class' => 'btn btn-danger')));
        
        $complain = $this->Complain_model->get_by_id($param)->row();
        $customer = $this->customer->get_details($complain->cust_id)->row();
        
        $data['customer'] = $this->customer->combo();
        $data['vendor'] = $this->vendor->combo();
        $data['passenger'] = $this->complain->combo_passenger();
        $data['account'] = $this->account->combo_asset();
        $data['airport'] = $this->airport->combo();
        $data['airline'] = $this->airline->combo();
        $data['tax'] = $this->tax->combo();
        $data['payment'] = $this->payment->combo();
        $data['source'] = site_url($this->title.'/getdatatable');
        $data['graph'] = site_url()."/complain/chart/";
        $data['city'] = $this->city->combo_city_combine();
        $data['default']['dates'] = date("Y/m/d");
        $data['code'] = $complain->code;
        
        $data['default']['customer'] = $complain->cust_id;
        $data['default']['email'] = $customer->email;
        $data['default']['ship_address'] = $customer->shipping_address;
        $data['default']['dates'] = $complain->dates;
        $data['default']['due_date'] = $complain->due_date;
        $data['default']['payment'] = $complain->payment_id;
        $data['default']['account'] = $complain->account;
        $data['default']['costs'] = $complain->cost;
        $data['default']['tax'] = $complain->tax;
        $data['default']['discount'] = $complain->discount;
        $data['default']['total'] = $complain->total;
        $data['default']['tot_amt'] = floatval($complain->amount);
        
        // transaction table
        $data['items'] = $this->sitem->get_last_item($param)->result();
        $this->load->view('template', $data);
    }
    
   function invoice($sid=null,$type=null)
   {
       $this->acl->otentikasi2($this->title);

       $data['h2title'] = 'Print Tax Invoice'.$this->modul['title'];
       
       if ($type == 'code'){ $complain = $this->Complain_model->get_by_code($sid)->row();
       }else{ $complain = $this->Complain_model->get_by_id($sid)->row(); }
       
       // customer
       $customer = explode('|', $this->request($complain->cust_id));
       $data['customer'] = $complain->cust_id;
       $data['custname'] = $customer[1];
       $data['custno'] = $customer[0];
       $data['custaddress'] = $customer[3].' - '.$customer[4];

       //complain
       $data['pono'] = 'SO-'.$sid;
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
    
    function receivable_process()
    {
        $this->acl->otentikasi2($this->title);
        $data['title'] = $this->properti['name'].' | Report '.ucwords($this->modul['title']);

        $data['rundate'] = tglin(date('Y-m-d'));
        $data['log'] = $this->session->userdata('log');
        $period = $this->input->post('reservation');  
        $start = picker_between_split($period, 0);
        $end = picker_between_split($period, 1);

        $data['start'] = tglin($start);
        $data['end'] = tglin($end);
        
        $cust = $this->input->post('ccustomer');
        $trans = $this->input->post('ctrans');

        $data['currency'] = 'IDR';
        $data['start'] = tglin($start);
        $data['end'] = tglin($end);

        $data['rundate'] = tgleng(date('Y-m-d'));
        $data['log'] = $this->session->userdata('log');
        
        // Property Details
        $data['company'] = $this->properti['name'];
        
        $data['customer'] = $this->customer->get_name($cust);
        $data['open'] = $this->trans->get_sum_transaction_open_balance(null, 'IDR', $start, $cust, 'AR', $trans);
        $data['trans'] = $this->trans->get_transaction(null, 'IDR', $start, $end, $cust, 'AR', $trans)->result();
        
        $this->load->view('receivable_card', $data);
    }

}

?>