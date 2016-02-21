<div class="row">
    <?php $i=0; foreach ($articles as $article): ?>
    <?php if ($i==0): ?>
    <div class="blog-page">
        <article>
            <h1 class="title">
                <a href="<?php echo $article->url_short ? $article->url_short : site_url('detail/'.$article->url_title); ?>"><?php echo $article->title; ?></a>
            </h1>
            <?php if ($article->image_type==IMAGE_TYPE_MULTI): ?>
            <?php $this->load->view('frontend/slider/detail_slider', array('images'=>$article->images)); ?>
            <?php elseif($article->image_url): ?>
            <figure>
                <a href="<?php echo $article->url_short ? $article->url_short : site_url('detail/'.$article->url_title); ?>">
                    <img class="img-responsive large" src="<?php echo get_image_thumb($article->image_url, IMAGE_THUMB_LARGE); ?>" alt="Article image">
                </a>
            </figure>
            <?php endif; ?>
            <div class="blog-content">
                <div class="info">
                    <a href="#"><?php echo number_format($article->comment); ?> comments</a>,
                    <span class="date"><?php echo date('d/m/Y',$article->date); ?></span>, by
                    <a href="#"><?php echo $article->created_by_name; ?></a>
                </div>
                <?php echo $article->synopsis; ?>
            </div>
        </article>
    </div>
    <?php else: ?>
    <div class="blog-style">
        <article>
            <div class="inner">
                <?php if ($article->image_url): ?>
                <figure>
                    <img class="small" src="<?php echo get_image_thumb($article->image_url, IMAGE_THUMB_SMALL); ?>" alt="">
                </figure>
                <?php endif; ?>
                <div class="text" <?php echo $article->image_url? '':'style="width:100%;"'; ?>>
                    <div class="inner-border">
                        <div class="title"><a href="<?php echo $article->url_short ? $article->url_short : site_url('detail/'.$article->url_title); ?>"><?php echo $article->title; ?></a></div>
                        <div class="description">
                            <div class="date"><?php echo number_format($article->comment); ?> comments, <?php echo date('d/m/Y',$article->date); ?>, by <?php echo $article->created_by_name; ?></div>
                            <div class="excerpt">
                                <p><?php echo $article->synopsis; ?> <a href="<?php echo $article->url_short ? $article->url_short : site_url('detail/'.$article->url_title); ?>">Read more...</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </article>
    </div>
    <?php endif; ?>
    <?php $i++; endforeach; ?>
</div>