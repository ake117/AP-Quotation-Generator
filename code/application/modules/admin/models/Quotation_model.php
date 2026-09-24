<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Quotation_model extends MY_Model
{
    public $table_name = 'quotations';
    public $primary_key = 'quotation_id';
    
    public function insert_quotation($data)
    {
        $this->db->insert($this->table_name, $data);
        return $this->db->insert_id();
    }
    
    public function update_quotation($data)
    {
        $id = $data['quotation_id'];
        unset($data['quotation_id']);
        $this->db->where('quotation_id', $id);
        $this->db->update($this->table_name, $data);
        return $this->db->affected_rows();
    }
    
    public function remove_quotation($id)
    {
        $data['is_active'] = "N";
        $data['is_delete'] = "Y";
        $this->db->where('quotation_id', $id);
        $this->db->update($this->table_name, $data);
        return true;
    }
    
    public function insert_quotations_detail($data)
    {
        $this->db->insert('quotations_detail', $data);
        return $this->db->insert_id();
    }
    
    public function delete_quotations_detail($id)
    {
        $data['is_active'] = "N";
        $data['is_delete'] = "Y";
        $this->db->where('quotation_detail_id', $id);
        $this->db->update('quotations_detail', $data);
        return $this->db->affected_rows();
    }
    
    public function remove_quotation_detail($id)
    {
        $data['is_active'] = "N";
        $data['is_delete'] = "Y";
        $this->db->where('quotation_id', $id);
        $this->db->update("quotations_detail", $data);
        return true;
    }
    
    public function update_quotations_detail($data)
    {
        $id = $data['quotation_detail_no'];
        $quotation_id = $data['quotation_id'];
        if($this->has_quotations_detail($id,$quotation_id)){
            unset($data['quotation_id']);
            unset($data['quotation_detail_no']);
            $this->db->where('quotation_detail_no', $id);
            $this->db->where('quotation_id', $quotation_id);  
            $this->db->update('quotations_detail', $data);
            return $this->db->affected_rows();
        }else{
            $id = $this->insert_quotations_detail($data);
            return $id;
        }
    }
    
    public function has_quotations_detail($id,$quotation_id) {        
        $this->db->select('*');
        $this->db->from('quotations_detail');
        $this->db->where('quotation_detail_no', $id);        
        $this->db->where('quotation_id', $quotation_id);        
        $res = $this->db->get();
        return ($res->num_rows()>0)?true:false;               
    }
    
    public function _get_quotations($id)
    {
        if($id == 0){
            return array();
        }
        $this->db->select('quotations.*');
        $this->db->from('quotations');
        $this->db->where('quotations.quotation_id', $id);
        $query = $this->db->get();
        $rs = $query->result_array();
        //echo '<pre>';print_r($rs);echo '</pre>';
        return $rs[0];
    }
    
    public function _get_quotations_detail($id)
    {
        $this->db->select('quotations_detail.*');
        $this->db->from('quotations_detail');
        $this->db->where('quotations_detail.quotation_id', $id);
        $this->db->where('quotations_detail.is_active', "Y");
        $this->db->where('quotations_detail.is_delete', "N");
        $query = $this->db->get();
        $rs = $query->result_array();
        for($x = 0; $x < count($rs); $x++) {
            $rs[$x]['quotation_detail_qty'] = ($rs[$x]['quotation_detail_qty'] == 0)?"":$rs[$x]['quotation_detail_qty'];
            $rs[$x]['quotation_detail_price'] = ($rs[$x]['quotation_detail_price'] == 0)?"":$rs[$x]['quotation_detail_price'];
            $rs[$x]['quotation_detail_amount'] = ($rs[$x]['quotation_detail_amount'] == 0)?"":$rs[$x]['quotation_detail_amount'];    
        }
        
        //echo '<pre>';print_r($rs);echo '</pre>';
        return $rs;
    }
    
    public function _get_all_quotation($where = array(),$from = null,$to = null,$search = null)
    {
        $this->db->select('*');
        $this->db->from($this->table_name);
        if(!empty($search)){
            $this->db->join('members', 'quotations.issue_member_id = members.member_id');
        }
        if ((is_array($where)) and (count($where)!=0)) {
            foreach ($where as $key => $value) {
                if(!empty($value)){
                    $this->db->where($key, $value);
                }
            }
        }
        if(!empty($from) && !empty($to)){
            if($from == $to){
                $this->db->where('production_start >=', "{$from} 00:00:00");
            }else{
                $this->db->where('production_start >=', "{$from} 00:00:00");
                $this->db->where('production_end <=', "{$to} 00:00:00");
            }
        }elseif(!empty($from) && empty($to)){
            $this->db->where('production_start >=', "{$from} 00:00:00");
        }
        $this->db->where('is_active', 'Y');
        $this->db->where('is_delete', 'N');
        if(!empty($search)){
            $this->db->like('member_name', $search);
            $this->db->or_like('job_no', $search);
            $this->db->or_like('production_name', $search);
        }
        $this->db->order_by($this->table_name.'.create_date', 'DESC');
        //print_r($this->db->get_compiled_select());
        return $this->db->get()->result();
    }
    
    public function _get_customers()
    {
        $this->db->select('customers.*');
        $this->db->from('customers');
        $this->db->where('customers.is_active', 'Y');
        $this->db->where('customers.is_delete', 'N');
        $query = $this->db->get();
        $rs = $query->result_array();
        $data[''] = 'กรุณาเลือก';
        foreach ($rs as $key => $row) {
            $data[$row['customer_id']] = $row['customer_name'];
        }
        return $data;
    }
    
    public function _get_list_export_quotation($id) {
        $query = <<<TXT
SELECT
q.quotation_date,
q.quotation_no,
q.job_no,
q.customer_id,
q.production_id,
q.project_id,
q.production_name,
q.quotation_discount,
q.quotation_amount,
q.quotation_vat,
q.quotation_total,
q.quotation_credit_term,
q.issue_member_id,
c.customer_name,
c.customer_address,
c.customer_taxid,
m.member_fullname
FROM
customers AS c
INNER JOIN quotations AS q ON q.customer_id = c.customer_id and q.quotation_id = $id
INNER JOIN members AS m ON q.issue_member_id = m.member_id
                
TXT;
        return $this->db->query("$query")->result_array();
    }


}
