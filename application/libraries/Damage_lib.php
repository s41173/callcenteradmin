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
        $val = $this->db->get($this->tableName);
        
        if ($val->num_rows() > 0){
            $result = $val->result();
            foreach($result as $row){ $data['options'][$row->id] = 'DM-0'.$row->id.' : '.ucfirst($row->description); }
        }else{ $data['options'][0] = '--'; }
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
    
    function get_address($id=null)
    {
        if ($id)
        {
            $this->db->select($this->field);
            $this->db->where('id', $id);
            $val = $this->db->get($this->tableName)->row();
            if ($val){ return $val->address; }
        }
        else { return ''; }
    }
    
    function get_dates($id=null)
    {
        if ($id)
        {
            $this->db->select($this->field);
            $this->db->where('id', $id);
            $val = $this->db->get($this->tableName)->row();
            if ($val){ return tglincompletetime($val->dates); }
        }
        else { return ''; }
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
    
    function get_interval($id=0,$type=0){
        
        $this->db->select($this->field);
        $this->db->where('id', $id);
        $val = $this->db->get($this->tableName)->row();
        
        $datetime1 =  new DateTime($val->dates);
        $datetime2 =  new DateTime($val->due);

        $interval = $datetime1->diff($datetime2);
        
        $days = intval($interval->format('%a'));
        $hour = $interval->format('%h');
        $minutes = $interval->format('%i');
        $res1 = null;
        
        if ($days >= 2){ $res1 = '> 48 jam'; }
        elseif ($days >= 1){ $res1 = '> 24 jam'; }
        elseif ($days == 0 && $hour > 12){ $res1 = '> 12 jam';}
        elseif ($days == 0 && $hour < 12){ $res1 = '< 12 jam';}
        $res2 = $days.'hari - '.$hour.'jam - '.$minutes.' menit';
        if ($val->status == 1){ if ($type == 0){ return $res1; }else{ return $res2; } }
    }

}

/* End of file Property.php */