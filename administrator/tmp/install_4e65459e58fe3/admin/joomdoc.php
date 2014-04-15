<?php

/**
 * @version		$Id$
 * @package		Joomla.Administrator
 * @subpackage	com_joomdoc
 * @author      ARTIO s.r.o., info@artio.net, http:://www.artio.net
 * @copyright	Copyright (C) 2011 Artio s.r.o. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die();

// import Joomla framework
jimport('joomla.application.component.model');
jimport('joomla.application.component.controller');
jimport('joomla.filesystem.file');

$mainframe =& JFactory::getApplication();
/* @var $mainframe JAdministrator */
$document =& JFactory::getDocument();
/* @var $document JDocumentHTML */
$user =& JFactory::getUser();
/* @var $user JUser */

// import adapter for Joomla 1.6.x
include_once(JPATH_COMPONENT_SITE . DS . 'assets' . DS . 'j16' . DS . 'index.php');
// import component defines constants
include_once(JPATH_COMPONENT_ADMINISTRATOR . DS . 'defines.php');

// register main access class, only Joomla 1.6.x
JLoader::register(JOOMDOC_ACCESS_PREFIX, JOOMDOC_ACCESS . DS . 'joomdoc.php');

// control component access, only Joomla 1.6.x
if (J16 && $mainframe->isAdmin() && !$user->authorise('core.manage', JOOMDOC_OPTION)) {
    return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

// import access helpers	
foreach (JFolder::files(JOOMDOC_ACCESS, '.php', true, true) as $access) {
    JLoader::register(JOOMDOC_ACCESS_PREFIX . str_replace('.php', '', JFile::getName($access)), $access);
}

// import framework helpers
foreach (JFolder::files(JOOMDOC_HELPERS, '.php', true, true, array('.svn', 'CVS', '.DS_Store', '__MACOSX', 'access')) as $helper) {
    JLoader::register(JOOMDOC_HELPER_PREFIX . str_replace('.php', '', JFile::getName($helper)), $helper);
}

// import Joomla javascript frameworks
JHtml::_('behavior.tooltip');
JHtml::_('behavior.mootools');

// import backend language manualy because this file is used from frontend and language is only in backend
$language =& JFactory::getLanguage();
/* @var $language JLanguage */
$language->load(JOOMDOC_OPTION, JPATH_ADMINISTRATOR);

// import CSS and JS assets
$document->addStyleSheet(JOOMDOC_ASSETS . 'css/general.css');
$document->addScript(JOOMDOC_ASSETS . 'js/script.js');
// language constants for javascript
include_once(JPATH_COMPONENT_SITE . DS . 'assets' . DS . 'js' . DS . 'script.php');

$config =& JoomDOCConfig::getInstance();

define('ARTIO_UPGRADE_DOWNLOAD_ID', $config->downloadId);

// add include path for tables and models to access this class from all parts of component and from frontend
JTable::addIncludePath(JOOMDOC_TABLES);
JModel::addIncludePath(JOOMDOC_MODELS);
if (class_exists('JToolBar')) {
    $bar =& JToolBar::getInstance('toolbar');
    /* @var $bar JToolBar */
    $bar->addButtonPath(JOOMDOC_BUTTONS);
}

// start controller
$controller = J16 ? JController::getInstance(JOOMDOC) : J16Controller::getInstance();

if (JOOMDOC_LOG)
    JFactory::getDbo()->debug(1);

$controller->execute(JRequest::getCmd('task'));

if (JOOMDOC_LOG) {
    JFactory::getDbo()->debug(0);
    JoomDOCHelper::showLog();
}

$controller->redirect();

if ($config->displaySignature) {
    echo '<p>' . JText::_('JOOMDOC_SIGNATURE') . '</p>';
}
?>