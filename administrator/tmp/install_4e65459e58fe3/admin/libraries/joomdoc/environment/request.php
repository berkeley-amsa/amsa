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

class JoomDOCRequest {
    /**
     * Get path paremeter from request. Test if parameter is relative path or document alias.
     * Test if path is virtual path.
     *
     * @param string $path path to control, if null use param path from request
     * @return string
     */
    public function getPath ($path = null) {
        static $model;
        /* @var $model JoomDOCModelDocument */
        if (empty($model))
            $model =& JModel::getInstance(JOOMDOC_DOCUMENT, JOOMDOC_MODEL_PREFIX);
        static $isSite;
        if (is_null($isSite))
            $isSite = JFactory::getApplication()->isSite();
        // get root path from request or config
        if (!$path)
            $path = JString::trim(JRequest::getString('path'));
        $path = JoomDOCString::urldecode($path);
        if ($isSite) {
            // path from request can be document full alias - search for path in document table
            $candidate = $model->searchRelativePathByFullAlias($path);
            if ($candidate)
                $path = $candidate;
            else
                // if request param is path convert from virtual to full relative path
                $path = JoomDOCFileSystem::getNonVirtualPath($path);
        }
        return $path;
    }
    /**
     * Get array of relative paths from request parameter paths.
     *
     * @return array
     */
    public function getPaths () {
        return JRequest::getVar('paths', array(), 'default', 'array');
    }
}
