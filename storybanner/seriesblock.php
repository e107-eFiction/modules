<?php
// Hook "seriesblock": $stories is the series row (includes/seriesblock.php).
if(!defined("_CHARSET")) exit( );
include_once(_BASEDIR."modules/storybanner/functions.php");

$html = "";
$banner = "";
$banner_title = "";
if(isset($stories) && is_array($stories) && !empty($stories['banner'])) {
	$banner = storybanner_html($stories['banner']);
	$banner_title = storybanner_title(isset($stories['title']) ? $stories['title'] : "");
	$html = '<img class="banner serie-banner" alt="'. $banner_title.'" src="' . $banner . '">';
}

$tpl->assign('seriesbanner', $html);
$tpl->assign('seriesbanner_src', $banner);
$tpl->assign('seriesbanner_title', $banner_title);

