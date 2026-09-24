<?php


defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Widget Plugin Class
 * 
 * Provides a way to use widgets into WpanelCms
 * 
 * @package     WpanelCms
 * @subpackage  Libraries
 * @category    Libraries
 * @version:     0.0.1
 */
class Widget
{
	
    public $module_path;

    function __get($var) {
        global $CI;
        return $CI->$var;
    }

    function runit($file ,$param = null) 
    {        
        $args = func_get_args();
        $module = '';
        /* is module in filename? */
        if (($pos = strrpos($file, '/')) !== FALSE) {
            $module = substr($file, 0, $pos);
            $file = substr($file, $pos + 1);
        }
        list($path, $file) = Modules::find($file, $module, 'widgets/');
        if ($path === FALSE)
            $path = APPPATH.'widgets/';
        Modules::load_file($file, $path);
        $file = ucfirst($file);
        $widget = new $file($param);
        return $widget->run();
    }
} 