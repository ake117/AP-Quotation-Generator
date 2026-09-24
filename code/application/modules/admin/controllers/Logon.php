<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * This class executes the authentication functions into the Wpanel CMS.
 *
 * @package Wpanel
 * @author Eliel de Paula <dev@gelieldepaula.com.br>
 * @since v1.2.2 12/09/2015
 * @version 2.0.0
 */
class Logon extends MX_Controller {

	function __construct()
	{
		parent::__construct();
	}

	public function index()
	{

		if ($this->auth->accounts_empty() == TRUE)
			redirect('setup');

		$this->form_validation->set_rules('email', wpn_lang('input_email', 'Email'), 'required|valid_email');
		$this->form_validation->set_rules('password', wpn_lang('input_password', 'Password'), 'required');

		if ($this->form_validation->run() == FALSE)
			$this->load->load->view('logon/index');
		else {
			if($this->auth->login($this->input->post('email'), $this->input->post('password')))
				return redirect('admin/quotationAP');
			else {
				$this->session->set_flashdata('msg_auth', wpn_lang('msg_login_error', 'Your login failed, try again.'));
				return redirect('admin/login');
			}
		}
	}

	/**
	 * This method does the logout of the user account.
	 *
	 * @return void
	 */
	public function out()
	{
		$this->auth->logout();
		redirect('admin/login');
	}

	/**
	 * This method performs the password recovery procedure for wpanel users.
	 *
	 * @return void
	 * @author Eliel de Paula <elieldepaula@gmail.com>
	 **/
	public function recovery($recovery_key = null)
	{
		$this->load->library('email');

		if ($this->wpanel->get_config('use_smtp') == 1)
		{
			$conf_email = array();
			$conf_email['protocol'] = 'smtp';
			$conf_email['smtp_host'] = $this->wpanel->get_config('smtp_host');
			$conf_email['smtp_port'] = $this->wpanel->get_config('smtp_port');
			$conf_email['smtp_user'] = $this->wpanel->get_config('smtp_user');
			$conf_email['smtp_pass'] = $this->wpanel->get_config('smtp_password');
			$this->email->initialize($conf_email);
		}

		$this->form_validation->set_rules('email', 'Email', 'required|valid_email');

		if ($this->form_validation->run() == FALSE)
		{
			if($recovery_key == null)
				$this->load->view('logon/recovery');
			else {
                                $this->load->model('account');
                                $recovery_key = str_repeat('=', strlen($recovery_key) % 4).$recovery_key; /*fix url disable character = */
                                $key	= explode(',', base64_decode(strrev($recovery_key)));
                                $id		= $key[0];
                                $name	= $key[1];
                                $email	= $key[2];
                                $password  = md5('123mudar');

                                $data_save = array();
                                $data_save['password'] = $password;

                                if($this->account->update($id, $data_save))
                                {
                                        $message = "";
                                        $message .= "Hello ".$name.", this is an automated message, do not reply to this email. <br/><br/>";
                                        $message .= "Your password to access the control panel has been successfully reset! ";
                                        $message .= "Write down your new data: <br/><br/>";
                                        $message .= "Email: ".$email." <br/>";
                                        $message .= "Password: 123mudar <br/><br/>";
                                        $message .= "RESPECT!!!<br/><br/>Change the password immediately after login, this is a temporary password and "
                                                . "if not changed your access will be revoked. <br/><br/>";

                                        $this->email->to($email);
                                        $this->email->from($this->wpanel->get_config('site_contact'), $this->wpanel->get_config('site_title'));
                                        $this->email->subject('Password reset');
                                        $this->email->message($message);
                                        $this->email->send();

                                        $this->session->set_flashdata('msg_auth', 'Your password has been reset and we will send your new access data to your email in case you do not receive your SPAM box.');
                                        redirect('admin/login');
                                } else {
                                        $this->session->set_flashdata('msg_recover', 'There was a problem and your password can not be reset, please contact us by email <b>'.$this->wpanel->get_config('site_contact').'</b>.');
                                        redirect('admin/recovery');
                                }
			}
		} else {

			$this->load->model('account');

			$query = $this->account->get_by_field('email', $this->input->post('email', true))->row();

			if(count($query) >= 1)
			{
                            $name = $this->auth->get_extra_data('name', $query->extra_data);

				$recovery_key	= strrev(base64_encode($query->id.','.$name.','.$query->email));
                                $recovery_key = ltrim($recovery_key, '='); /*fix url disable character = */
				$recovery_link	= base_url('admin/recovery').'/'.$recovery_key;

				$message = "";
				$message .= "Hello ".$name.", this is an automated message, do not reply to this email. <br/><br/>";
				$message .= "We received a password reset request from your control panel on our site. To complete the reset click the link below: <br/><br/>";
				$message .= "Link: " . $recovery_link." <br/><br/>";
				$message .= "If you have not requested to reset your password, please ignore this message. <br/><br/>";
				$message .= "Date / Time of request: ".date('d/m/Y H:i')."<br/>";
				$message .= "Connection IP: " . $_SERVER['REMOTE_ADDR']." <br/><br/>";

				$this->email->to($query->email);
				$this->email->from($this->wpanel->get_config('site_contact'), $this->wpanel->get_config('site_title'));
				$this->email->subject('Password Reset');
				$this->email->message($message);
				$this->email->send();

				$this->session->set_flashdata('msg_auth', 'We send instructions to reset your password in your email informed in the register.');
				redirect('admin/login');
			} else {
				$this->session->set_flashdata('msg_recover', 'User not exist.');
				redirect('admin/recovery');
			}
		}
	}
}