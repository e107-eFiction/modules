<?php
$html = "";
$banner = "";
$banner_title = "";
$banner = $stories['banner'];
if(isset($banner) && $banner != "") {
        $banner_title  = stripslashes($stories['title']);
        $html = '<img class="banner" alt="'. $banner_title.'" src="' . $banner . '">';
}
 
$tpl->assign('storybanner', $html);
$tpl->assign('storybanner_src', $banner);
$tpl->assign('storybanner_title', $banner_title);

 