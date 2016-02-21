<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of User_socmed_m
 *
 * @author Marwan Saleh <marwan.saleh@ymail.com>
 */
class User_socmed_m extends MY_Model {
    protected $_table_name = 'user_socmed';
    protected $_primary_key = 'id';
    protected $_primary_filter = 'intval';
    protected $_order_by = 'client_app, client_name';
    
}

/*
 * file location: engine/application/models/users/user_socmed_m.php
 */