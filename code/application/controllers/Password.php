<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Password extends CI_Controller {
    private $config_grande;
    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('Password_model');
        //$this->load->library('tcdc_api');

        $this->load->config('grande', true);
        $this->config_grande =  $this->config->item('grande');
    }

   
    public function index() {
        redirect(base_url());
    }
    
    
    public function change() {
        if (!$this->session->has_userdata('user')) {redirect(base_url());}
        
        $cache_id = md5($_SERVER['REQUEST_URI']);
        $template = 'password-change.tpl';
        
        if(!$this->smarty->isCached($template, $cache_id)) {
            
            /*
             * can't move position
             */            
            $this->smarty->assign('main_menu', $this->lang->line('menu_array'));
            $this->smarty->assign('current_menu', 'home');
            $this->smarty->assign('language', $this->lang->line('lang'));
            $this->smarty->assign('lang', $this->lang->language);

            $this->smarty->assign('title', $this->config_grande['default']['title']);
            $this->smarty->assign('description', $this->config_grande['default']['description']);
            $this->smarty->assign('keywords', $this->config_grande['default']['keywords']);
            $this->smarty->assign('og_url', $this->config_grande['default']['og_url']);
            $this->smarty->assign('og_image', $this->config_grande['default']['og_image']);
            
            $this->smarty->assign('author', $this->config_grande['default']['author']);
            $this->smarty->assign('og_site_name', $this->config_grande['default']['og_site_name']);
            
            $this->smarty->assign('member', $this->Password_model->get_member($this->user['ID']));
        }
        
        $this->smarty->setCaching(0);
        $this->smarty->view( $template, $cache_id);        
    }
    
    public function reset() {
        $cache_id = md5($_SERVER['REQUEST_URI']);
        $template = 'password-reset.tpl';
        
        if(!$this->smarty->isCached($template, $cache_id)) {
            
            /*
             * can't move position
             */            
            $this->smarty->assign('main_menu', $this->lang->line('menu_array'));
            $this->smarty->assign('current_menu', 'home');
            $this->smarty->assign('language', $this->lang->line('lang'));
            $this->smarty->assign('lang', $this->lang->language);

            $this->smarty->assign('title', $this->config_grande['default']['title']);
            $this->smarty->assign('description', $this->config_grande['default']['description']);
            $this->smarty->assign('keywords', $this->config_grande['default']['keywords']);
            $this->smarty->assign('og_url', $this->config_grande['default']['og_url']);
            $this->smarty->assign('og_image', $this->config_grande['default']['og_image']);
            
            $this->smarty->assign('author', $this->config_grande['default']['author']);
            $this->smarty->assign('og_site_name', $this->config_grande['default']['og_site_name']);                        
        }
        
        $this->smarty->setCaching(0);
        $this->smarty->view( $template, $cache_id);        
    }
    
    public function create($token) {        

        $token = urldecode($token);
        $token = base64_decode($token);       
        $member = $this->Password_model->valid_resettoken($token);                
        
        $cache_id = md5($_SERVER['REQUEST_URI']);
        $template = 'password-new.tpl';                        
        if(!$this->smarty->isCached($template, $cache_id)) {
            
            /*
             * can't move position
             */            
            $this->smarty->assign('main_menu', $this->lang->line('menu_array'));
            $this->smarty->assign('current_menu', 'home');
            $this->smarty->assign('language', $this->lang->line('lang'));
            $this->smarty->assign('lang', $this->lang->language);

            $this->smarty->assign('title', $this->config_grande['default']['title']);
            $this->smarty->assign('description', $this->config_grande['default']['description']);
            $this->smarty->assign('keywords', $this->config_grande['default']['keywords']);
            $this->smarty->assign('og_url', $this->config_grande['default']['og_url']);
            $this->smarty->assign('og_image', $this->config_grande['default']['og_image']);
            
            $this->smarty->assign('author', $this->config_grande['default']['author']);
            $this->smarty->assign('og_site_name', $this->config_grande['default']['og_site_name']);   
            $this->smarty->assign('member', $member);
        }
        
        $this->smarty->setCaching(0);
        $this->smarty->view( $template, $cache_id);        
    }
    
    public function submitchangereset() {            
        
        $data = $this->input->post(null, TRUE);        
        extract($data);                
        
        $this->Password_model->changepassword($sign_member_id,$sign_password);           
        $result["status"] = "success";                        
                                                
        echo json_encode($result);        
    }
    
    public function submitchange() {            
        
        $data = $this->input->post(null, TRUE);        
        extract($data);   
        
        $member = $this->Password_model->get_member($this->user['ID']);        
                
        if (count($member) > 0) {            
            if (!password_verify($sign_password_old,$member['password'])) {
                $result["status"] = "error";
                $result["message"] = "{$this->lang->language['validate_login_password']}";
            } else {
                $this->Password_model->changepassword($sign_member_id,$sign_password);           
                $result["status"] = "success";
                $result["message"] = "";
            }
        } else {$result["status"] = "error";}
                                                                                        
        echo json_encode($result);        
    }
    
    public function submitreset() {            
        
        $data = $this->input->post(null, TRUE);        
        extract($data);
        
        if ($this->Password_model->hasemail($sign_email)) {
            //send reset email
            
            $member = $this->Password_model->get_memberbyemail($sign_email);
            $token = $this->Password_model->set_resettoken($member["member_id"]);
            
            $resetUrl = base_url()."password/create/".urlencode(base64_encode($token));
            $resetLink = "<a href='".$resetUrl."'>".$this->lang->line('resetpass_link')."</a>";
                    
            $email_title = $this->lang->line('resetpass_emaildear')." ".$member['name']." ".$member['surname'];
            $email_content = sprintf($this->lang->line('resetpass_emailcontent'),$resetLink);
            
            $this->smarty->setCaching(0);
            $this->smarty->assign('email_title', $email_title);
            $this->smarty->assign('email_content', $email_content);            
            $body = $this->smarty->fetch('includes/email_template.tpl' );
            $subject = $this->lang->line('email_title')." Reset Password.";
            
            //echo $body;
            $this->load->library('email');
            
            $res = $this->email
            ->from('onlinebooking@grandeasset.com', $this->lang->line('email_from'))
            //->reply_to('wutthiphat@ewitewit.com')    // Optional, an account where a human being reads.
            ->to($sign_email)
            ->subject($subject)
            ->message($body)
            ->send();
                                    
            $result["status"] = "success";
            $result["message"] = $body; 
            $result["res"] = $res;
            
        } else {
            $result["status"] = "error";
            $result["message"] = $this->lang->line('validate_reset_email');
        }                
                   
                                                
        echo json_encode($result);        
    }

}