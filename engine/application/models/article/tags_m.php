<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of Tags_m
 *
 * @author Marwan
 * @email amazzura.biz@gmail.com
 */
class Tags_m extends MY_Model {
    protected $_table_name = 'tags';
    protected $_primary_key = 'tag';
    protected $_primary_filter = 'strval';
    protected $_order_by = 'tag';
    
    public function save_tags($data) {
        $sql = "INSERT IGNORE INTO ". $this->db->dbprefix($this->_table_name)." (tag) VALUES ";
        if (is_array($data)){
            $tags = array();
            foreach ($data as $tag){
                $tags [] = "('$tag')";
            }
            
            $sql .= implode(',', $tags);
        }else{
            $sql .= "('$data')";
        }
        
        $this->db->simple_query($sql);
    }
}

/*
 * file location: /application/models/article/tags_m.php
 */
