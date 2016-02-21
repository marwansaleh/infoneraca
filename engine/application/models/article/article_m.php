<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of Article_m
 *
 * @author Marwan
 * @email amazzura.biz@gmail.com
 */
class Article_m extends MY_Model {
    protected $_table_name = 'articles';
    protected $_primary_key = 'id';
    protected $_primary_filter = 'intval';
    protected $_order_by = 'date desc';
    protected $_timestamps = TRUE;
    
    public $rules = array(
        'title' => array(
            'field' => 'title', 
            'label' => 'Title', 
            'rules' => 'required|trim|min_length[10]|xss_clean'
        ),
        'url_title' => array(
            'field' => 'url_title', 
            'label' => 'Url title', 
            'rules' => 'required|trim|min_length[10]|xss_clean'
        ),
        'url_short' => array(
            'field' => 'url_short', 
            'label' => 'URL short', 
            'rules' => 'trim|min_length[10]|xss_clean'
        ),
        'date' => array(
            'field' => 'date', 
            'label' => 'Date', 
            'rules' => 'xss_clean'
        ),
        'category_id' => array(
            'field' => 'category_id', 
            'label' => 'Category', 
            'rules' => 'numeric|required|xss_clean'
        ),
        'image_url' => array(
            'field' => 'image_url', 
            'label' => 'Main Image', 
            'rules' => 'trim|xss_clean'
        ),
        'image_type' => array(
            'field' => 'image_type', 
            'label' => 'Image type', 
            'rules' => 'trim|xss_clean'
        ),
        'tags' => array(
            'field' => 'tags', 
            'label' => 'Article Tag', 
            'rules' => 'trim|xss_clean'
        ),
        'synopsis' => array(
            'field' => 'synopsis', 
            'label' => 'Synopsis', 
            'rules' => 'trim|xss_clean'
        ),
        'content' => array(
            'field' => 'content', 
            'label' => 'content', 
            'rules' => 'trim|xss_clean'
        ),
        'published' => array(
            'field' => 'published', 
            'label' => 'Published', 
            'rules' => 'numeric|trim|xss_clean'
        ),
        'last_update' => array(
            'field' => 'last_update', 
            'label' => 'Last_update', 
            'rules' => 'xss_clean'
        )
    );

    
    function save($data, $id = NULL) {
        if (!$id){
            $data['created_by'] = $this->session->userdata('userid');
            $data['modified_by'] = $this->session->userdata('userid');
        }else{
            $data['modified_by'] = $this->session->userdata('userid');
        }
        
        //chek if url title is unique
        if (!$this->is_url_title_unique($data['url_title'], $id)){
            $this->_last_message = 'URL title is not unique, try to change the url title value';
            return FALSE;
        }
        
        return parent::save($data, $id);
    }
    
    function increase_view($id){
        $this->db->where('id', $id);
        $this->db->set('view_count', '`view_count`+ 1', FALSE);
        $this->db->update($this->_table_name);
    }
    
    function is_url_title_unique($url_title,$id=NULL){
        if (!$id){
            return $this->get_count(array('url_title' => $url_title))==0;
        }else{
            return $this->get_count(array('url_title' => $url_title, 'id !='=>$id))==0;
        }
    }
}

/*
 * file location: /application/models/article/article_m.php
 */
