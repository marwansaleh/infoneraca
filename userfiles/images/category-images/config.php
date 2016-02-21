<?php
date_default_timezone_set('Asia/Jakarta');

//Automatic resizing //
// If you set $image_resizing to TRUE the script converts all uploaded images exactly to image_resizing_width x image_resizing_height dimension
// If you set width or height to 0 the script automatically calculates the other dimension
// Is possible that if you upload very big images the script not work to overcome this increase the php configuration of memory and time limit
$image_resizing 		= TRUE;
$image_resizing_width 		= 500;
$image_resizing_height 		= 220;
$image_resizing_mode 		= 'crop'; // same as $image_max_mode
$image_resizing_override 	= FALSE; 

?>