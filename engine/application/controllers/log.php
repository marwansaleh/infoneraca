<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Description of Log
 *
 * @author marwansaleh
 */
class Log extends MY_BaseController {
    function __construct() {
        parent::__construct();
    }
    
    function index($token=NULL){
        if ($token && $token == 'melog'){
            $this->load->view('log/index');
        }else{
            show_404();
        }
    }
}

/*
 * file location: engine/application/controllers/log.php
 */
