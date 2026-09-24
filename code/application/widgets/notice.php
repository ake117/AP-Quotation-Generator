<?php


defined('BASEPATH') OR exit('No direct script access allowed');

class Notice extends Widget
{

    public function run()
    {
        $data = $this->session->flashdata('_notice');
        if (count($data))
            $this->load->view('widgets/notice', $data);
    }

}
