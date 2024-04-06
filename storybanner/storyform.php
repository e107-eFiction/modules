<?php
if(isset($stories['banner'])) {
   $banner = $stories['banner'];
}
else {
   $banner = "";
}

$output .= 
"<div style='margin-bottom: 1em'><label for=\"banner\">Banner Tips:</label> 
<br />
<input type=\"text\" class=\"textbox\" id=\"storybanner\" maxlength=\"250\" name=\"banner\" size=\"100\"  value=\"" . htmlentities($banner) . "\"></div>";
   