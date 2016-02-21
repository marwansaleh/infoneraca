<div class="row">
    <div class="blog-page">
        <article>
            <h1 class="title"><?php echo $staticpage->title; ?></h1>
            <div class="blog-content">
                <?php echo $staticpage->content; ?>
            </div>
            
            <div class="blog-bottom">
                <div class="share-title">Share</div>
                <div class="share-content">
                    <a class="btn btn-default" href="mailto:redaksi@indonesiasatu.co?Subject=<?php echo urlencode(current_url()); ?>" target="_blank"><span class="glyphicon glyphicon-envelope"></span> Email</a>
                    <a class="btn btn-social btn-twitter" href="javascript:share_tw('<?php echo urlencode(current_url()); ?>','<?php echo urlencode($staticpage->title); ?>');"><span class="fa fa-twitter"></span> Twitter</a>
                    <a id="btn-google" class="btn btn-social btn-google-plus" 
                       href="https://plus.google.com/share?url=<?php echo current_url(); ?>&hl=id" 
                       onclick="javascript:window.open(this.href,
  '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600'); return false;">
                        <span class="fa fa-google-plus"></span> Google
                    </a>
                    <a class="btn btn-social btn-facebook" href="javascript:share_fb('<?php echo urlencode(current_url()); ?>');"><span class="fa fa-facebook"></span> Facebook</a>
                    
                </div>
            </div>
        </article>
    </div>
</div>

<script type="text/javascript">
    window.fbAsyncInit = function() {
        FB.init({
          appId      : '<?php echo $FB_ID; ?>',
          xfbml      : true,
          version    : 'v2.5'
        });
    };
    (function(d, s, id) {
      var js, fjs = d.getElementsByTagName(s)[0];
      if (d.getElementById(id)) return;
      js = d.createElement(s); js.id = id;
      js.src = "//connect.facebook.net/id_ID/sdk.js#xfbml=1&version=v2.5";
      fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));

    function share_fb(link){
        
        FB.ui({
          method: 'share',
          href: link
        }, function(response){}); 
        
        return false;
    }
    function share_tw(encoded_url,text){
        var tw_window = window.open('https://twitter.com/intent/tweet?url='+encoded_url+'&text='+text,'Twitter-Web-Intent');
        tw_window.focus();
    }
</script>
