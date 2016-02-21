<?php
$this->load->view('components/_header_html');
if (isset($active_menu) && $active_menu == 'home'){
    $this->load->view('components/_body_html_home');
}else{
    $this->load->view('components/_body_html');
}
$this->load->view('components/_footer_html');