<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Description of User
 *
 * @author marwansaleh
 */
class User extends REST_Api {
    private $users;
    function __construct($config='rest') {
        parent::__construct($config);
        //Load User Library
        $this->users = Userlib::getInstance();
    }
    
    function socmed_post(){
        $result = array('status' => FALSE);
        //Load data model
        $this->load->model(array('users/user_m','users/user_socmed_m', 'users/usergroup_m'));
        
        $client_app = $this->post('app');
        $client_id = $this->post('id');
        $name = $this->post('name');
        $email = $this->post('email');
        $picture = $this->post('picture');
        
        //check user scomed exists
        $user_filter_condition = array('client_app' => $client_app, 'client_id' => $client_id);
        if (!$this->user_socmed_m->get_count($user_filter_condition)){
            
            //create new user internall database
            $user_id = $this->user_m->save(array(
                'username'  => $client_app .'_'. $client_id,
                'password'  => $this->users->hash($client_id),
                'full_name' => $name,
                'group_id'  => $this->usergroup_m->get_value('group_id',array('group_name' => 'Socmed')),
                'type'      => USERTYPE_EXTERNAL,
                'email'     => $email,
                'avatar'    => $picture,
                'last_ip'   => $this->input->ip_address(),
                'created_on'=> time(),
                'is_active' => 1
            ));
            if ($user_id){
                $this->user_socmed_m->save(array(
                    'user_id'       => $user_id,
                    'client_app'    => $client_app,
                    'client_id'     => $client_id,
                    'client_name'   => $name,
                    'client_email'  => $email
                ));
                $new_user = new stdClass();
                $new_user->id = $user_id;
                
                $result['status'] = TRUE;
                $result['user'] = $new_user;
            }else{
                $result['message'] = 'Faile creating new user';
            }
        }else{
            $result['status'] = TRUE;
            $user = $this->user_socmed_m->get_select_where('user_id as id',$user_filter_condition, TRUE);
            $result['user'] = $user;
        }
        
        $this->response($result);
    }
    
    function posts_get(){
        $month = $this->get('month');
        $year = $this->get('year');
        
        if ($month && $year){
            //get all users
            $users = $this->users->get_user_internal();
            $articles = $this->_get_stat_articles($month, $year);
            $published = $this->_get_stat_published($month, $year);

            $result = array();
            foreach ($users as $user){
                $statistic = new stdClass();
                $statistic->userid = $user->id;
                $statistic->username = $user->username;
                $statistic->name = $user->full_name;
                $statistic->articles = isset($articles[$user->id]) ? $articles[$user->id] : 0;
                $statistic->published = isset($published[$user->id]) ? $published[$user->id] : 0;
                $result[] = $statistic;
            }
            $this->response($result);
        }else{
            show_404();
        }
    }
    
    private function _get_stat_articles($month, $year){
        if (!isset($this->article_m)){
            $this->load->model('article/article_m');
        }
        
        $sql = 'SELECT created_by,count(*) AS articles FROM nsc_articles WHERE MONTH(FROM_UNIXTIME(created))=? AND YEAR(FROM_UNIXTIME(created))=? GROUP BY created_by';
        $query = $this->db->query($sql, array($month,$year));
        
        $result = array();
        foreach ($query->result() as $row){
            $result[$row->created_by] = $row->articles;
        }
        
        return $result;
    }
    
    private function _get_stat_published($month, $year){
        if (!isset($this->article_m)){
            $this->load->model('article/article_m');
        }
        
        $sql = 'SELECT created_by,count(*) AS articles FROM nsc_articles WHERE published=1 AND MONTH(FROM_UNIXTIME(created))=? AND YEAR(FROM_UNIXTIME(created))=? GROUP BY created_by';
        $query = $this->db->query($sql, array($month,$year));
        
        $result = array();
        foreach ($query->result() as $row){
            $result[$row->created_by] = $row->articles;
        }
        
        return $result;
    }
    
    function postdetail_get(){
        $this->load->model(array('article/article_m','article/category_m'));
        
        $userid = $this->get('user');
        $month = $this->get('month');
        $year = $this->get('year');
        
        if ($userid && $month && $year){
            $result = array();

            //get userinfo
            $user = $this->user_m->get($userid);

            $userinfo = new stdClass();
            $userinfo->userid = $userid;
            $userinfo->username = $user->username;
            $userinfo->name = $user->full_name;
            

            //get article for this user
            $sql = 'SELECT created,title,published,category_id,url_short as url FROM nsc_articles WHERE created_by=? AND MONTH(FROM_UNIXTIME(created))=? AND YEAR(FROM_UNIXTIME(created))=?';
            $articles = $this->db->query($sql, array($userid,$month,$year));
            foreach ($articles->result() as $article){
                $article->date = date('Y-m-d H:i', $article->created);
                $article->category = $this->category_m->get_value('name', array('id'=>$article->category_id));
                $article->published = $article->published==1?'Yes':'No';
                $result['articles'] [] = $article;
            }
            $userinfo->articles = count($result['articles']);
            
            $result['user'] = $userinfo;


            $this->response($result);
        }else{
            show_404();
        }
    }
}

/*
 * file location: engine/application/controllers/service/user.php
 */
