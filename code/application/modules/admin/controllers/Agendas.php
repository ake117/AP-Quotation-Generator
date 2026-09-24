<?php

defined('BASEPATH') OR exit('No direct script access allowed');


class Agendas extends MX_Controller {

	function __construct()
	{
            parent::__construct();
		$this->auth->check_permission();
		$this->form_validation->set_error_delimiters('<p><span class="label label-danger">', '</span></p>');
	}

	public function index()
	{
		$this->load->model('post');
		$this->load->library('table');

		$layout_vars = array();
		$content_vars = array();

		// Template da tabela
		$this->table->set_template(array('table_open'  => '<table id="grid" class="table table-striped">'));
		$this->table->set_heading('#', 'Title', 'Data', 'Status', 'Action');
		$query = $this->post->get_by_field('page','2', array('field'=>'created','order'=>'desc'));//, array('offset'=>'0','limit'=>'2'));

		foreach($query->result() as $row)
		{
			$this->table->add_row(
				$row->id,
				$row->title,
				datetime_for_user($row->created, false),
				status_post($row->status),
				// Icons de ações
				div(array('class'=>'btn-group btn-group-xs')).
				anchor('admin/agendas/edit/'.$row->id, glyphicon('edit'), array('class' => 'btn btn-default')).
				'<button class="btn btn-default" onClick="return confirm(\''.site_url('admin/agendas/delete/'.$row->id).'\');">'.glyphicon('trash').'</button>' .
				div(null,true)

				);
		}

		$content_vars['listagem'] = $this->table->generate();

		$this->wpanel->load_view('agendas/index', $content_vars);
	}

	public function add()
	{

		$layout_vars = array();
		$content_vars = array();

		$this->form_validation->set_rules('title', 'Title', 'required');
		$this->form_validation->set_rules('created', 'Data', 'required');

		if ($this->form_validation->run() == FALSE)
		{
			$this->load->model('category');
			// Prepara a lista de category.
			$query = $this->category->get_list();
			$category = array();
			foreach($query->result() as $row){
				$category[$row->id] = $row->title;
			}

			$content_vars['category'] = $category;

			$this->wpanel->load_view('agendas/add', $content_vars);

		} else {

			$this->load->model('post');

			$data_save = array();
			$data_save['user_id'] = auth_login_data('id');
			$data_save['title'] = $this->input->post('title');
			$data_save['description'] = $this->input->post('description');
			$data_save['link'] = strtolower(url_title(convert_accented_characters($this->input->post('title'))));
			$data_save['content'] = $this->input->post('content');
			$data_save['tags'] = $this->input->post('tags');
			$data_save['status'] = $this->input->post('status');
			$data_save['created'] = datetime_for_mysql($this->input->post('created') . ' 12:00:00'); //date('Y-m-d H:i:s', strtotime($this->input->post('created')));
			$data_save['updated'] = date('Y-m-d H:i:s');
			$data_save['image'] = $this->post->upload_media('capas', '*', 'userfile', date('YmdHis'));
			// Identifica se é uma página ou uma postagem
			// 0=post, 1=Página, 2=Agenda
			$data_save['page'] = '2';

			$new_post = $this->post->save($data_save);

			if($new_post)
			{
				$this->session->set_flashdata('msg_system', 'Agenda salva com sucesso.');
				redirect('admin/agendas');
			} else {
				$this->session->set_flashdata('msg_system', 'Erro ao salvar a agenda.');
				redirect('admin/agendas');
			}
		}
	}

	public function edit($id = null)
	{
		$layout_vars = array();
		$content_vars = array();

		$this->form_validation->set_rules('title', 'Title', 'required');
		$this->form_validation->set_rules('created', 'Data', 'required');

		if ($this->form_validation->run() == FALSE)
		{

			if($id == null){
				$this->session->set_flashdata('msg_system', 'Agenda inexistente.');
				redirect('admin/posts');
			}

			$this->load->model('post_category');
			$this->load->model('category');
			$this->load->model('post');

			// Prepara a lista de category.
			$query = $this->category->get_list();
			$category = array();
			foreach($query->result() as $row){
				$category[$row->id] = $row->title;
			}

			// Prepara as category selecionadas.
			$query = $this->post_category->get_by_field('post_id', $id);
			$cat_select = array();
			foreach($query->result() as $x => $row){
				$cat_select[$x] = $row->category_id;
			}

			$content_vars['id'] = $id;
			$content_vars['category'] = $category;
			$content_vars['cat_select'] = $cat_select;
			$content_vars['row'] = $this->post->get_by_id($id)->row();

			$this->wpanel->load_view('agendas/edit', $content_vars);

		} else {

			$this->load->model('post');

			$data_save = array();
			$data_save['title'] = $this->input->post('title');
			$data_save['description'] = $this->input->post('description');
			$data_save['link'] = strtolower(url_title(convert_accented_characters($this->input->post('title'))));
			$data_save['content'] = $this->input->post('content');
			$data_save['tags'] = $this->input->post('tags');
			$data_save['status'] = $this->input->post('status');
			$data_save['created'] = datetime_for_mysql($this->input->post('created') . ' 12:00:00');//date('Y-m-d H:i:s', strtotime($this->input->post('created')));
			$data_save['updated'] = date('Y-m-d H:i:s');
			// Identifica se é uma página ou uma postagem
			// 0=post, 1=Página, 2=Agenda
			$data_save['page'] = '2';

			if($this->input->post('alterar_imagem')=='1')
			{
				$postagem = $this->post->get_by_id($id)->row();
				$this->post->remove_media('capas/' . $postagem->image);
				$data_save['image'] = $this->post->upload_media('capas', '*', 'userfile', date('YmdHis'));
			}

			$upd_post = $this->post->update($id, $data_save);

			if($upd_post)
			{
				$this->session->set_flashdata('msg_system', 'Agenda salva com sucesso.');
				redirect('admin/agendas');
			} else {
				$this->session->set_flashdata('msg_system', 'Erro ao salvar a agenda.');
				redirect('admin/agendas');
			}
		}
	}

	public function delete($id = null)
	{

		if($id == null){
			$this->session->set_flashdata('msg_system', 'Agenda inexistente.');
			redirect('admin/agendas');
		}

		$this->load->model('post');

		$postagem = $this->post->get_by_id($id)->row();
		$this->post->remove_media('capas/' . $postagem->image);

		if($this->post->delete($id)){
			$this->session->set_flashdata('msg_system', 'Agenda excluída com sucesso.');
			redirect('admin/agendas');
		} else {
			$this->session->set_flashdata('msg_system', 'Erro ao excluir a agenda.');
			redirect('admin/agendas');
		}
	}
}
