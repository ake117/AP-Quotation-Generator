<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Service extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->auth->check_permission();
    }

    public function get_floor_option($project_id){

        $rs = $this->db->select('floor_id,floor_name_th')
                        ->where('project_id', $project_id)->get('floor')->result();
        $options = '';

        foreach ($rs as $row) {
            $options .= "<option value='{$row->floor_id}' >{$row->floor_name_th}</option>";
        }

        $data['options'] = $options;
        header('Content-Type: application/json');
        echo json_encode($data);
    }
}