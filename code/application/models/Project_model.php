<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Project_model extends MY_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_project($project_id) {   
        $lang = $this->lang->line('lang');
        $this->db->select("project_id,seq, project_name_{$lang} as project_name, project_logo_{$lang} as project_logo, project_detail_image_{$lang} as project_detail_image, project_detail_{$lang} as project_detail, project_360");
        $this->db->from('projects');
        $this->db->where('project_id', $project_id);
        $this->db->where('is_active', 'Y');
        $this->db->where('is_delete', 'N');
        $res = $this->db->get()->result_array();  
        
        foreach ($res as $key => $value) {            
            $res[$key]["project_name_url"] = url_title($value["project_name"]);
        }
        
        //print_r($res); 
        //print_r($res[0]['project_name_url']); 
        //die();
        
        return isset($res[0])?$res[0]:array();   
    }   
    
    public function get_facility($project_id) {   
        $lang = $this->lang->line('lang');
        $this->db->select("facility_id, seq, facility_name_{$lang} as facility_name, facility_image_{$lang} as facility_image, facility_detail_{$lang} as facility_detail");
        $this->db->from('facility');
        $this->db->where('project_id', $project_id);
        $this->db->where('is_active', 'Y');
        $this->db->where('is_delete', 'N');
        $res = $this->db->get()->result_array();
        return isset($res)?$res:array();  
    }
    
    public function get_roomtype_gallery($project_id) {   
        $lang = $this->lang->line('lang');
        $this->db->select("roomtype_gallery_id, seq, roomtype_gallery_name_{$lang} as roomtype_gallery_name, roomtype_gallery_image_{$lang} as roomtype_gallery_image, roomtype_gallery_detail_{$lang} as roomtype_gallery_detail");
        $this->db->from('roomtype_gallery');
        $this->db->where('project_id', $project_id);
        $this->db->where('is_active', 'Y');
        $this->db->where('is_delete', 'N');
        $res = $this->db->get()->result_array();
        return isset($res)?$res:array();
    }
    
    public function get_gallery($project_id) {   
        $lang = $this->lang->line('lang');
        $this->db->select("gallery_id, seq, gallery_name_{$lang} as gallery_name, gallery_image_{$lang} as gallery_image, gallery_detail_{$lang} as gallery_detail");
        $this->db->from('gallery');
        $this->db->where('project_id', $project_id);
        $this->db->where('is_active', 'Y');
        $this->db->where('is_delete', 'N');
        $res = $this->db->get()->result_array();
        return isset($res)?$res:array();   
    }

}

