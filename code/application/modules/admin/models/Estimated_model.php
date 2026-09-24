<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Estimated_model extends MY_Model
{
    public $table_name = 'production';
    public $primary_key = 'production_id';
    
    public function insert_estimated($data)
    {
        $this->db->insert($this->table_name, $data);
        return $this->db->insert_id();
    }
    
    public function update_estimated($data)
    {
        $id = $data['production_id'];
        unset($data['production_id']);
        unset($data['total_cost_amount']);
        $this->db->where('production_id', $id);
        $this->db->update($this->table_name, $data);
        return $this->db->affected_rows();
    }
    
    public function remove_production($id)
    {
        $data['is_active'] = "N";
        $data['is_delete'] = "Y";
        $this->db->where('production_id', $id);
        $this->db->update('production', $data);
        return true;
    }
    
    public function duplicate_production($id)
    {
        return true;
    }
    
    public function _get_estimated($id)
    {
        if($id == 0){
            return array();
        }
        $this->db->select('production.*');
        $this->db->from('production');
        $this->db->where('production.production_id', $id);
        $query = $this->db->get();
        $rs = $query->result_array();
        //echo '<pre>';print_r($rs);echo '</pre>';
        return $rs[0];
    }
    
    public function _get_quotations_by_id($id)
    {
        if($id == 0){
            return array();
        }
        $this->db->select('quotations.*');
        $this->db->from('quotations');
        $this->db->where('quotations.production_id', $id);
        $this->db->where('quotations.is_active', 'Y');
        $this->db->where('quotations.is_delete', 'N');
        $this->db->order_by('create_date', 'DESC');
        $query = $this->db->get();
        $rs = $query->result_array();
        //echo '<pre>';print_r($rs);echo '</pre>';
        return $rs[0];
    }
    
    public function _get_production_cost_by_id($id)
    {
        if($id == 0){
            return array();
        }
        $this->db->select('production_cost.*');
        $this->db->from('production_cost');
        $this->db->where('production_cost.production_id', $id);
        $this->db->where('production_cost.is_active', 'Y');
        $this->db->where('production_cost.is_delete', 'N');
        $query = $this->db->get();
        $rs = $query->result_array();
        //echo '<pre>';print_r($rs);echo '</pre>';
        return $rs;
    }
    
    public function _get_quotations_detail_by_id($id)
    {
        if($id == 0){
            return array();
        }
        $this->db->select('quotations_detail.*');
        $this->db->from('quotations_detail');
        $this->db->where('quotations_detail.quotation_id', $id);
        $this->db->where('quotations_detail.is_active', 'Y');
        $this->db->where('quotations_detail.is_delete', 'N');
        $this->db->order_by('quotation_detail_no', 'ASC');
        $query = $this->db->get();
        $rs = $query->result_array();
        //echo '<pre>';print_r($rs);echo '</pre>';
        return $rs;
    }
    
    public function insert_production_task($data)
    {
        $this->db->insert('production_task', $data);
        return $this->db->insert_id();
    }
    
    public function delete_production_task($id)
    {
        $data['is_active'] = "N";
        $data['is_delete'] = "Y";
        $this->db->where('production_id', $id);
        $this->db->update('production_task', $data);
        return $this->db->affected_rows();
    }
    
    public function update_production_task($data)
    {
        $id = $data['task_id'];
        unset($data['task_id']);
        if($this->has_production_task($id)){
            $this->db->where('task_id', $id);
            $this->db->update('production_task', $data);
            return $this->db->affected_rows();
        }else{
            $id = $this->insert_production_task($data);
            return $id;
        }
    }
    
    public function has_production_task($id) {        
        $this->db->select('*');
        $this->db->from('production_task');
        $this->db->where('task_id', $id);        
        $res = $this->db->get();
        return ($res->num_rows()>0)?true:false;               
    }
    
    public function _get_all_estimated($where = array(),$from = null,$to = null,$search = null)
    {
        $this->db->select('*');
        $this->db->from($this->table_name);
        if(!empty($search)){
            $this->db->join('members', 'production.pm_member_id = members.member_id');
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
        $this->db->order_by('production.create_date', 'DESC');
        //print_r($this->db->get_compiled_select());exit;
        return $this->db->get()->result();
    }
    
    public function _get_member()
    {
        $this->db->select('members.*');
        $this->db->from('members');
        $this->db->where('members.is_active', 'Y');
        $this->db->where('members.is_delete', 'N');
        $this->db->order_by("team_id", "asc");
        $this->db->order_by("member_name", "asc");
        $query = $this->db->get();
        $rs = $query->result_array();
        $data[''] = 'กรุณาเลือก';
        foreach ($rs as $key => $row) {
            $teams = $this->_get_teams($row['team_id']);
            $data1 = $row['member_name']." - ".$teams['team_name'];
            $data[$row['member_id']] = $data1;
        }
        return $data;
    }
    
    public function _get_price_member($id)
    {
        $this->db->select('members.*');
        $this->db->select('job_title.job_rate');
        $this->db->select('job_title.job_discount');
        $this->db->from('members');
        $this->db->join('job_title', 'job_title.job_id = members.job_id');
        $this->db->where('members.member_id', $id);
        $query = $this->db->get();
        $rs = $query->result_array();
        foreach ($rs as $key => $row) {
            $data = ($row['job_discount'] != 0)?$row['job_rate']*((100 - $row['job_discount'])/100):$row['job_rate'];
        }
        return $data;
    }
    
    public function _get_production_select($check = "")
    {
        if(!empty($check)){
            $rs = $this->_get_quotations_in_have();
        }
        $this->db->reset_query();
        $this->db->select('production.*');
        $this->db->from('production');
        if(!empty($check)){
            if(is_array($rs)){
                $ids = array();
                foreach ($rs as $key => $row) {
                    $ids[] = $row['production_id'];
                }
                $txt_ids = implode("', '", $ids);
                $this->db->where("production.production_id NOT IN ('$txt_ids')");    
            }
        }
        $production_status = array('approved', 'close');
        $this->db->where_in('production.production_status', $production_status);
        $this->db->where('production.is_active', 'Y');
        $this->db->where('production.is_delete', 'N');
        $query = $this->db->get();
        $rs = $query->result_array();
        $data[''] = 'เลือกตาม Production Cost';
        foreach ($rs as $key => $row) {
            $data[$row['production_id']] = $this->_get_project_name($row['project_id'])." : ".$row['production_name']." (".$row['production_status'].")";
        }
        return $data;
    }
    
    public function _get_projects()
    {
        $this->db->select('projects.*');
        $this->db->from('projects');
        $this->db->where('projects.is_active', 'Y');
        $this->db->where('projects.is_delete', 'N');
        $this->db->order_by("project_name", "asc");
        $query = $this->db->get();
        $rs = $query->result_array();
        $data[''] = 'กรุณาเลือก';
        foreach ($rs as $key => $row) {
            $data[$row['project_id']] = $row['project_name'];
        }
        return $data;
    }
    
    public function _get_project_name($id)
    {
        $this->db->select('projects.*');
        $this->db->from('projects');
        $this->db->where('projects.project_id', $id);
        $query = $this->db->get();
        $rs = $query->result_array();
        $data = "";
        foreach ($rs as $key => $row) {
            $data = $row['project_name'];
        }
        return $data;
    }
    
    public function _get_pm_name($id)
    {
        $this->db->select('members.*');
        $this->db->from('members');
        $this->db->where('members.member_id', $id);
        $query = $this->db->get();
        $rs = $query->result_array();
        $data = "";
        foreach ($rs as $key => $row) {
            $data = str_replace(" - PM", "", $row['member_name']);
        }
        return $data;
    }
    
    public function _get_pm_short_name($id)
    {
        $this->db->select('members.*');
        $this->db->from('members');
        $this->db->where('members.member_id', $id);
        $query = $this->db->get();
        $rs = $query->result_array();
        $data = "";
        foreach ($rs as $key => $row) {
            $data = (!empty($row['member_fullname']))?$row['member_fullname']:$row['member_name'];
        }
        return $data;
    }
    
    public function _get_production($id)
    {
        if($id == 0){
            return array();
        }
        $this->db->select('production.*');
        $this->db->from('production');
        $this->db->where('production.production_id', $id);
        $query = $this->db->get();
        $rs = $query->result_array();
        //echo '<pre>';print_r($rs);echo '</pre>';
        $data = $rs[0];
        $data['projects'] = $this->_get_project($rs[0]['project_id']);
        return $data;
    }
    
    public function _get_project($id)
    {
        if($id == 0){
            return array();
        }
        $this->db->select('projects.*');
        $this->db->from('projects');
        $this->db->where('projects.project_id', $id);
        $query = $this->db->get();
        $rs = $query->result_array();
        //echo '<pre>';print_r($rs);echo '</pre>';
        return $rs[0];
    }
    
    public function _get_teams($id)
    {
        if($id == 0){
            return array();
        }
        $this->db->select('teams.*');
        $this->db->from('teams');
        $this->db->where('teams.team_id', $id);
        $query = $this->db->get();
        $rs = $query->result_array();
        //echo '<pre>';print_r($rs);echo '</pre>';
        return $rs[0];
    }
    
    public function _get_quotations_in_have()
    {
        $this->db->reset_query();
        $this->db->select('quotations.*');
        $this->db->from('quotations');
        //$this->db->where('quotations.production_id != ""');
        $this->db->where('quotations.is_active', "Y");
        $this->db->where('quotations.is_delete', "N");
        $this->db->order_by('production_id', 'asc');
        $query = $this->db->get();
        $rs = $query->result_array();
        //print_r($this->db->get_compiled_select());
        //echo '<pre>';print_r($rs);echo '</pre>';
        return $rs;
    }
    
    public function _get_production_task($id)
    {
        $this->db->select('production_task.*');
        $this->db->from('production_task');
        $this->db->where('production_task.production_id', $id);
        $this->db->where('production_task.is_active', "Y");
        $this->db->where('production_task.is_delete', "N");
        $this->db->order_by('task_no', 'ASC');
        $this->db->order_by('create_date', 'DESC');
        $query = $this->db->get();
        $rs = $query->result_array();
        //echo '<pre>';print_r($rs);echo '</pre>';
        return $rs;
    }
    
    public function _get_list_export($id) {
        $query = <<<TXT
SELECT
p.production_id,
pj.project_name,
p.production_name,
p.production_start,
pt.task_name,
pt.task_description,
pt.work_hours,
m.member_name
FROM
production AS p
INNER JOIN production_task AS pt ON pt.production_id = p.production_id
INNER JOIN projects AS pj ON p.project_id = pj.project_id
INNER JOIN members AS m ON pt.member_id = m.member_id
WHERE
pt.production_id = $id AND
pt.is_active = 'Y' AND
pt.is_delete = 'N'
ORDER BY
pt.task_no ASC
                
TXT;
        return $this->db->query("$query");
    }


}
