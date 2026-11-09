<?php
// Hook "addseries": runs in series.php after the new series row was inserted ($seriesid = dbinsertid()).

if (!defined("_CHARSET")) exit();
include_once(_BASEDIR."modules/storybanner/functions.php");

if(isset($_POST['banner']) && !empty($seriesid)) {
	$sb_banner = storybanner_clean($_POST['banner']);
	if($sb_banner !== "") {
		dbquery("UPDATE " . TABLEPREFIX . "fanfiction_series 
		SET banner = '" . escapestring($sb_banner) . "' WHERE seriesid = '" . (int)$seriesid . "'");
	}
	unset($sb_banner);
}
