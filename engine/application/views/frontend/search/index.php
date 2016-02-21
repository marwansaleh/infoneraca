<div class="row">
    <div class="blog-style" style="margin-top:10px; margin-bottom:10px;">
        <form method="post" action="<?php echo site_url('search'); ?>">
            <div class="input-group input-group-lg">
                <input type="text" class="form-control" name="search" value="<?php echo $search; ?>" placeholder="Search text..." >
                <div class="input-group-btn">
                    <button class="btn btn-default btn-large" type="submit">Search</button>
                </div>
            </div>
        </form>
    </div>
</div>
<?php if (isset($pagination)):?>
<div class="row">
    <div class="blog-style">
        <div class="pagination col-sm-5">
            Menampilkan <strong><?php echo ($offset+1); ?>-<?php echo ($offset+count($articles)); ?></strong> dari <strong><?php echo $totalRecords; ?></strong> artikel
        </div>
        <?php echo $pagination;?>
    </div>
</div>
<?php endif; ?>
<div class="row">
    <?php foreach ($articles as $article): ?>
    <div class="blog-style">
        <article>
            <div class="inner">
                <?php if ($article->image_url):?>
                <figure>
                    <img class="small" src="<?php echo get_image_thumb($article->image_url, IMAGE_THUMB_SMALL); ?>" alt="">
                </figure>
                <?php endif; ?>
                <div class="text" <?php echo $article->image_url? '':'style="width:100%;"'; ?>>
                    <div class="inner-border">
                        <div class="title"><a href="<?php echo $article->url_short ? $article->url_short : site_url('detail/'.$article->url_title); ?>"><?php echo $article->title; ?></a></div>
                        <div class="description">
                            <div class="date"><?php echo number_format($article->comment); ?> comments, <?php echo date('d/m/Y',$article->date); ?>, <?php echo $article->created_by_name; ?></div>
                            <div class="excerpt">
                                <p><?php echo $article->synopsis; ?> <a href="<?php echo $article->url_short ? $article->url_short : site_url('detail/'.$article->url_title); ?>">Read more...</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </article>
    </div>
    <?php endforeach; ?>
</div>
<?php if (isset($pagination)):?>
<div class="row">
    <div class="blog-style">
        <div class="pagination col-sm-5">
            Menampilkan <strong><?php echo ($offset+1); ?>-<?php echo ($offset+count($articles)); ?></strong> dari <strong><?php echo $totalRecords; ?></strong> artikel
        </div>
        <?php echo $pagination;?>
    </div>
</div>
<?php endif;