<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Teams extends MX_Controller
{
    private $status_options = array(
                'active'  => 'Active',
                'inactive'  => 'Inactive'
            );
    public function __construct()
    {
        parent::__construct();
        $this->auth->check_permission();
        $this->load->model('Teams_model', 'teams');
        $this->load->helper('form');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $query = $this->teams->_get_list();
        foreach ($query as &$row)
        {
            $row->created = mdate('%d/%m/%Y', strtotime($row->update_date));
            $row->status = status_post(($row->team_status =='active')?'1':'0');
            $row->actions = div(array('class' => 'btn-group btn-group-xs')) .
                    anchor('admin/teams/edit/' . $row->team_id, glyphicon('edit'), array('class' => 'btn btn-default')) .
                    '<button class="btn btn-default" onClick="return confirm(\'' . site_url('admin/teams/delete/' . $row->team_id) . '\');">' . glyphicon('trash') . '</button>' .
                    div(null, true);

        }
        $template = 'index.tpl';
        $this->smarty->assign('teams', $query);
        $this->smarty->view_admin( $template );
    }

    public function add()
    {
        $this->form_validation->set_rules('name', 'Name', 'required');
        if ($this->form_validation->run() == FALSE){
            $template = 'add.tpl';
            $this->smarty->assign('status_options', $this->status_options);
            $this->smarty->view_admin( $template );
        } else {
            // Create the new account.
            $data = array(
                'team_name' => $this->input->post('name'),
                'team_status' => $this->input->post('status'),
                'is_active' => ($this->input->post('status') == 'active')?'Y':'N',
                'is_delete' => 'N',
                'create_date' => date('Y-m-d H:i:s')
            );
            $newaccount = $this->teams->_insert_team($data);

            if ($newaccount > 0) {
                $this->session->set_flashdata('msg_system', 'User saved successfully ..');
                redirect('admin/teams');
            } else {
                $this->session->set_flashdata('msg_system', 'Error saving user.');
                redirect('admin/teams');
            }
        }

    }

    public function edit($id = null)
    {
        $this->form_validation->set_rules('name', 'Name', 'required');
        $template = 'edit.tpl';

        if ($this->form_validation->run() == FALSE) {

            if ($id == null) {
                $this->session->set_flashdata('msg_system', 'Non-existent user.');
                redirect('admin/usuarios');
            }
            $row_team = $this->teams->_team_by_id($id);
            
            $this->smarty->assign('id', $id);
            $this->smarty->assign('team_name', $row_team->team_name );
            $this->smarty->assign('statusSelect', $row_team->team_status );
            $this->smarty->assign('status_options', $this->status_options);
            $this->smarty->view_admin( $template );

        } else {

            $data = array(
                'team_id' => $id,
                'team_name' => $this->input->post('name'),
                'team_status' => $this->input->post('status'),
                'is_active' => ($this->input->post('status') == 'active')?'Y':'N',
                'is_delete' => 'N',
                'update_date' => date('Y-m-d H:i:s')
            );
            $updatedaccount = $this->teams->_update_team($data);

            if ($updatedaccount > 0) {
                $this->session->set_flashdata('msg_system', 'User saved successfully ..');
                redirect('admin/teams');
            } else {
                $this->session->set_flashdata('msg_system', 'Erro ao salvar o usuário.');
                redirect('admin/teams');
            }
        }
    }

    public function delete($id = null)
    {
        if ($id == null) {
            $this->session->set_flashdata('msg_system', 'Conta de usuário inexistente.');
            redirect('admin/teams');
        }

        if ($this->teams->_remove_team($id)) {
            $this->session->set_flashdata('msg_system', 'Account successfully deleted.');
            redirect('admin/teams');
        } else {
            $this->session->set_flashdata('msg_system', 'Error delete account');
            redirect('admin/teams');
        }
    }

}
