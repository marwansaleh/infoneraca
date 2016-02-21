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
                        <label>Category</label>
                        <select name="category_id" class="form-control selectpicker" data-live-search="true" data-size="5">
                            <?php foreach ($categories as $category): ?>
                            <option value="<?php echo $category->id; ?>" <?php echo $category->id==$item->category_id?'selected':''; ?>><?php echo $category->name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Attribute name</label>
                        <input type="text" id="attr_name" name="attr_name" class="form-control" placeholder="Attribute name" value="<?php echo $item->attr_name; ?>">
                    </div>
                    <div class="form-group">
                        <label>Attribute label</label>
                        <input type="text" id="attr_label" name="attr_label" class="form-control" placeholder="Attribute label" value="<?php echo $item->attr_label; ?>">
                    </div>
                    <div class="form-group">
                        <label>Attribute type</label>
                        <select name="attr_type" class="form-control selectpicker" data-live-search="true" data-size="5">
                            <?php foreach ($attr_types as $type): ?>
                            <option value="<?php echo $type; ?>" <?php echo $type==$item->attr_type?'selected':''; ?>><?php echo ucfirst($type); ?></option>
                            <?php endforeach; ?>
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

<script type="text/javascript">
    $(document).ready(function (){
        $('input#attr_name').on('blur', function (){
            create_url_title('attr_name', 'attr_name');
        });
    });
    
    function create_url_title(sourceField,targetField){
        var url = $.trim($('#'+sourceField).val());
        url = url.replace('%','-persen');
        //replace everything not alpha numeric
        url = url.replace(/[^a-z0-9]/gi, '-').toLowerCase();
        //url = url.replace(/[ \t\r]+/g,"-");
        $('#'+targetField).val(url);
    }
</script>