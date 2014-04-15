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

class JoomDOCViewFile extends JoomDOCView {
    /**
     * List of file versions
     *
     * @var array
     */
    var $data;
    /**
     * Browse table filter
     *
     * @var JObject
     */
    var $filter;
    /**
     * File last version document.
     *
     * @var stdClass
     */
    var $document;

    /**
     * Display browse table of file versions with extended filter.
     *
     * @param string $tpl used template
     */
    public function display ($tpl = null) {
        $mainframe = JFactory::getApplication();
        /* @var $mainframe JAdministrator */
        $modelFile =& $this->getModel();
        /* @var $modelFile JoomDOCModelFile */
        $this->filter = new JObject();
        $this->filter->path = JRequest::getString('path');
        $sprefix = 'joomdoc_file_version_list_' . $this->filter->path . '_';
        $this->filter->offset = $mainframe->getUserStateFromRequest($sprefix . 'offset', 'limitstart', 0, 'int');
        $this->filter->limit = $mainframe->getUserStateFromRequest($sprefix . 'limit', 'limit', 10, 'int');
        $this->filter->listOrder = $mainframe->getUserStateFromRequest($sprefix . 'listOrder', 'filter_order', 'version', 'string');
        $this->filter->listDirn = $mainframe->getUserStateFromRequest($sprefix . 'listDirn', 'filter_order_Dir', 'asc', 'string');
        $this->filter->uploader = $mainframe->getUserStateFromRequest($sprefix . 'uploader', 'uploader', '', 'string');
        $this->data =& $modelFile->getData($this->filter);
        $this->document =& $modelFile->getDocument($this->filter);
        if (!JoomDOCAccessFileSystem::viewFileInfo($this->document ? $this->document->id : null, $this->filter->path)) {
            JError::raiseError(403, JText::sprintf('JOOMDOC_VIEW_FILE_INFO_NOT_ALLOW'));
        }
        $this->addToolbar();
        parent::display($tpl);
    }

    /**
     * Add page main toolbar.
     *
     * @return void
     */
    protected function addToolbar () {
        JToolBarHelper::title(JText::sprintf('JOOMDOC_FILE_PATH', $this->filter->path), 'file');
        JToolBarHelper::back('Back', JRoute::_(JoomDOCRoute::viewDocuments()));
    }
}
?>