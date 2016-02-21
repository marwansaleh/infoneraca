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
                    <label>Newsticker Text</label>
                    <input type="text" maxlength="254" name="news_text" class="form-control" placeholder="News text ..." value="<?php echo $item->news_text; ?>">
                </div>
                <div class="form-group">
                    <label>Set Active</label>
                    <select class="form-control" name="active">
                        <option value="0" <?php echo $item->active==0?'selected':''; ?>>Not Active</option>
                        <option value="1" <?php echo $item->active==1?'selected':''; ?>>Active</option>
                    </select>
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

