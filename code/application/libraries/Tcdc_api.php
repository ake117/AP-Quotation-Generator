<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//session_start();

class Tcdc_api {

    private $ci;

    private $publication_ids = array();

    public $access_token = false;

    public function __construct() {
        $this->ci =& get_instance();
        $this->ci->load->config('tcdc', true);
        $this->ci->load->driver('cache');

        $cache_lifetime = $this->ci->smarty->cache_lifetime;

        $access_token = $this->ci->cache->file->get('access_token');
        if($access_token === false){
            $access_token = $this->get_access_code();
            if($access_token!==false){
                $this->ci->cache->file->save('access_token', $access_token, $cache_lifetime);
            }else{
                $this->ci->smarty->caching = 3600*10; // use cache case: api fail
            }
        }

        $this->access_token = $access_token;

    }

    private function get_access_code() {

        if(!$this->access_token === false){
            return $this->access_token;
        }

        $url = $this->ci->config->item('url', 'tcdc');
        $username = $this->ci->config->item('username', 'tcdc');
        $password = $this->ci->config->item('password', 'tcdc');

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, rawurldecode(http_build_query(array(
          'email' => $username,
          'password' => $password,
        ))));

        $json = json_decode(curl_exec($curl));

        if($json === null){
            return false;
        }else{
            $this->access_token = $json->access_token;
            return $this->access_token;
        }

    }

    private function _shuffle_assoc($list) {
        if (!is_array($list)){ return $list; }

        $keys = array_keys($list);
        shuffle($keys);
        $random = array();
        foreach ($keys as $key){
            $random[$key] = $list[$key];
        }

        return $random;
    }

    private function _limit_category(&$a, $limit=6) {
        $j = 0;
        $n = array();
        if(!empty($a)){
            foreach ($a as $key => $value) {
                $n[$key] = $value;
                $j++;
                if($limit===$j){
                    break;
                }
            }
        }
        $a = $n;
    }


    public function _get_article($search='', $limit=10, $sort='date', $sort_type='desc', $page=1, $url_nextpage='') {

        $access_token = $this->get_access_code();

        if(empty($url_nextpage)){
            $param = "?sort={$sort}&sort_type={$sort_type}&limit={$limit}&page={$page}";
            if(!empty($search)){
                $param .= "&{$search}";
            }
            $url = $this->ci->config->item('get-article', 'tcdc'). $param;

        }else{
            $url = $url_nextpage;
        }

        $headers = array(
            'Content-Type: application/json',
            sprintf('Authorization: Bearer %s', $access_token)
        );

        $curl = curl_init($url);

        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $result = json_decode(curl_exec($curl));

        $this->ci->load->library('session');
        if($this->ci->session->has_userdata('article_intro')){
            $article_intro = $this->ci->session->article_intro;
        }else{
            $article_intro = array();
        }

        if(isset($result->data)){
            foreach ($result->data as &$article) {
                $article_intro[$article->article_id] = $article->article_intro;
            }
        }
        $this->ci->session->set_userdata('article_intro', $article_intro);

        return $result;

    }

    public function get_article($cat_id = '', $limit=12, $sort='date', $sort_type='desc', $page=1, $ignore_id=null) {

        $a_article = array();
        foreach ($GLOBALS['my_main_menu'] as $key => $a) {
            if($a['type']==='cat' && $cat_id === $key ){
                $a_article[$key] = $a;
                break;
            }
        }

        foreach ($a_article as &$val) {
            $search = "{$val['search']}={$val['search_value']}";
            $rs = $this->_get_article($search, $limit, $sort, $sort_type, $page);
//        return $rs;
//        echo '<pre>';
//        echo $rs->next_page_url;
//        var_dump($rs);
//        die();
//        echo '</pre>';

            $a_article['next_page_url'] = empty($rs->next_page_url)?'' : $rs->next_page_url;
            $a_article['current_page'] = $rs->current_page;

            $i = 1;
            $articles = array();

            if(isset($rs->data)){
                foreach ($rs->data as $article) {
                    if((int)$article->article_id === (int)$ignore_id) {$rs->total--; continue;}

                    $read_min = read_article_bylength($article->article_length);

                    $articles[$i]["article_id"] = $article->article_id;
                    $articles[$i]["article_slug"] = $article->article_slug;
                    $articles[$i]["article_name"] = $article->article_name;
                    $articles[$i]["article_intro"] = $article->article_intro;
                    $articles[$i]["article_read"] = $read_min;
                    $articles[$i]["article_view"] = (is_numeric($article->article_view)?shorten_k($article->article_view):0);
                    $articles[$i]['image_url'] = $article->images[0]->image_url;
                    $i++;
                }
                $val['result'] = $articles;
            }

            $a_article["total"] = 0;
            if(isset($rs->total)){
                $a_article["total"] = $rs->total;
            }

        }

        return $a_article;
    }

    public function get_article_search($q, $url_nextpage='', $limit=12, $sort='date', $sort_type='desc', $page=1) {

        $search = '';
        if(!empty($q)){
            $search = rawurldecode("search=$q");
        }

        $rs = $this->_get_article($search, $limit, $sort, $sort_type, $page, $url_nextpage);


        $i = 1;
        $articles = $a_articles = array();
        if(isset($rs->data)){
            foreach ($rs->data as $article) {
                $new_cat = $this->is_cat_avai($article->tag);

                if($new_cat === false){
                    $new_cat = '';
                }else{
                    $new_cat = '/' . $new_cat;
                }

                $read_min = read_article_bylength($article->article_length);

                $articles[$i]["article_id"] = $article->article_id;
                $articles[$i]["article_slug"] = $article->article_slug;
                $articles[$i]["article_name"] = $article->article_name;
                $articles[$i]["article_intro"] = $article->article_intro;
                $articles[$i]["category"] = $new_cat;
                $articles[$i]["article_read"] = $read_min;
                $articles[$i]["article_view"] = (is_numeric($article->article_view)?shorten_k($article->article_view):0);
                $articles[$i]['image_url'] = $article->images[0]->image_url;
                $i++;
            }
            $a_articles['next_page_url'] = empty($rs->next_page_url)?'' : $rs->next_page_url;
            $a_articles['current_page'] = $rs->current_page;
        }

        $a_articles['result'] = $articles;
        $a_articles["total"] = 0;
        if(isset($rs->total)){
            $a_articles["total"] = $rs->total;
        }

        return $a_articles;
    }

    public function get_article_by_main($limit=10, $sort='date', $sort_type='desc', $page=1) {
        $limit_cat_display = 6;
        $carousel_limit = 4;
        $slide_limit = 3;

        $a_article = array();
        foreach ($GLOBALS['my_main_menu'] as $key => $a) {
            if($a['type']==='cat'){
                $a_article[$key] = $a;
            }
        }

        $adv_search = $GLOBALS['my_main_menu']['advertorial']['search'];
        $adv_search_value = $GLOBALS['my_main_menu']['advertorial']['search_value'];

        $rs_adv = $this->_get_article("{$adv_search}={$adv_search_value}", 10);


        $a_article = $this->_shuffle_assoc($a_article);

        if($rs_adv->total > 0 ){
            $adv['advertorial'] = $GLOBALS['my_main_menu']['advertorial'];
            $a_article = array_merge($adv, $a_article);
        }

        $this->_limit_category($a_article, $limit_cat_display);

        $a_article2 = array();
        foreach ($a_article as $key => &$val) {
            $search = "{$val['search']}={$val['search_value']}";
            $display_type = rand(1, 2);

            $limit = $carousel_limit; /*carousel type*/
            if($display_type===2){
                $limit = $slide_limit; /*slide type*/
            }

            $rs = $this->_get_article($search, $limit, $sort, $sort_type, $page);

//        echo '<pre>';
//        var_dump($rs);
//        echo '</pre>';

            $i = 1;
            $articles = array();
            if(isset($rs->data)){
                foreach ($rs->data as $article) {
                    $read_min = read_article_bylength($article->article_length);

                    $articles[$i]["article_read"] = $read_min;
                    $articles[$i]["article_id"] = $article->article_id;
                    $articles[$i]["article_slug"] = $article->article_slug;
                    $articles[$i]["article_name"] = $article->article_name;
                    $articles[$i]["article_intro"] = $article->article_intro;
                    $articles[$i]['image_url'] = $article->images[0]->image_url;
                    $i++;
                }
                $val['display_type'] = $display_type;

                if($key==='advertorial'){
                    $val['display_type'] = 1;
                }

                $val['result'] = $articles;

                $a_article2[$key] = $val;
            }
        }

        return $a_article2;
    }

    public function get_article_data($article_id) {
        $access_token = $this->get_access_code();

        $param = "?article_id={$article_id}";
        $url = $this->ci->config->item('get-article-data', 'tcdc') . $param;

        $headers = array(
            'Content-Type: application/json',
            sprintf('Authorization: Bearer %s', $access_token)
        );

        $curl = curl_init($url);

        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $result = json_decode(curl_exec($curl));

        return $result;
    }

    public function get_category() {
        $access_token = $this->get_access_code();

        $url = $this->ci->config->item('get-category', 'tcdc');

        $headers = array(
            'Content-Type: application/json',
            sprintf('Authorization: Bearer %s', $access_token)
        );

        $curl = curl_init($url);

        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $result = json_decode(curl_exec($curl));

        return $result;
    }

    public function get_tag() {
        $access_token = $this->get_access_code();

        $url = $this->ci->config->item('get-tag', 'tcdc');

        $headers = array(
            'Content-Type: application/json',
            sprintf('Authorization: Bearer %s', $access_token)
        );

        $curl = curl_init($url);

        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $result = json_decode(curl_exec($curl));

        return $result;
    }

    public function get_brand() {
        $access_token = $this->get_access_code();

        $url = $this->ci->config->item('get-brand', 'tcdc');

        $headers = array(
            'Content-Type: application/json',
            sprintf('Authorization: Bearer %s', $access_token)
        );

        $curl = curl_init($url);

        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $result = json_decode(curl_exec($curl));

        return $result;
    }

    public function get_publication_data($publication_id) {
//        $access_token = $this->get_access_code();
        $this->ci->load->model('magazine_model', 'magazine');
        $publication = $this->ci->magazine->get_magazine_detail($publication_id)->row();

//        echo '<pre>';
//        var_dump($publication);
//        echo '</pre>';

        $image_url = '';
        $file_url = '';
        $a_publication_data = array();
        if(!empty($publication)){

            $image_url = base_url() . "assets/{$publication->cover}";

            if(empty($publication->cover)
                    //|| !url_exists($image_url)
                    ){
                $image_url = base_url().'assets/images/no_pic_book.jpg';
            }

            $file_url = base_url() . "assets/{$publication->file_url}";

            $a_publication_data["publication_id"] = $publication->publication_id;
            $a_publication_data["publication_slug"] = url_title($publication->title);
            $a_publication_data["publication_subject"] = $publication->title;
            $a_publication_data["publication_intro"] = $publication->title;
            $a_publication_data["publication_detail"] = $publication->publication_detail;
            $a_publication_data['image_url'] = $image_url;
            $a_publication_data['file_url'] = $file_url;
            $a_publication_data['og_url'] = base_url().'magazine/'.$publication->publication_id.'/'.url_title($publication->title);
            $a_publication_data['og_image'] = 'http://www.tcdc.or.th/upload/'.$publication->cover;
            $a_publication_data['og_description'] = substr(strip_tags($publication->publication_detail), 0, 1000);

        }

        return $a_publication_data;
    }

    public function get_publication($limit=10, $sort='active_from', $sort_type='desc', $page=1) {

        $this->ci->load->model('magazine_model', 'magazine');
        $this->ci->load->helper('date');
        $result = $this->ci->magazine->get_magazine($page, $limit, $sort, $sort_type)->result();

        $i = 1;
        $publications = array();
        $this->publication_ids = array();
        foreach ($result as $publication)
        {
            $this->publication_ids[] = $publication->publication_id;
            $image_url = '';
            $file_url = '';

            $image_url = base_url() . "assets/{$publication->cover}";

            if(empty($publication->cover)
                    //|| !url_exists($image_url)
                    ){
                $image_url = base_url().'assets/images/no_pic_book.jpg';
            }

            $file_url = base_url() . "assets/{$publication->file_url}";

            $publications[$i]["publication_id"] = $publication->publication_id;
            $publications[$i]["publication_slug"] = url_title($publication->title);
            $publications[$i]["publication_subject"] = $publication->title;
            $publications[$i]["publication_intro"] = $publication->title;
            $publications[$i]["active_from"] = mmyyyy_to_thai($publication->active_from);

            if($this->ci->uri->total_segments()===0){
                $publications[$i]["publication_detail"] = $publication->publication_detail;
            }
            $publications[$i]['image_url'] = $image_url;
            $publications[$i]['image_url_share'] = 'http://www.tcdc.or.th/upload/'.$publication->cover;
            $publications[$i]['file_url'] = $file_url;
            $i++;

        }

        $publications['next_page_url'] = empty($this->ci->magazine->next_page)?'' : $this->ci->magazine->next_page;
        $publications['current_page'] = $this->ci->magazine->current_page;

//        echo '<pre>';
//        var_dump($publications);
//        echo '</pre>';die();

        return $publications;
    }

    public function get_publication_data_by_main() {
        $a_publication = array();
        $i = 0;
        foreach ($this->publication_ids as $publication_id) {
            $rs = $this->get_publication_data($publication_id);
            if(!empty($rs)){
                $a_publication[] = $rs;
            }
        }
        return $a_publication;
    }

    public function get_youtube() {
        $api_list_url = $this->ci->config->item('y_get_list', 'tcdc');
        $api_video_url = $this->ci->config->item('y_get_video', 'tcdc');
        $channel_id = $this->ci->config->item('y_channel_id', 'tcdc');
        $api_key = $this->ci->config->item('y_api_key', 'tcdc');
        $max_results = 6;
        $display_limit = 3;

        $youtube_list = json_decode(file_get_contents("{$api_list_url}?part=snippet,id&order=date&maxResults={$max_results}&key={$api_key}&channelId={$channel_id}"));

        $youtube = array();
        $i = 0; $j=0;
        foreach($youtube_list->items as $item)
        {
            if(isset($item->id->videoId)){
                $yt = json_decode(file_get_contents("{$api_video_url}?part=snippet&key={$api_key}&id=".$item->id->videoId));

                if(isset($yt->items[0])){
                    if(isset($yt->items[0]->snippet->thumbnails->maxres)){
                        $cover = $yt->items[0]->snippet->thumbnails->maxres->url;
                    }elseif (isset($yt->items[0]->snippet->thumbnails->standard)) {
                        $cover = $yt->items[0]->snippet->thumbnails->standard->url;
                    }elseif (isset($yt->items[0]->snippet->thumbnails->high)) {
                        $cover = $yt->items[0]->snippet->thumbnails->high->url;
                    }
                }

                $description = '';
                $short_description = '';
                if( !empty($yt->items[0]) ){
                    $a_description = explode("\n", $yt->items[0]->snippet->description );


                    $description = (isset($a_description[0])?$a_description[0] : '');

                    $length = 140;
                    if($i===0){
                        $length = 350;
                        for($j=1; $j<count($a_description); $j++){
                            if( strlen(utf8_decode($description))< $length ){
                                $description .= "\n" . $a_description[$j];
                            }else{
                                break;
                            }
                        }
                    }

                    $s_des = explode(' ', $description);

                    foreach ($s_des as $des) {
                        $short_description .= $des . ' ';
                        if( strlen(utf8_decode($short_description))>$length ){
                            break;
                        }
                    }
                }

                $youtube[$item->snippet->publishedAt] = array(
                    'id' => $item->id->videoId,
                    'title' => $item->snippet->title,
                    'description' => $description,
                    'short_description' => trim($short_description),
                    'cover' => $cover,
                );

                $i++;

                if($i === $display_limit){
                    break;
                }
            }
        }

        krsort($youtube);

        return $youtube;

    }

    private function is_cat_avai($tags){
        $menu = $GLOBALS['my_main_menu'];
        $category = false;
        foreach ($menu as $k => $m) {
            foreach ($tags as $t) {
                //echo "article_tag_id {$t->article_tag_name}, {$t->article_tag_id} : search_value {$m['search_value']}<br/>";
                if((int)$t->article_tag_id === (int)$m['search_value']){
                    $category = $k;
                    return $category;
                }
            }
        }
        return $category;
    }

    public function get_article_rel($cat_id, $ignore_id, $tags, $page=1, $sort='date', $sort_type='desc') {
        $cat_limit = 7;
        $a_article = array();
        $limit = $cat_limit;
//        if(!empty($tags)){
//            $limit = ceil($cat_limit/count($tags));
//        }

        $tags = $this->_shuffle_assoc($tags);


        $i = 1;
        foreach ($tags as $a_tag) {
            $search = "tag={$a_tag->article_tag_id}";
            $rs = $this->_get_article($search, $limit, $sort, $sort_type, $page);
//        echo '<pre>';
//        var_dump($a_article);
//        echo '</pre>';

            if(isset($rs->data)){
                foreach ($rs->data as $article) {
                    if((int)$article->article_id === (int)$ignore_id) {$rs->total--; continue;}

                    $new_cat = $this->is_cat_avai($article->tag);
                    if($new_cat === false){
                        $new_cat = $cat_id;
                    }
                    $read_min = read_article_bylength($article->article_length);

                    $a_article[$article->article_id]["article_id"] = $article->article_id;
                    $a_article[$article->article_id]["article_slug"] = $article->article_slug;
                    $a_article[$article->article_id]["article_name"] = $article->article_name;
                    $a_article[$article->article_id]["article_intro"] = $article->article_intro;
                    $a_article[$article->article_id]["article_read"] = $read_min;
                    $a_article[$article->article_id]["tag"] = $article->tag;
                    $a_article[$article->article_id]["category"] = $new_cat;
                    $a_article[$article->article_id]["article_view"] = (is_numeric($article->article_view)?shorten_k($article->article_view):0);
                    $a_article[$article->article_id]['image_url'] = $article->images[0]->image_url;
                    $i++;
                }
            }
            $a_article["total"] = 0;
            if(isset($rs->total)){
                $a_article["total"] = $rs->total;
            }

        }

        $this->_limit_category($a_article, $cat_limit);


        return $a_article;
    }
    
    public function get_article_update($article_id) {
        $access_token = $this->get_access_code();
        $param = "?article_id={$article_id}&article_modified_user=admin&article_modified_user_id=1&article_view=200";
        $url = $this->ci->config->item('update-article', 'tcdc') . $param;

        $headers = array(
            'Content-Type: application/json',
            sprintf('Authorization: Bearer %s', $access_token)
        );

        $curl = curl_init($url);

        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
//        curl_setopt($curl, CURLOPT_POSTFIELDS, rawurldecode(http_build_query(array(
//          'article_id' => $article_id,
//          'article_modified_user' => 'admin',
//          'article_modified_user_id' => 1,
//          'article_view' => 10,
//        ))));

        $result = json_decode(curl_exec($curl));

        return $result;
    }

}
