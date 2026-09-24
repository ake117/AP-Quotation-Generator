<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Projects extends CI_Controller {
    private $config_grande;
    public function __construct() {
        parent::__construct();
        
        if (!$this->session->has_userdata('user')) {redirect(base_url());}
        
        //$this->load->helper('url');
        //$this->load->helper(array('form', 'url'));
        //$this->load->library('form_validation');
        
        $this->load->model('Projects_model');
        //$this->load->library('tcdc_api');

        //$this->load->config('grande', true);
        //$this->config_grande =  $this->config->item('grande');        
    }

    public function index() {
        $cache_id = md5($_SERVER['REQUEST_URI']);
        $template = 'projects.tpl';
        
        
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
            
            //$this->smarty->assign('amphur', '');
            //$this->smarty->assign('province', $this->Signup_model->get_province());
            //$this->smarty->assign('district', array(''=>'กรุณาระบุอำเภอ/เขต'));
            //$this->smarty->assign('subdistrict', array(''=>'กรุณาระบุตำบล/แขวง'));
            
            $this->smarty->assign('projects', $this->Projects_model->get_projects());
        }

        $this->smarty->view( $template, $cache_id);

//        $this->smarty->clearAllCache();
    }  
    
            
    

}