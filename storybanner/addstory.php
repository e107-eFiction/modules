<?php
 

if (!defined("_CHARSET")) exit();
 
if(!empty($_POST['banner']) && !empty($sid)) {

    $result = dbquery("UPDATE " . TABLEPREFIX . "fanfiction_stories 
    SET banner = '" . stripslashes($_POST['banner']) . "' WHERE sid = '$sid'");
  
}
   