<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Agents extends CI_Controller {

    private $config_grande;

    public function __construct() {
        parent::__construct();

        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');

        $this->load->model('Agents_model');
        $this->load->model('Signup_model');
        $this->load->library('Grande_api');

        // Load Pagination library
        $this->load->library('pagination');

        $this->load->config('grande', true);
        $this->config_grande = $this->config->item('default');
        $this->bbl = $this->config->item('bbl');
    }

    public function index($agent_id_code = '', $active_page = '', $param = '') {

        $agent_id = PseudoCrypt::unhash($agent_id_code);
        $res = $this->Agents_model->get_agent($agent_id);

        if ($res->num_rows() > 0) {

            $cache_id = md5($_SERVER['REQUEST_URI']);
            $template = 'agents.tpl';
            $this->smarty->setCaching(0);

            if (!$this->smarty->isCached($template, $cache_id)) {

                $this->smarty->assign('main_menu', $this->lang->line('menu_array'));
                $this->smarty->assign('current_menu', 'home');
                $this->smarty->assign('language', $this->lang->line('lang'));
                $this->smarty->assign('lang', $this->lang->language);
                $txt_active_page = (!empty($active_page))?ucfirst($active_page)." - ":"";
                $this->smarty->assign('title', $txt_active_page. 'Agents '.$this->config_grande['title']);
                $this->smarty->assign('description', $this->config_grande['description']);
                $this->smarty->assign('keywords', $this->config_grande['keywords']);
                $this->smarty->assign('og_url', $this->config_grande['og_url']);
                $this->smarty->assign('og_image', $this->config_grande['og_image']);

                $this->smarty->assign('author', $this->config_grande['author']);
                $this->smarty->assign('og_site_name', $this->config_grande['og_site_name']);

                $this->smarty->assign('prefix', $res->row()->prefix);
                $this->smarty->assign('name', $res->row()->name);
                $this->smarty->assign('surname', $res->row()->surname);
                $this->smarty->assign('agent_id', $res->row()->agent_id);
                $this->smarty->assign('email', $res->row()->email);
                $this->smarty->assign('phone', $res->row()->phone);

                $this->smarty->assign('agent_id_code', $agent_id_code);
                $this->smarty->assign('active_page', $active_page);
                $this->smarty->assign('param', $param);
                $this->smarty->assign('base_url', base_url());
                $Ref = $this->input->get('Ref', TRUE);
                if(!empty($Ref)){
                    $this->smarty->assign('Ref', $Ref);
                }

                if ($this->session->has_userdata('agent') && $this->session->agent['ID'] == $agent_id ) {

                    $this->smarty->assign('login', true);
                    $this->smarty->assign('menu_login', "");

                    if ($active_page=='history') {

                        $historys = $this->Agents_model->get_history($agent_id);
                        $this->smarty->assign('historys', $historys);

                    } else {

                        $this->smarty->assign('province', $this->Signup_model->get_province());
                        $this->smarty->assign('district', array('' => $this->lang->line('sign_district_info')));
                        $this->smarty->assign('subdistrict', array('' => $this->lang->line('sign_subdistrict_info')));
                        //$this->smarty->assign('projects', $this->Agents_model->get_project_agent());
                    }

                } else {

                    $this->smarty->assign('login', false);
                    $this->smarty->assign('menu_login', "show");
                }
                $this->smarty->view($template, $cache_id);
            }
        } else {
            redirect(base_url(), 'refresh');
        }

//        $this->smarty->clearAllCache();
    }

    public function signin() {
        $data = $this->input->post(null, TRUE);
        extract($data);

        $query = $this->Agents_model->get_agent($agent_id);

        $result["status"] = "n/a";
        $result["message"] = "";

        if ($query->num_rows() > 0) {
            $row = $query->row();
            if ($row->agent_status === 'active') {
                if (!password_verify($agentPassword, $row->password)) {
                    $result["status"] = "error";
                    $result["message"] = "{$this->lang->language['validate_login_password']}";
                } else {
                    $result["status"] = "success";
                    $agent['sessionID'] = bin2hex(openssl_random_pseudo_bytes(16));
                    $agent['ID'] = $row->agent_id;
                    $this->session->set_userdata('agent', $agent);
                    $result["message"] = $this->lang->language['validate_success'];
                }
            } else {
                $result["status"] = "error";
                $result["message"] = "{$this->lang->language['validate_login_status']}";
            }
        } else {
            $result["status"] = "error";
            $result["message"] = "{$this->lang->language['validate_login_email']}";
        }

        echo json_encode($result);
    }

    public function submit() {
        $base_url = base_url();
        $data = $this->input->post(null, TRUE);

        $data['sign_phone'] = str_replace("-","",$data['sign_phone']);
        $data['sign_payment'] = str_replace(",","",$data['sign_payment']);
        if (!empty($data['sign_subdistrict'])) {$data['sign_subdistrict'] = $this->Signup_model->get_tumbon_name($data['sign_subdistrict']);}
        if (!empty($data['sign_district'])) {$data['sign_district'] = $this->Signup_model->get_amphur_name($data['sign_district']);}
        if (!empty($data['sign_province'])) {$data['sign_province'] = $this->Signup_model->get_province_name($data['sign_province']);}

        extract($data);



        $reg_lang = $this->lang->line('lang');

        $this->form_validation->set_data($data);

        if (!isset($sign_address_no)) {$sign_address_no = '';}
        if (!isset($sign_subdistrict)) {$sign_subdistrict = '';}
        if (!isset($sign_district)) {$sign_district = '';}
        if (!isset($sign_province)) {$sign_province = '';}

        $total_tax = $sign_payment*0.07;
        $total_payment = $sign_payment-$total_tax;

        $time = date("Y-m-d H:i:s");
        $charge_id = $sign_unit."/".$sign_payment."/".$time;
        $token = md5($charge_id);

        $data = array(
            'agent_id' => $agent_id,
            'prefix' => $sign_prefix,
            'name' => $sign_name,
            'surname' => $sign_surname,
            'email' => $sign_email,
            'phone' => $sign_phone,
            'address_no' => $sign_address_no,
            'subdistrict' => $sign_subdistrict,
            'district' => $sign_district,
            'province' => $sign_province,
            'postcode' => $sign_postcode,
            'language' => $reg_lang,
            'payment_status' => 'P',
            //'project_id' => $sign_project,
            'token' => 'a_'.$token,
            'charge_id' => $charge_id,
            'project_unit' => $sign_unit,
            'total_paid' => $sign_payment,
            'total_tax' => $total_tax,
            'total_payment' => $total_payment,
            'country' => $country,
            'create_date' => date('Y-m-d H:i:s'),
        );

        //print_r($data); die();

        $this->db->insert('agents_payment', $data);

        $result["status"] = "success";
        $result["message"] = "{$this->lang->line('sign_success_title')}";
        $result["xyz"] = $data;
        $agent_id_hash = PseudoCrypt::hash($agent_id);
        $result["url"] = $base_url."agents/".$agent_id_hash."/reservation/";
        $result["pay_url"] = $this->bbl['pay_url'];
        $result["pay_merchantid"] = $this->bbl['merchantid'];

        echo json_encode($result);
    }

    public function signout() {
        $this->session->unset_userdata('agent');
    }

    public function loadRecord($agent_id,$page=1,$keyword=''){

        // Row per page
        $rowperpage = 10;

        // All records count
        $allcount = $this->Agents_model->get_historyCount($agent_id,$keyword);

        // Get records
        $users_record = $this->Agents_model->get_history($agent_id,$keyword,$page,$rowperpage);

        // Pagination Configuration
        $config['base_url'] = base_url().'/agents/loadRecord';
        $config['use_page_numbers'] = TRUE;
        $config['total_rows'] = $allcount;
        $config['per_page'] = $rowperpage;

        $config['first_link'] = '<<';
        $config['last_link'] = '>>';

        // Initialize
        $this->pagination->initialize($config);

        // Initialize $data Array
        $data['pagination'] = $this->pagination->create_links();
        $data['result'] = $users_record;
        $data['page'] = $page;
        $data['keyword'] = $keyword;

        echo json_encode($data);

    }

}
