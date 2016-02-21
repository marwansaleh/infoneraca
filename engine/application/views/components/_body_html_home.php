<style type="text/css">
    .flexslider {margin-bottom: 5px;}
</style>

<?php $this->load->view('components/_body_header'); ?>
<div id="main">
    <div class="container">
        <div class="col-sm-8">
            <!-- if any slider -->
            <?php if (isset($main_slider)&& $main_slider):?>
            <div class="row">
                <?php $this->load->view('frontend/slider/main_slider'); ?>
            </div>
            <?php endif; ?>
            <!-- highlight news -->
            <div class="row">
                <div class="article-showcase hidden-xs">
                    <div class="inner-border">
                        <div class="half">
                            <?php $rel=1; foreach ($highlight_news as $highlight): ?>
                            <div class="big-article <?php echo $rel==1?'active':''; ?>" rel="<?php echo $rel++; ?>">
                                <div class="title">
                                    <span><a href="<?php echo site_url('detail/'.$highlight->url_title); ?>"><?php echo $highlight->title ?></a></span>
                                </div>
                                <figure style="max-height: 205px; overflow-y: hidden;">
                                    <img src="<?php echo get_image_thumb($highlight->image_url, IMAGE_THUMB_MEDIUM); ?>" alt="">
                                </figure>
                                <div class="main-text">
                                    <div class="inner">
                                        <span class="article-info"><?php echo number_format($highlight->comment); ?> comments, <?php echo date('d/m/Y',$highlight->date); ?></span>
                                        <p><?php echo $highlight->synopsis; ?><a href="<?php echo site_url('detail/'.$highlight->url_title); ?>">Read more...</a></p>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="half">
                            <div class="inner-left-border">
                                <?php $rel=1; foreach ($highlight_news as $highlight): ?>
                                <article <?php echo $rel==1?'class="first-child active':''; ?>" rel="<?php echo $rel++; ?>">
                                    <figure>
                                        <img src="<?php echo get_image_thumb($highlight->image_url, IMAGE_THUMB_SQUARE); ?>" alt="">
                                    </figure>
                                    <div class="text">
                                        <h3><?php echo $highlight->title ?></h3>
                                        <span class="info"><?php echo date('d/m/Y',$highlight->date); ?>, <?php echo number_format($highlight->comment); ?> comments</span>
                                    </div>
                                </article>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- end highlight news -->
            <?php $this->load->view($subview);?>
        </div>
        <aside class="col-sm-4">
            <div class="row">
                <div class="widget">
                    <div class="box-title">
                        <h2 style="margin-top: 0; padding-top: 0;">Inspirasi</h2>
                        <div class="title-line"></div>
                    </div>
                    <div class="articles-slider">
                        <div class="flex-viewport article-inspiration">
                            <a href="<?php echo site_url('detail/'. $inspirasi->url_title); ?>">
                                <figure style="max-height: 350px; overflow-y: hidden;">
                                    <img src="<?php echo get_image_thumb($inspirasi->image_url, IMAGE_THUMB_PORTRAIT); ?>" class="img-responsive">
                                </figure>
                                <div class="article-inspiration-info">
                                    <?php if ($inspirasi->ext_attributes):?>
                                    <h4 class="name"><?php echo $inspirasi->ext_attributes->name;  ?></h4>
                                    <?php endif; ?>
                                    <h2 class="article-title"><?php echo $inspirasi->title; ?></h2>
                                    <p class="synopsis"><?php echo $inspirasi->synopsis; ?></p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="widget">
                    <img src="<?php echo userfiles_baseurl(config_item('advert').'iklan-vds.jpg'); ?>" class="img-responsive" />
                </div>
            </div>
            <?php if (isset($teropong)): ?>
            <div class="row">
                <div class="widget">
                    <div class="box-title">
                        <h2>Teropong</h2>
                        <div class="title-line"></div>
                    </div>
                    <div class="category-image-container">
                        <figure>
                            <img class="img-responsive" src="<?php echo get_image_thumb($teropong->category->image_url, IMAGE_THUMB_ORI); ?>" />
                        </figure>
                        <div class="category-info-container">
                            <h4 class="title"><?php echo $teropong->category->name; ?></h4>
                            <div class="category-info">
                                <p><?php echo $teropong->article->synopsis; ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            <?php if (isset($embun_pagi)): ?>
            <div class="row">
                <div class="widget">
                    <div class="box-title">
                        <h2>Embun Pagi</h2>
                        <div class="title-line"></div>
                    </div>
                </div>
                <div class="category-image-container">
                    <figure>
                        <img class="img-responsive" src="<?php echo get_image_thumb($embun_pagi->category->image_url, IMAGE_THUMB_ORI); ?>" />
                    </figure>
                    <div class="category-info-container right">
                        <h4 class="title"><?php echo $embun_pagi->category->name; ?></h4>
                        <div class="category-info">
                            <p><?php echo $embun_pagi->article->synopsis; ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <?php $this->load->view('components/_side_right'); ?>
        </aside>
    </div>
    <!-- insert advert -->
<!--    <div class="container">
        <div class="row">
            
            <div class="col-sm-4">
                <div class="flexslider flexslider-mid-advert">
                    <ul class="slides">
                        <li>
                            <img src="<?php echo userfiles_baseurl(config_item('advert').'ag01.jpg'); ?>" />
                        </li>
                        <li>
                            <img src="<?php echo userfiles_baseurl(config_item('advert').'ag02.jpg'); ?>" />
                        </li>
                        <li>
                            <img src="<?php echo userfiles_baseurl(config_item('advert').'ag03.jpg'); ?>" />
                        </li>
                        <li>
                            <img src="<?php echo userfiles_baseurl(config_item('advert').'ag04.jpg'); ?>" />
                        </li>
                        <li>
                            <img src="<?php echo userfiles_baseurl(config_item('advert').'ag05.jpg'); ?>" />
                        </li>
                        <li>
                            <img src="<?php echo userfiles_baseurl(config_item('advert').'ag06.jpg'); ?>" />
                        </li>
                        <li>
                            <img src="<?php echo userfiles_baseurl(config_item('advert').'ag07.jpg'); ?>" />
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>-->
    
</div>

<script type="text/javascript">
    $(document).ready(function (){
        $('.flexslider-mid-advert').flexslider({
            animation: "slide",
            slideshow: true,
            controlNav: false,
            animationLoop: true,
            //itemWidth: 300,
            itemMargin: 5
        });
    });
</script>
<div id="advert-bottom-bar">
    <div class="container">
        <div class="row">
            <img src="<?php echo userfiles_baseurl(config_item('advert').'1449799747.jpg'); ?>" class="img-responsive" />
        </div>
    </div>
</div>
<?php $this->load->view('components/_body_footer');