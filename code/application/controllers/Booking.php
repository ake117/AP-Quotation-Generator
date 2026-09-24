<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Booking extends CI_Controller {
    private $config_grande, $omise;
    public function __construct() {
        parent::__construct();
        $fetch_method = strtolower($this->router->fetch_method());
        /*echo "<pre>";echo $fetch_method;echo "</pre>";//exit;
        if($fetch_method == "cancelurl"){
            echo "<pre>";echo 111;echo "</pre>";
        }*/
        if (!$this->session->has_userdata('user')) {
            if($fetch_method != "cancelurl"){
                if($fetch_method != "data_feed"){
                    redirect(base_url());
                }
            }
        }

        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');


        $this->load->model('Booking_model');

        $this->load->config('grande', true);
        $this->config_grande =  $this->config->item('default');
        $this->config_grande2 =  $this->config->item('grande');
        $this->omise =  $this->config->item('omise');
        $this->bbl =  $this->config->item('bbl');


        $this->load->library('omise_api');
        $this->omise_api->init($this->omise['pkey'], $this->omise['skey']);  // replace with your keys

    }

    public function index($id) {
        if ($id == '') {redirect(base_url());}
        $cache_id = md5($_SERVER['REQUEST_URI']);
        $template = 'booking.tpl';

        $this->smarty->setCaching(0);

        if(!$this->smarty->isCached($template, $cache_id)) {

            /*
             * can't move position
             */
            //$publications_data = $this->tcdc_api->get_publication_data_by_main();
            $actual_link = base_url().$_SERVER['REQUEST_URI'];
            $this->session->set_userdata('redirect', $actual_link);

            $this->smarty->assign('main_menu', $this->lang->line('menu_array'));
            $this->smarty->assign('current_menu', 'home');
            $this->smarty->assign('language', $this->lang->line('lang'));
            $this->smarty->assign('lang', $this->lang->language);

            $this->smarty->assign('title', $this->config_grande['title']);
            $this->smarty->assign('description', $this->config_grande['description']);
            $this->smarty->assign('keywords', $this->config_grande['keywords']);
            $this->smarty->assign('og_url', $this->config_grande['og_url']);
            $this->smarty->assign('og_image', $this->config_grande['og_image']);
            $this->smarty->assign('author', $this->config_grande['author']);
            $this->smarty->assign('og_site_name', $this->config_grande['og_site_name']);

            $this->smarty->assign('logo', $this->Booking_model->get_logo($id));
            $this->smarty->assign('project_id', $id);
            $this->smarty->assign('project_name', $this->uri->segment(2));

            $this->smarty->assign('get_floor_in_project', $this->Booking_model->get_floor_in_project($id));
            $this->smarty->assign('get_room_in_project', $this->Booking_model->get_room_in_project($id));
            $this->smarty->assign('get_roomtype_in_project', $this->Booking_model->get_roomtype_in_project($id));

        }

        $this->smarty->view( $template, $cache_id);

//        $this->smarty->clearAllCache();
    }

    public function get_floor_image() {
        $cache_id = md5($_SERVER['REQUEST_URI']);
        $template = 'booking_get_floor.tpl';

        $this->smarty->setCaching(0);

        if(!$this->smarty->isCached($template, $cache_id)) {
            $project_id = $this->input->post('project_id');
            $floor_id = $this->input->post('floor_id');
            $roomtype_id = $this->input->post('roomtype_id');
            $this->smarty->assign('language', $this->lang->line('lang'));
            $this->smarty->assign('lang', $this->lang->language);
            $this->smarty->assign('get_room_in_project', $this->Booking_model->get_room_in_project($project_id));
            $this->smarty->assign('get_floor_plan', $this->Booking_model->get_floor_plan($floor_id,$roomtype_id));
            $this->smarty->assign('get_floor_room', $this->Booking_model->get_floor_room($project_id,$floor_id,$roomtype_id));
            $this->smarty->assign('get_floor_image', $this->Booking_model->get_floor_image($project_id,$floor_id,$roomtype_id));
        }
        $this->smarty->view( $template);
        //echo $floor_image;
    }

    public function get_room_image() {
        $cache_id = md5($_SERVER['REQUEST_URI']);
        $template = 'booking_get_floor.tpl';

        $this->smarty->setCaching(0);

        if(!$this->smarty->isCached($template, $cache_id)) {
            $project_id = $this->input->post('project_id');
            $floor_id = $this->input->post('floor_id');
            $roomtype_id = $this->input->post('roomtype_id');
            $room_id = $this->input->post('room_id');
            $this->smarty->assign('language', $this->lang->line('lang'));
            $this->smarty->assign('lang', $this->lang->language);
            $this->smarty->assign('get_room_in_project', $this->Booking_model->get_room_in_project($project_id));
            $this->smarty->assign('get_floor_plan', $this->Booking_model->get_floor_plan($floor_id,$roomtype_id));
            $this->smarty->assign('get_floor_room', $this->Booking_model->get_floor_room($project_id,$floor_id,$roomtype_id));
            $this->smarty->assign('get_floor_image', $this->Booking_model->get_floor_image($project_id,$floor_id,$roomtype_id,$room_id));
        }
        $this->smarty->view( $template);
        //echo $floor_image;
    }

    public function check_room() {
        $cache_id = md5($_SERVER['REQUEST_URI']);
        $template = 'booking_checkroom.tpl';

        $this->smarty->setCaching(0);
        $user_id = $this->user['ID'];
        if(!$this->smarty->isCached($template, $cache_id)) {
            $room_id = $this->input->post('room_id');
            if ($this->Booking_model->room_busy($room_id)) {
                $this->smarty->assign('language', $this->lang->line('lang'));
                $this->smarty->assign('lang', $this->lang->language);
                $this->smarty->assign('get_member', $this->Booking_model->get_member($user_id));
                $this->smarty->assign('get_room', $this->Booking_model->get_room($room_id));
            }
        }
        $this->smarty->view( $template);
        //echo $floor_image;
    }

    public function successUrl() {
        $base_url = base_url();
        /*$Ref = $this->input->get('Ref', TRUE);
        if($Ref != ''){
            $cancelPay = $this->Booking_model->check_successPay($Ref);
            if($cancelPay){
                $roomid = $this->Booking_model->get_roomid_bytoken($Ref);
                $this->complete($Ref,$roomid);
            }
            redirect($base_url."booking/successUrl");
        }*/
        redirect(base_url().'history');
    }

    public function failUrl() {
        $base_url = base_url();
        $Ref = $this->input->get('Ref', TRUE);
        if($Ref != ''){
            $table = $this->Booking_model->get_table_bytoken($Ref);
            $cancelPay = $this->Booking_model->failPay($Ref,$table);
            redirect($base_url."booking/failUrl");
        }

        $cache_id = md5($_SERVER['REQUEST_URI']);
        $template = 'booking_failUrl.tpl';

        $this->smarty->setCaching(0);

        if(!$this->smarty->isCached($template, $cache_id)) {

            $this->smarty->assign('main_menu', $this->lang->line('menu_array'));
            $this->smarty->assign('current_menu', 'home');
            $this->smarty->assign('language', $this->lang->line('lang'));
            $this->smarty->assign('lang', $this->lang->language);

            $this->smarty->assign('title', $this->config_grande2['default']['title']);
            $this->smarty->assign('description', $this->config_grande2['default']['description']);
            $this->smarty->assign('keywords', $this->config_grande2['default']['keywords']);
            $this->smarty->assign('og_url', $this->config_grande2['default']['og_url']);
            $this->smarty->assign('og_image', $this->config_grande2['default']['og_image']);

            $this->smarty->assign('author', $this->config_grande2['default']['author']);
            $this->smarty->assign('og_site_name', $this->config_grande2['default']['og_site_name']);
        }

        $this->smarty->view( $template, $cache_id);
    }
    public function cancelUrl($id) {
        $base_url = base_url();
        $Ref = $this->input->get('Ref', TRUE);
        if($Ref != ''){
            $table = $this->Booking_model->get_table_bytoken($Ref);
            $cancelPay = $this->Booking_model->cancelPay($Ref,$table);
            redirect($base_url."booking/failUrl");
        }
        redirect($this->session->userdata('redirect'));
    }

    public function data_feed() {
        $this->load->helper('file');
        $this->load->helper('date');

        $file_path = FCPATH . "/assets/log/payment.txt";

        $now = mdate('%d-%m-%Y %h:%i:%s',  time());

        $successcode = $this->input->post('successcode');
        $PayRef = $this->input->post('PayRef');
        $Ref = $this->input->post('Ref');

        foreach($this->input->post() as $key => $val)
        {
            $data[$key] = $val;
        }

        $json_data = json_encode($data);
        write_file($file_path, "{$now} | {$json_data} \n", 'a+');

        $table = $this->Booking_model->get_table_bytoken($Ref);
        echo "OK";
        //echo $roomid;exit;
        if(!empty($table)){
            $update_transaction = $this->update_transaction($Ref,$table,$json_data);
            /**
             * สถานะการทำรายการ:
                "-1" – ผิดพลาด
                "0" – ผ่าน
                "1" – ไม่ผ่าน
            */
            if($successcode == "0"){
                if($Ref != ''){
                $cancelPay = $this->Booking_model->check_successPay($Ref,$table);
                    if($cancelPay){
                        $this->complete($Ref,$table);
                    }else{
                        //redirect($base_url."booking/successUrl");
                    }
                }
            }elseif($successcode == "1"){
                $cancelPay = $this->Booking_model->failPay($Ref,$table);
            }else{
                $cancelPay = $this->Booking_model->cancelPay($Ref,$table);
            }
        }
        //exit;
    }


    public function checkout($room_id) {
        $base_url = base_url();
        $room = $this->Booking_model->get_room($room_id);
        $userid = $this->Booking_model->userid();
        $booking_price = number_format($room[0]['booking_price'],2,'.','');
        $time = date("Y-m-d H:i:s");
        $orderRef = "$userid/$room_id/$time";
        $card_token = 'b_'.md5($orderRef);
        $charge_id = $orderRef;
        $json_charge = $orderRef;

        if($this->Booking_model->room_busy($room_id)){
            if($this->Booking_model->booking_confirm($room_id,$card_token,$charge_id,$json_charge,$time)){
                $pay_url = $this->bbl['pay_url'];
                $pay_merchantid = $this->bbl['merchantid'];
                $data["me"] = base64_encode("POST"); //method
                $data["ac"] = base64_encode($pay_url); //action
                $data["m"] = base64_encode($pay_merchantid); //merchantId
                $data["a"] = base64_encode("$booking_price"); //amount
                $data["o"] = $card_token; //orderRef
                $data["c"] = base64_encode("764"); //currCode
                $data["s"] = base64_encode($base_url."booking/$room_id/successUrl"); //successUrl
                $data["f"] = base64_encode($base_url."booking/$room_id/failUrl"); //failUrl
                $data["ca"] = base64_encode($base_url."booking/$room_id/cancelUrl"); //cancelUrl
                $data["p"] = base64_encode("N"); //payType
                $data["l"] = base64_encode("E"); //lang
                $data["r"] = base64_encode("-"); //remark
                $data["url"] = "";
                echo json_encode($data);exit;
            }
        }
        $data["url"] = base64_encode($base_url.'history');
        echo json_encode($data);exit;

        /*echo '<pre>';
        print_r($status);
        echo '</pre>';*/
    }

    public function complete($token,$table) {
        if($this->Booking_model->complete_booking($token,$table,"S")){
            if($table == 'booking'){
                $sentmail_complete = $this->sentmail_complete($token);
            }
            //redirect(base_url().'history');
        }
        //redirect(base_url().'history');
    }

    public function update_transaction($token,$table,$transaction = "") {
        $this->Booking_model->updatetransaction($token,$table,$transaction);
        return true;
    }

    public function sentmail_complete($token) {
        $this->load->library('email');
        $lang = $this->lang->language;
        $txt_subject = ($lang == 'en')?'Confirm payment for booking a room. #' : 'ยืนยันการชำระเงินสำหรับการจองห้องสำเร็จ #';

        if($token != ''){
            $get_detail_sentmail = $this->Booking_model->get_detail_sentmail($token);
            $get_room = $this->Booking_model->get_room($get_detail_sentmail['room_id']);
            $get_total_booking = $get_detail_sentmail['total_booking'];
            $subject = $txt_subject." Room ".$get_room[0]['room_name_en'];
            $get_logo = $this->Booking_model->get_logo($get_room[0]['project_id']);
            $get_member = $this->Booking_model->get_member($get_detail_sentmail['member_id']);
            
            $email = $get_member[0]['email'];

            $this->smarty->setCaching(0);
            $this->smarty->assign('lang', $lang);
            $this->smarty->assign('logo', $get_logo);
            $this->smarty->assign('room', $get_room[0]);
            $this->smarty->assign('member', $get_member[0]);
            $this->smarty->assign('total_booking', $get_total_booking);
            $body = $this->smarty->fetch( 'includes/booking_complete.tpl' );


            //echo $body;

            $result = $this->email
            ->from('onlinebooking@grandeasset.com', $this->lang->line('email_from'))
            //->reply_to('wutthiphat@ewitewit.com')    // Optional, an account where a human being reads.
            ->to($email)
            ->subject($subject)
            ->message($body)
            ->send();
            return true;
            //$this->index();
        }
        return false;
        //redirect(base_url());
    }

}

/*
 *
$("#submit_reserve").click(function() {
    $.ajax( {
        type: "POST",
        url: "{$base_url}/booking/{$get_room.0.room_id}/checkout",
        data: { },
        dataType: "json",
        success: function(msg) {
            if(msg.url !== ''){
                window.location.href = atob(msg.url);
            }

            var form = document.createElement("form");
            form.method = atob(msg.me);
            form.action = atob(msg.ac);

            var input1 = document.createElement("input");
            input1.setAttribute("type", "hidden");
            input1.setAttribute("name", "merchantId");
            input1.setAttribute("value", atob(msg.m));
            form.appendChild(input1);

            var input2 = document.createElement("input");
            input2.setAttribute("type", "hidden");
            input2.setAttribute("name", "amount");
            input2.setAttribute("value", atob(msg.a));
            form.appendChild(input2);

            var input3 = document.createElement("input");
            input3.setAttribute("type", "hidden");
            input3.setAttribute("name", "orderRef");
            input3.setAttribute("value", msg.o);
            form.appendChild(input3);

            var input4 = document.createElement("input");
            input4.setAttribute("type", "hidden");
            input4.setAttribute("name", "currCode");
            input4.setAttribute("value", atob(msg.c));
            form.appendChild(input4);

            var input5 = document.createElement("input");
            input5.setAttribute("type", "hidden");
            input5.setAttribute("name", "successUrl");
            input5.setAttribute("value", atob(msg.s));
            form.appendChild(input5);

            var input6 = document.createElement("input");
            input6.setAttribute("type", "hidden");
            input6.setAttribute("name", "failUrl");
            input6.setAttribute("value", atob(msg.f));
            form.appendChild(input6);

            var input7 = document.createElement("input");
            input7.setAttribute("type", "hidden");
            input7.setAttribute("name", "cancelUrl");
            input7.setAttribute("value", atob(msg.ca));
            form.appendChild(input7);

            var input8 = document.createElement("input");
            input8.setAttribute("type", "hidden");
            input8.setAttribute("name", "payType");
            input8.setAttribute("value", atob(msg.p));
            form.appendChild(input8);

            var input9 = document.createElement("input");
            input9.setAttribute("type", "hidden");
            input9.setAttribute("name", "lang");
            input9.setAttribute("value", atob(msg.l));
            form.appendChild(input9);

            var input10 = document.createElement("input");
            input10.setAttribute("type", "hidden");
            input10.setAttribute("name", "remark");
            input10.setAttribute("value", atob(msg.r));
            form.appendChild(input10);

            var input11 = document.createElement("input");
            input11.setAttribute("type", "hidden");
            input11.setAttribute("name", "redirect");
            input11.setAttribute("value", "15");
            form.appendChild(input11);

            document.body.appendChild(form);

            form.submit();
        }
    });
});
 *
 */