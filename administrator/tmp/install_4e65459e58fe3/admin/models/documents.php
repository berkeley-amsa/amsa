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

class JoomDOCModelDocuments extends JoomDOCModelList {

    /**
     * Paths to search documents.
     *
     * @var array
     */
    var $paths;

    /**
     * Database connector.
     *
     * @var JDatabaseMySQL
     */
    var $_db;
    /**
     * Filter keywords
     *
     * @var string
     */
    private $keywords;
    /**
     * Create object and set filter.
     *
     * @param array $config
     * @return void
     */
    function __construct ($config = array ()) {

        $this->filter[JOOMDOC_FILTER_TITLE] = JOOMDOC_STRING;
        $this->filter[JOOMDOC_FILTER_FILENAME] = JOOMDOC_STRING;
        $this->filter[JOOMDOC_FILTER_ACCESS] = JOOMDOC_INT;
        $this->filter[JOOMDOC_FILTER_CATEGORY] = JOOMDOC_INT;
        $this->filter[JOOMDOC_FILTER_STATE] = JOOMDOC_STRING;
        $this->filter[JOOMDOC_FILTER_ORDERING] = JOOMDOC_STRING;
        $this->filter[JOOMDOC_FILTER_DIRECTION] = JOOMDOC_STRING;
        $this->filter[JOOMDOC_FILTER_START] = JOOMDOC_INT;
        $this->filter[JOOMDOC_FILTER_LIMIT] = JOOMDOC_INT;
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(JOOMDOC_FILTER_ID, JOOMDOC_FILTER_TITLE, JOOMDOC_FILTER_STATE, JOOMDOC_FILTER_ACCESS, JOOMDOC_FILTER_CREATED, JOOMDOC_FILTER_ORDERING, JOOMDOC_ORDER_ORDERING, JOOMDOC_FILTER_HITS, JOOMDOC_FILTER_PUBLISH_UP, JOOMDOC_FILTER_PUBLISH_DOWN, JOOMDOC_FILTER_PATH, JOOMDOC_FILTER_UPLOAD);
        }

        parent::__construct($config);
    }

    /**
     * Add filter keywords values.
     *
     * @param string $keywords
     */
    public function setKeywords ($keywords) {
        $this->keywords = JString::trim($keywords);
    }

    /**
     * Get SQL query for documents list.
     *
     * @return string
     */
    protected function getListQuery () {
        $mainframe =& JFactory::getApplication();
        /* @var $mainframe JApplication */

        // have concrete paths of search files
        if (count(($paths = $this->getState(JOOMDOC_FILTER_PATHS)))) {
            // cleanup paths and quote
            foreach ($paths as $i => $path) {
                if (($path = JString::trim($path)))
                    $paths[$i] = $this->_db->quote($path);
                else
                    unset($paths[$i]);
            }
            if (count($paths) && (!isset($this->docids) && !isset($this->fileids))) {
                $paths = implode(',', $paths);
                // search latest version of files document
                $this->_db->setQuery('SELECT MIN(`id`) FROM `#__joomdoc` WHERE `path` IN (' . $paths . ') GROUP BY `path`');
                $this->docids =& $this->_db->loadResultArray();
                // search lastest version ids of file
                $this->_db->setQuery('SELECT MAX(`id`) FROM `#__joomdoc_file` WHERE `path` IN (' . $paths . ') GROUP BY `path`');
                $this->fileids =& $this->_db->loadResultArray();
                $this->_db->setQuery('SELECT `path`, SUM(`hits`) AS `hits` FROM `#__joomdoc_file` WHERE `path` IN (' . $paths . ') GROUP BY `path`');
                $this->hits =& $this->_db->loadObjectList('path');
            }
        }

        if (!isset($this->docids) && !isset($this->fileids)) {
            $this->docids = $this->fileids = array();
        }

        if ($mainframe->isSite()) {

            /* on site control document access */
            $published = JoomDOCModelList::getDocumentPublished();

            $query = 'SELECT `document`.`id`, `document`.`title`, `document`.`description`, `document`.`full_alias`, `document`.`modified`, `document`.`created`, `document`.`created_by`, `document`.`state`, `document`.`params`, `document`.`favorite`, `document`.`ordering`, `document`.`publish_up`, (' . $published . ') AS `published`, `file`.`upload`, `file`.`path`, `document`.`checked_out` ';

        } else {
            $query = 'SELECT `file`.`upload`, `file`.`path`, `document`.`id`, `document`.`title`, `document`.`ordering`, `document`.`access`, `document`.`publish_up`, `document`.`publish_down`, `document`.`state` AS `published`, `document`.`checked_out`, `document`.`checked_out_time`, `document`.`created_by`, `document`.`state`, `document`.`favorite`, `document`.`parent_path`, `editor`.`name` AS `editor`, `access`.`' . (J16 ? 'title' : 'name') . '` AS `access_title`, `document`.`full_alias` ';
        }

        // complet query from/join state
        $query .= 'FROM `#__joomdoc_file` AS `file` ';
        $query .= 'LEFT JOIN `#__joomdoc` AS `document` ON `file`.`path` = `document`.`path` ';
        // user who checked out document
        $query .= 'LEFT JOIN `#__users` AS `editor` ON `editor`.`id` = `document`.`checked_out` ';
        // document access name, in Joomla 1.6.x is used different table then Joomla 1.5.x
        $query .= 'LEFT JOIN `#__' . (J16 ? 'viewlevels' : 'groups') . '` AS `access` ON `access`.`id` = `document`.`access` ';

        // filter for files
        if (count($this->fileids)) {
            $where[] = '(`file`.`id` IN (' . implode(', ', $this->fileids) . ') OR `file`.`id` IS NULL)';
        }
        // filter for documents
        if (count($this->docids)) {
            $where[] = '(`document`.`id` IN (' . implode(', ', $this->docids) . ') OR `document`.`id` IS NULL)';
        }
        // without output
        if (!count($this->fileids) && !count($this->docids)) {
            $where[] = '0';
        }

        // filter for keywords
        if ($this->keywords) {
            $keywords = JString::strtolower($this->keywords);
            // split to unique words
            $keywords = explode(' ', $keywords);
            if (is_array($keywords)) {
                // search for each word extra
                foreach ($keywords as $keyword) {
                    $keyword = JString::trim($keyword);
                    if ($keyword) {
                        $keyword = $this->_db->quote('%' . $keyword . '%');
                        $filter[] = 'LOWER(`document`.`title`) LIKE ' . $keyword;
                        $filter[] = 'LOWER(`file`.`path`) LIKE ' . $keyword;
                        $filter[] = 'LOWER(`file`.`content`) LIKE ' . $keyword;
                    }
                }
            }
            if (isset($filter)) {
                $where[] = '(' . implode(' OR ', $filter) . ')';
            }
        }

        if (isset($where)) {
            $query .= ' WHERE ' . implode(' AND ', $where);
        }

        if (in_array($this->state->get(JOOMDOC_FILTER_ORDERING), array(JOOMDOC_ORDER_PATH, JOOMDOC_ORDER_UPLOAD, JOOMDOC_ORDER_HITS, JOOMDOC_ORDER_TITLE, JOOMDOC_ORDER_ORDERING))) {
            $query .= ' ORDER BY `' . $this->_db->getEscaped($this->state->get(JOOMDOC_FILTER_ORDERING) . '` ' . JString::strtoupper($this->state->get(JOOMDOC_FILTER_DIRECTION)));
        }

        return $query;
    }

    /**
     * Get documents list.
     *
     * @return array
     */
    public function getItems () {
        //$items =& $this->_getList($this->getListQuery(), $this->state->get(JOOMDOC_FILTER_START), $this->state->get(JOOMDOC_FILTER_LIMIT));
        $items =& $this->_getList($this->getListQuery());
        $count = count($items);
        for ($i = 0; $i < $count; $i++) {
            $item =& $items[$i];
            $item->hits = isset($this->hits[$item->path]) ? $this->hits[$item->path]->hits : 0;
        }
        return $items;
    }

    /**
     * Set documents as favorite/unfavorite
     *
     * @param array $ids   documents IDs
     * @param int   $value value use constantS JOOMDOC_FAVORITE/JOOMDOC_STANDARD to set as favorite/unfavorite
     * @return int num of affected rows
     */
    function setFavorite ($ids, $value) {
        if (count($ids)) {
            JArrayHelper::toInteger($ids);
            $this->_db->setQuery(sprintf('UPDATE `#__joomdoc` SET `favorite` = %d WHERE `id` IN (%s)', $value, implode(', ', $ids)));
            $this->_db->query();
            return $this->_db->getAffectedRows();
        }
        return 0;
    }
}
?>