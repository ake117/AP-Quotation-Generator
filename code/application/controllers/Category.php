<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Category extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
    }

    public function index() {
//        $this->smarty->caching = 1;
//        $this->smarty->assign('base_url', base_url());


        $article = $this->tcdc_api->get_article_by_main();
        $youtube = $this->tcdc_api->get_youtube();
        $publications = $this->tcdc_api->get_publication();

//        echo '<pre>';
//        var_dump($publications_data);
//        echo '</pre>';

        $this->smarty->assign('main_menu', $GLOBALS['my_main_menu']);
        $this->smarty->assign('current_menu', 'home');
        $this->smarty->assign('articles', $article);
        $this->smarty->assign('youtube', $youtube);

        $this->smarty->assign('publications', $publications);
        $this->smarty->assign('publications_data', $publications_data);

    }

}