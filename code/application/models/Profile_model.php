<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile_model extends MY_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_profile($member_id) {   
        
        $sql = <<<SQL
        SELECT m.*,p.PROVINCE_ID as province_id,a.AMPHUR_ID as amphur_id,t.tumbon_code,dp.PROVINCE_ID as doc_province_id,da.AMPHUR_ID as doc_amphur_id,dt.tumbon_code as doc_tumbon_code
        FROM members AS m
        LEFT JOIN province AS p ON m.province = trim(p.PROVINCE_NAME) COLLATE utf8_general_ci
        LEFT JOIN amphur AS a ON m.district = trim(a.AMPHUR_NAME) COLLATE utf8_general_ci
        LEFT JOIN tumbon AS t ON m.subdistrict = trim(t.tumbon_name) COLLATE utf8_general_ci
        LEFT JOIN province AS dp ON m.doc_province = trim(dp.PROVINCE_NAME) COLLATE utf8_general_ci
        LEFT JOIN amphur AS da ON m.doc_district = trim(da.AMPHUR_NAME) COLLATE utf8_general_ci
        LEFT JOIN tumbon AS dt ON m.doc_subdistrict = trim(dt.tumbon_name) COLLATE utf8_general_ci
        WHERE m.member_id = {$member_id}
SQL;

        $query = $this->db->query($sql);
        $res = $query->result_array();
        //print_r($sql); die();       
        return $res[0];   
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

}

