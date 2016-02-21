<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Description of Newsindex
 *
 * @author marwansaleh
 */
class Staticpage extends MY_News {
    function __construct() {
        parent::__construct();
        
        //$this->data['mainmenus'] = $this->_mainmenu(0); //no submenu
        $this->data['active_menu'] = 'lainnya';
        $this->load->model('article/static_m');
    }
    
    function index($name=NULL){
        $condition = NULL;
        if ($name){
            $condition = array('name' => $name);
        }
        $staticpage = $this->static_m->get_by($condition, TRUE);
        if (!$staticpage){
            $staticpage = $this->static_m->get_by(NULL, TRUE);
        }
        $this->data['staticpage'] = $staticpage;
        $this->data['meta_title'] = $this->data['meta_title'] .' - '. $staticpage->title;
        
        //Load layout parameters for home page
        $parameters = $this->get_sys_parameters('LAYOUT');
        $this->data['parameters'] = $parameters;
        
        $widgets = explode(',',$parameters['LAYOUT_DETAIL_WIDGETS']);
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
        
        $this->data['subview'] = 'frontend/staticpage/index';
        $this->load->view('_layout_main', $this->data);
    }
}

/*
 * file location: engine/application/controllers/staticpage.php
 */
