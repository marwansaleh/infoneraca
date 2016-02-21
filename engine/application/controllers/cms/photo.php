<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Description of Photo
 *
 * @author marwansaleh
 */
class Photo extends MY_AdminController {
    function __construct() {
        parent::__construct();
        $this->data['active_menu'] = 'photo';
        $this->data['page_title'] = 'Photo News';
        $this->data['page_description'] = 'List and update photo news';
        
        //load models
        $this->load->model('photo/photo_m');
    }
    
    function index(){
        $page = $this->input->get('page', TRUE) ? $this->input->get('page', TRUE):1;
        
        $this->data['page'] = $page;
        $offset = ($page-1) * $this->REC_PER_PAGE;
        $this->data['offset'] = $offset;
        
        $where = NULL;
        
        //count totalRecords
        $this->data['totalRecords'] = $this->photo_m->get_count($where);
        //count totalPages
        $this->data['totalPages'] = ceil ($this->data['totalRecords']/$this->REC_PER_PAGE);
        $this->data['items'] = array();
        if ($this->data['totalRecords']>0){
            $items = $this->photo_m->get_offset('*',$where,$offset,  $this->REC_PER_PAGE);

            if ($items){
                foreach($items as $item){
                    $this->data['items'][] = $item;
                }
                $url_format = site_url('cms/photo/index?page=%i');
                $this->data['pagination'] = smart_paging($this->data['totalPages'], $page, $this->_pagination_adjacent, $url_format, $this->_pagination_pages, array('records'=>count($items),'total'=>$this->data['totalRecords']));
            }
        }
        $this->data['pagination_description'] = smart_paging_description($this->data['totalRecords'], count($this->data['items']));
        
        //set breadcumb
        breadcumb_add($this->data['breadcumb'], 'Photo News', site_url('cms/photo'), TRUE);
        
        $this->data['subview'] = 'cms/photo/index';
        $this->load->view('_layout_admin', $this->data);
    }
    
    function edit(){
        $id = $this->input->get('id', TRUE);
        $page = $this->input->get('page', TRUE);
        
        $item = $id ? $this->photo_m->get($id):$this->photo_m->get_new();
        
        if (!$this->users->has_access('PHOTO_NEWS_UPDATE')){
            $this->session->set_flashdata('message_type','error');
            $this->session->set_flashdata('message', 'Sorry. You dont have access for this feature');
            redirect('cms/photo/index?page='.$page);
        }
        
        $this->data['item'] = $item;
        //set breadcumb
        breadcumb_add($this->data['breadcumb'], 'Photo News', site_url('cms/photo'));
        breadcumb_add($this->data['breadcumb'], 'Update Item', NULL, TRUE);
        
        $this->data['submit_url'] = site_url('cms/photo/save?id='.$id);
        $this->data['back_url'] = site_url('cms/photo/index?page='.$page);
        $this->data['subview'] = 'cms/photo/edit';
        $this->load->view('_layout_admin', $this->data);
    }
    
    function save(){
        $id = $this->input->get('id', TRUE);
        $page = $this->input->get('page', TRUE);
        
        if (!$this->users->has_access('PHOTO_NEWS_UPDATE')){
            $this->session->set_flashdata('message_type','error');
            $this->session->set_flashdata('message', 'Sorry. You dont have access for this feature');
            redirect('cms/photo/index?page='.$page);
        }
        
        $rules = $this->photo_m->rules;
        $this->form_validation->set_rules($rules);
        //exit(print_r($rules));
        if ($this->form_validation->run() != FALSE) {
            $postdata = $this->photo_m->array_from_post(array('image_url','title','date','description'));
            
            if (!$postdata['date']){
                $postdata['date'] = time();
            }else{
                $postdata['date'] = strtotime($postdata['date']);
            }
            
            if ($this->photo_m->save($postdata, $id)){
                $this->session->set_flashdata('message_type','success');
                $this->session->set_flashdata('message', 'Data photo item saved successfully');
                
                redirect('cms/photo/index?page='.$page);
            }else{
                $this->session->set_flashdata('message_type','error');
                $this->session->set_flashdata('message', $this->photo_m->get_last_message());
            }
        }
        
        if (validation_errors()){
            $this->session->set_flashdata('message_type','error');
            $this->session->set_flashdata('message', validation_errors());
        }
        
        redirect('cms/photo/edit?id='.$id.'&page='.$page);
    }
    
    function delete(){
        $id = $this->input->get('id', TRUE);
        $page = $this->input->get('page', TRUE);
        
        if (!$this->users->has_access('PHOTO_NEWS_UPDATE')){
            $this->session->set_flashdata('message_type','error');
            $this->session->set_flashdata('message', 'Sorry. You dont have access for this feature');
            redirect('cms/photo/index?page='.$page);
        }
        
        //check if found data item
        $item = $this->photo_m->get($id);
        if (!$item){
            $this->session->set_flashdata('message_type','error');
            $this->session->set_flashdata('message', 'Could not find data photo item. Delete item failed!');
        }else{
            if ($this->photo_m->delete($id)){
                $this->session->set_flashdata('message_type','success');
                $this->session->set_flashdata('message', 'Data photo item deleted successfully');
            }else{
                $this->session->set_flashdata('message_type','error');
                $this->session->set_flashdata('message', $this->photo_m->get_last_message());
            }
        }
        
        redirect('cms/photo/index?page='.$page);
    }
}

/*
 * file location: engine/application/controllers/cms/photo.php
 */
