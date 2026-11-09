<?php
// Hook "addstory": runs in newstory() in stories.php after the new story row was inserted.

if (!defined("_CHARSET")) exit();
include_once(_BASEDIR."modules/storybanner/functions.php");

if(isset($_POST['banner']) && !empty($sid)) {
	$sb_banner = storybanner_clean($_POST['banner']);
	if($sb_banner !== "") {
		dbquery("UPDATE " . TABLEPREFIX . "fanfiction_stories 
		SET banner = '" . escapestring($sb_banner) . "' WHERE sid = '" . (int)$sid . "'");
	}
	unset($sb_banner);
}
