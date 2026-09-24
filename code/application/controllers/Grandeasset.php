<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Grandeasset extends CI_Controller {
    private $config_grande;
    public function __construct() {
        parent::__construct();
        
        if ($this->session->has_userdata('user')) {redirect(base_url()."projects");}
        
        $this->load->helper('url');
        $this->load->model('Grandeasset_model');
        //$this->load->library('tcdc_api');

        $this->load->config('grande', true);
        $this->config_grande =  $this->config->item('grande');       
    }

    public function index() {
        redirect(base_url()."admin");
        $cache_id = md5($_SERVER['REQUEST_URI']);        
        $template = 'grandeasset.tpl';        
        
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
            
        }

        $this->smarty->view( $template, $cache_id);

//        $this->smarty->clearAllCache();
    }

}