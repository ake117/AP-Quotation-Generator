<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Banner extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->auth->check_permission();
        $this->load->model('Banner_model', 'banner');
        $this->form_validation->set_error_delimiters('<p><span class="label label-danger">', '</span></p>');
    }

    public function index()
    {
        //$this->load->library('table');

        $content_vars = array();

        $query = $this->banner->get_all(); // ordenar pela sequencia dos banner.
        //echo $this->db->last_query();
        $content_vars['query'] = $query;
        $content_vars['lang_field'] = $this->banner->lang_field;

        $template = 'index.tpl';
        $this->smarty->view_admin( $template, $content_vars );
    }

    public function update_sequence()
    {
        $i = 0;
        $itens = $_POST['item']; //$this->input->post('item');
        foreach ($itens as $value) {
            // Execute statement:
            $this->banner->update($value,  array('sequence' => $i));
            $i++;
        }
    }

    public function add()
    {
        $data = array();

        $this->banner->set_validate();

        if ($this->form_validation->run() == FALSE)
        {
                $template = 'add.tpl';

                $this->banner->set_data_field($data);

                //$this->session->set_flashdata('msg_system', wpn_lang('msg_enter_information'));

                $published_options = config_item('published_options');
                $this->smarty->assign('published_options', $published_options);
                $this->smarty->assign('lang_field', $this->banner->lang_field);
                $this->smarty->assign('published_selected', 'N');
                $this->smarty->view_admin( $template, $data);
        } else {
                $this->banner->set_data_field($data);

                $this->banner->set_created($data);
                $new_post = $this->banner->save($data);

                if($new_post){
                    $this->session->set_flashdata('msg_system', 'Room type banner saved successfully..');
                    redirect('admin/banner');
                } else {
                    $this->session->set_flashdata('msg_system', 'Error saving banner.');
                    redirect('admin/banner');
                }
        }
    }

    public function edit($id = null)
    {
        $data = array();
        $content_vars = array();

        $this->banner->set_validate('edit');

        if ($this->form_validation->run() == FALSE)
        {
            if($id == null){
                $this->session->set_flashdata('msg_system', 'Banner Not found');
                redirect('admin/banner');
            }

            if($this->input->post('submit') !== null){
                $this->session->set_flashdata('msg_system', wpn_lang('msg_enter_information'));
            }

            $published_options = config_item('published_options');
            $this->smarty->assign('published_options', $published_options);

            $content_vars['id'] = $id;
            $content_vars['lang_field'] = $this->banner->lang_field;
            $content_vars['row'] = $this->banner->get_by_id($id)->row_array();
            $this->smarty->view_admin('edit.tpl', $content_vars);

        } else {

                $this->banner->set_data_field($data, array(), 'edit');

                $this->banner->set_updated($data);
                $this->banner->remove_image($id);

                $new_post = $this->banner->update($id, $data);
//                    var_dump($this->db->error());
//                    echo $this->db->last_query();

            if($new_post)
            {
                $this->session->set_flashdata('msg_system', 'Banner saved successfully.');
                redirect('admin/banner');
            } else {
                $this->session->set_flashdata('msg_system', 'Error saving Banner.');
                redirect('admin/banner');
            }
        }
    }

    public function delete($id = null)
    {
            if($id == null){
                $this->session->set_flashdata('msg_system', 'Banner does not exist.');
                redirect('admin/banner');
            }

            //Remove the file from the banner.
            //$banner = $this->banner->get_by_id($id)->row();
            //$this->banner->remove_media('banner/' . $banner->banner_image_th);

            if($this->banner->delete($id)){
                    $this->session->set_flashdata('msg_system', 'Banner successfully deleted.');
                    redirect('admin/banner');
            } else {
                    $this->session->set_flashdata('msg_system', 'Error deleting Banner.');
                    redirect('admin/banner');
            }
    }

}