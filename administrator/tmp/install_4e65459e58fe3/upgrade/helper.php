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

class ArtioReinstall {

    function addFilesOp (&$instance, $target, $source, $recursive = true) {
        $upgraded = array();
        if (($directory = dir(($dir = realpath(dirname(__FILE__) . DS . '..' . DS . $source)))))
            while (($filename = $directory->read()) !== false)
                if (strpos($filename, '.') !== 0) {
                    if (is_file(($filepath = $dir . DS . $filename))) {
                        $instance->_addFileOp(DS . $target . DS . $filename, 'upgrade', DS . $source . DS . $filename);
                        $upgraded[] = $filename;
                    } elseif (is_dir($filepath) && $recursive)
                        ArtioReinstall::addFilesOp($instance, $target . DS . $filename, $source . DS . $filename);
                }
        /*
        if (($directory = dir(($dir = JPATH_ROOT . DS . $target))))
            while (($filename = $directory->read()) !== false)
                if (is_file(($filepath = $dir . DS . $filename)) && !in_array($filename, $upgraded)) {
                    $instance->_addFileOp(DS . $target . DS . $filename, 'delete');
                }
        */
    }
}
?>