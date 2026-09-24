<?php

function smarty_function_site_url($params,&$smarty) 
{
    //check if the needed function exists
    //otherwise try to load it
    if (!function_exists('site_url')) {
        //return error message in case we can't get CI instance
        if (!function_exists('get_instance')) return "Can't get CI instance";
        $CI= &get_instance();
        $CI->load->helper('url');
    }
    return site_url($params['url']);
}
 