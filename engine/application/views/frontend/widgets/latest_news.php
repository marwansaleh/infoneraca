<div class="widget">
    <div class="inner">
        <h3>Latest Posts</h3>
        <div class="list">
            <?php if ($latest_post): ?>
            <ul class="nicescroll" style="height:290px;overflow:hidden;">
                <?php foreach ($latest_post as $latest): ?>
                <li>
                    <a title="" href="<?php echo $latest->url_short ? $latest->url_short : site_url('detail/'.$latest->url_title); ?>">
                        <figure>
                            <img class="tiny" src="<?php echo get_image_thumb($latest->image_url, IMAGE_THUMB_TINY); ?>" alt="">
                        </figure>
                        <p><?php echo $latest->title; ?> <br> <span> <?php echo date('d M Y',$latest->date); ?> </span></p>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>
            <?php endif; ?>
        </div>
    </div>
</div>