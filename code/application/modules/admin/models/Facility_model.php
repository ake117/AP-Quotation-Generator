<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Facility_model extends MY_Model
{
    public $table_name = 'facility';
    public $primary_key = 'facility_id';

    public $field_available = array(
        'facility_name' => array('multi_lang'=>true, 'type'=>'text','validate'=>true,'validate_text'=>'Facility name','validate_type'=>'required')
        ,'facility_detail' => array('multi_lang'=>true, 'type'=>'text','validate'=>false,'validate_text'=>'Description','validate_type'=>'required')
        ,'project_id' => array('multi_lang'=>false, 'type'=>'select', 'validate'=>true, 'validate_text'=>'Project', 'validate_type'=>'required')
        ,'facility_image' => array('multi_lang'=>true, 'type'=>'file', 'validate'=>true,'validate_text'=>'Facility image','validate_type'=>'required')
        ,'seq' => array('multi_lang'=>false, 'type'=>'text', 'validate'=>true, 'validate_text'=>'Order','validate_type'=>'required')
        ,'is_active' => array('multi_lang'=>false, 'type'=>'checkbox', 'validate'=>false, 'validate_text'=>'','validate_type'=>'required')
        );

    public function get_all($select=null, $order=array('field' => 'facility_name_th', 'order' => 'asc')) {
        return $this->get_by_field(array('is_delete'=>'N'), null , $order, $select)->result_array();
    }

    public function get_all_assoc() {
        $rs = $this->get_all('facility_id, facility_name_th', array('field'=>'seq', 'order'=>'asc'));
        foreach ($rs as $key => $row) {
            $a_facility[$row[$this->primary_key]] = $row['facility_name_th'];
        }
        return $a_facility;
    }

    public function get_facility_project(){
        return $this->db->query("select f.*, p.project_name_th
                                 from facility f join projects p on f.project_id = p.project_id
                                    and p.is_delete = 'N' and f.is_delete = 'N' order by project_name_th, facility_name_th ")->result_array();
    }

}