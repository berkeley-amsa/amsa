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

jimport('joomla.application.component.controller');

class JoomDOCController extends JController {
    function webdav () {
        /* webdav list URL */
        // session id
        $session = JRequest::getString('s');
        // parent folder path
        $path = JoomDOCRequest::getPath();
        if (!$session) {
            /* webdav edit url */
            // file path
            $file = JRequest::getString('file');
            // file path segments
            $segments = explode('/', $file);
            // cleanup
            foreach ($segments as $i => $segment)
                if (!JString::trim($segment))
                    unset($segments[$i]);
            // reindexing
            $segments = array_merge($segments);
            // session id is first file path segment
            $session = $segments[0];
            // remove session id
            unset($segments[0]);
            // file path in JoomDOC standard
            $path = implode(DS, $segments);
            // file path in webdav framework usage (with slash on the begin)
            $file = '/' . implode('/', $segments);
            // set file param in get for webdav framework
            JRequest::setVar('file', $file, 'get');
        }
        if (JoomDOCAccessFileSystem::editWebDav(null, JoomDOCFileSystem::clean($path), $session))
            require_once(JOOMDOC_WEBDAV . DS . 'index.php');
        else
            die('{"error":"","files":""}');
    }
}
?>