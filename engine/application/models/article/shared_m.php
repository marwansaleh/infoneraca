<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of Shared_m
 *
 * @author Marwan
 * @email amazzura.biz@gmail.com
 */
class Shared_m extends MY_Model {
    protected $_table_name = 'article_shares';
    protected $_primary_key = 'id';
    protected $_primary_filter = 'intval';
    protected $_order_by = 'post_time';
}

/*
 * file location: /application/models/article/shared_m.php
 */
