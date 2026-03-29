<?php
// ----------------------------------------------------------------------
// Copyright (c) 2007 by Tammy Keefer
// Valid HTML 4.01 Transitional
// Based on eFiction 1.1
// Copyright (C) 2003 by Rebecca Smallwood.
// http://efiction.sourceforge.net/
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

if($current == "viewstory") {
	if(file_exists(_BASEDIR."modules/displayword/languages/{$language}.php")) include_once(_BASEDIR."modules/displayword/languages/{$language}.php");
	else include_once(_BASEDIR."modules/displayword/languages/en.php");
	if(!empty($chapter) && $chapters > 1) $printword = "<img src='".(isset($wordicon) ? $wordicon : "modules/displayword/images/doc.gif")."' border='0' alt='"._MSWORD."'> <a href=\"modules/displayword/displayword.php?sid=$sid&amp;chapter=$chapter\">"._CHAPTER."</a> "._OR." <a href=\"modules/displayword/displayword.php?sid=$sid&amp;chapter=all\">"._STORY."</a>";
	else $printword = "<a href=\"modules/displayword/displayword.php?sid=$sid&amp;textsize=$textsize&amp;chapter=1\"><img src='".(isset($wordicon) ? $wordicon : "modules/displayword/images/doc.gif")."' border='0' alt='"._MSWORD."'></a>";
	$tpl->assign("printword", $printword);
}

?>