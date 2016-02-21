<div class="widget">
    <div class="inner">
        <div class="tabbable"> <!-- Only required for left/right tabs -->
            <ul class="nav nav-tabs">
                <li class="first-child active">
                    <a data-toggle="tab" href="#tab1"><div class="inner-tab">Popular</div></a>
                </li>
                <li>
                    <a data-toggle="tab" href="#tab2"><div class="inner-tab">Recent</div></a>
                </li>
                <li>
                    <a data-toggle="tab" href="#tab3"><div class="inner-tab">Comments</div></a>
                </li>
            </ul>
            <div class="tab-content">
                <div id="tab1" class="tab-pane active">
                    <?php if ($popular_news): ?>
                    <ul class="nicescroll" style="height:295px;overflow:hidden;">
                        <?php foreach ($popular_news as $popular): ?>
                        <li>
                            <a title="" href="<?php echo $popular->url_short ? $popular->url_short : site_url('detail/'.$popular->url_title); ?>">
                                <figure>
                                    <img class="tiny" src="<?php echo get_image_thumb($popular->image_url, IMAGE_THUMB_TINY); ?>" alt="">
                                </figure>
                                <p><?php echo $popular->title; ?> <br> <span> <?php echo date('d M Y',$popular->date); ?> </span>, <span> <?php echo number_format($popular->view_count); ?> views </span></p>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    <?php endif; ?>
                </div>
                <div id="tab2" class="tab-pane">
                    <?php if ($recent_news): ?>
                    <ul class="nicescroll" style="height:295px;overflow:hidden;">
                        <?php foreach ($recent_news as $recent): ?>
                        <li>
                            <a title="" href="<?php echo $recent->url_short ? $recent->url_short : site_url('detail/'.$recent->url_title); ?>">
                                <figure>
                                    <img class="tiny" src="<?php echo get_image_thumb($recent->image_url, IMAGE_THUMB_TINY); ?>" alt="">
                                </figure>
                                <p><?php echo $recent->title; ?> <br> <span> <?php echo date('D, d M Y',$recent->date); ?> </span></p>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    <?php endif; ?>
                </div>
                <div id="tab3" class="tab-pane">
                    <?php if ($commented_news): ?>
                    <ul class="nicescroll" style="height:295px;overflow:hidden;">
                        <?php foreach ($commented_news as $commented): ?>
                        <li>
                            <a title="" href="<?php echo $commented->url_short ? $commented->url_short : site_url('detail/'.$commented->url_title); ?>">
                                <figure>
                                    <img class="tiny" src="<?php echo get_image_thumb($commented->image_url, IMAGE_THUMB_TINY); ?>" alt="">
                                </figure>
                                <p><?php echo $commented->title; ?> <br> <span> <?php echo date('d M Y',$commented->date); ?> </span>, <span> <?php echo number_format($popular->comment); ?> views </span></p></p>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>