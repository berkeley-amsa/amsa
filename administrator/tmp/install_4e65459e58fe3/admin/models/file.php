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

class JoomDOCModelFile extends JoomDOCModelList {
    /**
     * Get list of file versions.
     *
     * @param JObject $filter
     * @return array
     */
    function getData (&$filter) {
        $joins = ' FROM `#__joomdoc_file` AS `file` ';
        $joins .= 'LEFT JOIN `#__users` AS `uploader` ON `file`.`uploader` = `uploader`.`id` ';

        $where = ' WHERE `path` = ' . $this->_db->quote($filter->path);
        $where .= ' AND LOWER(`uploader`.`name`) LIKE ' . $this->_db->quote('%' . $filter->uploader . '%') . ' ';

        $this->_db->setQuery('SELECT COUNT(*)' . $joins . $where);
        $filter->total = (int) $this->_db->loadResult();

        if ($filter->total <= $filter->offset && $filter->limit) {
            $filter->offset = floor($filter->total / $filter->limit) * $filter->limit;
        }

        $query = 'SELECT `file`.*, `uploader`.`name` ';
        $query .= $joins . $where . ' ORDER BY `' . $filter->listOrder . '` ' . $filter->listDirn;

        return $this->_getList($query, $filter->offset, $filter->limit);
    }
    /**
     * Get file last version document.
     *
     * @param mixed $filter stdClass or null if not found
     */
    public function getDocument (&$filter) {
        $this->_db->setQuery('SELECT * FROM `#__joomdoc` WHERE `path` = ' . $this->_db->quote($filter->path) . ' ORDER BY `version` DESC', 0, 1);
        return $this->_db->loadObject();
    }

    /**
     * Save file download hits.
     *
     * @param string $path file relative path
     * @return boolean
     */
    public function saveHits ($path, $version = null) {
                $this->_db->setQuery('UPDATE `#__joomdoc_file` SET `hits`=`hits`+1 WHERE `path` = ' . $this->_db->quote($path) . ' AND `version` = ' . (int) $version);
        return $this->_db->query();
    }
}
?>