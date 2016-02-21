<style type="text/css">
    .image-container {min-height: 150px;}
    .thumbnail .remove-image {position: absolute; top: 28%; right: 47%; transition: opacity 0.5s ease; opacity: 0.3}
    .thumbnail:hover .remove-image {opacity: 1; }
</style>
<div class="row">
    <div class="col-sm-12">
        <?php if ($this->session->flashdata('message')): ?>
        <?php echo create_alert_box($this->session->flashdata('message'),$this->session->flashdata('message_type')); ?>
        <?php endif; ?>
        
        <form role="form" method="post" action="<?php echo $submit_url; ?>">
            <input type="hidden" id="id" name="id" value="<?php echo $item->id; ?>" />
            <input type="hidden" id="has_ext_attributes" name="has_ext_attributes" value="<?php echo $ext_attributes?count($ext_attributes):0;  ?>" />
            <div class="row">
                <div class="col-sm-8">
                    <div class="box box-default">
                        <div class="box-header">
                            <h3 class="box-title"><?php echo $id?'Update Data':'Create New'; ?></h3>
                            <div class="box-tools pull-right">
                                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                            </div>
                        </div><!-- /.box-header -->

                        <div class="box-body">
                            <div class="form-group">
                                <label>Title</label>
                                <input type="text" id="title" name="title" class="form-control" placeholder="Title ..." value="<?php echo $item->title; ?>">
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>URL Title</label>
                                        <div class="input-group">
                                            <input type="text" id="url_title" name="url_title" class="form-control" placeholder="Url title ..." value="<?php echo $item->url_title; ?>">
                                            <div class="input-group-btn">
                                                <button type="button" class="btn btn-default" id="btn-gen-slug">Generate</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Google Short URL</label>
                                        <input type="text" id="url_short" name="url_short" class="form-control" placeholder="Url short ..." value="<?php echo $item->url_short; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Category</label>
                                        <select id="category_id" name="category_id" class="form-control selectpicker" data-live-search="true" data-size="5">
                                            <option value="0">-- No Category--</option>
                                            <?php foreach ($categories as $category): ?>
                                            <option value="<?php echo $category->id; ?>" <?php echo $category->id==$item->category_id?'selected':''; ?>><?php echo $category->name; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Article Date (dd-mm-yyyy)</label>
                                        <div class="input-group datetimepicker">
                                            <input type="text" name="date" class="form-control" value="<?php echo date('d-m-Y H:i', $item->date?$item->date:time()); ?>">
                                            <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Set Publish ?</label>
                                        <select name="published" class="form-control">
                                            <option value="0" <?php echo $item->published==0?'selected':''; ?>>Not Published</option>
                                            <option value="1" <?php echo $item->published==1?'selected':''; ?>>Published</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div id="attribute_ext_container" class="well well-sm <?php echo $ext_attributes?'':'hidden'; ?>">
                                <div class="row">
                                    <?php if ($ext_attributes): ?>
                                    <?php foreach ($ext_attributes as $ext): ?>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label><?php echo $ext->attr_label ? $ext->attr_label : $ext->attr_name; ?></label>
                                            <input type="<?php echo $ext->attr_type ? $ext->attr_type : 'text' ?>" name="<?php echo $ext->attr_name; ?>" class="form-control" value="<?php echo isset($item->ext_attributes->{$ext->attr_name})?$item->ext_attributes->{$ext->attr_name}:''; ?>">
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Allow Comment ?</label>
                                        <select name="allow_comment" class="form-control">
                                            <option value="0" <?php echo $item->allow_comment==0?'selected':''; ?>>Not allow</option>
                                            <option value="1" <?php echo $item->allow_comment==1?'selected':''; ?>>Allow comment</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-8">
                                    <div class="form-group">
                                        <label>Article Types</label>
                                        <select name="types[]" class="form-control selectpicker" data-live-search="true" data-size="5" multiple="true">
                                            <option value="">--Select feature--</option>
                                            <?php foreach ($article_types as $type): ?>
                                            <option value="<?php echo $type->slug; ?>" <?php echo in_array($type->slug, $item->types)?'selected':''; ?>><?php echo $type->name; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Hide Author Name</label>
                                        <select name="hide_author" class="form-control">
                                            <option value="0" <?php echo $item->hide_author==0?'selected':''; ?>>Show author</option>
                                            <option value="1" <?php echo $item->hide_author==1?'selected':''; ?>>Hide author</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-8">
                                    <div class="form-group">
                                        <label for="tag" class="control-label">Page Tag</label>
                                        <div class="input-group">
                                            <input type="text" autocomplete="off" id="tags" name="tags" class="form-control tagsinput" placeholder="Tags" value="<?php echo $item->tags; ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Synopsis</label>
                                <textarea name="synopsis" class="form-control" placeholder="Short description..."><?php echo $item->synopsis; ?></textarea>
                            </div>
                        </div>
                        <div class="box-footer clearfix">
                            <button class="btn btn-primary" type="submit"><i class="fa fa-save"></i> Submit</button>
                            <button class="btn btn-warning" type="reset"><i class="fa fa-refresh"></i> Reset</button>
                            <a class="btn btn-default" href="<?php echo $back_url; ?>"><i class="fa fa-backward"></i> Cancel</a>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="box box-info" style="margin-bottom:5px;">
                        <div class="box-header">
                            <h3 class="box-title">Article Image</h3>
                            <div class="box-tools pull-right">
                                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                Image Type:
                                <label>
                                    <input type="radio" class="form-control icheck image_type" name="image_type" value="single" <?php echo $item->image_type!='multi'?'checked':''; ?>> Single
                                    <input type="radio" class="form-control icheck image_type" name="image_type" value="multi" <?php echo $item->image_type=='multi'?'checked':''; ?>> Multi images
                                </label>
                            </div>
                            <div class="form-group">
                                <input type="hidden" id="image_url" name="image_url" value="<?php echo $item->image_list; ?>">
                                <div class="input-group">
                                    <input type="text" readonly="true" class="form-control disabled" id="selected_image" name="selected_image" placeholder="Browse image..">
                                    <div class="input-group-btn">
                                        <a href="<?php echo site_url(config_item('path_lib').'filemanager/dialog.php?type=1&&fldr=/&field_id=selected_image&relative_url=1&iframe=true&width=80%&height=80%'); ?>"  rel="prettyPhoto" class="btn btn-default"><i class="fa fa-upload"></i> Browse Image</a>
                                    </div>
                                </div>
                            </div>
                            <div class="row image-container">
                                <?php if ($item->image_type==IMAGE_TYPE_SINGLE && $item->image_url){ ?>
                                <div class="col-xs-12 col-sm-12 image-item">
                                    <div class="thumbnail">
                                        <a rel="prettyPhoto" href="<?php echo get_image_thumb($item->image_url, IMAGE_THUMB_ORI); ?>">
                                            <img class="img-responsive" src="<?php echo get_image_thumb($item->image_url, IMAGE_THUMB_MEDIUM); ?>">
                                        </a>
                                        <a class="remove-image btn btn-danger btn-xs" title="Remove this image">
                                            <i class="fa fa-minus-circle"></i>
                                        </a>
                                    </div>
                                </div>
                                <?php }else if($item->image_type==IMAGE_TYPE_MULTI && count($item->multi_images)){ ?>
                                <?php foreach ($item->multi_images as $img){ ?>
                                <div class="col-xs-4 col-sm-4 image-item">
                                    <div class="thumbnail">
                                        <a rel="prettyPhoto" href="<?php echo get_image_thumb($img->image_url, IMAGE_THUMB_ORI) ; ?>">
                                            <img class="img-responsive" src="<?php echo get_image_thumb($img->image_url, IMAGE_THUMB_SMALLER); ?>">
                                        </a>
                                        
                                        <a class="remove-image btn btn-danger btn-xs" title="Remove this image">
                                            <i class="fa fa-minus-circle"></i>
                                        </a>
                                    </div>
                                </div>
                                <?php } ?>
                                <?php } ?>
                            </div>
                            <div class="form-group" style="margin-bottom:0;">
                                <label>Image caption</label>
                                <input type="text" class="form-control" name="image_caption" placeholder="Image caption.." value="<?php echo $item->image_caption; ?>">
                            </div>
                        </div>
                        
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="box box-info">
                        <div class="box-header">
                            <h3 class="box-title">Data Support</h3>
                            <div class="box-tools pull-right">
                                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                            </div>
                        </div>
                        <div class="box-body">
                            <table class="table table-striped table-condensed">
                                <tbody>
                                    <tr>
                                        <td>Created</td>
                                        <td><?php echo date('d-M-Y H:i',$item->created); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Lastupdate</td>
                                        <td><?php echo date('d-M-Y H:i',$item->modified); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Author</td>
                                        <td><?php echo $item->created_by; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Modified by</td>
                                        <td><?php echo $item->modified_by; ?></td>
                                    </tr>
                                    <tr>
                                        <td>View count</td>
                                        <td><?php echo number_format($item->view_count); ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                    </div>
                </div>
            </div>
            <div class="box box-primary collapsed-box">
                <div class="box-header">
                    <h3 class="box-title">Article Content</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <textarea name="content" class="form-control texteditor" placeholder="Write content here..."><?php echo $item->content; ?></textarea>
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
<input type="hidden" id="base_ori_url" value="<?php echo get_image_base(IMAGE_THUMB_ORI); ?>" />
<input type="hidden" id="base_small_url" value="<?php echo get_image_base(IMAGE_THUMB_SMALL); ?>" />
<input type="hidden" id="base_medium_url" value="<?php echo get_image_base(IMAGE_THUMB_MEDIUM); ?>" />
<script src="<?php echo site_url(config_item('path_lib').'tinymce/tinymce.min.js'); ?>"></script>
<script type="text/javascript">
    
    $(document).ready(function(){
        ArticleManagers.init();
        
        $('input[name="title"]').focus();
        $('input#title').on('blur', function(){
            if ($('input#url_title').val()==''){
                $('#btn-gen-slug').click();
            }
        });
        
        $('#tags').tagsinput({
            typeahead: {
                source: <?php echo json_encode($tags);?>
            }
        })
        
        $('#btn-gen-slug').on('click',function(){
            if (!$('#title').val()){
                alert('URL title can not be empty. Please fill the title before execute link generation');
                return false;
            }
            create_url_title('title', 'url_title');
            var slug = $('#url_title').val();
            
            //test if url title is unique
            $.getJSON('<?php echo site_url('service/article/urltitle_test'); ?>/'+$('#id').val(),{url_title:slug},function(result){
                if (!result.unique){
                    slug = result.modified;
                    $('#url_title').val(slug);
                }
                
                var base_controller = 'detail/';
                var longUrl = '<?php echo site_url(); ?>' + base_controller + slug;
                $.getJSON('<?php echo site_url('ajax/google_service/shortener'); ?>',{url:longUrl},function(result){
                    if (result.success){
                        $('#url_short').val(result.short);
                    }else{
                        alert(result.message);
                    }
                });
            });
        });
        
        $('.image-container').on('click','.remove-image',function(){
            var src = $(this).attr('src');
            $(this).parents('.image-item').remove()
            
            //update image url array
            var images = $('#image_url').val();
            images = images.split('|');
            images.splice(images.indexOf(src),1);
            
            $('input#image_url').val(images.join('|'));
            
        });
        
        $('#category_id').on('change', function (){
            if ($(this).val() > 0){
                ArticleManagers.loadExtendedAttributes($(this).val());
            }
        });
    });

    function create_url_title(sourceField,targetField){
        ArticleManagers.createUrlTitle(sourceField, targetField);
    }
    
    function responsive_filemanager_callback(field_id){
        ArticleManagers.RFM_Callback(field_id);
    }
    
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
        relative_urls: false
    });
    
    var ArticleManagers = {
        hasExtAttrElementId: 'has_ext_attributes',
        extAttrContainerId: 'attribute_ext_container',
        _baseOri: '',
        _baseMedium: '',
        _baseSmall: '',
        init: function (){
            //set base image
            this.setBaseImage('ori', $('#base_ori_url').val());
            this.setBaseImage('medium', $('#base_medium_url').val());
            this.setBaseImage('small', $('#base_small_url').val());
        },
        loadExtendedAttributes: function (categoryID){
            var _this = this;
            $.getJSON('<?php echo site_url('cms/article/get_category_ext_attributes'); ?>/'+categoryID, function (data){
                if (data && data.length > 0){
                    $('#'+_this.hasExtAttrElementId).val(1);
                    $('#'+_this.extAttrContainerId).removeClass('hidden');
                    $('#'+_this.extAttrContainerId +' .row').empty();
                    var s = '';
                    for (var i in data){
                        var ext = data[i];
                        s= '<div class="col-sm-6">';
                            s+= '<div class="form-group">';
                                s+= '<label>'+ (ext.attr_label ? ext.attr_label : ext.attr_name) +'</label>';
                                s+= '<input type="'+ (ext.attr_type ? ext.attr_type : 'text')+'" name="'+ext.attr_name+'" class="form-control" value="">';
                            s+= '</div>';
                        s+= '</div>';
                        
                        $('#'+_this.extAttrContainerId +' .row').append(s);
                    }
                }else{
                    $('#'+_this.hasExtAttrElementId).val(0);
                    $('#'+_this.extAttrContainerId).addClass('hidden');
                }
            });
        },
        createUrlTitle: function(source,target){
            var url = $.trim($('#'+source).val());
            url = url.replace('%','-persen');
            //replace everything not alpha numeric
            url = url.replace(/[^a-z0-9]/gi, '-').toLowerCase();
            //url = url.replace(/[ \t\r]+/g,"-");
            $('#'+target).val(url);
        },
        RFM_Callback: function (field_id){
            var _this = this;
            var images = [];
            var base_image = '';

            //$.prettyPhoto.close();
            var image_name = document.getElementById(field_id).value;

            var image_type = $('.image_type:checked').val();
            if (image_type=='single'){
                base_image = _this.getBaseImage('medium');

                $('input#image_url').val(image_name);
                images.push(image_name);
            }else{
                base_image = _this.getBaseImage('small');

                var image_list = $('input#image_url').val();
                if (image_list){
                    images = image_list.split('|');
                }
                if (images.length<6){
                    images.push(image_name);
                }else{
                    alert('Maximum image item reached. Could not add more images');
                }

                $('input#image_url').val(images.join('|'));
            }
            
            $('.image-container').empty();

            var size;
            if (image_type=='single'){
                size = 'col-xs-12 col-sm-12 image-item';
            }else{
                size = 'col-xs-4 col-sm-4 image-item';
            }
            if (images.length>0){
                for (var i=0; i<images.length; i++){
                    var image_full_url = image_type=='single' ? _this.getImageFullUrl('medium', images[i]) : _this.getImageFullUrl('small', images[i]);
                    
                    var s = '<div class="'+size+'">';
                        s+= '<div class="thumbnail">';
                            s+= '<a rel="prettyPhoto" href="'+ (_this.getImageFullUrl('ori', images[i]))+'">';
                                s+= '<img class="img-responsive" src="'+image_full_url+'">';
                            s+= '</a>';
                            s+= '<a class="remove-image btn btn-danger btn-xs" title="Remove this image">';
                                s+= '<i class="fa fa-minus-circle"></i>';
                            s+= '</a>';
                        s+= '</div>';
                    s+= '</div>';

                    $('.image-container').append(s);
                }
            }
        },
        setBaseImage: function(imageType, baseUrl){
            switch (imageType){
                case 'all':
                    this._baseOri = baseUrl;
                    this._baseMedium = baseUrl;
                    this._baseSmall = baseUrl;
                    break;
                case 'small': this._baseSmall = baseUrl; break;
                case 'medium': this._baseMedium = baseUrl; break;
                case 'ori': 
                default:
                        this._baseOri = baseUrl;
                
                
            }
        },
        getBaseImage: function(imageType){
            switch (imageType){
                case 'ori': return this._baseOri; break;
                case 'medium': return this._baseMedium; break;
                case 'small': return this._baseSmall; break;
                default: return this._baseOri;
            }
        },
        getImageFullUrl: function (imageType, imageName){
            var fullUrl = this._baseOri;
            switch (imageType){
                case 'ori': fullUrl = this._baseOri; break;
                case 'medium': fullUrl = this._baseMedium; break;
                case 'small': fullUrl = this._baseSmall; break;
            }
            
            if (imageName){
                fullUrl += imageName;
            }
            
            return fullUrl;
        }
    };
</script>