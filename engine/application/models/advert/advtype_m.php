<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of Advtype_m
 *
 * @author Marwan
 * @email amazzura.biz@gmail.com
 */
class Advtype_m extends MY_Model {
    protected $_table_name = 'advert_types';
    protected $_primary_key = 'id';
    protected $_primary_filter = 'intval';
    protected $_order_by = 'name';
    
    public $rules = array(
        'name' => array(
            'field' => 'name',
            'label' => 'Type name', 
            'rules' => 'required|trim|xss_clean'
        ),
        'description' => array(
            'field' => 'description',
            'label' => 'Type description', 
            'rules' => 'trim|xss_clean'
        )
    );
    
    public function save($data, $id = NULL) {
        if ($id && $this->get_count(array('id !='=>$id,'name'=>$data['name']))){
            $this->_last_message = 'Duplicate entry for '.$data['name'];
            return FALSE;
        }else if (!$id && $this->get_count(array('name'=>$data['name']))){
            $this->_last_message = 'Duplicate entry for '.$data['name'];
            return FALSE;
        }
        return parent::save($data, $id);
    }
}

/*
 * file location: /application/models/advert/advtype_m.php
 */
