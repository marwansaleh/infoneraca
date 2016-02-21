<div class="widget">
    <div class="inner">
        <h3>News in Pictures</h3>
        <div class="photo-list">
            <?php if ($photo_news): ?>
            <div  class="nicescroll" style="max-height:120px;overflow:hidden;">
                <?php foreach ($photo_news as $photo): ?>
                <div class="photo">
                    <a href="<?php echo get_image_thumb($photo->image_url, IMAGE_THUMB_ORI); ?>" rel="prettyPhoto[news-in-pictures]" data-toggle="tooltip" title="<?php echo $photo->description; ?>" data-original-title="<?php echo $photo->title; ?>">
                        <img src="<?php echo get_image_thumb($photo->image_url, IMAGE_THUMB_SQUARE); ?>" alt="<?php echo $photo->title; ?>">
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>