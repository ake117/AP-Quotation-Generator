<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {
    private $config_tcdc;
    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('article_model');
        $this->load->library('tcdc_api');

        $this->load->config('tcdc', true);
        $this->config_tcdc =  $this->config->item('tcdc');

    }

    public function index() {
        redirect('admin');
        $cache_id = md5($_SERVER['REQUEST_URI']);
        $template = 'home.tpl';

        if(!$this->smarty->isCached($template, $cache_id)) {
            $article = $this->tcdc_api->get_article_by_main();
            $youtube = $this->tcdc_api->get_youtube();
            $publications = $this->tcdc_api->get_publication();

            $channel_id = $this->config_tcdc['y_channel_id'];
            /*
             * can't move position
             */
            //$publications_data = $this->tcdc_api->get_publication_data_by_main();


            $this->smarty->assign('main_menu', $GLOBALS['my_main_menu']);
            $this->smarty->assign('current_menu', 'home');
            $this->smarty->assign('articles', $article);
            $this->smarty->assign('youtube', $youtube);
            $this->smarty->assign('channel_id', $channel_id);

            $this->smarty->assign('publications', $publications);
            //$this->smarty->assign('publications_data', $publications_data);

            $this->smarty->assign('title', $this->config_tcdc['default']['title']);
            $this->smarty->assign('description', $this->config_tcdc['default']['description']);
            $this->smarty->assign('keywords', $this->config_tcdc['default']['keywords']);
            $this->smarty->assign('og_url', $this->config_tcdc['default']['og_url']);
            $this->smarty->assign('og_image', $this->config_tcdc['default']['og_image']);
        }

        $this->smarty->view( $template, $cache_id);

//        $this->smarty->clearAllCache();
    }

}