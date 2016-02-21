<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of Ow_cuaca_m
 *
 * @author Marwan
 * @email amazzura.biz@gmail.com
 */
class Ow_cuaca_m extends MY_Model {
    protected $_table_name = 'owm_forecast';
    protected $_primary_key = 'id';
    protected $_primary_filter = 'intval';
    protected $_order_by = 'last_checked_time desc';
}

/*
 * file location: /application/models/weather/ow_cuaca_m.php
 */
