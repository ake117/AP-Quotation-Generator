<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Signup_model extends MY_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_province_name($province_id) {
        $this->db->select('trim(province_name) as name');
        $this->db->from('province');
        $this->db->where('province_id', $province_id);
        $this->db->not_like('province_name', '*');
        $res = $this->db->get(); 
        $res = $res->row();
        return $res->name;        
    }
    public function get_amphur_name($amphur_id) {
        $this->db->select('trim(amphur_name) as name');
        $this->db->from('amphur');
        $this->db->where('amphur_id', $amphur_id);
        $this->db->not_like('amphur_name', '*');
        $res = $this->db->get();
        $res = $res->row();
        return $res->name;
    }
    public function get_tumbon_name($tumbon_code) {
        $this->db->select('trim(tumbon_name) as name');
        $this->db->from('tumbon');
        $this->db->where('tumbon_code', $tumbon_code);
        $this->db->not_like('tumbon_name', '*');
        $res = $this->db->get();
        $res = $res->row();
        return $res->name;
    }
    
    public function get_province() {
        $query = $this->db->query("SELECT province_id, trim(province_name) as province_name from province where geo_id <> 0");
        
        $result = array();
        foreach ($query->result_array() as $row)
        {
            $result[$row['province_id']] = $row['province_name'];                
        }        
        return $result;
    }
    
    public function get_amphur($province_id) {                
        $this->db->select('amphur_id, trim(amphur_name) as amphur_name');
        $this->db->from('amphur');
        $this->db->where('province_id', $province_id);
        $this->db->not_like('amphur_name', '*');
        $res = $this->db->get();        
        return $res;
    }
    
    public function get_tumbon($amphur_id) {                
        $this->db->select('tumbon_code, trim(tumbon_name) as tumbon_name');
        $this->db->from('tumbon');
        $this->db->where('amphur_id', $amphur_id);
        $this->db->not_like('tumbon_name', '*'); 
        $res = $this->db->get();       
        return $res;        
    }
    
    public function get_postcode($tumbon_code) {        
        $this->db->select('zipcode');
        $this->db->from('zipcodes');
        $this->db->where('district_code', $tumbon_code);        
        $res = $this->db->get();       
        return $res;   
    }
    
    public function has_data($field, $data) {        
        $this->db->select($field);
        $this->db->from('members');
        $this->db->where($field, $data["sign_".$field]);        
        $res = $this->db->get();
        return ($res->num_rows()>0)?true:false;               
    }
    
    public function valid_verifytoken($token) {        
                             
        $this->db->from('members');
        $this->db->where('reset_password_token', $token);        
        $this->db->where('member_status', 'verify');
        $this->db->where('is_active', 'Y');
        $this->db->where('is_delete', 'N'); 
        $res = $this->db->get();
        $res = $res->result_array();
        
        //print_r($res); die();       
        return (count($res)>0)?$res[0]:array();
    }
    
    public function verify_success($id) {        
        
        $this->db->set('member_status', 'active');     
        $this->db->set('reset_password_token', null);
        $this->db->set('reset_password_validity', null);
        $this->db->where('member_id', $id);
        $this->db->update('members');
        
        //print_r($sql); die();       
        return true; 
    }
    
    

}