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
    
    protected $field = array('id', 'type', 'name', 'phone', 'district', 'ticketno', 'dates', 'cust_id', 'damage', 'category', 'description', 'status', 'log',
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
    
    function search($ticket=null,$customer=null,$category=null,$phone=null)
    {   
        $this->db->select($this->field);
        $this->db->from($this->tableName); 
        $this->db->where('deleted', $this->deleted);
        $this->cek_null_string($customer, 'name');
        $this->cek_null_string($category, 'DATE(dates)');
        
        $this->cek_null_string($ticket, 'ticketno');
        $this->cek_null_string($phone, 'phone');
        
        $this->db->order_by('dates', 'desc'); 
        return $this->db->get(); 
    }
    
    function search_json($customer=null,$limit=0,$offset=0)
    {   
        $this->db->select($this->field);
        $this->db->from($this->tableName); 
        $this->db->where('deleted', $this->deleted);
        $this->cek_null($customer, 'cust_id');
        $this->db->order_by('dates', 'desc'); 
        $this->db->limit($limit, $offset);
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
    
    function valid_ticket($custid)
    {
       $this->db->where('cust_id', $custid);
       $this->db->where('status', 0);
       $this->db->where('deleted', $this->deleted);
       $query = $this->db->get($this->tableName)->num_rows();
       if ($query > 0){ return FALSE; }else{ return TRUE; }
    }

}

?>