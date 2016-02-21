<style type="text/css">
    .large-box {height: 300px; overflow: hidden; outline: none;}
    .medium-box {height: 200px; overflow: hidden; outline:none;}
</style>
<div class="row">
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-aqua"><i class="ion ion-ios-people"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Visitors</span>
                <span class="info-box-number"><?php echo number_format($visitor_count); ?><small> persons</small></span>
            </div><!-- /.info-box-content -->
        </div><!-- /.info-box -->
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-red"><i class="ion ion-ios-chatboxes-outline"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Comments</span>
                <span class="info-box-number"><?php echo number_format($comment_count); ?></span>
            </div><!-- /.info-box-content -->
        </div><!-- /.info-box -->
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-green"><i class="ion ion-ios-cart-outline"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Articles</span>
                <span class="info-box-number"><?php echo number_format($article_count); ?></span>
            </div><!-- /.info-box-content -->
        </div><!-- /.info-box -->
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-yellow"><i class="ion ion-ios-people-outline"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">New Members</span>
                <span class="info-box-number"><?php echo number_format($member_count); ?></span>
            </div><!-- /.info-box-content -->
        </div><!-- /.info-box -->
    </div>
    
    <div class="col-sm-8">
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title"><i class="fa fa-file"></i> Latest Articles</h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body">
                <div class="large-box nicescroll">
                    <ul class="media-list">
                        <?php foreach ($last_articles as $la): ?>
                        <li class="media">
                            <?php if ($la->image_url): ?>
                            <div class="media-left">
                                <a href="<?php echo site_url('cms/article/edit?id='.$la->id); ?>">
                                    <img class="media-object" src="<?php echo get_image_thumb($la->image_url, IMAGE_THUMB_SMALLER) ?>" />
                                </a>
                            </div>
                            <?php endif; ?>
                            <div class="media-body">
                                <h4 class="media-heading"><a href="<?php echo site_url('cms/article/edit?id='.$la->id); ?>"><?php echo $la->title; ?></a></h4>
                                <p><?php echo $la->synopsis; ?>  <i class="fa fa-user"></i> <span class="text-maroon"><?php echo $la->author;?></span> <i class="fa fa-clock-o"></i> <span class="text-maroon"><?php echo date('d M Y H:i', $la->modified); ?></span></p>
                            </div>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="box box-danger">
            <div class="box-header">
                <h3 class="box-title"><i class="fa fa-users"></i> User Online</h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body">
                <div class="large-box nicescroll">
                    <table class="table table-striped">
                        <tbody>
                            <?php foreach ($user_onlines as $ol): ?>
                            <tr>
                                <td><a data-toggle="tooltip" data-placement="left" title="Look profile" href="<?php echo site_url('cms/profile/index?id='.$ol->id) ;?>"><?php echo $ol->full_name; ?></a></td>
                                <td>
                                    <td class="text-right">
                                        <?php if ($ol->is_online): ?>
                                        <i class="ion ion-ios-person" data-toggle="tooltip" title="Online"></i>
                                        <?php else:?>
                                        <i class="ion ion-ios-person-outline" data-toggle="tooltip" title="Offline"></i>
                                        <?php endif; ?>
                                    </td>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        
    });
</script>