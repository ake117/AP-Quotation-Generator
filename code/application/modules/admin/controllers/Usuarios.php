<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Usuarios extends MX_Controller {

	function __construct()
	{
            parent::__construct();
            $this->form_validation->set_error_delimiters('<p><span class="label label-danger">', '</span></p>');
            $this->load->model('user');
	}

	public function index()
	{
		$this->auth->protect('usuarios');
		$layout_vars = array();
		$content_vars = array();
        $roles = config_item('users_role');

		$content_vars['usuarios'] = $this->user->get_list()->result();
        $content_vars['roles'] = $roles;
		$this->wpanel->load_view('usuarios/index', $content_vars);
	}

	public function add()
	{
		$this->auth->protect('usuarios');
		$this->form_validation->set_rules('username', 'Nome de usuário', 'required');
		$this->form_validation->set_rules('password', 'Password', 'required|md5');
		$this->form_validation->set_rules('name', 'Full name', 'required');
		$this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.email]');

		if ($this->form_validation->run() == FALSE)
		{
			$layout_vars = array();
			$content_vars = array();

			$this->wpanel->load_view('usuarios/add', $content_vars);

		} else {

			$data_save = array();
			$data_save['name'] = $this->input->post('name');
			$data_save['email'] = $this->input->post('email');
			$data_save['skin'] = $this->input->post('skin');
			$data_save['image'] = $this->user->upload_media('avatar');
			$data_save['username'] = $this->input->post('username');
			$data_save['password'] = $this->input->post('password');
			$data_save['role'] = $this->input->post('role');
			$data_save['created'] = date('Y-m-d H:i:s');
			$data_save['updated'] = date('Y-m-d H:i:s');
			$data_save['status'] = $this->input->post('status');
			$data_save['permissions'] = serialize($this->input->post('permissions'));

			if($this->user->save($data_save))
			{
				$this->session->set_flashdata('msg_system', 'User saved successfully ..');
				redirect('admin/usuarios');
			} else {
				$this->session->set_flashdata('msg_system', 'Erro ao salvar o usuário.');
				redirect('admin/usuarios');
			}
		}
	}

	public function edit($id = null)
	{

		$this->auth->protect('usuarios');

		// Verifica se altera a senha
		if($this->input->post('alterar_senha') == '1'){
			$this->form_validation->set_rules('password', 'Password', 'required|md5');
		}

		$this->form_validation->set_rules('username', 'Nome de usuário', 'required');
		$this->form_validation->set_rules('name', 'Full name', 'required');
		$this->form_validation->set_rules('email', 'Email', 'required|valid_email');

		if ($this->form_validation->run() == FALSE)
		{

			if($id == null){
				$this->session->set_flashdata('msg_system', 'Usuário inexistente.');
				redirect('admin/usuarios');
			}

			$layout_vars = array();
			$content_vars = array();

			$content_vars['id'] = $id;
			$content_vars['row'] = $this->user->get_by_id($id)->row();
			$this->wpanel->load_view('usuarios/edit', $content_vars);

		} else {

			$data_save = array();
			$data_save['name'] = $this->input->post('name');
			$data_save['email'] = $this->input->post('email');
			$data_save['skin'] = $this->input->post('skin');
			$data_save['username'] = $this->input->post('username');
			$data_save['role'] = $this->input->post('role');
			$data_save['updated'] = date('Y-m-d H:i:s');
			$data_save['status'] = $this->input->post('status');
			$data_save['permissions'] = serialize($this->input->post('permissions'));

			// Verifica se altera a imagem
			if($this->input->post('alterar_imagem') == '1'){
				$query = $this->user->get_by_id($id)->row();
				$this->user->remove_media('avatar/' . $query->image);
				$data_save['image'] = $this->user->upload_media('avatar');
			}

			// Verifica se altera a senha.
			if($this->input->post('alterar_senha') == '1'){
				$data_save['password'] = $this->input->post('password');
			}

			if($this->user->update($id, $data_save))
			{
				if($this->input->post('alterar_senha') == '1'){
					redirect('admin/logout');
				} else {
					$this->session->set_flashdata('msg_system', 'User saved successfully ..');
					redirect('admin/usuarios');
				}
			} else {
				$this->session->set_flashdata('msg_system', 'Erro ao salvar o usuário.');
				redirect('admin/usuarios');
			}
		}
	}

	public function delete($id = null)
	{

		$this->auth->protect('usuarios');

		if($id == null){
			$this->session->set_flashdata('msg_system', 'Usuário inexistente.');
			redirect('admin/usuarios');
		}

		if($this->user->delete($id)){
			$this->session->set_flashdata('msg_system', 'Usuário excluído com sucesso.');
			redirect('admin/usuarios');
		} else {
			$this->session->set_flashdata('msg_system', 'Erro ao excluir o usuário.');
			redirect('admin/usuarios');
		}
	}

	public function profile()
	{

		$id = login_userobject('id');

		// Verifica se altera a senha
		if($this->input->post('alterar_senha') == '1'){
			$this->form_validation->set_rules('password', 'Password', 'required|md5');
		}

		$this->form_validation->set_rules('username', 'Nome de usuário', 'required');
		$this->form_validation->set_rules('name', 'Full name', 'required');
		$this->form_validation->set_rules('email', 'Email', 'required|valid_email');

		if ($this->form_validation->run() == FALSE)
		{

			if($id == null){
				$this->session->set_flashdata('msg_system', 'Usuário inexistente.');
				redirect('admin/dashboard');
			}

			$layout_vars = array();
			$content_vars = array();

			$content_vars['id'] = $id;
			$content_vars['row'] = $this->user->get_by_id($id)->row();
			$this->wpanel->load_view('usuarios/profile', $content_vars);

		} else {

			$data_save = array();
			$data_save['name'] = $this->input->post('name');
			$data_save['email'] = $this->input->post('email');
			$data_save['skin'] = $this->input->post('skin');
			$data_save['username'] = $this->input->post('username');
			$data_save['updated'] = date('Y-m-d H:i:s');

			// Verifica se altera a imagem
			if($this->input->post('alterar_imagem') == '1'){
				$query = $this->user->get_by_id($id)->row();
				$this->user->remove_media('avatar/' . $query->image);
				$data_save['image'] = $this->user->upload_media('avatar');
			}

			// Verifica se altera a senha.
			if($this->input->post('alterar_senha') == '1'){
				$data_save['password'] = $this->input->post('password');
			}

			if($this->user->update($id, $data_save))
			{
				if($this->input->post('alterar_senha') == '1'){
					redirect('admin/logout');
				} else {
					$this->session->set_flashdata('msg_system', 'Your data has been successfully saved.');
					redirect('admin/usuarios/profile');
				}
			} else {
				$this->session->set_flashdata('msg_system', 'Erro ao salvar os seus dados.');
				redirect('admin/usuarios/profile');
			}
		}
	}
}