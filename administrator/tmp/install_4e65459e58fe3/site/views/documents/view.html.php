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

jimport('joomla.html.parameter');

class JoomDOCViewDocuments extends JoomDOCView {

    /**
     * Current viewed folder.
     *
     * @var JoomDOCFolder
     */
    protected $root;
    /**
     * Root access
     *
     * @var JoomDOCAccessHelper
     */
    protected $access;
    /**
     * Filter folder/file name.
     *
     * @var string
     */
    protected $filter;
    /**
     * Request filter state.
     *
     * @var JObject
     */
    public $state;
    /**
     * Support for page listing.
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
        /* @var $mainframe JSite */
        $document =& JFactory::getDocument();
        /* @var $documents JDocumentHTML */
        $config =& JoomDOCConfig::getInstance();
        /* @var $config JoomDOCConfig */

        $modelDocument =& JModel::getInstance(JOOMDOC_DOCUMENT, JOOMDOC_MODEL_PREFIX);
        /* @var $modelDocument JoomDOCModelDocument */
        $modelDocuments =& JModel::getInstance(JOOMDOC_DOCUMENTS, JOOMDOC_MODEL_PREFIX);
        /* @var $modelDocuments JoomDOCModelDocuments */
        $modelFile =& JModel::getInstance(JOOMDOC_FILE, JOOMDOC_MODEL_PREFIX);
        /* @var $modelFile JoomDOCModelFile */

        $path = JoomDOCRequest::getPath();

        // convert to absolute path, if path si empty use document root path
        $path = $path ? JoomDOCFileSystem::getFullPath($path) : $config->path;

        // request path value isn't subfolder of document root
        if (!JoomDOCFileSystem::isSubFolder($path, $config->path)) {
            JError::raiseError(403, JText::_('JERROR_ALERTNOAUTHOR'));
        }

        $this->filter = $mainframe->getUserStateFromRequest('JOOMDOC_DOCUMENTS_FILTER_' . $path, 'filter', '', 'string');
        // get content of selected folder
        $this->root = JoomDOCFileSystem::getFolderContent($path, $this->filter);

        if (JoomDOCFileSystem::isFolder($this->root)) {
            // selected path is folder
            $modelDocuments->setState(JoomDOCView::getStateName(JOOMDOC_FILTER_PATHS), $this->root->getPaths());
            // get child documents
            $this->documents =& $modelDocuments->getItems();
            $this->state =& $modelDocuments->getState();
            // add documents to given subfolders and files
            $this->root->setDocuments($this->documents);
            $this->access = new JoomDOCAccessHelper($this->root);
            // control permissions to access folder
            if (!$this->access->canEnterFolder) {
                JError::raiseError(403, JText::_('JOOMDOC_UNABLE_ACCESS_FOLDER'));
            }
            // reorder
            $this->root->reorder($config->documentOrdering, $config->fileOrdering, JOOMDOC_ORDER_ASC);
            // set root parent
            $this->root->parent =& $modelDocument->getParent(JoomDOCFileSystem::getParentPath($this->root->getRelativePath()));

        } elseif (JoomDOCFileSystem::isFile($this->root)) {
            // use different layout
            $this->setLayout('file');
            // search document by path
            $this->root->document =& $modelDocument->getItemByPath($this->root->getRelativePath());
            $this->access = new JoomDOCAccessHelper($this->root);
            // document unpublished
            $this->root->parent =& $modelDocument->getParent(JoomDOCFileSystem::getParentPath($this->root->getRelativePath()));
                    } else {
            JError::raiseError(404, JText::_('JERROR_LAYOUT_PAGE_NOT_FOUND'));
        }

        // control root access
        if ($this->access->docid) {
            // root unpublished
            if (!$this->access->canAnyEditOp && $this->root->document->published == JOOMDOC_STATE_UNPUBLISHED) {
                JError::raiseError(403, JText::_('JERROR_ALERTNOAUTHOR'));
            }
            // root trashed
            if ($this->root->document->state == JOOMDOC_STATE_TRASHED) {
                JError::raiseError(404, JText::_('JERROR_ALERTNOAUTHOR'));
            }
        }

        // take candidates for metadata sort on priority
        if ($this->access->docid) {
            // from document data
            $params = new JParameter($this->root->document->params);
            $titles[] = JString::trim($this->root->document->title);
            $metakeywords[] = JString::trim($params->getValue('metakeywords'));
            $metadescriptions[] = JString::trim($params->getValue('metadescription'));
            $metadescriptions[] = JoomDOCHelper::getMetaDescriptions($this->root->document->description);
        }
        // default candidates
        $titles[] = $this->access->relativePath;
        $titles[] = $config->defaultTitle;
        $metakeywords[] = $config->defaultMetakeywords;
        $metadescriptions[] = $config->defaultMetadescription;

        // set meta data from candidates acording to priority

        // set meta keywords
        $document->setMetaData('keywords', JoomDOCHelper::getFirstNoEmpty($metakeywords));
        // set page title
        $document->setTitle(JoomDOCHelper::getCompletTitle(JoomDOCHelper::getFirstNoEmpty($titles)));
        // set head meta description
        $document->setDescription(JoomDOCHelper::getFirstNoEmpty($metadescriptions));

        parent::display($tpl);
    }
}
?>