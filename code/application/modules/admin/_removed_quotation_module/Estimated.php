<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Estimated extends MX_Controller
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
        $this->load->helper('form');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $query = $this->estimated->_get_all_estimated();
        foreach ($query as &$row)
        {
            $row->production_id = $row->production_id;
            $row->production_name = $row->production_name;
            $row->project_id = $this->estimated->_get_project_name($row->project_id);
            $row->total_work_hours = $row->total_work_hours;
            $row->job_no = $row->job_no;
            $row->pm_member_id = $this->estimated->_get_pm_name($row->pm_member_id);
            $row->create_date = mdate('%Y/%m/%d %H:%i:%s', strtotime($row->create_date));
            $row->production_status = $row->production_status;
            $row->actions = div(array('class' => 'btn-group btn-group-xs')) .
                    anchor('admin/estimated/edit/' . $row->production_id, glyphicon('edit'), array('class' => 'btn btn-default','data-toggle' => 'tooltip','title' => 'Edit')) .
                    '<button class="btn btn-default" data-toggle="tooltip" title="Delete" onClick="click_confirm(\'คุณยืนยันที่จะทำการลบ\',\'' . site_url('admin/estimated/delete/' . $row->production_id) . '\')">' . glyphicon('trash') . '</button>' .
                    '<button class="btn btn-default" data-toggle="tooltip" title="Duplicate" onClick="click_confirm(\'คุณยืนยันที่จะทำการ Duplicate\',\'' . site_url('admin/estimated/duplicate/' . $row->production_id) . '\');">' . glyphicon('duplicate') . '</button>' .
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
        $data['production_status'] = $stauts;
        $projects = $this->input->get('projects', TRUE);
        $data['project_id'] = $projects; 
        
        $this->session->set_userdata("e_t_from",$t_from);
        $this->session->set_userdata("e_t_to",$t_to);
        $this->session->set_userdata("e_status",$stauts);
        $this->session->set_userdata("e_search",$search);
        $this->session->set_userdata("e_projects",$projects);

        $query = $this->estimated->_get_all_estimated($data,$t_from,$t_to,$search);
        
        foreach ($query as &$row)
        {
            $row->production_id = $row->production_id;
            $row->production_name = $row->production_name;
            $row->project_id = $this->estimated->_get_project_name($row->project_id);
            $row->total_work_hours = $row->total_work_hours;
            $row->job_no = $row->job_no;
            $row->pm_member_id = $this->estimated->_get_pm_name($row->pm_member_id);
            $row->create_date = mdate('%Y/%m/%d %H:%i:%s', strtotime($row->create_date));
            $row->production_status = $row->production_status;
            $row->actions = div(array('class' => 'btn-group btn-group-xs')) .
                    anchor('admin/estimated/edit/' . $row->production_id, glyphicon('edit'), array('class' => 'btn btn-default','data-toggle' => 'tooltip','title' => 'Edit')) .
                    (($row->production_status=='close')?"":'<button class="btn btn-default" data-toggle="tooltip" title="Delete" onClick="click_confirm(\'คุณยืนยันที่จะทำการลบ\',\'' . site_url('admin/estimated/delete/' . $row->production_id) . '\')">' . glyphicon('trash') . '</button>') .
                    '<button class="btn btn-default" data-toggle="tooltip" title="Duplicate" onClick="click_confirm(\'คุณยืนยันที่จะทำการ Duplicate\',\'' . site_url('admin/estimated/duplicate/' . $row->production_id) . '\');">' . glyphicon('duplicate') . '</button>' .
                    div(null, true);

        }
        $this->smarty->assign('accounts', $query);
        $this->smarty->view_admin( 'load.tpl' );
    }

    public function add()
    {
        $this->form_validation->set_rules('name', 'Full name', 'required');
        /*$this->form_validation->set_rules('project', 'Project', 'required');
        $this->form_validation->set_rules('pm', 'Project', 'required');*/
        if ($this->form_validation->run() == FALSE){
            $template = 'add.tpl';
            
            $this->smarty->assign('projects_options', $this->estimated->_get_projects());
            $this->smarty->assign('member_options', $this->estimated->_get_member());
            $this->smarty->assign('status_options', $this->status_options);
            $this->smarty->view_admin( $template );
        } else {
            $account_id = $this->auth->get_account_id();
            $workhours = $this->input->post('workhours');
            $s_workhours = array_sum($workhours);
            $data = array(
                'project_id' => $this->input->post('project'),
                'production_name' => $this->input->post('name'),
                'production_start' => $this->input->post('from'),
                'production_end' => $this->input->post('to'),
                'pm_member_id' => $this->input->post('pm'),
                'total_work_hours' => $s_workhours,
                'production_status' => $this->input->post('status'),
                'create_by_id' => $account_id,
                'create_ip' => $_SERVER['REMOTE_ADDR'],
                'create_date' => date('Y-m-d H:i:s')
            );
            $new_estimated = $this->estimated->insert_estimated($data);
            $no = $this->input->post('no');
            $name_task = $this->input->post('name_task');
            $description = $this->input->post('description');
            $assignee = $this->input->post('assignee');
            for($x = 0; $x < count($no); $x++) {
                if(!empty($no[$x])){
                    $data_task = array(
                        'production_id' => $new_estimated,
                        'task_no' => $no[$x],
                        'task_name' => $name_task[$x],
                        'task_description' => $description[$x],
                        'work_hours' => $workhours[$x],
                        'member_id' => $assignee[$x],
                        'is_active' => 'Y',
                        'is_delete' => 'N',
                        'create_by_id' => $account_id,
                        'create_ip' => $_SERVER['REMOTE_ADDR'],
                        'create_date' => date('Y-m-d H:i:s')
                    );
                    $this->estimated->insert_production_task($data_task);
                }
            }
            
            if ($new_estimated > 0) {
                $this->session->set_flashdata('msg_system', 'User saved successfully ..');
                redirect('admin/estimated');
            } else {
                $this->session->set_flashdata('msg_system', 'Error saving estimated.');
                redirect('admin/estimated');
            }
        }

    }

    public function duplicate($id = null)
    {
        if ($id == null) {
            $this->session->set_flashdata('msg_system', 'Conta de usuário inexistente.');
            redirect('admin/estimated');
        }
        
        $production = $this->estimated->_get_estimated($id);
        $account_id = $this->auth->get_account_id();
        $data = array(
            'project_id' => $production['project_id'],
            'production_name' => "(copy) ".$production['production_name'],
            'production_start' => $production['production_start'],
            'production_end' => $production['production_end'],
            'pm_member_id' => $production['pm_member_id'],
            'total_work_hours' => $production['total_work_hours'],
            'total_cost_amount' => $production['total_cost_amount'],
            'production_status' => 'active',
            'create_by_id' => $account_id,
            'create_ip' => $_SERVER['REMOTE_ADDR'],
            'create_date' => date('Y-m-d H:i:s')
        );
        $new_estimated = $this->estimated->insert_estimated($data);
        $production_task = $this->estimated->_get_production_task($id);
        if(is_array($production_task)){
            foreach ($production_task as $key => $row) {
                if(isset($row["is_active"])){
                    $data_task = array(
                        'production_id' => $new_estimated,
                        'task_no' => $row["task_no"],
                        'task_name' => $row["task_name"],
                        'task_description' => $row["task_description"],
                        'work_hours' => $row["work_hours"],
                        'member_id' => $row["member_id"],
                        'is_active' => 'Y',
                        'is_delete' => 'N',
                        'create_by_id' => $account_id,
                        'create_ip' => $_SERVER['REMOTE_ADDR'],
                        'create_date' => date('Y-m-d H:i:s')
                    );
                    $this->estimated->insert_production_task($data_task);
                }
            }
        }
        
        $production_cost = $this->estimated->_get_production_cost_by_id($id);
        $this->load->model('Production_cost_model', 'production_cost');
        if(is_array($production_cost)){
            foreach ($production_cost as $key => $row) {
                if(isset($row["is_active"])){
                    $data_task = array(
                        'production_id' => $new_estimated,
                        'job_id' => $row["job_id"],
                        'job_rate' => $row["job_rate"],
                        'job_discount' => $row["job_discount"],
                        'cost_rate' => $row["cost_rate"],
                        'cost_discount' => $row["cost_discount"],
                        'work_hours' => $row["work_hours"],
                        'cost_amount' => $row["cost_amount"],
                        'is_active' => 'Y',
                        'is_delete' => 'N',
                        'create_by_id' => $account_id,
                        'create_ip' => $_SERVER['REMOTE_ADDR'],
                        'create_date' => date('Y-m-d H:i:s')
                    );
                    $this->production_cost->insert_production_cost($data_task);
                }
            }
        }
        
        $quotations = $this->estimated->_get_quotations_by_id($id);
        $this->load->model('Quotation_model', 'quotation');
        $old_quotation = "";
        if(isset($quotations["quotation_id"])){
            $old_quotation = $quotations["quotation_id"];
            $data = array(
                'quotation_date' => $quotations["quotation_date"],
                'quotation_no' => $quotations["quotation_no"],
                'job_no' => $quotations["job_no"],
                'customer_id' => $quotations["customer_id"],
                'project_id' => $quotations["project_id"],
                'production_id' => $new_estimated,
                'production_name' => "(copy) ".$quotations["production_name"],
                'production_start' => $quotations["production_start"],
                'production_end' => $quotations["production_end"],
                'quotation_amount' => $quotations["quotation_amount"],
                'quotation_vat' => $quotations["quotation_vat"],
                'quotation_total' => $quotations["quotation_total"],
                'quotation_credit_term' => $quotations["quotation_credit_term"],
                'issue_member_id' => $quotations["issue_member_id"],
                'quotation_status' => $quotations["quotation_status"],
                'create_by_id' => $account_id,
                'create_ip' => $_SERVER['REMOTE_ADDR'],
                'create_date' => date('Y-m-d H:i:s')
            );
            $new_quotation = $this->quotation->insert_quotation($data);
            $quotations_detail = $this->estimated->_get_quotations_detail_by_id($old_quotation);
            if(is_array($quotations_detail)){
                foreach ($quotations_detail as $key => $row) {
                    if(isset($row["quotation_detail_no"])){
                        $data_task = array(
                            'quotation_id' => $new_quotation,
                            'quotation_detail_no' => $row["quotation_detail_no"],
                            'quotation_detail_name' => $row["quotation_detail_name"],
                            'quotation_detail_description' => $row["quotation_detail_description"],
                            'quotation_detail_qty' => $row["quotation_detail_qty"],
                            'quotation_detail_price' => $row["quotation_detail_price"],
                            'quotation_detail_amount' => $row["quotation_detail_amount"],
                            'is_active' => 'Y',
                            'is_delete' => 'N',
                            'create_by_id' => $account_id,
                            'create_ip' => $_SERVER['REMOTE_ADDR'],
                            'create_date' => date('Y-m-d H:i:s')
                        );
                        $this->quotation->insert_quotations_detail($data_task);
                    }
                    
                }
            }
        }
        

        if ($this->estimated->duplicate_production($id)) {
            $this->session->set_flashdata('msg_system', 'Delete successfully.');
            redirect('admin/estimated');
        } else {
            $this->session->set_flashdata('msg_system', 'Delete unsuccess.');
            redirect('admin/estimated');
        }
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
            $get_quotations = $this->estimated->_get_production($id);
            $get_quotations['production_start'] = substr($get_quotations['production_start'], 0, 10);
            $get_quotations['production_end'] = ($get_quotations['production_end'] == '0000-00-00 00:00:00')?"":$get_quotations['production_end'];
            $get_quotations_task = $this->estimated->_get_production_task($id);

            //echo '<pre>';print_r($id);echo '</pre>';
            $this->smarty->assign('row', $get_quotations);
            $this->smarty->assign('row_task', $get_quotations_task);
            $this->smarty->assign('projects_options', $this->estimated->_get_projects());
            $this->smarty->assign('member_options', $this->estimated->_get_member());
            $this->smarty->assign('status_options', $this->status_options);
            $this->smarty->view_admin( $template );

        } else {
            $account_id = $this->auth->get_account_id();
            $workhours = $this->input->post('workhours');
            $s_workhours = array_sum($workhours);
            $assignee = $this->input->post('assignee');
            $total_cost_amount = 0;
            for($x = 0; $x < count($assignee); $x++) {
                $total_cost_amount += $workhours[$x] * $this->estimated->_get_price_member($assignee[$x]);
            }
            $data = array(
                'production_id' => $id,
                'project_id' => $this->input->post('project'),
                'production_name' => $this->input->post('name'),
                'production_start' => $this->input->post('from'),
                'production_end' => $this->input->post('to'),
                'pm_member_id' => $this->input->post('pm'),
                'total_work_hours' => $s_workhours,
                'total_cost_amount' => $total_cost_amount,
                'production_status' => $this->input->post('status'),
                'update_by_id' => $account_id,
                'update_ip' => $_SERVER['REMOTE_ADDR'],
                'update_date' => date('Y-m-d H:i:s')
            );
            
            $updatedaccount = $this->estimated->update_estimated($data);
            $this->estimated->delete_production_task($id);
            
            $task_id = $this->input->post('task_id');
            $no = $this->input->post('no');
            $name_task = $this->input->post('name_task');
            $description = $this->input->post('description');
            for($x = 0; $x < count($no); $x++) {
                if(!empty($no[$x])){
                    $data_task[$x] = array(
                        'task_id' => $task_id[$x],
                        'production_id' => $id,
                        'task_no' => $no[$x],
                        'task_name' => $name_task[$x],
                        'task_description' => $description[$x],
                        'work_hours' => $workhours[$x],
                        'member_id' => $assignee[$x],
                        'is_active' => 'Y',
                        'is_delete' => 'N',
                        'create_by_id' => $account_id,
                        'create_ip' => $_SERVER['REMOTE_ADDR'],
                        'create_date' => date('Y-m-d H:i:s')
                    );
                    $this->estimated->update_production_task($data_task[$x]);
                }
            }
            if(!empty($id)){
                $this->load->model('Production_cost_model', 'production_cost');
                $get_production_cost = $this->production_cost->_get_production_cost($id);
                foreach($get_production_cost as $v){
                    $data_task = array(
                        'production_id' => $v['production_id'],
                        'job_id' => $v['job_id'],
                        'job_rate' => str_replace(",", "", $v['job_rate']),
                        'job_discount' => $v['job_discount'],
                        'cost_rate' => str_replace(",", "", $v['n_cost_rate']),
                        'cost_discount' => str_replace(",", "", $v['n_cost_discount']),
                        'work_hours' => str_replace(",", "", $v['n_work_hours']),
                        'cost_amount' => str_replace(",", "", $v['n_cost_amount']),
                        'is_active' => 'Y',
                        'is_delete' => 'N',
                        'create_by_id' => $account_id,
                        'create_ip' => $_SERVER['REMOTE_ADDR'],
                        'create_date' => date('Y-m-d H:i:s')
                    );
                    $this->production_cost->update_production_cost($data_task);
                }
            }
            
            
            //echo '<pre>';print_r($data_task);echo '</pre>';exit;

            if ($updatedaccount > 0) {
                $this->session->set_flashdata('msg_system', 'User saved successfully ..');
                redirect('admin/estimated');
            } else {
                $this->session->set_flashdata('msg_system', 'Erro ao salvar o usuário.');
                redirect('admin/estimated');
            }
        }
    }

    public function delete($id = null)
    {
        if ($id == null) {
            $this->session->set_flashdata('msg_system', 'Conta de usuário inexistente.');
            redirect('admin/estimated');
        }

        if ($this->estimated->remove_production($id)) {
            $this->session->set_flashdata('msg_system', 'Delete successfully.');
            redirect('admin/estimated');
        } else {
            $this->session->set_flashdata('msg_system', 'Delete unsuccess.');
            redirect('admin/estimated');
        }
    }
    
    public function export($id = null)
    {
        if ($id == null) {
            $this->session->set_flashdata('msg_system', 'Non-existent ID.');
            redirect('admin/estimated');
        }
        

        $rs = $this->estimated->_get_list_export($id);

        $heading = array('','Scope of Work',"Total\nWork Hr",'Assignee');
        $this->load->library('Excel');
        //Create a new Object
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getActiveSheet()->setTitle('Product Estimate');
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5.14);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(95);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10.14);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(16.43);
        $objPHPExcel->getActiveSheet()->getRowDimension('5')->setRowHeight(73.5);
        
        //Loop Heading
        $rowNumberH = 5;
        $colH = 'A';
        foreach($heading as $h){
            $objPHPExcel->getActiveSheet()->setCellValue($colH.$rowNumberH,$h);
            $objPHPExcel->getActiveSheet()->getStyle($colH.$rowNumberH)->getAlignment()->setWrapText(true);
            $colH++;
        }

        $objPHPExcel->getActiveSheet()->getStyle('C5')->getAlignment()->setTextRotation(90);
        $objPHPExcel->getActiveSheet()->getStyle('D5')->getAlignment()->setTextRotation(90);
        //Loop Result
        
        $objPHPExcel->getActiveSheet()->setCellValue('B7', 'System Feature');
        
        $totn = $rs->num_rows();
        $rows=$rs->result();
        $r = 9;
        $f = 9;
        $total = 0;
        foreach($rows as $row){
            $project_name = $row->project_name;
            $production_name = $row->production_name;
            $production_start = $row->production_start;
            if(isset($row->work_hours)){
                $total += $row->work_hours;
            }
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$r, '')->getColumnDimension('A');
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$r, $row->task_name)->getColumnDimension('B');
            $objPHPExcel->getActiveSheet()->getStyle('B'.$r)->getAlignment()->setWrapText(true);
            $objPHPExcel->getActiveSheet()->getStyle('B'.$r)->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$r, $row->work_hours)->getColumnDimension('C');
            $objPHPExcel->getActiveSheet()->setCellValue('D'.$r, $row->member_name)->getColumnDimension('D');
            $r++;
            if(!empty(trim($row->task_description))){
                $objPHPExcel->getActiveSheet()->setCellValue('B'.$r, trim($row->task_description))->getColumnDimension('B');
                $objPHPExcel->getActiveSheet()->getStyle('B'.$r)->getAlignment()->setWrapText(true);
                $objPHPExcel->getActiveSheet()->getStyle('B'.$r)->getAlignment()->setIndent(3);
                $r++;
            }
            $r++;
        }
        
        $filename = "Estimated_".$project_name."_".$production_name;
        $duration = date("M Y", strtotime($production_start));
        
        $objPHPExcel->getActiveSheet()->setCellValue('A2', $project_name." : ".$production_name." ".$duration);
        $objPHPExcel->getActiveSheet()->setCellValue('A3', "Product Estimate");
        $objPHPExcel->getActiveSheet()->setCellValue('B4', "Resources");
        $objPHPExcel->getActiveSheet()->getStyle('B4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $objPHPExcel->getActiveSheet()->getStyle("C5:D$r")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        
        $objPHPExcel->getActiveSheet()->getStyle("A2:B7")->getFont()->setBold( true );
        //Freeze pane
        //$objPHPExcel->getActiveSheet()->freezePane('A2');
        //Cell Style
        $styleArray = array(
            'borders' => array(
                'outline' => array(
                    'style' => PHPExcel_Style_Border::BORDER_MEDIUM
                )
            )
        );
        $objPHPExcel->getActiveSheet()->getStyle("A3:C3")->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyle("D3")->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyle("A4:A5")->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyle("B4:B5")->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyle("C4:C5")->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyle("D4:D5")->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyle("A6:A$r")->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyle("B6:B$r")->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyle("C6:C$r")->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyle("D6:D$r")->applyFromArray($styleArray);
        $m = $r+1;
        $objPHPExcel->getActiveSheet()->getStyle("A$m")->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyle("B$m")->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->setCellValue("C$m", "=SUM(C$f:C$r)");
        $objPHPExcel->getActiveSheet()->getStyle("C$m")->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyle("D$m")->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getRowDimension($m)->setRowHeight(22.5);
        $objPHPExcel->getActiveSheet()->getStyle("C$m")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle("C$m")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        
        $styleArray_a = array(
            'font'  => array(
            'size'  => 10,
            'name'  => 'Arial Unicode MS'
        ));
        $objPHPExcel->getActiveSheet()->getStyle("A1:D$m")->applyFromArray($styleArray_a);
        
        $styleArray_h = array(
            'font'  => array(
            'size'  => 12,
            'name'  => 'Arial Unicode MS'
        ));
        $objPHPExcel->getActiveSheet()->getStyle('A2')->applyFromArray($styleArray_h);
        $objPHPExcel->getActiveSheet()->getPageSetup()->setPrintArea("A1:D$m");
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

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.$extFile.'"');
        header('Cache-Control: max-age=0');

        $objWriter->save('php://output');
        exit();

    }

}
