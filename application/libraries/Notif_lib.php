<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Notif_lib extends Custom_Model {

    public function __construct($deleted=NULL)
    {
        $this->deleted = $deleted;
        $this->tableName = 'complain';
        $this->logs = new Log_lib();
        $this->sms = new Sms_lib();
        $this->customer = new Customer_login_lib();
        $this->push = new Push_lib();
        $this->complain = new Complain_lib();
    }

    private $ci,$email,$sms,$customer,$push,$complain;
    protected $field = array('id', 'customer', 'subject', 'content', 'type', 'reading', 'modul', 'status', 'publish', 'created', 'deleted');
    
    /*
        0 = email
        1= sms
        2 = email + sms
        3 = notif socket
        4 = socket + SMS
        5 = email + notif socket
        6 = email + sms+ notif socket
    */
    
    function get_type($val=0){
        
        $res = null;
        switch ($val) {
            case 0: $res = 'Email'; break;
            case 1: $res = 'SMS'; break;
            case 2: $res = 'Email + SMS'; break;
            case 3: $res = 'Socket'; break;
            case 4: $res = 'Socket + SMS'; break;
            case 5: $res = 'Email + Socket'; break;
            case 6: $res = 'Email + SMS + Socket'; break;
        }
        return $res;
    }
    
    function send($ticket,$content='test content'){
       
        $val = $this->complain->get_by_ticket($ticket)->row();
        $res = false;
        
        if ($val->type == 0){
          $res1 = $this->push->send_device($this->customer->get_device($val->cust_id), $content);
          $res2 = $this->sms->send($val->phone, $content);  
          if ($res1 == true && $res2 == true){ $res = true; }
        }elseif ($val->type == 1){
          $res = $this->sms->send($val->phone, $val->content);  
        }
        return $res;
    }

}

/* End of file Property.php */
