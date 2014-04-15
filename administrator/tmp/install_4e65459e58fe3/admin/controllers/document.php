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

jimport('joomla.application.component.controllerform');

class JoomDOCControllerDocument extends JoomDOCControllerForm {

    /**
     * Rename file/folder.
     */
    public function rename () {
        $renamePath = JString::trim(JRequest::getString('renamePath'));
        if (JoomDOCAccessFileSystem::rename(false, $renamePath)) {
            $success = JoomDOCFileSystem::rename($renamePath, JString::trim(JRequest::getString('newName')));
            $this->setRedirect(JoomDOCRoute::viewDocuments(), JText::_($success ? 'JOOMDOC_RENAME_SUCCESS' : 'JOOMDOC_RENAME_FAILED'), $success ? 'message' : 'error');
        } else
            JError::raiseError(403, JText::_('JOOMDOC_UNABLE_RENAME'));
    }
}
?>