<?php
 

if (!defined("_CHARSET")) exit();
 
if(!empty($_POST['banner'])) {

    $result = dbquery("UPDATE " . TABLEPREFIX . "fanfiction_series 
    SET banner = '" . stripslashes($_POST['banner']) . "' WHERE seriesid = '$seriesid'");
  
}
   