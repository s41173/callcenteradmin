<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Complain_model extends Custom_Model
{
    protected $logs;
    
    function __construct()
    {
        parent::__construct();
        $this->logs = new Log_lib();
        $this->com = new Components();
        $this->com = $this->com->get_id('complain');
        $this->tableName = 'complain';
    }
    
    protected $field = array('id', 'ticketno', 'dates', 'cust_id', 'damage', 'category', 'description', 'status', 'log',
                             'created', 'updated', 'deleted');
    protected $com;
    
    function get_last($limit, $offset=null)
    {
        $this->db->select($this->field);
        $this->db->from($this->tableName); 
        $this->db->where('deleted', $this->deleted);
        $this->db->order_by('id', 'desc'); 
        $this->db->limit($limit, $offset);
        return $this->db->get(); 
    }
    
    function search($ticket=null,$customer=null,$category=null)
    {   
        $this->db->select($this->field);
        $this->db->from($this->tableName); 
        $this->db->where('deleted', $this->deleted);
        $this->cek_null_string($customer, 'cust_id');
        $this->cek_null_string($category, 'damage');
        $this->cek_null_string($ticket, 'ticketno');
        
        $this->db->order_by('dates', 'desc'); 
        return $this->db->get(); 
    }
    
        
    function get_by_code($uid)
    {
        $this->db->select($this->field);
        $this->db->where('ticketno', $uid);
        return $this->db->get($this->tableName);
    }
    
    function report($start=null,$end=null)
    {   
        $this->db->select($this->field);
        $this->db->from($this->tableName); 
        $this->db->where('deleted', $this->deleted);
        $this->between('dates', $start, $end);
        $this->db->order_by('dates', 'desc'); 
        return $this->db->get(); 
    }
    
    function counter($type=0)
    {
       $this->db->select_max('id');
       $query = $this->db->get($this->tableName)->row_array(); 
       if ($type == 0){ return intval($query['id']+1); }else { return intval($query['id']); }
    }
    
    function valid_confirm($sid)
    {
       $this->db->where('id', $sid);
       $query = $this->db->get($this->tableName)->row();
       if ($query->paid_date != NULL){ return FALSE; }else{ return TRUE; }
    }

}

?>