<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bootstrap extends CI_Controller {

    public function __construct()   {
        parent::__construct();

    }

    public function index()   {

    }

    public function getBoots()  {
        $this->load->view('samplebootstrap');
    }

}