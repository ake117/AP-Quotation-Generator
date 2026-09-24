<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Article extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('date');
        $this->load->library('tcdc_api');

        $this->load->config('tcdc', true);
        $this->config_tcdc =  $this->config->item('tcdc');
    }

    public function index($cat='') {
        if( $cat !== '' ){

            $cache_id = md5($cat);

            $template = 'article.tpl';
            if(!$this->smarty->isCached($template, $cache_id) ) {
                $article = $this->tcdc_api->get_article($cat);
//                echo '<pre>';
//                var_dump($article);
//                echo 'xxxxxx</pre>';
//                die();
                $cat = strtolower($cat);
                $main_menu = $GLOBALS['my_main_menu'];
                $article_title = $main_menu[$cat]['txt'];
                $article_short_desc = $main_menu[$cat]['article_short_desc'];
                $this->smarty->assign('main_menu', $main_menu);
                $this->smarty->assign('article_title', $article_title);
                $this->smarty->assign('article_short_desc', $article_short_desc);
                $this->smarty->assign('current_menu', $cat);
                $this->smarty->assign('articles', $article);

                $this->smarty->assign('title', $article_title . ' ' . $article_short_desc . ' - ' . $this->config_tcdc['default']['title_nodash']);
                $this->smarty->assign('description', $this->config_tcdc['default']['description']);
                $this->smarty->assign('keywords', $this->config_tcdc['default']['keywords']);
                $this->smarty->assign('og_url', $this->config_tcdc['default']['og_url']);
                $this->smarty->assign('og_image', $this->config_tcdc['default']['og_image']);
            }

            $this->smarty->view( $template, $cache_id );
        }
    }

    public function article_data($cat='', $article_id='') {

        if($cat !== '' && $article_id!==''){
            $cache_id = md5($cat.$article_id);
            $template = 'article_data.tpl';
            
            $this->smarty->setCaching(0);
            
            if(!$this->smarty->isCached($template, $cache_id)) {

                $article_data = $this->tcdc_api->get_article_data($article_id);

                $rel_article = $this->tcdc_api->get_article_rel($cat, $article_data->article_id, $article_data->tag);
                $pop_article = $this->tcdc_api->get_article($cat, 7, 'view', 'desc', 1, $article_id);

//                echo '<pre>';
//                var_dump($rel_article);
//                echo '</pre>';

                $this->load->library('session');
                if($this->session->has_userdata('article_intro')){
                    $article_intro = $this->session->article_intro;
                    $description = $article_intro[$article_id];
                }else{
                    $description = $article_data->article_name;
                }

                $article_create_date = ddmmyyyy_to_thai($article_data->article_create_date);

                $cat = strtolower($cat);

                $read_min = read_article($article_data->article_detail);

//                $s1 = $s2 = '';
//                split2line(30, $article_data->article_name, $s1, $s2);
                $this->smarty->assign('article_name', $article_data->article_name);
//                $this->smarty->assign('article_name1', $s1);
//                $this->smarty->assign('article_name2', $s2);
                $this->smarty->assign('main_menu', $GLOBALS['my_main_menu']);
                $this->smarty->assign('current_menu', $cat);
                $this->smarty->assign('read_min', $read_min);
                $this->smarty->assign('article_create_date', $article_create_date);
                $this->smarty->assign('content', $article_data->article_detail);
                $this->smarty->assign('rel_article', $rel_article);
                $this->smarty->assign('pop_article', $pop_article);
                $this->smarty->assign('images', $article_data->images);
                $this->smarty->assign('article_view', shorten_k($article_data->article_view));

                $this->smarty->assign('title', $article_data->article_name. ' - ' . strtoupper($cat) );
                $this->smarty->assign('description', $description);
                //$this->smarty->assign('keywords', $this->config_tcdc['article']['keywords']);

                $og_image = $this->config_tcdc['default']['og_image'];

                if(isset($article_data->images[0]) && isset($article_data->images[0]->image_url)){
                    $og_image = $article_data->images[0]->image_url;
                }

                $this->smarty->assign('og_url', base_url(). "article/{$cat}/{$article_id}/{$article_data->article_slug}" );
                $this->smarty->assign('og_image', $og_image);

            }

            $this->smarty->view($template, $cache_id);
        }
//        $this->smarty->clearAllCache();
    }



}