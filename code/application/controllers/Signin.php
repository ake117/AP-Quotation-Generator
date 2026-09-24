<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Signin extends CI_Controller {
    private $config_grande;
    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('Signin_model');
        //$this->load->library('tcdc_api');

        $this->load->config('grande', true);
        $this->config_grande =  $this->config->item('grande');
    }

    public function index($email='') {
        
        $p_email = '';
        if (isset($email)) {
            $p_email = urldecode($email);
            $p_email = base64_decode($p_email);   
        }                
        
        $cache_id = md5($_SERVER['REQUEST_URI']);
        $template = 'signin.tpl';
        
        if ($this->session->has_userdata('user')) {redirect(base_url()."projects");}
        
        $this->smarty->setCaching(0);

        if(!$this->smarty->isCached($template, $cache_id)) {
            
            /*
             * can't move position
             */
            //$publications_data = $this->tcdc_api->get_publication_data_by_main();

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
            
            $this->smarty->assign('p_email', $p_email);
        }

        $this->smarty->view( $template, $cache_id);

//        $this->smarty->clearAllCache();
    }
    
    public function submit() {
        
        $data = $this->input->post(null, TRUE);        
        extract($data);
        
        $query = $this->Signin_model->get_member($email);     
        
        $result["status"] = "n/a";
        $result["message"] = "";
                
        if ($query->num_rows() > 0) {
            $row = $query->row();    
            if ($row->member_status === 'active') {
                if (!password_verify($password,$row->password)) {
                    $result["status"] = "error";
                    $result["message"] = "{$this->lang->language['validate_login_password']}";
                } else {
                    $result["status"] = "success";  
                    $user['sessionID'] = bin2hex(openssl_random_pseudo_bytes(16));
                    $user['ID'] = $row->member_id;
                    $user['language'] = $row->language;
                    $this->session->set_userdata('user', $user);                     
                }
            } else {
                $result["status"] = "error";
                $result["message"] = "{$this->lang->language['validate_login_status']}";
            }
            
        } else {
            $result["status"] = "error";
            $result["message"] = "{$this->lang->language['validate_login_email']}";
        }
                                                
        echo json_encode($result);        
    }
    
    public function signout() {        
        $this->session->unset_userdata('user');        
        redirect(base_url());
    }

}