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
        // Columns that may be absent in older media plans (optional, filled with NULL)
        $optional = array('PURE MEDIA', 'BG', 'PERIOD');
        
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

        // Optional columns: drop them from the mapping when not found
        foreach ($coldata['pos'] as $key => $pos) {
            if ($pos === 999 && in_array($key, $optional)) {
                unset($coldata['pos'][$key]);
                unset($coldata['field'][$key]);
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
                                $cell = $worksheet->getCellByColumnAndRow($colposition, $row);
                                // Formula cells (e.g. Pure Media) -> use the calculated value
                                if ($cell->isFormula()) {
                                    $val = $cell->getCalculatedValue();
                                } else {
                                    $val = $cell->getValue();
                                }
                                $datarow[$coldata['field'][$colname]] = trim($val);
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
                    
                    $versionExcel = "Xlsx"; // .xlsx only
                    $extFile = ".xlsx";

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
        $coldata['field']   = array(
            'PROJECT'       => 'project',
            'BRIEFNAME'     => 'project_file',
            'QT NO.'        => 'quotation_no',
            'AD FORMAT'     => 'ad_format',
            'BUDGET ALL'    => 'budget_all',
            'PURE MEDIA'    => 'media_cost',
            'BG'            => 'bg_key',
            'PERIOD'        => 'period_text'
        );        
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
    
    function sanitize_text($text, $sep = '-', $len = 74) {
        return rtrim(trim(mb_substr(preg_replace('/[^_a-zA-Z0-9ก-ฮั็่้๊๋าำิีึืุูะเแโใไๅๆ๐-๘ฯ์]+/iu', $sep, strip_tags(trim($text))), 0, $len, 'UTF-8')), $sep);
    }
    
    
    /**
     * Change the contact name (cell E10) for one quotation.
     * GET  /admin/quotationAP/edit_contact?quotation_no=2026/1529
     * POST /admin/quotationAP/edit_contact  (quotation_no, contact_name)
     */
    public function edit_contact()
    {
        $quotation_no = $this->input->post('quotation_no', TRUE);

        if ($quotation_no === null || $quotation_no === '') {
            $quotation_no = $this->input->get('quotation_no', TRUE);
        }

        if ($quotation_no === null || $quotation_no === '') {
            echo json_encode(array('status' => 'error', 'message' => 'quotation_no required'));
            return;
        }

        // POST: save the new contact name
        $contact_name = $this->input->post('contact_name', TRUE);
        if ($contact_name !== null) {
            $this->quotation->update_contact_name($quotation_no, $contact_name);
            echo json_encode(array(
                'status'       => 'success',
                'quotation_no' => $quotation_no,
                'contact_name' => $contact_name
            ));
            return;
        }

        // GET: return current value
        $row = $this->quotation->get_by_quotation_no($quotation_no);
        echo json_encode(array(
            'status'       => 'success',
            'quotation_no' => $quotation_no,
            'contact_name' => ($row !== false && !empty($row['contact_name'])) ? $row['contact_name'] : ' คุณสุวิมล ศีลพิพัฒน์ '
        ));
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
                $period_text = isset($value["period_text"]) ? trim($value["period_text"]) : '';
                $detail_sub = ($period_text !== '') ? 'Boost post ' . $period_text : 'Boost post ' . trim($value["ad_format"]);
                $budget = (float) $value["budget"];
                $budget_all = (float) $value["budget_all"];
                                
                $objPHPExcel = PHPExcel_IOFactory::load($path);
                        
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K10', PHPExcel_Shared_Date::PHPToExcel($quotation_date));
                $objPHPExcel->getActiveSheet()->getStyle("K10")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX15 );

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K12', $quotation_no);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K14', $job_no);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E10', $value["contact_name"]);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E11', $company_title);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E12', $company_address);

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E22', $detail);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E23', $detail_project);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E24', $detail_sub);

                // Pure media from media plan (K24) + Agency Fee 11.5% block + BAHTTEXT formula
                $media_cost = (float) $value["media_cost"];
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K24', ($media_cost > 0) ? $media_cost : $budget);

                $feeFont = array(
                    'font' => array(
                        'name'   => 'CordiaUPC',
                        'size'   => 14,
                        'color'  => array('rgb' => '000000'),
                        'italic' => false
                    )
                );
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J47', ' Agency Fee 11.5%');
                $objPHPExcel->setActiveSheetIndex(0)->getStyle('J47')->applyFromArray($feeFont);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L47', '=L46*11.5/100');
                $objPHPExcel->setActiveSheetIndex(0)->getStyle('L47')->applyFromArray($feeFont);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L48', '=L46*(1+11.5/100)');

                //$objPHPExcel->setActiveSheetIndex(0)->setCellValue('L50', $budget_all);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D50', '=_xlfn.BAHTTEXT(L50)');
                
                //$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D50', '=BAHTTEXT(1999)');

                //Save as an Excel BIFF (xls) file

                $versionExcel = "Xlsx"; // .xlsx only
                $extFile = ".xlsx";
                
                $saveFileName = $quotation_no."_".$file_project;                
                $saveFileName = $this->sanitize_text($saveFileName,'-');                
                $saveFileNameFull = $saveFileName.$extFile;                
                $pathSaveFile1 = $filepath.$saveFileNameFull;
                
                // Insert standard images (header logo, stamp, signatures) — mirrors APQT 2_image_change_2025.py
                $this->insert_quotation_images($objPHPExcel);

                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,$versionExcel);
                
                $objWriter->setPreCalculateFormulas(true);
//                ob_end_clean();                                               
                $objWriter->save($pathSaveFile1);

                // Convert the quotation to PDF (LibreOffice headless) alongside the .xlsx
                $this->convert_to_pdf($pathSaveFile1);
                
//                if ($key==2) { break; }
                
            }                        
        }                                        
    }
    
    /**
     * Convert an .xlsx file to PDF using LibreOffice headless.
     * The PDF is written next to the source file. Returns true on success.
     */
    function convert_to_pdf($xlsx_path)
    {
        if (!file_exists($xlsx_path)) {
            return false;
        }

        $dir = dirname($xlsx_path);
        $cmd = 'soffice --headless --norestore --convert-to pdf --outdir '
             . escapeshellarg($dir) . ' ' . escapeshellarg($xlsx_path)
             . ' 2>&1';

        exec($cmd, $output, $exitCode);

        $pdf = substr($xlsx_path, 0, -5) . '.pdf';
        return ($exitCode === 0 && file_exists($pdf));
    }

    /**
     * Insert the 4 standard images into the quotation sheet
     * (same assets & placement as APQT 2_image_change_2025.py).
     */
    function insert_quotation_images($objPHPExcel)
    {
        $imgPath = realpath(dirname(__FILE__).'/../../../..').'/public_html/upload/quotation/ap/img/';
        if (!is_dir($imgPath)) {
            return;
        }

        $sheet = $objPHPExcel->getActiveSheet();

        // Wipe any existing images in the template
        $sheet->getDrawingCollection()->exchangeArray(array());

        $images = array(
            array('file' => 'ewit_resizeto_075x110in-and_140x100in.png', 'width' => 71.25, 'height' => 104.5, 'anchor' => 'A2'),
            array('file' => 'signature_or_resizeto_126x070in.png',       'width' => 113.4, 'height' => 63.0,  'anchor' => 'G55'),
            array('file' => 'ewit_logo-stamp_2025v2_075x116in.png',      'width' => 75.0,  'height' => 116.4, 'anchor' => 'I53'),
            array('file' => 'signature_ake_resizeto_137x079in.png',      'width' => 123.3, 'height' => 71.1,  'anchor' => 'K55'),
        );

        foreach ($images as $img) {
            $file = $imgPath.$img['file'];
            if (!file_exists($file)) continue;

            $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
            $drawing->setName(pathinfo($file, PATHINFO_FILENAME));
            $drawing->setPath($file);
            $drawing->setHeight($img['height']);
            $drawing->setWidth($img['width']);
            $drawing->setCoordinates($img['anchor']);
            $drawing->setWorksheet($sheet);
        }
    }

    /**
     * Zip the generated files into TWO separate archives:
     *  - {folder}.zip          : quotation .xlsx files only (media plan excluded)
     *  - {folder}_pdf.zip      : PDF files only (filled when PDF conversion runs)
     * Media plan files are removed from the temp dir instead of being zipped.
     */
    function zip_quotationAP($folder="") {

        $filepath    = FCPATH."upload/quotation/ap/temp/{$folder}/";
        $archivepath = FCPATH."upload/quotation/ap/archive/";

        // File names
        $xlsxZipName = "{$folder}.zip";
        $pdfZipName  = "{$folder}_pdf.zip";

        // Remove media plan files from the temp dir (not part of any zip)
        foreach (glob($filepath."Mediaplan*") as $mediaplanFile) {
            @unlink($mediaplanFile);
        }

        $xlsxZip = new ZipArchive;
        $pdfZip  = new ZipArchive;

        $xlsxZipOk = $xlsxZip->open($archivepath.$xlsxZipName, ZIPARCHIVE::CREATE | ZIPARCHIVE::OVERWRITE) === TRUE;
        $pdfZipOk  = $pdfZip->open($archivepath.$pdfZipName,  ZIPARCHIVE::CREATE | ZIPARCHIVE::OVERWRITE) === TRUE;

        $xlsxCount = 0;
        $pdfCount  = 0;

        if ($xlsxZipOk || $pdfZipOk) {

            if ($handle = opendir($filepath)) {
                while (false !== ($entry = readdir($handle))) {
                    if ($entry == "." || $entry == ".." || is_dir($filepath.$entry)) {
                        continue;
                    }

                    $entryLower = strtolower($entry);
                    $isPdf  = (substr($entryLower, -4) === '.pdf');
                    $isXlsx = (substr($entryLower, -5) === '.xlsx');

                    if ($isPdf && $pdfZipOk) {
                        $pdfZip->addFile($filepath.$entry, $folder."/".$entry);
                        $pdfCount++;
                    } elseif ($isXlsx && $xlsxZipOk) {
                        $xlsxZip->addFile($filepath.$entry, $folder."/".$entry);
                        $xlsxCount++;
                    }
                }
                closedir($handle);
            }

            if ($xlsxZipOk) { $xlsxZip->close(); }
            if ($pdfZipOk)  { $pdfZip->close(); }

            array_map('unlink', glob("{$filepath}/*.*"));
            @rmdir($filepath);

            // PDF zip only offered when it actually has content
            $pdfLink = ($pdfZipOk && $pdfCount > 0)
                ? " <a href='/upload/quotation/ap/archive/{$pdfZipName}'>PDF</a>"
                : "";

            return "/upload/quotation/ap/archive/".$xlsxZipName.$pdfLink;
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
        $config['allowed_types'] = 'xlsx'; // .xlsx only
        
        //$filename = (!empty($name))?$name:$uploadFile; 
        //$config['file_name'] = date('Ymdhis')."_".$filename;
        
        $filename = $_FILES[$uploadFile]['name'];

        // Only .xlsx is supported
        if (strtolower(substr($filename, -5)) !== '.xlsx') {
            return array(
                'status'  => 'error',
                'message' => 'Unsupported file type: please upload .xlsx files only (Excel 2007+). Filename: ' . htmlspecialchars($filename)
            );
        }

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
