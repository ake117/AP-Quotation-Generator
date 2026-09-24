<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Project extends CI_Controller {
    private $config_grande;
    public function __construct() {
        parent::__construct();
        
        if (!$this->session->has_userdata('user')) {redirect(base_url());}
        
        //$this->load->helper('url');
        //$this->load->helper(array('form', 'url'));
        //$this->load->library('form_validation');
        
        $this->load->model('Project_model');
        //$this->load->library('tcdc_api');

        //$this->load->config('grande', true);
        //$this->config_grande =  $this->config->item('grande');        
    }

    public function index($project_id) {
                                
        $cache_id = md5($_SERVER['REQUEST_URI']);
        $template = 'project.tpl';
        
        
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
            
            $project = $this->Project_model->get_project($project_id);
            $facility = $this->Project_model->get_facility($project_id);
            $roomtype = $this->Project_model->get_roomtype_gallery($project_id);
            $gallery = $this->Project_model->get_gallery($project_id);
            
            
            $this->smarty->assign('project', $project);
            $this->smarty->assign('facility', $facility);
            $this->smarty->assign('roomtype', $roomtype);
            $this->smarty->assign('gallery', $gallery);
            
            
            $this->smarty->assign('logo', $this->config->item('path')['projects'].$project['project_logo']);
        }

        $this->smarty->view( $template, $cache_id);

//        $this->smarty->clearAllCache();
    }  
    
            
    

}