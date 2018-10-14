<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Damage_lib extends Main_Model {

    public function __construct($deleted=NULL)
    {
        $this->deleted = $deleted;
        $this->tableName = 'damage';
    }
    
    protected $field = array('id', 'dates', 'due', 'category', 'description', 'address', 'coordinate', 'status', 'approved', 'staff', 'log', 'created', 'updated', 'deleted');
    
    function get()
    {
        $this->db->select($this->field);
        $this->db->where('deleted', NULL);
        $this->db->where('publish',1);
        return $this->db->get($this->tableName)->result();
    }
    
    function combo()
    {
        $this->db->select($this->field);
        $this->db->where('deleted', NULL);
        $this->db->where('approved',1);
        $this->db->where('status',0);
//        $this->db->where('parent_id >',0);
        $this->db->order_by('name', 'asc');
        $val = $this->db->get($this->tableName)->result();
        $data['options'][0] = 'Top';
        foreach($val as $row){ $data['options'][$row->id] = ucfirst($row->description); }
        return $data;
    }

    function combo_id()
    {
        $this->db->select($this->field);
        $this->db->where('deleted', NULL);
        $this->db->where('approved',1);
        $this->db->where('status',0);
//        $this->db->where('parent_id >',0);
        $val = $this->db->get($this->tableName)->result();
        $data['options'][0] = 'Top';
        foreach($val as $row){ $data['options'][$row->id] = 'DM-0'.$row->id.' : '.ucfirst($row->description); }
        return $data;
    }
    
    function combo_category($category)
    {
        $data = null;
        $this->db->select($this->field);
        $this->db->where('deleted', NULL);
//        $this->db->where('approved',1);
        $this->db->where('status',0);
        $this->db->where('category',$category);
        $val = $this->db->get($this->tableName)->result();
        if ($val){
          foreach($val as $row){ $data['options'][$row->id] = 'DM-0'.$row->id.' : '.ucfirst($row->description); }    
        }else{ $data['options'][0] = '--'; }
        return $data;
    }

    function get_name($id=null)
    {
        if ($id)
        {
            $this->db->select($this->field);
            $this->db->where('id', $id);
            $val = $this->db->get($this->tableName)->row();
            if ($val){ return $val->description; }
        }
        else if($id == 0){ return 'Top'; }
        else { return ''; }
    }
    
    function get_id($id=null)
    {
        if ($id)
        {
            $this->db->select($this->field);
            $this->db->where('name', $id);
            $val = $this->db->get($this->tableName)->row();
            if ($val){ return $val->id; }else { return 0; }
        }
        else { return 0; }
    }
    
    function get_status($id){
        $this->db->select($this->field);
        $this->db->where('id', $id);
        $val = $this->db->get($this->tableName)->row();
        if ($val){
          if ($val->status == 1){ return 'Completed'; }else { return 'Progress'; }    
        }
    }
    
    function valid_damage($id=null)
    {
        $this->db->select($this->field);
        $this->db->where('id', $id);
        $val = $this->db->get($this->tableName)->row();
        if ($val->status == 1){ return FALSE; }else { return TRUE; }
    }

}

/* End of file Property.php */