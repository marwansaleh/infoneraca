<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of Article_visit_m
 *
 * @author Marwan
 * @email amazzura.biz@gmail.com
 */
class Article_visit_m extends MY_Model {
    protected $_table_name = 'article_visited';
    protected $_primary_key = 'id';
    protected $_primary_filter = 'intval';
    protected $_order_by = 'date';
}

/*
 * file location: /application/models/article/article_visit_m.php
 */
