<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Description of Dashboard
 *
 * @author marwansaleh
 */
class Dashboard extends MY_AdminController {
    function __construct() {
        parent::__construct();
        $this->data['active_menu'] = 'dashboard';
        $this->data['page_title'] = '<i class="fa fa-home"></i> Dashboard';
        //$this->data['page_description'] = 'List and update articles';
    }
    
    function index(){
        //Load last articles
        $this->load->model('article/article_m');
        $articles = $this->article_m->get_offset('*',NULL,0,10);
        $this->data['last_articles'] = array();
        foreach ($articles as $article){
            $article->author = $this->user_m->get_value('full_name',array('id'=>$article->created_by));
            $this->data['last_articles'] [] = $article;
        }
        
        //get article count
        $this->data['article_count'] = $this->article_m->get_count();
        
        //get visitors
        $this->load->model('system/visitor_m');
        $this->data['visitor_count'] = $this->visitor_m->get_count();
        
        //get comment
        $this->load->model('article/comment_m');
        $this->data['comment_count'] = $this->comment_m->get_count();
        
        //get users
        $this->data['user_onlines'] = array();
        $users = $this->user_m->get_select_where('id,session_id,full_name',array('type' => USERTYPE_INTERNAL));
        foreach ($users as $user){
            $user->is_online = $this->users->is_online($user->session_id);
            $this->data['user_onlines'] [] = $user;
        }
        
        //get number of members external users
        $this->data['member_count'] = $this->user_m->get_count(array('type'=>USERTYPE_EXTERNAL));
        
        $this->data['subview'] = 'cms/dashboard/index';
        $this->load->view('_layout_admin', $this->data);
    }
    
}

/*
 * file location: engine/application/controllers/cms/dashboard.php
 */
