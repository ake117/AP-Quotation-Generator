<?php
defined('BASEPATH') OR exit('No direct script access allowed');


function compress()
{
	$CI =& get_instance();
	$buffer = $CI->output->get_output();

	// Avoid corrupting inline JavaScript and tags in script blocks
	$CI->output->set_output($buffer);
	$CI->output->_display();
}