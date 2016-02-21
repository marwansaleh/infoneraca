<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Description of Poststat
 *
 * @author marwansaleh
 */
class Poststat extends MY_AdminController {
    function __construct() {
        parent::__construct();
        $this->data['active_menu'] = 'poststat';
        $this->data['page_title'] = '<i class="fa fa-file-text"></i> Statistic';
        $this->data['page_description'] = 'Posts Statistic';
        
        //Loading model
        $this->load->model(array('article/article_m'));
    }
    
    function index(){
        //get all months year in articles writen
        $this->data['months'] = array(
            1=>'Januari',2=>'Februari',3=>'Maret',4=>'April', 5=>'Mei', 6=>'Juni', 7=>'Juli',
            8=>'Agustus', 9=>'September', 10=>'Oktober', 11=>'Nopember', 12=>'Desember'
        );
        $this->data['years'] = $this->_get_distinct_years();
        
        //set breadcumb
        breadcumb_add($this->data['breadcumb'], 'Statistic', site_url('cms/article'));
        
        $this->data['subview'] = 'cms/poststat/index';
        $this->load->view('_layout_admin', $this->data);
    }
    
    private function _get_distinct_years(){
        if (!isset($this->article_m)){
            $this->load->model('article/article_m');
        }
        
        $sql = 'SELECT DISTINCT YEAR(FROM_UNIXTIME(created)) AS theyear FROM nsc_articles ORDER BY theyear';
        $query = $this->db->query($sql);
        
        $result = array();
        foreach($query->result() as $row){
            $result[]= $row->theyear;
        }
        
        return $result;
    }
}

/*
 * file location: engine/application/controllers/cms/poststat.php
 */
