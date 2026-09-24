<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Projects_model extends MY_Model
{
    /**
     * Name the table.
     *
     * @var $table_name string
     **/
    public $table_name = 'projects';

    /**
     * Name of the field that is the primary key of the table.
     *
     * @var $primary_key string
     **/
    public $primary_key = 'project_id';

    public function _get_list() {
        $query = <<<TXT
SELECT
p.project_id,
p.project_name,
p.project_description,
p.customer_id,
p.project_status,
p.is_active,
p.is_delete,
p.create_date,
c.customer_name,
p.update_date
FROM
projects AS p
INNER JOIN customers AS c ON p.customer_id = c.customer_id
WHERE
p.is_delete = 'N'
                
TXT;
        return $this->db->query("$query")->result();
    }
    
    public function _get_customers() {
        $query = <<<TXT
SELECT *
FROM customers AS t
WHERE
t.is_active = 'Y' AND
t.is_delete = 'N'
                
TXT;
        return $this->db->query("$query")->result();
    }
    
    public function _project_by_id($id = NULL)
    {
            if($id == NULL)
                    return FALSE;
                    //throw new Exception('Account ID is empty.');
            $this->db->where('project_id', $id);
            $account = $this->db->get('projects');
            if($account->num_rows() > 0)
                return $account->row();
            else
                return FALSE;

    }
    
    public function _insert_project($data)
    {
            $this->db->insert('projects', $data);
            return $this->db->insert_id();
    }
    
    public function _update_project($data)
    {
            $id = $data['project_id']; // Recupera o ID.
            unset($data['project_id']); // Remove o ID do array de atualização.
            $this->db->where('project_id', $id);
            $this->db->update('projects', $data);
            return $this->db->affected_rows();
    }
    
    public function _remove_project($id)
    {
            $data = array(); // Recupera o ID.
            $data['is_delete'] = 'Y';
            $this->db->where('project_id', $id);
            $this->db->update('projects', $data);
            return $this->db->affected_rows();
    }
    

} // END class