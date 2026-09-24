<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('product_model', 'product');
    }

    public function index()
    {
        $data['name'] = 'suree';
        $data['surname'] = 'rungruang';
        $this->name = 'suree';
        $this->surname = 'rungruang';
        $this->load->view('demo/header');
        $this->load->view('product/index', $data);

        echo $this->product->set_name('suree');
        echo $this->product->set_surname('rungruang');
        echo $this->product->show_name();
    }
}
