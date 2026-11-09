<?php
// ----------------------------------------------------------------------
// Copyright (c) 2007 by Tammy Keefer
// Also Like Module developed for eFiction 3.0
// // http://efiction.hugosnebula.com/
// ----------------------------------------------------------------------
// LICENSE
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License (GPL)
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------

 
if(!defined("_CHARSET")) exit( );

if(file_exists(_BASEDIR."modules/storyend/languages/{$language}.php")) include_once(_BASEDIR."modules/storyend/languages/{$language}.php");
else include_once(_BASEDIR."modules/storyend/languages/en.php");
include_once(_BASEDIR."modules/storyend/functions.php");
 
if(file_exists("./$skindir/storyend.tpl")) $storyendcode = new TemplatePower("./$skindir/storyend.tpl");
else $storyendcode = new TemplatePower("./modules/storyend/default_tpls/storyend.tpl");


$storyendcode->prepare( );
$storyendcode->assign("theend", !empty($storyinfo['completed']) ? _THEEND : _THEENDMAYBE);


/* module settings: $storyend is the fanfiction_settings.storyend column (header.php turns
   every settings column into a global). It is only read here, never overwritten. */
$storyend_options = storyend_options(isset($storyend) ? $storyend : "");
 
if($storyend_options['display_authorinfo']) {

	$authors = array((int)$storyinfo['uid']);
	if (!empty($storyinfo['coauthors']))
	{
		// e107-eFiction core (3.5.8+): viewstory.php loads the co-authors into coauthors_array (keyed by uid).
		if(isset($storyinfo['coauthors_array']) && is_array($storyinfo['coauthors_array'])) {
			foreach($storyinfo['coauthors_array'] AS $c=>$coauth)
			{
				$authors[] = (int)$c;
			}
		}
		// Official core: no coauthors_array, so read them from the co-authors table.
		else {
			$coauth = dbquery("SELECT uid FROM ".TABLEPREFIX."fanfiction_coauthors WHERE sid = '".(int)$storyinfo['sid']."'");
			while($c = dbassoc($coauth)) {
				$authors[] = (int)$c['uid'];
			}
		}
	}
	$authors = array_unique($authors);

	// The author row is matched on the author table, so an author without an authorprefs row is listed too.
	if(count($authors) > 1) $cond = "FIND_IN_SET("._UIDFIELD.", '".implode(",", $authors)."') > 0";
	else $cond = _UIDFIELD." = '".$authors[0]."'";

	$aquery = dbquery("SELECT "._PENNAMEFIELD." as penname, "._UIDFIELD." as uid, ap.stories FROM "._AUTHORTABLE." 
	LEFT JOIN ".TABLEPREFIX."fanfiction_authorprefs AS ap on "._UIDFIELD." = ap.uid WHERE $cond");
	
	$alist = array( );
	while($a2 = dbassoc($aquery)) {
		$alist[] = sprintf(_AUTHORCOUNT, "<a href='viewuser.php?uid=".(int)$a2['uid']."'>".storyend_html($a2['penname'])."</a>", max(0, (int)$a2['stories'] - 1));
	}

	$storyendcode->assign("authorcount", implode("<br />", $alist));
}

if ($storyend_options['display_favouritesinfo'])
{
	$favquery = dbquery("SELECT uid FROM ".TABLEPREFIX."fanfiction_favorites WHERE item = '".(int)$storyinfo['sid']."' AND type = 'ST'");
	$favcount = dbnumrows($favquery);
	$authlist = array( );
	if($favcount > 0) {
		while($favau = dbassoc($favquery)) {
			$authlist[] = (int)$favau['uid'];
		}
		$authlist = implode(",", $authlist);
		$storyendcode->assign("favof", sprintf(_SE_FAVOF, $favcount));
		$other = dbquery("SELECT item FROM ".TABLEPREFIX."fanfiction_favorites WHERE type = 'ST' AND FIND_IN_SET(uid, '$authlist') > 0 AND item != '".(int)$storyinfo['sid']."' GROUP BY item");
		$othercount = dbnumrows($other);
		$storyendcode->assign("alsolike", sprintf(_SE_ALSOLIKE, title_link($storyinfo), $storyinfo['sid'], $othercount));
	}
}

if ($storyend_options['display_seriesinfo'])
{
	// If we've got series links for this story, let's give them links to the previous and next stories in the series.
	// This file is eval()'d inside viewstory.php, where $story holds the chapter text: the sibling story is $sibling.
	$seriesquery = "SELECT series.title, series.seriesid, list.inorder FROM ".TABLEPREFIX."fanfiction_inseries as list, ".TABLEPREFIX."fanfiction_series as series WHERE list.sid = '".(int)$storyinfo['sid']."' AND series.seriesid = list.seriesid";
	$seriesresults = dbquery($seriesquery);
	$seSeries = array( );
	while($series = dbassoc($seriesresults)) {
		$thisSeries = sprintf(_SE_INSERIES, "<a href='"._BASEDIR."viewseries.php?seriesid=".(int)$series['seriesid']."'>".storyend_html(strip_tags(stripslashes($series['title'])))."</a>");
		$others = dbquery("SELECT subseriesid, sid, inorder FROM ".TABLEPREFIX."fanfiction_inseries WHERE seriesid = '".(int)$series['seriesid']."' AND confirmed = 1 AND (inorder = ".((int)$series['inorder'] - 1)." OR inorder = ".((int)$series['inorder'] + 1).")"); 
		while($o = dbassoc($others)) {
			$sibling = false;
			$sub = false;
			if(!empty($o['sid'])) $sibling = dbassoc(dbquery(_STORYQUERY." AND sid = ".(int)$o['sid']." LIMIT 1"));
			elseif(!empty($o['subseriesid'])) $sub = dbassoc(dbquery(_SERIESQUERY." AND seriesid = ".(int)$o['subseriesid']));
			
			if($o['inorder'] < $series['inorder']) {
				if($sibling) $thisSeries .= " ".sprintf(_SE_SERIES_PREVST, title_link($sibling));
				elseif($sub) $thisSeries .= " ".sprintf(_SE_SERIES_PREVSE, "<a href='"._BASEDIR."viewseries.php?seriesid=".(int)$sub['seriesid']."'>".storyend_html(strip_tags(stripslashes($sub['title'])))."</a>");
			}
			elseif ($o['inorder'] > $series['inorder']) {
				if($sibling) $thisSeries .= " ".sprintf(_SE_SERIES_NEXTST, title_link($sibling));
				elseif($sub) $thisSeries .= " ".sprintf(_SE_SERIES_NEXTSE, "<a href='"._BASEDIR."viewseries.php?seriesid=".(int)$sub['seriesid']."'>".storyend_html(strip_tags(stripslashes($sub['title'])))."</a>");
			}
			
		}  
		$seSeries[] = $thisSeries;
	}
	if(count($seSeries) > 0) $storyendcode->assign("seriesinfo", implode("<br />", $seSeries));
}	

$tpl->assign("storyend", $storyendcode->getOutputContent( ));

?>
