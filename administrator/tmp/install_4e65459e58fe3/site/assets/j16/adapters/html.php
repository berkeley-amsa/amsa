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

class J16HTML {

    static $formatOptions = array(
        'format.depth' => 0,
        'format.eol' => "\n",
        'format.indent' => "\t");

    /**
     * Import file with javascript into HTML HEAD.
     *
     * @param string $file relative path or url
     */
    function script ($file, $framework = false, $relative = false) {
        if ($relative) {
            $file = JFile::getName($file);
            if ($file == 'mootools-more.js') {
                return;
            }
            if ($file == 'mootools-core.js') {
                $file = 'mootools.js';
            }
            $file = 'media/system/js/' . $file;
        }
        $document =& JFactory::getDocument();
        /* @var $document JDocumentHTML */
        $document->addScript($file);
    }

    /**
     * Write a <link rel="stylesheet" style="text/css" /> element
     *
     * @access	public
     * @param	string 	The relative URL to use for the href attribute
     * @since	1.5
     */
    function stylesheet ($path, $attribs = array (), $relative = false) {
        if (JString::strpos($path, 'http') !== 0) {
            $path = JURI::root(true) . '/' . $path;
        }
        $document =& JFactory::getDocument();
        /* @var $document JDocumentHTML */
        $document->addStylesheet($path, 'text/css', null, $attribs);
        return;
    }
}
?>