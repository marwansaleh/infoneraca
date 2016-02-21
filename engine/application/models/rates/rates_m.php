<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of Rates_m
 *
 * @author Marwan
 * @email amazzura.biz@gmail.com
 */
class Rates_m extends MY_Model {
    protected $_table_name = 'rates';
    protected $_primary_key = 'id';
    protected $_primary_filter = 'intval';
    protected $_order_by = 'last_update desc,bank,name';
    
    public $rules = array(
        'bank' => array(
            'field' => 'bank', 
            'label' => 'Bank', 
            'rules' => 'required|trim|xss_clean'
        ), 
        'name' => array(
            'field' => 'name', 
            'label' => 'Mata uang', 
            'rules' => 'required|trim|xss_clean'
        ),
        'sell' => array(
            'field' => 'sell', 
            'label' => 'Nilai jual', 
            'rules' => 'required|numeric|trim|xss_clean'
        ),
        'buy' => array(
            'field' => 'buy', 
            'label' => 'Nilai beli', 
            'rules' => 'required|trim|xss_clean'
        )
    );
}

/*
 * file location: /application/models/rates/rates_m.php
 */
