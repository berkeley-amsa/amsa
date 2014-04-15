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

class JoomDOCTableDocument extends JTable {

    public $id = null;
    public $title = null;
    public $alias = null;
    public $full_alias = null;
    public $description = null;
    public $checked_out = null;
    public $checked_out_time = null;
    public $state = null;
    public $access = null;
    public $params = null;
    public $ordering = null;
    public $created = null;
    public $created_by = null;
    public $modified = null;
    public $modified_by = null;
    public $publish_up = null;
    public $publish_down = null;
    public $parent_path = null;
    public $path = null;
    public $version = null;
    public $versionNote = null;
    public $favorite = null;

    /**
     * Constructor.
     *
     * @param JDatabase A database connector object
     */

    public function __construct (&$db) {
        parent::__construct('#__joomdoc', 'id', $db);
    }

    /**
     * Method to compute the default name of the asset.
     * The default name is in the form `table_name.id`
     * where id is the value of the primary key of the table.
     *
     * @return	string
     * @since	1.6
     */
    protected function _getAssetName () {
        return JoomDOCHelper::getDocumentAsset($this->id);
    }

    /**
     * Method to return the title to use for the asset table.
     *
     * @return	string
     * @since	1.6
     */
    protected function _getAssetTitle () {
        return $this->title;
    }

    /**
     * Get the parent asset id for the record
     *
     * @return	int
     * @since	1.6
     */
    protected function _getAssetParentId ($table = null, $id = null) {
		// search parent document asset ID
        $this->_db->setQuery('SELECT `asset_id` FROM `#__joomdoc` WHERE `path` = ' . $this->_db->Quote($this->parent_path) . ' ORDER BY `version` DESC');
        if (($parentAssetId = (int) $this->_db->loadResult()))
            return $parentAssetId;
        // search component asset ID    
        $this->_db->setQuery('SELECT `id` FROM `#__assets` WHERE `name` = ' . $this->_db->Quote(JOOMDOC_OPTION));
        if (($parentAssetId = (int) $this->_db->loadResult()))
            return $parentAssetId;
        // return default parent asset ID    
        return parent::_getAssetParentId($table, $id);
    }

    /**
     * Reoder document in folder.
     *
     * @param array $where SQL WHERE criteria
     */
    public function reorder ($where) {
        if (!J16 && is_array($where)) {
            $where = implode(' ', $where);
        }
        return parent::reorder($where);
    }

    /**
     * Overloaded bind function to pre-process the params.
     *
     * @param array Named array
     * @return null|string	null is operation was satisfactory, otherwise returns an error
     */
    public function bind ($array, $ignore = '') {
        if (isset($array['params']) && is_array($array['params'])) {
            $registry = new JRegistry();
            $registry->loadArray($array['params']);
            $array['params'] = (string) $registry->toString();
        }
        $this->favorite = isset($array['favorite']) ? 1 : 0;
        if (J16 && isset($array['rules']) && is_array($array['rules'])) {
            $rules = new JRules($array['rules']);
            $this->setRules($rules);
        }
        parent::bind($array, $ignore);
        $this->parent_path = JoomDOCFileSystem::getParentPath($this->path);
        return true;
    }

    /**
     * Mode document up/down in folder.
     *
     * @param int $delta move direction (-1 down, 1 up)
     * @param array $where SQL WHERE criteria
     */
    public function move ($delta, $where) {
        if (!J16 && is_array($where)) {
            $where = implode(' ', $where);
        }
        return parent::move($delta, $where);
    }

    /**
     * Overload the store method for the Weblinks table.
     *
     * @param	boolean	Toggle whether null values should be updated.
     * @return	boolean	True on success, false on failure.
     * @since	1.6
     */
    public function store ($updateNulls = false) {
        $date = JFactory::getDate();
        /* @var $date JDate current date */
        $currentDate = $date->toMySQL();
        /* @var $currentDate string current date as MySQL datetime in GMT0 */
        $user = JFactory::getUser();
        /* @var $user JUser current logged user */
        $config = JoomDOCConfig::getInstance();
        /* @var $config JoomDOCConfig */
        $app = JFactory::getApplication();
        /* @var $app JApplication */
        if ($this->id) {
            // for exists document store modifier
            $this->modified = $currentDate;
            $this->modified_by = $user->get('id');
        } else {
            if (!intval($this->created))
                $this->created = $currentDate;
            if (empty($this->created_by))
                $this->created_by = $user->get('id');
            // add on end in list
            $this->_db->setQuery('SELECT MAX(`ordering`) FROM `#__joomdoc` WHERE `parent_path` = ' . $this->_db->quote($this->parent_path));
            $this->ordering = (int) $this->_db->loadResult() + 1;
        }
                // if user doesn't set alias use title
        if (!JString::trim($this->alias)) {
            $this->alias = $this->title;
        }
        // convert alias to safe string
        if ($app->getCfg('unicodeslugs') == 1) {
            $this->alias = JFilterOutput::stringURLUnicodeSlug($this->alias);
        } else {
            $this->alias = JFilterOutput::stringURLSafe($this->alias);
        }

        $this->full_alias = $this->alias;

        if ($this->parent_path) {
            // search alias of parent document
            $this->_db->setQuery('SELECT `full_alias` FROM `#__joomdoc` WHERE `path` = ' . $this->_db->Quote($this->parent_path) . ' ORDER BY `version` DESC', 0, 1);
            $parentAlias = $this->_db->loadResult();
            // create full alias with parent path
            if (!JString::trim($parentAlias)) {
                // if parent alias not available use parent path
                $parentAlias = $this->parent_path;
                // convert to path with normal slash
                $parentAlias = JPath::clean($this->parent_path, '/');
                // path segments
                $segments = explode('/', $parentAlias);
                if (is_array($segments)) {
                    // convert to URL safe string
                    $segments = array_map('JApplication::stringURLSafe', $segments);
                    // back to path
                    $parentAlias = implode('/', $segments);
                } else {
                    $parentAlias = '';
                }
            }
            if (JString::trim($parentAlias)) {
                $this->full_alias = $parentAlias . '/' . $this->alias;
            }
        }

        // update alias in child documents
        $query = 'SELECT `id`, `alias`, `full_alias` FROM `#__joomdoc` WHERE `id` IN ';
        $query .= '(SELECT MIN(`id`) FROM `#__joomdoc` WHERE `parent_path` = ' . $this->_db->Quote($this->path) . ' GROUP BY `path`)';
        $this->_db->setQuery($query);
        $childs =& $this->_db->loadObjectList();

        foreach ($childs as $child) {
            $fullAlias = $this->full_alias . '/' . $child->alias;
            if ($child->full_alias != $fullAlias) {
                $query = 'UPDATE `#__joomdoc` SET `full_alias` = ' . $this->_db->Quote($fullAlias) . ' WHERE `id` = ' . (int) $child->id;
                $this->_db->setQuery($query);
                $this->_db->query();
            }
        }

        return parent::store($updateNulls);
    }

    /**
     * Delete document and all versions by file path.
     *
     * @return boolean true/false - success/unsuccess
     */
    public function delete () {
        $query = 'DELETE FROM `#__joomdoc` WHERE `path` = ' . $this->_db->Quote($this->path);
        $this->_db->setQuery($query);
        return $this->_db->query();
    }

    /**
     * Method to set the publishing state for a row or list of rows in the database
     * table.  The method respects checked out rows by other users and will attempt
     * to checkin rows that it can after adjustments are made.
     *
     * @param	mixed	An optional array of primary key values to update.  If not
     * set the instance property value is used.
     * @param	integer The publishing state. eg. [0 = unpublished, 1 = published]
     * @param	integer The user id of the user performing the operation.
     * @return	boolean	True on success.
     * @since	1.0.4
     */
    public function publish ($pks = null, $state = 1, $userId = 0) {
        // Initialise variables.
        $k = $this->_tbl_key;

        // Sanitize input.
        JArrayHelper::toInteger($pks);
        $userId = (int) $userId;
        $state = (int) $state;

        // If there are no primary keys set check to see if the instance key is set.
        if (empty($pks)) {
            if ($this->$k) {
                $pks = array($this->$k);
            } // Nothing to set publishing state on, return false.
            else {
                $this->setError(JText::_('JLIB_DATABASE_ERROR_NO_ROWS_SELECTED'));
                return false;
            }
        }

        // Build the WHERE clause for the primary keys.
        $where = $k . '=' . implode(' OR ' . $k . '=', $pks);

        // Determine if there is checkin support for the table.
        if (!property_exists($this, 'checked_out') || !property_exists($this, 'checked_out_time')) {
            $checkin = '';
        } else {
            $checkin = ' AND (checked_out = 0 OR checked_out = ' . (int) $userId . ')';
        }

        // Update the publishing state for rows with the given primary keys.
        $this->_db->setQuery('UPDATE `' . $this->_tbl . '`' . ' SET `state` = ' . (int) $state . ' WHERE (' . $where . ')' . $checkin);
        $this->_db->query();

        // Check for a database error.
        if ($this->_db->getErrorNum()) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }

        // If checkin is supported and all rows were adjusted, check them in.
        if ($checkin && (count($pks) == $this->_db->getAffectedRows())) {
            // Checkin the rows.
            foreach ($pks as $pk) {
                $this->checkin($pk);
            }
        }

        // If the JTable instance value is in the list of primary keys that were set, set the instance.
        if (in_array($this->$k, $pks)) {
            $this->state = $state;
        }

        $this->setError('');
        return true;
    }
}
?>