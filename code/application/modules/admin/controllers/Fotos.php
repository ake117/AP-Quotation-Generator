<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Fotos extends MX_Controller
{

	function __construct()
	{
            parent::__construct();
		$this->auth->check_permission();
		$this->form_validation->set_error_delimiters('<p><span class="label label-danger">',
			'</span></p>');
	}

	public function index($album_id)
	{
		$this->load->model('album');
		$this->load->model('foto');
		$this->load->library('table');

		$layout_vars = array();
		$content_vars = array();

		// Dados do álbum
		// $query_album = $this->album->get_by_id($album_id)->row();

		// Template da tabela
		$this->table->set_template(
			array('table_open'  => '<table id="grid" class="table table-striped">')
		);
		$this->table->set_heading('#', 'Imagem', 'Description', 'Data', 'Status', 'Action');
		$query = $this->foto->get_by_field(
			'album_id',
			$album_id,
			array('field'=>'created', 'order'=>'desc')
		);

		foreach($query->result() as $row)
		{

			$capa_properties = array(
            	'src' => base_url() . '/media/albuns/'.$album_id . '/' . $row->filename,
            	'class' => 'img-responsive',
            	'width' => '120',
            	'alt' => $row->descricao
            );
            $imagem = img($capa_properties);

			$this->table->add_row(
				$row->id,
				$imagem,
				$row->descricao,
				mdate('%d/%m/%Y - %H:%i', strtotime($row->created)),
				status_post($row->status),
				div(array('class'=>'btn-group btn-group-xs')).
				anchor('admin/fotos/edit/'.$row->id, glyphicon('edit'),
					array('class' => 'btn btn-default')).
				'<button class="btn btn-default" onClick="return confirm(\'' .
					site_url('admin/fotos/delete/' . $row->id) . '\');">' .
			glyphicon('trash').'</button>' .
				div(null,true)
				);
		}

		$content_vars['album_id'] = $album_id;
		$content_vars['listagem'] = $this->table->generate();
		$this->wpanel->load_view('fotos/index', $content_vars);
	}

	public function add($album_id)
	{
		$layout_vars = array();
		$content_vars = array();

		$this->form_validation->set_rules('descricao', 'Foto', 'required');

		if ($this->form_validation->run() == FALSE)
		{
			$content_vars['album_id'] = $album_id;
			$this->wpanel->load_view('fotos/add', $content_vars);
		} else {

			$this->load->model('foto');

			$data_save = array();
			$data_save['album_id'] = $album_id;
			$data_save['descricao'] = $this->input->post('descricao');
			$data_save['status'] = $this->input->post('status');
			$data_save['created'] = date('Y-m-d H:i:s');
			$data_save['updated'] = date('Y-m-d H:i:s');
			$data_save['filename'] = $this->foto->upload_media('albuns/'.$album_id);

			$new_post = $this->foto->save($data_save);

			if($new_post)
			{
				$this->session->set_flashdata('msg_system', 'Foto salva com sucesso.');
				redirect('admin/fotos/index/'.$album_id);
			} else {
				$this->session->set_flashdata('msg_system', 'Erro ao salvar a foto.');
				redirect('admin/fotos/index/'.$album_id);
			}
		}
	}

  public function addmass($album_id = null)
  {

    if($album_id == null)
    {
      $this->session->set_flashdata('msg_system', 'Álbum de fotos inexistente.');
      redirect('admin/fotos/index/'.$album_id);
    }

    $layout_vars = array();
    $content_vars = array();

    $this->form_validation->set_rules('descricao', 'Foto', 'required');

    if ($this->form_validation->run() == FALSE)
    {
      $content_vars['album_id'] = $album_id;
      $this->wpanel->load_view('fotos/addmass', $content_vars);
    } else {

      $this->load->model('foto');

      /* Faz o laço do upload */
      $pasta = FCPATH.'media/albuns/'.$album_id.'/';
      $fotos = $_FILES['fotos'];

      for($i = 0; $i < sizeof($fotos['name']); $i++)
      {

        $name = $album_id . '_' . time() . '_' . str_replace(array(' ', ',', '-'), '',
        	$fotos["name"][$i]);
        $tmpname = $fotos["tmp_name"][$i];
        $caminho = $pasta . $name;

        //TODO Fazer um type de validação aqui...
        if(move_uploaded_file($tmpname, $caminho))
        {

          $data_save = array();
          $data_save['album_id'] = $album_id;
          $data_save['descricao'] = $this->input->post('descricao');
          $data_save['status'] = $this->input->post('status');
          $data_save['created'] = date('Y-m-d H:i:s');
          $data_save['updated'] = date('Y-m-d H:i:s');
          $data_save['filename'] = $name;

          $uploads = $this->foto->save($data_save);

        } else {
          $this->session->set_flashdata('msg_system', 'No foi possível enviar as fotos.');
          redirect('admin/fotos/index/'.$album_id);
        }
      }
      /* Fim do laço do upload */

      $this->session->set_flashdata('msg_system', 'Fotos salvas com sucesso.');
      redirect('admin/fotos/index/'.$album_id);

    }
  }

	public function edit($id = null)
	{
		$layout_vars = array();
		$content_vars = array();

		$this->load->model('foto');
		$row = $this->foto->get_by_id($id)->row();

		$this->form_validation->set_rules('descricao', 'Description', 'required');

		if ($this->form_validation->run() == FALSE)
		{

			if($id == null){
				$this->session->set_flashdata('msg_system', 'Foto inexistente.');
				redirect('admin/albuns');
			}

			$content_vars['id'] = $id;
			$content_vars['row'] = $row;
			$this->wpanel->load_view('fotos/edit', $content_vars);

		} else {

			$data_save['descricao'] = $this->input->post('descricao');
			$data_save['status'] = $this->input->post('status');
			$data_save['updated'] = date('Y-m-d H:i:s');

			if($this->input->post('alterar_imagem')=='1')
			{
				$query = $this->foto->get_by_id($id)->row();
				$this->foto->remove_media('albuns/' . $query->album_id . '/' . $query->filename);
				$data_save['filename'] = $this->foto->upload_media('albuns/' . $query->album_id . '/');
			}

			$new_post = $this->foto->update($id, $data_save);

			if($new_post)
			{
				$this->session->set_flashdata('msg_system', 'Foto salva com sucesso.');
				redirect('admin/fotos/index/'.$row->album_id);
			} else {
				$this->session->set_flashdata('msg_system', 'Erro ao salvar a foto.');
				redirect('admin/fotos/index/'.$row->album_id);
			}
		}
	}

	public function delete($id = null)
	{

		if($id == null){
			$this->session->set_flashdata('msg_system', 'Foto inexistente.');
			redirect('admin/albuns');
		}

		$this->load->model('foto');
		$qry_foto = $this->foto->get_by_id($id)->row();
		$this->foto->remove_media('albuns/' . $qry_foto->album_id . '/' . $qry_foto->filename);

		if($this->foto->delete($id)){
			$this->session->set_flashdata('msg_system', 'Foto excluída com sucesso.');
			redirect('admin/fotos/index/'.$qry_foto->album_id);
		} else {
			$this->session->set_flashdata('msg_system', 'Erro ao excluir a foto.');
			redirect('admin/fotos/index/'.$qry_foto->album_id);
		}
	}
}
