<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Api_lib {

    public function __construct(){
 
    }

    private $ci;
    
    // ==================================== API ==============================
    
    function request($url=null,$param=null)
    {   
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $param,
        CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json'
        ),
      ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
//        $data = json_decode($response, true); 

        curl_close($curl);
        if ($err) { return $err; }
        else { return $response; }
    }
    
}

/* End of file Property.php */