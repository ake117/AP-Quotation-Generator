<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Main2
 *
 * @author ao
 */
class Main extends CI_Controller{
    public function __construct() {
        parent::__construct();
    }

    public function index() {
       echo 'main controller';
    }

    public function detail($id = 0) {
       echo 'detail method: ' . $id[0];
    }

//    public function _remap($method, $params = array())
//    {
////        $this->load->model('xxx');
//            if ($method === 'detail')
//            {
//                    $this->$method($params);
//            }
//            else
//            {
//                    $this->default_method();
//            }
//    }
}
