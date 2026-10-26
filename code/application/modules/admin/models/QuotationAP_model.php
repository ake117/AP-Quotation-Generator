<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class QuotationAP_model extends MY_Model
{

    function insert_data($table, $data)  
    {
        $this->db->truncate($table);
        $this->db->insert_batch($table, $data);
    } 
    
    function get_quotationAP_doc()  
    {
$query = <<<SQL
        SELECT q.*,acc.email
FROM quotation_ap_doc q
LEFT JOIN cms_accounts acc ON acc.id = q.create_by_id
LEFT JOIN quotation_ap_doc b ON b.doc_type = q.doc_type AND q.create_date < b.create_date
WHERE b.create_date is NULL
SQL;
        $result = array();
        $res = $this->db->query("$query")->result_array();
        foreach ($res as $key => $value) {
            $result[$value['doc_type']] = $value;
        }

        return $result;
    }
    
    function get_quotationAP_running($inpt_quotation_date)  
    {
        $inpt_quotation_date->modify('-1 month');                
        $quotation_year = (int) date_format($inpt_quotation_date,"Y");
        $quotation_month = (int) date_format($inpt_quotation_date,"m");
        
        $this->db->select('*');
        $this->db->from('quotation_ap_running');
        $this->db->where('quotation_month', $quotation_month);
        $this->db->where('quotation_year', $quotation_year);
        $query = $this->db->get();
        $rs = $query->result_array();
        return ($query->num_rows()>0)?$rs[0]['running']:false;
    }
    
    /**
     * The counter as it stands for this exact month — no shifting.
     *
     * get_quotationAP_running() deliberately reads the previous month, which is right
     * when a month's import starts from where the last one ended. An export has to read
     * the current month or a second export would re-issue numbers that were already used.
     */
    function get_quotationAP_running_exact($inpt_quotation_date)
    {
        $quotation_year = (int) date_format($inpt_quotation_date, "Y");
        $quotation_month = (int) date_format($inpt_quotation_date, "m");

        $this->db->select('running');
        $this->db->from('quotation_ap_running');
        $this->db->where('quotation_year', $quotation_year);
        $this->db->where('quotation_month', $quotation_month);
        $query = $this->db->get();
        $rs = $query->result_array();

        return ($query->num_rows() > 0) ? (int) $rs[0]['running'] : false;
    }

    /** Every quotation number already on file for a year, so a new one cannot repeat it. */
    function get_quotation_nos($quotation_year)
    {
        $this->db->select('quotation_no');
        $this->db->from('quotation_ap_data');
        $this->db->like('quotation_no', $quotation_year . '/', 'after');
        $query = $this->db->get();

        $numbers = array();
        foreach ($query->result_array() as $row) {
            $numbers[] = $row['quotation_no'];
        }

        return $numbers;
    }

    function update_quotationAP_running($inpt_quotation_date, $running)  
    {        
        $quotation_year = (int) date_format($inpt_quotation_date,"Y");
        $quotation_month = (int) date_format($inpt_quotation_date,"m");
        
        $data = array(
            'quotation_year'    => $quotation_year,
            'quotation_month'   => $quotation_month,
            'running'           => $running
        );

        $res = $this->db->replace('quotation_ap_running', $data);
                
        return $res;
        
    }
    
    function update_quotationAP_doc($doc_type, $doc_name)  
    {        
        
        $account_id = $this->auth->get_account_id();        
        $now = date("Y-m-d H:i:s");
        $data = array(
            'doc_type'      => $doc_type,
            'doc_name'      => $doc_name,
            'create_date'   => $now,
            'create_by_id'  => $account_id,
            'create_ip'     => $_SERVER['REMOTE_ADDR']
        );

        $this->db->set($data);
        $res = $this->db->insert('quotation_ap_doc');
                
        return $now;
        
    }
    
    function update_quotationAP($inpt_quotation_date)  
    {
        $this->db->select('*');
        $this->db->from('quotation_ap_data');
        //$this->db->where('UPPER(project)', $project);       
        $query = $this->db->get();
        
        if ($query->num_rows()>0) {
            
            $quotation_date = date_format($inpt_quotation_date,"Y-m-d");        
            $job_no = date_format($inpt_quotation_date,"ym")."02AP";
            
            $error_ref = array();
            $rs = $query->result_array();
            foreach ($rs as $key => $value) {
                
                $project = $value['project'];                
                $budget_all = $value['budget_all'];;                            
                $budget = round($budget_all*100/107,2); 
                $vat = $budget_all -$budget;
                
                $company_title = '';
                $company_address = '';
                $company_ref = $this->quotation->get_reference($project);
                if ($company_ref == false) {
                    $error_ref[] = $project;
                    $company_title = '';
                    $company_address = '';
                    $company_taxid = '';
                } else {
                    $company_title = $company_ref['company_title'];
                    $company_address = $company_ref['company_address'];
                    $company_taxid = $company_ref['company_taxid'];
                }   
                
                $data = array(
                        'budget'           => $budget,
                        'vat'              => $vat,
                        'company_title'    => $company_title,
                        'company_address'  => $company_address,
                        'company_taxid'    => $company_taxid,
                        'quotation_date'   => $quotation_date,
                        'job_no'           => $job_no
                );

                $this->db->where('id', $value['id']);
                $this->db->update('quotation_ap_data', $data);                                
            }
            
            return $error_ref;
        }
        return false;
    }
    
    function get_reference_name($synProject) 
    {
        $this->db->select('*');
        $this->db->from('quotation_ap_reference');
        $this->db->where('UPPER(REPLACE(project," ",""))=', strtoupper(str_replace(" ", "", $synProject)) );  
        $this->db->limit(1);
        $query = $this->db->get();

        if ($query->num_rows()>0) {
            $rs = $query->result_array();                
            return $rs[0];
        } else {
            return false;
        }
        
    }
    
    function get_reference($project) 
    {
        $this->db->select('*');
        $this->db->from('quotation_ap_reference_synonym');
        $this->db->where('UPPER(REPLACE(synonym," ",""))=', strtoupper(str_replace(" ", "", $project)) ); 
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows()>0) {
            
            $rs = $query->result_array();
            $synProject = $rs[0]['project'];
            return $this->get_reference_name($synProject);
            
        } else {            
            $project_name = array('CORPORATE', 'HR', 'PR' , 'ONLINE MKT');

            foreach ($project_name as $key => $value) {
                if (preg_match("/^{$value}/i", $project)) {
                    return $this->get_reference_name($value);
                }
            }               
            return false;
        }                        
    }
    
    function get_data() 
    {
        $this->db->select('*');
        $this->db->from('quotation_ap_data');        
        $query = $this->db->get();
        $rs = $query->result_array();        
        return ($query->num_rows()>0)?$rs:false;
    }
    
    


    /**
     * Update the contact name shown on the exported quotation (cell E10).
     */
    function update_contact_name($quotation_no, $contact_name)
    {
        $this->db->where('quotation_no', $quotation_no);
        $this->db->update('quotation_ap_data', array('contact_name' => $contact_name));
        return $this->db->affected_rows();
    }

    /**
     * Get one quotation row (by quotation_no).
     */
    function get_by_quotation_no($quotation_no)
    {
        $this->db->where('quotation_no', $quotation_no);
        $query = $this->db->get('quotation_ap_data');
        return ($query->num_rows() > 0) ? $query->row_array() : false;
    }
}
