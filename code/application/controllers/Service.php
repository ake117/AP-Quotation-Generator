<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Service extends CI_Controller {

    public function __construct()   {
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('date');
        $this->load->library('tcdc_api');
        $this->smarty->setCaching(0);
    }

    public function index()   {

    }

    public function load_more(){
        $cat = $this->input->post('cat');
        $current_page = $this->input->post('current_page');
        if( $cat !== '' ){
            $article = $this->tcdc_api->get_article($cat, 12, 'date', 'desc', ++$current_page);
//            var_dump($article);
            $this->smarty->assign('articles', $article);
            $this->smarty->assign('base_url', trim( base_url(), '/' ));
            $articles['result'] = $this->smarty->fetch( 'includes/load_more.tpl' );
            $articles['current_page'] = $article['current_page'];
            $articles['next_page_url'] = $article['next_page_url'];
            echo json_encode($articles);
        }
    }

    public function search_load_more(){
        $next_page_url = $this->input->post('next_page_url');

        if(!empty($next_page_url)){
            $article = $this->tcdc_api->get_article_search('', $next_page_url);

            $this->smarty->assign('articles', $article);
            $this->smarty->assign('base_url', trim( base_url(), '/' ));
            $articles['result'] = $this->smarty->fetch( 'includes/search_load_more.tpl' );
            $articles['current_page'] = $article['current_page'];
            $articles['next_page_url'] = $article['next_page_url'];

            echo json_encode($articles);
        }
    }

    public function magazine_load_more(){

            $current_page = $this->input->post('current_page');

            $publication = $this->tcdc_api->get_publication(8, 'active_from', 'desc', ++$current_page);

            $this->smarty->assign('publications', $publication);
            $this->smarty->assign('base_url', trim( base_url(), '/' ));
            $publications['result'] = $this->smarty->fetch( 'includes/magazine_load_more.tpl' );
            $publications['current_page'] = $publication['current_page'];
            $publications['next_page_url'] = $publication['next_page_url'];
            echo json_encode($publications);

    }
    
    
    

}