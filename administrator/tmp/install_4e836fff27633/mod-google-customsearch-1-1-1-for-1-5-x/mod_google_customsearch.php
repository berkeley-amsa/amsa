<?php
/*
	Joomler!.net Google Custom Search Module - version 1.1.1 for 1.6.x
______________________________________________________________________
	License http://www.gnu.org/copyleft/gpl.html GNU/GPL
	Copyright(c) 2008 Joomler!.net All Rights Reserved.
	Comments & suggestions: http://www.joomler.net/
*/
defined('_JEXEC') or die('Restricted access');

require_once (dirname(__FILE__).DS.'helper.php');

$Jm_gws = GWebSearch::execute($fields);
require(JModuleHelper::getLayoutPath('mod_google_customsearch'));

?>