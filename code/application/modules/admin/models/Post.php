<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class Post extends MY_Model 
{

	public $table_name = 'posts';
	public $primary_key = 'id';

	/**
	 * Este método faz uma consulta retornando as postagens e as suas
	 * category de acordo com o código da category.
	 *
	 * @return mixed
	 * @param $category_id int Code da category.
	 * @param $order  string Type de ordenação do resultado ASC ou DESC.
	 * @param $limit array Um array com os detalhes de limite, Ex: array('offset'=>'0', 'limit'=>'10')
	 * @author Eliel de Paula <elieldepaula@gmail.com>
	 **/
	public function get_by_category($category_id = 0, $order = 'asc', $limit = array())
	{
		$this->db->select('posts.*, posts_category.post_id, posts_category.category_id');
		$this->db->from($this->table_name);
		$this->db->join('posts_category', 'posts_category.post_id = posts.id');
		$this->db->where('posts_category.category_id', $category_id);
		$this->db->where('posts.status', '1');
		$this->db->order_by('created', $order);
		if((is_array($limit)) and (count($limit) != 0))
        {
            $this->db->limit($limit['limit'], $limit['offset']);
        }
		return $this->db->get();
	}

	/**
	 * Este método faz a pesquisa de uma palavra ou frase no título
	 * e no corpo da postagem.
	 *
	 * @return mixed
	 * @author Eliel de Paula <elieldepaula@gmail.com>
	 **/
	public function busca_posts($search = null)
	{
		if ($search) {
			$this->db->like('title', $search, 'both');
			$this->db->or_like('content', $search, 'both');
			$this->db->or_like('tags', $search, 'both');
		}

		$this->db->where('status','1');
		$this->db->order_by('created', 'desc');
		
		return $this->db->get($this->table_name);
	}

}