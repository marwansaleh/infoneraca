<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of Rates_update_m
 *
 * @author Marwan
 * @email amazzura.biz@gmail.com
 */
class Rates_update_m extends MY_Model {
    protected $_table_name = 'rates_update';
    protected $_primary_key = 'id';
    protected $_primary_filter = 'intval';
    protected $_order_by = 'last_update';
    
    public $rules = array(
        'bank' => array(
            'field' => 'bank', 
            'label' => 'Bank', 
            'rules' => 'required|trim|xss_clean'
        ), 
        'last_update' => array(
            'field' => 'last_update', 
            'label' => 'Last update', 
            'rules' => 'required|trim|xss_clean'
        )
    );
    
    public function save($data, $id = NULL) {
        if (isset($data['bank']) && !$this->is_unique($data['bank'], $id)){
            $this->_last_message = 'Bank\'s name must unique';
            return FALSE;
        }
        
        parent::save($data, $id);
    }
    
    function is_unique($subject, $id=NULL){
        if (!$id){
            $found = $this->get_count(array('bank'=>$subject));
        }else{
            $found = $this->get_count(array('id != ' => $id, 'bank'=>$subject));
        }
        
        return !$found;
    }
}

/*
 * file location: /application/models/rates/rates_update_m.php
 */
