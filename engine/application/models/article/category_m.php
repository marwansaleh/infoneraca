<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of Category_m
 *
 * @author Marwan
 * @email amazzura.biz@gmail.com
 */
class Category_m extends MY_Model {
    protected $_table_name = 'category';
    protected $_primary_key = 'id';
    protected $_primary_filter = 'intval';
    protected $_order_by = 'sort';
    
    public $rules = array(
        'name' => array(
            'field' => 'name',
            'label' => 'Category', 
            'rules' => 'required|trim|xss_clean'
        ),
        'slug' => array(
            'field' => 'slug',
            'label' => 'Slug', 
            'rules' => 'required|trim|xss_clean'
        ),
        'sort' => array(
            'field' => 'sort',
            'label' => 'Sort', 
            'rules' => 'numeric|trim|xss_clean'
        ),
        'parent' => array(
            'field' => 'parent',
            'label' => 'Parent', 
            'rules' => 'numeric|trim|xss_clean'
        ),
        'is_menu' => array(
            'field' => 'is_menu',
            'label' => 'Menu', 
            'rules' => 'numeric|trim|xss_clean'
        )
    );
}

/*
 * file location: /application/models/article/category_m.php
 */
