        <script type="text/javascript">
            $(document).ready(function(){
                //iCheck for checkbox and radio inputs
                $('input[type="checkbox"].icheck, input[type="radio"].icheck').iCheck({
                  checkboxClass: 'icheckbox_minimal-blue',
                  radioClass: 'iradio_minimal-blue'
                });
                //pretty photo
                $("a[rel^='prettyPhoto']").prettyPhoto({
                    social_tools:''
                });
                //nice scroll
                $('html').niceScroll({cursorcolor:"#00F"});
                $('.nicescroll').niceScroll({cursorcolor:"#00F"});
                //tooltip
                $('[data-toggle="tooltip"]').tooltip();
                //selectpicker
                $('select.selectpicker').selectpicker();
                //datepicker
//                $('.datepicker').datepicker({
//                    format: 'dd-mm-yyyy',
//                });
                $('.datetimepicker').datetimepicker({
                    format: 'DD-MM-YYYY HH:mm',
                });
                $('.confirmation').on('click',function(){
                    var confirm_text = 'Are you sure to delete this item?';
                    if ($(this).attr('data-confirmation')){
                        confirm_text = $(this).attr('data-confirmation');
                    }
                    return confirm(confirm_text);
                });
                $('input.nospace').on('keypress', function (e){
                    if(e.which !== 32){
                        return true;
                    }else{
                        e.preventDefault();
                        if ($(this).attr('data-alert-message')){
                            alert($(this).attr('data-alert-message'));
                        }
                        return false;
                    }
                });
                $('input.valid_user').on('keypress', function (e){
                    //var regex = /^[-\w\.\$@\*\!]$/;
                    //var regex = /^[a-zA-Z0-9_]$/;
                    var regex = /^[-\w\.\$*]$/;
                    
                    if (e.which !== 13){
                        if (regex.test(String.fromCharCode(e.which))){
                            return true;
                        }else{
                            e.preventDefault();
                            if ($(this).attr('data-alert-message')){
                                alert($(this).attr('data-alert-message'));
                            }
                            return false;
                        }
                    }
                    return true;
                });
            });
        </script>
        <!-- bootstrap -->
        <script src="<?php echo site_url(config_item('path_lib').'bootstrap/js/bootstrap.min.js'); ?>"></script>
        <!-- nice scroll -->
        <script src="<?php echo site_url(config_item('path_lib').'scrollTo/jquery.scrollTo.min.js'); ?>"></script>
        <script src="<?php echo site_url(config_item('path_lib').'nicescroll/jquery.nicescroll.min.js'); ?>" type="text/javascript"></script>
        <!-- prettyPhoto -->
        <script src="<?php echo site_url(config_item('path_lib').'prettyPhoto/3.15/js/jquery.prettyPhoto.js'); ?>"></script>
        <!-- iCheck -->
        <script src="<?php echo site_url(config_item('path_lib').'iCheck/icheck.min.js'); ?>"></script>
        <!-- Bootstrap select -->
        <script src="<?php echo site_url(config_item('path_lib').'bootstrap-select/js/bootstrap-select.min.js'); ?>"></script>
        <!-- Bootstrap datetimepicker -->
        <script src="<?php echo site_url(config_item('path_lib').'momentjs/moment.min.js'); ?>"></script>
        <script src="<?php echo site_url(config_item('path_lib').'datetimepicker/js/bootstrap-datetimepicker.min.js'); ?>"></script>
        <!-- Bootstrap Typeahead & TagsInput -->
        <script src="<?php echo site_url(config_item('path_lib').'bootstrap-typeahead/bootstrap3-typeahead.js'); ?>"></script>
        <script src="<?php echo site_url(config_item('path_lib').'angular/angular.min.js'); ?>"></script>
        <script src="<?php echo site_url(config_item('path_lib').'tagsinput/bootstrap-tagsinput.min.js'); ?>"></script>
        <script src="<?php echo site_url(config_item('path_lib').'tagsinput/bootstrap-tagsinput-angular.js'); ?>"></script>
        <!-- Image Row grid -->
        <script src="<?php echo site_url(config_item('path_lib').'rowgrid/jquery.row-grid.min.js'); ?>"></script>
    </body>
</html>