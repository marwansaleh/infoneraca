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
                    <label>Category Title</label>
                    <input type="text" id="name" name="name" class="form-control" placeholder="Title ..." value="<?php echo $item->name; ?>">
                </div>
                <div class="form-group">
                    <label>Category Slug</label>
                    <input type="text" id="slug" name="slug" class="form-control" placeholder="Short category ..." value="<?php echo $item->slug; ?>">
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Category Parent</label>
                            <select name="parent" class="form-control selectpicker" data-live-search="true" data-size="5">
                                <option value="0">-- No Parent--</option>
                                <?php foreach ($parents as $parent): ?>
                                <option value="<?php echo $parent->id; ?>" <?php echo $parent->id==$item->parent?'selected':''; ?>><?php echo $parent->name; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Sort / Order</label>
                            <input type="number" name="sort" class="form-control" min="0" step="1" value="<?php echo $item->sort; ?>">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Is Menu ?</label>
                            <select name="is_menu" class="form-control">
                                <option value="0" <?php echo $item->is_menu==0?'selected':''; ?>>Not menu</option>
                                <option value="1" <?php echo $item->is_menu==1?'selected':''; ?>>Menu</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Show in Home ?</label>
                            <select name="is_home" class="form-control">
                                <option value="0" <?php echo $item->is_home==0?'selected':''; ?>>Hidden</option>
                                <option value="1" <?php echo $item->is_home==1?'selected':''; ?>>Display</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label>Select Category Image</label>
                            <input type="hidden" id="base_image" value="<?php echo get_image_base(IMAGE_THUMB_ORI); ?>" />
                            <div class="input-group">
                                <input type="text" readonly="true" class="form-control disabled" id="image_url" name="image_url" value="<?php echo $item->image_url; ?>" placeholder="Browse image..">
                                <div class="input-group-btn">
                                    <a href="<?php echo site_url(config_item('path_lib').'filemanager/dialog.php?type=1&fldr=category-images&field_id=image_url&relative_url=1&iframe=true&width=80%&height=80%'); ?>"  rel="prettyPhoto" class="btn btn-default"><i class="fa fa-upload"></i> Browse Image</a>
                                    <button type="button" class="btn btn-danger" id="btn-delete-image">Remove</button>
                                </div>
                            </div>
                        </div>
                        <img id="img-selected" class="img-responsive" <?php echo $item->image_url ? 'src="'.  get_image_thumb($item->image_url, IMAGE_THUMB_ORI).'"' : ''; ?> />
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
    var CategoryManager = {
        selectedImage: null,
        baseImage: null,
        init: function (){
            this.loadImage();
        },
        setSelectedImage: function (imageUrl){
            this.selectedImage = imageUrl;
        },
        setBaseImage: function (basepath){
            this.baseImage = basepath;
        },
        loadImage: function (){
            if (this.selectedImage){
                var imageUrl = this.baseImage + this.selectedImage;
                $('#img-selected').attr('src', imageUrl);
            }
        },
        deleteSelectedImage: function (){
            this.selectedImage = null;
            $('#img-selected').attr('src', '');
            $('#selected_image').val('');
        },
        createSlug: function(source,target){
            var url = $.trim($('#'+source).val());
            url = url.replace('%','-persen');
            //replace everything not alpha numeric
            url = url.replace(/[^a-z0-9]/gi, '-').toLowerCase();
            //url = url.replace(/[ \t\r]+/g,"-");
            $('#'+target).val(url);
        }
    };
    
    CategoryManager.setBaseImage($('#base_image').val());
    CategoryManager.setSelectedImage($('#image_url').val());
    CategoryManager.init();
    
    $(document).ready(function (){
        $('input#name').on('blur', function (){
            if (!$('input#slug').val()){
                CategoryManager.createSlug('name', 'slug');
            }
        });
        $('#btn-delete-image').on('click', function (){
            CategoryManager.deleteSelectedImage();
        });
    });
    
    function responsive_filemanager_callback(field_id){
        CategoryManager.setSelectedImage($('#'+field_id).val());
        CategoryManager.loadImage();
    }
</script>