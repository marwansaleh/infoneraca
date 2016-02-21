<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Description of Advert_type
 *
 * @author marwansaleh
 */
class Advert_type extends MY_AdminController {
    function __construct() {
        parent::__construct();
        $this->data['active_menu'] = 'advert';
        $this->data['page_title'] = '<i class="fa fa-cogs"></i> Advert Types';
        $this->data['page_description'] = 'List and update advert types';
        
        //load models
        $this->load->model(array('advert/advtype_m','advert/advert_m'));
    }
    
    function index(){
        $page = $this->input->get('page', TRUE) ? $this->input->get('page', TRUE):1;
        
        $this->data['page'] = $page;
        $offset = ($page-1) * $this->REC_PER_PAGE;
        $this->data['offset'] = $offset;
        
        $where = NULL;
        
        //count totalRecords
        $this->data['totalRecords'] = $this->advtype_m->get_count($where);
        //count totalPages
        $this->data['totalPages'] = ceil ($this->data['totalRecords']/$this->REC_PER_PAGE);
        $this->data['items'] = array();
        if ($this->data['totalRecords']>0){
            $items = $this->advtype_m->get_offset('*',$where,$offset,  $this->REC_PER_PAGE);
            if ($items){
                foreach($items as $item){
                    $item->advert_count = $this->advert_m->get_count(array('type'=>$item->id));
                    $this->data['items'][] = $item;
                }
                $url_format = site_url('cms/advert_type/index?page=%i');
                $this->data['pagination'] = smart_paging($this->data['totalPages'], $page, $this->_pagination_adjacent, $url_format, $this->_pagination_pages, array('records'=>count($items),'total'=>$this->data['totalRecords']));
            }
        }
        $this->data['pagination_description'] = smart_paging_description($this->data['totalRecords'], count($this->data['items']));
        
        //set breadcumb
        breadcumb_add($this->data['breadcumb'], 'Advert Types', site_url('cms/advert_type'), TRUE);
        
        $this->data['subview'] = 'cms/advert_type/index';
        $this->load->view('_layout_admin', $this->data);
    }
    
    function edit(){
        $id = $this->input->get('id', TRUE);
        $page = $this->input->get('page', TRUE);
        
        $item = $id ? $this->advtype_m->get($id):$this->advtype_m->get_new();
        
//        if ((!$id && !$this->users->has_access('CATEGORY_CREATE'))||($id && !$this->users->has_access('CATEGORY_EDIT'))){
//            $this->session->set_flashdata('message_type','error');
//            $this->session->set_flashdata('message', 'Sorry. You dont have access for this feature');
//            redirect('cms/category/index?page='.$page);
//        }
        
        $this->data['item'] = $item;
        
        //set breadcumb
        breadcumb_add($this->data['breadcumb'], 'Advert Types', site_url('cms/advert_type'));
        breadcumb_add($this->data['breadcumb'], 'Update Item', NULL, TRUE);
        
        $this->data['submit_url'] = site_url('cms/advert_type/save?id='.$id.'&page='.$page);
        $this->data['back_url'] = site_url('cms/advert_type/index?page='.$page);
        $this->data['subview'] = 'cms/advert_type/edit';
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
        
        $rules = $this->advtype_m->rules;
        $this->form_validation->set_rules($rules);
        //exit(print_r($rules));
        if ($this->form_validation->run() != FALSE) {
            $postdata = $this->advtype_m->array_from_post(array('name','description'));
            
            if ($this->advtype_m->save($postdata, $id)){
                $this->session->set_flashdata('message_type','success');
                $this->session->set_flashdata('message', 'Data item saved successfully');
                
                redirect('cms/advert_type/index?page='.$page);
            }else{
                $this->session->set_flashdata('message_type','error');
                $this->session->set_flashdata('message', $this->advtype_m->get_last_message());
            }
        }
        
        if (validation_errors()){
            $this->session->set_flashdata('message_type','error');
            $this->session->set_flashdata('message', validation_errors());
        }
        
        redirect('cms/advert_type/edit?id='.$id.'&page='.$page);
    }
    
    function copy(){
        $id = $this->input->get('id', TRUE);
        $page = $this->input->get('page', TRUE);
        
        $item = $this->advtype_m->get($id);
        if (!$item){
            $this->session->set_flashdata('message_type','error');
            $this->session->set_flashdata('message', 'Could not find data item. Copy item failed!');
        }else{
            $copy_data = array(
                'name' => $item->name .'_'.time(),
                'description' => $item->description
            );
            if ($this->advtype_m->save($copy_data)){
                $this->session->set_flashdata('message_type','success');
                $this->session->set_flashdata('message', 'Data item copied successfully');
            }else{
                $this->session->set_flashdata('message_type','error');
                $this->session->set_flashdata('message', $this->advtype_m->get_last_message());
            }
        }
        
        redirect('cms/advert_type/index?page='.$page);
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
        $item = $this->advtype_m->get($id);
        if (!$item){
            $this->session->set_flashdata('message_type','error');
            $this->session->set_flashdata('message', 'Could not find data item. Delete item failed!');
        }else{
            if ($this->advtype_m->delete($id)){
                $this->session->set_flashdata('message_type','success');
                $this->session->set_flashdata('message', 'Data item deleted successfully');
            }else{
                $this->session->set_flashdata('message_type','error');
                $this->session->set_flashdata('message', $this->advtype_m->get_last_message());
            }
        }
        
        redirect('cms/advert_type/index?page='.$page);
    }
}

/*
 * file location: engine/application/controllers/cms/advert_type.php
 */
