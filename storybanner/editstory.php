<?php
// Hook "editstory": runs in editstory() in stories.php after the story row was updated.
// A posted banner that is empty or not accepted clears the stored banner.

if (!defined("_CHARSET")) exit();
include_once(_BASEDIR."modules/storybanner/functions.php");

if(isset($_POST['banner']) && !empty($sid)) {
	$sb_banner = storybanner_clean($_POST['banner']);
	dbquery("UPDATE " . TABLEPREFIX . "fanfiction_stories 
	SET banner = '" . escapestring($sb_banner) . "' WHERE sid = '" . (int)$sid . "'");
	unset($sb_banner);
}
