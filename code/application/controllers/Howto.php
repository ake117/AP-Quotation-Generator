<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Howto extends CI_Controller {
    private $config_grande;
    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->config('grande', true);
        
        $this->config_grande =  $this->config->item('grande');
    }

    public function index() {
               
        
        $cache_id = md5($_SERVER['REQUEST_URI']);
        $template = 'howto.tpl';                
        
        $this->smarty->setCaching(0);

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

        $this->smarty->view( $template, $cache_id);

//        $this->smarty->clearAllCache();
    }        

}