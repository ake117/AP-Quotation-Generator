<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Resource extends CI_Controller {
    private $config_grande;
    public function __construct() {
        parent::__construct();
        
        //$this->load->config('grande', true);
        //$this->config_grande =  $this->config->item('grande');
        
    }

    public function index() {
        $cache_id = md5($_SERVER['REQUEST_URI']);
        $template = 'resource.tpl';
        
        $this->smarty->setCaching(0);

        if(!$this->smarty->isCached($template, $cache_id)) {
            
            /*
             * can't move position
             */            
            $this->smarty->assign('language', $this->lang->line('lang'));
            $this->smarty->assign('lang', $this->lang->language);            
        }

        $this->smarty->view( $template, $cache_id);

//        $this->smarty->clearAllCache();
    }        

}