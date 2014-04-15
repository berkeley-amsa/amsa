<?php
/**
* @version		1.5 (J16)
* @author		Michael A. Gilkes (jaido7@yahoo.com)
* @copyright	Michael Albert Gilkes
* @license		GNU/GPLv2
*/
/*

Easy Folder Listing Pro Plugin for Joomla! 1.6+
Copyright (C) 2010-2011  Michael Albert Gilkes

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

*/


// Set flag that this is a parent file
/*define( '_JEXEC', 1 );
define( 'DS', DIRECTORY_SEPARATOR );

define('JPATH_BASE', $_POST['JPATH_BASE'] );

require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );

// Instantiate the application.
$app = JFactory::getApplication('site');

// Initialise the application.
$app->initialise();

// Route the application.
$app->route();

jimport('joomla.environment.uri');

//get the hosts name
$host = JURI::root();
*/

//get the full path
$fullpath = $_POST['href'];

//spit it into the path and the filename
$filename = basename($fullpath);

echo $_POST['base']."download.php?filename=".$filename."&fullpath=".$fullpath;
//exit(0);