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
$current = "storyend";
if(isset($_GET['action']) && ($_GET['action'] == "add" || $_GET['action'] == "edit")) $displayform = 1;

if(file_exists(_BASEDIR."languages/{$language}_admin.php")) include_once(_BASEDIR."languages/{$language}_admin.php");
else include_once(_BASEDIR."languages/en_admin.php");
if(file_exists(_BASEDIR."modules/storyend/languages/{$language}.php")) include_once(_BASEDIR."modules/storyend/languages/{$language}.php");
else include_once(_BASEDIR. "modules/storyend/languages/en.php");

 
 
$output = "<div id='pagetitle'>"._STORYENDADMIN."</div>";
if(!isADMIN) accessDenied( );
 
if(isset($_POST['submit'])) {
 
	$dbdata =  serialize($_POST['storyend']);
	$dbdata = addslashes($dbdata); 
	$result = dbquery("UPDATE ".$settingsprefix."fanfiction_settings SET storyend = '$dbdata' WHERE sitekey = '".SITEKEY."'");
	if($result) $output .= write_message(_ACTIONSUCCESSFUL);
	else $output .= write_error(_ERROR);
}
else {
 
	$tmp =  unserialize($storyend);

	$display_authorinfo = isset($tmp['display_authorinfo']) ? $tmp['display_authorinfo'] : "";
	$display_seriesinfo = isset($tmp['display_seriesinfo']) ? $tmp['display_seriesinfo'] : "";
	$display_favouritesinfo = isset($tmp['display_favouritesinfo']) ? $tmp['display_favouritesinfo'] : "";
 

	$output .= 
	"<div  id=\"settingsform\" style=\"width: 99%\">
	  	<form method=\"POST\" style=\"margin: 1em auto;\" enctype=\"multipart/form-data\"  action=\"admin.php?action=modules&amp;admin=true&amp;module=storyend\">";
	$output .= "<div style='margin-bottom: 1em'>
	        		<label for=\"storyend[display_authorinfo]\">Display Author Info: </label> 
					<select name=\"storyend[display_authorinfo]\">
						<option value=\"1\"".($display_authorinfo == "1" ? " selected" : "").">"._YES."</option>
						<option value=\"0\"".($display_authorinfo == "0" ? " selected" : "").">"._NO. "</option>
					</select>
				</div>
				<div style='margin-bottom: 1em'>
	        		<label for=\"storyend[display_authorinfo]\">Display Series Info: </label> 
					<select name=\"storyend[display_seriesinfo]\">
						<option value=\"1\"" . ($display_seriesinfo == "1" ? " selected" : "") . ">" . _YES . "</option>
						<option value=\"0\"" . ($display_seriesinfo == "0" ? " selected" : "") . ">" . _NO . "</option>
					</select>
				</div>
				
				 <div style='margin-bottom: 1em'>
	        		<label for=\"storyend[display_favouritesinfo]\">Display Favourites: </label> 
			 		<select name=\"storyend[display_favouritesinfo]\">
						<option value=\"1\"" . ($display_favouritesinfo == "1" ? " selected" : "") . ">" . _YES . "</option>
						<option value=\"0\"" . ($display_favouritesinfo == "0" ? " selected" : "") . ">" . _NO . "</option>
					</select>
				</div>
				
				 ";
	$output .= "<div id='submitdiv'>
					<input type=\"submit\" id=\"submit\" class=\"button\" name=\"submit\" value=\""._SUBMIT."\">
				</div>
				</form> 
	</div>";
	 
}
?>
