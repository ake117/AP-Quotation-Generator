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
        $template = 'quotationAP/index.tpl';
        $quotation_doc = $this->quotation->get_quotationAP_doc();        
        $this->smarty->assign('quotation_doc', $quotation_doc);        
        $this->smarty->view_admin( $template );
    }
           
    function excel_search_col($worksheet, $coldata) {     
        require_once APPPATH . 'libraries/QuotationBatch.php';

        // Columns that may be absent in older media plans (optional, filled with NULL)
        $optional = array('PURE MEDIA', 'BG', 'PERIOD');
        
        foreach ($coldata['field'] as $key => $value) {
            $coldata['pos'][$key] = 999;    
        }
        
        $highestColumn = $worksheet->getHighestColumn();
        // Media plan columns used for quotations are A-N (14); ignore anything past that.
        $highestColumnIndex = min(PHPExcel_Cell::columnIndexFromString($highestColumn), 14);

        // The PO Status file keeps its headers on row 4 (counters sit above them); an
        // older media plan starts on row 1. Use whichever row names the most columns.
        $headerRow = isset($coldata['header_row']) ? (int) $coldata['header_row'] : 0;
        if ($headerRow < 1) {
            $probe = array();
            for ($probeRow = 1; $probeRow <= 8; $probeRow++) {
                for ($probeCol = 1; $probeCol <= $highestColumnIndex; $probeCol++) {
                    $probe[$probeRow][$probeCol - 1] = $worksheet->getCell([$probeCol, $probeRow])->getValue();
                }
            }
            $headerRow = QuotationBatch::headerRow($probe, array_keys($coldata['field']));
        }

        for($col=1; $col<=$highestColumnIndex; $col++) {            
            $colname = strtoupper(trim(($worksheet->getCell([$col, $headerRow])->getValue()) ?? ''));  
            if (array_key_exists($colname, $coldata['field'])) {
                $coldata['pos'][$colname] = $col-1;            
            }         
        }

        // Optional columns: drop them from the mapping when not found
        foreach ($coldata['pos'] as $key => $pos) {
            if ($pos === 999 && in_array($key, $optional)) {
                unset($coldata['pos'][$key]);
                unset($coldata['field'][$key]);
            }
        }

        $coldata['header_row'] = $headerRow;

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
                        $sheetWrites = array();
                        $firstDataRow = (isset($coldata['header_row']) ? (int) $coldata['header_row'] : 1) + 1;
                        for($row=$firstDataRow; $row<=$highestRow; $row++) {
                            $datarow = array();
                            $firstcol = '';
                            foreach ($coldata['pos'] as $colname => $colposition) {
                                if ($firstcol=='') { $firstcol=$coldata['field'][$colname]; }
                                $cell = $worksheet->getCell([$colposition+1, $row]);
                                // Formula cells (e.g. Pure Media) -> use the calculated value
                                if ($cell->isFormula()) {
                                    $val = $cell->getCalculatedValue();
                                } else {
                                    $val = $cell->getValue();
                                }
                                $datarow[$coldata['field'][$colname]] = trim(($val) ?? '');
                            }
                            

                            if ($datarow[$firstcol] == '') { break; }

                            if ($uploadFile == 'file_quotation_data') {
                                require_once APPPATH . 'libraries/QuotationBatch.php';

                                $quotation_year = date_format($inpt_quotation_date,"Y");

                                // One plan row can stand for several documents: the note
                                // column lists a ProjectID each, so the row becomes that
                                // many quotation rows with consecutive numbers (rows
                                // 49/49 of the September sheet: 2026/1591, 2026/1592).
                                $ids = QuotationBatch::projectIds((string) ($datarow['note'] ?? ''));
                                $count = max(1, count($ids));
                                $issued = QuotationBatch::consecutiveNumbers($quotation_year, $quotation_running, $count);
                                $quotation_running = $issued['last'];

                                $splitRows = QuotationBatch::splitRows($datarow, $issued['numbers']);

                                // Written back to the sheet only after every row has been
                                // read: inserting rows mid-loop would shift the ones still
                                // to come.
                                $sheetWrites[$row] = $splitRows;

                                foreach ($splitRows as $splitRow) {
                                    $data_import[] = $splitRow;
                                }
                            } else {
                                $data_import[] = $datarow;
                            }
                        }   
//                        print_r("<pre>");
//                        print_r($data_import);
//                        print_r("</pre>");

                        // Bottom-up, so an inserted row never moves a row still waiting.
                        if (!empty($sheetWrites)) {
                            require_once APPPATH . 'libraries/MediaPlanWriter.php';
                            krsort($sheetWrites);
                            foreach ($sheetWrites as $writeRow => $documents) {
                                MediaPlanWriter::writeRow($worksheet, $writeRow, $documents, $coldata['pos'], $coldata['field']);
                            }
                        }

                        if ($this->quotation->insert_data($coldata['table'], $data_import) === FALSE) {
                            $dberr = $this->db->error();
                            $result['status']  = 'error';
                            $result['message'] = 'Insert into '.$coldata['table'].' failed: '
                                .($dberr['message'] !== '' ? $dberr['message'] : 'unknown database error')
                                .' (code '.$dberr['code'].')';
                            return $result;
                        }
                        
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

                    // Media plan file for the client zip: month/year from the quotation date
                    $saveFileName = "AP_Media_Plan_".date_format($inpt_quotation_date,"M")."_".date_format($inpt_quotation_date,"Y");
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
        
        // Surface DB/exception details to the AJAX caller instead of dying with an HTML error page
        $this->db->db_debug = FALSE;
        try {

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
            'PERIOD'        => 'period_text',
            'NOTE'          => 'note'
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
        
        } catch (Throwable $e) {
            $result['status']  = 'error';
            $result['message'] = get_class($e).': '.$e->getMessage()
                .' @ '.basename($e->getFile()).':'.$e->getLine();
        }

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
        require_once APPPATH . 'libraries/QuotationExporter.php';
        require_once APPPATH . 'libraries/QuotationBatch.php';

        $root = realpath(dirname(__FILE__).'/../../../..');
        $template = $root.'/public_html/upload/quotation/ap/template/template.xlsx';

        $quotation = $this->quotation->get_data();

        if ($quotation !== false) {

            if ($filepath=='') {
                $filepath = $root.'/public_html/upload/quotation/ap/Quotation/';
            } else {
                $filepath = $root.'/public_html/upload/'.$filepath.'/';
            }

            if (!is_dir($filepath)) {
                mkdir($filepath, 0777, TRUE);
            }

            // Rendering lives in QuotationExporter so that the very code that writes
            // a quotation can be exercised without CodeIgniter, a database or HTTP:
            // see code/tests/QuotationExporterTest.php.
            $exporter = new QuotationExporter(
                $template,
                $root.'/public_html/upload/quotation/ap/img/'
            );

            // One plan row can become several documents (the usual case: one budget
            // line split into equal documents) or one document holding several items.
            // QuotationBatch decides; the renderer still writes a single quotation.
            $batch = new QuotationBatch($exporter);

            // Numbers are counted for the month the quotations belong to; the rows carry
            // their own date, so the first one speaks for the selection.
            $firstRow = reset($quotation);
            $dateSource = is_array($firstRow) && !empty($firstRow['quotation_date'])
                ? $firstRow['quotation_date']
                : date('Y-m-d');
            $quotation_date = date_create($dateSource);

            // A split row issues several documents, so it needs several numbers. They run
            // on from the counter the import left behind (2026/1591, 2026/1592, …) and the
            // counter is saved once, after the whole export, so a failure part-way through
            // does not burn numbers that never printed.
            $year = (int) date_format($quotation_date, 'Y');

            // This month's counter first; a fresh month continues where the previous one
            // stopped; and never below a number that is already on file.
            $running = $this->quotation->get_quotationAP_running_exact($quotation_date);
            if ($running === false) {
                $running = $this->quotation->get_quotationAP_running($quotation_date);
                $running = ($running === false) ? 0 : (int) $running;
            }
            $running = max($running, QuotationBatch::highestNumber(
                $this->quotation->get_quotation_nos($year)
            ));

            foreach ($quotation as $value) {
                $count = $batch->documentCount($value);
                if ($count > 1 && empty($value['split_quotation_nos'])) {
                    $issued = QuotationBatch::consecutiveNumbers($year, $running, $count);
                    $value['split_quotation_nos'] = $issued['numbers'];
                    $running = $issued['last'];
                }
                $batch->export($value, $filepath);
            }

            $this->quotation->update_quotationAP_running($quotation_date, $running);
        }
    }
    
    /**
     * Import the month's quotations straight from the Google Sheet.
     *
     * No download, no upload: the tab named after the quotation month (0926, 1026, …) is
     * read through the Sheets API, split into one row per ProjectID exactly like the file
     * import does, inserted into the database with consecutive numbers, and the numbers are
     * written back into the sheet so the team's PO Status file is already up to date.
     */
    public function import_from_google()
    {
        require_once APPPATH . 'libraries/GoogleSheetsClient.php';
        require_once APPPATH . 'libraries/SheetTable.php';
        require_once APPPATH . 'libraries/QuotationBatch.php';

        $this->load->config('google');

        $starttime = microtime(TRUE);
        $result = array('status' => 'error', 'message' => '', 'process' => '');

        $inpt_quotation_date = date_create($this->input->post('quotation_date'));
        if (!$inpt_quotation_date) {
            $result['message'] = 'quotation_date is missing';

            return $this->output->set_content_type('application/json')->set_output(json_encode($result));
        }

        $quotation_year = (int) date_format($inpt_quotation_date, "Y");
        $tab = date_format($inpt_quotation_date, "my");     // 0926, 1026, ...

        $fields = array(
            'PROJECT'       => 'project',
            'BRIEFNAME'     => 'project_file',
            'QT NO.'        => 'quotation_no',
            'AD FORMAT'     => 'ad_format',
            'BUDGET ALL'    => 'budget_all',
            'PURE MEDIA'    => 'media_cost',
            'BG'            => 'bg_key',
            'PERIOD'        => 'period_text',
            'NOTE'          => 'note',
        );

        try {
            $client = new GoogleSheetsClient($this->config->item('google_service_account_json'));
            $spreadsheetId = $this->config->item('google_spreadsheet_id');

            $values = $client->getValues($spreadsheetId, "'" . $tab . "'!A1:AZ5000");
            $table = SheetTable::read($values, $fields, array('PURE MEDIA', 'BG', 'PERIOD', 'NOTE'));

            if (!empty($table['missing'])) {
                $result['message'] = 'not found column ' . implode(', ', $table['missing']) . ' in tab ' . $tab;

                return $this->output->set_content_type('application/json')->set_output(json_encode($result));
            }

            // Numbers run on from where the month (or the sheet itself) left off.
            $running = $this->quotation->get_quotationAP_running_exact($inpt_quotation_date);
            if ($running === false) {
                $running = $this->quotation->get_quotationAP_running($inpt_quotation_date);
                $running = ($running === false) ? 0 : (int) $running;
            }
            $running = max($running, QuotationBatch::highestNumber($this->quotation->get_quotation_nos($quotation_year)));

            $data_import = array();
            $writes = array();

            foreach ($table['rows'] as $row) {
                $ids = QuotationBatch::projectIds((string) ($row['note'] ?? ''));
                $count = max(1, count($ids));
                $issued = QuotationBatch::consecutiveNumbers($quotation_year, $running, $count);
                $running = $issued['last'];

                $documents = QuotationBatch::splitRows($row, $issued['numbers']);
                $writes[$row['_row']] = $documents;

                foreach ($documents as $document) {
                    $data_import[] = $document;
                }
            }

            if (empty($data_import)) {
                $result['message'] = 'no rows found in tab ' . $tab;

                return $this->output->set_content_type('application/json')->set_output(json_encode($result));
            }

            if ($this->quotation->insert_data('quotation_ap_data', $data_import) === FALSE) {
                $dberr = $this->db->error();
                throw new RuntimeException('Insert failed: '.($dberr['message'] !== '' ? $dberr['message'] : 'unknown database error').' (code '.$dberr['code'].')');
            }
            $this->quotation->update_quotationAP_running($inpt_quotation_date, $running);

            $result['process'] .= $this->showtime($starttime, "Insert: ");
            $result['rows'] = count($data_import);

            if ($this->config->item('google_write_back')) {
                $result['write_back'] = $this->write_numbers_back($client, $spreadsheetId, $tab, $table, $writes);
            }

            $result['status'] = 'success';
            $result['message'] = count($data_import) . ' quotations from tab ' . $tab;
        } catch (Exception $e) {
            $result['message'] = $e->getMessage();
        }

        return $this->output->set_content_type('application/json')->set_output(json_encode($result));
    }

    /**
     * Put the numbers back where the rows came from: insert a row for every extra document
     * and write the per-document columns. Runs bottom-up so an inserted row never shifts a
     * row that is still waiting.
     *
     * @return array<int, string> what happened, per sheet row
     */
    private function write_numbers_back($client, $spreadsheetId, $tab, array $table, array $writes)
    {
        $perDocument = array(
            'quotation_no' => 'QT NO.',
            'project'      => 'PROJECT',
            'project_file' => 'BRIEFNAME',
            'note'         => 'NOTE',
            'budget_all'   => 'BUDGET ALL',
            'media_cost'   => 'PURE MEDIA',
        );

        $log = array();

        try {
            $sheetId = $client->sheetId($spreadsheetId, $tab);
        } catch (Exception $e) {
            return array('skipped: ' . $e->getMessage());
        }

        krsort($writes);

        foreach ($writes as $sheetRow => $documents) {
            $extra = count($documents) - 1;
            if ($extra > 0) {
                $client->insertRows($spreadsheetId, $sheetId, $sheetRow, $extra);
            }

            foreach ($perDocument as $key => $header) {
                if (!isset($table['positions'][$header]) || $table['positions'][$header] === 999) {
                    continue;
                }

                $values = array();
                foreach ($documents as $document) {
                    $values[] = array(isset($document[$key]) ? $document[$key] : '');
                }

                $range = SheetTable::columnRange($tab, (int) $table['positions'][$header], $sheetRow, $sheetRow + $extra);
                $client->updateValues($spreadsheetId, $range, $values);
            }

            $log[] = 'row ' . $sheetRow . ': ' . count($documents) . ' document(s)';
        }

        return $log;
    }

    /**
     * Convert an .xlsx file to PDF using LibreOffice headless.
     * The PDF is written next to the source file. Returns true on success.
     */
    function convert_to_pdf($xlsx_path)
    {
        require_once APPPATH . 'libraries/QuotationExporter.php';
        $root = realpath(dirname(__FILE__).'/../../../..');
        $exporter = new QuotationExporter('', $root.'/public_html/upload/quotation/ap/img/');

        return $exporter->convertToPdf($xlsx_path) !== null;
    }

    /**
     * Insert the 4 standard images into the quotation sheet
     * (same assets & placement as APQT 2_image_change_2025.py).
     */
    function insert_quotation_images($objPHPExcel)
    {
        require_once APPPATH . 'libraries/QuotationExporter.php';
        $root = realpath(dirname(__FILE__).'/../../../..');
        $exporter = new QuotationExporter('', $root.'/public_html/upload/quotation/ap/img/');

        $exporter->insertImages($objPHPExcel);
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

        // The media plan now travels back with the quotations: it carries the QT numbers
        // the import wrote, one row per document, so the team can paste it straight into
        // the PO Status file.

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
                    } elseif ($isXlsx && $xlsxZipOk && preg_match('/^\d{4}[-_]/', $entry)) {
                        // quotation files start with the quotation no ("2026-1689_...");
                        // everything else in temp (uploaded media plan, AP_Media_Plan_*.xlsx) stays out of the zip
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
