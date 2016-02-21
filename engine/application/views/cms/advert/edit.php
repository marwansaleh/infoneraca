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
                        <label>Advert name</label>
                        <input type="text" id="name" name="name" class="form-control" placeholder="Advert name" value="<?php echo $item->name; ?>">
                    </div>
                    <div class="form-group">
                        <label>Advert type</label>
                        <select name="type" class="form-control selectpicker" data-live-search="true" data-size="5">
                            <?php foreach ($advert_types as $type): ?>
                            <option value="<?php echo $type->id; ?>" <?php echo $type->id==$item->type?'selected':''; ?>><?php echo $type->name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Start date (dd-mm-yyyy)</label>
                                <div class="input-group">
                                    <input type="text" name="start_date" class="form-control datepicker"value="<?php echo date('d-m-Y', $item->start_date?$item->start_date:time()); ?>">
                                    <div class="input-group-btn">
                                        <button type="button" class="btn btn-default btn-calender"><span class="glyphicon glyphicon-calendar"></span></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>End date (dd-mm-yyyy)</label>
                                <div class="input-group">
                                    <input type="text" name="end_date" class="form-control datepicker"value="<?php echo date('d-m-Y', $item->end_date?$item->end_date:time()); ?>">
                                    <div class="input-group-btn">
                                        <button type="button" class="btn btn-default btn-calender"><span class="glyphicon glyphicon-calendar"></span></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Upload file</label>
                        <input type="file" id="file" name="file" class="form-control file">
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
<script type="text/javascript" src="<?php echo site_url(config_item('path_lib').'bootstrap-fileinput/js/fileinput.min.js'); ?>"
<script type="text/javascript">
    $(document).ready( function () {
        $('.btn-calender').on('click', function(){
            $(this).parents('.input-group').find('input.datepicker').focus();
        });
        
        $('input#file').fileinput({
            showCaption: false,
            allowedFileExtensions: ["jpg", "png", "gif", "mp4"]
        })
        .on('fileuploaded', function(event, data, previewId, index) {
            var form = data.form, files = data.files, extra = data.extra, 
            response = data.response, reader = data.reader;
            console.log('File uploaded triggered');
        })
        .on('filebrowse', function (){
            alert('Huh..it browsed');
        })
    });
    var AdvertManager = {
        
    };
</script>