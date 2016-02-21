<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Description of Detail
 *
 * @author marwansaleh
 */
class Detail extends MY_News {
    function __construct() {
        parent::__construct();
        
        //$this->data['mainmenus'] = $this->_mainmenu(0); //no submenu
        //load model
        $this->load->model('article/article_image_m', 'image_m');
    }
    
    function _remap($slug){
        //$this->mobile($slug);
        if ($this->is_mobile()){
            $this->mobile($slug);
        }else{
            $this->index($slug);
        }
    }
    
    function index($slug=NULL){
        //Load layout parameters for home page
        $parameters = $this->get_sys_parameters('LAYOUT');
        $this->data['parameters'] = $parameters;
        
        //get item
        $article = $this->article_m->get_by(array('url_title'=>$slug), TRUE);
        if (!$article){
            redirect('home');
            exit;
        }
        
        //replace Indonesiasatu.co with link
        $article->content = str_ireplace('indonesiasatu.co', '<a href="'.site_url().'">IndonesiaSatu.co</a>', $article->content);
        
        $this->data['meta_title'] = $article->title;
        
        $this->_article_view_counter($article->id);
        
        //Does article has multi images
        if ($article->image_type==IMAGE_TYPE_MULTI){
            //load all images
            $article->images = $this->image_m->get_by(array('article_id'=>$article->id));
        }
        $article->share_url = $article->url_short ? $article->url_short : current_url();
        //$article->share_url = current_url();
        //get author name
        $article->created_by_name = $this->user_m->get_value('full_name', array('id'=>$article->created_by));
        
        $this->meta_set_props(array(
            'author'            => 'IndonesiaSatu.co',
            'description'       => str_replace('"', '', $article->synopsis),
            'keywords'          => str_replace('"', '', $article->title),
            'canonical'         => current_url()
        ));
        //set og properties
        $this->og_set_props(array(
            'title'         => str_replace('"', '', $article->title),
            'url'           => site_url('detail/'.$article->url_title), 
            'description'   => str_replace('"', '', $article->synopsis),
            'type'          => 'article'
        ));
        //for google share
        $this->data['metaprop'] = array(
            'name'          => str_replace('"', '', $article->title),
            'description'   => str_replace('"', '', $article->synopsis),
            'image'         => get_image_thumb($article->image_url, IMAGE_THUMB_SMALL),
            'datePublished' => date('d-M-Y H:i', $article->date),
            'articleSection'=> $article->synopsis
        );
        
        if ($article->image_url){
            //get image properties from image shared
            $image_shared = get_image_thumbpath($article->image_url, IMAGE_THUMB_LARGE, TRUE);
            $this->_write_log('Try to read image size '.$image_shared);
            if (file_exists($image_shared)){
                $image_shared_dimensions = @getimagesize($image_shared);
                if ($image_shared_dimensions){
                    $this->_write_log('Image size read done');
                    $this->og_set_props(array(
                        'og:image'         => get_image_thumb($article->image_url, IMAGE_THUMB_LARGE),
                        'og:image:url'     => get_image_thumb($article->image_url, IMAGE_THUMB_LARGE),
                        'og:image:type'    => $image_shared_dimensions['mime'],
                        'og:image:width'   => $image_shared_dimensions[0],
                        'og:image:height'  => $image_shared_dimensions[1]
                    ));
                }else{
                    $this->_write_log('Can not read image size using getimagesize function');
                }
            }else{
                $this->_write_log('Image file '.$image_shared.' can not be found');
            }
        }
        
        if ($this->_is_facebook_robot()){
            $this->_write_log('Facebook crawler is scrapping page '. $article->url_title);
            if (isset($image_shared_dimensions)){
                $this->data['image_dimension'] = $image_shared_dimensions;
            } 
            $this->load->view('metadata/index', $this->data);
            //exit;
        }else{
        
            $this->data['article'] = $article;
            //get category
            $selected_category = $this->category_m->get($article->category_id);
            $this->data['category'] = $selected_category;
            $this->data['related_news'] = $this->_related_news(explode(',',$article->tags), 3, array('id !='=>$article->id));

            //need to filter category to determine which name should be shown as writer
            $teropong = $this->_get_category_inherit_ids(CATEGORY_TEROPONG);
            if (in_array($article->category_id, $teropong)){
                $this->data['article_author'] = $selected_category->name;
            }else{
                $embun_pagi = $this->_get_category_inherit_ids(CATEGORY_EMBUNPAGI);
                if (in_array($article->category_id, $embun_pagi)){
                    $this->data['article_author'] = $selected_category->name;
                }
            }

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
                $this->data['selected_news_category'] = array(
                    'category'  => $selected_category,
                    'articles' => $this->_article_categories($selected_category->id, 5)
                );
            }

            $category_slug = $this->category_m->get_value('slug', array('id'=>$article->category_id));
            $this->data['active_menu'] = $category_slug;

            //support for comments
            $this->data['is_admin'] = $this->users->isLoggedin() ? $this->users->is_admin() : FALSE;

            //$this->data['main_slider'] = TRUE;
            $this->data['subview'] = 'frontend/detail/index';
            $this->load->view('_layout_main', $this->data);
        }
    }
    
    function mobile($slug=NULL){
        //get item
        $article = $this->article_m->get_by(array('url_title'=>$slug), TRUE);
        if (!$article){
            redirect('home');
            exit;
        }
        
        $this->data['meta_title'] = $this->data['meta_title'] . ' - ' .$article->title;
        
        $this->_article_view_counter($article->id);
        
        $article->share_url = site_url('detail/'.$article->url_title);
        $article->category_name = $this->category_m->get_value('name', array('id'=>$article->category_id));
        //get author name
        $article->created_by_name = $this->user_m->get_value('full_name', array('id'=>$article->created_by));
        
        $this->data['article'] = $article;
        $this->data['related_news'] = $this->_related_news(explode(',',$article->tags), 6, array('id !='=>$article->id));
        
        $this->meta_set_props(array(
            'author'            => 'IndonesiaSatu.co',
            'description'       => $article->synopsis,
            'keywords'          => $article->title,
            'canonical'         => current_url()
        ));
        
        //set og properties
        $this->og_set_props(array(
            'title'         => $article->title,
            'url'           => site_url('detail/'.$article->url_title), 
            'description'   => $article->synopsis,
            'type'          => 'article',
            //'article:author'=> $article->created_by_name,
            'image'         => get_image_thumb($article->image_url, IMAGE_THUMB_LARGE)
        ));
        
        //$this->data['main_slider'] = TRUE;
        $this->data['subview'] = 'mobile/detail/index';
        $this->load->view('_layout_mobile', $this->data);
    }
    
    private function _related_news($tags_array, $num=3, $condition=NULL){
        $limit_each_tag = 10;
        
        /** set up category should not not be seen in news list **/
        $forbiden_cat_ids = $this->get_forbidden_categories();
        /** end setup **/
        
        if ($tags_array && is_array($tags_array)){
            $current = time();
            $related = array();
            foreach ($tags_array as $tag){
                if ($forbiden_cat_ids){
                    $this->db->where_not_in('category_id', $forbiden_cat_ids);
                }
                $this->db->like('tags', $tag);
                $result = $this->article_m->get_offset('id,title,url_title,url_short,synopsis,image_url,date',$condition,0,$limit_each_tag);
                
                if ($result){
                    foreach ($result as $r){
                        $related[$current - $r->date] = $r;
                    }
                }
            }
            //now sort by key (the latest)
            ksort($related);
            //create new array for last proccess
            $new_related = array();
            foreach ($related as $r){
                if (count($new_related)<$num){
                    if (!in_array($r->id, $new_related)){
                        $new_related [$r->id] = $r;
                    }
                }
            }
            
            return $new_related;
        }
        
        return NULL;
    }
    
    private function _is_facebook_robot(){
        $useragent = $this->agent->agent_string();
        $needle = 'facebookexternalhit/1.1';
        
        if (strpos($useragent, $needle)!== FALSE){
            return TRUE;
        }else{
            return FALSE;
        }
    }
}

/*
 * file location: engine/application/controllers/detail.php
 */
