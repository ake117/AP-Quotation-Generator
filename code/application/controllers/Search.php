<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Search extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('article_model');
        $this->load->library('tcdc_api');
        $this->smarty->setCaching(0);
        
        $this->config_tcdc =  $this->config->item('tcdc');
    }

    public function index() {
            $q = $this->input->get('q');

            $article = $this->tcdc_api->get_article_search($q);
            
//        echo '<pre>';
//        var_dump($article);
//        echo '</pre>';
//        die();
            $main_menu = $GLOBALS['my_main_menu'];
            $this->smarty->assign('main_menu', $main_menu);
            $this->smarty->assign('current_menu', 'search');
            $this->smarty->assign('keyword_search', $q);
            $this->smarty->assign('articles', $article);

            $this->smarty->assign('title', $q. ' - ' . $this->config_tcdc['default']['title_nodash']);
            $this->smarty->assign('description', $this->config_tcdc['default']['description']);
            $this->smarty->assign('keywords', $this->config_tcdc['default']['keywords']);

            $this->smarty->view( 'article_search_result.tpl' );
    }

}