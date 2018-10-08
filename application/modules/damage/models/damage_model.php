<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Damage_model extends Custom_Model
{
    protected $logs;
    
    function __construct()
    {
        parent::__construct();
        $this->logs = new Log_lib();
        $this->com = new Components();
        $this->com = $this->com->get_id('damage');
        $this->tableName = 'damage';
    }
    
    protected $field = array('id', 'dates', 'due', 'category', 'description', 'address', 'coordinate', 'status', 'approved', 'staff', 'log', 'created', 'updated', 'deleted');
    
    function get_last($limit)
    {
        $this->db->select($this->field);
        $this->db->from($this->tableName);
        $this->db->where('deleted', NULL);
        $this->db->order_by('id', 'asc'); 
        $this->db->limit($limit);
        return $this->db->get(); 
    }
    
    function search($category='null',$status='null')
    {
        $this->db->select($this->field);
        $this->db->from($this->tableName);
        $this->db->where('deleted', NULL);
        $this->db->order_by('id', 'desc'); 
        $this->cek_null_string($category, 'category');
        $this->cek_null_string($status, 'status');
        return $this->db->get(); 
    }
    
    function get_coordinate()
    {
        $this->db->select($this->field);
        $this->db->from($this->tableName);
        $this->db->where('deleted', NULL);
        $this->db->where('approved', 1);
        $this->db->where('status', 0);
        $this->db->order_by('id', 'desc'); 
        return $this->db->get(); 
    }
    
    function report($start,$end){
        
       $this->db->select($this->field);
       $this->db->from($this->tableName);
       $this->db->where('deleted', NULL);
       $this->cek_between($start, $end);
       $this->db->order_by('id', 'desc'); 
       return $this->db->get();   
    }
    
    private function cek_between($start,$end)
    {
        if ($start == null || $end == null ){return null;}
        else { return $this->db->where("dates BETWEEN '".$start."' AND '".$end."'"); }
    }

}

?>