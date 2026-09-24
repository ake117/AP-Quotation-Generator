<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Production_cost extends MX_Controller
{
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
        $this->load->model('Production_cost_model', 'production_cost');
        $this->load->helper('form');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data['production_status'] = "active"; 
        $query = $this->estimated->_get_all_estimated($data);
        foreach ($query as &$row)
        {
            $row->production_id = $row->production_id;
            $row->production_name = $row->production_name;
            $row->project_id = $this->estimated->_get_project_name($row->project_id);
            $row->total_work_hours = $row->total_work_hours;
            $row->total_cost_amount = $row->total_cost_amount;
            $row->job_no = $row->job_no;
            $row->pm_member_id = $this->estimated->_get_pm_name($row->pm_member_id);
            $row->create_date = mdate('%Y/%m/%d %H:%i:%s', strtotime($row->create_date));
            $row->production_status = $row->production_status;
            $row->actions = div(array('class' => 'btn-group btn-group-xs')) .
                    anchor('admin/production_cost/edit/' . $row->production_id, glyphicon('edit'), array('class' => 'btn btn-default','data-toggle' => 'tooltip','title' => 'Edit')) .
                    div(null, true);

        }
        $status_options = $this->status_options;
        //$status_options['approved'] = "Approved";
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
        
        $this->session->set_userdata("pc_t_from",$t_from);
        $this->session->set_userdata("pc_t_to",$t_to);
        $this->session->set_userdata("pc_status",$stauts);
        $this->session->set_userdata("pc_search",$search);
        $this->session->set_userdata("pc_projects",$projects);

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
                    anchor('admin/production_cost/edit/' . $row->production_id, glyphicon('edit'), array('class' => 'btn btn-default','data-toggle' => 'tooltip','title' => 'Edit')) .
                    div(null, true);

        }
        $this->smarty->assign('accounts', $query);
        $this->smarty->view_admin( 'load.tpl' );
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
            $get_production_cost = $this->production_cost->_get_production_cost($id);

            //echo '<pre>';print_r($get_production_cost);echo '</pre>';
            $this->smarty->assign('row', $get_quotations);
            $this->smarty->assign('row_task', $get_quotations_task);
            $this->smarty->assign('row_cost', $get_production_cost);
            $this->smarty->assign('projects_options', $this->estimated->_get_projects());
            $this->smarty->assign('member_options', $this->estimated->_get_member());
            $this->smarty->assign('status_options', $this->status_options);
            $this->smarty->view_admin( $template );

        } else {
            $account_id = $this->auth->get_account_id();
            $job_no = $this->input->post('job_no');
            $work_hours = $this->input->post('work_hours');
            $n_cost_amount_input = $this->input->post('n_cost_amount');
            $status = $this->input->post('status');
            $s_workhours = array_sum($work_hours);
            $n_cost_amount_all = 0;
            if(is_array($n_cost_amount_input)){
                foreach ($n_cost_amount_input as $key => $row) {
                    $row = str_replace(",", "",$row);
                    $n_cost_amount_all = $n_cost_amount_all + $row;
                }
            }
            $data = array(
                'production_id' => $id,
                'job_no' => $job_no,
                'total_work_hours' => $s_workhours,
                'total_cost_amount' => $n_cost_amount_all,
                'production_status' => strtolower($status),
                'update_by_id' => $account_id,
                'update_ip' => $_SERVER['REMOTE_ADDR'],
                'update_date' => date('Y-m-d H:i:s')
            );
            $change_production_cost_image = $this->input->post('change_production_cost_image');
            if(!empty($change_production_cost_image)) {
                $image = $this->uploadPhoto('file_production_cost_image', "production_cost","production_id_".$id);
                $data['production_cost_image'] = $image;
            }
            //echo $this->input->post('change_production_cost_image')."_".$image;exit;
            $this->production_cost->update_production_page_cost($data);
            
            if(strtolower($status) == "close"){
                unset($data);
                $data = array(
                    'production_id' => $id,
                    'quotation_status' => strtolower($status),
                    'update_by_id' => $account_id,
                    'update_ip' => $_SERVER['REMOTE_ADDR'],
                    'update_date' => date('Y-m-d H:i:s')
                );
                $this->production_cost->update_quotation_by_production_id($data);
            }
            
            $this->production_cost->delete_production_cost($id);
            
            $job_id = $this->input->post('job_id');
            $job_rate = $this->input->post('job_rate');
            $job_discount = $this->input->post('job_discount');
            $n_cost_discount = $this->input->post('n_cost_discount');
            $n_cost_rate = $this->input->post('n_cost_rate');
            $n_cost_amount = $this->input->post('n_cost_amount');
            for($x = 0; $x < count($job_rate); $x++) {
                $data_task = array(
                    'production_id' => $id,
                    'job_id' => $job_id[$x],
                    'job_rate' => str_replace(",", "", $job_rate[$x]),
                    'job_discount' => $job_discount[$x],
                    'cost_rate' => str_replace(",", "", $n_cost_rate[$x]),
                    'cost_discount' => str_replace(",", "", $n_cost_discount[$x]),
                    'work_hours' => str_replace(",", "", $work_hours[$x]),
                    'cost_amount' => str_replace(",", "", $n_cost_amount[$x]),
                    'is_active' => 'Y',
                    'is_delete' => 'N',
                    'create_by_id' => $account_id,
                    'create_ip' => $_SERVER['REMOTE_ADDR'],
                    'create_date' => date('Y-m-d H:i:s')
                );
                $this->production_cost->update_production_cost($data_task);
            }

            //if ($updatedaccount > 0) {
                $this->session->set_flashdata('msg_system', 'User saved successfully ..');
                redirect('admin/production_cost');
            /*} else {
                $this->session->set_flashdata('msg_system', 'Erro ao salvar o usuário.');
                redirect('admin/production_cost');
            }*/
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
    
    // Upload Photo
    public function jobno($id = null){
        if ($id == null) {
            $data['txt'] = "";
        }else{
            $rs = $this->production_cost->has_job_no($id);
            if($rs){
                $data['txt'] = "Job No. นี้มีอยู่แล้ว";
            }else{
                $data['txt'] = "";
            }
        }
        echo json_encode($data);
    }

    public function export($id = null)
    {
        if ($id == null) {
            $this->session->set_flashdata('msg_system', 'Non-existent ID.');
            redirect('admin/production_cost');
        }
        

        $rs = $this->production_cost->_get_list_export_cost($id);

        $heading = array('Job Title','Rate/Hours',"HOURS",'Amount');
        $this->load->library('Excel');
        //Create a new Object
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getActiveSheet()->setTitle('Production Cost');
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(48.57);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(9.14);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(9);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(13);
        
        $get_production = $this->estimated->_get_production($id);
        $project_name = $this->estimated->_get_project_name($get_production['project_id']);
        $production_name = $get_production['production_name'];
        $job_no = $get_production['job_no'];
        $production_start = $get_production['production_start'];
        $production_end = $get_production['production_end'];
        $pm_name = $this->estimated->_get_pm_name($get_production['pm_member_id']);
        $date_s = date_create($production_start);
        $n_date_s = date_format($date_s,"d M Y");
        $date_e = date_create($production_end);
        $n_date_e = ($production_end != "0000-00-00 00:00:00")?" - ".date_format($date_e,"d M Y"):"";
        //print_r($get_production);exit;
        $objPHPExcel->getActiveSheet()->mergeCells('A1:C1');
        $objPHPExcel->getActiveSheet()->setCellValue('A1', "EWIT : MAN HOURS - Production Cost Sheet Only (without marketing activity cost)");
        $style = array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            ),
            'font'  => array(
                'bold'  => true,
                'color' => array('rgb' => 'FFFFFF'),
            )
        );
        $objPHPExcel->getActiveSheet()->getStyle("A1:C1")->applyFromArray($style);
        $objPHPExcel->getActiveSheet()->getStyle("A1:C1")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('000000');
        
        $objPHPExcel->getActiveSheet()->setCellValue('A2', "Client : $project_name  : $production_name");
        $objPHPExcel->getActiveSheet()->setCellValue('A3', "Project Duration : $n_date_s $n_date_e");
        $objPHPExcel->getActiveSheet()->setCellValue('A4', "Job No : $job_no");
        $objPHPExcel->getActiveSheet()->setCellValue('A5', "PM : $pm_name");
        $objPHPExcel->getActiveSheet()->getStyle("A2:A5")->getFont()->setBold( true );
        
        $objDrawing = new PHPExcel_Worksheet_Drawing();    //create object for Worksheet drawing
        $objDrawing->setName('Customer Signature');        //set name to image
        $objDrawing->setDescription('Customer Signature'); //set description to image
        $signature = realpath(dirname(__FILE__).'/../../../..').'/public_html/assets/images/logo.png';
        $objDrawing->setPath($signature);
        $objDrawing->setOffsetX(6);                       //setOffsetX works properly
        $objDrawing->setOffsetY(-8);                       //setOffsetY works properly
        $objDrawing->setCoordinates("D4");        //set image to cell
        $objDrawing->setWidth(32);                 //set width, height
        $objDrawing->setHeight(32);  

        $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());  //save
        
        //Loop Heading
        $rowNumberH = 7;
        $colH = 'A';
        foreach($heading as $h){
            $objPHPExcel->getActiveSheet()->setCellValue($colH.$rowNumberH,$h);
            $objPHPExcel->getActiveSheet()->getStyle($colH.$rowNumberH)->getAlignment()->setWrapText(true);
            $objPHPExcel->getActiveSheet()->getStyle($colH.$rowNumberH)->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getStyle($colH.$rowNumberH)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle($colH.$rowNumberH)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $colH++;
        }
        //Loop Result
                
        $totn = $rs->num_rows();
        $rows=$rs->result();
        $r = 8;
        $j = $r;
        $jid = 0;
        $total = 0;
        $work_hours = 0;
        $os = array(13, 17, 26, 30, 33, 36, 39, 42);
        foreach($rows as $row){
            $jid = $row->job_id;
            $total += $row->cost_amount;
            $work_hours += $row->work_hours;
            $n_work_hours = ($row->work_hours != 0)?$row->work_hours:"";
            
            if (in_array($jid, $os)) {
                $objPHPExcel->getActiveSheet()->getStyle("A$r:C$r")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('FFF2CC');
                $r++;
            }
            $txt_title = ($row->cost_discount != 0)?$row->job_title." (Man-hour drop ".$row->cost_discount."%)" : $row->job_title;
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$r, $txt_title);
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$r, $row->cost_rate);
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$r, $n_work_hours);
            $cost_amount = ($row->cost_amount != 0)?"=(B$r*C$r)*((100-$row->cost_discount)/100)":"-";
            $objPHPExcel->getActiveSheet()->setCellValue('D'.$r, $cost_amount);
            $r++;
        }
        $styleArray = array(
            'borders' => array(
                'outline' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            )
        );
        $styleArray_db = array(
            'borders' => array(
                'outline' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                ),
                'bottom' => array(
                    'style' => PHPExcel_Style_Border::BORDER_DOUBLE
                ),
            )
        );
        $styleFontRed = array(
            'font'  => array(
                'color' => array('rgb' => 'FF0000'),
            )
        );
        $r--;
        $objPHPExcel->getActiveSheet()->getStyle("A7")->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyle("A8:A$r")->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyle("B7")->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyle("B8:B$r")->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyle("C7")->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyle("C8:C$r")->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyle("D7")->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyle("D8:D$r")->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyle("D7:D$r")->applyFromArray($styleFontRed);
        
        $p = $r;
        $r++;
        $objPHPExcel->getActiveSheet()->setCellValue('A'.$r, "TOTAL");
        $objPHPExcel->getActiveSheet()->setCellValue('C'.$r, "=SUM(C$j:C$p)");
        $objPHPExcel->getActiveSheet()->setCellValue('D'.$r, "=SUM(D$j:D$p)");
        $objPHPExcel->getActiveSheet()->getStyle('A'.$r)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT)->setIndent(1);
        $objPHPExcel->getActiveSheet()->getStyle("B8:B$r")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT)->setIndent(1);
        $objPHPExcel->getActiveSheet()->getStyle("C8:C$r")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT)->setIndent(1);
        $objPHPExcel->getActiveSheet()->getStyle("D8:D$r")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT)->setIndent(1);
        $objPHPExcel->getActiveSheet()->getStyle("A$r")->applyFromArray($styleArray_db);
        $objPHPExcel->getActiveSheet()->getStyle("B$r")->applyFromArray($styleArray_db);
        $objPHPExcel->getActiveSheet()->getStyle("C$r")->applyFromArray($styleArray_db);
        $objPHPExcel->getActiveSheet()->getStyle("D$r")->applyFromArray($styleArray_db);
        $objPHPExcel->getActiveSheet()->getStyle("C$r")->applyFromArray($styleFontRed);
        $objPHPExcel->getActiveSheet()->getStyle("D$r")->applyFromArray($styleFontRed);
        $objPHPExcel->getActiveSheet()->getStyle("B$j:D$r")->getNumberFormat()->setFormatCode('#,##0.00');
        $r++;
        $r++;
        $objPHPExcel->getActiveSheet()->setCellValue('A'.$r, "Description: [Man hours included revised design and copy two times]");
        $objPHPExcel->getActiveSheet()->getStyle('A'.$r)->getFont()->setBold(true);
        $r++;
        $objPHPExcel->getActiveSheet()->setCellValue('A'.$r, "Scope of work");
        $objPHPExcel->getActiveSheet()->getStyle('A'.$r)->getFont()->setBold(true);
        $r++;
        $objPHPExcel->getActiveSheet()->setCellValue('A'.$r, $production_name);
        $objPHPExcel->getActiveSheet()->getStyle('A'.$r)->getFont()->setBold(true);
        $r++;
        $rs2 = $this->estimated->_get_list_export($id);
        $rows2=$rs2->result();
        $j = $r;
        foreach($rows2 as $row){
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$r, $row->task_name);
            $objPHPExcel->getActiveSheet()->getStyle('A'.$r)->getAlignment()->setWrapText(true);
            $objPHPExcel->getActiveSheet()->getStyle('A'.$r)->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getStyle('A'.$r)->getAlignment()->setIndent(1);
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$r, $row->work_hours);
            $objPHPExcel->getActiveSheet()->getStyle('B'.$r)->getAlignment()->setIndent(1);
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$r, str_replace(" - PM", "", $row->member_name));
            $objPHPExcel->getActiveSheet()->getStyle('C'.$r)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $r++;
            if(!empty(trim($row->task_description))){
                $objPHPExcel->getActiveSheet()->setCellValue('A'.$r, trim($row->task_description));
                $objPHPExcel->getActiveSheet()->getStyle('A'.$r)->getAlignment()->setWrapText(true);
                $objPHPExcel->getActiveSheet()->getStyle('A'.$r)->getAlignment()->setIndent(3);
                $r++;
            }
        }
        $p = $r-1;
        $objPHPExcel->getActiveSheet()->setCellValue('A'.$r, "TOTAL");
        $objPHPExcel->getActiveSheet()->getStyle('A'.$r)->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A'.$r)->getAlignment()->setIndent(1);
        $objPHPExcel->getActiveSheet()->setCellValue('B'.$r, "=SUM(B$j:B$p)");
        $objPHPExcel->getActiveSheet()->getRowDimension($r)->setRowHeight(22.5);
        $styleArray_db2 = array(
            'borders' => array(
                'bottom' => array(
                    'style' => PHPExcel_Style_Border::BORDER_DOUBLE
                ),
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            ),
            'font'  => array(
                'bold'  => true,
                'color' => array('rgb' => '000000'),
            )
        );
        $objPHPExcel->getActiveSheet()->getStyle("B$r")->applyFromArray($styleArray_db2);
        
        
        $styleArray_a = array(
            'font'  => array(
                'size'  => 12,
                'name'  => 'Browallia New'
                )
            );
        $objPHPExcel->getActiveSheet()->getStyle("A1:D$r")->applyFromArray($styleArray_a);
        
        $objPHPExcel->getActiveSheet()->getPageSetup()->setPrintArea("A1:D$r");
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
        $filename = $project_name.'_'.$production_name;
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.$extFile.'"');
        header('Cache-Control: max-age=0');

        $objWriter->save('php://output');
        exit();

    }

}
