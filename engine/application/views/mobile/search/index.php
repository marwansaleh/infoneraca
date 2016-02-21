<input type="hidden" id="limit" value="<?php echo $limit; ?>">
<div class="main">
    <ul id="news-list" class="media-list">
    </ul>
    <div id="lastPostsLoader"></div>
</div>

<script type="text/javascript">
    var News = {
        searchInput: '',
        dataLimit: 15,
        page: 1,
        inProccess: false,
        reachLimit: false,
        setSearchInput: function(text){
            this.searchInput = text;
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
            if (_this.searchInput == ''){
                _this.clearNewsList();
            }
            if (_this.reachLimit || _this.inProccess){
                return;
            }
            _this.inProccess = true;
            $('div#lastPostsLoader').html('<center>Loading news...</center>');
            
            //load from service
            $.post("<?php echo site_url('service/article/search'); ?>",{limit:_this.dataLimit,page:_this.page,search:_this.searchInput}, function(data){
                _this.inProccess = false;
                if (data.length > 0){
                    for (var i in data){
                        var s = '<li data-id="'+data[i].id+'" class="media" data-href="'+data[i].link_href+'">';
                        s+= '<div class="media-left">';
                            s+= '<a href="'+data[i].link_href+'">';
                                s+= '<img class="media-object" src="'+data[i].image_url.square+'" alt="'+data[i].title+'">' ;
                            s+= '</a>';
                        s+= '</div>';
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
                
            },'json');
        },
        clearNewsList: function(){
            $('#news-list').empty();
        }
    };
    
    
    $(document).ready(function(){
        News.setSearchInput($('#search_input').val());
        News.setDataLimit($('#limit').val());
        News.init();
        //lastAddedLiveFunc();
        $(window).scroll(function(){

            var wintop = $(window).scrollTop(), docheight = $(document).height(), winheight = $(window).height();
            var  scrolltrigger = 0.95;

            //if (docheight < winheight){return;}
            if  ((wintop/(docheight-winheight)) > scrolltrigger) {
                //console.log('scroll bottom');
                News.loadNews();
            }

            //console.log('wintop:'+wintop+', docheight:'+docheight+', winheight:'+winheight);
        });
    });
    
</script>