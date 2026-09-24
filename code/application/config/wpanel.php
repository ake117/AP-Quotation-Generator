<?php

 defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Define os idiomas disponíveis.
 */
$config['available_languages'] = array(
    'english'    => array('locale' => 'en', 'label' => 'English'),
    'thai' => array('locale' => 'pt_BR', 'label' => 'ไทย')
);

$config['available_editors'] = array('ckeditor'=>'CKEditor', 'tinymce'=>'TinyMCE');

/**
 * Define os tipos de usuário serão permitidos no site.
 */
$config['users_role'] = array('user' => 'Common user', 'admin' => 'Administrador');

/**
 * Define as views disponíveis para a exibição das listas de postagens.
 */
$config['posts_views'] = array('list' => 'Listing', 'mosaic' => 'Mosaico');

/**
 * Define as posições dos banners no site para serem listados no
 * painel de contorle.
 */
$config['banner_positions'] = array('slide' => 'Slide-Show', 'sidebar' => 'Barra lateral');

$config['published_options'] = array('N' => 'Unpublished', 'Y' => 'Published');

$config['agent_status'] = array('verify' => 'Verify', 'active' => 'Active', 'inactive' => 'Inactive');

$config['prefix'] = array('Mr.' => 'Mr.', 'Mrs.' => 'Mrs.', 'Miss' => 'Miss');

/**
 * Define os links funcionais para o gerenciador de menu.
 */
$config['functional_links'] = array (
        'home' => 'Home page',
        'contact' => 'Contact page',
        'albuns' => 'Photo Gallery',
        'videos' => 'Video Gallery',
        'events' => 'List of events',
        'pool' => 'Poll List',
        'users' => 'User Area',
        'rss' => 'RSS Page'
    );