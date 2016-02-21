<div class="row">
    <div class="col-sm-12">
        <?php if ($this->session->flashdata('message')): ?>
        <?php echo create_alert_box($this->session->flashdata('message'),$this->session->flashdata('message_type')); ?>
        <?php endif; ?>
        <div class="box">
            <div class="box-header">
                <div class="row">
                    <div class="col-sm-3">
                        <h3 class="box-title">List of Comments</h3>
                    </div>
                    <div class="col-sm-9">
                        <div class="row">
                            <div class="col-sm-4 col-sm-offset-5">
                                <div class="form-group form-group-sm">
                                    <select id="article" class="form-control selectpicker" data-live-search="true" data-size="8" style="min-width: 150px;">
                                        <option value="0">-- All articles--</option>
                                        <?php foreach ($articles as $article): ?>
                                        <option value="<?php echo $article->id; ?>" <?php echo $article->id==$selected_article_id?'selected':''; ?>><?php echo $article->title; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group form-group-sm">
                                    <div class="input-group">
                                        <input type="number" class="form-control" step="1" min="2" id="limit" value="15" title="Data limit" />
                                        <div class="input-group-btn">
                                            <button type="button" class="btn btn-primary btn-sm" id="btn-filter"><i class="fa fa-filter"></i> Filter</button>
                                        </div>
                                    </div>
                                </div>
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
                            <th>Comment</th>
                            <th>Sender</th>
                            <th>Date</th>
                            <th>Article</th>
                            <th>Display</th>
                            <th style="width: 120px">Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div><!-- /.box-body -->
            <div class="box-footer clearfix">
                <div id="pagination"></div>
            </div>
        </div>
    </div>
</div>
<input type="hidden" id="selected-article" value="<?php echo isset($selected_article) ? $selected_article : 0; ?>" />

<script type="text/javascript">
    $(document).ready(function(){
        CommentManager.setDataLimit($('#limit').val());
        CommentManager.setArticle($('#article').val());
        CommentManager.init();
        
        $('#data-list').on('click', '.btn-set-approval', function(){
            var id = $(this).attr('data-id');
            var approval = $(this).attr('data-approval');
            CommentManager.setApproval(id, (approval=='true' ? 0 : 1));
        });
        
        $('#btn-filter').on('click', function (){
            CommentManager.setDataLimit($('#limit').val());
            CommentManager.setArticle($('#article').val());
            CommentManager.loadComments();
        });
    });
    var CommentManager = {
        page: 1,
        dataLimit: 12,
        reachLimit: false,
        articleId: 0,
        init: function(){
            this.loadComments();
        },
        setDataLimit: function (limit){
            this.dataLimit = parseInt(limit);
        },
        setArticle: function (id){
            this.articleId = parseInt(id);
        },
        prev: function(){
            if (this.page > 1){
                this.page = this.page - 1;
            }
            //console.log('Current page:'+this.page);
        },
        next: function(){
            if (!this.reachLimit){
                this.page = this.page + 1;
            }
            //console.log('Current page:'+this.page);
        },
        loadComments: function(){
            var _this= this;
            //console.log('Current page:'+_this.page);
            
            $('#data-list tbody').empty();
            $.getJSON('<?php echo site_url('service/comment/index'); ?>',{limit:_this.dataLimit,page:_this.page,article:_this.articleId},function(result){
                if (result.length > 0){
                    for (var i in result){
                        var s = '<tr id="'+result[i].id+'">';
                        s+= '<td>'+_this._getRecNumber(i)+'.</td>';
                        s+= '<td>'+result[i].comment+'</td>';
                        s+= '<td>'+result[i].name+'</td>';
                        s+= '<td>'+result[i].date+'</td>';
                        s+= '<td>'+result[i].title+'</td>';
                        s+= '<td class="text-center"><a data-id="'+result[i].id+'" data-approval="'+result[i].approved+'" class="btn btn-set-approval"><i class="fa icon-approval '+(result[i].approved ? 'fa-check-circle text-primary':'fa-check-circle-o text-gray')+'" data-toggle="tooltip" title="" data-original-title="Display in web view"></i></a></td>';
                        s+= '<td class="text-center"><a class="btn btn-xs btn-danger confirmation" data-toggle="tooltip" title="Delete" data-confirmation="Are your sure to delete this record ?" href="javascript:deleteComment('+result[i].id+')"><i class="fa fa-minus-square"></i></a></td>';
                        s+= '</tr>';

                        $('#data-list tbody').append(s);
                    }
                    if (result.length < _this.dataLimit){
                        _this.reachLimit = true;
                        console.log('Reach limit');
                    }else{
                        _this.reachLimit = false;
                    }
                    
                }else{
                    _this.reachLimit = true;
                    //console.log('Reach limit');
                }
                
                _this._drawingPaging();
            });
        },
        deleteComment: function (id){
            var _this = this;
            $.ajax({
                url: '<?php echo site_url('service/comment/index'); ?>/'+id,
                type: 'DELETE',
                success: function (result){
                    if (result.status){
                        _this.loadComments();
                    }else{
                        alert(result.message);
                    }
                },
                contentType: 'json'
            });
        },
        setApproval: function (id, approval){
            var _this = this;
            
            $.ajax({
                url: '<?php echo site_url('service/comment/approve'); ?>/'+id,
                type: 'PUT',
                contentType: 'json',
                data: {"approval": approval},
                success: function (result){
                    var $btn = $('tr#'+id).find('a');
                    $btn.attr('data-approval', result.approved);
                    
                    var $icon = $('tr#'+id).find('.icon-approval');
                    if (result.approved){
                        $icon.removeClass('fa-check-circle-o');
                        $icon.removeClass('text-gray');
                        $icon.addClass('fa-check-circle');
                        $icon.addClass('text-primary');
                    }else{
                        $icon.removeClass('fa-check-circle');
                        $icon.removeClass('text-primary');
                        $icon.addClass('fa-check-circle-o');
                        $icon.addClass('text-gray');
                    }
                }
            });
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
        }
    };
    
    function previousPage(){
        CommentManager.prev();
        CommentManager.loadComments();
    }
    
    function nextPage(){
        CommentManager.next();
        CommentManager.loadComments();
    }
    
    function deleteComment(id){
        if (confirm('Delete this line of record ?')){
            CommentManager.deleteComment(id);
        }
    }
</script>