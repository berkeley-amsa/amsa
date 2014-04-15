<?php

class JoomDOCAccessHelper {
    public $docid;

    public $isFolder;
    public $isFile;

    public $relativePath;
    public $absolutePath;

    public $inRoot;

    public $name;
    public $alias;

    public $isChecked;
    public $isLocked;

    public $canViewFileInfo;
    public $canRename;
    public $canWebDav;
    public $canEdit;
    public $canAnyEditOp;
    public $canCreate;
    public $canDownload;

    public $canEnterFolder;
    public $canOpenFolder;
    public $canOpenFile;

    public $canEditState;
    public $canEditStates;
    public $canCopyMove;
    public $canDeleteDoc;
    public $canDeleteDocs;
    public $canDeleteFile;
    public $canUpload;
    public $canCreateFolder;
    public $canViewVersions;

    public $canShowFileDates;
    public $canShowFileInfo;
    public $canShowAllDesc;

    public $isFavorite;
    public $canDisplayFavorite;

    public function __construct (&$item) {
        $config =& JoomDOCConfig::getInstance();
        /* @var $config JoomDOCConfig */
        $mainframe =& JFactory::getApplication();
        /* @var $mainframe JApplication */

        $this->isFile = JoomDOCFileSystem::isFile($item);
        $this->isFolder = JoomDOCFileSystem::isFolder($item);

        $isFileSystemItem = $this->isFile || $this->isFolder;

        $this->docid = $isFileSystemItem ? JoomDOCHelper::getDocumentID($item) : $item->id;

        if (isset($item->document)) {
            $document = new JObject();
            $document->setProperties($item->document);
        } elseif (!$isFileSystemItem) {
            if ($item instanceof JObject) {
                $document = $item;
            } else {
                $document = new JObject($item);
                $document->setProperties($item);
            }
        } else {
            $document = new JObject();
        }

        if ($mainframe->isSite() && $document->get('state') == JOOMDOC_STATE_TRASHED) {
            $this->docid = null;
        }

        $this->relativePath = $isFileSystemItem ? $item->getRelativePath() : $item->path;
        $this->absolutePath = $isFileSystemItem ? $item->getAbsolutePath() : JoomDOCFileSystem::getFullPath($this->relativePath);

        $this->inRoot = $this->absolutePath == $config->path;

        $this->name = $isFileSystemItem ? $item->getFileName() : JFile::getName($this->relativePath);
        $this->alias = JoomDOCHelper::getDocumentAlias($item);

        $this->isChecked = JoomDOCHelper::isChecked($item);
        $this->isLocked = false;
        
        $this->canViewFileInfo = JoomDOCAccessFileSystem::viewFileInfo($this->docid, $this->relativePath);
        $this->canRename = JoomDOCAccessFileSystem::rename($this->docid, $this->relativePath);
        $this->canWebDav = JoomDOCAccessFileSystem::editWebDav($this->docid, $this->relativePath);
        $this->canEdit = $this->docid && JoomDOCAccessDocument::canEdit($document);
        $this->canCreate = !$this->docid && JoomDOCAccessDocument::create($this->relativePath);
        $this->canDownload = $this->isFile && JoomDOCAccessFileSystem::download($this->docid, $this->relativePath);

        $this->canEnterFolder = JoomDOCAccessFileSystem::enterFolder($this->docid, $this->relativePath);
        $this->canOpenFolder = $this->isFolder && $config->showOpenFolder && $this->canEnterFolder;
        $this->canOpenFile = $this->isFile && $config->showOpenFile;

        $this->canEditStates = JoomDOCAccessDocument::editState($this->docid, $document->get('checked_out'));
        $this->canEditState = $this->docid && JoomDOCAccessDocument::editState($this->docid, $document->get('checked_out'));
        $this->canCopyMove = JoomDOCAccessFileSystem::copyMove($this->docid, $this->relativePath);
        $this->canDeleteDocs = JoomDOCAccessDocument::delete($this->docid);
        $this->canDeleteDoc = $this->docid && JoomDOCAccessDocument::delete($this->docid);
        $this->canDeleteFile = JoomDOCAccessFileSystem::deleteFile($this->docid, $this->relativePath);
        $this->canUpload = JoomDOCAccessFileSystem::uploadFile($this->docid, $this->relativePath);
        $this->canCreateFolder = JoomDOCAccessFileSystem::newFolder($this->docid, $this->relativePath);
        $this->canViewVersions = JoomDOCAccessDocument::viewVersions($this->docid);

        $this->canShowFileDates = $config->showCreated || $config->showModified;
        $this->canShowFileInfo = $config->showFilesize || $config->showHits;
        $this->canShowAllDesc = $config->showFolderDesc && $config->showFileDesc;

        $this->isFavorite = $document->get('favorite') == 1;
        $this->canDisplayFavorite = $this->isFavorite && $config->displayFavorite;

        $this->canAnyEditOp = $this->canEdit || $this->canWebDav || $this->canEditState || $this->canCreate || $this->canDeleteFile || $this->canDeleteDoc;

    }
}
