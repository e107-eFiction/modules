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

define("_SE_FAVOF", "Počet členov, ktorým sa páči táto poviedka: %d");
define("_SE_ALSOLIKE", "Členom, ktorým sa páči %1\$s, sa tiež páči <a href='"._BASEDIR."browse.php?type=alsolike&sid=%2\$d'>%3\$d iných poviedok</a>.");
define("_SE_BROWSE", "Členovia, ktorým sa páči %s, majú v obľúbených aj tieto poviedky.");
define("_THEEND", "KONIEC.");
define("_THEENDMAYBE", "Pokračovanie nabudúce...");
define("_AUTHORCOUNT", "%s je autorom %d iných poviedok.");
define("_AL_BROWSE", "Členom, ktorým sa páčila %s, sa tiež páčilo");
define("_SE_INSERIES", "Táto poviedka je súčasťou série, %s.");
define("_SE_SERIES_PREVST", "Predchádzajúca poviedka v sérii: %s.");
define("_SE_SERIES_NEXTST", "Nasledujúca poviedka v sérii: %s.");
define("_SE_SERIES_PREVSE", "Predchádzajúca séria v tejto sérii: %s.");
define("_SE_SERIES_NEXTSE", "Nasledujúca séria v tejto sérii: %s.");

if(!defined("_UNINSTALLWARNING")) {
    define("_UNINSTALLWARNING", "<strong>Warning!</strong> No data will be deleted from the database!");
}

if (!defined("_STORYENDADMIN"))
{
    define("_STORYENDADMIN", "<strong>Module Administration</strong> Display settings ");
}
