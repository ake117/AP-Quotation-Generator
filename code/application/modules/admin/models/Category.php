<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class Category extends MY_Model 
{

	public $table_name = 'category';
	public $primary_key = 'id';

	public function get_by_post($post_id = 0, $order = 'asc', $limit = array())
	{
		$this->db->select('category.*, posts_category.post_id, posts_category.category_id');
		$this->db->from($this->table_name);
		$this->db->join('posts_category', 'posts_category.category_id = category.id');
		$this->db->where('posts_category.post_id', $post_id);
		if((is_array($limit)) and (count($limit) != 0))
        {
            $this->db->limit($limit['limit'], $limit['offset']);
        }
		return $this->db->get();
	}

	/*
	| Retorna o título da category passando o ID
	| usado na lista de category para nomear as sub-category.
	*/
	public function get_title_by_id($id)
	{
		if($id){
			$query = $this->get_by_id($id, null, null, 'title')->row();
			return $query->title;
		} else
			return false;
	}

	/*
	| Exclui as category pelo ID da category-pai.
	*/
	public function delete_son($id)
	{
		$this->db->where('category_id', $id);
        $this->db->delete($this->table_name);
        return $this->db->affected_rows();
	}
}