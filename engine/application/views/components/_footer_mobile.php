    <footer>
        <p class="redaksi">
            Email Redaksi: <a href="mailto:redaksi@infoneraca.com">redaksi@infoneraca.com</a>, 
        </p>
        <p class="copyright">Copyright@2016 Infoneraca.com</p>
    </footer>
    <script type="text/javascript">
            $(document).ready(function(){
                $('.media-list').on('click','.media',function(){
                    if ($(this).attr('data-href')){
                        window.location = $(this).attr('data-href');
                    }
                });
            });
        </script>
    
        <!-- bootstrap -->
        <script src="<?php echo site_url(config_item('path_lib').'bootstrap/js/bootstrap.min.js'); ?>"></script>
    </body>
</html>