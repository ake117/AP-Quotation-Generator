<?php if ( ! defined('BASEPATH')) exit('Direct access allowed');
 
class LanguageSwitcher extends CI_Controller {
 
   public function __construct() {
 
       parent::__construct();
 
   }
 
   function switchLang($language = "") {
         
       $myLanguage = array('thai', 'english');  
       if (!in_array($language,$myLanguage )) { $language = "thai"; }
       $this->session->set_userdata('site_lang', $language);
       die();
   }
 
}