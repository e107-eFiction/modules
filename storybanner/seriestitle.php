<?php
// Hook "seriestitle": $series is the series row, $titleblock the title template (viewseries.php).
if(!defined("_CHARSET")) exit( );
include_once(_BASEDIR."modules/storybanner/functions.php");

$html = "";
$banner = "";
$banner_title = "";
if(isset($series) && is_array($series) && !empty($series['banner'])) {
	$banner = storybanner_html($series['banner']);
	$banner_title = storybanner_title(isset($series['title']) ? $series['title'] : "");
	$html = '<img class="banner serie-banner" alt="'. $banner_title.'" src="' . $banner . '">';
}

$titleblock->assign('seriesbanner', $html);
$titleblock->assign('seriesbanner_src', $banner);
$titleblock->assign('seriesbanner_title', $banner_title);

