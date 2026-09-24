<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class Configuracao extends MY_Model 
{
    
    function __construct(){
        parent::__construct();
    }

    private function load_config($conf_item = null)
    {
        $json = file_get_contents(FCPATH . 'config/config.json');
        $cobj = (object) json_decode($json);
        if($conf_item == null)
            return $cobj;
        else
            return $cobj->$conf_item;
    }
    
    public function save_config($data)
    {
        $json = json_encode($data);
        if(write_file(FCPATH . 'config/config.json', $json))
            return true;
        else
            return false;
    }
    
    public function get_config($item = null)
    {
        return $this->load_config($item);
    }
}