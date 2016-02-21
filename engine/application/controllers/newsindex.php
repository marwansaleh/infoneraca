<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Description of Newsindex
 *
 * @author marwansaleh
 */
class Newsindex extends MY_News {
    function __construct() {
        parent::__construct();
        
        //$this->data['mainmenus'] = $this->_mainmenu(0); //no submenu
        $this->data['active_menu'] = 'newsindex';
    }
    
    function index($day=0,$month=0, $year=0){
        
        $this->data['meta_title'] = $this->data['meta_title'] .' - '. 'Indeks Berita';
        
        //Load layout parameters for home page
        $parameters = $this->get_sys_parameters('LAYOUT');
        $this->data['parameters'] = $parameters;
        
        //get current day, month, year
        $date = getdate();
        
        $this->data['index_day'] = $this->input->post('index_day') ? $this->input->post('index_day') : ($day ? $day : $date['mday']);
        $this->data['index_month'] = $this->input->post('index_month') ? $this->input->post('index_month') : ($month ? $month : $date['mon']);
        $this->data['index_year'] = $this->input->post('index_year') ? $this->input->post('index_year') : ($year ? $year : $date['year']);
        
        //prepare month name
        $this->data['indonesian_months'] = array(1=>'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','Nopember','Desember');
        //prepare years
        $this->data['article_years'] = $this->_range_years();
        
        $articles = $this->_get_index_list($this->data['index_day'], $this->data['index_month'], $this->data['index_year']);
        $this->data['articles'] = array();
        foreach ($articles as $article){
            $article->category_name = $this->category_m->get_value('name',array('id'=>$article->category_id));
            $this->data['articles'][] = $article;
        }
        $widgets = explode(',',$parameters['LAYOUT_CUSTOM_WIDGETS']);
        foreach ($widgets as $widget){
            $this->data['widgets'] [] = trim($widget);
        }
        $widgets = $this->data['widgets'];
        if (in_array(WIDGET_NEWSGROUP, $widgets)){
            //Load popular news
            $this->data['popular_news'] = $this->_popular_news(isset($parameters['LAYOUT_NEWSGROUP_NUM'])?$parameters['LAYOUT_NEWSGROUP_NUM']:4);
            //Load popular news
            $this->data['recent_news'] = $this->_latest_news(isset($parameters['LAYOUT_NEWSGROUP_NUM'])?$parameters['LAYOUT_NEWSGROUP_NUM']:4);
            //Load popular news
            $this->data['commented_news'] = $this->_commented_news(isset($parameters['LAYOUT_NEWSGROUP_NUM'])?$parameters['LAYOUT_NEWSGROUP_NUM']:4);
        }
        if (in_array(WIDGET_NEWSLATEST, $widgets)){
            //Load latest post
            $this->data['latest_post'] = $this->_latest_news(isset($parameters['LAYOUT_NEWSLATEST_NUM'])?$parameters['LAYOUT_NEWSLATEST_NUM']:5);
        }
        if (in_array(WIDGET_STOCKS, $widgets)){
            //Load rates
            $this->data['rates'] = $this->_get_rates();
        }
        if (in_array(WIDGET_NEWSPHOTO, $widgets)){
            //store photo news
            $this->data['photo_news'] = $this->_photo_news(isset($parameters['LAYOUT_NEWSPHOTO_NUM'])?$parameters['LAYOUT_NEWSPHOTO_NUM']:10);
        }
        if (in_array(WIDGET_SELECTED_CATEGORY, $widgets)){
            //get category name
            $selected_category_name = $parameters['LAYOUT_WIDGET_SELECTED_CATEGORY'];
            $selected_category = NULL;
            if ($selected_category_name){
                //get category id
                $selected_category = $this->category_m->get_by(array('slug'=>$selected_category_name),TRUE);
                if (!$selected_category){
                    $selected_category = $this->category_m->get_select_where('id,name',NULL,TRUE);
                }
            }
            
            $this->data['selected_news_category'] = array(
                'category'  => $selected_category,
                'articles' => $this->_article_categories($selected_category->id, 
                    isset($parameters['LAYOUT_HOME_CAT_ARTICLE_NUM'])?$parameters['LAYOUT_HOME_CAT_ARTICLE_NUM']:3)
            );
        }
        
        $this->data['subview'] = 'frontend/newsindex/index';
        $this->load->view('_layout_main', $this->data);
    }
    
    private function _get_index_list($day=NULL, $month=NULL, $year=NULL){
        $fields = 'id, title, category_id, url_title, image_url, image_type, date, synopsis, comment, created_by';
        
        $condition = array();
        if ($day){
            $condition['day'] = $day;
        }
        if ($month){
            $condition['month'] = $month;
        }
        if ($year){
            $condition['year'] = $year;
        }
        if (!count($condition)){
            $condition = NULL;
        }
        return $this->article_m->get_select_where($fields, $condition, FALSE);
    }
    
    private function _range_years(){
        $result = array('min'=>date('Y'), 'max'=>date('Y'));
        
        $query = $this->db->query('SELECT MIN(`year`) min_year, MAX(`year`) max_year FROM '. $this->article_m->get_tablename(TRUE));
        
        if ($query->num_rows() > 0){
            $row = $query->row();
            $result['min'] = $row->min_year;
            $result['max'] = $row->max_year;
        }
        
        return $result;
    }
}

/*
 * file location: engine/application/controllers/newsindex.php
 */
