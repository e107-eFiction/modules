<?php
// Hook "editseries": runs in series.php after the series row was updated.
// A posted banner that is empty or not accepted clears the stored banner.

if (!defined("_CHARSET")) exit();
include_once(_BASEDIR."modules/storybanner/functions.php");

if(isset($_POST['banner']) && !empty($seriesid)) {
	$sb_banner = storybanner_clean($_POST['banner']);
	dbquery("UPDATE " . TABLEPREFIX . "fanfiction_series 
	SET banner = '" . escapestring($sb_banner) . "' WHERE seriesid = '" . (int)$seriesid . "'");
	unset($sb_banner);
}
