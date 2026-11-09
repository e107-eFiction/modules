<?php
// Hooks "viewstory" and "storyblock": $stories is the story row.
if(!defined("_CHARSET")) exit( );
include_once(_BASEDIR."modules/storybanner/functions.php");

$html = "";
$banner = "";
$banner_title = "";
if(isset($stories) && is_array($stories) && !empty($stories['banner'])) {
	$banner = storybanner_html($stories['banner']);
	$banner_title = storybanner_title(isset($stories['title']) ? $stories['title'] : "");
	$html = '<img class="banner" alt="'. $banner_title.'" src="' . $banner . '">';
}
 
$tpl->assign('storybanner', $html);
$tpl->assign('storybanner_src', $banner);
$tpl->assign('storybanner_title', $banner_title);

