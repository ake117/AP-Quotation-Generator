<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Esta é a classe do módulo de administração Ipbanneds, ela foi
 * gerada automaticamente pela ferramenta Wpanel-GEN para a criação
 * de códigos padrão para o Wpanel CMS.
 *
 * @author      Eliel de Paula <dev@elieldepaula.com.br>
 * @version		0.0.1
 */
class Ipbanneds extends MX_Controller {

	/**
	* Método construtor.
	*/
	function __construct()
	{
            parent::__construct();
		$this->auth->check_permission();
		$this->load->model('ipban');
		$this->form_validation->set_error_delimiters('<p><span class="label label-danger">', '</span></p>');
	}

	/**
	* Mostra a lista de registros.
	*
	* @return mixed
	*/
	public function index()
	{
		$this->load->library('table');
		$layout_vars = array();
		$content_vars = array();
		$this->table->set_template(array('table_open'  => '<table id="grid" class="table table-striped">'));
		$this->table->set_heading('#', 'IP address', 'Data', 'Action');
		$query = $this->ipban->get_list(null, null, null, array('is_delete'=>'N'))->result();
		foreach($query as $row)
		{
			$this->table->add_row(
				$row->id, $row->ip_address, date('d/m/Y H:i:s', strtotime($row->created)),
				// Icons de ações
				div(array('class'=>'btn-group btn-group-xs')).
				'<button class="btn btn-default" onClick="return confirm(\''.site_url('admin/ipbanneds/delete/'.$row->id).'\');">'.glyphicon('trash').'</button>' .
				div(null,true)
			);
		}
		$content_vars['listagem'] = $this->table->generate();
		$this->wpanel->load_view('ipbanneds/index', $content_vars);
	}

	/**
	* Mostra o formulário e cadastra um new record.
	*
	* @return mixed
	*/
	public function add()
	{
		$layout_vars = array();
		$content_vars = array();
		$this->form_validation->set_rules('ip_address', 'IP address', 'required');
		if ($this->form_validation->run() == FALSE)
		{
			$this->wpanel->load_view('ipbanneds/add', $content_vars);
		} else {
			$data = array();
			$data['ip_address'] = $this->input->post('ip_address');
			$data['created'] = date('Y-m-d H:i:s');

			if($this->ipban->save($data))
			{
				$this->session->set_flashdata('msg_system', 'Record successfully saved');
				redirect('admin/ipbanneds');
			} else {
				$this->session->set_flashdata('msg_system', 'Erro ao salvar o registro.');
				redirect('admin/ipbanneds');
			}
		}
	}

	/**
	* Exclui um registro.
	*
	* @param $id int Id do registro a ser excluído.
	* @return mixed
	*/
	public function delete($id = null)
	{
		if($id == null){
			$this->session->set_flashdata('msg_system', 'Registro inexistente.');
			redirect('admin/ipbanneds');
		}
		if($this->ipban->delete($id)){
			$this->session->set_flashdata('msg_system', 'Registry successfully deleted.');
			redirect('admin/ipbanneds');
		} else {
			$this->session->set_flashdata('msg_system', 'Erro ao excluir o registro.');
			redirect('admin/ipbanneds');
		}
	}
}

// End of file modules/admin/controllers/Ipbanneds.php