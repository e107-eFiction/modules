<?php
$html = "";
$banner = "";
$banner_title = "";
$banner = $series['banner'];
 
if(isset($banner) && $banner != "") {
        $banner_title  = stripslashes($series['title']);
        $html = '<img class="banner serie-banner" alt="'. $banner_title.'" src="' . $banner . '">';
}

$titleblock->assign('seriesbanner', $html);
$titleblock->assign('seriesbanner_src', $banner);
$titleblock->assign('seriesbanner_title', $banner_title);

 