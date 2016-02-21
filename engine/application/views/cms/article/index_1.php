<div class="row">
    <div class="col-sm-12">
        <?php if ($this->session->flashdata('message')): ?>
        <?php echo create_alert_box($this->session->flashdata('message'),$this->session->flashdata('message_type')); ?>
        <?php endif; ?>
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">List of Articles</h3>
                <div class="box-tools">
                    <div class="input-group">
                        <input type="text" name="table_search" class="form-control input-sm pull-right" style="width: 250px;" placeholder="Search">
                        <div class="input-group-btn">
                            <button type="submit" class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
                            <a class="btn btn-sm btn-primary" data-toggle="tooltip" title="Create" href="<?php echo site_url('cms/article/edit'); ?>"><i class="fa fa-plus-square"></i></a>
                        </div>
                    </div>
                </div>
            </div><!-- /.box-header -->
            
            <div class="box-body table-responsive no-padding">
                <table class="table table-bordered">
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
                        <?php $i=1; foreach($items as $item): ?>
                        <tr>
                            <td><?php echo ($offset+$i++); ?>.</td>
                            <td><?php echo $item->title; ?></td>
                            <td><?php echo $item->category; ?></td>
                            <td class="text-center">
                                <?php if ($item->highlight): ?>
                                <i class="fa fa-check-circle text-primary" data-toggle="tooltip" title="Highlight"></i>
                                <?php else: ?>
                                <i class="fa fa-check-circle-o text-gray" data-toggle="tooltip" title="Highlight"></i>
                                <?php endif; ?>
                                <?php if ($item->slider): ?>
                                <i class="fa fa-check-circle text-primary" data-toggle="tooltip" title="Slider news"></i>
                                <?php else: ?>
                                <i class="fa fa-check-circle-o text-gray" data-toggle="tooltip" title="Slider news"></i>
                                <?php endif; ?>
                            </td>
                            <td class="text-center"><?php echo date('d-m-Y H:i', $item->date); ?></td>
                            <td class="text-right"><?php echo number_format($item->view_count); ?></td>
                            <td class="text-center">
                                <?php if ($item->published==1): ?>
                                <i class="fa fa-check-circle text-primary"></i>
                                <?php else:?>
                                <i class="fa fa-check-circle-o text-gray"></i>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <a class="btn btn-xs btn-warning" data-toggle="tooltip" title="Edit" href="<?php echo site_url('cms/article/edit?id='.$item->id.'&page='.$page); ?>"><i class="fa fa-pencil-square"></i></a>
                                <a class="btn btn-xs btn-danger confirmation" data-toggle="tooltip" title="Delete" data-confirmation="Are your sure to delete this record ?" href="<?php echo site_url('cms/article/delete?id='.$item->id.'&page='.$page); ?>"><i class="fa fa-minus-square"></i></a>
                                <a class="btn btn-xs btn-primary" data-toggle="tooltip" title="Facebook statistik" href="javascript:facebookStats('<?php echo site_url('detail/'.$item->url_title); ?>');"><i class="fa fa-facebook"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div><!-- /.box-body -->
            <div class="box-footer clearfix">
                <!-- paging description -->
                <?php echo isset($pagination_description)? $pagination_description:''; ?>
                <!-- paging list bullets -->
                <?php echo isset($pagination)? $pagination:''; ?>
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

<!-- load script to handle facebook request to conduct crawling -->
<script src="<?php echo site_url(config_item('path_assets').'js/socmed.js'); ?>"></script>
<script type="text/javascript">
    
    function facebookStats(url){
        $('#MyModal .modal-title').html('Facebook Crawler Result');
        $('#MyModal .modal-body').html('<div class="myloader"><div class="big"></div></div>');
        $('#MyModal').modal('show');
        
        //get FB stats for this url
        SocialMedia.fbShareCount(url, function (response){
            if (response && !response.error){
                $('#MyModal .modal-body').html('<div class="fb-stats">'+SocialMedia.JSONFormatter(response)+'</div>');
                $('#MyModal .modal-body .fb-stats').append('<p><button type="button" class="btn btn-primary" onclick="facebookCrawler(\''+url+'\')">Force FB Crawler</button></p><div class="fb-crawler"></div>');
            }
        });
    }
    
    function facebookCrawler(url){
        $('#MyModal .modal-body .fb-crawler').html('<div class="myloader"><div class="big"></div></div>');
        
        SocialMedia.fbCrawler(url, function (response){
            if (response && !response.error){
                $('#MyModal .modal-body .fb-crawler').html('<div class="well">'+SocialMedia.JSONFormatter(response)+'</div>');
            }else{
                $('#MyModal .modal-body .fb-crawler').html('<p>Error while executing Facebook crawler</p>');
            }
        });
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
</script>