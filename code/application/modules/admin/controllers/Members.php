<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Members extends MX_Controller
{
    private $status_options = array(
                'active'  => 'Active',
                'inactive'  => 'Inactive'
            );
    public function __construct()
    {
        parent::__construct();
        $this->auth->check_permission();
        $this->load->model('Members_model', 'members');
        $this->load->helper('form');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $query = $this->members->_get_list();
        foreach ($query as &$row)
        {
            $row->created = mdate('%d/%m/%Y', strtotime($row->update_date));
            $row->status = status_post(($row->member_status =='active')?'1':'0');
            $row->actions = div(array('class' => 'btn-group btn-group-xs')) .
                    anchor('admin/members/edit/' . $row->member_id, glyphicon('edit'), array('class' => 'btn btn-default')) .
                    '<button class="btn btn-default" onClick="return confirm(\'' . site_url('admin/members/delete/' . $row->member_id) . '\');">' . glyphicon('trash') . '</button>' .
                    div(null, true);

        }
        $template = 'index.tpl';
        $this->smarty->assign('members', $query);
        $this->smarty->view_admin( $template );
    }

    public function add()
    {
        $this->form_validation->set_rules('name', 'Name', 'required');
        $this->form_validation->set_rules('full_name', 'Full Name', 'required');
        if ($this->form_validation->run() == FALSE){
            $template = 'add.tpl';
            $get_teams = $this->members->_get_teams();
            $teams = array();
            foreach ($get_teams as &$row)
            {
                $teams[$row->team_id] = $row->team_name;
            }
            $this->smarty->assign('teams_options', $teams);
            $get_jobs = $this->members->_get_jobs();
            $jobs = array();
            foreach ($get_jobs as &$row)
            {
                $jobs[$row->job_id] = $row->job_title;
            }
            $this->smarty->assign('jobs_options', $jobs);
            $this->smarty->assign('status_options', $this->status_options);
            $this->smarty->view_admin( $template );
        } else {
            // Create the new account.
            $data = array(
                'member_name' => $this->input->post('name'),
                'member_fullname' => $this->input->post('full_name'),
                'team_id' => $this->input->post('teams'),
                'job_id' => $this->input->post('jobs'),
                'member_status' => $this->input->post('status'),
                'is_active' => ($this->input->post('status') == 'active')?'Y':'N',
                'is_delete' => 'N',
                'create_date' => date('Y-m-d H:i:s')
            );
            $newaccount = $this->members->_insert_member($data);

            if ($newaccount > 0) {
                $this->session->set_flashdata('msg_system', 'User saved successfully ..');
                redirect('admin/members');
            } else {
                $this->session->set_flashdata('msg_system', 'Error saving user.');
                redirect('admin/members');
            }
        }

    }

    public function edit($id = null)
    {
        $this->form_validation->set_rules('name', 'Name', 'required');
        $this->form_validation->set_rules('full_name', 'Full Name', 'required');
        $template = 'edit.tpl';

        if ($this->form_validation->run() == FALSE) {

            if ($id == null) {
                $this->session->set_flashdata('msg_system', 'Non-existent user.');
                redirect('admin/usuarios');
            }
            $row_member = $this->members->_member_by_id($id);
            $get_teams = $this->members->_get_teams();
            $teams = array();
            foreach ($get_teams as &$row)
            {
                $teams[$row->team_id] = $row->team_name;
            }
            $this->smarty->assign('teams_options', $teams);
            $get_jobs = $this->members->_get_jobs();
            $jobs = array();
            foreach ($get_jobs as &$row)
            {
                $jobs[$row->job_id] = $row->job_title;
            }
            
            $this->smarty->assign('id', $id);
            $this->smarty->assign('member_name', $row_member->member_name );
            $this->smarty->assign('member_fullname', $row_member->member_fullname );
            $this->smarty->assign('teamsSelect', $row_member->team_id );
            $this->smarty->assign('jobsSelect', $row_member->job_id );
            $this->smarty->assign('statusSelect', $row_member->member_status );
            $this->smarty->assign('jobs_options', $jobs);
            $this->smarty->assign('status_options', $this->status_options);
            $this->smarty->view_admin( $template );

        } else {

            $data = array(
                'member_id' => $id,
                'member_name' => $this->input->post('name'),
                'member_fullname' => $this->input->post('full_name'),
                'team_id' => $this->input->post('teams'),
                'job_id' => $this->input->post('jobs'),
                'member_status' => $this->input->post('status'),
                'is_active' => ($this->input->post('status') == 'active')?'Y':'N',
                'is_delete' => 'N',
                'update_date' => date('Y-m-d H:i:s')
            );
            $updatedaccount = $this->members->_update_member($data);

            if ($updatedaccount > 0) {
                $this->session->set_flashdata('msg_system', 'User saved successfully ..');
                redirect('admin/members');
            } else {
                $this->session->set_flashdata('msg_system', 'Erro ao salvar o usuário.');
                redirect('admin/members');
            }
        }
    }

    public function delete($id = null)
    {
        if ($id == null) {
            $this->session->set_flashdata('msg_system', 'Conta de usuário inexistente.');
            redirect('admin/members');
        }

        if ($this->members->_remove_member($id)) {
            $this->session->set_flashdata('msg_system', 'Account successfully deleted.');
            redirect('admin/members');
        } else {
            $this->session->set_flashdata('msg_system', 'Error delete account');
            redirect('admin/members');
        }
    }

}
