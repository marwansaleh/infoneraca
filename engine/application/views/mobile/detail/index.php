<div class="blog-page">
    <article>
        <span style="color: #CCC; font-size: 12px;"><?php echo strtoupper($article->category_name); ?></span>
        <h3 class="title" style="margin-top:0;"><?php echo $article->title; ?></h3>
        <div class="info">
            <span class="date"><?php echo date('d/m/Y H:i',$article->date); ?></span> 
            <?php if ($article->hide_author!=AUTHOR_HIDDEN): ?>
            --- <span class="author"><?php echo $article->created_by_name; ?></span>
            <?php endif; ?>
        </div>
        <?php if ($article->image_url): ?>
        <figure>
            <img class="img-responsive" src="<?php echo get_image_thumb($article->image_url, IMAGE_THUMB_LARGE); ?>" alt="Article image">
            <?php if ($article->image_caption): ?><figcaption class="info"><?php echo $article->image_caption; ?></figcaption><?php endif; ?>
        </figure>
        <?php endif; ?>
        <div class="content">
            <?php echo $article->content; ?>
        </div>
        <div style="clear: both; float: left; width: 100%; margin-top: 10px;">
            <div class="share-blog">
                <div class="hidden-xs">
                    <div class="btn-group btn-group-justified" role="group">
                        <a class="btn btn-default" 
                           href="mailto:?Subject=<?php echo urlencode($article->title); ?>&body=<?php echo urlencode($article->synopsis . '. ' . $article->share_url); ?>" 
                           target="_blank"><span class="glyphicon glyphicon-envelope"></span> Email
                        </a>
                        <a class="btn btn-social btn-twitter" href="javascript:twitterShare('<?php echo urlencode($article->share_url); ?>','<?php echo urlencode($article->title); ?>');"><span class="fa fa-twitter"></span> Twitter</a>
                        <a id="btn-google" class="btn btn-social btn-google-plus" 
                           href="https://plus.google.com/share?url=<?php echo $article->share_url; ?>&hl=id" 
                           onclick="javascript:window.open(this.href,
                '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600'); return false;">
                            <span class="fa fa-google-plus"></span> Google
                        </a>
                        <a class="btn btn-social btn-facebook" href="javascript:facebookShare(<?php echo $article->id; ?>,'<?php echo urlencode($article->share_url); ?>');"><span class="fa fa-facebook"></span> Facebook</a>
                    </div>
                </div>
                <div class="visible-xs">
                    <a class="btn btn-default btn-block btn-sm" 
                       href="mailto:?Subject=<?php echo urlencode($article->title); ?>&body=<?php echo urlencode($article->synopsis . '. ' . $article->share_url); ?>" 
                       target="_blank"><span class="glyphicon glyphicon-envelope"></span> Email
                    </a>
                    <a class="btn btn-block btn-sm btn-social btn-twitter" href="javascript:twitterShare('<?php echo urlencode($article->share_url); ?>','<?php echo urlencode($article->title); ?>');"><span class="fa fa-twitter"></span> Twitter</a>
                    <a id="btn btn-google btn-block btn-sm" class="btn btn-social btn-google-plus" 
                       href="https://plus.google.com/share?url=<?php echo $article->share_url; ?>&hl=id" 
                       onclick="javascript:window.open(this.href,
            '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600'); return false;">
                        <span class="fa fa-google-plus"></span> Google
                    </a>
                    <a class="btn btn-block btn-sm btn-social btn-facebook" href="javascript:facebookShare(<?php echo $article->id; ?>,'<?php echo urlencode($article->share_url); ?>');"><span class="fa fa-facebook"></span> Facebook</a>
                </div>
            </div>
        </div>
    </article>
</div>
<!-- related news -->
<?php if ($related_news): ?>
<div class="blog-page">
    <h4 style="border-bottom: solid 1px #CACACA; margin-bottom:0;">Berita Terkait</h4>

    <ul class="media-list">
        <?php foreach($related_news as $news): ?>
        <li class="media" data-href="<?php echo site_url('detail/'.$news->url_title); ?>">
            <div class="media-left">
                <a href="<?php echo site_url('detail/'.$news->url_title); ?>">
                    <img class="media-object" src="<?php echo get_image_thumb($news->image_url, IMAGE_THUMB_SQUARE); ?>" alt="<?php echo $news->url_title ?>">
                </a>
            </div>
            <div class="media-body">
                <h4 class="media-heading"><a href="<?php echo site_url('detail/'.$news->url_title); ?>"><?php echo $news->title; ?></a></h4>
                <p class="date"><?php echo date('d-M-Y H:i', $news->date); ?></p>
            </div>
        </li>
        <?php endforeach;?>
    </ul>
</div>
<?php endif; ?>


<div class="blog-page" style="width: 100%;"><a class="btn btn-primary btn-block btn-sm" href="<?php echo site_url('home'); ?>">Back</a></div>


<script src="<?php echo site_url(config_item('path_assets').'js/socmed.js'); ?>"></script>