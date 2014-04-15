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

jimport('joomla.application.component.modeladmin');

class JoomDOCModelDocument extends JModelAdmin {

    /**
     * Path parents.
     *
     * @var array of JObjects with two params: parentPath (last parent path with document), subparentPath (last parent path), documentID
     */
    public $parents;

    function __construct ($config) {
        parent::__construct();
        $this->option = JOOMDOC_OPTION;
        $this->name = $this->getName();
        $this->setState($this->getName() . '.id', JRequest::getInt('id'));
        $this->checkin();
    }

    /**
     * @var string The prefix to use with controller messages.
     * @since	1.6
     */
    protected $text_prefix = JOOMDOC_OPTION;

    /**
     * Method to test whether a record can be deleted.
     *
     * @param object $record a record object.
     * @return boolean True if allowed to delete the record. Defaults to the permission set in the component.
     */
    protected function canDelete ($record) {
        return JoomDOCAccessDocument::delete($record->id);
    }

    /**
     * Method to test whether a record can be deleted.
     *
     * @param JForm $record a record object.
     * @return boolean True if allowed to change the state of the record. Defaults to the permission set in the component.
     */
    protected function canEditState (&$record) {
        if (!empty($record)) {
            if ($record instanceof JForm)
                return JoomDOCAccessDocument::editState($record->getValue('id'), $record->getValue('checked_out'), $record->getValue('path'));
            else
                return JoomDOCAccessDocument::editState($record->id, $record->checked_out, $record->path);
        }
        return parent::canEditState($record);
    }

    /**
     * Prepare and sanitise the table data prior to saving.
     *
     * @param JTable A JTable object.
     * @return void
     */
    protected function prepareTable (&$table) {
        if ($table->state == 1 && intval($table->publish_up) == 0)
            $table->publish_up = JFactory::getDate()->toMySQL();
    }

    /**
     * Returns a Table object, always creating it.
     *
     * @param type The table type to instantiate
     * @param string A prefix for the table class name. Optional.
     * @param array Configuration array for model. Optional.
     * @return JTable A database object
     */
    public function getTable ($type = JOOMDOC_DOCUMENT, $prefix = JOOMDOC_TABLE_PREFIX, $config = array ()) {
        return JTable::getInstance($type, $prefix, $config);
    }

    /**
     * Method to get the record form.
     *
     * @param array	$data Data for the form.
     * @param boolean $loadData	True if the form is to load its own data (default case), false if not.
     * @return mixed A JForm object on success, false on failure
     */
    public function getForm ($data = array (), $loadData = true) {

        $form =& $this->loadForm(sprintf('%s.%s', JOOMDOC_OPTION, JOOMDOC_DOCUMENT), JFile::read(JOOMDOC_MODELS . DS . 'forms' . DS . JOOMDOC_DOCUMENT . '.xml'), array('control' => 'jform', 'load_data' => $loadData));
        /* @var $form JForm */

        if (empty($form))
            return false;

        if (!$this->canEditState($form)) {

            $form->setFieldAttribute('publish_up', 'disabled', 'true');
            $form->setFieldAttribute('publish_down', 'disabled', 'true');
            $form->setFieldAttribute('state', 'disabled', 'true');
            $form->setFieldAttribute('favorite', 'disabled', 'true');
            $form->setFieldAttribute('ordering', 'disabled', 'true');

            $form->setFieldAttribute('publish_up', 'filter', 'unset');
            $form->setFieldAttribute('publish_down', 'filter', 'unset');
            $form->setFieldAttribute('state', 'filter', 'unset');
            $form->setFieldAttribute('favorite', 'filter', 'unset');
            $form->setFieldAttribute('ordering', 'filter', 'unset');

        }
        return $form;
    }
    /**
     * Method to get the data that should be injected in the form.
     *
     * @return	mixed	The data for the form.
     * @since	1.6
     */
    protected function loadFormData () {
        $data = JFactory::getApplication()->getUserState(sprintf('%s.%s.%s.data', JOOMDOC_OPTION, JOOMDOC_TASK_EDIT, JOOMDOC_DOCUMENT), array());
        if (empty($data))
            $data = $this->getItem();
        return $data;
    }

    /**
     * A protected method to get a set of ordering conditions.
     *
     * @param object A record object.
     * @return array An array of conditions to add to add to ordering queries.
     */
    protected function getReorderConditions ($table) {
        $condition[] = '`parent_path` = ' . $this->_db->quote($table->parent_path) . ' AND `id` IN (SELECT MIN(`id`) FROM `#__joomdoc` GROUP BY `path`)';
        return $condition;
    }

    /**
     * Load document by path value.
     *
     * @param string $path
     */
    public function getItemByPath ($path) {

        $published = JoomDOCModelList::getDocumentPublished();

        $path = $this->_db->quote($path);

        $query = 'SELECT `document`.`id`, (' . $published . ') AS `published`, `document`.`title`, `document`.`description`, `document`.`params`, `document`.`full_alias`, `document`.`created_by`, `document`.`checked_out`, `document`.`state`, ';
        // use document create or file upload date as created value (document primary)
        $query .= 'COALESCE(`document`.`created`,(SELECT MIN(`upload`) FROM `#__joomdoc_file` WHERE `path` = ' . $path . ')) AS `created`, ';
        // use document modified or file upload date as modified value (document primary)
        $query .= 'COALESCE(`document`.`modified`,(SELECT MAX(`upload`) FROM `#__joomdoc_file` WHERE `path` = ' . $path . ')) AS `modified`, ';
        $query .= '`document`.`favorite`, `document`.`parent_path` ';
        $query .= 'FROM `#__joomdoc_file` AS `file` ';
        $query .= 'LEFT JOIN `#__joomdoc` AS `document` ON `document`.`path` = `file`.`path` ';
        // search for relative file path
        $query .= 'WHERE `file`.`path` = ' . $path;
        // only document last version
        $query .= ' AND (`document`.`id` IN (SELECT MIN(`id`) FROM `#__joomdoc` GROUP BY `path`) OR `document`.`id` IS NULL) ';
        // only file last version
        $query .= 'AND (`file`.`id` IN (SELECT MAX(`id`) FROM `#__joomdoc_file` GROUP BY `path`)) ';

        $this->_db->setQuery($query);
        $item =& $this->_db->loadObject();

        if ($item) {
            $query = 'SELECT SUM(`hits`) FROM `#__joomdoc_file` WHERE `path` = ' . $path . ' GROUP BY `path`';
            $this->_db->setQuery($query);
            $item->hits = $this->_db->loadResult();
        }

        return $item;
    }
    /**
     * Get parent document by parent path.
     *
     * @param string $parentPath parent relative path
     * @return stdClass if not found return null
     */
    public function getParent ($parentPath) {
        if (!JString::trim($parentPath)) {
            return null;
        }
        $this->_db->setQuery('SELECT * FROM `#__joomdoc` WHERE `path` = ' . $this->_db->Quote($parentPath) . ' ORDER BY `version` DESC');
        return $this->_db->loadObject();
    }

    /**
     * Search document ID by file relative path.
     *
     * @param string $path relative path
     * @return int
     */
    public function searchIdByPath ($path) {
        $this->_db->setQuery('SELECT `id` FROM `#__joomdoc` WHERE `path` = ' . $this->_db->quote($path) . ' ORDER BY `version` DESC', 0, 1);
        return (int) $this->_db->loadResult();
    }

    /**
     * Search checked out by file relative path.
     *
     * @param string $path relative path
     * @return int
     */
    public function searchCheckedOutByPath ($path) {
        $this->_db->setQuery('SELECT `checked_out` FROM `#__joomdoc` WHERE `path` = ' . $this->_db->quote($path) . ' ORDER BY `version` DESC', 0, 1);
        return (int) $this->_db->loadResult();
    }

    /**
     * Search full document alias by relative file path.
     *
     * @param string $path relative path of file
     * @return string full alias of last version of document, null if not found
     */
    public function searchFullAliasByPath ($path) {
        static $results;
        if (is_null($results))
            $results = array();
        // search in cache		
        foreach ($results as $result)
            if ($result->path == $path)
                return $result->alias;
        $this->_db->setQuery('SELECT `full_alias` FROM `#__joomdoc` WHERE `path` = ' . $this->_db->quote($path) . ' ORDER BY `version` DESC', 0, 1);
        $result = new JObject();
        $result->path = $path;
        $result->alias = $this->_db->loadResult();
        // cache result
        $results[] = $result;
        return $result->alias;
    }

    /**
     * Search file relative path by document full alias.
     *
     * @param string $fullAlias value from table #__joomdoc column full_alias
     * @return string value from table #__joomdoc column columns parent_path and path
     */
    public function searchRelativePathByFullAlias ($fullAlias) {
        if (!JString::trim($fullAlias)) {
            return null;
        }
        $config =& JoomDOCConfig::getInstance();
        if ($config->virtualFolder)
            // if turn on virtual folder try to complete full document alias
            if (($rootPath = JoomDOCFileSystem::getRelativePath($config->path))) {
                // search for full alias of virtual root parent
                $rootFullAlias = $this->searchFullAliasByPath($rootPath);
                if ($rootFullAlias)
                    // add parent full alias on begin of document virtual full alias
                    $fullAlias = $rootFullAlias . DS . $fullAlias;
                else
                	// add virtual root path parent on begin of document virtual full alias
                    $fullAlias = $rootPath . DS . $fullAlias;
            }
        // search for file relative path by document full alias
        $this->_db->setQuery('SELECT `path` FROM `#__joomdoc` WHERE `full_alias` = ' . $this->_db->quote($fullAlias) . ' ORDER BY `version` DESC', 0, 1);
        return $this->_db->loadResult();
    }
    
    /**
     * Get ID of document of last path parent.
     *
     * @param string $path relative file path
     * @return int document ID, null of not found
     */
    public function getParentDocumentID ($path) {
        static $results;
        // last parent path of path
        $parentPath = $subParentPath = JoomDOCFileSystem::getParentPath($path);

        // search in cache
        if (is_array($results))
            foreach ($results as $result)
                if ($result->subparentPath == $subParentPath)
                    return $result->documentID;

        // generate parents tree
        while ($parentPath) {
            $where[] = '`path` = ' . $this->_db->quote($parentPath);
            $parentPath = JoomDOCFileSystem::getParentPath($parentPath);
        }

        if (isset($where)) {
            // search last parent path with document
            $this->_db->setQuery('SELECT `id`, `path` FROM `#__joomdoc` WHERE (' . implode(' OR ', $where) . ') AND `id` IN (SELECT MIN(`id`) FROM `#__joomdoc` GROUP BY `path`)');
            $row =& $this->_db->loadObject();

            // cache result
            $result = new JObject();
            $result->parentPath = $row ? $row->path : null;
            $result->subparentPath = $subParentPath;
            $result->documentID = $row ? $row->id : null;
            $results[] = $result;

            return $result->documentID;
        }
        return null;
    }

    /**
     * Set document state by file path.
     *
     * @param string $path file path
     * @param int $value new state value
     * @return boolean
     */
    public function setPublish ($path, $value) {
        if (($candidate = $this->searchRelativePathByFullAlias($path)))
            $path = $candidate;
        if (($id = $this->searchIdByPath($path)) && JoomDOCAccessDocument::editState($id, $this->searchCheckedOutByPath($path))) {
            $this->_db->setQuery('UPDATE `#__joomdoc` SET `state` = ' . $value . ' WHERE `id` = ' . $id);
            return $this->_db->query();
        }
        return false;
    }

    /**
     * Get relative path's of all trashed document's.
     *
     * @return array relative path's
     */
    public function emptytrash () {
        $this->_db->setQuery('SELECT `path` FROM `#__joomdoc` WHERE `state` = ' . JOOMDOC_STATE_TRASHED . ' AND `id` IN (SELECT MIN(`id`) FROM `#__joomdoc` GROUP BY `path`)');
        $paths = $this->_db->loadResultArray();
        $count = count($paths);
        if ($count) {
            $paths = array_map(array($this->_db, 'quote'), $paths);
            $this->_db->setQuery('DELETE FROM `#__joomdoc` WHERE `path` IN (' . implode(',', $paths) . ')');
            $this->_db->query();
        }
        return $count;
    }

    /**
     * Trash documents.
     *
     * @param array $cid document's id's
     * @return boolean
     */
    public function trash ($cid) {
        $this->_db->setQuery('UPDATE `#__joomdoc` SET `state` = ' . JOOMDOC_STATE_TRASHED . ' WHERE `id` IN (' . implode(',', $cid) . ')');
        $this->_db->query();
        return $this->_db->getAffectedRows();
    }
}
?>