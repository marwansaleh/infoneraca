<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Description of Weather
 *
 * @author marwansaleh
 */
class Article extends REST_Api {
    private $_share_maps = array(
        'id'            => 'id',
        'article_id'    => 'article_id',
        'article_title' => 'title',
        'user_id'       => 'user_id',
        'user'          => 'full_name',
        'post_id'       => 'post_id',
        'post_time'     => 'time',
        'post_datetime' => 'datetime'
    );
    function __construct($config='rest') {
        parent::__construct($config);
    }
    
    function index_get($id=NULL){
        //load models
        $this->load->model(array('article/article_m','article/category_m','article/article_image_m','users/user_m'));
        $this->load->helper('general');
        $remap_fields = array(
            'id'                => 'id',
            'category_id'       => 'category_id',
            'category_name'     => 'category',
            'title'             => 'title',
            'url_title'         => 'url_title',
            'link_href'         => 'link_href',
            'date'              => 'article_date',
            'day'               => 'day',
            'month'             => 'month',
            'year'              => 'year',
            'synopsis'          => 'synopsis',
            'content'           => 'content',
            'image_url'         => 'image_url',
            'image_type'        => 'image_type',
            'image_urls'        => 'image_urls',
            'tags'              => 'tags',
            'types'             => 'types',
            'allow_comment'     => 'allow_comment',
            'published'         => 'published',
            'view_count'        => 'view',
            'created'           => 'created',
            'modified'          => 'modified',
            'created_by'        => 'created_by',
            'created_by_name'   => 'created_by_name',
            'ext_attributes'    => 'ext_attributes'
            
        );
        
        $forbidden_categories = $this->get_forbidden_categories();
        
        if ($id){
            $item = $this->article_m->get($id);
            $this->result = $this->remap_fields($remap_fields, $this->_article_proccess($item, $forbidden_categories));
        }else{
            $admin = $this->get('admin') ? TRUE : FALSE;
            $limit = $this->get('limit') ? $this->get('limit') : 100;
            $page = $this->get('page') ? $this->get('page') : 1;
            $search_text = $this->get('search') ? $this->get('search') : NULL;
            $category_id = $this->get('category') ? $this->get('category') : 0;
            
            if ($category_id){
                $category_id_list = array($category_id);
                //get children category
                $children_categories = $this->category_m->get_select_where('id',array('parent'=>$category_id));
                if ($children_categories){
                    foreach ($children_categories as $child){
                        $category_id_list [] = $child->id;
                    }
                }
                $this->db->where_in('category_id', $category_id_list);
            }
            
            if ($forbidden_categories && !$admin){
                $this->db->where_not_in('category_id', $forbidden_categories);
            }
            
            if ($search_text){
                $this->db->like('title', $search_text);
            }
            
            $condition = NULL;
            if (!$admin){
                $condition = array('published' => 1);
            }
            
            $items = $this->article_m->get_offset('*',$condition,($page-1)*$limit,$limit);
            foreach ($items as $item){
                $this->result [] = $this->remap_fields($remap_fields, $this->_article_proccess($item, $forbidden_categories));
            }
        }
        
        $this->response($this->result);
    }
    
    private function _article_proccess($item, $forbidden_categories = NULL){
        $thumb_sizes =  array(
            'original' => IMAGE_THUMB_ORI,'large' => IMAGE_THUMB_LARGE,
            'portrait' => IMAGE_THUMB_PORTRAIT,'medium' => IMAGE_THUMB_MEDIUM,
            'small' => IMAGE_THUMB_SMALL, 'smaller' => IMAGE_THUMB_SMALLER,
            'square' => IMAGE_THUMB_SQUARE, 'tiny' => IMAGE_THUMB_TINY
        );
        $item->date = date('d-M-Y H:i', $item->date);
        $item->category_name = $this->category_m->get_value('name',array('id'=>$item->category_id));
        $item->link_href = site_url('detail/'.$item->url_title);
        if ($item->image_url){
            $image_url = $item->image_url;
            $item->image_url = new stdClass();
            foreach ($thumb_sizes as $label => $key_value){
                $item->image_url->{$label} = get_image_thumb($image_url, $key_value);
            }
        }else{
            $item->image_url = NULL;
        }
        $item->image_urls = array();
        if ($item->image_type == IMAGE_TYPE_MULTI){
            $images = $this->article_image_m->get_by(array('article_id'=>$item->id));
            if ($images){
                foreach ($images as $img){
                    $image = new stdClass();
                    foreach($thumb_sizes as $label => $key_value){
                        $image->{$label} = get_image_thumb($img->image_url, $key_value);
                    }
                    
                    $item->image_urls[] = $image;
                }
            }
        }else{
            $item->image_urls = NULL;
        }
        $item->tags = $item->tags ? explode(',', $item->tags) : NULL;
        $item->types = $item->types ? explode('|', $item->types) : NULL;
        $item->allow_comment = (bool) $item->allow_comment;
        $item->published = (bool) $item->published;
        $item->created = date('Y-m-d H:i:s', $item->created);
        $item->modified = date('Y-m-d H:i:s', $item->modified);
        if ($forbidden_categories && in_array($item->category_id, $forbidden_categories)){
            $item->created_by_name = $item->category_name;
        }else{
            $item->created_by_name = $this->user_m->get_value('full_name',array('id'=>$item->created_by));
        }
        $item->ext_attributes = $item->ext_attributes ? json_decode($item->ext_attributes):NULL;
        return $item;
    }
    
    function search_post(){
        //load models
        $this->load->model(array('article/article_m','article/category_m','article/article_image_m','users/user_m'));
        $this->load->helper('general');
        $remap_fields = array(
            'id'                => 'id',
            'category_id'       => 'category_id',
            'category_name'     => 'category',
            'title'             => 'title',
            'url_title'         => 'url_title',
            'link_href'         => 'link_href',
            'date'              => 'article_date',
            'day'               => 'day',
            'month'             => 'month',
            'year'              => 'year',
            'synopsis'          => 'synopsis',
            'content'           => 'content',
            'image_url'         => 'image_url',
            'image_type'        => 'image_type',
            'image_urls'        => 'image_urls',
            'tags'              => 'tags',
            'types'             => 'types',
            'allow_comment'     => 'allow_comment',
            'published'         => 'published',
            'view_count'        => 'view',
            'created'           => 'created',
            'modified'          => 'modified',
            'created_by'        => 'created_by',
            'created_by_name'   => 'created_by_name',
            'ext_attributes'    => 'ext_attributes'
            
        );
        
        $search_input = $this->post('search');
        if ($search_input){
            $limit = $this->post('limit') ? $this->post('limit') : 100;
            $page = $this->post('page') ? $this->post('page') : 1;
            $this->db->like('title',$search_input);
            $items = $this->article_m->get_offset('*',array('published'=>1),($page-1)*$limit,$limit);
            foreach ($items as $item){
                $this->result [] = $this->remap_fields($remap_fields, $this->_article_proccess($item));
            }
        }
        
        $this->response($this->result);
    }
    
    function shares_get($id){
        $this->load->model('article/shared_m');
        
        $items = $this->shared_m->get_by(array('article_id'=>$id));
        $result = array();
        foreach ($items as $item){
            $result [] = $this->_shares_proccess($item);
        }
        
        $this->response($this->remap_fields($this->_share_maps, $result));
    }
    function shares_post($id){
        $this->load->model('article/shared_m');
        
        $post_id = $this->post('post_id');
        $post_app = $this->post('post_app') ? $this->post('post_app') : CLIENTAPP_FACEBOOK;
        
        if (($inserted_id=$this->shared_m->save(array(
            'article_id'        => $id,
            'client_app'        => $post_app,
            'user_id'           => $this->session->userdata('userid')?$this->session->userdata('userid'):0,
            'post_id'           => $post_id,
            'post_time'         => time()
        )))){
            $inserted_item = $this->shared_m->get($inserted_id);
            $this->response($this->remap_fields($this->_share_maps, $this->_shares_proccess($inserted_item)));
        }else{
            $this->response(array('status'=>FALSE));
        }
    }
    
    function fb_object_put($article_id){
        $this->load->model(array('article/article_m'));
        $object_id = $this->put('fb_object_id');
        
        if ($this->article_m->save(array('fb_object_id' => $object_id),$article_id)){
            $this->response(array('status'=>true));
        }else{
            $this->response(array('status'=>false));
        }
    }
    
    private function _shares_proccess($item){
        $this->load->model(array('users/user_m', 'article/article_m'));
        
        $item->article_title = $this->article_m->get_value('title',array('id'=>$item->article_id));
        $item->post_datetime = date('d-M-Y H:i:s', $item->post_time);
        $item->user = $item->user_id ? $this->user_m->get_value('full_name',array('id'=>$item->user_id)) : '';
        
        return $item;
    }
    
    function urltitle_test_get($id=NULL){
        $this->load->model(array('article/article_m'));
        
        $url_title = $this->get('url_title');
        if ($this->article_m->is_url_title_unique($url_title,$id)){
            $this->response(array('unique' => TRUE));
        }else{
            //modified url_title
            $url_title = $url_title . '-' . time();
            $this->response(array('unique' => FALSE, 'modified' => $url_title));
        }
    }
}

/*
 * file location: engine/application/controllers/service/article.php
 */
