<?php
/**
* @package   com_zoo
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

if (!class_exists('App')) {

	// init vars
	$path = dirname(__FILE__);

	// load imports
	jimport('joomla.filesystem.file');
	jimport('joomla.filesystem.folder');
	jimport('joomla.filesystem.path');
	jimport('joomla.application.component.model');
	jimport('joomla.application.component.view');
	jimport('joomla.application.component.controller');
	jimport('joomla.user.helper');
	jimport('joomla.mail.helper');

	// load classes
	require_once($path.'/classes/app.php');
	require_once($path.'/classes/controller.php');
	require_once($path.'/classes/helper.php');
	require_once($path.'/classes/view.php');
	require_once($path.'/helpers/component.php');
	require_once($path.'/helpers/path.php');

}