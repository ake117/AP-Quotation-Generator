<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Teams_model extends MY_Model
{
    /**
     * Name the table.
     *
     * @var $table_name string
     **/
    public $table_name = 'teams';

    /**
     * Name of the field that is the primary key of the table.
     *
     * @var $primary_key string
     **/
    public $primary_key = 'team_id';

    public function _get_list() {
        $query = <<<TXT
SELECT *
FROM teams AS t
WHERE 
t.is_delete = 'N'
                
TXT;
        return $this->db->query("$query")->result();
    }
    
    public function _team_by_id($id = NULL)
    {
            if($id == NULL)
                    return FALSE;
                    //throw new Exception('Account ID is empty.');
            $this->db->where('team_id', $id);
            $account = $this->db->get($this->table_name);
            if($account->num_rows() > 0)
                return $account->row();
            else
                return FALSE;

    }
    
    public function _insert_team($data)
    {
            $this->db->insert($this->table_name, $data);
            return $this->db->insert_id();
    }
    
    public function _update_team($data)
    {
            $id = $data['team_id']; // Recupera o ID.
            unset($data['team_id']); // Remove o ID do array de atualização.
            $this->db->where('team_id', $id);
            $this->db->update($this->table_name, $data);
            return $this->db->affected_rows();
    }
    
    public function _remove_team($id)
    {
            $data = array(); // Recupera o ID.
            $data['is_delete'] = 'Y';
            $this->db->where('team_id', $id);
            $this->db->update($this->table_name, $data);
            return $this->db->affected_rows();
    }
    

} // END class