<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Product_model
 *
 * @author ao
 */
class Product_model extends CI_Model {
    private $name;
    private $surname;

    public function __construct() {
        parent::__construct();
    }

    public function set_name($name='') {
        $this->name = $name;
    }

    public function set_surname($surname=''){
        $this->surname = $surname;
    }

    public function show_name() {
        return $this->name . ' ' . $this->surname;
    }

    public function plus($num1, $num2) {
        return (int)$num1 + (int)$num2;
    }
}
