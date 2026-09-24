<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Magazine extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('article_model');
        $this->load->library('tcdc_api');
        $this->config_tcdc =  $this->config->item('tcdc');
    }

    public function index() {
//        $this->smarty->caching = 1;
//        $this->smarty->assign('base_url', base_url());

        $publications = $this->tcdc_api->get_publication(8);

//        echo '<pre>';
//        var_dump($publications);
//        echo '</pre>';
        $template = 'magazine.tpl';
        if(!$this->smarty->isCached($template) ) {
            $this->smarty->assign('main_menu', $GLOBALS['my_main_menu']);
            $this->smarty->assign('current_menu', 'magazine');
            $this->smarty->assign('publications', $publications);

            $this->smarty->assign('title', $this->config_tcdc['default']['title']);
            $this->smarty->assign('description', $this->config_tcdc['default']['description']);
            $this->smarty->assign('keywords', $this->config_tcdc['default']['keywords']);
        }
        $this->smarty->view($template);

    }

    public function detail($id) {
        $this->smarty->setCaching(0);
        $publication_data = $this->tcdc_api->get_publication_data($id);

//        echo '<pre>';
//        var_dump($publications);
//        echo '</pre>';

        $this->smarty->assign('main_menu', $GLOBALS['my_main_menu']);
        $this->smarty->assign('current_menu', 'magazine');
        $this->smarty->assign('publication_data', $publication_data);

        $this->smarty->assign('title', $publication_data['publication_subject']);
        $this->smarty->assign('description', $this->config_tcdc['default']['description']);
        $this->smarty->assign('keywords', $this->config_tcdc['default']['keywords']);
        $this->smarty->assign('og_url', $publication_data['og_url']);
        $this->smarty->assign('og_image', $publication_data['og_image']);
        $this->smarty->assign('og_description', $publication_data['og_description']);

        $this->smarty->view('magazine_data.tpl');

    }

}