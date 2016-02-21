<div class="row">
    <div class="col-sm-12">
        <?php if ($this->session->flashdata('message')): ?>
        <?php echo create_alert_box($this->session->flashdata('message'),$this->session->flashdata('message_type')); ?>
        <?php endif; ?>
        <div class="box">
            <div class="box-header">
                <div class="row">
                    <div class="col-sm-3">
                        <h3 class="box-title">List of Articles</h3>
                    </div>
                    <div class="col-sm-9">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group form-group-sm">
                                    <select id="category" class="form-control selectpicker" data-live-search="true" data-size="8" style="min-width: 150px;">
                                        <option value="0">-- All Categories--</option>
                                        <?php foreach ($categories as $category): ?>
                                        <option value="<?php echo $category->id; ?>" <?php echo $category->id==$selected_category_id?'selected':''; ?>><?php echo $category->name; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group form-group-sm">
                                    <div class="input-group">
                                        <input type="number" class="form-control" step="1" min="2" id="limit" value="10" title="Data limit" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-5">
                                <div class="form-group form-group-sm">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="search" placeholder="Search title..." />
                                        <div class="input-group-btn">
                                            <button type="button" class="btn btn-success btn-sm" id="btn-filter"><i class="fa fa-filter"></i> Search</button>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                            <div class="col-sm-1">
                                <a class="btn btn-sm btn-primary" data-toggle="tooltip" title="Create" href="<?php echo site_url('cms/article/edit'); ?>"><i class="fa fa-plus-square"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div><!-- /.box-header -->
            
            <div class="box-body table-responsive no-padding">
                <table class="table table-bordered" id="data-list">
                    <thead>
                        <tr>
                            <th style="width: 10px">#</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th class="text-center" style="width: 60px;">Types</th>
                            <th class="text-center">Date</th>
                            <th style="width: 40px;">View</th>
                            <th style="width: 40px;">Published</th>
                            <th style="width: 90px">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
            </div><!-- /.box-body -->
            <div class="box-footer clearfix">
                <div id="pagination"></div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="MyModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Modal title</h4>
            </div>
            <div class="modal-body">
                <p>One fine body&hellip;</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<input type="hidden" id="site_url" value="<?php echo site_url(); ?>" />
<input type="hidden" id="page" value="<?php echo $page; ?>" />

<!-- load script to handle facebook request to conduct crawling -->
<script src="<?php echo site_url(config_item('path_assets').'js/socmed.js'); ?>"></script>
<script type="text/javascript">
    $(document).ready (function (){
        ArticleManagers.init();
        
        $('#category').on('change', function(){
            ArticleManagers.setCategory($(this).val());
            ArticleManagers.loadArticles();
        });
        
        $('#btn-filter').on('click', function(){
            ArticleManagers.setDataLimit($('#limit').val());
            ArticleManagers.setCategory($('#category').val());
            ArticleManagers.setSearchValue($('#search').val());
            ArticleManagers.setPage(1);
            ArticleManagers.loadArticles();
        });
        
        $('#search').on('keypress', function (event){
            if ( event.which == 13 ) {
                $('#btn-filter').click();
            }
        });
    });
    var ArticleManagers = {
        _articles: [],
        categoryId: 0,
        dataLimit: 10,
        searchText: null,
        page: 1,
        inProccess: false,
        reachLimit: false,
        init: function (){
            var _this = this;
            _this.setDataLimit($('#limit').val());
            _this.setCategory($('#category').val());
            _this.setSearchValue($('#search').val());
            _this.setPage($('#page').val());
            _this.loadArticles();
        },
        setCategory: function(category){
            this.categoryId = parseInt(category);
            this.reachLimit = false;
        },
        setDataLimit: function (limit){
            this.dataLimit = parseInt(limit);
            this.reachLimit = false;
        },
        setSearchValue: function (text){
            this.searchText = text;
            this.reachLimit = false;
        },
        setPage: function (page){
            if (parseInt(page) > 0){
                this.page = parseInt(page);
                this.reachLimit = false;
            }
        },
        prev: function(){
            if (this.page > 1){
                this.setPage(this.page-1);
            }
            //console.log('Current page:'+this.page);
        },
        next: function(){
            if (!this.reachLimit){
                this.setPage(this.page + 1);
            }
            //console.log('Current page:'+this.page);
        },
        loadArticles: function (){
            var _this = this;
            if (_this.reachLimit || _this.inProccess){
                return;
            }
            _this.inProccess = true;
            
            _this._setList('loading');
            //load from service
            $.getJSON("<?php echo site_url('service/article/index'); ?>",{admin:true,limit:_this.dataLimit,page:_this.page,search:_this.searchText,category:_this.categoryId}, function(data){
                _this.inProccess = false;
                _this._clearList();
                
                if (data.length > 0){
                    for (var i in data){
                        var s = '<tr id="'+data[i].id+'" data-id="'+data[i].id+'">';
                            s+= '<td>'+_this._getRecNumber(i)+'.</td>';
                            s+= '<td>'+data[i].title+'</td>';
                            s+= '<td>'+data[i].category+'</td>';
                            s+= '<td class="text-center">';
                                //check for highlight / slider-news
                                s+= '<i class="fa icon-approval '+(data[i].types && data[i].types.indexOf('highlight')>=0 ? 'fa-check-circle text-primary':'fa-check-circle-o text-gray')+'" data-toggle="tooltip" title="" data-original-title="Display in web view"></i>';
                                s+= '<i class="fa icon-approval '+(data[i].types && data[i].types.indexOf('slider-news')>=0 ? 'fa-check-circle text-primary':'fa-check-circle-o text-gray')+'" data-toggle="tooltip" title="" data-original-title="Display in web view"></i>';
                            s+= '</td>';
                            s+= '<td class="text-center">'+data[i].article_date+'</td>';
                            s+= '<td class="text-right">'+data[i].view+'</td>';
                            s+= '<td class="text-center"><i class="fa icon-approval '+(data[i].published ? 'fa-check-circle text-primary':'fa-check-circle-o text-gray')+'" data-toggle="tooltip" title="" data-original-title="Display in web view"></i></td>';
                            s+= '<td class="text-center">';
                                s+= '<a class="btn btn-xs btn-warning" data-toggle="tooltip" title="Edit" href="'+(_this._getUrl('cms/article/edit?id='+data[i].id+'&page='+_this.page))+'"><i class="fa fa-pencil-square"></i></a>';
                                s+= '<a class="btn btn-xs btn-danger confirmation" data-toggle="tooltip" title="Delete" onclick="deleteArticle(\''+_this._getUrl('cms/article/delete?id='+data[i].id+'&page='+_this.page)+'\');"><i class="fa fa-minus-square"></i></a>';
                                s+= '<a class="btn btn-xs btn-primary" data-toggle="tooltip" title="Facebook statistik" href="javascript:facebookStats(\''+_this._getUrl('detail/'+data[i].url_title)+'\');"><i class="fa fa-facebook"></i></a>';
                            s+= '</td>';
                        s+= '</tr>';

                        $('#data-list').append(s);
                    }
                    if (data.length < _this.dataLimit){
                        _this.reachLimit = true;
                        console.log('Reach limit');
                    }else{
                        _this.reachLimit = false;
                    }
                }else{
                    _this.reachLimit = true;
                    console.log('Reach limit');
                }
                
                _this._drawingPaging();
            });
        },
        _setList: function (status){
            if (status == 'loading'){
                this._clearList();
                
                $('#data-list tbody').append('<tr><td colspan="9">Loading data....</td></tr>');
            }else{
                this._clearList();
            }
        },
        _clearList: function (){
            $('#data-list tbody').empty();
        },
        _getRecNumber: function(offset){
            var recNumber = ((this.page-1)*this.dataLimit) + parseInt(offset) + 1;
            
            return recNumber;
        },
        _drawingPaging: function (){
            var s = '<nav><ul class="pager">';
            if (this.page > 1){
                s+= '<li><a href="javascript:previousPage();">Previous</a></li>';
            }else{
                s+= '<li class="disabled"><a href="#">Previous</a></li>';
            }
            if (!this.reachLimit){
                s+= '<li><a href="javascript:nextPage();">Next</a></li>';
            }else{
                s+= '<li class="disabled"><a href="#">Next</a></li>';
            }
            s+= '</ul></nav>';
            
            $('#pagination').html(s);
        },
        _getUrl: function (path){
            var siteUrl = $('#site_url').val();
            if (path){
                siteUrl += path;
            }
            return siteUrl;
        }
    };
    
    function previousPage(){
        ArticleManagers.prev();
        ArticleManagers.loadArticles();
    }
    
    function nextPage(){
        ArticleManagers.next();
        ArticleManagers.loadArticles();
    }
    
    function facebookStats(url){
        console.log('Get Facebook stat for this url: '+url);
        $('#MyModal .modal-title').html('Facebook Crawler Result');
        $('#MyModal .modal-body').html('<div class="myloader"><div class="big"></div></div>');
        $('#MyModal').modal('show');
        
        //get FB stats for this url
        SocialMedia.fbShareCount(url, function (response){
            if (response && !response.error){
                $('#MyModal .modal-body').html('<div class="fb-stats">'+SocialMedia.JSONFormatter(response)+'</div>');
                $('#MyModal .modal-body .fb-stats').append('<p><button type="button" class="btn btn-primary" onclick="facebookCrawler(\''+url+'\')">Force FB Crawler</button></p><div class="fb-crawler"></div>');
            }else{
                $('#MyModal .modal-body').html('<div class="fb-stats">'+SocialMedia.JSONFormatter(response.error)+'</div>');
            }
        });
    }
    
    function facebookCrawler(url){
        console.log('Force facebook to crawl this url: '+url);
        $('#MyModal .modal-body .fb-crawler').html('<div class="myloader"><div class="big"></div></div>');
        
        SocialMedia.fbCrawler(url, function (response){
            if (response && !response.error){
                $('#MyModal .modal-body .fb-crawler').html('<div class="well">'+SocialMedia.JSONFormatter(response)+'</div>');
            }else{
                $('#MyModal .modal-body .fb-crawler').html('<div class="well">'+SocialMedia.JSONFormatter(response.error)+'</div>');
            }
        },'1667512626834805|iTkphW4LI0Ey_PXf0Db-s7BL7mY');
    }
    
    function updateArticleFacebookObjectId(articleId,fbObjId){
        $.ajax({
            url: "<?php echo site_url('service/article/fb_object'); ?>/"+articleId,
            type: 'put',
            data: {fb_object_id:fbObjId},
            dataType: 'json',
            success: function (response){
                //do something here
            }
        });
    }
    
    function deleteArticle(url){
        if (confirm('Are you sure to delete this article ?')){
            window.location.href = url;
        }
    }
</script>