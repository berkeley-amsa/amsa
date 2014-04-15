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

class JoomDOCViewDocuments extends JoomDOCView {
    /**
     * Current viewed folder.
     *
     * @var JoomDOCFolder
     */
    protected $root;
    /**
     * Filter folder/file name.
     *
     * @var string
     */
    protected $filter;
    /**
     * Documents list for listed files/folders.
     *
     * @var array
     */
    protected $documents;

    /**
     * Request filter state.
     *
     * @var JObject
     */
    public $state;
    /**
     * Root folder access rules.
     *
     * @var JoomDOCAccessHelper
     */
    public $access;
    /**
     * Select folder is in doc root.
     *
     * @var boolean
     */
    public $inRoot;
    /**
     * Page listing.
     *
     * @var JPagination
     */
    public $pagination;

    /**
     * Display page with folder content.
     *
     * @param $tpl used template
     * @return void
     */
    public function display ($tpl = null) {
        $mainframe =& JFactory::getApplication();
        /* @var $mainframe JAdministrator */
        $config =& JoomDOCConfig::getInstance();
        /* @var $config JoomDOCConfig */
        $model =& $this->getModel();
        /* @var $model JoomDOCModelDocuments */
        $document =& JFactory::getDocument();
        /* @var $document JDocumentHTML */

        // relative path from request or user session
        $path = $mainframe->getUserStateFromRequest('joomdoc_documents_path', 'path', '', 'string');
        if ($path == JText::_('JOOMDOC_ROOT'))
            $path = '';

        // convert to absolute path
        $path = JoomDOCFileSystem::getFullPath($path);

        $this->filter = $mainframe->getUserStateFromRequest('joomdoc_documents_filter_' . $path, 'filter', '', 'string');
        $this->root =& JoomDOCFileSystem::getFolderContent($path, '');
        // control if select folder is subfolder of docroot
        if ((!JoomDOCFileSystem::isSubFolder($path, $config->docroot) || $this->root === false) && $config->docroot !== false)
            $mainframe->redirect(JoomDOCRoute::viewDocuments($config->docroot));

        $model->setKeywords($this->filter);
        $model->setState(JoomDOCView::getStateName(JOOMDOC_FILTER_PATHS), $this->root->getPaths());

        $this->documents =& $model->getItems();
        $this->state =& $model->getState();
        $this->pagination =& $model->getPagination();

        $this->access = new JoomDOCAccessHelper($this->root);

        $this->root->setDocuments($this->documents);
        $this->root->reorder($this->state->get(JOOMDOC_FILTER_ORDERING), $this->state->get(JOOMDOC_FILTER_ORDERING), $this->state->get(JOOMDOC_FILTER_DIRECTION));

        // control permissions to access folder
        if (!$this->access->canEnterFolder) {
            $mainframe->setUserState('joomdoc_documents_path', null);
            JError::raiseError(403, JText::_('JOOMDOC_UNABLE_ACCESS_FOLDER'));
        }

        $this->addToolbar();

        JoomDOCHelper::setSubmenu(JOOMDOC_DOCUMENTS);
        JoomDOCHelper::clipboardInfo();
        JoomDOCHelper::folderInfo($this->access->absolutePath);

        parent::display($tpl);
    }

    /**
     * Add page main toolbar.
     *
     * @return void
     */
    protected function addToolbar () {
        $bar =& JToolBar::getInstance('toolbar');
        /* @var $bar JToolBar */
        JToolBarHelper::title(JText::_('JOOMDOC_DOCUMENTS'), 'documents');
        if ($this->access->canEditStates) {
            JToolBarHelper::publish(JoomDOCHelper::getTask(JOOMDOC_DOCUMENTS, JOOMDOC_TASK_PUBLISH));
            JToolBarHelper::unpublish(JoomDOCHelper::getTask(JOOMDOC_DOCUMENTS, JOOMDOC_TASK_UNPUBLISH));
            if (J16)
                JToolBarHelper::custom(JoomDOCHelper::getTask(JOOMDOC_DOCUMENTS, JOOMDOC_TASK_CHECKIN), 'checkin', '', 'JTOOLBAR_CHECKIN', true);
        } else {
            $bar->appendButton('Disabled', 'publish', 'JTOOLBAR_PUBLISH');
            $bar->appendButton('Disabled', 'unpublish', 'JTOOLBAR_UNPUBLISH');
            if (J16)
                $bar->appendButton('Disabled', 'checkin', 'JTOOLBAR_CHECKIN');
        }
        JToolBarHelper::divider();
        if ($this->access->canCopyMove) {
            JToolBarHelper::custom(JoomDOCHelper::getTask(JOOMDOC_DOCUMENT, JOOMDOC_TASK_COPY), 'copy', '', 'JTOOLBAR_COPY', true);
            JToolBarHelper::custom(JoomDOCHelper::getTask(JOOMDOC_DOCUMENT, JOOMDOC_TASK_MOVE), 'move', '', 'JTOOLBAR_MOVE', true);
        } else {
            $bar->appendButton('Disabled', 'copy', 'JTOOLBAR_COPY');
            $bar->appendButton('Disabled', 'move', 'JTOOLBAR_MOVE');
        }
        if ($this->access->canCopyMove && JoomDOCFileSystem::haveOperation()) {
            JToolBarHelper::custom(JoomDOCHelper::getTask(JOOMDOC_DOCUMENT, JOOMDOC_TASK_PASTE), 'save', '', 'JTOOLBAR_PASTE', false);
            JToolBarHelper::custom(JoomDOCHelper::getTask(JOOMDOC_DOCUMENT, JOOMDOC_TASK_RESET), 'remove', '', 'JTOOLBAR_RESET', false);
        } else {
            $bar->appendButton('Disabled', 'save', 'JTOOLBAR_PASTE');
            $bar->appendButton('Disabled', 'remove', 'JTOOLBAR_RESET');
        }
        JToolBarHelper::divider();
        // Document delete
        if ($this->access->canDeleteDocs)
            $bar->appendButton('Confirm', 'JOOMDOC_ARE_YOU_SURE_DELETE_DOCUMETS', 'docs-delete', 'JOOMDOC_DELETE_DOCUMENT', JoomDOCHelper::getTask(JOOMDOC_DOCUMENTS, JOOMDOC_TASK_DELETE), true);
        else
            $bar->appendButton('Disabled', 'docs-delete', 'JOOMDOC_DELETE_DOCUMENT');
        // Item delete
        if ($this->access->canDeleteFile)
            JToolBarHelper::deleteList('JOOMDOC_ARE_YOU_SURE_DELETE_ITEMS', JoomDOCHelper::getTask(JOOMDOC_DOCUMENTS, JOOMDOC_TASK_DELETEFILE), 'JOOMDOC_DELETE_ITEM');
        else
            $bar->appendButton('Disabled', 'delete', 'JOOMDOC_DELETE_ITEM');
        if ($this->access->canDeleteDocs && $this->access->canDeleteFile)
            $bar->appendButton('Confirm', 'JOOMDOC_ARE_YOU_SURE_EMPTY_TRASH', 'trash', 'JTOOLBAR_EMPTY_TRASH', JoomDOCHelper::getTask(JOOMDOC_DOCUMENTS, JOOMDOC_TASK_TRASH), false);
        else
            $bar->appendButton('Disabled', 'trash', 'JTOOLBAR_TRASH');
        if (JoomDOCAccess::admin()) {
            JToolBarHelper::divider();
            JToolBarHelper::preferences(JOOMDOC_OPTION, JOOMDOC_PARAMS_WINDOW_HEIGHT, JOOMDOC_PARAMS_WINDOW_WIDTH);
        }
    }
}
?>