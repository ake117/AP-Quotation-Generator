<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Agents_model extends MY_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_agent($agent_id) {
        $this->db->select('agent_id,password,agent_status,prefix,name,surname,email,phone');
        $this->db->from('agents');
        //$this->db->where('email', $email);
        $this->db->where('is_active', 'Y');
        $this->db->where('is_delete', 'N');
        $this->db->where('agent_status', 'active');
        $this->db->where('agent_id', $agent_id);
        $res = $this->db->get();
        return $res;
    }

    public function get_project_agent() {
        $this->db->select("project_id,seq,project_code, project_name_th as project_name");
        $this->db->from('projects_agent');        
        $this->db->where('is_active', 'Y');
        $this->db->where('is_delete', 'N');        
        $res = $this->db->get()->result_array();
                
        
        $result = array();
        foreach ($res as $row)
        {
            $result[$row['project_id']] = $row['project_code']." : ".$row['project_name'];                
        }
        
        return $result;
    }
    
    // Fetch records
    public function get_history($agent_id, $keyword='', $page=1, $limit=5) {                
        
        if ($this->session->has_userdata('agent')) {
            
            $this->db->select('*,DATE_FORMAT(create_date,"%d-%m-%y") as create_date_format');
            $this->db->from('agents_payment');        
            $this->db->where('is_active', 'Y');
            $this->db->where('is_delete', 'N');
            $this->db->where('payment_status', 'S');
            $this->db->where('agent_id', $agent_id);            
            if ($keyword!= '') {
                $where = "((name like '%{$keyword}%') or (surname like '%{$keyword}%') or (phone like '%{$keyword}%') or (email like '%{$keyword}%') or (project_unit like '%{$keyword}%') or (address_no like '%{$keyword}%') or (subdistrict like '%{$keyword}%') or (district like '%{$keyword}%') or (province like '%{$keyword}%') or (postcode like '%{$keyword}%') or (country like '%{$keyword}%') or (DATE_FORMAT(create_date,'%d-%m-%y') like '%{$keyword}%')) ";
                $this->db->where($where);
            }            
            $this->db->order_by('create_date', 'DESC');
            $this->db->limit($limit, ($page-1)*$limit);
            $result = $this->db->get();
            return $result->result_array();
        } else {
            return array();
        }                        
    }
    
    // Select total records
    public function get_historyCount($agent_id, $keyword='') {

        $this->db->select('count(*) as allcount');
        $this->db->from('agents_payment');
        $this->db->where('is_active', 'Y');
        $this->db->where('is_delete', 'N');
        $this->db->where('payment_status', 'S');
        $this->db->where('agent_id', $agent_id);
        if ($keyword!= '') {
            $where = "((name like '%{$keyword}%') or (surname like '%{$keyword}%') or (phone like '%{$keyword}%') or (email like '%{$keyword}%') or (project_unit like '%{$keyword}%') or (address_no like '%{$keyword}%') or (subdistrict like '%{$keyword}%') or (district like '%{$keyword}%') or (province like '%{$keyword}%') or (postcode like '%{$keyword}%') or (country like '%{$keyword}%') or (DATE_FORMAT(create_date,'%d-%m-%y') like '%{$keyword}%')) ";
            $this->db->where($where);
        }
        $this->db->order_by('create_date', 'DESC');
        $query = $this->db->get();
        $result = $query->result_array();

        return $result[0]['allcount'];
    }

}