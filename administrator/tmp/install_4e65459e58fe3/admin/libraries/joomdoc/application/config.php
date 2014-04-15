<?php

/**
 * @version		$Id$
 * @package		Joomla.Administrator
 * @subpackage	JoomDOC
 * @author      ARTIO s.r.o., info@artio.net, http:://www.artio.net
 * @copyright	Copyright (C) 2011 Artio s.r.o.. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

class JoomDOCConfig extends JObject {
    /**
     * Download ID.
     *
     * @var string
     */
    public $downloadId;
    /**
     * Filesystem document root.
     *
     * @var string
     */
    public $docroot;
    /**
     * Default document root title.
     *
     * @var string
     */
    public $defaultTitle;
    /**
     * Default meta keywords. If document hasn't own meta keywords use them instead.
     *
     * @var string
     */
    public $defaultMetakeywords;
    /**
     * Default meta description. If document hasn't own meta description use them instead.
     *
     * @var string
     */
    public $defaultMetadescription;
    /**
     * Default document root description.
     *
     * @var string
     */
    public $defaultDescription;
    /**
     * Root path for menu item
     *
     * @var string
     */
    public $path;
    /**
     * Show folder subfolders
     *
     * @var string
     */
    public $showSubfolders;
    /**
     * Show subfolders/subfiles without document.
     *
     * @var int 0/1 - false/true
     */
    public $filesWithoutDoc;
    /**
     * Document ordering.
     *
     * @var string
     */
    public $documentOrdering;
    /**
     * Files without documents ordering.
     *
     * @var string
     */
    public $fileOrdering;
    /**
     * Show file mime/type icon.
     *
     * @var int
     */
    public $showFileicon;
    /**
     * Show file size.
     *
     * @var int
     */
    public $showFilesize;
    /**
     * Show documents date create.
     *
     * @var int
     */
    public $showCreated;
    /**
     * Show documents modified date.
     *
     * @var int
     */
    public $showModified;
    /**
     * Show documents hits.
     *
     * @var string
     */
    public $showHits;
        /**
     * Display icon document is favorite on frontend.
     *
     * @var boolean
     */
    public $displayFavorite;

    /**
     * Allow use webdav for user group.
     *
     * @var mixed
     */
    public $webdavAllow;
    /**
     * Show open file link on file list bellow file detail.
     *
     * @var boolean
     */
    public $showOpenFile;
    /**
     * Show download file link on file list bellow file detail.
     *
     * @var boolean
     */
    public $showDownloadFile;
    /**
     * Show open folder link on file list bellow folder detail.
     *
     * @var boolean
     */
    public $showOpenFolder;

    /**
     * Show files documents description in documents list.
     *
     * @var boolean
     */
    public $showFileDesc;
    /**
     * Show folders documents description in documents list.
     *
     * @var boolean
     */
    public $showFolderDesc;
    /**
     * Display or hide powered signature.
     *
     * @var boolean
     */
    public $displaySignature;
    /**
     * If turn on folder seted as root of this menu item is used as virtual folder. It means that relative path to subfolders and subfiles is show from this folder without parent path.
     *
     * @var boolean
     */
    public $virtualFolder;
    /**
     * Name of folder with file icons.
     *
     * @var string
     */
    public $iconTheme;
    /**
     * Option completely disable WebDav support.
     *
     * @var boolean
     */
    public $useWebdav;
    /**
     * Get JoomDOC configuration instance.
     *
     * @return JoomDOCConfig
     */
    public function getInstance ($path = null) {
        static $instances;
        if (empty($instances))
            $instances = array();
        foreach ($instances as $instance)
            if ($instance->path == $path)
                return $instance->cfg;
        $instance = new JObject();
        $instance->path = $path;
        $instance->cfg = new JoomDOCConfig($path);
        $instances[] = $instance;
        return $instance->cfg;
    }

    /**
     * Create object and load JoomDOC configuration.
     *
     * @return void
     */
    public function __construct ($path = null) {
        $params = JComponentHelper::getParams(JOOMDOC_OPTION);
        /* @var $params JRegistry */
        $mainframe =& JFactory::getApplication();
        /* @var $mainframe JApplication */

        $defaultDocRoot = JPATH_ROOT . DS . 'documents';
        $maskDocRoot = '[%DOCROOT%]';

        $this->docroot = JPath::clean(JString::trim($params->getValue('docroot', $defaultDocRoot)));

        if (JFile::exists(JOOMDOC_CONFIG) && is_writable(JOOMDOC_CONFIG)) {
            $content = JFile::read(JOOMDOC_CONFIG);
            if (JString::strpos($content, $maskDocRoot) !== false) {
                $content = str_replace($maskDocRoot, $defaultDocRoot, $content);
                JFile::write(JOOMDOC_CONFIG, $content);
            }
        }

        $this->docrootrel = str_replace(JPATH_ROOT . DS, '', $this->docroot);
        if (!JFolder::exists($this->docroot)) {
            if (!JFolder::create($this->docroot)) {
                if ($mainframe->isAdmin()) {
                    JError::raiseWarning(21, JText::sprintf('JOOMDOC_UNABLE_CREATE_DOCROOT', $this->docroot));
                }
                $this->docroot = false;
            } elseif ($mainframe->isAdmin()) {
                $mainframe->enqueueMessage(JText::sprintf('JOOMDOC_DOCROOT_CREATED', $this->docroot));
            }
        }
        $this->downloadId = JString::trim($params->getValue('download_id', ''));
        $this->defaultTitle = JString::trim($params->getValue('default_title'));
        $this->defaultDescription = JString::trim($params->getValue('default_description'));
        $this->defaultMetakeywords = JString::trim($params->getValue('default_metakeywords'));
        $this->defaultMetadescription = JString::trim($params->getValue('default_metadescription'));
        $this->versionFile = (int) $params->getValue('version_file');
                $this->displayFavorite = (int) $params->getValue('display_favorite', 1);
        $this->webdavAllow = (int) $params->getValue('webdav_allow', 25);
        $this->displaySignature = (int) $params->getValue('display_signature', 1);

        $this->path = $this->docroot;
        $this->documentOrdering = JOOMDOC_ORDER_ORDERING;
        $this->fileOrdering = JOOMDOC_ORDER_PATH;
        $this->iconTheme = JString::trim($params->getValue('icon_theme'));

        $this->useWebdav = (int) $params->getValue('use_webdav', 0);

        if ($mainframe->isSite()) {
            $menu =& $mainframe->getMenu();
            /* @var $menu JMenuSite */

            $itemID = $path ? JoomDOCMenu::getMenuItemID($path) : null;
            $itemID = $itemID ? $itemID : JRequest::getInt('Itemid');

            $item = $itemID ? $menu->getItem($itemID) : $menu->getActive();

            if (is_object($item)) {
                if (isset($item->query['path'])) {
                    // get start folder from menu item URL (param path)
                    $path = JString::trim($item->query['path']);
                    if ($path) {
                        $path = JPath::clean($this->docroot . DS . $path);
                        if (JFolder::exists($path) || JFile::exists($path)) {
                            $this->path = $path;
                        } else {
                            $this->path = false;
                        }
                    }
                }

                if (!is_object($item->params)) {
                    // Joomla 1.5.x
                    $params = new JParameter($item->params);
                } else {
                    // Joomla 1.6.x
                    $params = $item->params;
                }
                // get display options from menu item setting
                $this->showSubfolders = (int) $params->getValue('show_subfolders', 1);
                $this->filesWithoutDoc = (int) $params->getValue('files_without_doc', 1);
                $this->documentOrdering = $params->getValue('document_ordering', JOOMDOC_ORDER_ORDERING);
                $this->fileOrdering = $params->getValue('file_ordering', JOOMDOC_ORDER_PATH);
                $this->showFileicon = (int) $params->getValue('show_fileicon', 1);
                $this->showFilesize = (int) $params->getValue('show_filesize', 1);
                $this->showCreated = (int) $params->getValue('show_created', 1);
                $this->showModified = (int) $params->getValue('show_modified', 1);
                $this->showHits = (int) $params->getValue('show_hits', 1);
                $this->showOpenFile = (int) $params->getValue('show_open_file', 1);
                $this->showDownloadFile = (int) $params->getValue('show_download_file', 1);
                $this->showOpenFolder = (int) $params->getValue('show_open_folder', 1);
                $this->showFileDesc = (int) $params->getValue('show_file_desc', 1);
                $this->showFolderDesc = (int) $params->getValue('show_folder_desc', 1);
                $this->virtualFolder = (int) $params->getValue('virtual_folder', 1);
            }
        }
    }
}
?>