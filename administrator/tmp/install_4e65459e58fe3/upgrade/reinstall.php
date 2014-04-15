<?php

/**
 * @version		$Id$
 * @package		Joomla.Administrator
 * @subpackage	JoomDOC
 * @author      ARTIO s.r.o., info@artio.net, http:://www.artio.net
 * @copyright	Copyright (C) 2011 Artio s.r.o.. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die('Restricted access');

include_once('helper.php');

/* Update component admin */

$target = 'administrator' . DS . 'components' . DS . ARTIO_UPGRADE_OPTION;
$source = 'admin';
ArtioReinstall::addFilesOp($this, $target, $source);

/* Update component manifest */

$source = '';
ArtioReinstall::addFilesOp($this, $target, $source, false);

/* Update component site */

$target = 'components' . DS . ARTIO_UPGRADE_OPTION;
$source = 'site';
ArtioReinstall::addFilesOp($this, $target, $source);

/* Update admin language */

$target = 'administrator' . DS . 'language';
$source = 'language' . DS . 'admin';
ArtioReinstall::addFilesOp($this, $target, $source);

/* Update search plugin */

$target = 'plugins' . DS . 'search' . (J16 ? DS . 'joomdoc' : '');
$source = 'admin' . DS . 'extensions' . DS . 'plg_search_joomdoc';
ArtioReinstall::addFilesOp($this, $target, $source, false);

/* Update editors-xtd plugin */

$target = 'plugins' . DS . 'editors-xtd' . (J16 ? DS . 'joomdoc' : '');
$source = 'admin' . DS . 'extensions' . DS . 'plg_editors-xtd_joomdoc';
ArtioReinstall::addFilesOp($this, $target, $source, false);

/* Check database */

$db =& JFactory::getDbo();
/* @var $db JDatabaseMySQL */
$fields = reset($db->getTableFields('#__joomdoc'));
if (!isset($fields['asset_id'])) {
	// column to asign document with Joomla 1.6 access system
    $this->_addSQL('ALTER TABLE `#__joomdoc` ADD COLUMN `asset_id` INT(11) NULL AFTER `id`');
}
?>