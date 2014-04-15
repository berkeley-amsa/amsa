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

class JoomDOCTableFile extends JTable {
    /**
     * Primary key
     *
     * @var int
     */
    var $id;
    /**
     * Relative path of original file
     *
     * @var string
     */
    var $path;
    /**
     * Version number
     *
     * @var int
     */
    var $version;
    /**
     * Date of file upload in GMT0
     *
     * @var string MySQL datetime format
     */
    var $upload;
    /**
     * Joomla user ID who uploaded file
     *
     * @var int ID from table #__users
     */
    var $uploader;
    /**
     * Cleaned content of file to full text search
     *
     * @var string
     */
    var $content;

    /**
     * Create object and set database conector
     *
     * @param JDatabaseMySQL $db
     */
    function __construct (&$db) {
        parent::__construct('#__joomdoc_file', 'id', $db);
    }

    /**
     * Store file row into database
     *
     * @param boolean $versions save file version
     */
    function store ($versions = false) {
        $this->id = null;
                if (!$versions) {
            $this->_db->setQuery('SELECT `id` FROM `#__joomdoc_file` WHERE `path` = ' . $this->_db->Quote($this->path));
            // if versioning turn off update exists row
            $this->id = $this->_db->loadResult();
        }
        $this->version = 1;
                // current datetime in GMT0 as upload date
        $this->upload = JFactory::getDate()->toMySQL();
        // current user as uploader
        $this->uploader = JFactory::getUser()->id;
                parent::store();
    }
    
    /**
     * Delete files and documents with their versions with path.
     *
     * @param array $paths absolute path to deleted files
     * @return array absolute paths of files and their versions to delete
     */
    function delete ($paths) {
        // prepare data
        foreach ($paths as $key => $path) {
            // search selected files
            $paths[$key] = $this->_db->Quote($path);
            // search children
            $childrenPaths[] = '`path` LIKE ' . $this->_db->Quote($path . DS . '%');
        }

        // sql format where
        $where = 'WHERE `path` IN (' . implode(', ', $paths) . ') OR ' . implode(' OR ', $childrenPaths);

        // search files row and rows their children
        $query = 'SELECT `path` FROM `#__joomdoc_file` ' . $where;
        $this->_db->setQuery($query);
        // complet tree parents and children with versions
        $tree =& $this->_db->loadResultArray();

        // delete tree
        $query = 'DELETE FROM `#__joomdoc_file` ' . $where;
        $this->_db->setQuery($query);
        $this->_db->query();

        // delete tree documents
        $query = 'DELETE FROM `#__joomdoc` ' . $where;
        $this->_db->setQuery($query);
        $this->_db->query();

        return $tree;
    }

    /**
     * Rename file/folder. Change their path and path of their children in file and document.
     *
     * @param string $oldPath
     * @param string $newPath
     */
    function rename ($oldPath, $newPath) {
        $oldPathParent = $this->_db->Quote($oldPath);
        $oldPathChildren = $this->_db->Quote($oldPath . DS . '%');
        $newPathParent = $this->_db->Quote($newPath);
        $newPathChildren = $this->_db->Quote($newPath);

        $query = 'UPDATE `#__joomdoc` SET path = replace(path,' . $oldPathParent . ',' . $newPathParent . ') WHERE `path` = ' . $oldPathParent . ' OR `path` LIKE ' . $oldPathChildren;
        $this->_db->setQuery($query);
        $this->_db->query($query);
        $query = 'UPDATE `#__joomdoc_file` SET path = replace(path,' . $oldPathParent . ',' . $newPathParent . ') WHERE `path` = ' . $oldPathParent . ' OR `path` LIKE ' . $oldPathChildren;
        $this->_db->setQuery($query);
        $this->_db->query($query);
    }
    /**
     * Copy/move files and documents database rows.
     *
     * @param string $oldPath old file relative path
     * @param string $newPath new file relative path
     * @param boolean $move false copy, true move
     */
    function copyMove ($oldPath, $newPath, $move) {
        $app =& JFactory::getApplication();
        /* @var $app JApplication */
        $oldPath = $this->_db->quote($oldPath);

        $newParentPath = JoomDOCFileSystem::getParentPath($newPath);
        $newParentPathQuote = $this->_db->quote($newParentPath);

        $newPath = $this->_db->quote($newPath);

        if (!$move) {
            // get IDs of all old rows
            $this->_db->setQuery('SELECT `id` FROM `#__joomdoc_file` WHERE `path` = ' . $oldPath);
            $fileIDs = $this->_db->loadResultArray();

            $this->_db->setQuery('SELECT `id` FROM `#__joomdoc` WHERE `path` = ' . $oldPath);
            $docIDs = $this->_db->loadResultArray();

            // copy all old rows
            foreach ($fileIDs as $id)
                $newFileIDs[] = JoomDOCModelList::copyRow('#__joomdoc_file', 'id', $id);

            foreach ($docIDs as $id)
                $newDocIDs[] = JoomDOCModelList::copyRow('#__joomdoc', 'id', $id);

            // update path in new rows
            if (isset($newFileIDs)) {
                $this->_db->setQuery('UPDATE `#__joomdoc_file` SET `path` = ' . $newPath . ' WHERE `id` IN (' . implode(', ', $newFileIDs) . ')');
                $this->_db->query();
            }
            // update path and parent path in new rows
            if (isset($newDocIDs)) {
                $newDocIDs = implode(',', $newDocIDs);
                $this->_db->setQuery('UPDATE `#__joomdoc` SET `path` = ' . $newPath . ', `parent_path` = ' . $newParentPathQuote . ' WHERE `id` IN (' . $newDocIDs . ')');
                $this->_db->query();
                $this->_db->setQuery('SELECT `id`, `alias` FROM `#__joomdoc` WHERE `id` IN (' . $newDocIDs . ')');
                $newDocs =& $this->_db->loadObjectList();
            }
        } else {
            // for move only update paths
            $this->_db->setQuery('UPDATE `#__joomdoc_file` SET `path` = ' . $newPath . ' WHERE `path` = ' . $oldPath);
            $this->_db->query();

            $this->_db->setQuery('UPDATE `#__joomdoc` SET `path` = ' . $newPath . ', `parent_path` = ' . $newParentPathQuote . ' WHERE `path` = ' . $oldPath);
            $this->_db->query();

            $this->_db->setQuery('SELECT `id`, `alias` FROM `#__joomdoc` WHERE `path` = ' . $newPath);
            $newDocs =& $this->_db->loadObjectList();
        }
        if (isset($newDocs)) {
            // get alias of last version of new parent
            $this->_db->setQuery('SELECT `alias` FROM `#__joomdoc` WHERE `path` = ' . $newParentPathQuote . ' ORDER BY `version` DESC');
            $parentAlias = $this->_db->loadResult();
            if (is_null($parentAlias))
                $parentAlias = $newParentPath;
            foreach ($newDocs as $newDoc) {
                // new full alias from new parent alias and new document alias
                $newDoc->full_alias = $parentAlias . '/' . $newDoc->alias;
                // update path, parent path and full alias in new document
                $this->_db->setQuery('UPDATE `#__joomdoc` SET `full_alias` = ' . $this->_db->quote($newDoc->full_alias) . ' WHERE `id` = ' . $newDoc->id);
                $this->_db->query();
            }
        }
    }
}
?>