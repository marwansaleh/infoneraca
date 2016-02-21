<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Description of Comment
 *
 * @author marwansaleh
 */
class Comment extends REST_Api {
    private $_remap_fields = array(
        'id'                => 'id',
        'sender'            => 'sender',
        'name'              => 'name',
        'ip_address'        => 'ip_address',
        'date'              => 'date',
        'article_id'        => 'article',
        'article_title'     => 'title',
        'comment'           => 'comment',
        'is_approved'       => 'approved'
    );
    
    function __construct($config='rest') {
        parent::__construct($config);
        
        $this->load->model(array('article/article_m','users/user_m','article/comment_m'));
    }
    
    function index_get($id=NULL){
        
        if ($id){
            $item = $this->comment_m->get($id);
            $this->result = $this->remap_fields($this->_remap_fields, $this->_proccess_item($item));
        }else{
            $limit = $this->get('limit') ? $this->get('limit') : 100;
            $page = $this->get('page') ? $this->get('page') : 1;
            $article_id = $this->get('article') ? $this->get('article') : 0;
            $approved_only = $this->get('approve') ? $this->get('approve') : FALSE;
            $condition = array();
            if ($approved_only){
                $condition['is_approved'] = 1;
            }
            if ($article_id){
                $condition['article_id'] = $article_id;
            }
            if (!count($condition)){
                $condition = NULL;
            }
            
            $items = $this->comment_m->get_offset('*',$condition,($page-1)*$limit,$limit);
            foreach ($items as $item){
                $this->result [] = $this->remap_fields($this->_remap_fields, $this->_proccess_item($item));
            }
        }
        
        $this->response($this->result);
    }
    
    private function _proccess_item($item){
        $item->name = $this->user_m->get_value('full_name', array('id'=>$item->sender));
        $item->date = date('d-M-Y H:i:s', $item->date);
        $item->article_title = $this->article_m->get_value('title',array('id'=>$item->article_id));
        $item->is_approved = (bool) $item->is_approved;
        return $item;
    }
    
    function index_post(){
        $data = array(
            'sender'        => $this->post('sender'),
            'ip_address'    => $this->input->ip_address(),
            'date'          => time(),
            'article_id'    => $this->post('article'),
            'comment'       => $this->post('comment'),
        );
        
        //is comment auto approve ?
        $parameters = $this->get_sys_vars('COMMENT_AUTO_APPROVE');
        if ($parameters && $parameters['COMMENT_AUTO_APPROVE']){
            $data['is_approved'] = 1;
        }else{
            $data['is_approved'] = 0;
        }
        
        if (($inserted_id=$this->comment_m->save($data))){
            $this->result['status'] = TRUE;
            $new_item = $this->comment_m->get($inserted_id);
            $this->result['item'] = $this->remap_fields($this->_remap_fields, $this->_proccess_item($new_item));
            
            //update article comment count
            $this->article_m->save(array('comment' => $this->_count_comment($this->post('article'))),$this->post('article'));
        }else{
            $this->result['status'] = FALSE;
            $this->result['message'] = $this->comment_m->get_last_message();
        }
        
        $this->response($this->result);
    }
    
    function index_delete($id){
        $item = $this->comment_m->get($id);
        if ($item){
            if ($this->comment_m->delete($id)){
                $this->result['status'] = TRUE;
                $this->result['message'] = 'Data item has been deleted successfully.';
                
                //update article comment count
                $this->article_m->save(array('comment' => $this->_count_comment($id)),$id);
            }else{
                $this->result['status'] = FALSE;
                $this->result['message'] = 'Failed to delete data item with message:'. $this->comment_m->get_last_message;
            }
        }else{
            $this->result['status'] = FALSE;
            $this->result['message'] = 'Can not find data item with ID:'.$id;
        }
        
        $this->response($this->result);
    }
    
    function approve_put($id){
        $approval = $this->put('approval');
        $this->comment_m->save(array('is_approved' => $approval), $id);
        
        $item = $this->comment_m->get($id);
        $this->result = $this->remap_fields($this->_remap_fields, $this->_proccess_item($item));
        
        $this->response($this->result);
    }
    
    private function _count_comment($article_id){
        $comment_count = $this->comment_m->get_count(array('article_id'=>$article_id));
        
        return $comment_count;
    }
}

/*
 * file location: engine/application/controllers/service/comment.php
 */
