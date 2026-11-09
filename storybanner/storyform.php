<?php
// Hook "storyform": runs in storyform() (includes/storyform.php); $stories is the story being edited.
if(!defined("_CHARSET")) exit( );
include_once(_BASEDIR."modules/storybanner/functions.php");

if(isset($stories['banner'])) {
   $banner = $stories['banner'];
}
else {
   $banner = "";
}

$output .= 
"<div style='margin-bottom: 1em'><label for=\"storybanner\">Banner:</label> 
<br />
<input type=\"text\" class=\"textbox\" id=\"storybanner\" maxlength=\"250\" name=\"banner\" size=\"100\"  value=\"" . storybanner_html($banner) . "\"></div>";
