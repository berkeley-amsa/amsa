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

function com_uninstall () {
    JLoader::register('AInstaller', JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_joomdoc' . DS . 'libraries' . DS . 'joomdoc' . DS . 'installer' . DS . 'installer.php');
    AInstaller::uninstall(JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_joomdoc' . DS . 'extensions');
}
?>