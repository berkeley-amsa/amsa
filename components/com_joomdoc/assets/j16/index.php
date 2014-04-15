<?php

/**
 * @version		$Id$
 * @package		Joomla
 * @subpackage	Joomla 1.6.x adapter
 * @author      ARTIO s.r.o., info@artio.net, http:://www.artio.net
 * @copyright	Copyright (C) 2011 Artio s.r.o. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die();

if (!isset($importLibraries)) {
    $importLibraries = true;
}

// information for component that environment is genuine Joomla 1.6.x
define('J16', (JString::strpos(JVERSION, '1.6.') === 0 || JString::strpos(JVERSION, '1.7.') === 0));
// tag is used in language constants
define('JTAG', J16 ? 'J16' : 'J15');

if (!J16) {

    /* create simulation of Joomla 1.6.x environment on Joomla 1.5.x */

    $document =& JFactory::getDocument();
    /* @var $document JDocument */

    jimport('joomla.filesystem.file');

    // Joomla 1.6.x libraries
    if ($importLibraries) {
        $jlibraries = JFolder::files(JPATH_ROOT . DS . 'components' . DS . 'com_joomdoc' . DS . 'assets' . DS . 'j16' . DS . 'libraries', '.php', true, true);
    } else {
        $jlibraries = array();
    }
    // Adapters classes to make bridge between Joomla 1.6.x and Joomla 1.5.x
    $adapters = JFolder::files(JPATH_ROOT . DS . 'components' . DS . 'com_joomdoc' . DS . 'assets' . DS . 'j16' . DS . 'adapters', '.php', true, true);

    $libraries = array_merge($jlibraries, $adapters);
    // register all classes in auto load
    foreach ($libraries as $fullpath) {
        // search class name
        $subject = JFile::read($fullpath);
        $pattern = '#class (J[0-9,a-z,A-Z]*)#i';
        $matches = array();
        if (preg_match_all($pattern, $subject, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $className = $match[1];
                // register class name and full path to file in auto load
                JLoader::register($className, $fullpath);
            }
        }
    }

    if ($importLibraries) {
        $js = JFolder::files(JPATH_COMPONENT_SITE . DS . 'assets' . DS . 'j16' . DS . 'media', '.js', true, true);
        foreach ($js as $fullpath) {
            // get relative path from Joomla root
            $relativepath = str_replace(JPATH_ROOT, '', $fullpath);
            // convert backslashes into slashes
            $relativepath = JPath::clean($relativepath, '/');
            // add URL path
            $relativepath = JURI::root(true) . $relativepath;
            $document->addScript($relativepath);
        }
    }
}
?>