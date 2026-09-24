<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Production_cost_model extends MY_Model
{
    public $table_name = 'production';
    public $primary_key = 'production_id';
    
    public function _get_production_cost($id){
        $query = <<<TXT
SELECT
p.production_id,
j.job_id,
j.job_title,
j.job_rate,
j.job_discount,
j.job_status,
ifnull(pc.cost_rate,j.job_rate) AS n_cost_rate,
ifnull(pc.cost_discount,j.job_discount) AS n_cost_discount,
SUM(pt.work_hours) AS n_work_hours,
 (ifnull(pc.cost_rate,j.job_rate) * ( 1 - ((ifnull(pc.cost_discount,j.job_discount))/100))) * Sum(pt.work_hours) AS n_cost_amount,
p.job_no
FROM
members AS m
 JOIN job_title AS j ON m.job_id = j.job_id
 JOIN production_task AS pt ON pt.member_id = m.member_id and pt.is_active = 'Y' AND pt.is_delete = 'N'
 JOIN production AS p ON p.production_id = pt.production_id AND p.production_id = $id
 left JOIN production_cost AS pc ON pc.production_id = p.production_id  and  pc.job_id = j.job_id
 GROUP BY j.job_id               
                
TXT;
                
        return $this->db->query($query)->result_array();
    }
    public function update_production_page_cost($data)
    {
        $id = $data['production_id'];
        unset($data['production_id']);
        $this->db->where('production_id', $id);
        $this->db->update('production', $data);
        return $this->db->affected_rows();
    }

    public function insert_production_cost($data)
    {
        $this->db->insert('production_cost', $data);
        return $this->db->insert_id();
    }
    
    public function delete_production_cost($id)
    {
        $data['is_active'] = "N";
        $data['is_delete'] = "Y";
        $this->db->where('production_id', $id);
        $this->db->update('production_cost', $data);
        return $this->db->affected_rows();
    }
    
    public function update_quotation_by_production_id($data)
    {
        $id = $data['production_id'];
        unset($data['production_id']);
        $this->db->where('production_id', $id);
        $this->db->update('quotations', $data);
        return $this->db->affected_rows();
    }
    
    public function update_production_cost($data)
    {
        $id = $data['production_id'];
        $job_id= $data['job_id'];
        if($this->has_production_cost($id,$job_id)){
            $data['update_by_id'] = $data['create_by_id'];
            $data['update_ip'] = $_SERVER['REMOTE_ADDR'];
            $data['update_date'] = date('Y-m-d H:i:s');
            unset($data['create_by_id']);
            unset($data['create_ip']);
            unset($data['create_date']);
            unset($data['production_id']);
            unset($data['job_id']);
            $this->db->where('production_id', $id);
            $this->db->where('job_id', $job_id);
            $this->db->update('production_cost', $data);
            return $this->db->affected_rows();
        }else{
            $this->insert_production_cost($data);
        }
    }
    
    public function has_production_cost($id,$job_id) {        
        $this->db->select('*');
        $this->db->from('production_cost');
        $this->db->where('production_id', $id);
        $this->db->where('job_id', $job_id);
        $res = $this->db->get();
        return ($res->num_rows()>0)?true:false;               
    }
    
    public function has_job_no($job_no) {        
        $this->db->select('*');
        $this->db->from('production');
        $this->db->where('job_no', $job_no);
        $res = $this->db->get();
        return ($res->num_rows()>0)?true:false;               
    }
    
    public function _get_list_export_cost($id) {
        $query = <<<TXT
SELECT
jt.job_id,
jt.job_title,
IFNULL(pc.job_rate,jt.job_rate) job_rate,
IFNULL(pc.job_discount,jt.job_discount) job_discount,
IFNULL(pc.cost_rate,jt.job_rate) cost_rate,
IFNULL(pc.cost_discount,jt.job_discount) cost_discount,
pc.work_hours,
pc.cost_amount
FROM
job_title AS jt
left JOIN production_cost AS pc ON pc.job_id = jt.job_id and pc.production_id = $id and pc.is_active = 'Y' AND pc.is_delete = 'N'
ORDER BY jt.job_id asc
                
TXT;
        return $this->db->query("$query");
    }
}
