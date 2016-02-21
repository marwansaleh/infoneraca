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
                        <label>Caption</label>
                        <input type="text" id="caption" name="caption" class="form-control" placeholder="Static caption" value="<?php echo $item->caption; ?>">
                    </div>
                    <div class="form-group">
                        <label>Static name</label>
                        <input type="text" id="name" name="name" class="form-control" placeholder="Static name" value="<?php echo $item->name; ?>">
                    </div>
                    <div class="form-group">
                        <label>Page title</label>
                        <input type="text" id="title" name="title" class="form-control" placeholder="Title" value="<?php echo $item->title; ?>">
                    </div>
                    <div class="form-group">
                        <label>Page content</label>
                        <textarea name="content" class="form-control texteditor" placeholder="Write content here..."><?php echo $item->content; ?></textarea>
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
<script src="<?php echo site_url(config_item('path_lib').'tinymce/tinymce.min.js'); ?>"></script>
<script type="text/javascript">
    $(document).ready( function () {
        $('input#caption').on('blur', function(){
            if ($('input#name').val()==''){
                SPManager.createNameFromCaption('caption', 'name');
            }
        });
    });
    var SPManager = {
        init: function (){
            tinymce.init({
                selector: "textarea.texteditor",
                theme: 'modern',
                //width: '100%',
                height: '220',
                plugins : [
                    "advlist autolink lists link image charmap print preview anchor",
                    "searchreplace visualblocks code fullscreen",
                    "insertdatetime media table contextmenu paste responsivefilemanager"
                ],
                toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect | responsivefilemanager | link unlink anchor | image media | forecolor backcolor  | print preview code ",
                //toolbar2: "",
                image_advtab: true,
                external_filemanager_path:"/<?php echo config_item('path_lib').'filemanager'; ?>/",
                filemanager_title:"Filemanager" ,
                external_plugins: { "filemanager" : "<?php echo site_url(config_item('path_lib').'filemanager/plugin.min.js'); ?>"},
                filemanager_access_key:"",
                relative_urls: false,
                //document_base_url: "<?php echo site_url(); ?>"
            });
        },
        createNameFromCaption: function (sourceField,targetField) {
            var url = $.trim($('#'+sourceField).val());
            url = url.replace('%','-persen');
            //replace everything not alpha numeric
            url = url.replace(/[^a-z0-9]/gi, '-').toLowerCase();
            //url = url.replace(/[ \t\r]+/g,"-");
            $('#'+targetField).val(url);
        }
    };
    
    SPManager.init();
</script>