<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Quotation extends MX_Controller
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
    private $status_options = array(
                'active'  => 'Active',
                'inactive'  => 'Inactive',
                'approved'  => 'Approved',
                'close'  => 'Close',
            );
    function __construct()
    {
        parent::__construct();
        $this->auth->check_permission();
        $this->load->model('Estimated_model', 'estimated');
        $this->load->model('Quotation_model', 'quotation');
        $this->load->helper('form');
        $this->load->library('form_validation');
    }


    /**
     * Empty form fields arrive as '' — an INT column rejects that under strict
     * sql_mode. Turn blanks into NULL and normalise the quotation number to the
     * YYYY/N shape the generator itself produces.
     */
    private function _null_if_empty($value)
    {
        $value = trim((string) $value);
        return ($value === '' || strtoupper($value) === 'N/A') ? NULL : $value;
    }

    private function _normalise_quotation_no($value)
    {
        $value = $this->_null_if_empty($value);
        if ($value === NULL) {
            return NULL;
        }
        // "QT2020/129" and "Q-0002" were typed by hand before the format existed.
        $value = preg_replace('/^(QT|Q-)/i', '', $value);
        return trim($value);
    }

    public function index()
    {
        $query = $this->quotation->_get_all_quotation();
        foreach ($query as &$row)
        {
            $get_estimated = $this->estimated->_get_estimated($row->production_id);
            $total_work_hours = (isset($get_estimated["total_work_hours"]))?$get_estimated["total_work_hours"]:"";
            $row->production_id = $row->production_id;
            $row->production_name = $row->production_name;
            $row->project_id = $this->estimated->_get_project_name($row->project_id);
            $row->total_work_hours = $total_work_hours;
            $row->job_no = $row->job_no;
            $row->pm_member_id = $this->estimated->_get_pm_name($row->issue_member_id);
            $row->create_date = mdate('%Y/%m/%d %H:%i:%s', strtotime($row->create_date));
            $row->quotation_status = $row->quotation_status;
            $row->actions = div(array('class' => 'btn-group btn-group-xs')) .
                    anchor('admin/quotation/edit/' . $row->quotation_id, glyphicon('edit'), array('class' => 'btn btn-default','data-toggle' => 'tooltip','title' => 'Edit')) .
                    '<button class="btn btn-default" data-toggle="tooltip" title="Delete" onClick="click_confirm(\'คุณยืนยันที่จะทำการลบ\',\'' . site_url('admin/quotation/delete/' . $row->quotation_id) . '\');">' . glyphicon('trash') . '</button>' .
                    div(null, true);

        }
        $status_options = $this->status_options;
        $projects_options = $this->estimated->_get_projects();
        $projects_options[''] = "All Project";
        $template = 'index.tpl';
        $this->smarty->assign('accounts', $query);
        $this->smarty->assign('status_options', $status_options);
        $this->smarty->assign('projects_options', $projects_options);
        $this->smarty->view_admin( $template );
    }
    
    public function load()
    {
        $t_from = $this->input->get('t_from', TRUE);
        $t_to = $this->input->get('t_to', TRUE);
        $stauts = $this->input->get('status', TRUE);
        $search = $this->input->get('search', TRUE);
        $data['quotation_status'] = $stauts; 
        $projects = $this->input->get('projects', TRUE);
        $data['project_id'] = $projects; 
        
        $this->session->set_userdata("q_t_from",$t_from);
        $this->session->set_userdata("q_t_to",$t_to);
        $this->session->set_userdata("q_status",$stauts);
        $this->session->set_userdata("q_search",$search);
        $this->session->set_userdata("q_projects",$projects);

        $query = $this->quotation->_get_all_quotation($data,$t_from,$t_to,$search);
        
        foreach ($query as &$row)
        {
            $get_estimated = $this->estimated->_get_estimated($row->production_id);
            $total_work_hours = (isset($get_estimated["total_work_hours"]))?$get_estimated["total_work_hours"]:"";
            $row->production_id = $row->production_id;
            $row->production_name = $row->production_name;
            $row->project_id = $this->estimated->_get_project_name($row->project_id);
            $row->total_work_hours = $total_work_hours;
            $row->job_no = $row->job_no;
            if(isset($get_estimated["pm_member_id"])){
                $row->pm_member_id = $this->estimated->_get_pm_name($get_estimated["pm_member_id"]);
            }
            $row->create_date = mdate('%Y/%m/%d %H:%i:%s', strtotime($row->create_date));
            $row->quotation_status = $row->quotation_status;
            $row->actions = div(array('class' => 'btn-group btn-group-xs')) .
                    anchor('admin/quotation/edit/' . $row->quotation_id, glyphicon('edit'), array('class' => 'btn btn-default','data-toggle' => 'tooltip','title' => 'Edit')) .
                    '<button class="btn btn-default" data-toggle="tooltip" title="Delete" onClick="return confirm(\'' . site_url('admin/quotation/delete/' . $row->quotation_id) . '\');">' . glyphicon('trash') . '</button>' .
                    div(null, true);

        }
        $this->smarty->assign('accounts', $query);
        $this->smarty->view_admin( 'load.tpl' );
    }
    
    public function load_ajax_estimated($id = null)
    {
        $get_quotations = $this->estimated->_get_production($id);
        $data['project_id'] = $get_quotations['project_id'];
        $data['customer_id'] = $get_quotations['projects']['customer_id'];
        $data['production_name'] = $get_quotations['production_name'];
        $data['production_start'] = (empty($get_quotations['production_start']) || $get_quotations['production_start'] == '0000-00-00 00:00:00')?"":mdate('%Y/%m/%d', strtotime($get_quotations['production_start']));
        $data['production_end'] = (empty($get_quotations['production_end']) || $get_quotations['production_end'] == "0000-00-00 00:00:00")?"":mdate('%Y/%m/%d', strtotime($get_quotations['production_end']));
        $data['pm_member_id'] = $get_quotations['pm_member_id'];
        $data['job_no'] = $get_quotations['job_no'];
        $data['quo_date'] = mdate('%Y-%m-%d', strtotime($get_quotations['update_date']));
        
        $this->smarty->assign('data', $data);
        $this->smarty->view_admin( 'load_ajax_estimated.tpl' );
    }
    
    public function load_estimated($id = null)
    {
        $get_quotations_task = $this->estimated->_get_production_task($id);
        $get_quotations = $this->estimated->_get_production($id);
        $this->smarty->assign('row_task', $get_quotations_task);
        $this->smarty->assign('row_quotations', $get_quotations);
        $this->smarty->view_admin( 'load_estimated.tpl' );
    }

    public function add()
    {
        $this->form_validation->set_rules('name', 'Full name', 'required');
        /*$this->form_validation->set_rules('project', 'Project', 'required');
        $this->form_validation->set_rules('pm', 'Project', 'required');*/
        if ($this->form_validation->run() == FALSE){
            $template = 'add.tpl';
            
            $this->smarty->assign('production_select_options', $this->estimated->_get_production_select("Y"));
            $this->smarty->assign('customers_options', $this->quotation->_get_customers());
            $this->smarty->assign('projects_options', $this->estimated->_get_projects());
            $this->smarty->assign('member_options', $this->estimated->_get_member());
            $this->smarty->assign('status_options', $this->status_options);
            $this->smarty->view_admin( $template );
        } else {
            $account_id = $this->auth->get_account_id();
            $no = $this->input->post('no');
            $name_task = $this->input->post('name_task');
            $description = $this->input->post('description');
            $quantity = $this->input->post('quantity');
            $unit_price = $this->input->post('unit_price');
            $amount = $this->input->post('amount');
            $a_amount = str_replace(",", "",$amount[0]);
            $data = array(
                'quotation_date' => $this->input->post('quo_date'),
                'quotation_no' => $this->_normalise_quotation_no($this->input->post('quo_no')),
                'job_no' => $this->input->post('job_no'),
                'customer_id' => $this->input->post('customer'),
                'project_id' => $this->input->post('project'),
                'production_id' => $this->_null_if_empty($this->input->post('production')),
                'production_name' => $this->input->post('name'),
                'production_start' => $this->input->post('from'),
                'production_end' => $this->input->post('to'),
                'quotation_amount' => $a_amount,
                'quotation_vat' => $a_amount*0.07,
                'quotation_total' => $a_amount + ($a_amount*0.07),
                'quotation_credit_term' => "30",
                'issue_member_id' => $this->input->post('pm'),
                'quotation_status' => $this->input->post('status'),
                'create_by_id' => $account_id,
                'create_ip' => $_SERVER['REMOTE_ADDR'],
                'create_date' => date('Y-m-d H:i:s')
            );
            $new_estimated = $this->quotation->insert_quotation($data);
            
            for($x = 0; $x < count($no); $x++) {
                if(!empty($name_task[$x])){
                    $data_task = array(
                        'quotation_id' => $new_estimated,
                        'quotation_detail_no' => $no[$x],
                        'quotation_detail_name' => $name_task[$x],
                        'quotation_detail_description' => $description[$x],
                        'quotation_detail_qty' => $quantity[$x],
                        'quotation_detail_price' => str_replace(",", "",$unit_price[$x]),
                        'quotation_detail_amount' => str_replace(",", "",$amount[$x]),
                        'is_active' => 'Y',
                        'is_delete' => 'N',
                        'create_by_id' => $account_id,
                        'create_ip' => $_SERVER['REMOTE_ADDR'],
                        'create_date' => date('Y-m-d H:i:s')
                    );
                    $this->quotation->insert_quotations_detail($data_task);
                }
            }
            
            if ($new_estimated > 0) {
                $this->session->set_flashdata('msg_system', 'User saved successfully ..');
                redirect('admin/quotation');
            } else {
                $this->session->set_flashdata('msg_system', 'Error saving estimated.');
                redirect('admin/quotation');
            }
        }

    }
    
    function clean_cm($matches)
    {
        return str_replace("," , "" , $matches);
    }

    public function edit($id = null)
    {
        $this->form_validation->set_rules('name', 'Full name', 'required');
        $template = 'edit.tpl';

        if ($this->form_validation->run() == FALSE) {

            if ($id == null) {
                $this->session->set_flashdata('msg_system', 'Non-existent user.');
                redirect('admin/estimated');
            }
            $get_quotations = $this->quotation->_get_quotations($id);
            $get_quotations['production_start'] = substr($get_quotations['production_start'], 0, 10);
            $get_quotations['production_end'] = (empty($get_quotations['production_end']) || $get_quotations['production_end'] == '0000-00-00 00:00:00')?"":substr($get_quotations['production_end'], 0, 10);
            $get_quotations['quotation_date'] = (empty($get_quotations['quotation_date']) || $get_quotations['quotation_date'] == '0000-00-00 00:00:00')?"":substr($get_quotations['quotation_date'], 0, 10);
            $get_quotations_task = $this->quotation->_get_quotations_detail($id);

            //echo '<pre>';print_r($id);echo '</pre>';
            $this->smarty->assign('quotation_id', $id);
            $this->smarty->assign('row', $get_quotations);
            $this->smarty->assign('row_task', $get_quotations_task);
            $this->smarty->assign('production_select_options', $this->estimated->_get_production_select());
            $this->smarty->assign('customers_options', $this->quotation->_get_customers());
            $this->smarty->assign('projects_options', $this->estimated->_get_projects());
            $this->smarty->assign('member_options', $this->estimated->_get_member());
            $this->smarty->assign('status_options', $this->status_options);
            $this->smarty->view_admin( $template );

        } else {
            $account_id = $this->auth->get_account_id();
            $no = $this->input->post('no');
            $name_task = $this->input->post('name_task');
            $description = $this->input->post('description');
            $quantity = $this->input->post('quantity');
            $unit_price = $this->input->post('unit_price');
            $amount = $this->input->post('amount');
            $t_amount = $this->clean_cm($amount[0]);
            //$quotation_detail_id = $this->input->post('quotation_detail_id');
            $data = array(
                'quotation_id' => $id,
                'quotation_date' => $this->input->post('quo_date'),
                'quotation_no' => $this->_normalise_quotation_no($this->input->post('quo_no')),
                'job_no' => $this->input->post('job_no'),
                'customer_id' => $this->input->post('customer'),
                'project_id' => $this->input->post('project'),
                'production_id' => $this->_null_if_empty($this->input->post('production')),
                'production_name' => $this->input->post('name'),
                'production_start' => $this->input->post('from'),
                'production_end' => $this->input->post('to'),
                'quotation_amount' => $t_amount,
                'quotation_vat' => $t_amount*0.07,
                'quotation_total' => $t_amount + ($t_amount*0.07),
                'quotation_credit_term' => "30",
                'issue_member_id' => $this->input->post('pm'),
                'quotation_status' => $this->input->post('status'),
                'update_by_id' => $account_id,
                'update_ip' => $_SERVER['REMOTE_ADDR'],
                'update_date' => date('Y-m-d H:i:s')
            );
            $change_quotation_image = $this->input->post('change_quotation_image');
            if(!empty($change_quotation_image)) {
                $image = $this->uploadPhoto('file_quotation_image', "quotation","quotation_id_".$id);
                $data['quotation_image'] = $image;
            }
            $updatedaccount = $this->quotation->update_quotation($data);
            $this->quotation->remove_quotation_detail($id);
            for($x = 0; $x < count($no); $x++) {
                if(!empty($name_task[$x])){
                    $data_task = array(
                        'quotation_id' => $id,
                        'quotation_detail_no' => $no[$x],
                        'quotation_detail_name' => $name_task[$x],
                        'quotation_detail_description' => $description[$x],
                        'quotation_detail_qty' => $quantity[$x],
                        'quotation_detail_price' => $this->clean_cm($unit_price[$x]),
                        'quotation_detail_amount' => $this->clean_cm($amount[$x]),
                        'is_active' => 'Y',
                        'is_delete' => 'N',
                        'create_by_id' => $account_id,
                        'create_ip' => $_SERVER['REMOTE_ADDR'],
                        'create_date' => date('Y-m-d H:i:s')
                    );
                    $this->quotation->update_quotations_detail($data_task);
                }
            }
            
            if ($updatedaccount > 0) {
                $this->session->set_flashdata('msg_system', 'User saved successfully ..');
                redirect('admin/quotation');
            } else {
                $this->session->set_flashdata('msg_system', 'Erro ao salvar o usuário.');
                redirect('admin/quotation');
            }
        }
    }

    public function delete($id = null)
    {
        if ($id == null) {
            $this->session->set_flashdata('msg_system', 'Conta de usuário inexistente.');
            redirect('admin/quotation');
        }

        if ($this->quotation->remove_quotation($id)) {
            $this->session->set_flashdata('msg_system', 'Delete successfully.');
            redirect('admin/quotation');
        } else {
            $this->session->set_flashdata('msg_system', 'Delete unsuccess.');
            redirect('admin/quotation');
        }
    }
    
        // Upload Photo
    public function uploadPhoto($uploadFieldName, $dir_path, $name = ""){
        $config['upload_path'] = '../public_html/upload/'.$dir_path;
        $config['allowed_types'] = 'gif|jpg|png|pdf';
        $filename = (!empty($name))?$name:$uploadFieldName;
        $config['file_name'] = date('Ymdhis')."_".$filename;
        $this->load->library('upload', $config);
        if ( ! $this->upload->do_upload($uploadFieldName)) {
                $message = $this->upload->display_errors();
                $fileName = $message;
        } else {
                $data = array('upload_data' => $this->upload->data());
                //Insert new share data with upload file
                $fileName = $data['upload_data']['file_name'];
        }
        return $fileName;
    }
    
    public function export($id = null)
    {
        if ($id == null) {
            $this->session->set_flashdata('msg_system', 'Non-existent ID.');
            redirect('admin/quotation');
        }
        

        $quotations = $this->quotation->_get_list_export_quotation($id);
        $quotation = $quotations[0];
        $quotation['project_name'] = $this->estimated->_get_project_name($quotation['project_id']);
        $rs = $this->quotation->_get_quotations_detail($id);
        //echo "<pre>";print_r($quotations);echo "</pre>";exit;
        
        $this->load->library('Excel');
        //Create a new Object
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getActiveSheet()->setShowGridlines(false);
        $objPHPExcel->getActiveSheet()->getStyle("A1:L100")->applyFromArray(
                array(
                    'font'  => array(
                        'name'  => 'CordiaUPC',
                        'size'  => 12
                        ),
                    'alignment' => array(
                            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                            'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                        )
                    )
                );
        $objPHPExcel->getActiveSheet()->setTitle('Product Estimate');
        $objPHPExcel->getActiveSheet()->getRowDimension()->setRowHeight(15.75);
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(9.43);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(4.71);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(1.29);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(12);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(27);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(17.86);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(9.14);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(12);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(0.42);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(10.43);
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(12.86);
        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(14.14);
        $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(0.42);
        $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(6);
        $objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(16.5);
        $objPHPExcel->getActiveSheet()->getRowDimension('3')->setRowHeight(16.5);
        $objPHPExcel->getActiveSheet()->getRowDimension('4')->setRowHeight(11.25);
        $objPHPExcel->getActiveSheet()->getRowDimension('5')->setRowHeight(11.25);
        $objPHPExcel->getActiveSheet()->getRowDimension('6')->setRowHeight(11.25);
        $objPHPExcel->getActiveSheet()->getRowDimension('7')->setRowHeight(11.25);
        $objPHPExcel->getActiveSheet()->getRowDimension('8')->setRowHeight(14.25);
        $objPHPExcel->getActiveSheet()->getRowDimension('9')->setRowHeight(15.75);
        $objPHPExcel->getActiveSheet()->getRowDimension('10')->setRowHeight(15.75);
        $objPHPExcel->getActiveSheet()->getRowDimension('11')->setRowHeight(15.75);
        $objPHPExcel->getActiveSheet()->getRowDimension('12')->setRowHeight(15.75);
        $objPHPExcel->getActiveSheet()->getRowDimension('13')->setRowHeight(15.75);
        $objPHPExcel->getActiveSheet()->getRowDimension('14')->setRowHeight(15.75);
        $objPHPExcel->getActiveSheet()->getRowDimension('15')->setRowHeight(22.5);
        $objPHPExcel->getActiveSheet()->getRowDimension('18')->setRowHeight(2.25);

        $objDrawing = new PHPExcel_Worksheet_Drawing();    //create object for Worksheet drawing
        $objDrawing->setName('Ewit');        //set name to image
        $objDrawing->setDescription('Ewit'); //set description to image
        $signature = realpath(dirname(__FILE__).'/../../../..').'/public_html/assets/images/logo-ewit.png';
        $objDrawing->setPath($signature);
        $objDrawing->setOffsetX(0);                       //setOffsetX works properly
        $objDrawing->setOffsetY(0);                       //setOffsetY works properly
        $objDrawing->setCoordinates("A2");        //set image to cell
        $objDrawing->setWidth(72);                 //set width, height
        $objDrawing->setHeight(106);  
        $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());  //save
        
        $objPHPExcel->getActiveSheet()->setCellValue("D2", "EWIT CO., LTD.  (Head office)");
        $objPHPExcel->getActiveSheet()->getStyle("D2")->applyFromArray(array('font'  => array('bold'  => true,'size'  => 18)));
        $objPHPExcel->getActiveSheet()->setCellValue("D3", "บริษัท อีวิท จำกัด  (สำนักงานใหญ่)");
        $objPHPExcel->getActiveSheet()->getStyle("D3")->applyFromArray(array('font'  => array('bold'  => true,'size'  => 16)));
        $objPHPExcel->getActiveSheet()->setCellValue("D4", "52 Thaniya Building 26th Fl. #B Silom Rd., Suriyawongse, Bangrak, Bangkok 10500 ");
        $objPHPExcel->getActiveSheet()->getStyle("D4:D9")->applyFromArray(array('font'  => array('size'  => 9)));
        $objPHPExcel->getActiveSheet()->setCellValue("D5", "Tel.+66 2 632-9858 Fax.+66 2 632-9859  ");
        $objPHPExcel->getActiveSheet()->setCellValue("D6", "เลขที่ 52 อาคารธนิยะ ชั้น 26 ห้องบี ถนนสีลม แขวงสุริยวงศ์ เขตบางรัก กรุงเทพมหานคร 10500");
        $objPHPExcel->getActiveSheet()->setCellValue("D7", "โทร.+66 2 632-9858 แฟกซ์ +66 2 632-9859  เลขประจำตัวผู้เสียภาษี  0105541030420");
        $objPHPExcel->getActiveSheet()->setCellValue("D8", "www.ewitewit.com  info@ewitewit.com");
        $objPHPExcel->getActiveSheet()->setCellValue("D9", "ชื่อลูกค้า");
        $objPHPExcel->getActiveSheet()->setCellValue("E9", $quotation['customer_name']);
        $objPHPExcel->getActiveSheet()->getStyle("D9:E9")->applyFromArray(array('font'  => array('bold'  => true,'size'  => 14)));
        $objPHPExcel->getActiveSheet()->setCellValue("D10", "Customer Name");
        $objPHPExcel->getActiveSheet()->getStyle("D10")->applyFromArray(array('font'  => array('size'  => 11)));
        $objPHPExcel->getActiveSheet()->setCellValue("D11", "เลขที่ผู้เสียภาษี:");
        $objPHPExcel->getActiveSheet()->getStyle("D11")->applyFromArray(array('font'  => array('bold'  => true,'size'  => 12)));
        $objPHPExcel->getActiveSheet()->setCellValue("E11", $quotation['customer_taxid']);
        $objPHPExcel->getActiveSheet()->getStyle("E11")->applyFromArray(array('font'  => array('name'  => 'Arial','size'  => 10)));
        $objPHPExcel->getActiveSheet()->setCellValue("D12", "ที่อยู่");
        $objPHPExcel->getActiveSheet()->getStyle("D12")->applyFromArray(array('font'  => array('bold'  => true,'size'  => 14)));
        $objPHPExcel->getActiveSheet()->mergeCells('E12:H13');
        $objPHPExcel->getActiveSheet()->setCellValue("E12", $quotation['customer_address']);
        $objPHPExcel->getActiveSheet()->getStyle("E12")->getAlignment()->setWrapText(true);
        $objPHPExcel->getActiveSheet()->getStyle("E12")->applyFromArray(array('font'  => array('name'  => 'Arial','size'  => 10)));
        $objPHPExcel->getActiveSheet()->setCellValue("D13", "Address");
        $objPHPExcel->getActiveSheet()->getStyle("D13")->applyFromArray(array('font'  => array('size'  => 11)));
        $objPHPExcel->getActiveSheet()->setCellValue("D14", "Issue by");
        $objPHPExcel->getActiveSheet()->getStyle("D14")->applyFromArray(array('font'  => array('size'  => 11)));
        $objPHPExcel->getActiveSheet()->setCellValue("E14", $quotation['member_fullname']);
        $objPHPExcel->getActiveSheet()->getStyle("E14")->applyFromArray(array('font'  => array('name'  => 'Arial','size'  => 10)));
        $objPHPExcel->getActiveSheet()->setCellValue("D15", "We are pleased to send you here with our best quotation as the following.");
        $objPHPExcel->getActiveSheet()->getStyle("D15")->applyFromArray(array('font'  => array('size'  => 13)));
        $objPHPExcel->getActiveSheet()->setCellValue("J2", "QUOTATION");
        $objPHPExcel->getActiveSheet()->getStyle("J2")->applyFromArray(array('font'  => array('bold'  => true,'size'  => 18)));
        $objPHPExcel->getActiveSheet()->setCellValue("J3", "ใบเสนอราคา");
        $objPHPExcel->getActiveSheet()->getStyle("J3")->applyFromArray(array('font'  => array('bold'  => true,'size'  => 14)));
        $objPHPExcel->getActiveSheet()->setCellValue("J9", "วันที่");
        $objPHPExcel->getActiveSheet()->getStyle("J9")->applyFromArray(array('font'  => array('bold'  => true,'size'  => 14)));
        $newDate = (!empty($quotation['quotation_date']) && $quotation['quotation_date'] != "0000-00-00 00:00:00")?date("j-M-y", strtotime($quotation['quotation_date'])):"";
        $objPHPExcel->getActiveSheet()->setCellValue("K9", $newDate);
        $objPHPExcel->getActiveSheet()->getStyle("K9")->applyFromArray(array('font'  => array('size'  => 14)));
        $objPHPExcel->getActiveSheet()->mergeCells('K9:L9');
        $objPHPExcel->getActiveSheet()->getStyle("K9:L9")->applyFromArray(array('borders' => array('bottom' => array('style' => PHPExcel_Style_Border::BORDER_DOTTED))));
        $objPHPExcel->getActiveSheet()->setCellValue("J10", "Date");
        $objPHPExcel->getActiveSheet()->getStyle("J10")->applyFromArray(array('font'  => array('bold'  => true,'size'  => 11)));
        $objPHPExcel->getActiveSheet()->setCellValue("J11", "เลขที่");
        $objPHPExcel->getActiveSheet()->getStyle("J11")->applyFromArray(array('font'  => array('bold'  => true,'size'  => 14)));
        $objPHPExcel->getActiveSheet()->setCellValue("K11", $quotation['quotation_no']);
        $objPHPExcel->getActiveSheet()->mergeCells('K11:L11');
        $objPHPExcel->getActiveSheet()->getStyle("K11:L11")->applyFromArray(array('borders' => array('bottom' => array('style' => PHPExcel_Style_Border::BORDER_DOTTED))));
        $objPHPExcel->getActiveSheet()->getStyle("K11")->applyFromArray(array('font'  => array('size'  => 14)));
        $objPHPExcel->getActiveSheet()->setCellValue("J12", "Quotation No.");
        $objPHPExcel->getActiveSheet()->getStyle("J12")->applyFromArray(array('font'  => array('bold'  => true,'size'  => 11)));
        $objPHPExcel->getActiveSheet()->setCellValue("K13", $quotation['job_no']);
        $objPHPExcel->getActiveSheet()->mergeCells('K13:L13');
        $objPHPExcel->getActiveSheet()->getStyle("K13:L13")->applyFromArray(array('borders' => array('bottom' => array('style' => PHPExcel_Style_Border::BORDER_DOTTED))));
        $objPHPExcel->getActiveSheet()->getStyle("K13")->applyFromArray(array('font'  => array('size'  => 14)));
        $objPHPExcel->getActiveSheet()->setCellValue("J13", "Job No.");
        $objPHPExcel->getActiveSheet()->getStyle("J13")->applyFromArray(array('font'  => array('bold'  => true,'size'  => 11)));
        
        $objPHPExcel->getActiveSheet()->setCellValue("D16","เลขที่");
        $objPHPExcel->getActiveSheet()->setCellValue("E16","รายการ");
        $objPHPExcel->getActiveSheet()->setCellValue("J16","จำนวน");
        $objPHPExcel->getActiveSheet()->setCellValue("K16","ราคา");
        $objPHPExcel->getActiveSheet()->setCellValue("L16","รวม");
        $objPHPExcel->getActiveSheet()->mergeCells('E16:I16');
        $objPHPExcel->getActiveSheet()->setCellValue("D17","Item No.");
        $objPHPExcel->getActiveSheet()->setCellValue("E17","Description");
        $objPHPExcel->getActiveSheet()->setCellValue("J17","Quantity");
        $objPHPExcel->getActiveSheet()->setCellValue("K17","Unit Price");
        $objPHPExcel->getActiveSheet()->setCellValue("L17","Amount");
        $objPHPExcel->getActiveSheet()->mergeCells('E17:I17');
        $objPHPExcel->getActiveSheet()->getStyle("D16:L17")->getAlignment()->setWrapText(true);
        $objPHPExcel->getActiveSheet()->getStyle("D16:L17")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('C0C0C0');
        $objPHPExcel->getActiveSheet()->getStyle("D16:L17")->applyFromArray(array('alignment'  => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER)));
        $objPHPExcel->getActiveSheet()->getStyle("D16:L17")->applyFromArray(array('font'  => array('bold'  => true)));
        
        $objPHPExcel->getActiveSheet()->getStyle("J16:J17")->applyFromArray(array('borders'  => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_DOTTED))));
        $objPHPExcel->getActiveSheet()->getStyle("K16:K17")->applyFromArray(array('borders'  => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_DOTTED))));
        $objPHPExcel->getActiveSheet()->getStyle("L16:L17")->applyFromArray(array('borders'  => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_DOTTED))));
        $objPHPExcel->getActiveSheet()->getStyle("D16:L17")->applyFromArray(array('borders'  => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN))));
        
        
        $r = 19;
        $s = $r;
        $total = 0;
        $r++;
        $objPHPExcel->getActiveSheet()->mergeCells("E$r:I$r");
        $objPHPExcel->getActiveSheet()->setCellValue("E$r",$quotation['production_name']);
        $objPHPExcel->getActiveSheet()->getStyle("E$r")->applyFromArray(array('font'  => array('name'  => 'Arial Unicode MS','bold'  => true,'size'  => 14)));
        $objPHPExcel->getActiveSheet()->getStyle("E$r")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('C0C0C0');
        $r++;
        foreach($rs as $row){
            $objPHPExcel->getActiveSheet()->mergeCells("E$r:I$r");
            $objPHPExcel->getActiveSheet()->setCellValue("E$r",$row['quotation_detail_name']);
            $objPHPExcel->getActiveSheet()->getStyle("E$r")->applyFromArray(array('font'  => array('name'  => 'Arial Unicode MS','bold'  => true,'size'  => 10)));
            $objPHPExcel->getActiveSheet()->setCellValue("J$r", $row['quotation_detail_qty']);
            $objPHPExcel->getActiveSheet()->setCellValue("K$r", $row['quotation_detail_price']);
            $objPHPExcel->getActiveSheet()->getStyle("K$r")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $objPHPExcel->getActiveSheet()->setCellValue("L$r", $row['quotation_detail_amount']);
            $objPHPExcel->getActiveSheet()->getStyle("L$r")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $objPHPExcel->getActiveSheet()->getStyle("J$r")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle("K$r")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $objPHPExcel->getActiveSheet()->getStyle("L$r")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $objPHPExcel->getActiveSheet()->getStyle("J$r:L$r")->applyFromArray(array('font'  => array('size'  => 14)));
            $r++;
            if(!empty(trim($row['quotation_detail_description']))){
                $lines = explode("\n", $row['quotation_detail_description']);
                if ( !empty($lines) ) {
                    foreach ( $lines as $line ) {
                        if ( !empty($line) ) {
                            $objPHPExcel->getActiveSheet()->mergeCells("E$r:I$r");
                            $objPHPExcel->getActiveSheet()->getRowDimension("$r")->setRowHeight(-1);
                            $objPHPExcel->getActiveSheet()->setCellValue('E'.$r, $line);
                            $objPHPExcel->getActiveSheet()->getStyle('E'.$r)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
                            $objPHPExcel->getActiveSheet()->getStyle('E'.$r)->getAlignment()->setWrapText(true);
                            $objPHPExcel->getActiveSheet()->getStyle('E'.$r)->getAlignment()->setIndent(3);
                            $r++;    
                        }
                    }
                }
            }
        }
        $objPHPExcel->getActiveSheet()->getStyle("K19:K$r")->getAlignment()->setIndent(1);
        $objPHPExcel->getActiveSheet()->getStyle("E19:I$r")->applyFromArray(array('borders'  => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_DOTTED))));
        $objPHPExcel->getActiveSheet()->getStyle("J19:J$r")->applyFromArray(array('borders'  => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_DOTTED))));
        $objPHPExcel->getActiveSheet()->getStyle("K19:K$r")->applyFromArray(array('borders'  => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_DOTTED))));
        $objPHPExcel->getActiveSheet()->getStyle("D19:L$r")->applyFromArray(array('borders'  => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN))));
        $a = $r;
        $r++;
        $m = $r;
        $objPHPExcel->getActiveSheet()->setCellValue("D$r","Payment  Condition:");
        $objPHPExcel->getActiveSheet()->getStyle("D$r")->applyFromArray(array('font'  => array('name'  => 'CordiaUPC','bold'  => true,'size'  => 14)));
        $objPHPExcel->getActiveSheet()->mergeCells("J$r:K$r");
        $objPHPExcel->getActiveSheet()->setCellValue("J$r"," รวมมูลค่าสินค้า (Subtotal)");
        $objPHPExcel->getActiveSheet()->getStyle("J$r")->applyFromArray(array('font'  => array('name'  => 'CordiaUPC','size'  => 14)));
        $objPHPExcel->getActiveSheet()->setCellValue("L$r","=SUM(L{$s}:L{$a})");
        $objPHPExcel->getActiveSheet()->getStyle("L$r")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        $objPHPExcel->getActiveSheet()->getStyle("L$r")->applyFromArray(array('font'  => array('name'  => 'CordiaUPC','size'  => 14)));
        $objPHPExcel->getActiveSheet()->getStyle("L$r")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $r++;
        $objPHPExcel->getActiveSheet()->getStyle("J$m:K$r")->applyFromArray(array('borders'  => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_DOTTED))));
        $objPHPExcel->getActiveSheet()->getStyle("L$m:L$r")->applyFromArray(array('borders'  => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_DOTTED))));
        $r++;
        $f = $r;
        $quotation_credit_term = (!empty(trim($quotation['quotation_credit_term'])))?$quotation['quotation_credit_term']:30;
        $objPHPExcel->getActiveSheet()->setCellValue("E$r","Credit Term:    ……{$quotation_credit_term}……… Days");
        $objPHPExcel->getActiveSheet()->setCellValue("J$r"," ราคาสุทธิ (Net)");
        $objPHPExcel->getActiveSheet()->mergeCells("J$r:K$r");
        $objPHPExcel->getActiveSheet()->setCellValue("L$r","=L$m");
        $objPHPExcel->getActiveSheet()->getStyle("L$r")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        $objPHPExcel->getActiveSheet()->getStyle("L$r")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $objPHPExcel->getActiveSheet()->getStyle("J$r:L$r")->applyFromArray(array('font'  => array('name'  => 'CordiaUPC','size'  => 14)));
        $g = $r;
        $r++;
        $objPHPExcel->getActiveSheet()->setCellValue("E$r","* บริษัทฯสงวนสิทธิที่จะคิดค่าปรับกรณีชำระหนี้ช้ากว่ากำหนดในอัตรา 1.5% ต่อเดือน");
        $objPHPExcel->getActiveSheet()->setCellValue("J$r"," ภาษีมูลค่าเพิ่ม (Vat)");
        $objPHPExcel->getActiveSheet()->mergeCells("J$r:K$r");
        $objPHPExcel->getActiveSheet()->setCellValue("L$r","=L$g*7/100");
        $objPHPExcel->getActiveSheet()->getStyle("L$r")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        $objPHPExcel->getActiveSheet()->getStyle("L$r")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $objPHPExcel->getActiveSheet()->getStyle("J$r:L$r")->applyFromArray(array('font'  => array('name'  => 'CordiaUPC','size'  => 14)));
        $objPHPExcel->getActiveSheet()->getStyle("J$f:K$r")->applyFromArray(array('borders'  => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_DOTTED))));
        $objPHPExcel->getActiveSheet()->getStyle("L$f:L$r")->applyFromArray(array('borders'  => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_DOTTED))));
        $h = $r;
        $r++;
        $objPHPExcel->getActiveSheet()->setCellValue("E$r","=BAHTTEXT(L$r)");
        $objPHPExcel->getActiveSheet()->getStyle("E$r")->getFont()->setUnderline(true);
        $objPHPExcel->getActiveSheet()->setCellValue("J$r"," รวมทั้งสิ้น (Grand Total)");
        $objPHPExcel->getActiveSheet()->getStyle("J$r")->applyFromArray(array('font'  => array('bold'  => true)));
        $objPHPExcel->getActiveSheet()->mergeCells("J$r:K$r");
        $objPHPExcel->getActiveSheet()->setCellValue("L$r","=L$g+L$h");
        $objPHPExcel->getActiveSheet()->getStyle("L$r")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        $objPHPExcel->getActiveSheet()->getStyle("L$r")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $objPHPExcel->getActiveSheet()->getStyle("D$r:I$r")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('C0C0C0');
        $objPHPExcel->getActiveSheet()->getStyle("E$r:L$r")->applyFromArray(array('font'  => array('name'  => 'CordiaUPC','bold'  => true,'size'  => 14)));
        $objPHPExcel->getActiveSheet()->getStyle("J$r:K$r")->applyFromArray(array('borders'  => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_DOTTED))));
        $objPHPExcel->getActiveSheet()->getStyle("L$r:L$r")->applyFromArray(array('borders'  => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_DOTTED))));
        $objPHPExcel->getActiveSheet()->getStyle("D$m:L$r")->applyFromArray(array('borders'  => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN))));
        $r++;
        $objPHPExcel->getActiveSheet()->getRowDimension($r)->setRowHeight(9);
        $r++;
        $objPHPExcel->getActiveSheet()->getRowDimension($r)->setRowHeight(12.75);
        $r++;
        $objPHPExcel->getActiveSheet()->setCellValue("D$r","อนุมัติ โดย");
        $objPHPExcel->getActiveSheet()->setCellValue("G$r","ตรวจสอบโดย");
        $objPHPExcel->getActiveSheet()->mergeCells("K$r:L$r");
        $objPHPExcel->getActiveSheet()->setCellValue("K$r","ในนาม บริษัท อีวิท จำกัด");
        $objPHPExcel->getActiveSheet()->getStyle("D$r:K$r")->applyFromArray(array('font'  => array('name'  => 'CordiaUPC','bold'  => true,'size'  => 14)));
        $r++;
        $objPHPExcel->getActiveSheet()->getRowDimension($r)->setRowHeight(9.75);
        $objPHPExcel->getActiveSheet()->mergeCells("K$r:L$r");
        $objPHPExcel->getActiveSheet()->setCellValue("K$r","For Ewit Co., Ltd.");
        $objPHPExcel->getActiveSheet()->getStyle("D$r:K$r")->applyFromArray(array('font'  => array('name'  => 'CordiaUPC','bold'  => true,'size'  => 14)));
        $r++;
        $objPHPExcel->getActiveSheet()->getRowDimension($r)->setRowHeight(9);
        $r++;
        $objPHPExcel->getActiveSheet()->getRowDimension($r)->setRowHeight(10.5);
        $r++;
        $objPHPExcel->getActiveSheet()->getRowDimension($r)->setRowHeight(15.75);
        $r++;
        $objPHPExcel->getActiveSheet()->getRowDimension($r)->setRowHeight(15.75);
        $objPHPExcel->getActiveSheet()->mergeCells("D$r:E$r");
        $objPHPExcel->getActiveSheet()->getStyle("D$r:E$r")->applyFromArray(array('borders'  => array('bottom' => array('style' => PHPExcel_Style_Border::BORDER_DOTTED))));
        $objPHPExcel->getActiveSheet()->mergeCells("G$r:I$r");
        $objPHPExcel->getActiveSheet()->getStyle("G$r:I$r")->applyFromArray(array('borders'  => array('bottom' => array('style' => PHPExcel_Style_Border::BORDER_DOTTED))));
        $objPHPExcel->getActiveSheet()->mergeCells("K$r:L$r");
        $objPHPExcel->getActiveSheet()->getStyle("K$r:L$r")->applyFromArray(array('borders'  => array('bottom' => array('style' => PHPExcel_Style_Border::BORDER_DOTTED))));
        $r++;
        $objPHPExcel->getActiveSheet()->getRowDimension($r)->setRowHeight(17.25);
        $objPHPExcel->getActiveSheet()->mergeCells("D$r:E$r");
        $objPHPExcel->getActiveSheet()->setCellValue("D$r","ผู้มีอำนาจลงนาม");
        $objPHPExcel->getActiveSheet()->mergeCells("G$r:I$r");
        $objPHPExcel->getActiveSheet()->setCellValue("G$r","แผนกบัญชี");
        $objPHPExcel->getActiveSheet()->mergeCells("K$r:L$r");
        $objPHPExcel->getActiveSheet()->setCellValue("K$r","ผู้มีอำนาจลงนาม");
        $objPHPExcel->getActiveSheet()->getStyle("D$r:L$r")->applyFromArray(array('font'  => array('name'  => 'CordiaUPC','size'  => 13)));
        $r++;
        $objPHPExcel->getActiveSheet()->getRowDimension($r)->setRowHeight(10.5);
        $objPHPExcel->getActiveSheet()->mergeCells("D$r:E$r");
        $objPHPExcel->getActiveSheet()->setCellValue("D$r","Authorized Signature");
        $objPHPExcel->getActiveSheet()->mergeCells("G$r:I$r");
        $objPHPExcel->getActiveSheet()->setCellValue("G$r","Accountant");
        $objPHPExcel->getActiveSheet()->mergeCells("K$r:L$r");
        $objPHPExcel->getActiveSheet()->setCellValue("K$r","Authorized Signature");
        $objPHPExcel->getActiveSheet()->getStyle("D$r:L$r")->applyFromArray(array('font'  => array('name'  => 'CordiaUPC','size'  => 10)));
        $r++;
        $objPHPExcel->getActiveSheet()->getRowDimension($r)->setRowHeight(15);
        $objPHPExcel->getActiveSheet()->setCellValue("D$r","วันที่/Date");
        $objPHPExcel->getActiveSheet()->getStyle("E$r")->applyFromArray(array('borders'  => array('bottom' => array('style' => PHPExcel_Style_Border::BORDER_DOTTED))));
        $objPHPExcel->getActiveSheet()->setCellValue("G$r","วันที่/Date");
        $objPHPExcel->getActiveSheet()->getStyle("H$r:I$r")->applyFromArray(array('borders'  => array('bottom' => array('style' => PHPExcel_Style_Border::BORDER_DOTTED))));
        $objPHPExcel->getActiveSheet()->setCellValue("K$r","วันที่/Date");
        $objPHPExcel->getActiveSheet()->getStyle("L$r")->applyFromArray(array('borders'  => array('bottom' => array('style' => PHPExcel_Style_Border::BORDER_DOTTED))));
        $objPHPExcel->getActiveSheet()->getStyle("D$r:K$r")->applyFromArray(array('font'  => array('name'  => 'CordiaUPC','size'  => 11)));
        $objPHPExcel->getActiveSheet()->getStyle("L$r")->applyFromArray(array('font'  => array('name'  => 'CordiaUPC','size'  => 14)));
        $objPHPExcel->getActiveSheet()->setCellValue("L$r", $newDate);
        $objPHPExcel->getActiveSheet()->getStyle("L$r")->applyFromArray(array('alignment'  => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER)));
        $objPHPExcel->getActiveSheet()->getStyle("L19:L$r")->getAlignment()->setIndent(1);
        
        
        $objPHPExcel->getActiveSheet()->getPageMargins()->setTop(0.75);
        $objPHPExcel->getActiveSheet()->getPageMargins()->setRight(0.5);
        $objPHPExcel->getActiveSheet()->getPageMargins()->setLeft(0.1);
        $objPHPExcel->getActiveSheet()->getPageMargins()->setBottom(0.75);
        
        $objPHPExcel->getActiveSheet()->getPageSetup()->setPrintArea("A1:L$r");
        $objPHPExcel->getActiveSheet()->getPageSetup()->setFitToPage(true);
        $objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
        $objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
        $objPHPExcel->getActiveSheet()->getPageMargins()->setTop(0.75);
        $objPHPExcel->getActiveSheet()->getPageMargins()->setRight(0.25);
        $objPHPExcel->getActiveSheet()->getPageMargins()->setLeft(0.25);
        $objPHPExcel->getActiveSheet()->getPageMargins()->setBottom(0.75);
        $objPHPExcel->getActiveSheet()->getPageSetup()->setFitToPage(true);
        //Save as an Excel BIFF (xls) file
        $versionExcel = "Excel2007";
        $extFile = $versionExcel == 'Excel2007' ? ".xlsx" : ".xls";
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,$versionExcel);
        $objWriter->setPreCalculateFormulas(true);
        ob_end_clean();
        $filename = 'Quotation'.$quotation['quotation_no']."_".$quotation['project_name']."_".$quotation['production_name'];
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.$extFile.'"');
        header('Cache-Control: max-age=0');

        $objWriter->save('php://output');
        exit();

    }

}
