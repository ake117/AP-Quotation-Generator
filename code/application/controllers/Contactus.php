<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Contactus extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
//        $c = $this->load->config('tcdc_api', true);
//        $this->load->model('article_model');
//        $this->load->library('tcdc_api');
        $this->load->config('tcdc', true);
        $this->config_tcdc =  $this->config->item('tcdc');
    }

    public function index($error='') {
//        $this->smarty->caching = 1;
//        $this->smarty->assign('base_url', base_url());
//        echo '<pre>';
//        var_dump($article);
//        echo '</pre>';

        $this->smarty->assign('main_menu', $GLOBALS['my_main_menu']);
        $this->smarty->assign('current_menu', 'contactus');
        $this->smarty->assign('error', $error);

        $this->smarty->assign('title', 'Contact us – '. $this->config_tcdc['default']['title_nodash']);
        $this->smarty->assign('description', $this->config_tcdc['default']['description']);
        $this->smarty->assign('keywords', $this->config_tcdc['default']['keywords']);
        $this->smarty->assign('og_url', $this->config_tcdc['default']['og_url']);
        $this->smarty->assign('og_image', $this->config_tcdc['default']['og_image']);

        $this->smarty->view( 'contactus.tpl' );
//        $this->smarty->clearAllCache();
    }

    public function contactus_mail() {
        $this->smarty->setCaching(0);

        $this->load->library('email');

        $form_data = $this->input->post();
        // or just the username:
        $name = $this->input->post("name");
        $email = $this->input->post("email");
        $title = $this->input->post("title");
        $message = $this->input->post("message");
        $recaptcha = $this->input->post("g-recaptcha-response");
        if($name == '' || $email == '' || $title == '' || $message == '' || $recaptcha == ''){
            $_SESSION['name'] = $name;
            $_SESSION['email'] = $email;
            $_SESSION['title'] = $title;
            $_SESSION['message'] = $message;
            $this->index('กรุณากรอกข้อมูลให้ครบถ้วน');
            return;
        }
//        $today = date("Y-m-d H:i:s");

        $subject = "Contact US";

        $this->smarty->assign('name', $name);
        $this->smarty->assign('email', $email);
        $this->smarty->assign('title', $title);
        $this->smarty->assign('message', $message);
        $this->smarty->assign('email_title', html_escape($subject));
        $this->smarty->assign('email_charset', strtolower(config_item('charset')));

        $body = $this->smarty->fetch( 'includes/contactus.tpl' );

        $result = $this->email
            ->from('noreply@ewitewit.com', 'Createtive Thailand')
            //->reply_to('wutthiphat@ewitewit.com')    // Optional, an account where a human being reads.
            ->to('creativethailand@tcdc.or.th')
            ->subject($subject)
            ->message($body)
            ->send();
        $this->index();
//        $this->smarty->clearAllCache();
    }

    public function subscribe() {
        $txt_subscribe = $this->input->post("emailaddress");
        $txt_ip = $this->input->post("dataip");
        $result["status"] = "error";
	$result["html"] = '';
        if($txt_subscribe == ''){echo json_encode($result);return;}
        if(!filter_var($txt_subscribe, FILTER_VALIDATE_EMAIL)){echo json_encode($result);return;}
        function saveLog($detailLog, $fname="subscribe.log") {
            $fp = fopen(FCPATH ."/assets/log/".$fname,"a");
            fputs($fp, date("d/m/Y H:i:s").","."$detailLog\r\n");
            fclose($fp);
        }
        $txt_all = "$txt_subscribe,$txt_ip";
        saveLog($txt_all);
        $result["status"] = "success";
	$result["html"] = $txt_all;
	echo json_encode($result);
        return;
    }


}