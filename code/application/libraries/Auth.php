<?php


 defined('BASEPATH') OR exit('No direct script access allowed');

class Auth
{
    protected $auth_check_permyssion_by_hook = FALSE;
    protected $auth_white_list = array();
    protected $auth_password_hash_type = 'bcrypt';
    protected $auth_password_hash_salt;

    public function __construct($config = array())
    {
        if (count($config) > 0)
            $this->initialize($config);
        $this->load->model('auth_model', 'model');
        log_message('debug', "Auth Class Initialized");
    }

    public function __get($var)
    {
        return get_instance()->$var;
    }

    /**
     * Initialize the library loading the configuration files or
     * an array() passed on load of the class.
     *
     * @param $config array()
     * @return void
     */
    public function initialize($config = array())
    {
        foreach ($config as $key => $val)
        {
            if (isset($this->$key)) {
                $method = 'set_' . $key;
                if (method_exists($this, $method))
                    $this->$method($val);
                else
                    $this->$key = $val;
            }
        }
        return $this;
    }

    public function create_account($email, $password, $role, $extra_data = array(), $permissions = array())
    {

        if ($this->model->email_exists($email))
            throw new Exception("Email already exists.");
        //TODO Adicionar configuração se envia ou não ativação por email.
        $data = array(
            'email' => $email,
            'password' => $this->_hash_password($password),
            'role' => $role,
            'extra_data' => json_encode($extra_data),
            'ip_address' => $_SERVER['REMOTE_ADDR'],
            'created' => date('Y-m-d H:i:s'),
            'updated' => date('Y-m-d H:i:s'),
            'status' => 0
        );

        //TODO Testar esta solução online.
        $data_email = array();
        $data_email['to'] = $email;
        $data_email['subject'] = 'Confirmation e ativação de cadastro.';
        //$this->_send_activation_email($data_email);

        $new_account = $this->model->insert_account($data);

        if(count($permissions) > 0){
            foreach ($permissions as $key => $value) {
                $this->create_permission($value, $new_account);
            }
        }

        if (!$new_account > 0)
            throw new Exception('Error creating new account.');

        return $new_account;
    }

    public function create_permission($action_id, $account_id)
    {
        $data = array(
            'module_action_id' => $action_id,
            'account_id' => $account_id,
            'created' => date('Y-m-d H:i:s'),
            'updated' => date('Y-m-d H:i:s')
        );
        return $this->model->insert_permission($data);
    }

    public function upload_avatar($path = 'avatar', $types = '*', $fieldname = 'userfile', $filename = null)
    {
        if (!is_dir(FCPATH.'media/'.$path)) {
            mkdir(FCPATH.'media/'.$path, 0777, TRUE);
        }

        $config['upload_path'] = FCPATH.'media/'.$path.'/';
        $config['remove_spaces'] = TRUE;
        $config['file_ext_tolower'] = TRUE;
        $config['allowed_types'] = $types;
        if($filename == null)
            $config['file_name'] = md5(date('YmdHis'));
        else
            $config['file_name'] = $filename;

        $this->load->library('upload', $config);
        if ($this->upload->do_upload($fieldname))
        {
            $upload_data = array();
            $upload_data = $this->upload->data();
            return $upload_data['file_name'];
        } else
            return false;
    }

    public function activate_account($account_id = '')
    {
        $changed_account = $this->model->activate_account($account_id);
        if (!$changed_account > 0)
            throw new Exception('Error activating account.');

        return $changed_account;
    }

    public function deactivate_account($account_id = NULL)
    {
        $changed_account = $this->model->deactivate_account($account_id);
        if (!$changed_account > 0)
            throw new Exception('Error activating account.');

        return $changed_account;
    }

    public function remove_account($account_id = NULL)
    {
        $this->model->remove_permission_by_account($account_id);
        $removedaccount = $this->model->remove_account($account_id);

        if (!$removedaccount > 0)
            throw new Exception('Error removing an account.');

        return $removedaccount;
    }

    public function get_account_by_id($id = NULL)
    {
        if ($id == NULL)
            $id = $this->get_login_data('id');
        return $this->model->account_by_id($id);
    }

    public function update_account($id, $email, $role = NULL, $extra_data = array(), $permissions = array())
    {
        $data = array();
        $data['id'] = $id;
        $data['email'] = $email;
        if($role != NULL)$data['role'] = $role;
        $data['ip_address'] = $_SERVER['REMOTE_ADDR'];
        $data['updated'] = date('Y-m-d H:i:s');

        //TODO Melhorar a atualização dos dados-extra, desse jeito sempre perderá a foto.
        if(count($extra_data) > 0)
            $data['extra_data'] = json_encode($extra_data);

        $updated_account = $this->model->update_account($data);

        // Change the permissions if a parameter is sent by the array

        if(count($permissions) > 0){
            $this->model->remove_permission_by_account($id);
            foreach ($permissions as $key => $value) {
                $this->create_permission($value, $id);
            }
        }

        if (!$updated_account > 0)
            throw new Exception('Error updating an account.');

        return $updated_account;
    }

    public function change_password($id, $old_password = NULL, $new_password)
    {
        // Plain passwords: the model verifies the old one against the stored
        // hash (bcrypt or legacy MD5) and hashes the new one before saving.
        $data = array();
        $data['id'] = $id;
        if($old_password != NULL)$data['old_password'] = $old_password;
        $data['new_password'] = $new_password;

        $updated_password = $this->model->update_password($data);

        if (!$updated_password > -1)
            throw new Exception('Error updating an password.');

        return $updated_password;
    }

    public function login($email, $password, $remember = FALSE, $backlink = NULL)
    {
        //TODO Fazer o funcionamento do backlink.
        // The model verifies the plain password (bcrypt or legacy MD5) and
        // upgrades a legacy hash on success, so no pre-hashing here.
        $data = array(
            'email' => $email,
            'password' => $password
        );
        $login = $this->model->login_account($data);
        if ($login == FALSE)
            return FALSE;
        else {
            $this->_set_session($login);
            return TRUE;
        }
    }

    public function logout()
    {
        return $this->session->sess_destroy();
    }

    /**
     * This method returns the user accound by $id, or if null, return the logged
     * account.
     *
     * @param int $id
     * @return mixed
     */
    public function account($id = NULL)
    {
        if($id){
            return $this->model->account_by_id($id);
        } else {
            return $this->model->account_by_id($this->get_login_data('id'));
        }
    }

    public function get_account_id()
    {
//        echo '<pre>';
//        var_dump($this->session);
//        echo '</pre>';
        return $this->get_login_data('id');
    }

    public function get_login_data($item = NULL)
    {
        if ($item == NULL)
            return FALSE;
        else
            return $this->session->userdata($item);
    }

    public function get_extra_data($item = NULL, $json = NULL)
    {
        // If $json is empty uses the session login extra-data.
        if ($json == NULL)
           $json = $this->get_login_data('extra_data');
        $objeto = (object) json_decode($json);
        if($item == NULL)
            return $objeto;
        else
            return $objeto->$item;
    }

    public function send_activation_email()
    {
        //TODO Criar o método que envia a message de ativação por email.
    }

    public function accounts_empty()
    {
        return $this->model->accounts_empty();
    }

    public function check_permission_by_hook()
    {
        if ($this->config->item('auth_check_permyssion_by_hook') == TRUE)
            return $this->check_permission();
    }

    public function check_permission($url = NULL)
    {
        $account_id = $this->get_account_id();
        $account_role = $this->get_login_data('role');
        if ($account_id == '') {
            $this->session->flashdata('msg_auth', 'User is not logged.');
            redirect('admin/logout');
            exit;
        } else {
            if($url == NULL){
                $url = $this->uri->uri_string();
                ($this->uri->total_segments() == 3) ? $url . '/' : $url;
            }
            if ($account_role == 'ROOT')
                return TRUE;
            if ($url == '')
                return TRUE;
            if (in_array($this->_prepare_url($url), $this->auth_white_list))
                return TRUE;
            if ($this->model->validate_white_list($this->_prepare_url($url)))
                return TRUE;
            if ($this->model->validate_permission($account_id, $this->_prepare_url($url)) === false) {
                $this->session->flashdata('msg_system', 'User don\'t has permission.');
                redirect('admin/dashboard');
                exit;
            }
            return TRUE;
        }
    }

    /**
     * Verifica se um usuário tem permissão para determinado link. A opção $override_root
     * serve para não considerar o root na hora da checagem.
     *
     * @author Eliel de Paula <dev@elieldepaula.com.br>
     * @param String $url Link da pagina Eg. 'admin/users'
     * @param Integer $account_id ID da conta a ser testada. Caso seja null usa-se o usuário logado.
     * @param Bool $override_root Ignora o ROOT na verificação.
     * @return Mixed
     */
    public function has_permission($url, $account_id = NULL, $override_root = FALSE)
    {
        if($account_id == NULL)
            $account_id = $this->get_account_id();
        if($this->get_login_data('role') == 'ROOT' && $override_root == FALSE)
            return TRUE;
        else
           return $this->model->validate_permission($account_id, $url);
    }

    public function list_accounts($order = array(), $limit = array(), $select = null)
    {
        return $this->model->all_accounts($order, $limit, $select)->result();
    }

    public function list_modules_full()
    {
        $this->load->model(array('module', 'module_action'));
        $query_module = $this->module->get_list(array('field'=>'order', 'order'=>'asc'), null, null, array('is_delete'=>'N') )->result_array();
        // Adiciona as actions na lista de módulos.
        foreach ($query_module as $key => $value) {
            $query_action = $this->module_action->get_by_field(array('whitelist'=>'0', 'module_id'=>$value['id'], 'is_delete'=>'N'))->result_array();
            $query_module[$key]['actions'] = $query_action;
        }
        return $query_module;
    }

    private function _send_activation_email($data = null)
    {

        $data['html'] = TRUE;
        $data['message'] = $this->load->view('emails/account_activation', $data, TRUE);
        return $this->wpanel->send_email($data);

    }

    /**
     * Setup a login session.
     *
     * @param $account
     * @return bool
     */
    private function _set_session($account)
    {

        if (!$account->id)
            return FALSE;

        $session_data = array(
            'id' => $account->id,
            'email' => $account->email,
            'role' => $account->role,
            'extra_data' => $account->extra_data,
            'created' => $account->created,
            'logged_in' => TRUE
        );
        $this->session->set_userdata($session_data);
    }

    /**
     * Return a hashed password.
     *
     * @param $password
     * @param string $salt
     * @return string
     */
    private function _hash_password($password)
    {
        switch ($this->auth_password_hash_type)
        {
            case 'bcrypt':
                return password_hash($password, PASSWORD_DEFAULT);
                break;

            case 'md5':
                // Legacy only: still used to verify passwords that were never rehashed.
                return md5($password . $this->auth_password_hash_salt);
                break;
        }
    }

    /**
     * Verify a plain password against a stored hash, accepting both bcrypt and
     * the legacy unsalted MD5 hashes. Returns TRUE for a match.
     *
     * @param string $password plain password from the login form
     * @param string $hash     value from cms_accounts.password
     * @return bool
     */
    private function _verify_password($password, $hash)
    {
        if ($hash === null || $hash === '') {
            return FALSE;
        }

        // bcrypt / argon2 hashes are always longer than 32 chars and carry a $ prefix.
        if (strlen($hash) > 32 || strpos($hash, '$') === 0) {
            return password_verify($password, $hash);
        }

        // Legacy MD5 (32 hex chars, unsalted).
        if (strlen($hash) === 32 && ctype_xdigit($hash)) {
            return md5($password . $this->auth_password_hash_salt) === $hash;
        }

        return FALSE;
    }

    /**
     * TRUE when the stored hash is a legacy MD5 that should be upgraded on the
     * next successful login.
     *
     * @param string $hash
     * @return bool
     */
    private function _needs_rehash($hash)
    {
        if ($hash === null || $hash === '') {
            return FALSE;
        }
        if (strpos($hash, '$') === 0) {
            return password_needs_rehash($hash, PASSWORD_DEFAULT);
        }
        return strlen($hash) === 32 && ctype_xdigit($hash);
    }

    private function _prepare_url($url)
    {
        //TODO Aprender uma forma mais elegante de fazer isso. :)
        $x = explode('/', $url);
        $out = '';
        $bar = '/';
        foreach ($x as $key => $value)
        {
            if ($key > 2)
                $value = '*';
            if ($key == 0)
                $out .= $value;
            else
                $out .= $bar . $value;
        }
        return $out;
    }
}
