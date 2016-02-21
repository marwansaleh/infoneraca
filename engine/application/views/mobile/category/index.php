<input type="hidden" id="category" value="<?php echo $category->id; ?>">
<input type="hidden" id="limit" value="<?php echo $limit; ?>">
<div class="main">
    <h3 style="margin: 5px 0 0 10px;"><?php echo $category->name; ?></h3>
    <ul id="news-list" class="media-list">
    </ul>
    <div id="lastPostsLoader"></div>
</div>

<script type="text/javascript">
    var News = {
        categoryId: 0,
        dataLimit: 15,
        page: 1,
        inProccess: false,
        reachLimit: false,
        setCategory: function(category){
            this.categoryId = parseInt(category);
        },
        setDataLimit: function (limit){
            this.dataLimit = parseInt(limit);
        },
        init: function(){
            var _this = this;
            //if exists
            _this.loadNews();
        },
        loadNews: function (){
            var _this = this;
            if (_this.reachLimit || _this.inProccess){
                return;
            }
            _this.inProccess = true;
            $('div#lastPostsLoader').html('Loading news...');
            
            //load from service
            $.getJSON("<?php echo site_url('service/article/index'); ?>",{limit:_this.dataLimit,page:_this.page,category:_this.categoryId}, function(data){
                _this.inProccess = false;
                if (data.length > 0){
                    for (var i in data){
                        var s = '<li data-id="'+data[i].id+'" class="media '+(_this.page==1&&i==0?'first-item':'')+'" data-href="'+data[i].link_href+'">';
                        if (_this.page==1 &&i==0){
                            s+= '<a href="'+data[i].link_href+'">';
                                s+= '<img class="media-object img-responsive" src="'+data[i].image_url.large+'" alt="'+data[i].title+'">' ;
                            s+= '</a>';
                        }else{
                            s+= '<div class="media-left">';
                                s+= '<a href="'+data[i].link_href+'">';
                                    s+= '<img class="media-object" src="'+data[i].image_url.square+'" alt="'+data[i].title+'">' ;
                                s+= '</a>';
                            s+= '</div>';
                        }
                        s+= '<div class="media-body">';
                            s+= '<h4 class="media-heading"><a href="'+data[i].link_href+'">'+data[i].title+'</a></h4>';
                            s+= '<p class="date">'+data[i].article_date+'</p>';
                        s+= '</div>';
                        s+= '</li>';

                        $('#news-list').append(s);
                    }
                    if (data.length < _this.dataLimit){
                        _this.reachLimit = true;
                        $('div#lastPostsLoader').empty();
                    }else{
                        _this.page = _this.page+1;
                    }
                }else{
                    _this.reachLimit = true;
                    $('div#lastPostsLoader').empty();
                }
            });
        }
    };
    
    
    $(document).ready(function(){
        News.setDataLimit($('#limit').val());
        News.setCategory($('#category').val());
        News.init();
        //lastAddedLiveFunc();
        $(window).scroll(function(){

            var wintop = $(window).scrollTop(), docheight = $(document).height(), winheight = $(window).height();
            var  scrolltrigger = 0.95;

            if  ((wintop/(docheight-winheight)) > scrolltrigger) {
             //console.log('scroll bottom');
             News.loadNews();
            }
        });
    });
    
</script>