<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of Newsticker_m
 *
 * @author Marwan
 * @email amazzura.biz@gmail.com
 */
class Newsticker_m extends MY_Model {
    protected $_table_name = 'news_ticker';
    protected $_primary_key = 'id';
    protected $_primary_filter = 'intval';
    protected $_order_by = 'news_text';
    
    public $rules = array(
        'news_text' => array(
            'field' => 'news_text',
            'label' => 'Text', 
            'rules' => 'required|trim|xss_clean'
        )
    );
}

/*
 * file location: /application/models/article/newsticker_m.php
 */
