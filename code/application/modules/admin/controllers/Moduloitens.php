<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Esta é a classe do módulo de administração Moduloitens, ela foi
 * gerada automaticamente pela ferramenta Wpanel-GEN para a criação
 * de códigos padrão para o Wpanel CMS.
 *
 * @author      Eliel de Paula <dev@elieldepaula.com.br>
 * @version		0.0.1
 */
class Moduloitens extends MX_Controller {

	/**
	* Método construtor.
	*/
	function __construct()
	{
            parent::__construct();
            $this->auth->check_permission();
            $account = $this->auth->account();
            if ($account->role != 'ROOT') {
                    $this->session->flashdata('msg_system', 'Você não pode acessar este módulo.');
                    redirect('admin/dashboard');
            }
            $this->load->model('module_action');
            $this->form_validation->set_error_delimiters('<p><span class="label label-danger">', '</span></p>');
	}

	/**
	* Mostra a lista de registros.
	*
	* @return mixed
	*/
	public function index()
	{
		redirect('admin/modulos');
	}

	/**
	* Mostra o formulário e cadastra um new record.
	*
	* @return mixed
	*/
	public function add($module_id = NULL)
	{
		$layout_vars = array();
		$content_vars = array();
		$this->form_validation->set_rules('description', 'Description', 'required');
		$this->form_validation->set_rules('link', 'Link', 'required');
		if ($this->form_validation->run() == FALSE)
		{
			$content_vars['module_id'] = $module_id;
			$this->wpanel->load_view('moduloitens/add', $content_vars);
		} else {
			$data = array();
			$data['module_id'] = $module_id;
			$data['description'] = $this->input->post('description');
			$data['link'] = $this->input->post('link');
			if($this->input->post('whitelist') == '1')
				$data['whitelist'] = '1';
			else
				$data['whitelist'] = '0';
			$data['created'] = date('Y-m_d H:i:s');
			$data['updated'] = date('Y-m_d H:i:s');

			if($this->module_action->save($data))
			{
				$this->session->set_flashdata('msg_system', 'Record successfully saved');
				redirect('admin/modulos/edit/'.$module_id);
			} else {
				$this->session->set_flashdata('msg_system', 'Erro ao salvar o registro.');
				redirect('admin/modulos/edit/'.$module_id);
			}
		}
	}

	/**
	* Mostra o formulário e altera um registro.
	*
	* @param $id int Id do registro a ser editado.
	* @return mixed
	*/
	public function edit($id = NULL, $module_id = NULL)
	{
		$layout_vars = array();
		$content_vars = array();
		$this->form_validation->set_rules('description', 'Description', 'required');
		$this->form_validation->set_rules('link', 'Link', 'required');
		if ($this->form_validation->run() == FALSE)
		{
			if($id == NULL){
				$this->session->set_flashdata('msg_system', 'Registro inexistente.');
				redirect('admin/moduloitens');
			}
			$content_vars['module_id'] = $module_id;
			$content_vars['row'] = $this->module_action->get_by_id($id)->row();
			$this->wpanel->load_view('moduloitens/edit', $content_vars);
		} else {
			$data = array();
			$data['module_id'] = $module_id;
			$data['description'] = $this->input->post('description');
			$data['link'] = $this->input->post('link');
			if($this->input->post('whitelist') == '1')
				$data['whitelist'] = '1';
			else
				$data['whitelist'] = '0';
			$data['updated'] = date('Y-m_d H:i:s');

			if($this->module_action->update($id, $data))
			{
				$this->session->set_flashdata('msg_system', 'Record successfully saved');
				redirect('admin/modulos/edit/'.$module_id);
			} else {
				$this->session->set_flashdata('msg_system', 'Erro ao salvar o registro.');
				redirect('admin/modulos/edit/'.$module_id);
			}
		}
	}

	/**
	* Exclui um registro.
	*
	* @param $id int Id do registro a ser excluído.
	* @return mixed
	*/
	public function delete($id = NULL, $module_id = NULL)
	{
		if($id == null){
			$this->session->set_flashdata('msg_system', 'Registro inexistente.');
			redirect('admin/modulos');
		}
		if($this->module_action->delete($id)){
			$this->session->set_flashdata('msg_system', 'Registry successfully deleted.');
			redirect('admin/modulos/edit/'.$module_id);
		} else {
			$this->session->set_flashdata('msg_system', 'Erro ao excluir o registro.');
			redirect('admin/modulos/edit/'.$module_id);
		}
	}
}

// End of file modules/admin/controllers/Moduloitens.php