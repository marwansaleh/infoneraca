<div class="slider hidden-xs" style="width: 98%">
    <!--<div class="row">-->
        <div class="inner">
            <div id="slider">
                <ul class="slides">
                    <?php foreach ($slider_news as $slider): ?>
                    <li>
                        <div class="single-slide no-margin">
                            <div class="slider-caption" style="height: 118px; max-height: 118px; overflow-y: hidden;">
                                <div class="no-margin">
                                    <div class="title"><a href="<?php echo site_url('detail/'.$slider->url_title); ?>"><?php echo $slider->title; ?></a></div>
                                    <div class="description"><?php echo $slider->synopsis; ?></div>
                                </div>
                            </div>
                            <img src="<?php echo get_image_thumb($slider->image_url, IMAGE_THUMB_LARGE); ?>" alt="" style="max-height: 452px; overflow-y: hidden;">
                        </div>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="slider-navigation">
                <?php $rel=1; foreach ($slider_news as $slider): ?>
                <div class="navigation-item" rel="<?php echo $rel++; ?>">
                    <span><?php echo $slider->title; ?></span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    <!--</div>-->
</div>