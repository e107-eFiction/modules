<?php
$current = "challenges";
include ("../../header.php");

//make a new TemplatePower object
if(file_exists( "$skindir/default.tpl")) $tpl = new TemplatePower("$skindir/default.tpl" );
else $tpl = new TemplatePower(_BASEDIR."default_tpls/default.tpl");
$tpl->assignInclude( "header", "$skindir/header.tpl" );
$tpl->assignInclude( "footer", "$skindir/footer.tpl" );
include(_BASEDIR."includes/pagesetup.php");
include_once(_BASEDIR."languages/".$language."_admin.php");
if(file_exists(_BASEDIR."modules/displayword/languages/".$language.".php")) include_once(_BASEDIR."modules/displayword/languages/".$language.".php");
else include_once(_BASEDIR."modules/displayword/languages/en.php");
if(!isADMIN) accessDenied( );
$confirm = isset($_GET['confirm']) ? $_GET['confirm'] : false;
if($confirm == "yes") {
	dbquery("DELETE FROM `".TABLEPREFIX."fanfiction_codeblocks` WHERE code_module = 'displayword'");
	include("version.php");
	dbquery("DELETE FROM `".TABLEPREFIX."fanfiction_modules` WHERE name = '".escapestring($moduleName)."'");
	$output = write_message(_ACTIONSUCCESSFUL);
}
else if($confirm == "no") {
	$output = write_message(_ACTIONCANCELLED);
}
else {
	$modquery = dbquery("SELECT * FROM ".TABLEPREFIX."fanfiction_codeblocks WHERE code_module = 'displayword'");
	if(dbnumrows($modquery)) $output = write_message(_CONFIRMUNINSTALL." "._UNINSTALLWARNING."<br /><a href='uninstall.php?confirm=yes'>"._YES."</a> "._OR." <a href='uninstall.php?confirm=no'>"._NO."</a>");
	else $output .= write_message(_MODNOTINSTALLED);
}
$tpl->assign("output", $output);
$tpl->printToScreen( );
?>