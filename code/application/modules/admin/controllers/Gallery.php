<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gallery extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->auth->check_permission();
        $this->load->model('Gallery_model', 'gallery');
        $this->load->model('Projects_model', 'project');
        $this->form_validation->set_error_delimiters('<p><span class="label label-danger">', '</span></p>');
    }

    public function index()
    {
        //$this->load->library('table');

        $content_vars = array();

        $query = $this->gallery->get_gallery_project(); // ordenar pela sequencia dos gallery.
        //echo $this->db->last_query();
        $content_vars['query'] = $query;
        $content_vars['lang_field'] = $this->gallery->lang_field;

        $template = 'index.tpl';
        $this->smarty->view_admin( $template, $content_vars );
    }

    public function update_sequence()
    {
        $i = 0;
        $itens = $_POST['item']; //$this->input->post('item');
        foreach ($itens as $value) {
            // Execute statement:
            $this->gallery->update($value,  array('sequence' => $i));
            $i++;
        }
    }

    public function add()
    {
        $data = array();

        $this->gallery->set_validate();
        $project = $this->project->get_all_assoc();

        if ($this->form_validation->run() == FALSE)
        {
                $template = 'add.tpl';

                $this->gallery->set_data_field($data);

                //$this->session->set_flashdata('msg_system', wpn_lang('msg_enter_information'));

                $published_options = config_item('published_options');
                $this->smarty->assign('project', $project);
                $this->smarty->assign('published_options', $published_options);
                $this->smarty->assign('lang_field', $this->gallery->lang_field);
                $this->smarty->assign('published_selected', 'N');
                $this->smarty->view_admin( $template, $data);
        } else {
                $this->gallery->set_data_field($data);

                $this->gallery->set_created($data);
                $new_post = $this->gallery->save($data);

                if($new_post){
                    $this->session->set_flashdata('msg_system', 'Room type gallery saved successfully..');
                    redirect('admin/gallery');
                } else {
                    $this->session->set_flashdata('msg_system', 'Error saving gallery.');
                    redirect('admin/gallery');
                }
        }
    }

    public function edit($id = null)
    {
        $data = array();
        $content_vars = array();

        $this->gallery->set_validate('edit', array('project_id'));
        $project = $this->project->get_all_assoc();

        if ($this->form_validation->run() == FALSE)
        {
            if($id == null){
                $this->session->set_flashdata('msg_system', 'Gallery Not found');
                redirect('admin/gallery');
            }
            $v = $this->input->post('submit');

            if($this->input->post('submit') !== null){
                $this->session->set_flashdata('msg_system', wpn_lang('msg_enter_information'));
            }

            $published_options = config_item('published_options');
            $this->smarty->assign('published_options', $published_options);

            $content_vars['id'] = $id;
            $content_vars['project'] = $project;
            $content_vars['lang_field'] = $this->gallery->lang_field;
            $content_vars['row'] = $this->gallery->get_by_id($id)->row_array();
            $this->smarty->view_admin('edit.tpl', $content_vars);

        } else {

                $this->gallery->set_data_field($data, array('project_id'), 'edit');

                $this->gallery->set_updated($data);
                $this->gallery->remove_image($id);

                $new_post = $this->gallery->update($id, $data);
//                    var_dump($this->db->error());
//                    echo $this->db->last_query();

            if($new_post)
            {
                $this->session->set_flashdata('msg_system', 'Gallery saved successfully.');
                redirect('admin/gallery');
            } else {
                $this->session->set_flashdata('msg_system', 'Error saving gallery.');
                redirect('admin/gallery');
            }
        }
    }

    public function delete($id = null)
    {
            if($id == null){
                $this->session->set_flashdata('msg_system', 'Gallery does not exist.');
                redirect('admin/gallery');
            }

            //Remove the file from the gallery.
            //$gallery = $this->gallery->get_by_id($id)->row();
            //$this->gallery->remove_media('gallery/' . $gallery->gallery_image_th);

            if($this->gallery->delete($id)){
                    $this->session->set_flashdata('msg_system', 'Gallery successfully deleted.');
                    redirect('admin/gallery');
            } else {
                    $this->session->set_flashdata('msg_system', 'Error deleting gallery.');
                    redirect('admin/gallery');
            }
    }

}