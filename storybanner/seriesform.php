<?php
// Hook "seriesform": runs in series.php; $series is the series being edited (or an empty row).
if(!defined("_CHARSET")) exit( );
include_once(_BASEDIR."modules/storybanner/functions.php");

if(isset($series['banner'])) {
   $banner = $series['banner'];
}
else {
   $banner = "";
}

$output .= 
"<div style='margin-bottom: 1em'><label for=\"storybanner\">Banner:</label> 
<br />
<input type=\"text\" class=\"textbox\" id=\"storybanner\" maxlength=\"250\" name=\"banner\" size=\"100\"  value=\"" . storybanner_html($banner) . "\"></div>";
