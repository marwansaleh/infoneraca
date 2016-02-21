<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Description of Contact
 *
 * @author marwansaleh
 */
class Contact extends MY_News {
    function __construct() {
        parent::__construct();
        
        //$this->data['mainmenus'] = $this->_mainmenu(0); //no submenu
        $this->data['active_menu'] = 'contact';
    }
    
    function index(){
        $search_limit = 10;
        
        //Load layout parameters for home page
        $parameters = $this->get_sys_parameters('LAYOUT');
        $this->data['parameters'] = $parameters;
        $widgets = explode(',',$parameters['LAYOUT_CUSTOM_WIDGETS']);
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
        
        if ($this->session->flashdata('message_type') && $this->session->flashdata('message_type')=='success'){
            $this->data['showform'] = FALSE;
        }else{
            $this->data['showform'] = TRUE;
            
            $this->load->helper('captcha');

            $captcha = create_captcha(array(
                'img_path'	=> config_item('captcha'),
                'img_url'	=> site_url(config_item('captcha')) .'/',
                'img_width'	=> '150',
                'img_height' => 30,
                'expiration' => 900
            ));
            $this->session->set_userdata('captcha', $captcha);
            $this->data['captcha'] = $captcha;
        }
        
        $this->data['contactus'] = $this->session->userdata('contactpost');
        
        $this->data['subview'] = 'frontend/contact/index';
        $this->load->view('_layout_main', $this->data);
    }
    
    
    function save(){
        $this->load->library('form_validation');
        //Process Form
        $this->load->model('general/contactus_m');
        //check if any post data
        $rules = $this->contactus_m->rules;
        $this->form_validation->set_rules($rules);
        
        $this->session->set_userdata('contactpost', $this->contactus_m->array_from_post(array('name','email','subject','content')));
        
        if ($this->form_validation->run() !== FALSE) {
            $captcha = $this->input->post('captcha');
            $sess_captcha = $this->session->userdata('captcha');
            
            if ($captcha != $sess_captcha['word'] || !file_exists(config_item('captcha').$sess_captcha['time'].'.jpg')){
                $this->session->set_flashdata('message','Kode captcha tidak sesuai');
                $this->session->set_flashdata('message_type',  'error');
                redirect('contact');
            }
            //remove the captcha image
            if (file_exists(config_item('captcha').$sess_captcha['time'].'.jpg')){
                unlink(config_item('captcha').$sess_captcha['time'].'.jpg');
            }
            //save contat to database
            $data = array(
                'ip_address'    => $this->input->ip_address(),
                'date'          => time(),
                'name'          => $this->input->post('name'),
                'email'         => $this->input->post('email'),
                'subject'       => $this->input->post('subject'),
                'content'       => strip_tags($this->input->post('content')),
            );
            $this->contactus_m->save($data);
            
            //remove temp storage data
            $this->session->set_userdata('contactpost',NULL);
            
            $this->session->set_flashdata('message',  'Informasi anda berhasil disimpan');
            $this->session->set_flashdata('message_type',  'success');
        }
        
        if (validation_errors()){
            $this->session->set_flashdata('message',  validation_errors());
            $this->session->set_flashdata('message_type',  'error');
        }
        
        redirect('contact');
    }
}

/*
 * file location: engine/application/controllers/contact.php
 */
