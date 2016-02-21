<div class="article-box">
    <div class="box-title">
        <h2>Inspirasi</h2>
        <div class="title-line"></div>
    </div>
    <div class="articles-slider">
        <div class="flex-viewport">
            <div class="main-article">
                <div class="title">
                    <span><a href="<?php echo $inspirasi->url_short ? $inspirasi->url_short : site_url('detail/'.$inspirasi->url_title); ?>"><?php echo $inspirasi->title; ?></a></span>
                </div>
                <figure>
                    <img src="<?php echo get_image_thumb($inspirasi->image_url, IMAGE_THUMB_MEDIUM); ?>" alt="">
                </figure>
                <div class="main-text">
                    <div class="inner">
                        <span class="article-info"><?php echo number_format($inspirasi->comment); ?> comments, <?php echo date('d/m/Y',$inspirasi->date); ?>, by <?php echo $inspirasi->created_by_name; ?></span>
                        <p><?php echo $inspirasi->synopsis; ?> <a href="<?php echo $inspirasi->url_short ? $inspirasi->url_short : site_url('detail/'.$inspirasi->url_title); ?>">Read more...</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
