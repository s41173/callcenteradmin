<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Complain_lib extends Main_Model {

    public function __construct($deleted=NULL)
    {
        $this->deleted = $deleted;
        $this->tableName = 'complain';
    }
    
    protected $field = array('id', 'ticketno', 'dates', 'cust_id', 'category', 'damage', 'description', 'status', 'log', 'created', 'updated', 'deleted');
    
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

}

/* End of file Property.php */