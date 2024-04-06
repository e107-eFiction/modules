<?php
$html = "";
$banner = "";
$banner_title = "";
$banner = $stories['banner'];
 
if(isset($banner) && $banner != "") {
        $banner_title  = stripslashes($stories['title']);
        $html = '<img class="banner serie-banner" alt="'. $banner_title.'" src="' . $banner . '">';
}

$tpl->assign('seriesbanner', $html);
$tpl->assign('seriesbanner_src', $banner);
$tpl->assign('seriesbanner_title', $banner_title);

 