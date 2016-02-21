<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <?php if (isset($meta)&&  is_array($meta)): ?>
        <?php foreach($meta as $key=>$val): ?>
        <meta name="<?php echo $key; ?>" content="<?php echo $val; ?>">
        <?php endforeach; ?>
        <?php endif; ?>
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
        
    </head>
    <body>
        <?php if (isset($og_props['og:image'])): ?>
        <img src="<?php echo $og_props['og:image']; ?>" <?php echo isset($image_dimension)?'width="'.$image_dimension[0].'" height="'.$image_dimension[1].'"':''; ?> />
        <?php endif; ?>
    </body>
</html>