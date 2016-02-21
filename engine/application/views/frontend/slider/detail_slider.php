<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
    <!-- Indicators -->
    <ol class="carousel-indicators">
        <?php $i=0; foreach ($images as $image): ?>
        <li <?php echo $i==0?'class="active"':''; ?> data-target="#carousel-example-generic" data-slide-to="<?php echo $i++; ?>"></li>
        <?php endforeach; ?>
    </ol>

    <!-- Wrapper for slides -->
    <div class="carousel-inner" role="listbox" style="max-height:450px;overflow:hidden;">
        <?php $i=0; foreach ($images as $image): ?>
        <div class="item <?php echo $i==0?'active':''; ?>">
            <img class="large" src="<?php echo get_image_thumb($image->image_url, IMAGE_THUMB_LARGE); ?>">
        </div>
        <?php $i++; endforeach; ?>
    </div>

    <!-- Controls -->
    <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
</div>