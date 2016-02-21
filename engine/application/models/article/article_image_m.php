<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of Article_image_m
 *
 * @author Marwan
 * @email amazzura.biz@gmail.com
 */
class Article_image_m extends MY_Model {
    protected $_table_name = 'article_images';
    protected $_primary_key = 'id';
    protected $_primary_filter = 'intval';
    protected $_order_by = 'article_id';
}

/*
 * file location: /application/models/article/article_image_m.php
 */
