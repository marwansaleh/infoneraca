<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Description of Weather
 *
 * @author marwansaleh
 */
class Weather extends MY_AdminController {
    protected $icon_base = 'http://openweathermap.org/img/w/';
    protected $icon_ext = '.png';
    protected $icon_local_path = 'assets/img/cuaca/';
    
    function __construct() {
        parent::__construct();
        $this->data['active_menu'] = 'weather';
        $this->data['page_title'] = '<i class="fa fa-cloud"></i> Adverts';
        $this->data['page_description'] = 'List and update weather';
        
        //load models
        $this->load->model(array('weather/ow_city_m','weather/ow_cuaca_m'));
    }
    
    function index(){
        $page = $this->input->get('page', TRUE) ? $this->input->get('page', TRUE):1;
        
        $this->data['page'] = $page;
        $offset = ($page-1) * $this->REC_PER_PAGE;
        $this->data['offset'] = $offset;
        
        $where = NULL;
        
        //count totalRecords
        $this->data['totalRecords'] = $this->ow_cuaca_m->get_count($where);
        //count totalPages
        $this->data['totalPages'] = ceil ($this->data['totalRecords']/$this->REC_PER_PAGE);
        $this->data['items'] = array();
        if ($this->data['totalRecords']>0){
            $items = $this->ow_cuaca_m->get_offset('*',$where,$offset,  $this->REC_PER_PAGE);
            if ($items){
                foreach($items as $item){
                    $this->data['items'][] = $item;
                }
                $url_format = site_url('cms/weather/index?page=%i');
                $this->data['pagination'] = smart_paging($this->data['totalPages'], $page, $this->_pagination_adjacent, $url_format, $this->_pagination_pages, array('records'=>count($items),'total'=>$this->data['totalRecords']));
            }
        }
        $this->data['pagination_description'] = smart_paging_description($this->data['totalRecords'], count($this->data['items']));
        
        //set breadcumb
        breadcumb_add($this->data['breadcumb'], 'Weather forecast', site_url('cms/weather'), TRUE);
        
        $this->data['subview'] = 'cms/weather/index';
        $this->load->view('_layout_admin', $this->data);
    }
    
    function delete(){
        $id = $this->input->get('id', TRUE);
        $page = $this->input->get('page', TRUE);
        
        //check if found data item
        $item = $this->ow_cuaca_m->get($id);
        if (!$item){
            $this->session->set_flashdata('message_type','error');
            $this->session->set_flashdata('message', 'Could not find data item. Delete item failed!');
        }else{
            if ($this->ow_cuaca_m->delete($id)){
                $this->session->set_flashdata('message_type','success');
                $this->session->set_flashdata('message', 'Data item deleted successfully');
            }else{
                $this->session->set_flashdata('message_type','error');
                $this->session->set_flashdata('message', $this->ow_cuaca_m->get_last_message());
            }
        }
        
        redirect('cms/weather/index?page='.$page);
    }
}

/*
 * file location: engine/application/controllers/cms/weather.php
 */
