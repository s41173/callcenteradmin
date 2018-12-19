<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Api extends MX_Controller {

   function __construct()
   {
        parent::__construct();

        $this->properti = $this->property->get();
        $this->complain = new Complain_lib();
        $this->apilib = new Api_lib();
        $this->login = new Customer_login_lib();
        
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token'); 

        // Your own constructor code
   }

   private $log,$login,$complain,$apilib;
   private $properti;

   function index()
   {
       redirect('welcome');
   }
   
    function login(){
        
        $datas = (array)json_decode(file_get_contents('php://input'));
        $user = $datas['custid'];
        
        $status = 200;
        $error = null;
        $logid = null;
        $name = null;
        $noinstalasi = null;
        
        if ($user != null){
            
            if ($this->apilib->request(site_url('complain/cek_user/'.$user))){
               
                $result = explode('|', $this->apilib->request(site_url('complain/cek_user/'.$user)));
                $name = $result[1];
                $noinstalasi = $result[0];
                $logid = mt_rand(1000,9999);
                $this->login->add($user, $logid, $datas['device']);    
                
            }else{ $status = 401; $error = 'No ID Pelanggan Tidak Terdaftar'; }
        }else{ $status = 201; $error = "Wrong format..!!"; }
        
        $response = array('error' => $error, 'user' => $name, 'userid' => $user, 'noinstalasi' => $noinstalasi, 'log' => $logid); 
        $this->apilib->response($response, $status);
    }   
    
    function otentikasi(){
        
        $datas = (array)json_decode(file_get_contents('php://input'));
        $user = $datas['custid'];
        $log = $datas['log'];
        
        $status = 200;
        $error = null;
        
        if ($user != null && $log != null){
            if ($this->login->valid($user, $log) != TRUE){ $status = 401; $error = "Invalid Credential"; }
        }else{ $status = 201; $error = "Wrong format..!!"; }
        
        $response = array('error' => $error); 
        $this->apilib->response($response, $status);
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
