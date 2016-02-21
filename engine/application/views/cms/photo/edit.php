<style type="text/css">
    #photo-container {min-height: 200px;width: 100%;border:solid 1px #C7C7C7; cursor: pointer;}
</style>
<div class="row">
    <div class="col-sm-12">
        <?php if ($this->session->flashdata('message')): ?>
        <?php echo create_alert_box($this->session->flashdata('message'),$this->session->flashdata('message_type')); ?>
        <?php endif; ?>
        
        <form role="form" method="post" action="<?php echo $submit_url; ?>">
            <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?php echo $item->id?'Update Data':'Create New'; ?></h3>
            </div><!-- /.box-header -->
            
            <div class="box-body">
                <div class="form-group">
                    <label>Clik in the box to load a photo</label>
                    <input type="hidden" id="image_url" name="image_url" value="<?php echo $item->image_url; ?>">
                    <a href="<?php echo site_url(config_item('path_lib').'filemanager/dialog.php?type=1&field_id=image_url&relative_url=1&iframe=true&width=90%&height=90%'); ?>"  rel="prettyPhoto">
                        <div id="photo-container" class="text-center">
                            <?php if ($item->image_url):?>
                            <img src="<?php echo get_image_thumb($item->image_url, IMAGE_THUMB_LARGE); ?>" alt="Select Photo">
                            <?php endif; ?>
                        </div>
                    </a>
                </div>
                <div class="form-group">
                    <label>Photo Title (max. 254 chars)</label>
                    <input type="text" name="title" maxlength="254" class="form-control" placeholder="Title ..." value="<?php echo $item->title; ?>">
                </div>
                <div class="form-group">
                    <label>Photo Description  (max. 300 chars)</label>
                    <textarea name="description" maxlength="300" class="form-control" placeholder="Description ..."><?php echo $item->description; ?></textarea>
                </div>
                <div class="form-group">
                    <label>Article Date (dd-mm-yyyy)</label>
                    <div class="input-group">
                        <input type="text" name="date" class="form-control datepicker"value="<?php echo date('d-m-Y', $item->date?$item->date:time()); ?>">
                        <div class="input-group-btn">
                            <button type="button" class="btn btn-default btn-calender"><span class="glyphicon glyphicon-calendar"></span></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-footer clearfix">
                <button class="btn btn-primary" type="submit"><i class="fa fa-save"></i> Submit</button>
                <button class="btn btn-warning" type="reset"><i class="fa fa-refresh"></i> Reset</button>
                <a class="btn btn-default" href="<?php echo $back_url; ?>"><i class="fa fa-backward"></i> Cancel</a>
            </div>
        </div>
        </form>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $('.btn-calender').on('click', function(){
            $(this).parents('.input-group').find('input.datepicker').focus();
        });
    });
    
    function responsive_filemanager_callback(field_id){
        var images = [];
        var base_large = "<?php echo get_image_base(IMAGE_THUMB_LARGE); ?>";
        
        //$.prettyPhoto.close();
        var image_name = document.getElementById(field_id).value;
        var full_image_url = base_large + image_name;
        
        if ($('#photo-container').find('img').length>0){
            $('#photo-container').find('img').attr('src', full_image_url);
        }else{
            $('#photo-container').html('<img src="'+full_image_url+'" alt="Click me">');
        }
    }
</script>