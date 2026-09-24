<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * This class creates a new admin user if the account table accounts was empty. Usually this occurs in the
 * first run of Wpanel CMS.
 *
 * @author Eliel de Paula <dev@elieldepaula.com.br>
 * @since v1.2.2
 */
class Main extends MX_Controller
{

	var $layout_vars = array();
	var $content_vars = array();

	function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		// Check if accounts is empty.
		if ($this->auth->accounts_empty() == FALSE)
			redirect('admin/login');

		$this->form_validation->set_rules('password', wpn_lang('input_password', 'Password'), 'required');
		$this->form_validation->set_rules('name', wpn_lang('input_fullname', 'Full name'), 'required');
		$this->form_validation->set_rules('email', wpn_lang('input_validemail', 'Valid email'), 'required|valid_email');
		$this->form_validation->set_rules('agree', wpn_lang('input_agree', 'Terms of use'), 'required');

		if ($this->form_validation->run() == FALSE)
			$this->load->view('setup/index', $this->layout_vars);
		else {
			$newuser = $this->auth->create_account(
				$this->input->post('email', TRUE),
				$this->input->post('password', TRUE),
				'ROOT',
				array(
					'name' => $this->input->post('name'),
					'skin' => 'blue',
					'avatar' => ''
				)
			);
			if($newuser > 0)
			{

				// Activate the first user account.
				$this->auth->activate_account($newuser);

				$this->session->set_flashdata('msg_system', wpn_lang('first_account_success', 'Account succefull created'));
				redirect('admin/login');
			} else {
				$this->session->set_flashdata('msg_system', wpn_lang('first_account_error', 'Can´t create this account'));
				redirect('setup');
			}
		}
	}
}
