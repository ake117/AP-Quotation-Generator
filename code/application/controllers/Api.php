<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('tcdc_api');
    }

    public function index($cat='', $article_id='') {

    }
    
    

    public function article($cat, $id='') {
        if(empty($id)){
            $article_data = $this->tcdc_api->get_article($cat, 20);
        }else{
            $article_data = $this->tcdc_api->get_article_data($id);
        }
            echo '<pre>';
            var_dump($article_data);
            echo '</pre>';
    }
    
    public function article_update($id) {
        $article_data = $this->tcdc_api->get_article_update($id);
        var_dump($article_data);
    }

    public function tag() {
        $rs = $this->tcdc_api->get_tag();
        echo '<pre>';
        var_dump($rs);
        echo '</pre>';
    }

    public function publication() {
        $publication = $this->tcdc_api->get_publication();
        echo '<pre>';
        var_dump($publication);
        echo '</pre>';
    }

    public function publication_data($id) {
        $publication_data = $this->tcdc_api->get_publication_data($id);
        echo '<pre>';
        var_dump($publication_data);
        echo '</pre>';
    }

    public function publication_data_main() {
        $this->tcdc_api->get_publication();
        $publication_data = $this->tcdc_api->get_publication_data_by_main();
        echo '<pre>';
        var_dump($publication_data);
        echo '</pre>';
    }

    public function category() {
        $article_data = $this->tcdc_api->get_category();
        echo '<pre>';
        $str = 'เมื่อ     xxx';
        $str = preg_replace('/\\s/','', $str);
        echo mb_strlen(preg_replace('/\\s/','', $str),'UTF-8');
        var_dump($article_data);
        echo '</pre>';
    }


}