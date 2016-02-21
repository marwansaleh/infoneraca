<!DOCTYPE html>
<html lang="id" itemscope itemtype="http://schema.org/Article">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" type="image/png" href="<?php echo site_url('assets/img/one.png'); ?>"/>
        <!-- Meta tags -->
        <?php if (isset($meta)&&  is_array($meta)): ?>
        <?php foreach($meta as $key=>$val): ?>
        <meta name="<?php echo $key; ?>" content="<?php echo $val; ?>">
        <?php endforeach; ?>
        <?php endif; ?>
        <!-- Meta ItemProp for google share-->
        <?php if (isset($metaprop)&&  is_array($metaprop)): ?>
        <?php foreach($metaprop as $key=>$val): ?>
        <meta itemprop="<?php echo $key; ?>" content="<?php echo $val; ?>">
        <?php endforeach; ?>
        <?php endif; ?>
        <!-- Meta Og properties -->
        <?php if (isset($og_props)&&  is_array($og_props)): ?>
        <?php foreach($og_props as $prop=>$val): ?>
        <?php if (strpos($prop,':')!==FALSE): ?>
        <meta property="<?php echo $prop; ?>" content="<?php echo $val; ?>" />
        <?php else: ?>
        <meta property="og:<?php echo $prop; ?>" content="<?php echo $val; ?>" />
        <?php endif; ?>
        <?php endforeach; ?>
        <?php endif; ?>

        <title><?php echo $meta_title; ?></title>

        <!-- Bootstrap CSS -->    
        <link href="<?php echo site_url(config_item('path_lib').'bootstrap/css/bootstrap.min.css'); ?>" rel="stylesheet">
        <!-- bootstrap theme -->
        <link href="<?php echo site_url(config_item('path_lib').'bootstrap/css/bootstrap-theme.min.css'); ?>" rel="stylesheet">
        <!--external css-->
        <link href="<?php echo site_url(config_item('path_lib').'font-awesome-4.1.0/css/font-awesome.min.css'); ?>" rel="stylesheet" />    
        <link href="<?php echo site_url(config_item('path_lib').'prettyPhoto/3.15/css/prettyPhoto.css'); ?>" rel="stylesheet" />    
        <link href="<?php echo site_url(config_item('path_lib').'flexslider/2.4/flexslider.css'); ?>" rel="stylesheet" />    
        <link href="<?php echo site_url(config_item('path_lib').'bootstrap-social/bootstrap-social.css'); ?>" rel="stylesheet" />    
        <!-- slider slick -->
        
        <!-- Custom styles -->
        <link href="<?php echo site_url(config_item('path_assets').'css/style.css'); ?>" rel="stylesheet">

        <script src="<?php echo site_url(config_item('path_lib').'jquery/jquery-1.11.2.min.js'); ?>"></script>
        <script src="<?php echo site_url(config_item('path_assets').'js/nasabahco.js'); ?>"></script>
        <!-- HTML5 shim and Respond.js IE8 support of HTML5 -->
        <!--[if lt IE 9]>
          <script src="<?php echo site_url(config_item('path_lib').'html5shiv/html5shiv.min.js'); ?>"></script>
          <script src="<?php echo site_url(config_item('path_lib').'respondjs/respond.min.js'); ?>"></script>
          <script src="<?php echo site_url(config_item('path_lib').'lte-ie7/lte-ie7.js'); ?>"></script>
        <![endif]-->
        
        <script>
          (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
          (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
          m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
          })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

          ga('create', '<?php echo $GA_Code; ?>', 'auto');
          ga('send', 'pageview');

        </script>
    </head>
    <body <?php echo isset($body_class) && $body_class?'class="'.$body_class.'"':''; ?>>
        <div id="fb-root"></div>
    