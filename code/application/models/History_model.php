<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class History_model extends MY_Model {

    public function __construct() {
        parent::__construct();
    }    
    
    public function get_booking($member_id) { 
        $lang = $this->lang->line('lang');
        
        $res = $this->db->query("SELECT b.booking_id,r.room_id,f.floor_name_{$lang} as floor_name,f.floor_detail_{$lang} as floor_detail,rt.roomtype_name_{$lang} as roomtype_name,
rt.roomtype_detail_{$lang} as roomtype_detail,p.project_name_{$lang} as project_name,r.room_name_{$lang} as room_name,r.room_detail_{$lang} as room_detail,
r.room_size,b.create_date as booking_date,r.room_price,r.booking_price
FROM booking AS b
INNER JOIN rooms AS r ON b.room_id = r.room_id
INNER JOIN projects AS p ON r.project_id = p.project_id
INNER JOIN floor AS f ON r.floor_id = f.floor_id
INNER JOIN roomtype AS rt ON r.roomtype_id = rt.roomtype_id
where b.booking_status='S' and b.is_active='Y' and b.is_delete='N'and b.member_id = {$member_id} ");        
        //$res = $this->db->get();       
        $res = $res->result_array();
        
        foreach ($res as $key => $value) {
            $res[$key]['booking_date'] = date_format(date_create($value['booking_date']) ,"d-m-Y");;
            $res[$key]['room_price'] = number_format($value['room_price']);
            $res[$key]['booking_price'] = number_format($value['booking_price']);
        }
        
        //print_r($res); die();       
        return $res; 
    }
    
    

    
    

}