<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Projects_model extends MY_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_projects() {   
        $lang = $this->lang->line('lang');
        $this->db->select("project_id,seq, project_name_{$lang} as project_name, project_logo_{$lang} as project_logo, project_image_{$lang} as project_image, project_detail_short_{$lang} as project_detail_short, project_360");
        $this->db->from('projects');
        $this->db->where('is_active', 'Y');
        $this->db->where('is_delete', 'N');
        $res = $this->db->get()->result_array();  
        
        foreach ($res as $key => $value) {            
            $res[$key]["project_name_url"] = url_title($value["project_name"]);
        }
        
        //print_r($res); 
        //print_r($res[0]['project_name_url']); 
        //die();
        
        return $res;   
    }   

}

