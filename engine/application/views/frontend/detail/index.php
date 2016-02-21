<input type="hidden" id="url_short" value="<?php echo $article->url_short; ?>" />
<div class="row">
    <div class="blog-page">
        <article>
            <h1 class="title">
                <span style="font-size:15px; display:block;line-height:20px;padding:0;margin-bottom:10px;"><?php echo strtoupper($category->name); ?></span>
                <?php echo $article->title; ?>
                <span style="font-size:13px;display:block;line-height:15px;padding:0;margin-top:10px;"><?php echo date('d-M-Y H:i', $article->date); ?></span>
            </h1>
            <?php if ($article->image_type==IMAGE_TYPE_MULTI): ?>
            <?php $this->load->view('frontend/slider/detail_slider', array('images'=>$article->images)); ?>
            <?php elseif ($article->image_url): ?>
            <figure>
                <img class="img-responsive large" src="<?php echo get_image_thumb($article->image_url, IMAGE_THUMB_LARGE); ?>" alt="Article image">
            </figure>
            
            <?php endif; ?>
            <div class="blog-content">
                <div class="info">
                    <?php if ($article->image_caption): ?><figcaption class="info"><?php echo $article->image_caption; ?></figcaption><?php endif; ?>
                </div>
                <blockquote><?php echo $article->synopsis; ?></blockquote>
                <?php echo $article->content; ?>
                
                <?php if ($article->hide_author!=AUTHOR_HIDDEN): ?>
                <p class="text-muted small written-by">
                    <em>--- <?php echo isset($article_author)?$article_author : $article->created_by_name; ?></em>
                </p>
                <?php endif; ?>
                
                <?php if ($article->tags): ?>
                <div class="tag-container">
                    <div class="tag-title">Tags: </div>
                    <?php $tags = explode(',', $article->tags);?>
                    <?php foreach ($tags as $tag): ?>
                    <a class="tag" href="<?php echo site_url('category/tags?q='. urlencode($tag)); ?>"><?php echo $tag; ?></a>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
            
            <div class="blog-bottom">
                <div class="share-title">Share</div>
                <div class="share-content">
                    <a class="btn btn-default" 
                       href="mailto:?Subject=<?php echo urlencode($article->title); ?>&body=<?php echo urlencode($article->synopsis . '. ' . current_url()); ?>" 
                       target="_blank"><span class="glyphicon glyphicon-envelope"></span> Email
                    </a>
                    <a class="btn btn-social btn-twitter" href="javascript:twitterShare('<?php echo urlencode($article->share_url); ?>','<?php echo urlencode($article->title); ?>');"><span class="fa fa-twitter"></span> Twitter</a>
                    <a id="btn-google" class="btn btn-social btn-google-plus" 
                       href="https://plus.google.com/share?url=<?php echo current_url(); ?>&hl=id" 
                       onclick="javascript:window.open(this.href,
  '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600'); return false;">
                        <span class="fa fa-google-plus"></span> Google
                    </a>
                    <a class="btn btn-social btn-facebook" href="javascript:facebookShare(<?php echo $article->id; ?>,'<?php echo urlencode(current_url()); ?>');"><span class="fa fa-facebook"></span> Facebook</a>
                    
                </div>
            </div>
        </article>
    </div>
</div>

<!-- related news -->
<?php if ($related_news): ?>
<div class="row">
    <div class="related-news">
        <div class="inner-box">
            <h1 class="title">Related News</h1>
            <?php foreach ($related_news as $related): ?>
            <div class="column">
                <div class="inner">
                    <a href="<?php echo $related->url_short ? $related->url_short : site_url('detail/'.$related->url_title); ?>">
                        <figure style="height: 105px; overflow: hidden;">
                            <img class="small" src="<?php echo get_image_thumb($related->image_url, IMAGE_THUMB_SMALL); ?>" alt="">
                        </figure>
                        <div class="title"><?php echo $related->title; ?></div>
                        <div class="date"><?php echo date('D d M Y', $related->date); ?></div>
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php endif; ?>
<?php if ($article->allow_comment==1): ?>
<div class="row">
    <div class="comment-container">
        <div class="inner-box">
            <h1 class="title">Komentar</h1>
            <form id="form-comment">
                <input type="hidden" id="article" name="article" value="<?php echo $article->id; ?>" />
                <input type="hidden" id="is_logged_in" name="is_logged_in" value="<?php echo $is_logged_in; ?>" />
                <input type="hidden" id="is_admin" name="is_admin" value="<?php echo $is_admin; ?>" />
                <input type="hidden" id="sender" name="sender" value="<?php echo isset($me)?$me->id:NULL; ?>" />
                <input type="hidden" id="allow_comment" name="allow_comment" value="<?php echo $article->allow_comment; ?>" />
                
                <div class="column">
                    <div class="inner">
                        <div class="form-group">
                            <textarea class="form-control" id="input-comment" name="comment" maxlength="254" placeholder="Tulis komentar"></textarea>
                        </div>
                        <div class="form-group">
                            <button type="submit" id="btn-submit-comment" class="btn btn-default" loading-text="Saving...">Simpan</button>
                        </div>
                    </div>
                </div>
            </form>
            <div class="column">
                <div class="inner">
                    <ul id="comment-list"><!-- load by ajax --></ul>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
<script type="text/javascript">
    $(document).ready (function (){
        ArticleDetailManager.setAllowComment($('#allow_comment').val()==1 ? true:false);
        ArticleDetailManager.setArticleId($('#article').val());
        ArticleDetailManager.setLoggedIn($('#is_logged_in').val() ? true:false);
        ArticleDetailManager.setAdmin($('#is_admin').val() ? true:false);
        ArticleDetailManager.init();
        
//        $('#input-comment').on('focus', function(){
//            if (ArticleDetailManager.isLoggedIn == false){
//                alert('Anda harus login untuk menulis komentar');
//            }
//        });
        $('form#form-comment').on('submit', function (e){
            e.preventDefault();
            if ($('#input-comment').val()){
                ArticleDetailManager.saveComment($(this).serialize());
            }
        });
    });
    var ArticleDetailManager = {
        isLoggedIn: false,
        isAdmin: false,
        allowComment: false,
        articleID: 0,
        setAllowComment: function (bool){
            this.allowComment = bool;
        },
        setArticleId: function (id){
            this.articleID = parseInt(id);
        },
        setLoggedIn: function (bool){
            this.isLoggedIn = bool;
        },
        setAdmin: function (bool){
            this.isAdmin = bool;
        },
        init: function (){
            if (this.allowComment){
                this.loadComments();
            }else{
                console.log('Comment is not allowed');
            }
            
            //this.loadGoogleShort();
        },
        loadGoogleShort: function (){
            var google_url = $('#url_short').val();
            $.get(google_url);
        },
        loadComments: function (){
            var _this = this;
            $.getJSON("<?php echo site_url('service/comment/index'); ?>",{article:_this.articleID,approve:true},function(data){
                for (var i in data){
                    var s = '<li id="'+data[i].id+'">';
                        s+= '<h4 class="name">'+data[i].name+'</h4>';
                        s+= '<p class="date">'+data[i].date+'</p>';
                        s+= '<p class="text">'+data[i].comment+'</p>';
                        if (_this.isAdmin){
                            s+= '<p><a class="btn btn-warning pull-right" href="javascript:ArticleDetailManager.deleteComment('+data[i].id+');">Delete</a></p>';
                        }
                    s+= '</li>';
                    
                    $('#comment-list').append(s);
                }
            });
        },
        saveComment: function (serialized){
            var _this = this;
            if (_this.allowComment && this.isLoggedIn){
                $('#btn-submit-comment').button('loading');
                $.post("<?php echo site_url('service/comment/index'); ?>",serialized,function(data){
                    $('#btn-submit-comment').button('reset');
                    if (data.status==true){
                        var s = '<li id="'+data.item.id+'">';
                            s+= '<h4 class="name">'+data.item.name+'</h4>';
                            s+= '<p class="date">'+data.item.date+'</p>';
                            s+= '<p class="text">'+data.item.comment+'</p>';
                            
                            if (_this.isAdmin){
                                s+= '<p><a class="btn btn-warning pull-right" href="javascript:ArticleDetailManager.deleteComment('+data.item.id+');">Delete</a></p>';
                            }
                        s+= '</li>';

                        $('#comment-list').prepend(s);

                        //clear form
                        $('form#form-comment')[0].reset();
                    }
                });
            }else if (!this.isLoggedIn){
                alert('Maaf, komentar tidak diijinkan. Silahkan login terlebih dahulu.');
                $('#login-dialog').modal('show');
            }else{
                alert('Maaf, komentar tidak diijinkan untuk artikel ini.');
            }
        },
        deleteComment: function(id){
            if (this.isAdmin){
                $.ajax({
                    url: '<?php echo site_url('service/comment/index'); ?>/'+id,
                    type: 'DELETE',
                    success: function(result) {
                        if (result.status==true){
                            $('li#'+id).remove();
                        }
                    }
                });
            }
        }
    };
</script>
