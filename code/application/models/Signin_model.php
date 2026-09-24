<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Signin_model extends MY_Model {

    public function __construct() {
        parent::__construct();
    }    
    
    public function get_member($email) {        
        $this->db->select('member_id,password,member_status,language');
        $this->db->from('members');
        $this->db->where('email', $email);
        $this->db->where('is_active', 'Y');
        $this->db->where('is_delete', 'N');
        //$this->db->where('member_status', 'active');
        $res = $this->db->get();       
        return $res;   
    }

}