<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of Static_m
 *
 * @author Marwan
 * @email amazzura.biz@gmail.com
 */
class Static_m extends MY_Model {
    protected $_table_name = 'static_pages';
    protected $_primary_key = 'id';
    protected $_primary_filter = 'intval';
    protected $_order_by = 'name';
    protected $_timestamps = FALSE;
    
    public $rules = array(
        'caption' => array(
            'field' => 'caption', 
            'label' => 'Caption', 
            'rules' => 'trim|xss_clean'
        ),
        'name' => array(
            'field' => 'name', 
            'label' => 'Static name', 
            'rules' => 'required|trim|xss_clean'
        ),
        'title' => array(
            'field' => 'title', 
            'label' => 'Title', 
            'rules' => 'trim|xss_clean'
        ),
        'content' => array(
            'field' => 'content', 
            'label' => 'Content', 
            'rules' => 'xss_clean'
        )
    );

}

/*
 * file location: /application/models/article/static_m.php
 */
