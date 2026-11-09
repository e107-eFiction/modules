<?php
$current = "";
// Checks that the given $num is actually a number.  Used to help prevent XSS attacks.
function isNum($num) {
	if(empty($num)) return false;
	if(!is_string($num)) return false;
	return preg_match("/^[0-9]+$/", $num);
}

// Same with the author list ($stories['coauthors_array'] is uid => penname, loaded below;
// the coauthors column is only a 0/1 flag, it does not hold the co-authors' ids)
function author_list($stories) {
	$authlink = array($stories['penname']);
	if(!empty($stories['coauthors_array']) && is_array($stories['coauthors_array'])) {
		foreach($stories['coauthors_array'] as $copenname) {
			$authlink[] = $copenname;
		}
	}
	return implode(", ", $authlink);
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
// since 3.5.8 config.php no longer loads the database functions (header.php does)
if(!function_exists("dbquery")) include_once(_BASEDIR."includes/dbfunctions.php");
$output = "";


$settingsresults = dbquery("SELECT * FROM ".$settingsprefix."fanfiction_settings WHERE sitekey = '".$sitekey."'");
$settings = dbassoc($settingsresults);
if(!is_array($settings)) die("Error");
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

// a skin name is only used when it is a plain folder name in skins/ (as ef_valid_skin() in the core)
$skinok = function($name) { return is_string($name) && $name !== "" && basename($name) === $name && is_dir(_BASEDIR."skins/".$name); };
if(isset($_SESSION[$sitekey."_skin"]) && $skinok($_SESSION[$sitekey."_skin"])) $skin = $_SESSION[$sitekey."_skin"];

if(isset($_GET['skin']) && $skinok($_GET['skin'])) {
	$skin = $_GET['skin'];
	$_SESSION[$sitekey."_skin"] = $skin;
}

// Defines the character set for your language/location
if (!defined('_CHARSET')) {
    define('_CHARSET', 'utf-8');
}

if($skinok($skin)) $skindir = _BASEDIR."skins/$skin";
else $skindir = _BASEDIR."default_tpls";
if(file_exists(_BASEDIR."languages/{$language}.php")) require_once (_BASEDIR."languages/{$language}.php");
else require_once (_BASEDIR."languages/en.php");

$storyinfo = false;
// the site is closed for maintenance: only admins may read, and this script always runs as a visitor
if(!empty($sid) && empty($maintenance)) {
	$storyquery = dbquery(_STORYQUERY." AND sid = '$sid' LIMIT 1");
	$storyinfo = dbassoc($storyquery);
}
if($storyinfo && !empty($storyinfo['rid'])) {
	// the same rating checks as viewstory.php, for a visitor: members-only ratings are refused, the age
	// consent and the rating warning must have been accepted on the site in this session
	$rating = dbassoc(dbquery("SELECT ratingwarning FROM ".TABLEPREFIX."fanfiction_ratings WHERE rid = '".(int)$storyinfo['rid']."' LIMIT 1"));
	$warninglevel = sprintf("%03b", $rating ? (int)$rating['ratingwarning'] : 0);
	if($warninglevel[0] || ($warninglevel[1] && empty($_SESSION[$sitekey."_ageconsent"])) || ($warninglevel[2] && empty($_SESSION[$sitekey."_warned"][$storyinfo['rid']])))
		$storyinfo = false;
}
if($storyinfo) {
	$storyinfo['coauthors_array'] = array( );
	if(!empty($storyinfo['coauthors'])) {
		$coauth = dbquery("SELECT "._PENNAMEFIELD." as penname, co.uid FROM ".TABLEPREFIX."fanfiction_coauthors AS co LEFT JOIN "._AUTHORTABLE." ON co.uid = "._UIDFIELD." WHERE co.sid = '".(int)$sid."'");
		while($c = dbassoc($coauth)) {
			$storyinfo['coauthors_array'][$c['uid']] = $c['penname'];
		}
	}
}
if($storyinfo) {
	header("Expires: Mon, 26 Jul 2001 05:00:00 GMT");
	header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
	header("Cache-Control: no-store,no-cache,must-revalidate");
	header("Cache-Control: post-check=0,pre-check=0", false);
	header("Pragma: no-cache");
	header("Cache-control: private");
	header("Content-type: application/msword");
	// no quotes, path separators or control characters in the file name
	$docname = preg_replace(array('/[\x00-\x1F\x7F"\\\\\/:;*?<>|,]/', '/\s+/'), array('', '_'), strip_tags(stripslashes($storyinfo['title']))."_"._BY."_".author_list($storyinfo));
	header("Content-Disposition: attachment; filename=\"".$docname."-".date("MdY").".doc\"");
	echo "<html xmlns:o=\"urn:schemas-microsoft-com:office:office\"
xmlns:w=\"urn:schemas-microsoft-com:office:word\"
xmlns=\"http://www.w3.org/TR/REC-html40\">
<head>

<meta http-equiv=Content-Type content=\"text/html; charset="._CHARSET."\">
<style>";
	// a stylesheet is printed as it is, never run as PHP
	if(file_exists("$skindir/printword.css")) readfile("$skindir/printword.css");
	else readfile(_BASEDIR."modules/displayword/default_tpls/printword.css");
echo "</style></head>
<body>";
	include (_BASEDIR."includes/class.TemplatePower.inc.php");
	if(file_exists("$skindir/printword.tpl")) $tpl = new TemplatePower( "$skindir/printword.tpl" );
	else $tpl = new TemplatePower(_BASEDIR."modules/displayword/default_tpls/printword.tpl");
	include(_BASEDIR."includes/corefunctions.php");
	include(_BASEDIR."includes/pagesetup.php");
	// coauthors stays the 0/1 flag: includes/storyblock.php reads it as one and loads the co-authors itself
	$stories = $storyinfo;
	include(_BASEDIR."includes/storyblock.php");
	$tpl->assign("title", stripslashes($storyinfo['title']));
	$tpl->assign("author", author_list($storyinfo));
	if(empty($chapter)) $chapter = "all"; // shouldn't happen but just in case
	unset($stories);
	if($chapter == "all") {
		if(!empty($storyinfo['storynotes'])) {
			$tpl->newBlock("storynotes");
			$tpl->assign( "storynotes", format_story($storyinfo['storynotes']));
			$tpl->gotoBlock("_ROOT");
		}
		$chapterinfo = dbquery("SELECT *, "._PENNAMEFIELD." as penname FROM (".TABLEPREFIX."fanfiction_chapters as c, "._AUTHORTABLE.") WHERE sid = '$sid' AND c.uid = "._UIDFIELD." AND c.validated > 0 ORDER BY inorder");
		while($c = dbassoc($chapterinfo)) {
			$tpl->newBlock("storyindexblock");
			$tpl->assign("chapternumber", $c['inorder']);
			$tpl->assign("title", "<a href='#".$c['inorder']."'>".$c['title']."</a>");
			$tpl->assign("author", $c['penname']);
			$tpl->gotoBlock("_ROOT");
			$tpl->newBlock("chapterblock");
			$tpl->assign("chaptertitle", "<a name='".$c['inorder']."'></a>".$c['title']);
			$tpl->assign("chapterauthor", $c['penname']);
			if(!empty($c['notes'])) {
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
				$story = is_file($file) ? (string)@file_get_contents($file) : "";
			}
			else $story = $c['storytext'];
			// The following lines cleans up problems between pre-2.0 stories and 2.0 stories.  If there's html, don't send it through nl2br and then clean up smart quotes.
			$story = format_story($story);
			$tpl->assign("chaptertext", $story);	
			$tpl->assign("back2top", "<a href='#1'>"._BACK2INDEX."</a>");
			if(!empty($c['endnotes'])) {
				$tpl->newBlock("endnotes");
				$tpl->assign( "endnotes", format_story($c['endnotes']));
				$tpl->gotoBlock("chapterblock");
			}
		}
	}
	else {
		$chapterinfo = dbquery("SELECT *, "._PENNAMEFIELD." as penname FROM (".TABLEPREFIX."fanfiction_chapters as c, "._AUTHORTABLE.") WHERE sid = '$sid' AND inorder = '$chapter' AND c.uid = "._UIDFIELD." LIMIT 1");
		$c = dbassoc($chapterinfo);
		// a missing chapter, or one that hasn't been validated: this script always runs as a visitor
		// (USERUID 0), so nobody gets an unvalidated chapter here
		if(!$c || empty($c['validated'])) {
			// printword.tpl has no {output}, so the message is printed into the document itself
			echo write_error($c ? _ACCESSDENIED : _ERROR)."</body></html>";
			exit( );
		}
		if($c['inorder'] == 1 && !empty($storyinfo['storynotes'])) {
			$tpl->newBlock("storynotes");
			$tpl->assign( "storynotes", format_story($storyinfo['storynotes']));
			$tpl->gotoBlock("_ROOT");
		}
		$tpl->newBlock("chapterblock");
		$tpl->assign("chaptertitle", $c['title']);
		$tpl->assign("chapterauthor", $c['penname']);
		if(!empty($c['notes'])) {
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
			$story = is_file($file) ? (string)@file_get_contents($file) : "";
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