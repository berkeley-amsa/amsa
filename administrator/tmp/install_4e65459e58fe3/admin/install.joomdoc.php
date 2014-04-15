<?php

/**
 * @version		$Id$
 * @package		Joomla.Administrator
 * @subpackage	JoomDOC
 * @author      ARTIO s.r.o., info@artio.net, http:://www.artio.net
 * @copyright	Copyright (C) 2011 Artio s.r.o.. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die();

function com_install () {
    // install component extensions
    JLoader::register('AInstaller', JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_joomdoc' . DS . 'libraries' . DS . 'joomdoc' . DS . 'installer' . DS . 'installer.php');
    AInstaller::install(JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_joomdoc' . DS . 'extensions');
    // load component language
    JFactory::getLanguage()->load('com_joomdoc', JPATH_ADMINISTRATOR);

    /* Check database */

    $db =& JFactory::getDbo();
    /* @var $db JDatabaseMySQL */
    $fields = reset($db->getTableFields('#__joomdoc'));
    if (!isset($fields['asset_id'])) {
        // column to asign document with Joomla 1.6 access system
        $this->_addSQL('ALTER TABLE `#__joomdoc` ADD COLUMN `asset_id` INT(11) NULL AFTER `id`');
    }

    echo '<h2 style="color: rgb(183, 207, 95); margin: 0pt; padding: 15px;">' . JText::_('JOOMDOC') . '</h2>';
    echo '<img style="float: left; margin-left: 15px; margin-right: 10px; margin-bottom: 10px;" src="' . JURI::root() . 'components/com_joomdoc/assets/images/' . 'icon-48-joomdoc.png' . '" alt="JoomDOC logo" />';
    echo '<div style="width: 50em; margin: 0pt; padding: 0.5em;">';
    echo '<p>' . JText::_('JOOMDOC_DESC') . '</p>';
    echo '<p>' . JText::_('JOOMDOC_INSTALL_INFO') . '</p>';
    echo '<p><a style="font-weight: bold; color: #B7CF5F; font-size: 1.1em;" href="' . JRoute::_('index.php?option=com_joomdoc') . '" title="">' . JText::_('JOOMDOC_OPEN') . '</a></p>';
    echo '</div>';
}
?>