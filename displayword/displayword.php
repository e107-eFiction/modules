<?php
$current = "";
 error_reporting(E_ALL);
// Checks that the given $num is actually a number.  Used to help prevent XSS attacks.
function isNum($num) {
	if(empty($num)) return false;
	if(!is_string($num)) return false;
	return preg_match("/^[0-9]+$/", $num);
}

// Same with the author list
function author_list($stories) {
	if($stories['coauthors']) {
		$authlink[] = $stories['penname'];
		$coquery = dbquery("SELECT "._PENNAMEFIELD." as penname, "._UIDFIELD." as uid FROM "._AUTHORTABLE." WHERE FIND_IN_SET("._UIDFIELD.", '".$stories['coauthors']."') > 0 ORDER BY "._PENNAMEFIELD);
		while($co = dbassoc($coquery)) {
			$authlink[] = $co['penname'];
		}
	}
	return isset($authlink) ? implode(", ", $authlink) : $stories['penname'];
}

$sid = isset($_GET['sid']) && isNum($_GET['sid']) ? $_GET['sid'] : false;
$chapter = isset($_GET['chapter']) && isNum($_GET['chapter']) ? $_GET['chapter'] : false;
$_GET['action'] = "printable";
$action = "printable";
define("USERUID", 0);
define("USERPENNAME", false);
define("uLEVEL", 0);
define("isMEMBER", false);
define("isADMIN", false);

// Locate config.php and set the basedir path
$folder_level = "";
while (!file_exists($folder_level."header.php")) { $folder_level .= "../"; }
if(!defined("_BASEDIR")) define("_BASEDIR", $folder_level);

@include_once(_BASEDIR."config.php");
$output = "";


$settingsresults = dbquery("SELECT * FROM ".$settingsprefix."fanfiction_settings WHERE sitekey = '".$sitekey."'");
$settings = dbassoc($settingsresults);
foreach($settings as $var => $val) {
	if (is_NULL($val)) $val = '';
	$$var = stripslashes($val);
	$settings[$var] = htmlspecialchars($val);
}

$debug = 0;
define("TABLEPREFIX", $tableprefix);
define("STORIESPATH", $storiespath);
include_once(_BASEDIR."includes/queries.php");
session_start( );

if(isset($_SESSION[$sitekey."_skin"])) $skin = $_SESSION[$sitekey."_skin"];

if(isset($_GET['skin'])) {
	$skin = $_GET['skin'];
	$_SESSION[$sitekey."_skin"] = $skin;
}

// Defines the character set for your language/location
if (!defined('_CHARSET')) {
    define('_CHARSET', 'utf-8');
}

if(is_dir(_BASEDIR."skins/$skin")) $skindir = _BASEDIR."skins/$skin";
else $skindir = _BASEDIR."default_tpls";
if(file_exists(_BASEDIR."languages/{$language}.php")) require_once (_BASEDIR."languages/{$language}.php");
else require_once (_BASEDIR."languages/en.php");

if(!empty($sid)) {
	$storyquery = dbquery(_STORYQUERY." AND sid = '$sid' LIMIT 1");
	$storyinfo = dbassoc($storyquery);
}
if($storyinfo) {
	header("Expires: Mon, 26 Jul 2001 05:00:00 GMT");
	header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
	header("Cache-Control: no-store,no-cache,must-revalidate");
	header("Cache-Control: post-check=0,pre-check=0", false);
	header("Pragma: no-cache");
	header("Cache-control: private");
	header("Content-type: application/msword");
	header("Content-Disposition: attachment; filename=".preg_replace(array("/,/", "/\s/"), array("", "_"), $storyinfo['title']."_"._BY."_".author_list($storyinfo))."-".date("MdY").".doc");
	echo "<html xmlns:o=\"urn:schemas-microsoft-com:office:office\"
xmlns:w=\"urn:schemas-microsoft-com:office:word\"
xmlns=\"http://www.w3.org/TR/REC-html40\">
<head>

<meta http-equiv=Content-Type content=\"text/html; charset="._CHARSET."\">
<style>";
	if(file_exists("$skindir/printword.css")) include("$skindir/printword.css");
	else echo include("default_tpls/printword.css");
echo "</style></head>
<body>";
	include (_BASEDIR."includes/class.TemplatePower.inc.php");
	if(file_exists("$skindir/printstory.tpl")) $tpl = new TemplatePower( "$skindir/printword.tpl" );
	else $tpl = new TemplatePower(_BASEDIR."modules/displayword/default_tpls/printword.tpl");
	include(_BASEDIR."includes/corefunctions.php");
	include(_BASEDIR."includes/pagesetup.php");
	if($storyinfo['coauthors'] == 1) {
		$coauthors = array();
		$coauth = dbquery("SELECT "._PENNAMEFIELD." as penname, co.uid FROM ".TABLEPREFIX."fanfiction_coauthors AS co LEFT JOIN "._AUTHORTABLE." ON co.uid = "._UIDFIELD." WHERE co.sid = '".$sid."'");
		while($c = dbassoc($coauth)) {
			$coauthors[$c['uid']] = $c['penname'];
		}
		$storyinfo['coauthors'] = $coauthors;
		unset($coauthors);
	}
	else $storyinfo['coauthors'] = array();
	$stories = $storyinfo;
	include(_BASEDIR."includes/storyblock.php");
	$tpl->assign("title", stripslashes($storyinfo['title']));
	$tpl->assign("author", author_list($storyinfo));
	if(empty($chapter)) $chapter = "all"; // shouldn't happen but just in case
	unset($stories);
	if($chapter == "all") {
		if($storyinfo['storynotes']) {
			$tpl->newBlock("storynotes");
			$tpl->assign( "storynotes", format_story($storyinfo['storynotes']));
			$tpl->gotoBlock("_ROOT");
		}
		$chapterinfo = dbquery("SELECT *, "._PENNAMEFIELD." as penname FROM (".TABLEPREFIX."fanfiction_chapters as c, "._AUTHORTABLE.") WHERE sid = '$sid' AND c.uid = "._UIDFIELD." ORDER BY inorder");
		while($c = dbassoc($chapterinfo)) {
			$tpl->newBlock("storyindexblock");
			$tpl->assign("chapternumber", $c['inorder']);
			$tpl->assign("title", "<a href='#".$c['inorder']."'>".$c['title']."</a>");
			$tpl->assign("author", $c['penname']);
			$tpl->gotoBlock("_ROOT");
			$tpl->newBlock("chapterblock");
			$tpl->assign("chaptertitle", "<a name='".$c['inorder']."'></a>".$c['title']);
			$tpl->assign("chapterauthor", $c['penname']);
			if($c['notes']) {
				$tpl->newBlock("notes");
				$tpl->assign( "notes", format_story($c['notes']));
				$tpl->gotoBlock("chapterblock");
			}
			if($store == "files") {
				//shouldn't happen, but somehow has on occasion. :(
				if(!$c['uid']) {
					$tpl->assign("output", "<div style='text-align: center;'>"._ERROR."</div>");
					$tpl->printToScreen( );
					exit( );
				}
				$file = _BASEDIR.STORIESPATH."/".$c['uid']."/".$c['chapid'].".txt";
				$log_file = @fopen($file, "r");
				$file_contents = @fread($log_file, filesize($file));
				$story = $file_contents;
				@fclose($log_file);
			}
			else $story = $c['storytext'];
			// The following lines cleans up problems between pre-2.0 stories and 2.0 stories.  If there's html, don't send it through nl2br and then clean up smart quotes.
			$story = format_story($story);
			$tpl->assign("chaptertext", $story);	
			$tpl->assign("back2top", "<a href='#1'>"._BACK2INDEX."</a>");
			if($c['endnotes']) {
				$tpl->newBlock("endnotes");
				$tpl->assign( "endnotes", format_story($c['endnotes']));
				$tpl->gotoBlock("chapterblock");
			}
		}
	}
	else {
		$chapterinfo = dbquery("SELECT *, "._PENNAMEFIELD." as penname FROM (".TABLEPREFIX."fanfiction_chapters as c, "._AUTHORTABLE.") WHERE sid = '$sid' AND inorder = '$chapter' AND c.uid = "._UIDFIELD." LIMIT 1");
		$c = dbassoc($chapterinfo);
		// if the *CHAPTER* hasn't been validated and the viewer isn't an admin or the author throw them a warning.  
		if(empty($c['validated']) && !isADMIN && USERUID != $c['uid'] && !in_array($c['uid'], explode(",", $storyinfo['coauthors']))) {
			$warning = write_error(_ACCESSDENIED);
			$tpl->assign("output", $warning);
			$tpl->printToScreen( );
			exit( );
		}
		if($c['inorder'] == 1 && $storyinfo['storynotes']) {
			$tpl->newBlock("storynotes");
			$tpl->assign( "storynotes", format_story($storyinfo['storynotes']));
			$tpl->gotoBlock("_ROOT");
		}
		$tpl->newBlock("chapterblock");
		$tpl->assign("chaptertitle", $c['title']);
		$tpl->assign("chapterauthor", $c['penname']);
		if($c['notes']) {
			$tpl->newBlock("notes");
			$tpl->assign( "notes", format_story($c['notes']));
			$tpl->gotoBlock("chapterblock");
		}
		//shouldn't happen, but somehow has on occasion. :(
		if($store == "files") {
			if(!$c['uid']) {
				$tpl->assign("output", "<div style='text-align: center;'>"._ERROR."</div>");
				$tpl->printToScreen( );
				exit( );
			}
			$file = _BASEDIR.STORIESPATH."/".$c['uid']."/".$c['chapid'].".txt";
			$log_file = @fopen($file, "r");
			$file_contents = @fread($log_file, filesize($file));
			$story = $file_contents;
			@fclose($log_file);
		}
		else $story = $c['storytext'];
		// The following lines cleans up problems between pre-2.0 stories and 2.0 stories.  If there's html, don't send it through nl2br and then clean up smart quotes.
		$story = format_story($story);
		$tpl->assign("chaptertext", $story);	
	}
	$tpl->gotoBlock("_ROOT");
	$tpl->assign("archivedat", _ARCHIVEDAT." <a href=\"$url/viewstory.php?sid=$sid\">$url/viewstory.php?sid=$sid</a>");
	$copyquery = dbquery("SELECT message_text FROM ".TABLEPREFIX."fanfiction_messages WHERE message_name = 'printercopyright' LIMIT 1");
	list($copyright) = dbrow($copyquery);
	$tpl->assign("copyright", $copyright);
	$tpl->assign("output", $output);
	$tpl->printToScreen( );

}
else {

	if(file_exists(_BASEDIR."languages/{$language}.php")) require_once (_BASEDIR."languages/{$language}.php");
	else require_once (_BASEDIR."languages/en.php");
	die(_ERROR);
}
?>