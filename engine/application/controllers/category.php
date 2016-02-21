<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Description of Category
 *
 * @author marwansaleh
 */
class Category extends MY_News {
    function __construct() {
        parent::__construct();
        
        //$this->data['mainmenus'] = $this->_mainmenu(0); //no submenu
        $this->data['active_menu'] = 'category';
        
        //load model
        $this->load->model('article/article_image_m', 'image_m');
    }
    
    function index($slug=NULL, $is_tag=FALSE){
        //Load layout parameters for home page
        $parameters = $this->get_sys_parameters('LAYOUT');
        $this->data['parameters'] = $parameters;
        
        //$articles = array();
        if ($is_tag){
            $articles = $this->_tag_news(urldecode($slug), 20);
        }else{
            //get category from slug
            $category = $this->category_m->get_by(array('slug'=>$slug), TRUE);
            if (!$category){exit('Can not find category id for '.$slug);}
            $category_id = $category->id;
            $this->data['active_menu'] = $slug;
            $articles = $this->_category_news($category_id, 20);
            
            $this->data['meta_title'] = $this->data['meta_title'] . ' - ' .$category->name;
        }
        $teropong = $this->_get_category_inherit_ids(CATEGORY_TEROPONG);
        $embun_pagi = $this->_get_category_inherit_ids(CATEGORY_EMBUNPAGI);
            
        $this->data['articles'] = array();
        foreach ($articles as $index => $item){
            if ($index==0){
                if ($item->image_type==IMAGE_TYPE_MULTI){
                    $item->images = $this->image_m->get_by(array('article_id'=>$item->id));
                }
            }
            if (in_array($item->category_id, $teropong) || in_array($item->category_id, $embun_pagi)){
                $item->created_by_name = $this->category_m->get_value('name', array('id'=>$item->category_id));
            }else{
                $item->created_by_name = $this->user_m->get_value('full_name', array('id'=>$item->created_by));
            }
            $this->data['articles'][] = $item;
        }
        
        $widgets = explode(',',$parameters['LAYOUT_CATEGORY_WIDGETS']);
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
        
        $this->data['subview'] = 'frontend/category/index';
        $this->load->view('_layout_main', $this->data);
    }
    
    public function _remap($method=NULL){
        //$this->mobile($method);
        
        if ($this->is_mobile()){
            $this->mobile($method);
        }else{
            if ($method){
                if ($method=='tags'){
                    $this->index($this->input->get('q',TRUE), TRUE);
                }else{
                    //$slug = isset($param[0])?$param[0]:''
                    $this->index($method, FALSE);
                }
            }else{
                redirect('home');
            }
        }
    }
    
    function mobile($slug=NULL){
        //Load layout parameters for home page
        $parameters = $this->get_sys_parameters('MOBILE');
        
        $this->data['parameters'] = $parameters;
        
        //get category id from slug
        $category = $this->category_m->get_by(array('slug'=>$slug), TRUE);
        if (!$category){
            show_404();exit;
        }
        $this->data['category'] = $category;
        $this->data['meta_title'] = $this->data['meta_title'] . ' - ' .$category->name;
        $this->data['active_menu'] = $slug;
        
        //Load popular news
        $limit = isset($parameters['MOBILE_NEWS_NUM'])?$parameters['MOBILE_NEWS_NUM']:15;
        $this->data['limit'] = $limit;
        //data load by ajax
        //$this->data['mobile_news'] = $this->_mobile_news($limit, array('category_id'=>$category->id));
        
        $this->data['subview'] = 'mobile/category/index';
        $this->load->view('_layout_mobile', $this->data);
    }
    
    private function _category_news($category_id, $num=10){
        $fields = 'id,title, url_title, url_short, image_url, category_id, image_type, date, synopsis, comment, created_by';
        
        $category_id_list = array($category_id);
        //get children category
        $children_categories = $this->category_m->get_select_where('id',array('parent'=>$category_id));
        if ($children_categories){
            foreach ($children_categories as $child){
                $category_id_list [] = $child->id;
            }
        }
        $this->db->where_in('category_id', $category_id_list);
        $result = $this->article_m->get_offset($fields, array('published'=>ARTICLE_PUBLISHED),0,$num);
        return $result;
    }
    
    private function _tag_news($tag, $num=10){
        $fields = 'id,title, url_title, url_short, image_url, image_type, date, synopsis, comment, created_by';
        
        /** set up category should not not be seen in news list **/
        $forbiden_cat_ids = $this->get_forbidden_categories();
        if ($forbiden_cat_ids){
            $this->db->where_not_in('category_id', $forbiden_cat_ids);
        }
        
        $this->db->like('tags', $tag);
        return $this->article_m->get_offset($fields, array('published'=>ARTICLE_PUBLISHED),0,$num);
    }
}

/*
 * file location: engine/application/controllers/category.php
 */
