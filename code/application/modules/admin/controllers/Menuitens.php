<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Menuitens extends MX_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->auth->check_permission();
        $this->form_validation->set_error_delimiters('<p><span class="label label-danger">', '</span></p>');
        $this->load->model('menu_item');
    }

    public function index()
    {
        redirect('admin/menus');
    }

    public function add($menu_id)
    {

        $layout_vars = array();
        $content_vars = array();

        $this->form_validation->set_rules('label', 'Label', 'required');
        $this->form_validation->set_rules('type', 'Type', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->load->model('post');
            $this->load->model('category');
            $this->load->model('menu');
            $content_vars['menu_id'] = $menu_id;
            $content_vars['posts'] = $this->post->get_list()->result();
            $content_vars['category'] = $this->category->get_list()->result();
            $content_vars['menus'] = $this->menu->get_list()->result();
            $this->wpanel->load_view('menuitens/add', $content_vars);
        } else {
            $tipo_link = $this->input->post('type');
            $data_save = array();
            $data_save['menu_id'] = $menu_id;
            $data_save['label'] = $this->input->post('label');
            $data_save['type'] = $tipo_link;
            $data_save['slug'] = '';
            $data_save['ordem'] = $this->input->post('ordem');
            $data_save['created'] = date('Y-m-d H:i:s');
            $data_save['updated'] = date('Y-m-d H:i:s');
            // Verifica de onde vem os dados para o campo 'link'
            switch ($tipo_link)
            {
                case 'link':
                    $data_save['href'] = $this->input->post('link');
                    break;
                case 'post':
                    $data_save['href'] = $this->input->post('post_id');
                    break;
                case 'posts':
                    $data_save['href'] = $this->input->post('category_id');
                    break;
                case 'funcional':
                    $data_save['href'] = $this->input->post('funcional');
                    break;
                case 'submenu':
                    $data_save['href'] = $this->input->post('submenu');
                    break;
            }

            if ($this->menu_item->save($data_save)) {
                $this->session->set_flashdata('msg_system', 'Item de menu saved successfully..');
                redirect('admin/menus');
            } else {
                $this->session->set_flashdata('msg_system', 'Erro ao salvar o ítem de menu.');
                redirect('admin/menus');
            }
        }
    }

    public function edit($id = null)
    {
        $layout_vars = array();
        $content_vars = array();

        $this->form_validation->set_rules('label', 'Label', 'required');
        $this->form_validation->set_rules('type', "Type", 'required');

        if ($this->form_validation->run() == FALSE) {

            if ($id == null) {
                $this->session->set_flashdata('msg_system', 'Item de menu inexistente.');
                redirect('admin/menus');
            }

            $this->load->model('post');
            $this->load->model('category');
            $this->load->model('menu');

            $content_vars['posts'] = $this->post->get_list()->result();
            $content_vars['category'] = $this->category->get_list()->result();
            $content_vars['menus'] = $this->menu->get_list()->result();
            $content_vars['id'] = $id;
            $content_vars['row'] = $this->menu_item->get_by_id($id)->row();
            $this->wpanel->load_view('menuitens/edit', $content_vars);
        } else {

//            $menu_id = $this->input->post('menu_id');
            $tipo_link = $this->input->post('type');
            $data_save = array();
            $data_save['label'] = $this->input->post('label');
            $data_save['type'] = $tipo_link;
            $data_save['slug'] = '';
            $data_save['ordem'] = $this->input->post('ordem');
            $data_save['updated'] = date('Y-m-d H:i:s');

            // Verifica de onde vem os dados para o campo 'link'
            switch ($tipo_link)
            {
                case 'link':
                    $data_save['href'] = $this->input->post('link');
                    break;
                case 'post':
                    $data_save['href'] = $this->input->post('post_id');
                    break;
                case 'posts':
                    $data_save['href'] = $this->input->post('category_id');
                    break;
                case 'funcional':
                    $data_save['href'] = $this->input->post('funcional');
                    break;
                case 'submenu':
                    $data_save['href'] = $this->input->post('submenu');
                    break;
            }

            if ($this->menu_item->update($id, $data_save)) {
                $this->session->set_flashdata('msg_system', 'Item de menu saved successfully..');
                redirect('admin/menus');
            } else {
                $this->session->set_flashdata('msg_system', 'Erro ao salvar o item de menu.');
                redirect('admin/menus');
            }
        }
    }

    public function delete($id = null)
    {

        if ($id == null) {
            $this->session->set_flashdata('msg_system', 'Item de menu inexistente.');
            redirect('admin/menus');
        }

        if ($this->menu_item->delete($id)) {
            $this->session->set_flashdata('msg_system', 'Item de menu excluído com sucesso.');
            redirect('admin/menus');
        } else {
            $this->session->set_flashdata('msg_system', 'Erro ao excluir o item de menu.');
            redirect('admin/menus');
        }
    }

}
