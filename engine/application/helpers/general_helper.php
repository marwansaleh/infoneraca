<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('breadcumb_add')){
    function breadcumb_add(&$breadcumb,$title,$link=NULL,$active=FALSE){
        if (is_array($breadcumb)){
            $item = array('title'=>$title, 'active'=>$active);
            if ($link){
                $item['link'] = $link;
            }
            $breadcumb [] = $item;
        }
    }
}

if (!function_exists('breadcumb')){
    function breadcrumb($pages, $showServerTime=FALSE){
        $str = '<ol class="breadcrumb">';
        
        if (is_array($pages)){
            if ($showServerTime){
                $new_bc = array (array('title'=> date('D, dMY H:i:s')));
                array_splice($pages, 0,0, $new_bc);
            }
            foreach ($pages as $page){
                $active = (isset($page['active'])&&$page['active']==TRUE);
                $str.= '<li';
                if ($active)
                    $str.= ' class="active"';
                        
                $str.= '>';
                if (isset($page['link']))
                    $str.= '<a href="'.$page['link'].'">'. $page['title'].'</a>';
                else
                    $str.= $page['title'];
                
                
                $str.= '</li>';
            }
        }
        else
        {
            $str.= '<li>'.$page.'</li>';
        }
        $str.= '</ol>';
        return $str;
    }
}

if (!function_exists('create_alert_box')){
    function create_alert_box($alert_text, $alert_type=NULL, $alert_title=NULL, $autohide=TRUE){
        $type_labels = array(
            'default' => 'Information', 'info'=>'Information', 'success'=>'Successfull', 
            'warning'=>'Warning', 'danger'=>'Danger', 'error'=>'Error'
        );
        $type_alerts = array(
            'default'=>'alert-info', 'info'=>'alert-info', 'success'=>'alert-success', 
            'warning'=>'alert-warning', 'danger'=>'alert-danger', 'error'=>'alert-danger'
        );
        $s = '<div class="alert '.(isset($type_alerts[$alert_type])?$type_alerts[$alert_type]:$type_alerts['default']).' alert-dismissible" role="alert">';
        //button dismiss
        $s.= '<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>';
        //Label in bold
        $s.= '<strong>'. ($alert_title?$alert_title:(isset($type_labels[$alert_type])?$type_labels[$alert_type]:$type_labels['default']).'!').'</strong> ';
        //Alert text
        $s.= $alert_text;
        $s.= '</div>';
        
        //add js to hide automatically
        if ($autohide){
            $s.= PHP_EOL . '<script>setTimeout(function(){$(".alert-dismissible").fadeOut("slow");},2500);</script>';
        }
        
        return $s;
    }
}

if (!function_exists('indonesia_date_format')){
    /**
     * 
     * @param type $format
     * @param type $time
     */
    function indonesia_date_format($format='%d-%m-%Y', $time=NULL){
        
        //create date object
        if (!$time) { $time = time(); }
        $date_obj  =  getdate($time);
        
        //set Indonesian month name
        $bulan = array(
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'Nopember', 'Desember'
        );
        
        $bulan_short = array(
            'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nop', 'Des'
        );
        
        $hari = array('Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu');
        
        $format_search = array('%d','%D','%m','%M','%S','%y','%Y','%H','%i','%s');
        $format_replace = array( 
            $date_obj['mday'], $hari[$date_obj['wday']],  $date_obj['mon'], $bulan[$date_obj['mon']-1],  
            $bulan_short[$date_obj['mon']-1], $date_obj['year'], $date_obj['year'], $date_obj['hours'], 
            $date_obj['minutes'], $date_obj['seconds']  
        );
        $str = str_replace($format_search, $format_replace, $format);
        
        return $str;
    }
}

if (!function_exists('get_indonesia_month')){
    function get_indonesia_month($month_index=NULL, $short=FALSE){
        //set Indonesian month name
        $bulan = array(
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'Nopember', 'Desember'
        );
        
        $bulan_short = array(
            'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nop', 'Des'
        );
        
        if (!$month_index || $month_index < 1 || $month_index > 12){
            $month_index = date('m');
        }
        
        if ($short){
            return $bulan_short[$month_index-1];
        }else{
            return $bulan[$month_index-1];
        }
    }
}

if (!function_exists('smart_paging_description')){
    function smart_paging_description($total_recs=0, $curr_num_recs=0){
        $str = '<ul class="pagination pagination-sm no-margin">';
        if ($total_recs==0){
            $str .= '<li><span class="text-grey">Data not found</span></li>';
        }else{
            $str .= '<li><span class="text-grey">Showing '.$curr_num_recs.' from '. $total_recs.' records</span></li>';
        }
        
        $str .= '</ul>';
        
        return $str;
    }
}

if (!function_exists('smart_paging')){
    function smart_paging($totalPages, $page=1, $adjacents=2, $url_format='%i', $min_page_adjacents=5, $recordInfo=NULL){  
	$prev = $page - 1;
	$next = $page + 1;
        $first_page = 1;
	$lastpage = $totalPages-1;		//lastpage is = total pages / items per page, rounded up.
	$lpm1 = $lastpage - 1;			//last page minus 1

	/* 
         * Now we apply our rules and draw the pagination object. 
         * We're actually saving the code to a variable in case we want to draw it more than once.
	*/
        
        
	$pagination = '';
        $pagination .= '<ul class="pagination pagination-sm no-margin pull-right">';
        
	if($lastpage >=1)
	{   
            //previous button
            if ($page > 1) 
                $pagination.= '<li><a href="'.str_replace('%i',$prev,$url_format).'">&laquo;</a></li>';
            else
                $pagination.= '<li class="disabled"><a>&laquo;</a></li>';

            //pages	
            if ($lastpage < $min_page_adjacents + $adjacents)	//not enough pages to bother breaking it up
            {	
                for ($counter = 1; $counter <= $totalPages; $counter++)
		{
                    if ($counter == $page)
                        $pagination.= '<li class="active"><a class="current">'.$counter.'</a></li>';
                    else
                        $pagination.= '<li><a href="'.str_replace('%i',$counter,$url_format).'">'.$counter.'</a></li>';				
		}
            }
            
            elseif($lastpage > $min_page_adjacents + $adjacents)	//enough pages to hide some
            {
                //close to beginning; only hide later pages
		if($page < 1 + ($adjacents * 2))		
		{
                    for ($counter = 1; $counter < $min_page_adjacents + $adjacents; $counter++)
                    {
                        if ($counter == $page)
                            $pagination.= '<li class="active"><a class="current">'.$counter.'</a></li>';
                        else
                            $pagination.= '<li><a href="'.str_replace('%i',$counter,$url_format).'">'.$counter.'</a></li>';			
                    }
                    $pagination.='<li class="disabled"><a>...</a></li>';
                    for($i=0;$i<$adjacents;$i++){
                        $pagination.= '<li><a href="'.str_replace('%i',($lastpage-$i),$url_format).'">'.($lastpage-$i).'</a></li>';
                    }
                    
                }
                //in middle; hide some front and some back
                elseif($lastpage - $adjacents > $page && $page > $adjacents)
		{
                    for($i=0;$i<$adjacents;$i++){
                        $pagination.= '<li><a href="'.str_replace('%i',($first_page+$i),$url_format).'">'.($first_page+$i).'</a></li>';
                    }
                    $pagination.='<li class="disabled"><a>...</a></li>';
                    for ($counter = $page - $adjacents; $counter <= $page+$adjacents; $counter++)
                    {
                        if ($counter == $page)
                            $pagination.= '<li class="active"><a>'.$counter.'</a></li>';
			else
                            $pagination.= '<li><a href="'.str_replace('%i',$counter,$url_format).'">'.$counter.'</a></li>';
                    }
                    $pagination.='<li class="disabled"><a>...</a></li>';
                    for($i=0;$i<$adjacents;$i++){
                        $pagination.= '<li><a href="'.str_replace('%i',($lastpage-$i),$url_format).'">'.($lastpage-$i).'</a></li>';
                    }
                }
                //close to end; only hide early pages
		else
		{
                    for($i=0;$i<$adjacents;$i++){
                        $pagination.= '<li><a href="'.str_replace('%i',($first_page+$i),$url_format).'">'.($first_page+$i).'</a></li>';
                    }
                    $pagination.='<li class="disabled"><a>...</a></li>';
                    for ($counter = $lastpage - (2 + $adjacents); $counter <= $totalPages; $counter++)
                    {
                        if ($counter == $page)
                            $pagination.= '<li class="active"><a>'.$counter.'</a></li>';
                        else
                            $pagination.= '<li><a href="'.str_replace('%i',$counter,$url_format).'">'.$counter.'</a></li>';				
                    }
		}
            }
            
            //next button
            if ($page < $totalPages) 
                $pagination.= '<li><a href="'.str_replace('%i',$next,$url_format).'">&raquo;</a></li>';
            else
                $pagination.= '<li class="disabled"><a>&raquo;</a></li>';
	}
        $pagination.= '</ul>';

        
        return $pagination;
    }
}

if (!function_exists('smart_paging_js')){
    function smart_paging_js($totalPages, $page=1, $jsClick='', $adjacents=2, $offsetTag='$'){  
	$prev = $page - 1;
	$next = $page + 1;
	$lastpage = $totalPages-1;		//lastpage is = total pages / items per page, rounded up.
	$lpm1 = $lastpage - 1;			//last page minus 1
	
	/* 
         * Now we apply our rules and draw the pagination object. 
         * We're actually saving the code to a variable in case we want to draw it more than once.
	*/
	$pagination = '';
	if($lastpage >=1)
	{	
            $pagination .= '<div class="pagination"><ul>';
            //previous button
            if ($page > 1) 
                $pagination.= '<li><a href='.  parseJs($jsClick, $prev, $offsetTag).'>Prev</a></li>';
            else
                $pagination.= '<li class="disabled"><a>Prev</a></li>';
		
            //pages	
            if ($lastpage < 7 + ($adjacents * 2))	//not enough pages to bother breaking it up
            {	
                for ($counter = 1; $counter <= $totalPages; $counter++)
		{
                    if ($counter == $page)
                        $pagination.= '<li class="active"><a>'.$counter.'</a></li>';
                    else
                        $pagination.= '<li><a href='.  parseJs($jsClick, $counter).'>'.$counter.'</a></li>';				
		}
            }
            
            elseif($lastpage > 5 + ($adjacents * 2))	//enough pages to hide some
            {
                //close to beginning; only hide later pages
		if($page < 1 + ($adjacents * 2))		
		{
                    for ($counter = 1; $counter < 5 + ($adjacents * 2); $counter++)
                    {
                        if ($counter == $page)
                            $pagination.= '<li class="active"><a>'.$counter.'</a></li>';
                        else
                            $pagination.= '<li><a href='.  parseJs($jsClick, $counter).'>'.$counter.'</a></li>';			
                    }
                    $pagination.='<li><a>...</a></li>';
                    $pagination.= '<li><a href='.  parseJs($jsClick, $lpm1).'>'.$lpm1.'</a></li>';
                    $pagination.= '<li><a href='.  parseJs($jsClick, $lastpage).'>'.$lastpage.'</a></li>';
                    
                }
                //in middle; hide some front and some back
                elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
		{
                    $pagination.= '<li><a href='.  parseJs($jsClick, 1).'>1</a></li>';
                    $pagination.= '<li><a href='.  parseJs($jsClick, 2).'>2</a></li>';
                    $pagination.='<li><a>...</a></li>';
                    for ($counter = $page - $adjacents; $counter <= $page+1 + $adjacents; $counter++)
                    {
                        if ($counter == $page)
                            $pagination.= '<li class="active"><a>'.$counter.'</a></li>';
			else
                            $pagination.= '<li><a href='.  parseJs($jsClick, $counter).'>'.$counter.'</a></li>';
                    }
                    $pagination.='<li><a>...</a></li>';
                    $pagination.= '<li><a href='.  parseJs($jsClick, $lpm1).'>'.$lpm1.'</a></li>';
                    $pagination.= '<li><a href='.  parseJs($jsClick, $lastpage).'>'.$lastpage.'</a></li>';
                }
                //close to end; only hide early pages
		else
		{
                    $pagination.= '<li><a href='.  parseJs($jsClick, 1).'>1</a></li>';
                    $pagination.= '<li><a href='.  parseJs($jsClick, 2).'>2</a></li>';
                    $pagination.='<li><a>...</a></li>';
                    for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $totalPages; $counter++)
                    {
                        if ($counter == $page)
                            $pagination.= '<li class="active"><a>'.$counter.'</a></li>';
                        else
                            $pagination.= '<li><a href='.parseJs($jsClick, $counter).'>'.$counter.'</a></li>';				
                    }
		}
            }
            
            //next button
            if ($page < $totalPages) 
                $pagination.= '<li><a href='. parseJs($jsClick, $next).'>Next</a></li>';
            else
                $pagination.= '<li class="disabled"><a>Next</a></li>';

            $pagination.= '</ul></div>';
	}
		
	
        
        return $pagination;
    }
    
    function parseJs($js, $var, $tag='$'){
        return str_replace($tag, $var, $js);
    }
}
    
if (!function_exists('time_difference')){
    function time_difference($date,$unix_input=FALSE)
    {
        if(empty($date)) {
            return "Please provide date.";
        }

        $periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
        $lengths = array("60","60","24","7","4.35","12","10");

        $now = time();
        if ($unix_input){
            $unix_date = $date;
        }else{
            $unix_date = strtotime($date);
        }

        // check validity of date
        if(empty($unix_date)) {
            return "Invalid date";
        }

        //Check to see if it is past date or future date
        if($now > $unix_date) {
            $difference = $now - $unix_date;
            $tense = "ago";

        } else {
            $difference = $unix_date - $now;
            $tense = "from now";
        }

        for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
            $difference /= $lengths[$j];
        }

        $difference = round($difference);

        if($difference != 1) {
            $periods[$j].= "s";
        }

        return "$difference $periods[$j] {$tense}";
    }
}

if (!function_exists('file_extension')){
    function file_extension($path){
        $ext = pathinfo($path, PATHINFO_EXTENSION); 
        return strtolower($ext);
    }
}

if (!function_exists('userfiles_baseurl')){
    function userfiles_baseurl($url=''){
        $base_url = config_item('userfiles_base_url');
        if ($base_url=='' || !$base_url){
            return site_url($url);
        }else{
            $base_url = rtrim($base_url, '/') .'/';
            return  $base_url . $url;
        }
    }
}

if (!function_exists('userfiles_basepath')){
    function userfiles_basepath($path=''){
        $base_path = config_item('userfiles_base_path');
        if ($base_path=='' || !$base_path){
            return $path;
        }else{
            $base_path = rtrim($base_path, '/') .'/';
            return  $base_path . $path;
        }
    }
}

if (!function_exists('get_image_base')){
    function get_image_base($type=IMAGE_THUMB_LARGE, $full_url=TRUE){
        $base_url = '';
        

        switch ($type){
            case IMAGE_THUMB_ORI: $base_url= config_item('images'); break;
            case IMAGE_THUMB_LARGE: $base_url= config_item('th_large'); break;
            case IMAGE_THUMB_PORTRAIT: $base_url= config_item('th_portrait'); break;
            case IMAGE_THUMB_MEDIUM: $base_url= config_item('th_medium'); break;
            case IMAGE_THUMB_SMALL: $base_url= config_item('th_small'); break;
            case IMAGE_THUMB_SQUARE: $base_url= config_item('th_square'); break;
            case IMAGE_THUMB_SMALLER: $base_url= config_item('th_smaller'); break;
            case IMAGE_THUMB_TINY: $base_url= config_item('th_tiny'); break;
        }
        
        if ($full_url){
            $base_url = userfiles_baseurl($base_url);
        }
        return rtrim($base_url,'/') .'/';
    }
}

if (!function_exists('get_image_basepath')){
    function get_image_basepath($type=IMAGE_THUMB_LARGE, $full_path=TRUE){
        $base_path = '';
        

        switch ($type){
            case IMAGE_THUMB_ORI: $base_path= config_item('images'); break;
            case IMAGE_THUMB_LARGE: $base_path= config_item('th_large'); break;
            case IMAGE_THUMB_PORTRAIT: $base_path= config_item('th_portrait'); break;
            case IMAGE_THUMB_MEDIUM: $base_path= config_item('th_medium'); break;
            case IMAGE_THUMB_SMALL: $base_path= config_item('th_small'); break;
            case IMAGE_THUMB_SQUARE: $base_path= config_item('th_square'); break;
            case IMAGE_THUMB_SMALLER: $base_path= config_item('th_smaller'); break;
            case IMAGE_THUMB_TINY: $base_path= config_item('th_tiny'); break;
        }
        
        if ($full_path){
            $base_path = userfiles_basepath($base_path);
        }
        return rtrim($base_path,'/') .'/';
    }
}

if (!function_exists('get_image_thumb')){
    function get_image_thumb($filename, $type=IMAGE_THUMB_LARGE, $full_url=TRUE){
        $base_url = '';

        if ($full_url){
            $base_url = get_image_base($type, $full_url);
        }
        
        $image_url = rtrim($base_url , '/') . '/' . $filename;

        return $image_url;
    }
}

if (!function_exists('get_image_thumbpath')){
    function get_image_thumbpath($filename, $type=IMAGE_THUMB_LARGE, $full_path=TRUE){
        $base_path = '';

        if ($full_path){
            $base_path = get_image_basepath($type, $full_path);
        }
        
        $image_path = rtrim($base_path , '/') . '/' . $filename;

        return $image_path;
    }
}

if (!function_exists('boolval')){
    function boolval($value){
        return (bool) $value;
    }
}

if (!function_exists('variable_type_cast')){
    function variable_type_cast($value, $type='string'){
        switch ($type){
            case 'integer': return intval($value);
            case 'float': return floatval($value);
            case 'boolean': return boolval($value);
            default : return strval($value);
        }
    }
}

if (!function_exists('widget_exists')){
    function widget_exists($widget){
        $widgets = array(WIDGET_FACEBOOK,WIDGET_NEWSGROUP,WIDGET_NEWSLATEST,WIDGET_NEWSPHOTO,WIDGET_SOCMED_COUNTERS,WIDGET_STOCKS,WIDGET_VIDEO,WIDGET_INSPIRATION,WIDGET_SELECTED_CATEGORY);
        
        return in_array($widget, $widgets);
    }
}

if (!function_exists('kelvin_2_celcius')){
    function kelvin_2_celcius($degree){
        $constant = 273.15;
        
        return $degree-$constant;
    }
}

/*
 * file location: /application/helpers/general_helper.php
 */
