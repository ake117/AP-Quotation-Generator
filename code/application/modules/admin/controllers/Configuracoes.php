<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Configuracoes extends MX_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->auth->check_permission();
    }

    /**
     * @todo Resolver a questão de enviar 2 uploads no mesmo formulário.
     */
    public function index()
    {

        $configs = $this->wpanel->get_config();
        $content_vars   = array();
        $category_check = '';
        $page_check     = '';
        $smtp_checked   = '';

        $this->form_validation->set_rules('site_title', 'Title site', 'required');
        if ($this->form_validation->run() == FALSE){

            $this->load->model('category');
            $this->load->model('post');

            // Organiza as caixas de checagem da configuração da Home page.
            switch ($configs->home_tipo){
                case 'category':
                    $category_check = 'checked';
                    $page_check = '';
                    $custom_check = '';
                    break;
                case 'page':
                    $category_check = '';
                    $page_check = 'checked';
                    $custom_check = '';
                    break;
                default:
                    $category_check = '';
                    $page_check = '';
                    $custom_check = 'checked';
                    break;
            }
            // Organiza as caixas de checagem do uso de SMTP e TLS/SSL.
            if ($configs->use_smtp == 1){
                $smtp_checked = 'checked';
            } else {
                $smtp_checked = '';
            }
            if ($configs->usa_ssl == 'ssl'){
                $ssl_checked = 'checked';
            } else {
                $ssl_checked = '';
            }
            // Envia as variáveis para a view.
            $content_vars['category_check'] = $category_check;
            $content_vars['page_check']     = $page_check;
            $content_vars['custom_check']   = $custom_check;
            $content_vars['smtp_checked']   = $smtp_checked;
            $content_vars['ssl_checked']    = $ssl_checked;
            $content_vars['editor']         = $this->wpanel->load_editor();
            $content_vars['row']            = $configs;
            $this->wpanel->load_view('configuracoes/index', $content_vars);

        } else {

            $this->load->model('configuracao');

            $configs->site_title      = $this->input->post('site_title');
            $configs->site_desc        = $this->input->post('site_desc');
            $configs->site_tags        = $this->input->post('site_tags');
            $configs->site_contact     = $this->input->post('site_contact');
            $configs->site_telefone    = $this->input->post('site_telefone');
            $configs->link_instagram   = $this->input->post('link_instagram');
            $configs->link_twitter     = $this->input->post('link_twitter');
            $configs->link_facebook    = $this->input->post('link_facebook');
            $configs->link_likebox     = $this->input->post('link_likebox');
            $configs->copyright        = $this->input->post('copyright');
            $configs->addthis_uid      = $this->input->post('addthis_uid');
            $configs->texto_contato    = $this->input->post('texto_contato');
            $configs->google_analytics = $this->input->post('google_analytics');
            $configs->bgcolor          = $this->input->post('bgcolor');
            $configs->language         = $this->input->post('language');
            $configs->text_editor      = $this->input->post('text_editor');
            $configs->author           = $this->input->post('author');
            // Configuration da Home page do site.
            $configs->home_tipo         = $this->input->post('home_tipo');

            if ($this->input->post('home_tipo') == 'page') {
                $configs->home_id      = $this->input->post('home_post');
            } else {
                $configs->home_id      = $this->input->post('home_category');
            }
            // Smtp
            $configs->use_smtp         = $this->input->post('use_smtp');
            $configs->smtp_host    = $this->input->post('smtp_host');
            $configs->smtp_port       = $this->input->post('smtp_port');
            $configs->usa_ssl          = $this->input->post('usa_ssl');
            $configs->smtp_user     = $this->input->post('smtp_user');
            $configs->smtp_password       = $this->input->post('smtp_password');
            // Mantém os dados das imagens.
            $configs->logomarca        = $configs->logomarca;
            $configs->background       = $configs->background;

            if ($this->configuracao->save_config($configs)) {
                $this->session->set_flashdata('msg_system', 'Configuration saved successfully.');
                redirect('admin/configuracoes');
            } else {
                $this->session->set_flashdata('msg_system', 'Erro ao salvar a configuração.');
                redirect('admin/configuracoes');
            }
        }
    }

    /**
     * Altera a logomarca.
     */
    public function altlogo()
    {
        $configs = $this->wpanel->get_config();
        $this->configuracao->remove_media($configs->logomarca);
        $configs->logomarca = $this->configuracao->upload_media('', '*', 'logomarca');
        if ($this->configuracao->save_config($configs)) {
            $this->session->set_flashdata('msg_system', 'Logomarca salva com sucesso.');
            redirect('admin/configuracoes');
        } else {
            $this->session->set_flashdata('msg_system', 'Erro ao salvar a configuração.');
            redirect('admin/configuracoes');
        }
    }

    /**
     * Altera a imagem de fundo.
     */
    public function altback()
    {
        $configs = $this->wpanel->get_config();
        $this->configuracao->remove_media($configs->background);
        $configs->background = $this->configuracao->upload_media('', '*', 'background');
        if ($this->configuracao->save_config($configs)) {
            $this->session->set_flashdata('msg_system', 'Background image saved successfully.');
            redirect('admin/configuracoes');
        } else {
            $this->session->set_flashdata('msg_system', 'Erro ao salvar a configuração.');
            redirect('admin/configuracoes');
        }
    }
}
