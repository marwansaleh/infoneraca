<?php $this->load->view('components/_body_header'); ?>
<?php if (isset($main_slider)&& $main_slider) { $this->load->view('frontend/slider/main_slider'); }?>
<div id="main">
    <div class="container">
        <div class="content col-sm-8">
            <?php $this->load->view($subview);?>
        </div>
        <aside class="col-sm-4">
            <?php $this->load->view('components/_side_right'); ?>
        </aside>
    </div>
</div>
<div id="advert-bottom-bar">
    <div class="container">
        <div class="row">
            <img src="<?php echo userfiles_baseurl(config_item('advert').'1449799747.jpg'); ?>" class="img-responsive" />
        </div>
    </div>
</div>

<?php $this->load->view('components/_body_footer');