<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Description of Staticpage
 *
 * @author marwansaleh
 */
class Staticpage extends MY_AdminController {
    function __construct() {
        parent::__construct();
        $this->data['active_menu'] = 'static';
        $this->data['page_title'] = 'Static Pages';
        $this->data['page_description'] = 'List and update static pages';
        
        //load models
        $this->load->model(array('article/static_m'));
    }
    
    function index(){
        $page = $this->input->get('page', TRUE) ? $this->input->get('page', TRUE):1;
        
        $this->data['page'] = $page;
        $offset = ($page-1) * $this->REC_PER_PAGE;
        $this->data['offset'] = $offset;
        
        $where = NULL;
        
        //count totalRecords
        $this->data['totalRecords'] = $this->static_m->get_count($where);
        //count totalPages
        $this->data['totalPages'] = ceil ($this->data['totalRecords']/$this->REC_PER_PAGE);
        $this->data['items'] = array();
        if ($this->data['totalRecords']>0){
            $items = $this->static_m->get_offset('*',$where,$offset,  $this->REC_PER_PAGE);
            if ($items){
                foreach($items as $item){
                    $this->data['items'][] = $item;
                }
                $url_format = site_url('cms/staticpage/index?page=%i');
                $this->data['pagination'] = smart_paging($this->data['totalPages'], $page, $this->_pagination_adjacent, $url_format, $this->_pagination_pages, array('records'=>count($items),'total'=>$this->data['totalRecords']));
            }
        }
        $this->data['pagination_description'] = smart_paging_description($this->data['totalRecords'], count($this->data['items']));
        
        //set breadcumb
        breadcumb_add($this->data['breadcumb'], 'Static Pages', site_url('cms/staticpage'), TRUE);
        
        $this->data['subview'] = 'cms/staticpage/index';
        $this->load->view('_layout_admin', $this->data);
    }
    
    function edit(){
        $id = $this->input->get('id', TRUE);
        $page = $this->input->get('page', TRUE);
        
        $item = $id ? $this->static_m->get($id):$this->static_m->get_new();
      
        $this->data['item'] = $item;
        
        
        //set breadcumb
        breadcumb_add($this->data['breadcumb'], 'Static Pages', site_url('cms/staticpage'));
        breadcumb_add($this->data['breadcumb'], 'Update Item', NULL, TRUE);
        
        $this->data['submit_url'] = site_url('cms/staticpage/save?id='.$id.'&page='.$page);
        $this->data['back_url'] = site_url('cms/staticpage/index?page='.$page);
        $this->data['subview'] = 'cms/staticpage/edit';
        $this->load->view('_layout_admin', $this->data);
    }
    
    function save(){
        $id = $this->input->get('id', TRUE);
        $page = $this->input->get('page', TRUE);
        
//        if ((!$id && !$this->users->has_access('CATEGORY_CREATE'))||($id && !$this->users->has_access('CATEGORY_EDIT'))){
//            $this->session->set_flashdata('message_type','error');
//            $this->session->set_flashdata('message', 'Sorry. You dont have access for this feature');
//            redirect('cms/category/index?page='.$page);
//        }
        
        $rules = $this->static_m->rules;
        $this->form_validation->set_rules($rules);
        //exit(print_r($rules));
        if ($this->form_validation->run() != FALSE) {
            $postdata = $this->static_m->array_from_post(array('name','caption','title','content','is_menu','sort'));
            
            $postdata['last_update'] = time();
            
            if ($this->static_m->save($postdata, $id)){
                $this->session->set_flashdata('message_type','success');
                $this->session->set_flashdata('message', 'Data item saved successfully');
                
                redirect('cms/staticpage/index?page='.$page);
            }else{
                $this->session->set_flashdata('message_type','error');
                $this->session->set_flashdata('message', $this->static_m->get_last_message());
            }
        }
        
        if (validation_errors()){
            $this->session->set_flashdata('message_type','error');
            $this->session->set_flashdata('message', validation_errors());
        }
        
        redirect('cms/staticpage/edit?id='.$id.'&page='.$page);
    }
    
    function copy(){
        $id = $this->input->get('id', TRUE);
        $page = $this->input->get('page', TRUE);
        
        $item = $this->static_m->get($id);
        if (!$item){
            $this->session->set_flashdata('message_type','error');
            $this->session->set_flashdata('message', 'Could not find data item. Copy item failed!');
        }else{
            $copy_data = array();
            foreach ($item as $key => $value){
                if ($key != 'id'){
                    if ($key == 'name'){
                        $value = $value .'_'.time();
                    }
                    $copy_data[$key] = $value;
                }
            }
            if ($this->static_m->save($copy_data)){
                $this->session->set_flashdata('message_type','success');
                $this->session->set_flashdata('message', 'Data item copied successfully');
            }else{
                $this->session->set_flashdata('message_type','error');
                $this->session->set_flashdata('message', $this->static_m->get_last_message());
            }
        }
        
        redirect('cms/staticpage/index?page='.$page);
    }
    
    function delete(){
        $id = $this->input->get('id', TRUE);
        $page = $this->input->get('page', TRUE);
        
//        if (!$this->users->has_access('CATEGORY_DELETE')){
//            $this->session->set_flashdata('message_type','error');
//            $this->session->set_flashdata('message', 'Sorry. You dont have access for this feature');
//            redirect('cms/category/index?page='.$page);
//        }
        
        //check if found data item
        $item = $this->static_m->get($id);
        if (!$item){
            $this->session->set_flashdata('message_type','error');
            $this->session->set_flashdata('message', 'Could not find data item. Delete item failed!');
        }else{
            if ($this->static_m->delete($id)){
                $this->session->set_flashdata('message_type','success');
                $this->session->set_flashdata('message', 'Data item deleted successfully');
            }else{
                $this->session->set_flashdata('message_type','error');
                $this->session->set_flashdata('message', $this->static_m->get_last_message());
            }
        }
        
        redirect('cms/staticpage/index?page='.$page);
    }
}

/*
 * file location: engine/application/controllers/cms/staticpage.php
 */
