<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Members_model extends MY_Model
{
    /**
     * Name the table.
     *
     * @var $table_name string
     **/
    public $table_name = 'members';

    /**
     * Name of the field that is the primary key of the table.
     *
     * @var $primary_key string
     **/
    public $primary_key = 'member_id';

    public function _get_list() {
        $query = <<<TXT
SELECT
m.member_id,
m.member_name,
m.member_fullname,
m.team_id,
m.job_id,
m.member_status,
m.create_date,
m.update_date,
jt.job_title,
t.team_name,
m.is_active,
m.is_delete
FROM
members AS m
INNER JOIN teams AS t ON m.team_id = t.team_id
INNER JOIN job_title AS jt ON m.job_id = jt.job_id
WHERE 
m.is_delete = 'N'
                
TXT;
        return $this->db->query("$query")->result();
    }
    
    public function _get_teams() {
        $query = <<<TXT
SELECT *
FROM teams AS t
WHERE
t.is_active = 'Y' AND
t.is_delete = 'N'
                
TXT;
        return $this->db->query("$query")->result();
    }
    
    public function _get_jobs() {
        $query = <<<TXT
SELECT *
FROM job_title AS jt
WHERE
jt.is_active = 'Y' AND
jt.is_delete = 'N'
                
TXT;
        return $this->db->query("$query")->result();
    }
    
    public function _member_by_id($id = NULL)
    {
            if($id == NULL)
                    return FALSE;
                    //throw new Exception('Account ID is empty.');
            $this->db->where('member_id', $id);
            $account = $this->db->get('members');
            if($account->num_rows() > 0)
                return $account->row();
            else
                return FALSE;

    }
    
    public function _insert_member($data)
    {
            $this->db->insert('members', $data);
            return $this->db->insert_id();
    }
    
    public function _update_member($data)
    {
            $id = $data['member_id']; // Recupera o ID.
            unset($data['member_id']); // Remove o ID do array de atualização.
            $this->db->where('member_id', $id);
            $this->db->update('members', $data);
            return $this->db->affected_rows();
    }
    
    public function _remove_member($id)
    {
            $data = array(); // Recupera o ID.
            $data['is_delete'] = 'Y';
            $this->db->where('member_id', $id);
            $this->db->update('members', $data);
            return $this->db->affected_rows();
    }
    

} // END class