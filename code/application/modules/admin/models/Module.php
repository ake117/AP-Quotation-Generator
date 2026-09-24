<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Module extends MY_Model
{

    public $table_name = 'modules';
    public $primary_key = 'id';

    /**
     * This method removes all modules actions based on $module_id variable.
     *
     * @param $module_id int
     * @return mixed
     */
    public function delete_actions($module_id)
    {
    	$this->db->where('module_id', $module_id);
        $this->db->delete('modules_actions');
        return $this->db->affected_rows();
    }

}
