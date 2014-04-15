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

jimport('joomla.application.component.controlleradmin');

class JoomDOCControllerDocuments extends JoomDOCControllerAdmin {

    /**
     * Get document model.
     *
     * @return JoomDOCModelDocument
     */
    public function getModel () {
        return JModel::getInstance(JOOMDOC_DOCUMENT, JOOMDOC_MODEL_PREFIX);
    }

    /**
     * Upload file from request in current folder.
     *
     * @return void
     */
    public function uploadFile () {
        JoomDOCFileSystem::upload();
    }

    /**
     * Delete document.
     */
    public function delete () {
        // set document ID into request
        JRequest::setVar('cid', array($this->getModel()->searchIdByPath(JoomDOCRequest::getPath())));
        // move token from GET into POST
        JRequest::setVar(JRequest::getVar('token', '', 'get', 'string'), 1, 'post');
        parent::delete();
        $this->setRedirect(JoomDOCRoute::viewDocuments(JoomDOCFileSystem::getParentPath(JoomDOCRequest::getPath()), false));
    }
}
?>