<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Magazine_model extends MY_Model {

    public function __construct() {
        parent::__construct();
    }
    
    private function _get_pagers($page, $limit){
        $query = "select count(t1.id) count
            from b_iblock_element as t1 
             join b_iblock_element_property as t2 on t1.id=t2.iblock_element_id and iblock_property_id='211'  
             join b_file as t3 on t2.value=t3.id 
             join b_file t4 on t1.preview_picture=t4.id 
            WHERE t1.IBLOCK_ID='30' and t1.ACTIVE = 'Y' ";
        $this->get_pagers($query, $page, $limit);
    }

    public function get_magazine($page, $limit, $sort, $sort_type) {
        $this->_get_pagers($page, $limit);
        
        $query = $this->db->query("SELECT t1.ID publication_id, t1.active_from, t1.`name` title, t1.DETAIL_TEXT publication_detail
        ,t2.value as f_pdf_id
        ,concat(t3.subdir,'/',t3.file_name) file_url, concat(t4.subdir,'/',t4.file_name) cover
        from b_iblock_element as t1 
         join b_iblock_element_property as t2 on t1.id=t2.iblock_element_id and iblock_property_id='211'  
         join b_file as t3 on t2.value=t3.id 
         join b_file t4 on t1.preview_picture=t4.id 
        WHERE t1.IBLOCK_ID='30' and t1.ACTIVE = 'Y'
        ORDER BY t1.{$sort} {$sort_type}
        LIMIT {$this->offset}, {$limit}");
        return $query;
    }

    public function get_magazine_detail($magazine_id) {
        $query = $this->db->query("SELECT t1.ID publication_id, t1.active_from, t1.`name` title, t1.DETAIL_TEXT publication_detail
        ,t2.value as f_pdf_id
        ,concat(t3.subdir,'/',t3.file_name) file_url, concat(t4.subdir,'/',t4.file_name) cover
        from b_iblock_element as t1 
         join b_iblock_element_property as t2 on t1.id=t2.iblock_element_id and iblock_property_id='211'  
         join b_file as t3 on t2.value=t3.id 
         join b_file t4 on t1.preview_picture=t4.id 
        WHERE t1.IBLOCK_ID='30' and t1.ACTIVE = 'Y' and t1.id = {$magazine_id}");
        return $query;
    }
    
    

}