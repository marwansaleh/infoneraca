<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Description of Newsticker
 *
 * @author marwansaleh
 */
class Newsticker extends MY_AdminController {
    function __construct() {
        parent::__construct();
        $this->data['active_menu'] = 'static';
        $this->data['page_title'] = 'Newsticker';
        $this->data['page_description'] = 'List and update newsticker';
        
        //load models
        $this->load->model('article/newsticker_m');
    }
    
    function index(){
        $page = $this->input->get('page', TRUE) ? $this->input->get('page', TRUE):1;
        
        $this->data['page'] = $page;
        $offset = ($page-1) * $this->REC_PER_PAGE;
        $this->data['offset'] = $offset;
        
        $where = NULL;
        
        //count totalRecords
        $this->data['totalRecords'] = $this->newsticker_m->get_count($where);
        //count totalPages
        $this->data['totalPages'] = ceil ($this->data['totalRecords']/$this->REC_PER_PAGE);
        $this->data['items'] = array();
        if ($this->data['totalRecords']>0){
            $items = $this->newsticker_m->get_offset('*',$where,$offset,$this->REC_PER_PAGE);
            if ($items){
                foreach($items as $item){
                    $this->data['items'][] = $item;
                }
                $url_format = site_url('cms/newsticker/index?page=%i');
                $this->data['pagination'] = smart_paging($this->data['totalPages'], $page, $this->_pagination_adjacent, $url_format, $this->_pagination_pages, array('records'=>count($items),'total'=>$this->data['totalRecords']));
            }
        }
        $this->data['pagination_description'] = smart_paging_description($this->data['totalRecords'], count($this->data['items']));
        
        //set breadcumb
        breadcumb_add($this->data['breadcumb'], 'Newsticker', site_url('cms/newsticker'), TRUE);
        
        $this->data['subview'] = 'cms/newsticker/index';
        $this->load->view('_layout_admin', $this->data);
    }
    
    function edit(){
        $id = $this->input->get('id', TRUE);
        $page = $this->input->get('page', TRUE);
        
        if (!$this->users->has_access('NEWSTICKER_UPDATE')){
            $this->session->set_flashdata('message_type','error');
            $this->session->set_flashdata('message', 'Sorry. You dont have access for this feature');
            redirect('cms/newsticker/index?page='.$page);
        }
        
        $item = $id ? $this->newsticker_m->get($id):$this->newsticker_m->get_new();
        
        $this->data['item'] = $item;
        
        //set breadcumb
        breadcumb_add($this->data['breadcumb'], 'Newsticker', site_url('cms/newsticker'));
        breadcumb_add($this->data['breadcumb'], 'Update Item', NULL, TRUE);
        
        $this->data['submit_url'] = site_url('cms/newsticker/save?id='.$id.'&page='.$page);
        $this->data['back_url'] = site_url('cms/newsticker/index?page='.$page);
        $this->data['subview'] = 'cms/newsticker/edit';
        $this->load->view('_layout_admin', $this->data);
    }
    
    function save(){
        $id = $this->input->get('id', TRUE);
        $page = $this->input->get('page', TRUE);
        
        if (!$this->users->has_access('NEWSTICKER_UPDATE')){
            $this->session->set_flashdata('message_type','error');
            $this->session->set_flashdata('message', 'Sorry. You dont have access for this feature');
            redirect('cms/newsticker/index?page='.$page);
        }
        
        $rules = $this->newsticker_m->rules;
        $this->form_validation->set_rules($rules);
        //exit(print_r($rules));
        if ($this->form_validation->run() != FALSE) {
            $postdata = $this->newsticker_m->array_from_post(array('news_text','active'));
            
            if ($this->newsticker_m->save($postdata, $id)){
                $this->session->set_flashdata('message_type','success');
                $this->session->set_flashdata('message', 'Data newsticker item saved successfully');
                
                redirect('cms/newsticker/index?page='.$page);
            }else{
                $this->session->set_flashdata('message_type','error');
                $this->session->set_flashdata('message', $this->newsticker_m->get_last_message());
            }
        }
        
        if (validation_errors()){
            $this->session->set_flashdata('message_type','error');
            $this->session->set_flashdata('message', validation_errors());
        }
        
        redirect('cms/newsticker/edit?id='.$id.'&page='.$page);
    }
    
    function delete(){
        $id = $this->input->get('id', TRUE);
        $page = $this->input->get('page', TRUE);
        
        if (!$this->users->has_access('NEWSTICKER_UPDATE')){
            $this->session->set_flashdata('message_type','error');
            $this->session->set_flashdata('message', 'Sorry. You dont have access for this feature');
            redirect('cms/newsticker/index?page='.$page);
        }
        
        //check if found data item
        $item = $this->newsticker_m->get($id);
        if (!$item){
            $this->session->set_flashdata('message_type','error');
            $this->session->set_flashdata('message', 'Could not find data newsticker item. Delete item failed!');
        }else{
            if ($this->newsticker_m->delete($id)){
                $this->session->set_flashdata('message_type','success');
                $this->session->set_flashdata('message', 'Data newsticker item deleted successfully');
            }else{
                $this->session->set_flashdata('message_type','error');
                $this->session->set_flashdata('message', $this->newsticker_m->get_last_message());
            }
        }
        
        redirect('cms/newsticker/index?page='.$page);
    }
}

/*
 * file location: engine/application/controllers/cms/newsticker.php
 */
