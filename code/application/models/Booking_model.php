<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Booking_model extends MY_Model {

    public function __construct() {
        parent::__construct();
        $this->lang = ($this->session->userdata('site_lang') == 'thai')?'th':'en'; 
        //$this->load->config('grande', true);
        //$this->grande =  $this->config->item('grande');
        $this->path = $this->config->item('path');
    }

    public function get_room_in_project($project_id) {
        $this->update_room_padding();
        $lang = $this->lang;
        $user = $this->session->userdata('user');
        $user_language = (!empty($user['language']))?$user['language']:$lang;
        $query = $this->db->query("SELECT
r.room_id,
r.floor_id,
r.project_id,
r.roomtype_id,
r.room_code,
r.room_name_en,
r.room_name_$lang AS room_name,
r.room_detail_$lang AS room_detail,
r.room_plan_image_$lang AS room_plan_image,
r.room_floor_plan_image_$lang AS room_floor_plan_image,
r.booking_price_$user_language AS booking_price,
r.booking_status,
r.booking_date,
p.project_name_$lang AS project_name,
rt.roomtype_name_$lang AS roomtype_name,
rt.roomtype_image_$lang AS roomtype_image,
f.floor_name_$lang AS floor_name,
f.floor_detail_$lang AS floor_detail
FROM
roomtype AS rt
LEFT JOIN rooms AS r ON rt.roomtype_id = r.roomtype_id
RIGHT JOIN projects AS p ON r.project_id = p.project_id
RIGHT JOIN floor AS f ON r.floor_id = f.floor_id
WHERE
r.project_id = $project_id AND
f.project_id = $project_id AND
rt.is_active = 'Y' AND
rt.is_delete = 'N'AND
r.is_active = 'Y' AND
r.is_delete = 'N'AND
p.is_active = 'Y' AND
p.is_delete = 'N'");
        
        return $query->result_array();
    }
    
    public function get_floor_in_project($project_id) {
        $lang = $this->lang;
        $query = $this->db->query("SELECT
f.floor_id,
f.floor_name_$lang AS floor_name
FROM
floor AS f
LEFT JOIN rooms AS r ON r.floor_id = f.floor_id
RIGHT JOIN projects AS p ON r.project_id = p.project_id
WHERE
r.is_delete = 'N' AND
f.is_active = 'Y' AND
f.is_delete = 'N' AND
r.is_active = 'Y' AND
f.project_id = $project_id AND
p.project_id = $project_id GROUP BY f.floor_id ");
        
        return $query->result_array();
    }
    
    public function get_roomtype_in_project($project_id) {
        $lang = $this->lang;
        $query = $this->db->query("SELECT
f.floor_id,
rt.roomtype_id,
rt.roomtype_name_$lang AS roomtype_name
FROM
floor AS f
LEFT JOIN rooms AS r ON r.floor_id = f.floor_id
RIGHT JOIN projects AS p ON r.project_id = p.project_id
RIGHT JOIN roomtype AS rt ON r.roomtype_id = rt.roomtype_id
WHERE
r.is_delete = 'N' AND
f.is_active = 'Y' AND
f.is_delete = 'N' AND
r.is_active = 'Y' AND
f.project_id = $project_id AND
p.project_id = $project_id
GROUP BY r.roomtype_id,r.floor_id   
ORDER BY rt.roomtype_name_$lang ASC");
        
        $result = array();
        $i = 0;
        foreach ($query->result_array() as $row)
        {
            $result[$row['floor_id']][$i]['roomtype_id'] = $row['roomtype_id'];                
            $result[$row['floor_id']][$i]['roomtype_name'] = $row['roomtype_name'];    
            $i++;            
        }
        return $result;
    }
    
    public function get_logo($project_id) {
        $this->db->select('project_logo_'.$this->lang.' as project_logo');
        $this->db->from('projects');
        $this->db->where('project_id', $project_id);
        $res = $this->db->get();        
        return $this->config->item('path')['projects'].$res->row_array()['project_logo'];
    }
   
    public function get_room($room_id) {
        $lang = $this->lang;
        $user = $this->session->userdata('user');
        $user_language = (!empty($user['language']))?$user['language']:$lang;
        $query = $this->db->query("SELECT
r.room_id,
r.project_id,
r.roomtype_id,
r.room_code,
r.room_name_en,
r.room_name_$lang AS room_name,
r.room_detail_$lang AS room_detail,
r.room_plan_image_$lang AS room_plan_image,
r.room_floor_plan_image_$lang AS room_floor_plan_image,
r.booking_price_$user_language AS booking_price,
r.booking_status,
r.booking_date,
r.room_size,
r.room_price,
f.floor_name_$lang AS floor_name,
rt.roomtype_name_$lang AS roomtype_name
FROM
rooms AS r
INNER JOIN floor AS f ON r.floor_id = f.floor_id
INNER JOIN roomtype AS rt ON r.roomtype_id = rt.roomtype_id
WHERE
r.room_id = $room_id AND
r.is_active = 'Y' AND
r.is_delete = 'N' AND
f.is_active = 'Y' AND
f.is_delete = 'N' AND
rt.is_active = 'Y' AND
rt.is_delete = 'N'");
        return $query->result_array();
    }
    
    public function get_member($user_id) {
        $lang = $this->lang;
        $query = $this->db->query("SELECT
	m.member_id,
	m.prefix,
	m.`name`,
	m.surname,
	m.`password`,
	m.citizen_id,
	m.passport_id,
	m.email,
	m.phone,
	m.address_no,
	m.village,
	m.soi,
	m.subdistrict,
	m.district,
	m.province,
	m.country,
	m.postcode,
	m.doc_address_sameas,
	m.doc_address_no,
	m.doc_village,
	m.doc_soi,
	m.doc_subdistrict,
	m.doc_district,
	m.doc_province,
	m.doc_country,
	m.doc_postcode,
	m.member_status,
	m.`language`
FROM
	members AS m
WHERE
	m.member_id = $user_id
");
        return $query->result_array();
    }
    
    public function get_floor_plan($floor_id,$roomtype_id = '') {
        $lang = $this->lang;
        if(!empty($floor_id)){
            $txt = ($roomtype_id == 0)?"":" AND r.roomtype_id = $roomtype_id ";
            $query = $this->db->query("SELECT
            f.floor_id,
            f.floor_name_$lang AS floor_name,
            f.floor_plan_image_$lang AS floor_plan_image,
            rt.roomtype_name_$lang AS roomtype_name
    FROM
            floor AS f
    RIGHT JOIN rooms AS r ON r.floor_id = f.floor_id
    AND r.project_id = f.project_id
    INNER JOIN roomtype AS rt ON r.roomtype_id = rt.roomtype_id
    WHERE
            f.floor_id = $floor_id
    $txt
        GROUP BY
            r.roomtype_id
    ");
            return $query->result_array();
        }else{
            return "";
        }
        
    }
    
    public function update_room_padding() {
        $this->bbl =  $this->config->item('bbl');
        $timeout = $this->bbl['timeout'];
        $sql = "SELECT b.booking_id, r.room_id FROM booking AS b JOIN rooms AS r ON b.room_id = r.room_id WHERE b.create_date + INTERVAL $timeout MINUTE < now() AND b.booking_status = 'P' AND r.booking_status = 'P'";
        $query = $this->db->query($sql);
        foreach ($query->result_array() as $row)
        {
            if($row['room_id'] !=''){
                $room_id = $row['room_id'];
                $this->db->query("UPDATE rooms SET booking_status = 'Y' WHERE room_id = $room_id and booking_status = 'P'");
            }
            if($row['booking_id'] !=''){
                $booking_id = $row['booking_id'];
                $this->db->query("UPDATE booking SET booking_status = 'E' WHERE booking_id = $booking_id and booking_status = 'P'");
            }
        }
        return true;
    }
    
    public function get_floor_image($project_id,$floor_id,$roomtype_id,$room_id = '') {
        if(!empty($floor_id)){
            $txt_room = ($room_id != '')?"AND r.room_id = $room_id ":''; 
            $query = $this->db->query("SELECT
            r.room_id,r.booking_status,
            r.room_name_$this->lang AS room_name,
            r.room_plan_image_$this->lang AS room_plan_image,
            r.room_floor_plan_image_$this->lang AS room_floor_plan_image 
    FROM
            rooms AS r 
    WHERE 
            r.floor_id = $floor_id 
            AND r.project_id = $project_id 
            AND r.roomtype_id = $roomtype_id 
            $txt_room    
            AND r.is_active = 'Y' 
            AND r.is_delete = 'N' ORDER BY r.room_name_$this->lang ASC");
            $i=0;
            $result = array();
            $result[0]['have_booking_status'] = 'N';
            $result[0]['have_room'] = 'N';
            foreach ($query->result_array() as $row)
            {
                if($row['room_plan_image'] !=''){
                    $size_room_plan_image = getimagesize($this->config->item('path')['rooms'].$row['room_plan_image']);
                }
                if($row['room_floor_plan_image'] !=''){
                    $size_room_floor_plan_image = getimagesize($this->config->item('path')['rooms'].$row['room_floor_plan_image']);
                }
                $s_room_plan_image = '';
                $s_room_floor_plan_image = '';
                if (is_array($size_room_plan_image) && is_array($size_room_floor_plan_image)){
                    if($size_room_floor_plan_image[0] > $size_room_plan_image[0]){
                        $s_room_plan_image = '40%';
                        $s_room_floor_plan_image = '60%';
                    }else{
                        $s_room_plan_image = '40%';
                        $s_room_floor_plan_image = '60%';
                    }
                }
                $result[$i]['room_id'] = $row['room_id'];                
                $result[$i]['booking_status'] = $row['booking_status'];
                $result[0]['have_booking_status'] = ($row['booking_status']=='Y')?'Y':$result[0]['have_booking_status'];
                $result[0]['have_room'] = ($row['room_id']!='')?'Y':$result[0]['have_room'];
                $result[$i]['room_name'] = $row['room_name'];
                $result[$i]['size_room_plan_image'] = $s_room_plan_image;                
                $result[$i]['size_room_floor_plan_image'] = $s_room_floor_plan_image;                
                $result[$i]['room_plan_image'] = $this->config->item('path')['rooms'].$row['room_plan_image'];                
                $result[$i]['room_floor_plan_image'] = $this->config->item('path')['rooms'].$row['room_floor_plan_image'];    
                $i++;
            }
            return $result;
        }else{
            return "";
        }
    }
    
    public function get_floor_room($project_id,$floor_id,$roomtype_id,$room_id = '') {
        if(!empty($floor_id)){
            $txt_room = ($room_id != '')?"AND r.room_id = $room_id ":''; 
            $query = $this->db->query("SELECT
            r.room_id,r.booking_status,
            r.room_name_$this->lang AS room_name,
            r.room_plan_image_$this->lang AS room_plan_image,
            r.room_floor_plan_image_$this->lang AS room_floor_plan_image 
    FROM
            rooms AS r 
    WHERE 
            r.floor_id = $floor_id 
            AND r.project_id = $project_id 
            AND r.roomtype_id = $roomtype_id 
            $txt_room    
            AND r.is_active = 'Y' 
            AND r.is_delete = 'N' ORDER BY r.room_name_$this->lang ASC");
            $i=0;
            $result = array();
            $result[0]['have_booking_status'] = 'N';
            $result[0]['have_room'] = 'N';
            foreach ($query->result_array() as $row)
            {
                if($row['room_plan_image'] !=''){
                    $size_room_plan_image = getimagesize($this->config->item('path')['rooms'].$row['room_plan_image']);
                }
                if($row['room_floor_plan_image'] !=''){
                    $size_room_floor_plan_image = getimagesize($this->config->item('path')['rooms'].$row['room_floor_plan_image']);
                }
                $s_room_plan_image = '';
                $s_room_floor_plan_image = '';
                if (is_array($size_room_plan_image) && is_array($size_room_floor_plan_image)){
                    if($size_room_floor_plan_image[0] > $size_room_plan_image[0]){
                        $s_room_plan_image = '40%';
                        $s_room_floor_plan_image = '60%';
                    }else{
                        $s_room_plan_image = '40%';
                        $s_room_floor_plan_image = '60%';
                    }
                }
                $result[$i]['room_id'] = $row['room_id'];                
                $result[$i]['booking_status'] = $row['booking_status'];
                $result[0]['have_booking_status'] = ($row['booking_status']=='Y')?'Y':$result[0]['have_booking_status'];
                $result[0]['have_room'] = ($row['room_id']!='')?'Y':$result[0]['have_room'];
                $result[$i]['room_name'] = $row['room_name'];
                $result[$i]['size_room_plan_image'] = $s_room_plan_image;                
                $result[$i]['size_room_floor_plan_image'] = $s_room_floor_plan_image;                
                $result[$i]['room_plan_image'] = $this->config->item('path')['rooms'].$row['room_plan_image'];                
                $result[$i]['room_floor_plan_image'] = $this->config->item('path')['rooms'].$row['room_floor_plan_image'];    
                $i++;
            }
            return $result;
        }else{
            return "";
        }
    }
    
    public function has_data($field, $data) {        
        $this->db->select($field);
        $this->db->from('members');
        $this->db->where($field, $data["sign_".$field]);        
        $res = $this->db->get();
        return ($res->num_rows()>0)?true:false;               
    }
    
    public function room_busy($roomid) {
        $this->db->select('booking_status');
        $this->db->from('rooms');
        $this->db->where('room_id', $roomid);
        $res = $this->db->get();        
        return ($res->row_array()['booking_status']=='Y')?true:false;            
    }
    
    /*public function has_room_in_booking($roomid) {
        $this->db->select('booking_id');
        $this->db->from('booking');
        $this->db->where('room_id', $roomid);        
        $res = $this->db->get();
        return ($res->num_rows()>0)?true:false;           
    }*/
    
    public function update_room_busy($roomid) {
        if($this->room_busy($roomid)){
            $time = date("Y-m-d H:i:s");
            $this->db->query("UPDATE rooms SET booking_status = 'P' , update_date = '$time' WHERE room_id = $roomid");
            return true;
        }else{
            return false;
        }
    }
    
    public function check_successPay($token,$table) {
        $field_status = ($table == 'booking')?'booking_status':'payment_status';
        $this->db->select('is_active');
        $this->db->from($table);
        $this->db->where('token', $token);        
        $this->db->where($field_status, 'P');        
        $res = $this->db->get();
        return ($res->num_rows()>0)?true:false;
    }
    
    public function cancelPay($token,$table) {
        $field_status = ($table == 'booking')?'booking_status':'payment_status';
        $this->db->where('token', $token);
        $this->db->update($table, array($field_status => "C"));
        if($table == "booking"){
            $room_id = $this->get_roomid_bytoken($token);
            $this->db->where('room_id', $room_id);
            $this->db->update("rooms", array($field_status => "Y"));
        }
        return true;
    }
    
    public function failPay($token,$table) {
        $field_status = ($table == 'booking')?'booking_status':'payment_status';
        $this->db->where('token', $token);
        $this->db->update($table, array($field_status => "F"));
        if($table == "booking"){
            $room_id = $this->get_roomid_bytoken($token);
            $this->db->where('room_id', $room_id);
            $this->db->update("rooms", array($field_status => "Y"));
        }
        return true;
    }
    
    public function booking_confirm($room_id,$card_token,$charge_id,$json_charge,$create_date) {
        if ($this->room_busy($room_id)) {
            if ($this->update_room_busy($room_id)) {
            $room = $this->get_room($room_id);
            $data = array(
                'member_id' => $this->user['ID'],
                'room_id' => $room[0]['room_id'],                
                'total_paid' => $room[0]['booking_price'],
                'total_tax' => $room[0]['booking_price']*0.07,
                //'total_booking' => ($room[0]['booking_price'])-($room[0]['booking_price']*0.07),
                'total_booking' => $room[0]['booking_price'],
                'booking_status' => 'P',
                'token' => $card_token,
                'charge_id' => $charge_id,
                'transaction' => $json_charge,
                'create_by_id' => $this->user['ID'],
                'create_date' => $create_date,
                'is_active' => 'Y',                
                'is_delete' => 'N'
            );
            $this->db->insert('booking', $data);  
            return true;
            }
        }
        return false;
    }
    
    public function userid() {
        return $this->user['ID'];
    }
    
    
    public function complete_booking($token,$table,$status) {
        $field_status = ($table == 'booking')?'booking_status':'payment_status';
        if($this->has_booking($token,$table)){
            $this->db->query("UPDATE $table SET $field_status = '$status' WHERE token = '$token'");
            if($table == 'booking'){
                $room_id = $this->get_roomid_bytoken($token);
                if($status == 'S'){
                    $this->db->query("UPDATE rooms SET booking_status = 'N' WHERE room_id = $room_id");
                }else{
                    $this->db->query("UPDATE rooms SET booking_status = 'Y' WHERE room_id = $room_id");
                }
            }
            return true;
        }else{
            return false;
        }
    }
    
    public function updatetransaction($token,$table,$transaction = "") {
        if($this->has_booking($token,$table)){
            $data = json_decode($transaction);
            $payref =(!empty($data->PayRef))?$data->PayRef:"";
            $txtime = (!empty($data->TxTime))?$data->TxTime:"";
            $txtime = str_replace(".0","",$txtime);
            $this->db->query("UPDATE $table SET transaction = '$transaction', payref = '$payref', txtime = '$txtime' WHERE token = '$token'");
            return true;
        }else{
            return false;
        }
    }
    
    public function has_booking($token,$table) {
        $this->db->select('is_active');
        $this->db->from($table);
        $this->db->where('token', $token);      
        $res = $this->db->get();
        return ($res->num_rows()>0)?true:false;               
    }
    
    public function get_charge_id($token,$room_id) {
        $this->db->select('charge_id');
        $this->db->from('booking');
        $this->db->where('token', $token);        
        $this->db->where('room_id', $room_id);        
        $res = $this->db->get();
        return ($res->num_rows()>0)?$res->row_array()['charge_id']:'';
    }
    
    public function get_roomid_bytoken($token) {
        $this->db->select('room_id');
        $this->db->from('booking');
        $this->db->where('token', $token);        
        $res = $this->db->get();
        return ($res->num_rows()>0)?$res->row_array()['room_id']:'';
    }
    
    public function get_table_bytoken($token) {
        $this->db->select('room_id');
        $this->db->from('booking');
        $this->db->where('token', $token);        
        $res = $this->db->get();
        if($res->num_rows()>0){
            return "booking";
        }
        
        $this->db->select('payment_id');
        $this->db->from('agents_payment');
        $this->db->where('token', $token);        
        $res = $this->db->get();
        if($res->num_rows()>0){
            return "agents_payment";
        }
        
        return '';
    }
        
    public function get_detail_sentmail($token) {
        $this->db->select('member_id');
        $this->db->select('room_id');
        $this->db->select('total_booking');
        $this->db->from('booking');
        $this->db->where('token', $token);     
        $res = $this->db->get();
        return $res->row_array();
    }
    
    
    
    
    
}