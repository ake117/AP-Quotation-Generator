<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Newsletters extends MX_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->auth->check_permission();
        $this->load->model('newsletter');
        $this->form_validation->set_error_delimiters('<p><span class="label label-danger">', '</span></p>');
    }

    public function index()
    {
        $content_vars = array();
        $contatos = $this->newsletter->get_list()->result();
        $content_vars['contatos'] = $contatos;
        $this->wpanel->load_view('newsletter/index', $content_vars);
    }

    public function export()
    {

        $filename = 'wpanel-newsletters-' . date('d-m-Y-H-i-s') . '.csv';

        // Load the DB utility class
        $this->load->dbutil();

        $query = $this->db->query("SELECT id AS 'Code', nome AS 'Nome', email AS 'E-mail', created AS 'Data', ipaddress AS 'IP' FROM newsletter_email");

        $delimiter = ",";
        $newline = "\r\n";
        $enclosure = '"';

        $csv = $this->dbutil->csv_from_result($query, $delimiter, $newline, $enclosure);

        // Load the download helper and send the file to your desktop
        $this->load->helper('download');
        force_download($filename, $csv);
    }

    public function clear()
    {
        if($this->newsletter->empty_table('newsletter_email')){
            $this->session->set_flashdata('msg_system', 'Limpeza efetuada com sucesso.');
            redirect('admin/newsletters');
        } else {
            $this->session->set_flashdata('msg_system', 'Erro ao efetuar a limpeza.');
            redirect('admin/newsletters');
        }
    }

}
