<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class Erros extends MX_Controller
{

	public function index()
	{
		echo "Shows site errors";
	}

	public function err_404()
	{
		$this->load->view('error_404');
	}

}