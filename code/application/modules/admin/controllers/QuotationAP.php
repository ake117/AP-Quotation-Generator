<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class QuotationAP extends MX_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->auth->check_permission();
        $this->load->model('QuotationAP_model', 'quotation');
        $this->load->helper('form');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $template = 'index.tpl';        
        $quotation_doc = $this->quotation->get_quotationAP_doc();        
        $this->smarty->assign('quotation_doc', $quotation_doc);        
        $this->smarty->view_admin( $template );
    }
           
    function excel_search_col($worksheet, $coldata) {     
        
        foreach ($coldata['field'] as $key => $value) {
            $coldata['pos'][$key] = 999;    
        }
        
        $highestColumn = $worksheet->getHighestColumn();
        $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);               
        for($col=0; $col<=$highestColumnIndex; $col++) {            
            $colname = strtoupper(trim($worksheet->getCellByColumnAndRow($col, 1)->getValue()));  
            if (array_key_exists($colname, $coldata['field'])) {
                $coldata['pos'][$colname] = $col;
            }         
        }

        if (array_search(999, $coldata['pos']) == false) {
            $coldata['error'] = false;
            return $coldata;
        } else {
            $coldata['error'] = true;
            return $coldata;
        }        
    }
    
    function secondsToTime($s) {
        $h = floor($s / 3600);
        $s -= $h * 3600;
        $m = floor($s / 60);
        $s -= $m * 60;
        return $h . ':' . sprintf('%02d', $m) . ':' . sprintf('%02d', $s);
    }
    
    function showtime($starttime,$str) {
        $endtime = microtime(true);
        $timediff = $endtime - $starttime;
        $txttime = $this->secondsToTime($timediff);

        return "{$str} ".$txttime;
    }
    
    function import_quotationAP($uploadFile, $filepath, $worksheet_name, $coldata, $starttime, $result, $inpt_quotation_date='',$quotation_running=0) {
                
        $this->load->library('excel');          
        if(isset($_FILES[$uploadFile]["name"]) && $_FILES[$uploadFile]["name"] !== '')
        {            
            $file_quotation = $this->uploadFile($uploadFile, $filepath);    
            if ($file_quotation["status"]=='error') {
                $result["status"] = 'error';
                $result["message"] = $file_quotation["message"];
//                echo json_encode($result);
                return $result;
            }
            
            $path = realpath(dirname(__FILE__).'/../../../..').'/public_html/upload/'.$filepath.'/'.$file_quotation["message"];
            $objPHPExcel = PHPExcel_IOFactory::load($path);
            $result['process'] .= $this->showtime($starttime, "Load {$uploadFile} : ");
            
            $foundTitle = false;
            foreach($objPHPExcel->getWorksheetIterator() as $i => $worksheet)
            {
                $worksheetTitle     = $worksheet->getTitle();
                $highestRow         = $worksheet->getHighestRow(); // e.g. 10
                
                if (strtoupper($worksheetTitle) == strtoupper($worksheet_name)) {
                    $foundTitle = true;
                                        
                    $coldata = $this->excel_search_col($worksheet, $coldata);
                    if ($coldata['error'] == false) {
                        $data_import = array();
                        for($row=2; $row<=$highestRow; $row++) {
                            $datarow = array();
                            $firstcol = '';
                            foreach ($coldata['pos'] as $colname => $colposition) {
                                if ($firstcol=='') { $firstcol=$coldata['field'][$colname]; }
                                $datarow[$coldata['field'][$colname]] = trim($worksheet->getCellByColumnAndRow($colposition, $row)->getValue());
                            }
                            
                            if ($uploadFile == 'file_quotation_data') {
                                $quotation_running++; 
                                $quotation_year = date_format($inpt_quotation_date,"Y");
                                $quotation_no = $quotation_year."/".str_pad($quotation_running, 4, '0', STR_PAD_LEFT);;                                        
                                $datarow['quotation_no'] = $quotation_no;
                                $objPHPExcel->setActiveSheetIndex($i)->setCellValueByColumnAndRow($coldata['pos']['QT NO.'],$row, $quotation_no);
                            }

                            if ($datarow[$firstcol] == '') { break; }
                            $data_import[] = $datarow;          
                        }   
//                        print_r("<pre>");
//                        print_r($data_import);
//                        print_r("</pre>");
                        $this->quotation->insert_data($coldata['table'],$data_import);
                        
                        if ($uploadFile == 'file_quotation_data') {
                            $this->quotation->update_quotationAP_running($inpt_quotation_date, $quotation_running);
                        }
                                                
                        $result['process'] .= $this->showtime($starttime, "Insert Ref: ");
                    } else {
                        $colerror = array_keys($coldata['pos'], 999);
                        $result["status"] = 'error';
                        $result["message"] = "not found column ".implode(', ',$colerror).' in '.$file_quotation["message"];
//                        echo json_encode($result);
                        return $result;
                    }                      
                    break;
                }                
            } 
            if ($foundTitle==false) {
                $result["status"] = 'error';
                $result["message"] = "not found worksheet ".$worksheet_name.' in '.$file_quotation["message"];
//                echo json_encode($result);
                return $result;
            }  
            
            switch ($uploadFile) {
                case 'file_quotation_ref':
                    $resTime = $this->quotation->update_quotationAP_doc('reference',$file_quotation["message"]);
                    break;
                case 'file_quotation_synonym':
                    $resTime = $this->quotation->update_quotationAP_doc('synonym',$file_quotation["message"]);
                    break;
                case 'file_quotation_data':
                    $resTime = $this->quotation->update_quotationAP_doc('data',$file_quotation["message"]);
                    
                    $versionExcel = "Excel2007";
                    $extFile = $versionExcel == 'Excel2007' ? ".xlsx" : ".xls";

                    $saveFileName="Mediaplan";
                    $saveFileNameFull = $saveFileName.$extFile;
                    $placeFilesSave = realpath(dirname(__FILE__).'/../../../..')."/public_html/upload/{$filepath}/";
                    $pathSaveFile1 = $placeFilesSave.$saveFileNameFull;

                    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,$versionExcel);
                    //$objWriter->setPreCalculateFormulas(true);
                    $objWriter->save($pathSaveFile1);

                    $result['process'] .= $this->showtime($starttime,"End: ");
                    
                    break;
                default:
                    break;
            }
                        
            $result["status"] = 'success';
            $result["message"] = $file_quotation["message"];
            $result[$uploadFile] = $resTime;
            
//            echo json_encode($result);
            return $result;
        }
        $result["status"] = 'success';
        $result["message"] = "";
        return $result;
    }
    
    public function quotationAP() {  
        
        ini_set('memory_limit', '1024M');
        set_time_limit(0);
        
        $starttime = microtime(true);
        
//        ini_set('post_max_size','200M');
//        ini_set('upload_max_filesize','200M');
//        ini_set('max_execution_time','200M');
//        ini_set('max_input_time','200M');
//        ini_set('memory_limit','200M');        
        
        $result = array('process'=>'');
        
        $inpt_quotation_date = $this->input->post('quotation_date');
        $inpt_quotation_date = date_create($inpt_quotation_date);
        
        $quotation_date = date_format($inpt_quotation_date,"Y-m-d");
        $quotation_year = date_format($inpt_quotation_date,"Y");                        
        $job_no = date_format($inpt_quotation_date,"ym")."02AP";
        $media_plan_worksheet = date_format($inpt_quotation_date,"my");
        
        $quotation_running = $this->input->post('quotation_running');                 
        
        $filefolder = date('Ymdhis');                
        $filepath = "quotation/ap/temp/".$filefolder;
        
        $coldata['table']   = "quotation_ap_reference";
        $coldata['field']   = array('PROJECT'=> 'project', 'หัวบริษัท (TH)'=> 'company_title','ที่อยู่ (TH)'=> 'company_address','เลขประจำตัวผู้เสียภาษี'=> 'company_taxid');               
        $result = $this->import_quotationAP('file_quotation_ref', $filepath, "หัวบริษัทฯ", $coldata, $starttime, $result);
           
        if ($result["status"]=='error') {
            echo json_encode($result);    
            return;
        }
        
        $coldata['table']   = "quotation_ap_reference_synonym";
        $coldata['field']   = array('INDEX'=> 'project', 'SYNONYM'=> 'synonym');               
        $result = $this->import_quotationAP('file_quotation_synonym', $filepath, "Sheet1", $coldata, $starttime, $result);
       
        if ($result["status"]=='error') {
            echo json_encode($result);    
            return;
        }
        
        $coldata['table']   = "quotation_ap_data";
        $coldata['field']   = array('PROJECT'=> 'project', 'BRIEFNAME'=>'project_file','QT NO.'=>'quotation_no', 'AD FORMAT'=> 'ad_format', 'BUDGET ALL'=> 'budget_all');        
        $result = $this->import_quotationAP('file_quotation_data', $filepath, $media_plan_worksheet, $coldata, $starttime, $result, $inpt_quotation_date, $quotation_running);
        
        if ($result["status"]=='error') {
            echo json_encode($result);    
            return;
        }
        
                        
        //if(isset($_FILES['file_quotation_data']["name"]) && $_FILES['file_quotation_data']["name"] !== '') {
            $result_update = $this->quotation->update_quotationAP($inpt_quotation_date);
            
            if ($result_update !== false) {
                if (count($result_update)>0) {
                    $result["status"] = 'error';
                    $result["message"] = "not found project<br><br> ".implode('<br>',$result_update).'<br>in '.$result["message"];

                }
            } else {
                $result["status"] = 'error';
                $result["message"] = "update data error in ".$result["message"];
            }
        //}
                
        //if ($result["status"] == 'success') {
            $this->export_quotationAP($filepath); 
            
            $archive = $this->zip_quotationAP($filefolder);
            
            //$archive = $this->zip_quotationAP("20201228070826");
            
            $result["message"] = $result["message"]."<br>Download <a href='{$archive}'>Quotation</a>";
        //}
        
        echo json_encode($result);    
        return;
            
    }    
    
    function sanitize_text($text, $sep = '-', $len = 500) {
        return trim(substr(preg_replace('/[^_a-zA-Z0-9ก-ฮั็่้๊๋าำิีึืุูะเแโใไๅๆ๐-๘ฯ์]+/iu', $sep, strip_tags(trim($text))), 0, $len));
    }
    
    
    public function get_quotationAP_running() {
        $inpt_quotation_date = $this->input->post('quotation_date');
        $inpt_quotation_date = date_create($inpt_quotation_date);
        $res = $this->quotation->get_quotationAP_running($inpt_quotation_date);
        echo $res;                
    }
    
    public function test()
    {
//        $project = "Banklangmuang Phaholyothin 50";
//        $res = $this->quotation->get_reference($project);
//        
//        print_r("<pre>");
//        print_r($res);
//        print_r("</pre>");
  
//        $res = $this->quotation->get_quotationAP_running($inpt_quotation_date);
//        print_r("<pre>");
//        print_r($res);
//        print_r("</pre>");

        
        $res = $this->quotation->update_quotationAP_doc('data','xxxxxx');
        print_r("<pre>");
        print_r($res);
        print_r("</pre>");
    }
    

    public function export_quotationAP($filepath='')
    {        
        
        $this->load->library('Excel');               
        $path = realpath(dirname(__FILE__).'/../../../..').'/public_html/upload/quotation/ap/template/template.xlsx';
        
                
        $quotation = $this->quotation->get_data();                        
        if ($quotation !== false) {
            
            if ($filepath=='') {
                $filepath = realpath(dirname(__FILE__).'/../../../..').'/public_html/upload/quotation/ap/Quotation/';
            } else {
                $filepath = realpath(dirname(__FILE__).'/../../../..').'/public_html/upload/'.$filepath.'/';
            }
            
            if (!is_dir($filepath)) {
                mkdir($filepath, 0777, TRUE);
            }
            
            
            
            
            foreach ($quotation as $key => $value) {
                
                $quotation_date = date_create($value["quotation_date"]); 
                $quotation_no = $value["quotation_no"];
                $job_no = $value["job_no"];
                $company_title = $value["company_title"];
                $company_address = $value["company_address"];
                
                $detail = "Media Cost (".date_format($quotation_date,"F Y").")";
                $detail_project = $value["project"];
                $file_project = $value["project_file"];
                $detail_sub = $value["ad_format"];
                $budget = (float) $value["budget"];
                $budget_all = (float) $value["budget_all"];
                                
                $objPHPExcel = PHPExcel_IOFactory::load($path);
                        
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K10', PHPExcel_Shared_Date::PHPToExcel($quotation_date));
                $objPHPExcel->getActiveSheet()->getStyle("K10")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX15 );

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K12', $quotation_no);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K14', $job_no);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E11', $company_title);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E12', $company_address);

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E22', $detail);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E23', $detail_project);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E24', $detail_sub);                      
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K24', $budget);
                
                //$objPHPExcel->setActiveSheetIndex(0)->setCellValue('L50', $budget_all);
                 ;
                 
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D50', baht_text($budget_all) ); 
                
                //$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D50', '=BAHTTEXT(1999)');

                //Save as an Excel BIFF (xls) file

                $versionExcel = "Excel2007";
                $extFile = $versionExcel == 'Excel2007' ? ".xlsx" : ".xls";
                
                $saveFileName = $quotation_no."_".$file_project;                
                $saveFileName = $this->sanitize_text($saveFileName,'-');                
                $saveFileNameFull = $saveFileName.$extFile;                
                $pathSaveFile1 = $filepath.$saveFileNameFull;
                
                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,$versionExcel);
                
                $objWriter->setPreCalculateFormulas(true);
//                ob_end_clean();                                               
                $objWriter->save($pathSaveFile1);                
                
//                if ($key==2) { break; }
                
            }                        
        }                                        
    }
    
    function zip_quotationAP($folder="") {        
        
        $this->load->library('zip');
        
        $filepath = FCPATH."upload/quotation/ap/temp/{$folder}/";
        $archivepath = FCPATH."upload/quotation/ap/archive/";        

        // File name
        $filename = "{$folder}.zip";
        
        
        $zip = new ZipArchive;
        
        if ($zip->open($archivepath.$filename, ZIPARCHIVE::CREATE | ZIPARCHIVE::OVERWRITE) === TRUE)
        {
            if ($handle = opendir($filepath))
            {
                // Add all files inside the directory
                while (false !== ($entry = readdir($handle)))
                {
                    if ($entry != "." && $entry != ".." && !is_dir($filepath . $entry))
                    {                        
                        $zip->addFile($filepath . $entry, $folder."/".$entry);
                    }
                }
                closedir($handle);
            }
            $zip->close();
            
            array_map('unlink', glob("{$filepath}/*.*"));
            rmdir($filepath);
            
            return "/upload/quotation/ap/archive/".$filename;
        } else {
            return "error";
        }                
    }    
    
    
    // Upload File
    public function uploadFile($uploadFile, $dir_path, $name = ""){
        
        $upload_path = '../public_html/upload/'.$dir_path;
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0777, TRUE);
        }
                        
        $config['upload_path'] = $upload_path;
        $config['allowed_types'] = 'xls|xlsx';
        
        //$filename = (!empty($name))?$name:$uploadFile; 
        //$config['file_name'] = date('Ymdhis')."_".$filename;
        
        $filename = $_FILES[$uploadFile]['name'];
        $config['file_name'] = $filename;
                                
        $this->load->library('upload', $config);
        $this->upload->initialize($config);     
        $result = array();
        if ( ! $this->upload->do_upload($uploadFile)) {
                $message = $this->upload->display_errors();                
                $result['status'] = 'error';
                $result['message'] = $message;
        } else {
                $data = array('upload_data' => $this->upload->data());
                //Insert new share data with upload file
                $fileName = $data['upload_data']['file_name'];
                $result['status'] = 'success';
                $result['message'] = $fileName;
        }
        return $result;
    }
    


}
