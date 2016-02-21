<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of Global_category_m
 *
 * @author Marwan
 * @email amazzura.biz@gmail.com
 */
class Global_category_m extends MY_Model {
    protected $_table_name = 'global_categories';
    protected $_primary_key = 'id';
    protected $_primary_filter = 'intval';
    protected $_order_by = 'name';
}

/*
 * file location: /application/models/article/global_category_m.php
 */
