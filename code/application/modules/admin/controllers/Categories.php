<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Este é o controller de category, usado principalmente
 * no painel de controle do site.
 *
 * @author Eliel de Paula <elieldepaula@gmail.com>
 * @since 21/10/2014
 */

class categories extends MX_Controller
{

    function __construct()
    {
        parent::__construct();
		$this->auth->check_permission();
		$this->form_validation->set_error_delimiters('<p><span class="label label-danger">', '</span></p>');
    }

    public function index()
    {

		$this->load->model('category');
		$this->load->library('table');

		$layout_vars = array();
		$content_vars = array();
                $posts_views = config_item('posts_views');

		// Template da tabela
		$this->table->set_template(array('table_open' => '<table id="grid" class="table table-striped">'));
		$this->table->set_heading('#', 'Title', 'Category-pai', 'Visão', 'Action');
		$query = $this->category->get_list();

		foreach ($query->result() as $row) {
			$this->table->add_row(
				$row->id,
				$row->title,
				$this->category->get_title_by_id($row->category_id),
				$posts_views[$row->view],
				div(array('class' => 'btn-group btn-group-xs')) .
				anchor('admin/categories/edit/' . $row->id, glyphicon('edit'), array('class' => 'btn btn-default')) .
				'<button class="btn btn-default" onClick="return confirm(\''.site_url('admin/categories/delete/' . $row->id).'\');">'.glyphicon('trash').'</button>' .
				div(null, true)
			);
		}

		$content_vars['listagem'] = $this->table->generate();
		$this->wpanel->load_view('category/index', $content_vars);
	}

	public function add()
    {
		$this->load->model('category');
		$layout_vars = array();
		$content_vars = array();

		$this->form_validation->set_rules('title', 'Title', 'required');

		if ($this->form_validation->run() == FALSE) {
			// Prepara a lista de category.
			$query = $this->category->get_list();
			$options = array();
			$options[0] = 'Sem category';
			foreach ($query->result() as $row) {
				$options[$row->id] = $row->title;
			}

			$content_vars['options'] = $options;
			$this->wpanel->load_view('category/add', $content_vars);

		} else {

			$data_save = array();
			$data_save['title'] = $this->input->post('title');
			$data_save['description'] = $this->input->post('description');
			$data_save['link'] = url_title(convert_accented_characters($this->input->post('title')));
			$data_save['category_id'] = $this->input->post('category_id');
			$data_save['view'] = $this->input->post('view');

			if ($this->category->save($data_save)) {
				$this->session->set_flashdata('msg_system', 'Category salva com sucesso.');
				redirect('admin/categories');
			} else {
				$this->session->set_flashdata('msg_system', 'Erro ao salvar a category.');
				redirect('admin/categories');
			}
		}
	}

	public function edit($id = null)
    {
		$this->load->model('category');
		$layout_vars = array();
		$content_vars = array();

		$this->form_validation->set_rules('title', 'Title', 'required');

		if ($this->form_validation->run() == FALSE) {

			if ($id == null) {
				$this->session->set_flashdata('msg_system', 'Category inexistente.');
				redirect('admin/categories');
			}

			// Prepara a lista de category.
			$query = $this->category->get_list();
			$options = array();
			$options[0] = 'Sem category';
			foreach ($query->result() as $row) {
				$options[$row->id] = $row->title;
			}

			$content_vars['row'] = $this->category->get_by_id($id)->row();
			$content_vars['options'] = $options;
			$this->wpanel->load_view('category/edit', $content_vars);

		} else {

			$data_save = array();
			$data_save['title'] = $this->input->post('title');
			$data_save['description'] = $this->input->post('description');
			$data_save['link'] = url_title(convert_accented_characters($this->input->post('title')));
			$data_save['category_id'] = $this->input->post('category_id');
			$data_save['view'] = $this->input->post('view');

			if ($this->category->update($id, $data_save)) {
				$this->session->set_flashdata('msg_system', 'Category salva com sucesso.');
				redirect('admin/categories');
			} else {
				$this->session->set_flashdata('msg_system', 'Erro ao salvar a category.');
				redirect('admin/categories');
			}
		}
	}

	public function delete($id = null)
    {
		if ($id == null) {
			$this->session->set_flashdata('msg_system', 'Category inexistente.');
			redirect('admin/categories');
		}
		$this->load->model('category');
		if ($this->category->delete($id)) {
			$this->session->set_flashdata('msg_system', 'Category excluída com sucesso.');
			redirect('admin/categories');
		} else {
			$this->session->set_flashdata('msg_system', 'Erro ao excluir a category.');
			redirect('admin/categories');
		}
	}
}