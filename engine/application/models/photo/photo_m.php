<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of Photo_m
 *
 * @author Marwan
 * @email amazzura.biz@gmail.com
 */
class Photo_m extends MY_Model {
    protected $_table_name = 'photo_news';
    protected $_primary_key = 'id';
    protected $_primary_filter = 'intval';
    protected $_order_by = 'date desc';
    
    public $rules = array(
        'image_url' => array(
            'field' => 'image_url',
            'label' => 'Image', 
            'rules' => 'required|trim|xss_clean'
        ),
        'date' => array(
            'field' => 'date',
            'label' => 'Date', 
            'rules' => 'trim|xss_clean'
        ),
        'description' => array(
            'field' => 'description',
            'label' => 'Description', 
            'rules' => 'trim|xss_clean'
        )
    );
}

/*
 * file location: /application/models/photo/photo_m.php
 */
