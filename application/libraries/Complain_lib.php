<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Complain_lib extends Custom_Model {

    public function __construct($deleted=NULL)
    {
        $this->deleted = $deleted;
        $this->tableName = 'complain';
        $this->api = new Api_lib();
    }
    
    private $api;
    protected $field = array('id', 'type', 'name', 'phone', 'district', 'ticketno', 'dates', 'cust_id', 'category', 'damage', 'description', 'status', 'log', 'created', 'updated', 'deleted');
    
    function get()
    {
        $this->db->select($this->field);
        $this->db->where('deleted', NULL);
        $this->db->where('publish',1);
        return $this->db->get($this->tableName)->result();
    }

    function total_complain($damage){
        
        $this->db->select($this->field);
        $this->db->where('damage', $damage);
        $this->db->where('deleted', NULL);
        $val = $this->db->get($this->tableName)->num_rows();
        return intval($val);
    }
    
    function get_based_damage($damage,$limit=1){
        
        $this->db->select($this->field);
        $this->db->where('damage', $damage);
        $this->db->where('deleted', NULL);
        $this->db->limit($limit);
        $val = $this->db->get($this->tableName);
        if ($val->num_rows() > 0){ return $val->row(); }
    }
    
    function counter_field($custtype=null,$period=null,$stts=null){
        $this->db->select($this->field);
        $this->db->where('deleted', NULL);
        if ($period){
          $this->db->where('YEAR(dates)', date('Y'));
          $this->db->where('MONTH(dates)', date('m'));
        }
        $this->cek_null($custtype, 'type');
        $this->cek_null($stts, 'status');
        
        return $this->db->get($this->tableName)->num_rows();
    }
    
    function get_by_ticket($uid)
    {
        $this->db->select($this->field);
        $this->db->where('ticketno', $uid);
        return $this->db->get($this->tableName);
    }

}

/* End of file Property.php */