<?php
/**
* Pctech File Base Reader is a Free Joomla module to list files in a specific directory and subdirectories.
*
*
* @package pctech_modules
* @subpackage pctechfilebasereader
* @version $Id: mod_pctechfilebasereader.php 1.6 2011-01-21 23:25:54Z pctech $
* @copyright Marcel Wuersch
* @license GNU/GPLv2
* @author Marcel Wuersch <info@pcte.ch>
*/

defined( '_JEXEC' ) or die('Restricted access.');

// Include the syndicate functions only once
require_once( dirname(__FILE__).DS.'helper.php' );

try {
	// Run the helper to generate the file list.
	$filelist = modPctechFilebaseReaderFilesHelper::getFileList( $params );

	// Load the view to render the html for the file list.
	$document =& JFactory::getDocument();
	$user =& JFactory::getUser();
	if ($params->get('ossystem') == 'lin') {
	$document->addStyleSheet(JURI::base() . "/modules/mod_pctechfilebasereader/css/mod_pctechfilebasereader.css");
	} else {
	$document->addStyleSheet(JURI::base() . "modules/mod_pctechfilebasereader/css/mod_pctechfilebasereaderwin.css");
	}
	require JModuleHelper::getLayoutPath('mod_pctechfilebasereader', $params->get('layout', 'default'));
} catch (Exception $e) {
	echo JText::_("MOD_PCTECHFILEBASEREADER_ERROR") . ' ' . $e->getMessage();
}