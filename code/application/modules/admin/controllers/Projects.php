<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Projects extends MX_Controller
{
    private $status_options = array(
                'active'  => 'Active',
                'inactive'  => 'Inactive'
            );
    public function __construct()
    {
        parent::__construct();
        $this->auth->check_permission();
        $this->load->model('Projects_model', 'projects');
        $this->load->helper('form');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $query = $this->projects->_get_list();
        foreach ($query as &$row)
        {
            $row->created = mdate('%d/%m/%Y', strtotime($row->update_date));
            $row->status = status_post(($row->project_status =='active')?'1':'0');
            $row->actions = div(array('class' => 'btn-group btn-group-xs')) .
                    anchor('admin/projects/edit/' . $row->project_id, glyphicon('edit'), array('class' => 'btn btn-default')) .
                    '<button class="btn btn-default" onClick="return confirm(\'' . site_url('admin/projects/delete/' . $row->project_id) . '\');">' . glyphicon('trash') . '</button>' .
                    div(null, true);

        }
        $template = 'index.tpl';
        $this->smarty->assign('projects', $query);
        $this->smarty->view_admin( $template );
    }

    public function add()
    {
        $this->form_validation->set_rules('name', 'Name', 'required');
        $this->form_validation->set_rules('project_description', 'Description', 'required');
        if ($this->form_validation->run() == FALSE){
            $template = 'add.tpl';
            $get_customers = $this->projects->_get_customers();
            $customers = array();
            foreach ($get_customers as &$row)
            {
                $customers[$row->customer_id] = $row->customer_name;
            }
            $this->smarty->assign('customers_options', $customers);
            $this->smarty->assign('status_options', $this->status_options);
            $this->smarty->view_admin( $template );
        } else {
            // Create the new account.
            $data = array(
                'project_name' => $this->input->post('name'),
                'project_description' => $this->input->post('project_description'),
                'customer_id' => $this->input->post('customer_id'),
                'project_status' => $this->input->post('status'),
                'is_active' => ($this->input->post('status') == 'active')?'Y':'N',
                'is_delete' => 'N',
                'create_date' => date('Y-m-d H:i:s')
            );
            $newaccount = $this->projects->_insert_project($data);

            if ($newaccount > 0) {
                $this->session->set_flashdata('msg_system', 'User saved successfully ..');
                redirect('admin/projects');
            } else {
                $this->session->set_flashdata('msg_system', 'Error saving user.');
                redirect('admin/projects');
            }
        }

    }

    public function edit($id = null)
    {
        $this->form_validation->set_rules('name', 'Name', 'required');
        $this->form_validation->set_rules('project_description', 'Description', 'required');
        $template = 'edit.tpl';

        if ($this->form_validation->run() == FALSE) {

            if ($id == null) {
                $this->session->set_flashdata('msg_system', 'Non-existent user.');
                redirect('admin/usuarios');
            }
            $row_project = $this->projects->_project_by_id($id);
            $get_customers = $this->projects->_get_customers();
            $customers = array();
            foreach ($get_customers as &$row)
            {
                $customers[$row->customer_id] = $row->customer_name;
            }
            $this->smarty->assign('customers_options', $customers);
            
            $this->smarty->assign('id', $id);
            $this->smarty->assign('project_name', $row_project->project_name );
            $this->smarty->assign('project_description', $row_project->project_description );
            $this->smarty->assign('customersSelect', $row_project->customer_id );
            $this->smarty->assign('statusSelect', $row_project->project_status );
            $this->smarty->assign('status_options', $this->status_options);
            $this->smarty->view_admin( $template );

        } else {

            $data = array(
                'project_id' => $id,
                'project_name' => $this->input->post('name'),
                'project_description' => $this->input->post('project_description'),
                'customer_id' => $this->input->post('customer_id'),
                'project_status' => $this->input->post('status'),
                'is_active' => ($this->input->post('status') == 'active')?'Y':'N',
                'is_delete' => 'N',
                'create_date' => date('Y-m-d H:i:s')
            );
            $updatedaccount = $this->projects->_update_project($data);

            if ($updatedaccount > 0) {
                $this->session->set_flashdata('msg_system', 'User saved successfully ..');
                redirect('admin/projects');
            } else {
                $this->session->set_flashdata('msg_system', 'Erro ao salvar o usuário.');
                redirect('admin/projects');
            }
        }
    }

    public function delete($id = null)
    {
        if ($id == null) {
            $this->session->set_flashdata('msg_system', 'Conta de usuário inexistente.');
            redirect('admin/projects');
        }

        if ($this->projects->_remove_project($id)) {
            $this->session->set_flashdata('msg_system', 'Account successfully deleted.');
            redirect('admin/projects');
        } else {
            $this->session->set_flashdata('msg_system', 'Error delete account');
            redirect('admin/projects');
        }
    }

}
