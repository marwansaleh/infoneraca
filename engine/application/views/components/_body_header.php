<header>
    <div class="container">
        <div class="row top-header">
            <div class="col-sm-6">
                <ul class="login">
                    <?php if ($is_logged_in): ?>
                    <li>
                        <a href="<?php echo site_url('auth/logout?redirect='. urlencode(current_url())); ?>">Logout</a>
                    </li>
                    <?php else: ?>
                    <li>
                        <a class="btn btn-link" data-toggle="modal" data-target="#login-dialog">Login</a>
                    </li>
                    <li><a class="btn btn-link">Register</a></li>
                    <?php endif; ?>
                </ul>
            </div>
            <div class="col-sm-3 col-sm-offset-3">
                <form method="post" action="<?php echo site_url('search'); ?>">
                    <div class="input-group input-group-sm">
                        <input class="form-control" type="text" name="search" placeholder="Search...">
                        <div class="input-group-btn">
                            <button type="submit" class="btn btn-default" style="background: transparent;"><span class="glyphicon glyphicon-search"></span></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="row top-header">
            <div class="col-sm-12">
                <a href="<?php echo site_url() ?>">
                    <h1 class="text-center">INFONERACA.COM</h1>
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="col-sm-12 breaking-news">
                    <div class="row">
                        <div class="col-sm-2 title">
                           <span>Breaking News</span>
                        </div>
                        <div class="col-sm-10 header-news">
                            <?php if (isset($newstickers)): ?>
                            <div class="ticker">
                                <ul>
                                    <?php foreach ($newstickers as $ticker): ?>
                                    <li><?php echo $ticker->news_text; ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row main-nav">
            <div class="col-sm-12"><?php $this->load->view('components/_mainmenu'); ?></div>
        </div>
    </div>
</header>
<?php if (!$is_logged_in): ?>
<div class="modal fade" id="login-dialog" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">User Login</h4>
            </div>
            <div class="modal-body">
                <form id="form-login" action="<?php echo site_url('auth/login'); ?>" method="post">
                    <div class="row">
                        <div class="col-sm-7">
                            <div class="form-group">
                                <label for="username" class="control-label">Username</label>
                                <input type="text" name="username" id="username" class="form-control" placeholder="Username" />
                            </div>
                            <div class="form-group">
                                <label for="password" class="control-label">Password</label>
                                <input type="password" name="password" id="password" class="form-control" />
                            </div>
                        </div>
                        <div class="col-sm-5">
                            <div class="box bg-blue">
                                <div class="content">
                                    <p>Or you can select your login option</p>
                                    <a class="btn btn-block btn-social btn-twitter" href="<?php echo site_url('auth/twitter_redirect?redirect='. urlencode(current_url())); ?>">
                                        <span class="fa fa-twitter"></span> Sign in with Twitter
                                    </a>
                                    <a class="btn btn-block btn-social btn-facebook" onclick="facebookLogin()">
                                        <span class="fa fa-facebook"></span> Sign in with Facebook
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <hr>
                        <div class="col-sm-6">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                        <div class="col-sm-6">
                            <div class="pull-right">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<?php endif; ?>
