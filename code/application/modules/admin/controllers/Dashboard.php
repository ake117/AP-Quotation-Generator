<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Esta classe forma o conjunto de funcionalidades do Dashboard do Wpanel
 *
 * @package Wpanel
 * @author Eliel de Paula <dev@gelieldepaula.com.br>
 **/
class Dashboard extends MX_Controller {

	function __construct()
	{
            parent::__construct();
		// Protects access from the permission-independent dashboard.
		$this->auth->check_permission();
		if(auth_login_data('role') == 'user')
			redirect('users');
		$this->form_validation->set_error_delimiters('<p><span class="label label-danger">', '</span></p>');
		$this->load->model('dash');
	}

	public function index()
	{
		$layout_vars = array();
		$content_vars = array();
                // Captures the avatar of the user.

                $account_email = auth_login_data('email');
                $account_created = datetime_for_user(auth_login_data('created'), 0);

               /* $total_booking_remain = $this->dash->calculate_total_booking_remain();
                $total_reserved = $this->dash->calculate_total_reserved();
                $total_booking = $total_booking_remain + $total_reserved;
                $total_agents = $this->dash->calculate_total_agents();
                $total_agents_booking = $this->dash->calculate_total_agents_booking();*/

		/*$content_vars['account_email'] 	= $account_email;
		$content_vars['account_created'] = $account_created;
		$content_vars['total_projects'] = badge($this->dash->calculate_totals('projects'));
		$content_vars['total_members'] = badge($this->dash->calculate_totals('members'));
		$content_vars['total_booking'] = badge($total_booking);
		$content_vars['total_reserved'] = badge($total_reserved);
		$content_vars['total_booking_remain'] = badge($total_booking_remain);
		$content_vars['total_agents'] = badge($total_agents);
		$content_vars['total_agents_booking'] = badge($total_agents_booking);*/
		//----------------------------------------------------------------------------------------------------

                $template = 'dashboard/index.tpl';
                $this->smarty->view_admin( $template, $content_vars );
		//$this->wpanel->load_view('dashboard/index', $content_vars);

	}
}