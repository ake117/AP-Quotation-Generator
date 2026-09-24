<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * MY_Model Model Class
 *
 * This class maintain the common code for the WpanelCMS Models.
 *
 * @package     WpanelCms
 * @subpackage  Core
 * @category    Core
 * @author      Eliel de Paula <dev@elieldepaula.com.br>
 * @link        https://wpanelcms.com.br
 * @version     0.0.1
 */
class MY_Model extends CI_Model
{
    /**
     * The name of table in the database.
     *
     * @var $table_name string
     **/
    public $table_name;

    /**
     * The name of primary-key of the table.
     *
     * @var $primary_key string
     **/
    public $primary_key;

    public $userid;

    public $lang_field = array('th','en');
    public $field_available = array();

    public $total_pages = 0;
    public $total_rows = 0;
    public $offset = 0;
    public $current_page = 0;
    public $next_page = 0;

	/**
     * Class constructor.
     */

    public function __construct()   {
        parent::__construct();
        $this->load->database();
        $this->account();
    }

    public function get_pagers($command_text, $page=1, $limit=10) {

        $this->current_page = $page;
        $res = $this->db->query($command_text);

        $row = $res->row();
        $this->total_rows = $row->count;

        // calculation of total pages for the query
        if( $this->total_rows > 0 ) {
            $this->total_pages = ceil($this->total_rows/$limit);
        } else {
            $this->total_pages = 0;
        }

        // if for some reasons the requested page is greater than the total
        // set the requested page to total page
        if ($this->current_page > $this->total_pages) { $this->current_page = $this->total_pages; }

        // calculate the starting position of the rows
        $this->offset = ($limit * $this->current_page) - $limit; // do not put $limit*($page - 1)
        // if for some reasons start position is negative set it to 0
        // typical case is that the user type 0 for the requested page
        if($this->offset <0){ $this->offset = 0; }

        $this->next_page = $this->current_page + 1;
        if($this->current_page === $this->total_pages){
            $this->next_page = 0;
        }

        // the actual query for the grid data
        $res->free_result();
    }

    /*
     * User account information
     */
    public function account(){

        $data = array();
        $user = $this->session->userdata('user');

        if(isset($user)){
            $this->db->select('*');
            $this->db->from('members');
            $this->db->where('member_id', $user['ID']);
            $res = $this->db->get();
            $row = $res->row();
            $user['name'] = $row->name;
            $user['surname'] = $row->surname;

            $ci =& get_instance();
            $ci->user = $user;
            $this->smarty->assign('user', $this->user);
        }
        //if (isset($this->user)) {print_r($this->user);} else {print_r('not set');}die('xxx');
    }


    /**
     * This method returns a list of content.
     *
     * @param $order array Order results: ['field'=>'field name', 'order'=>'asc/desc'].
     * @param $limit array Limit results: ['offset'=>'0', 'limit'=>'10'].
     * @param $select string List of selected fields: "name, email, phone".
     * @param $where string List of selected fields: "name, email, phone".
     * @return mixed
     */
    public function get_list($order = array(), $limit = array(), $select = null, $where = null)
    {
        if ($select != null)
            $this->db->select($select);

        if ((is_array($order)) and (count($order)!=0))
            $this->db->order_by($order['field'], $order['order']);

        if((is_array($limit)) and (count($limit) != 0))
            $this->db->limit($limit['limit'], $limit['offset']);

        if((is_array($where)) and (count($where) != 0))
            $this->db->where($where);

        return $this->db->get($this->table_name);
    }

    /**
     * This method returns a record indicated by the primary key.
     *
     * @param $value int - The primary key value.
     * @param $order array Order results: ['field'=>'field name', 'order'=>'asc/desc'].
     * @param $limit array Limit results: ['offset'=>'0', 'limit'=>'10'].
     * @param $select string List of selected fields: "name, email, phone".
     * @return mixed
     */
    public function get_by_id($value = null, $order = array(), $limit = array(), $select = null)
    {
        if($value == null)
            return FALSE;
        else
            return $this->get_by_field($this->primary_key, $value, $order, $limit, $select);
    }

    /**
     * This method is similar to get_by_id () except that with
     * him, we inform the field that will be used as a filter.
     *
     * @param $field string - Field that will be used as a filter.
     * @param $value string - Value to be filtred. If $field is an array(), this param must be null.
     * @param $order array Order results: ['field'=>'field name', 'order'=>'asc/desc'].
     * @param $limit array Limit results: ['offset'=>'0', 'limit'=>'10'].
     * @param $select string List of selected fields: "name, email, phone".
     * @return mixed
     */
    public function get_by_field($field, $value = null, $order = array(), $limit = array(), $select = null)
    {
        if ($select != null)
            $this->db->select($select);

        if (is_array($field))
        {
            foreach ($field as $key => $val)
            {
                $this->db->where($key, $val);
            }
        }
        else
            $this->db->where($field, $value);

        if ((is_array($order)) and (count($order)!=0))
            $this->db->order_by($order['field'], $order['order']);

        if((is_array($limit)) and (count($limit) != 0))
            $this->db->limit($limit['limit'], $limit['offset']);

        return $this->db->get($this->table_name);
    }

    /**
     * This method returns the total number of table records.
     *
     * @return int
     */
    public function count_all()
    {
        return $this->db->count_all($this->table_name);
    }

    /**
     * This method returns the total of an where clausule.
     *
     * @param string $field
     * @param string $value
     * @return int
     */
    public function count_by($field, $value)
    {
        $this->db->where($field, $value);
        $this->db->from($this->table_name);
        return $this->db->count_all_results();
    }

    /**
     * Save a new record.
     *
     * @param $data array Array data..
     * @return mixed
     */
    public function save($data)
    {
        $this->db->insert($this->table_name, $data);
        return $this->db->insert_id();
    }

    /**
     * Update a record.
     *
     * @param $id int - Primary-key value.
     * @param $data array Array data.
     * @return mixed
     **/
    public function update($id, $data)
    {
        $this->db->where($this->primary_key, $id);
        $this->db->update($this->table_name, $data);
        return $this->db->affected_rows();
    }

    /**
     * Delete a record.
     *
     * @param $id int Primary-key.
     * @return mixed
     */
    public function delete($id, $data=array())
    {
        $this->set_deleted($data);
        $this->update($id, $data);
        return $this->db->affected_rows();
    }

    /**
     * Delete a record by some field.
     *
     * @param $field String Field of the table.
     * @param $value int Value of the field.
     * @param $table string Optional table name.
     * @return mixed
     */
    public function delete_by($field, $value, $table = NULL)
    {
        $this->db->where($field, $value);
        if($table == NULL)
            $this->db->delete($this->table_name);
        else
            $this->db->delete($table);
        return $this->db->affected_rows();
    }

    /**
     * Empty a table.
     *
     * @param $table String TAble name.
     * @return mixed
     */
    public function empty_table($table = NULL)
    {
        return $this->db->empty_table($table);
    }

    /**
     * Upload a file.
     *
     * @param $path String Path where the file must be saved.
     * @param $types String File type: gif|jpg|png.
     * @param $fieldname String Field name of the form.
     * @param $filename String Optional file name..
     * @return mixed
     */
    public function upload_media($path, $types = '*', $fieldname = 'userfile', $filename = null)
    {
        if (!is_dir(FCPATH.'media/'.$path)) {
            mkdir(FCPATH.'media/'.$path, 0777, TRUE);
         }

        $config['upload_path'] = FCPATH.'media/'.$path.'/';
        $config['remove_spaces'] = TRUE;
        $config['file_ext_tolower'] = TRUE;
        $config['allowed_types'] = $types;
        if($filename == null)
            $config['file_name'] = md5(microtime( true ));
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

    /**
     * Remove an uploaded file.
     *
     * @param $file String - Full path to the file.
     * @return boolean
     */
    public function remove_media($file)
    {
        $filename = FCPATH.'media/'.$file;
        if(file_exists($filename))
        {
            if(unlink($filename))
                return TRUE;
             else
                return FALSE;
        } else
            return FALSE;
    }

    public function get_current_date(){
        return date('Y-m-d H:i:s');
    }

    public function set_created(&$data_save){

        //$this->userid = $CI->auth->get_userid();
        $data_save['create_date'] = $this->get_current_date();
        $data_save['create_by_id'] = $this->auth->get_account_id();
        $data_save['create_ip'] = $this->input->ip_address();
    }

    public function set_updated(&$data_save){
        $data_save['update_date'] = $this->get_current_date();
        $data_save['update_by_id'] = $this->auth->get_account_id();
        $data_save['update_ip'] = $this->input->ip_address();
    }

    public function set_deleted(&$data_save){
        $data_save['is_active'] = 'N';
        $data_save['is_delete'] = 'Y';
        $data_save['delete_date'] = $this->get_current_date();
        $data_save['delete_by_id'] = $this->auth->get_account_id();
        $data_save['delete_ip'] = $this->input->ip_address();
    }
/*
 * multi language
 */

    public function set_validate($oper = 'add', $ignore_field=array()){
        /*
         * validate
         */
        foreach ($this->field_available as $field => $fields) {
            if(!$fields['validate'] || in_array($field, $ignore_field)){continue;}
            //echo $fields['type'] . '<br/>';
            if($fields['multi_lang']){
                foreach ($this->lang_field as $lang) {
                    $field_name = "{$field}_{$lang}";

                    $lang_upper = strtoupper($lang);
                    switch ($fields['type']) {
                        case 'text':
                                $this->form_validation->set_rules($field_name, "{$fields['validate_text']} ($lang_upper)", $fields['validate_type']);
                            break;
                        case 'file':
                            if($oper==='add'){
                                if (empty($_FILES["$field_name"]['name']))
                                {
                                    $this->form_validation->set_rules($field_name, "{$fields['validate_text']} ($lang_upper)", $fields['validate_type']);
                                }
                            }elseif ($oper==='edit') {
                                if ($this->input->post('alter_'.$field_name)=='1' && empty($_FILES["$field_name"]['name']))
                                {
                                    $this->form_validation->set_rules($field_name, "{$fields['validate_text']} ($lang_upper)", $fields['validate_type']);
                                }
                            }
                            break;

                        default:
                            break;
                    }
                }
            }else{
                switch ($fields['type']) {
                    case 'text':
                    case 'checkbox':
                    case 'select':
                            $this->form_validation->set_rules($field, "{$fields['validate_text']}", $fields['validate_type']);
                        break;
                    case 'file':
                        if($oper==='add'){
                            if (empty($_FILES["$field"]['name']))
                            {
                                $this->form_validation->set_rules($field, "{$fields['validate_text']}", $fields['validate_type']);
                            }
                        }elseif ($oper==='edit') {
                            if ($this->input->post('alter_'.$field) == '1' && empty($_FILES["$field"]['name']))
                            {
                                $this->form_validation->set_rules($field, "{$fields['validate_text']}", $fields['validate_type']);
                            }
                        }
                        break;

                    default:
                        break;
                }
            }
        }
        /*
         * end validate
         */
    }

    public function set_data_field(&$data, $ignore_field=array(), $oper='add'){
        foreach ($this->field_available as $field => $fields) {
            if(in_array($field, $ignore_field)){continue;}

            if($fields['multi_lang']){
                foreach ($this->lang_field as $lang) {
                    $field_name = "{$field}_{$lang}";
                    switch ($fields['type']) {
                        case 'text':
                                $data[$field_name] = $this->input->post($field_name);
                            break;
                        case 'file':
                            case 'file':
                                if($oper==='edit'){
                                    if($this->input->post('alter_'.$field_name)=='1'  && !empty($_FILES["$field_name"]['name'])){
                                        $data[$field_name] = $this->upload_media($this->table_name, '*', $field_name);
                                    }
                                }else{
                                    if(!empty($_FILES["$field_name"]['name'])){
                                        $data[$field_name] = $this->upload_media($this->table_name, '*', $field_name);
                                    }
                                }
                            break;
                        default:
                            break;
                    }
                }
            }else{
                switch ($fields['type']) {
                        case 'text':
                        case 'checkbox':
                        case 'select':
                                $data[$field] = $this->input->post($field);
                            break;
                        case 'file':
                            case 'file':
                                if($oper==='edit'){
                                    if($this->input->post($field)=='1'  && !empty($_FILES["$field"]['name'])){
                                        $data[$field] = $this->upload_media($this->table_name, '*', $field);
                                    }
                                }else{
                                    if(!empty($_FILES["$field"]['name'])){
                                        $data[$field] = $this->upload_media($this->table_name, '*', $field);
                                    }
                                }
                            break;
                        default:
                            break;
                    }
            }
        }
    }

    public function remove_image($id){
        $tb = $this->get_by_id($id)->row();

        foreach ($this->field_available as $field => $fields) {
            if($fields['multi_lang']){
                if($fields['type']==='file'){
                    foreach ($this->lang_field as $lang) {
                        $field_name = "{$field}_{$lang}";
                        if($this->input->post('alter_'.$field_name)=='1')
                        {
                            $this->remove_media($this->table_name.'/' . $tb->$field_name);
                        }
                    }
                }
            }else{
                if($fields['type']==='file'){
                        if($this->input->post('alter_' . $field)=='1')
                        {
                            $this->remove_media($this->table_name.'/' . $tb->$field);
                        }
                }
            }
        }

    }


 /*
  * end
 * multi language
 */

}

