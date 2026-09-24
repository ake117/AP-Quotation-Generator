<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Accounts extends MX_Controller
{
    private $skin_options = array(
                'black'  => 'Black',
                'black-light'  => 'Black-Light',
                'blue'  => 'Blue',
                'blue-light'  => 'Blue-Light',
                'green'  => 'Green',
                'green-light'  => 'Green-Light',
                'purple'  => 'Purple',
                'purple-light'  => 'Purple-Light',
                'red'  => 'Red',
                'red-light'  => 'Red-Light',
                'yellow'  => 'Yellow',
                'yellow-light'  => 'Yellow-Light'
            );
    public function __construct()
    {
        parent::__construct();
        $this->auth->check_permission();
    }

    public function index()
    {
        $query = $this->auth->list_accounts();
        foreach ($query as &$row)
        {
            $row->name = $this->auth->get_extra_data('name', $row->extra_data);
            $row->created = mdate('%d/%m/%Y', strtotime($row->created));
            $row->status = status_post($row->status);
            $row->actions = div(array('class' => 'btn-group btn-group-xs')) .
                    anchor('admin/accounts/edit/' . $row->id, glyphicon('edit'), array('class' => 'btn btn-default')) .
                    '<button class="btn btn-default" onClick="return confirm(\'' . site_url('admin/accounts/delete/' . $row->id) . '\');">' . glyphicon('trash') . '</button>' .
                    div(null, true);

        }
        $template = 'index.tpl';
        $this->smarty->assign('accounts', $query);
        $this->smarty->view_admin( $template );
    }

    public function check_auth_account_role()
    {
        $get_account = $this->auth->get_account_by_id($this->auth->get_account_id());
        $auth_account_role = config_item('auth_account_role');
        if($get_account->role != 'ROOT'){
            unset($auth_account_role['ROOT']);
        }
        return $auth_account_role;
    }

    public function add()
    {
        $this->form_validation->set_rules('password', 'Password', 'required');
        $this->form_validation->set_rules('name', 'Full name', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[cms_accounts.email]');
        if ($this->form_validation->run() == FALSE){
            $template = 'add.tpl';
            $auth_account_role = $this->check_auth_account_role();
            $this->smarty->assign('auth_account_role', $auth_account_role);
            $this->smarty->assign('skin_options', $this->skin_options);
            $this->smarty->assign('query_module', $this->auth->list_modules_full());
            $this->smarty->view_admin( $template );
        } else {
            // Create the new account.
            $newaccount = $this->auth->create_account(
                $this->input->post('email'),
                $this->input->post('password'),
                $this->input->post('role'),
                array(
                    'name' => $this->input->post('name'),
                    'skin' => $this->input->post('skin'),
                    'avatar' => $this->auth->upload_avatar()
                ),
                $this->input->post('permission')
            );

            if ($newaccount > 0) {
                $this->session->set_flashdata('msg_system', 'User saved successfully ..');
                redirect('admin/accounts');
            } else {
                $this->session->set_flashdata('msg_system', 'Error saving user.');
                redirect('admin/accounts');
            }
        }

    }

    public function edit($id = null)
    {
        $this->form_validation->set_rules('name', 'Full name', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $template = 'edit.tpl';

        if ($this->form_validation->run() == FALSE) {

            if ($id == null) {
                $this->session->set_flashdata('msg_system', 'Non-existent user.');
                redirect('admin/usuarios');
            }

            $row_account = $this->auth->get_account_by_id($id);
            $query_module = $this->auth->list_modules_full();

            $row_account->name = auth_extra_data('name', $row_account->extra_data);
            $row_account->skin_selected = auth_extra_data('skin', $row_account->extra_data);
            $row_account->avatar = auth_extra_data('avatar', $row_account->extra_data);

            $auth_account_role = $this->check_auth_account_role();

            //echo '<pre>';print_r($auth_account_role);echo '</pre>';
            $this->smarty->assign('query_module', $query_module);
            $this->smarty->assign('row_account', $row_account);
            $this->smarty->assign('skin_options', $this->skin_options);
            $this->smarty->assign('role_selected', $row_account->role );
            $this->smarty->assign('auth_account_role', $auth_account_role);
            $this->smarty->view_admin( $template );

        } else {

            $extra_data = array();
            $extra_data['name'] = $this->input->post('name');
            $extra_data['skin'] = $this->input->post('skin');
            if($this->input->post('change_avatar'))
                $extra_data['avatar'] = $this->auth->upload_avatar();
            else
                $extra_data['avatar'] = $this->input->post('avatar');

            // Atualiza uma conta.
            $updatedaccount = $this->auth->update_account(
                $id,
                $this->input->post('email'),
                $this->input->post('role'),
                $extra_data,
                $this->input->post('permission')
            );

            if ($updatedaccount > 0) {
                $this->session->set_flashdata('msg_system', 'User saved successfully ..');
                redirect('admin/accounts');
            } else {
                $this->session->set_flashdata('msg_system', 'Erro ao salvar o usuário.');
                redirect('admin/accounts');
            }
        }
    }

    public function changepassword($account_id = NULL)
    {
        if($account_id == NULL){
            $this->session->set_flashdata('msg_system', 'Could not change password.');
            redirect('admin/accounts');
        }

        $updatedpassword = $this->auth->change_password(
            $account_id,
            NULL,
            $this->input->post('password', TRUE)
        );

        if ($updatedpassword > 0) {
            $this->session->set_flashdata('msg_system', 'Password changed successfully.');
            redirect('admin/accounts/edit/'.$account_id);
        } else {
            $this->session->set_flashdata('msg_system', 'Error changing password.');
            redirect('admin/accounts/edit/'.$account_id);
        }
    }

    public function delete($id = null)
    {
        if ($id == null) {
            $this->session->set_flashdata('msg_system', 'Conta de usuário inexistente.');
            redirect('admin/accounts');
        }

        if ($this->auth->remove_account($id)) {
            $this->session->set_flashdata('msg_system', 'Account successfully deleted.');
            redirect('admin/accounts');
        } else {
            $this->session->set_flashdata('msg_system', 'Error delete account');
            redirect('admin/accounts');
        }
    }

    public function profile()
    {
        // Verifica se altera a senha
        if ($this->input->post('alterar_senha') == '1'){
            $this->form_validation->set_rules('password', 'Password', 'required');
        }

        $this->form_validation->set_rules('name', 'Full name', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');

        if ($this->form_validation->run() == FALSE) {
//            $content_vars = array();
//            $account_data = $this->auth->get_account_by_id($this->auth->get_account_id());
//            if ($account_data->id == null) {
//                $this->session->set_flashdata('msg_system', 'Usuário inexistente.');
//                redirect('admin/dashboard');
//            }
//            $content_vars['row'] = $account_data;
//            $content_vars['extra_data'] = $this->auth->get_extra_data(NULL, $account_data->extra_data);
//            $this->wpanel->load_view('accounts/profile', $content_vars);

            $row_account = $this->auth->get_account_by_id($this->auth->get_account_id());
            $row_account->name = auth_extra_data('name', $row_account->extra_data);
            $row_account->skin_selected = auth_extra_data('skin', $row_account->extra_data);
            $row_account->avatar = auth_extra_data('avatar', $row_account->extra_data);

            $this->smarty->assign('row_account', $row_account);
            $this->smarty->assign('skin_options', $this->skin_options);
            $this->smarty->view_admin( 'profile.tpl' );

        } else {
            $extra_data = array();
            $extra_data['name'] = $this->input->post('name');
            $extra_data['skin'] = $this->input->post('skin');
            if($this->input->post('change_avatar') == '1'){
                $extra_data['avatar'] = $this->auth->upload_avatar();
            }
            else{
                $extra_data['avatar'] = $this->input->post('avatar');
            }

            $updatedaccount = $this->auth->update_account(
                $this->auth->get_account_id(),
                $this->input->post('email'),
                $this->input->post('role'),
                $extra_data
            );

            if ($updatedaccount > 0) {
                if ($this->input->post('change_password') == '1') {
                    $updatedpassword = $this->auth->change_password(
                        $this->auth->get_account_id(),
                        NULL,
                        $this->input->post('password', TRUE)
                    );
                    redirect('admin/logout');
                } else {
                    $this->session->set_flashdata('msg_system', 'Your data has been successfully saved.');
                    redirect('admin/accounts/profile');
                }
            } else {
                $this->session->set_flashdata('msg_system', 'Error saving your data.');
                redirect('admin/accounts/profile');
            }
        }
    }

    public function activate($account_id = NULL)
    {
        if($this->auth->activate_account($account_id)){
            $this->session->set_flashdata('msg_system', 'Account activated successfully.');
            redirect('admin/accounts/edit/'.$account_id);
        } else {
            $this->session->set_flashdata('msg_system', 'Could not activate account.');
            redirect('admin/accounts/edit/'.$account_id);
        }
    }

    public function deactivate($account_id = NULL)
    {
        if($this->auth->deactivate_account($account_id)){
            $this->session->set_flashdata('msg_system', 'Account disabled successfully.');
            redirect('admin/accounts/edit/'.$account_id);
        } else {
            $this->session->set_flashdata('msg_system', 'Unable to disable account.');
            redirect('admin/accounts/edit/'.$account_id);
        }
    }

}
