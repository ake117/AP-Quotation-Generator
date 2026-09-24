<?php

class LanguageLoader
 
{    
   
   function initialize() {
 
       $ci =& get_instance();
       $ci->load->helper('language');                     
              
       if ($ci->session->has_userdata('site_lang')) {
           $siteLang = $ci->session->userdata('site_lang');
           $ci->lang->load(array('information','validation'),$siteLang);
       } else {
           $ci->lang->load(array('information','validation'),'thai');
       }                 

   }

}