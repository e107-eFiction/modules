// ----------------------------------------------------------------------
// Copyright (c) 2007 by Tammy Keefer
// Display Word Module developed for eFiction 3.0
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

To install this module:

1. Upload the entire displayword folder to the the modules folder within your 
eFiction installation.

2. Go to http://www.yoursite.com/modules/displayword/install.php where 
www.yoursite.com is your eFiction site's address. Or alternatively, go to
the Modules admin panel and click install.

3. Goto the main default_tpls folder (not the one inside the displayword
folder).  Open up viewstory.tpl and storyindex.tpl and add {printword}
where you want the MS Word icon/options to appear. 

4. Do the same for any skins with their own viewstory.tpl and/or storyindex.tpl

To uninsall this module:

1. Go to http://www.yoursite.com/modules/displayword/uninstall.php where 
www.yoursite.com is your eFiction site's address. Or alternatively, go to the
Modules admin panel and click uninstall for this module.

You may customize the look of the .doc file using the .tpl and .css files
in the default_tpls folder in the displayword folder.  Just realize that MS
Word does not handle HTML well.  Just as with other .tpls, any of your skins
can have customized MS Word layouts.  Just copy the printword.css and 
printword.tpl file from the default_tpls folder to your skins folder and
make any changes you wish.

DO NOT move the items inside the default_tpls folder.  The script will look
for them where they're at. 

You can define $wordicon in your skin's variables.php to override the 
default Word icon if you so desire.

There is one language definition in the en.php file in the languages folder
that provides the alt text for the MS Word icon.
