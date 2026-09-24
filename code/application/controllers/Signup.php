<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Signup extends CI_Controller {
    private $config_grande;
    public function __construct() {
        parent::__construct();
        //$this->load->helper('url');
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        
        $this->load->model('Signup_model');
        //$this->load->library('tcdc_api');

        $this->load->config('grande', true);
        $this->config_grande =  $this->config->item('grande');        
    }

    public function index() {
                
        $cache_id = md5($_SERVER['REQUEST_URI']);
        $template = 'signup.tpl';
        
        
        $this->smarty->setCaching(0);

        if(!$this->smarty->isCached($template, $cache_id)) {
            
            /*
             * can't move position
             */
            //$publications_data = $this->tcdc_api->get_publication_data_by_main();

            $this->smarty->assign('main_menu', $this->lang->line('menu_array'));
            $this->smarty->assign('current_menu', 'home');
            $this->smarty->assign('language', $this->lang->line('lang'));
            $this->smarty->assign('lang', $this->lang->language);

            $this->smarty->assign('title', $this->config_grande['default']['title']);
            $this->smarty->assign('description', $this->config_grande['default']['description']);
            $this->smarty->assign('keywords', $this->config_grande['default']['keywords']);
            $this->smarty->assign('og_url', $this->config_grande['default']['og_url']);
            $this->smarty->assign('og_image', $this->config_grande['default']['og_image']);
            $this->smarty->assign('author', $this->config_grande['default']['author']);
            $this->smarty->assign('og_site_name', $this->config_grande['default']['og_site_name']);
            
            $this->smarty->assign('amphur', '');
            $this->smarty->assign('province', $this->Signup_model->get_province());
            $this->smarty->assign('district', array(''=>$this->lang->line('sign_district_info')));
            $this->smarty->assign('subdistrict', array(''=>$this->lang->line('sign_subdistrict_info')));                        
        }

        $this->smarty->view( $template, $cache_id);

//        $this->smarty->clearAllCache();
    } 
    
    public function verify($token='') {
        
        $token = urldecode($token);
        $token = base64_decode($token);       
        $member = $this->Signup_model->valid_verifytoken($token);
        $email = '';
        
        //print_r($member); die();
        
        if (count($member) > 0) {  
            $email = $member['email'];
            $resetUrl = base_url()."signup/verify/".urlencode(base64_encode($token));                
            $email_title = sprintf($this->lang->line('sign_success_email_title'),$member['email']);
            $email_content = $this->lang->line('sign_success_email_body');

            $this->smarty->setCaching(0);
            $this->smarty->assign('email_title', $email_title);
            $this->smarty->assign('email_content', $email_content);            
            $body = $this->smarty->fetch('includes/email_template.tpl' );
            $subject = $this->lang->line('email_title')." Thank you for registerd.";

            $this->load->library('email');                                                
            $res = $this->email
            ->from('onlinebooking@grandeasset.com', $this->lang->line('email_from'))
            //->reply_to('wutthiphat@ewitewit.com')    // Optional, an account where a human being reads.
            ->to($member['email'])
            ->subject($subject)
            ->message($body)
            ->send();

            if (!$res) {
                $result["status"] = "error";
                $result["message"] = $res;
            }  

            $this->Signup_model->verify_success($member['member_id']);
        }       
        $cache_id = md5($_SERVER['REQUEST_URI']);
        $template = 'signup-verify.tpl';               
        $this->smarty->setCaching(0);
        if(!$this->smarty->isCached($template, $cache_id)) {
            
            /*
             * can't move position
             */
            //$publications_data = $this->tcdc_api->get_publication_data_by_main();

            $this->smarty->assign('main_menu', $this->lang->line('menu_array'));
            $this->smarty->assign('current_menu', 'home');
            $this->smarty->assign('language', $this->lang->line('lang'));
            $this->smarty->assign('lang', $this->lang->language);

            $this->smarty->assign('title', $this->config_grande['default']['title']);
            $this->smarty->assign('description', $this->config_grande['default']['description']);
            $this->smarty->assign('keywords', $this->config_grande['default']['keywords']);
            $this->smarty->assign('og_url', $this->config_grande['default']['og_url']);
            $this->smarty->assign('og_image', $this->config_grande['default']['og_image']);
            $this->smarty->assign('author', $this->config_grande['default']['author']);
            $this->smarty->assign('og_site_name', $this->config_grande['default']['og_site_name']);
            
            $success_content = sprintf($this->lang->line('sign_success_body'),$email); 
            $this->smarty->assign('success_content', $success_content);
            $this->smarty->assign('member', $member);
        }

        $this->smarty->view( $template, $cache_id);

//        $this->smarty->clearAllCache();
    }
    
    
    
    public function get_amphur() {
        $province_id = $this->input->post('province_id');                
        $query = $this->Signup_model->get_amphur($province_id);
        
        $options = '<option value="" disabled="disabled" selected="selected">'.$this->lang->line('sign_district_info').'</option>';
        foreach ($query->result_array() as $row)
        {            
            $options .= '<option value="'.$row['amphur_id'].'">'.$row['amphur_name'].'</option>';
        }        
        echo $options;
    }
    
    public function get_tumbon() {
        $amphur_id = $this->input->post('amphur_id');
        $query = $this->Signup_model->get_tumbon($amphur_id);
        
        $options = '<option value="" disabled="disabled" selected="selected">'.$this->lang->line('sign_subdistrict_info').'</option>';
        foreach ($query->result_array() as $row)
        {            
            $options .= '<option value="'.$row['tumbon_code'].'">'.$row['tumbon_name'].'</option>';
        }        
        echo $options;
    }
    
    public function get_postcode() {
        $tumbon_code = $this->input->post('tumbon_code');
        $query = $this->Signup_model->get_postcode($tumbon_code);
        
        $row = $query->row_array();
        $postcode = (isset($row['zipcode']))?$row['zipcode']:'-';
        echo $postcode;
    }
    
    public function check_unique() {
        $data = $this->input->post(null, TRUE);        
        
        $field_var = key($data);
        $field = str_replace("sign_", "", $field_var);
        $field_db = "members.".str_replace("sign_", "", $field_var);
        
        $id = (isset($data[$field_var]['id']))?($data[$field_var]['id']):'';
        $value = $data[$field_var]['value'];      
        
        if ($id !== '') {
            $query = $this->db->limit(1)->get_where('members', array($field_db => $value, 'member_id !='=>$id));
        } else {
            $query = $this->db->limit(1)->get_where('members', array($field_db => $value));
        }
        
        $result["status"] = "success";
        $result["message"] = "";        
        if ($query->num_rows()>0) {
            $result["status"] = "error";
            $result["message"] = "";
        }        
        
        echo json_encode($result);
    }
    public function signup() {
        
        //echo "signup";
        
        $data = $this->input->post(null, TRUE);
        
        if ($this->lang->line('lang') == 'en') {
            if (isset($data['sign_passport_id'])) {$data['sign_passport_id'] = str_replace("-","",$data['sign_passport_id']);}
        } else {
            if (isset($data['sign_citizen_id'])) {$data['sign_citizen_id'] = str_replace("-","",$data['sign_citizen_id']);}            
        }
        $data['sign_phone'] = str_replace("-","",$data['sign_phone']);
        
        extract($data);
                
        $result["status"] = "success";
        $result["message"] = "";
        
        $reg_lang = $this->lang->line('lang');
        
        $this->form_validation->set_data($data);        
        if ($mode!=='edit') {
            $this->form_validation->set_rules('sign_email', $this->lang->line('sign_email'), 'required|valid_email|is_unique[members.email]',array('required' => $this->lang->line('validate_require'),'is_unique' => $this->lang->line('validate_in_db')));        

            if ($this->lang->line('lang') === 'en') {
                $this->form_validation->set_rules('sign_passport_id', $this->lang->line('sign_passport_id'), 'required|is_unique[members.passport_id]',array('required' => $this->lang->line('validate_require'),'is_unique' =>  $this->lang->line('validate_in_db')));
            } else {
                $this->form_validation->set_rules('sign_citizen_id', $this->lang->line('sign_citizen_id'), 'required|is_unique[members.citizen_id]',array('required' => $this->lang->line('validate_require'),'is_unique' =>  $this->lang->line('validate_in_db')));
            }
            if ($this->form_validation->run() == FALSE)
            {            
                $result["status"] = "error";
                $result["message"] = $this->form_validation->error_array();
                echo json_encode($result);
                return; die();
            }
        } else {
            $reg_lang = $lang;
        }
        
        /*if ($query = $this->Signup_model->has_data("citizen_id",$data)) {
            echo "found sign_citizen_id";
        }
        if ($query = $this->Signup_model->has_data("email",$data)) {
            echo "found sign_email";
        }*/
                
        //$query = $this->Signup_model->save_signup($data);
            
            if (!isset($sign_sameas)) {
                $sign_sameas = '';
            }
                                                
            if ($reg_lang == 'en') {
                if ($sign_sameas === 'on') {
                    $sign_doc_address1 = $sign_address1; 
                    $sign_doc_address2 = $sign_address2; 
                    $sign_doc_country = $sign_country;
                    $sign_doc_postcode = $sign_postcode;
                    $sign_sameas = 'Y';
                } else {
                    $sign_sameas = 'N';
                }
                $data = array(
                    'prefix' => $sign_prefix,
                    'name' => $sign_name,
                    'surname' => $sign_surname,                
                    //'password' => password_hash($sign_password, PASSWORD_BCRYPT),
                    //'citizen_id' => $sign_citizen_id,
                    'passport_id' => $sign_passport_id,
                    'email' => $sign_email,
                    'phone' => $sign_phone,

                    'address_no' => $sign_address1." ".$sign_address2,
                    'country' => $sign_country,
                    'postcode' => $sign_postcode,

                    'doc_address_sameas' => $sign_sameas,
                    'doc_address_no' => $sign_doc_address1." ".$sign_doc_address2,
                    'doc_country' => $sign_doc_country,
                    'doc_postcode' => $sign_doc_postcode,
                    'language' => 'en',
                    'doc_address_sameas' => $sign_sameas
                );                 
            } else {
                
                if (!isset($sign_citizen_id)) {$sign_citizen_id = '';}
                if (!isset($sign_address_no)) {$sign_address_no = '';}
                if (!isset($sign_village)) {$sign_village = '';}
                if (!isset($sign_soi)) {$sign_soi = '';}
                if (!isset($sign_subdistrict)) {$sign_subdistrict = '';}
                if (!isset($sign_district)) {$sign_district = '';}
                if (!isset($sign_province)) {$sign_province = '';}
                if (!isset($sign_doc_subdistrict)) {$sign_doc_subdistrict = '';}
                if (!isset($sign_doc_district)) {$sign_doc_district = '';}
                if (!isset($sign_doc_province)) {$sign_doc_province = '';}
                if (!isset($sign_doc_postcode)) {$sign_doc_postcode = '';}
                
                $sign_subdistrict = $this->Signup_model->get_tumbon_name($sign_subdistrict);
                $sign_district = $this->Signup_model->get_amphur_name($sign_district);
                $sign_province = $this->Signup_model->get_province_name($sign_province);                                
                
                if ($sign_sameas === 'on') {
                    $sign_doc_address_no = $sign_address_no;
                    $sign_doc_village = $sign_village;
                    $sign_doc_soi = $sign_soi;
                    $sign_doc_subdistrict = $sign_subdistrict;
                    $sign_doc_district = $sign_district;
                    $sign_doc_province = $sign_province;
                    $sign_doc_postcode = $sign_postcode;
                    $sign_sameas = 'Y';
                }else {
                    $sign_doc_subdistrict = $this->Signup_model->get_tumbon_name($sign_doc_subdistrict);
                    $sign_doc_district = $this->Signup_model->get_amphur_name($sign_doc_district);
                    $sign_doc_province = $this->Signup_model->get_province_name($sign_doc_province);
                    $sign_sameas = 'N';
                }
                $data = array(
                    'prefix' => $sign_prefix,
                    'name' => $sign_name,
                    'surname' => $sign_surname,                
                    //'password' => password_hash($sign_password, PASSWORD_BCRYPT),
                    'citizen_id' => $sign_citizen_id,
                    //'passport_id' => $sign_passport_id,
                    'email' => $sign_email,
                    'phone' => $sign_phone,

                    'address_no' => $sign_address_no,                
                    'village' => $sign_village,
                    'soi' => $sign_soi,
                    'subdistrict' => $sign_subdistrict,
                    'district' => $sign_district,
                    'province' => $sign_province,
                    'postcode' => $sign_postcode,
                    
                    'doc_address_sameas' => $sign_sameas,
                    'doc_address_no' => $sign_doc_address_no,
                    'doc_village' => $sign_doc_village,
                    'doc_soi' => $sign_doc_soi,
                    'doc_subdistrict' => $sign_doc_subdistrict,
                    'doc_district' => $sign_doc_district,
                    'doc_province' => $sign_doc_province,
                    'doc_postcode' => $sign_doc_postcode,
                    'language' => 'th'
                ); 
            }
            
            if ($mode == "add") {
                $data['password'] = password_hash($sign_password, PASSWORD_BCRYPT);
                $data['member_status'] = 'verify';
                
                $token = password_hash($sign_email, PASSWORD_BCRYPT);                                
                $this->db->set('reset_password_token', $token);
                $this->db->set('create_date', 'NOW()', FALSE);
                $this->db->set('create_ip', $this->input->ip_address());                                                
                $this->db->insert('members', $data);
                
                $resetUrl = base_url()."signup/verify/".urlencode(base64_encode($token));                
                $email_title = sprintf($this->lang->line('sign_verify_email_title'),$sign_name." ".$sign_surname);
                $email_content = sprintf($this->lang->line('sign_verify_email_body'),$resetUrl,$this->lang->line('sign_verify_email_button'));

                $this->smarty->setCaching(0);
                $this->smarty->assign('email_title', $email_title);
                $this->smarty->assign('email_content', $email_content);            
                $body = $this->smarty->fetch('includes/email_template.tpl' );
                $subject = $this->lang->line('email_title')." Verify Email.";

                $this->load->library('email');                                                
                $res = $this->email
                ->from('onlinebooking@grandeasset.com', $this->lang->line('email_from'))
                //->reply_to('wutthiphat@ewitewit.com')    // Optional, an account where a human being reads.
                ->to($sign_email)
                ->subject($subject)
                ->message($body)
                ->send();
                
                if (!$res) {
                    $result["status"] = "error";
                    $result["message"] = $res;
                }
                                
                //$result["message"] = $body;                                
            } else {
                
                $this->db->set('update_date', 'NOW()', FALSE);
                $this->db->set('update_ip', $this->input->ip_address());
                $this->db->set('update_by_id', $this->session->userdata['user']['ID']);
                
                $this->db->where('member_id', $sign_member_id);
                $this->db->update('members', $data);
            }
        
        
        echo json_encode($result);
                
    }
    

}