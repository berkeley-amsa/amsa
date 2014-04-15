<?php

/**
 * @version		$Id$
 * @package		Joomla.Administrator
 * @subpackage	JoomDOC
 * @author      ARTIO s.r.o., info@artio.net, http:://www.artio.net
 * @copyright	Copyright (C) 2011 Artio s.r.o.. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

jimport('joomla.filesystem.folder');

class JoomDOCFolder extends JFolder {
    /**
     * Absolute folder path in file system.
     *
     * @var string
     */
    private $absolutePath;
    /**
     * Relative folder path from document root.
     *
     * @var string
     */
    private $relativePath;
    /**
     * Folder name without path.
     *
     * @var string
     */
    private $name;
    /**
     * Collection of folder child files.
     *
     * @var array
     */
    private $files;
    /**
     * Collection of folder child folders.
     *
     * @var array
     */
    private $folders;
    /**
     * Index used during files iteration.
     *
     * @var int
     */
    private $filesIndex;
    /**
     * Index used during folders iteration.
     *
     * @var int
     */
    private $foldersIndex;
    /**
     * Collection of folders relative paths.
     *
     * @var array
     */
    private $foldersPaths;
    /**
     * Collection of files relative paths.
     *
     * @var array
     */
    private $filesPaths;
    /**
     * Folders/files documents ordering.
     *
     * @var array
     */
    private $documentsOrderSetting;
    /**
     * Folder/files documents ordering values.
     *
     * @var array
     */
    private $documentsOrderValues;
    /**
     * Folders/files ordering settings.
     *
     * @var array
     */
    private $itemsOrderSetting;
    /**
     * Folders/files ordering values.
     *
     * @var array
     */
    private $itemsOrderValues;
    /**
     * Final complet collection.
     *
     * @var array
     */
    private $complet;
    /**
     * Index used during complet iteration.
     *
     * @var int
     */
    private $completIndex;
    /**
     * Document data.
     *
     * @var stdClass
     */
    public $document;
    /**
     * Folder hits (download).
     *
     * @var int
     */
    public $hits;
    /**
     * Date of folder creating.
     *
     * @var string
     */
    private $uploaded;

    /**
     * Create and init object. Set absolute path.
     *
     * @param string $abspath absolute path
     * @return void
     */
    public function __construct ($absolutePath) {
        $this->absolutePath = JPath::clean($absolutePath);
        $this->relativePath = JoomDOCFileSystem::getRelativePath($this->absolutePath);
        $this->name = JoomDOCFileSystem::getLastPathItem($this->absolutePath);
        $this->files = array();
        $this->folders = array();
        $this->filesPaths = array();
        $this->foldersPaths = array();
        $this->documentsOrderSetting = array(JOOMDOC_ORDER_TITLE, JOOMDOC_ORDER_ORDERING, JOOMDOC_ORDER_PUBLISH_UP);
        foreach ($this->documentsOrderSetting as $param)
            $this->documentsOrderValues[$param] = array();
        $this->itemsOrderSetting = array(JOOMDOC_ORDER_PATH, JOOMDOC_ORDER_UPLOAD, JOOMDOC_ORDER_HITS);
        foreach ($this->itemsOrderSetting as $param)
            $this->itemsOrderValues[$param] = array();
        $this->complet = array();
        $this->filesIndex = 0;
        $this->foldersIndex = 0;
        $this->document = null;
        $this->hits = 0;
        $this->uploaded = filemtime($this->absolutePath);
    }

    /**
     * Get folder absolute path in file system.
     *
     * @return string
     */
    public function getAbsolutePath () {
        return $this->absolutePath;
    }

    /**
     * Get folder relative path from document root.
     *
     * @return string
     */
    public function getRelativePath () {
        return $this->relativePath;
    }

    /**
     * Get folder name without path.
     *
     * @return string
     */
    public function getFileName () {
        return $this->name;
    }

    /**
     * Get folder date created.
     *
     * @return string
     */
    public function getUploaded () {
        return $this->uploaded;
    }

    /**
     * Add file into files collection.
     *
     * @param JoomDOCFile $file
     * @return void
     */
    public function addFile ($file) {
        $this->files[] = $file;
        $this->filesPaths[($key = (count($this->files) - 1))] = $file->getRelativePath();
    }

    /**
     * Add folder into folders collection.
     *
     * @param JoomDOCFolder $folder
     * @return void
     */
    public function addFolder ($folder) {
        $this->folders[] = $folder;
        $this->foldersPaths[($key = (count($this->folders) - 1))] = $folder->getRelativePath();
    }

    /**
     * Initaliase files list iteration.
     *
     * @return void
     */
    private function initFilesIteration () {
        $this->filesIndex = 0;
    }

    /**
     * Has next file to iterate.
     *
     * @return boolean
     */
    private function hasNextFile () {
        return $this->filesIndex < count($this->files);
    }

    /**
     * Get next file from list.
     *
     * @param boolean $getNext return next item
     * @return JoomDOCFile if not available function return false
     */
    private function getNextFile ($getNext = false) {
        if ($this->hasNextFile())
            return $getNext ? (isset($this->files[$this->filesIndex + 1]) ? $this->files[$this->filesIndex + 1] : false) : $this->files[$this->filesIndex ++];
        return false;
    }

    /**
     * Initalise folders list iteration.
     *
     * @return void
     */
    private function initFoldersIteration () {
        $this->foldersIndex = 0;
    }

    /**
     * Has next folder to iterate.
     *
     * @return boolean
     */
    private function hasNextFolder () {
        return $this->foldersIndex < count($this->folders);
    }

    /**
     * Get next folder from list.
     *
     * @param boolean $getNext return next item
     * @return JoomDOCFolder if not available function return false
     */
    private function getNextFolder ($getNext = false) {
        if ($this->hasNextFolder())
            return $getNext ? (isset($this->folders[$this->foldersIndex + 1]) ? $this->folders[$this->foldersIndex + 1] : false) : $this->folders[$this->foldersIndex ++];
        return false;
    }

    /**
     * Get relative paths of all folders/files.
     *
     * @return array
     */
    public function getPaths () {
        return array_merge($this->filesPaths, $this->foldersPaths, array($this->relativePath));
    }

    /**
     * Set folders/files documents by relative paths.
     *
     * @param array $documents
     * @return void
     */
    public function setDocuments ($documents) {
        foreach ($documents as $document) {
            if (($key = array_search($document->path, $this->foldersPaths)) !== false) {
                // search in folders
                $item =& $this->folders[$key];
                $type = 'folder';
            } elseif (($key = array_search($document->path, $this->filesPaths)) !== false) {
                // search in files
                $item =& $this->files[$key];
                $type = 'file';
            } elseif ($this->relativePath == $document->path) {
                // root document
                $this->document = $document;
                continue;
            } else {
                continue;
            }
            if ($document->title) {
                $item->document = $document;
            }
            foreach ($this->documentsOrderSetting as $param) {
                $this->documentsOrderValues[$param][$key . $type] = $document->$param;
            }
            foreach ($this->itemsOrderSetting as $param) {
                $item->$param = $this->itemsOrderValues[$param][$key . $type] = $document->$param;
            }
        }
    }

    /**
     * Get count of files and folders.
     *
     * @return int
     */
    public function getItemsCount () {
        return count($this->complet);
    }

    /**
     * Reorder items to final collection.
     *
     * @param string $documentsOrdering document param according to sort
     * @param string $itemsOrdering folders/files param according to sort
     * @param string $direction ordering direction (desc/asc)
     * @return void
     */
    public function reorder ($documentsOrdering = JOOMDOC_ORDER_ORDERING, $itemsOrdering = JOOMDOC_ORDER_PATH, $direction) {
        if (is_null($documentsOrdering))
            $documentsOrdering = JOOMDOC_ORDER_ORDERING;
        if (is_null($itemsOrdering))
            $itemsOrdering = JOOMDOC_ORDER_PATH;
        if (in_array($documentsOrdering, $this->documentsOrderSetting)) {
            foreach ($this->getValuesOrdeder($documentsOrdering, $direction, $this->documentsOrderValues) as $key => $value) {
                $this->addToComplet($key);
            }
        }
        if (in_array($itemsOrdering, $this->itemsOrderSetting)) {
            foreach ($this->getValuesOrdeder($itemsOrdering, $direction, $this->itemsOrderValues) as $key => $value) {
                $this->addToComplet($key);
            }
        }
    }

    /**
     * Add item to complet ordered collection by key.
     *
     * @param string $key key value
     * @return void
     */
    private function addToComplet ($key) {
        $ikey = (int) $key;
        if (isset($this->folders[$ikey]) && JString::strpos($key, 'folder') !== false) {
            $this->complet[] = $this->folders[$ikey];
            unset($this->folders[$ikey]);
        }
        if (isset($this->files[$ikey]) && JString::strpos($key, 'file') !== false) {
            $this->complet[] = $this->files[$ikey];
            unset($this->files[$ikey]);
        }
    }

    /**
     * Get saved values ordered by given setting.
     *
     * @param string $ordering param accroding to sort
     * @param string $direction sort direction
     * @param array $valuesStorage sorted values storage
     * @return array sorted values
     */
    private function getValuesOrdeder ($ordering, $direction, &$valuesStorage) {
        $values = $valuesStorage[$ordering];
        $sortType = SORT_STRING;
        foreach ($values as $value) {
            $sortType = is_numeric($value) ? SORT_NUMERIC : SORT_STRING;
        }
        $direction == JOOMDOC_ORDER_ASC ? asort($values, $sortType) : arsort($values, $sortType);
        return $values;
    }

    /**
     * Init complet iteration.
     *
     * @return void
     */
    public function initIteration () {
        $this->completIndex = 0;
    }

    /**
     * Has next item to iterate.
     *
     * @return boolean
     */
    public function hasNext () {
        return $this->completIndex < count($this->complet);
    }

    /**
     * Get next item from complet collection.
     *
     * @param bolean $getNext get next item after last iterated
     * @return mixed
     */
    public function getNext ($type = false) {
        if ($this->hasNext())
            switch ($type) {
                case JOOMDOC_ORDER_NEXT:
                    return isset($this->complet[$this->completIndex + 1]) ? $this->complet[$this->completIndex + 1] : false;
                case JOOMDOC_ORDER_PREV:
                    return isset($this->complet[$this->completIndex - 1]) ? $this->complet[$this->completIndex - 1] : false;
                default:
                    return $this->complet[$this->completIndex ++];
            }
        return false;
    }

    /**
     * Ordinary method to acces object properties.
     *
     * @param string $param name of property
     * @return string property value, if property no exists function return null
     */
    public function get ($param) {
        if (isset($this->$param))
            return $this->$param;
        return null;
    }
}
?>