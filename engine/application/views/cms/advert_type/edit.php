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
                        <label>Advert type</label>
                        <input type="text" id="name" name="name" class="form-control" placeholder="Type name" value="<?php echo $item->name; ?>">
                    </div>
                    <div class="form-group">
                        <label>Type description</label>
                        <textarea id="description" name="description" class="form-control" placeholder="Type description"><?php echo $item->description; ?></textarea>
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
</script>