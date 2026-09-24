<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Password_model extends MY_Model {

    public function __construct() {
        parent::__construct();
    }    
    
    public function get_member($id) {        
        $this->db->select('member_id,email,password');
        $this->db->from('members');
        $this->db->where('member_id', $id);
        $res = $this->db->get();       
        $res = $res->result_array();
        //print_r($sql); die();       
        return (count($res)>0)?$res[0]:array();; 
    }
    
    public function get_memberbyemail($email) {      
        $this->db->select('member_id,email,name,surname');
        $this->db->from('members');
        $this->db->where('email', $email);
        $this->db->where('is_active', 'Y');
        $this->db->where('is_delete', 'N');
        $res = $this->db->get();       
        $res = $res->result_array();
        //print_r($sql); die();       
        return $res[0]; 
    }
    
    public function hasemail($email) {        
        $this->db->select('email');
        $this->db->from('members');
        $this->db->where('email', $email);
        $res = $this->db->get();       
        //$res = $res->result_array();
        //print_r($sql); die();               
        return ($res->num_rows()>0)?true:false;      
    }
    
    public function set_resettoken($id) {        
        
        $token = password_hash($id, PASSWORD_BCRYPT);
        $data = array(                    
            'reset_password_token' => $token            
        );      
        $this->db->set('reset_password_validity', 'DATE_ADD(NOW(), INTERVAL 30 MINUTE)', FALSE);
        $this->db->where('member_id', $id);
        $this->db->update('members', $data);
        
        //print_r($sql); die();       
        return $token; 
    }
    
    
    public function valid_resettoken($token) {        
                             
        $this->db->from('members');
        $this->db->where('reset_password_token', $token);
        $this->db->where('reset_password_validity > ', 'NOW()', FALSE);
        $this->db->where('member_status', 'active');
        $this->db->where('is_active', 'Y');
        $this->db->where('is_delete', 'N'); 
        $res = $this->db->get();
        $res = $res->result_array();
        
        //print_r($res); die();       
        return (count($res)>0)?$res[0]:array();
    }
    
    public function changepassword($sign_member_id,$sign_password) {
        $data = array(                    
            'password' => password_hash($sign_password, PASSWORD_BCRYPT),
            'reset_password_token' => null,
            'reset_password_validity' => null
        ); 
                
        $this->db->where('member_id', $sign_member_id);
        $this->db->update('members', $data);
        return true;
    }
    

}