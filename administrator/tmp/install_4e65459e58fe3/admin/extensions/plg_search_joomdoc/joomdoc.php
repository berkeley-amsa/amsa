<?php
/**
 * @version		$Id$
 * @package		Joomla.Administrator
 * @subpackage	JoomDOC
 * @author      ARTIO s.r.o., info@artio.net, http:://www.artio.net
 * @copyright	Copyright (C) 2011 Artio s.r.o.. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');
jimport('joomla.filesystem.file');

/* Joomla 1.5.x */

$mainframe =& JFactory::getApplication();
/* @var $mainframe JSite */

$mainframe->registerEvent('onSearch', 'plgSearchJoomDOConSearch');
$mainframe->registerEvent('onSearchAreas', 'plgSearchJoomDOConContentSearchAreas');

function plgSearchJoomDOConContentSearchAreas () {
    return plgSearchJoomDOC::onContentSearchAreas();
}

function plgSearchJoomDOConSearch ($text, $phrase = '', $ordering = '', $areas = null) {
    return plgSearchJoomDOC::onContentSearch($text, $phrase, $ordering, $areas);
}

/* Joomla 1.6.x */

class plgSearchJoomDOC extends JPlugin {

    function onContentSearchAreas () {
        $language =& JFactory::getLanguage();
        /* @var $language JLanguage */
        $language->load('plg_search_joomdoc', JPATH_ADMINISTRATOR);
        return array('joomdoc' => JText::_('PLG_SEARCH_JOOMDOC_AREA'));
    }

    function onContentSearch ($text, $phrase = '', $ordering = '', $areas = null) {
        if (!JString::trim($text)) {
            // on empty search text return empty array
            return array();
        }
        if (is_array($areas) && !array_intersect($areas, array_keys(plgSearchJoomDOC::onContentSearchAreas()))) {
            // if no search in JoomDOC areas return empty array
            return array();
        }

        // import component JoomDOC framework
        $jdcAdm = JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_joomdoc' . DS;
        $jdcSit = JPATH_SITE . DS . 'components' . DS . 'com_joomdoc' . DS;
        $jdcLib = $jdcAdm . 'libraries' . DS . 'joomdoc' . DS;

        $includes['JoomDOCRoute'] = $jdcLib . 'application' . DS . 'route.php';
        $includes['JoomDOCMenu'] = $jdcLib . 'application' . DS . 'menu.php';
        $includes['JoomDOCConfig'] = $jdcLib . 'application' . DS . 'config.php';
        $includes['JoomDOCFileSystem'] = $jdcLib . 'filesystem' . DS . 'filesystem.php';
        $includes['JoomDOCString'] = $jdcLib . 'utilities' . DS . 'string.php';

        $includes[] = $jdcAdm . 'defines.php';
        $includes[] = $jdcSit . 'assets' . DS . 'j16' . DS . 'index.php';

        // disable import Joomla 1.6.x framework in J16Adapter
        $importLibraries = false;

        foreach ($includes as $classname => $include) {
            if (!JFile::exists($include)) {
                // JoomDOC is propably uninstalled or corupted
                return array();
            }
            is_string($classname) ? JLoader::register($classname, $include) : include_once($include);
        }
        JModel::addIncludePath($jdcAdm . 'models');
        JTable::addIncludePath($jdcAdm . 'tables');

        // import Joomla framework
        $db =& JFactory::getDbo();
        /* @var $db JDatabase database connector */
        $app =& JFactory::getApplication();
        /* @var $app JSite */
        $user =& JFactory::getUser();
        /* @var $user JUser logged user */
        $language =& JFactory::getLanguage();
        /* @var $language JLanguage */
        $date =& JFactory::getDate();
        /* @var $date JDate support for work with date */

        // prepare environment params
        $groups = J16 ? $user->getAuthorisedViewLevels() : J16User::getAuthorisedViewLevels();
        /* @var $groups string user allowed acceess levels */
        $tag = $language->getTag();
        /* @var $tag string used language tag like en-GB or cs-CZ*/
        $nullDate = $db->quote($db->getNullDate());
        /* @var $nullDate string null date in database format */
        $now = $db->quote($date->toMySQL());
        /* @var $now string current date in GMT0 in database format */

        // prepare SQL WHERE criteria
        $wheres = array();
        // according to type of searching
        switch ($phrase) {
            case 'exact':
                // exactly phrase
                $keywords = $db->Quote('%' . JString::strtolower(JString::trim($text)) . '%');
                $items[] = 'LOWER(`document`.`title`) LIKE ' . $keywords;
                $items[] = 'LOWER(`document`.`description`) LIKE ' . $keywords;
                $items[] = 'LOWER(`file`.`path`) LIKE ' . $keywords;
                $items[] = 'LOWER(`file`.`content`) LIKE ' . $keywords;
                $where[] = '(' . implode(') OR (', $items) . ') ';
                break;
            case 'all':
            case 'any':
            default:
                // all words or any word or default
                $keywords = explode(' ', $text);
                // split to words
                $wheres = array();
                // search for each word
                foreach ($keywords as $keyword) {
                    $keyword = JString::trim($keyword);
                    if ($keyword) {
                        $keyword = $db->Quote('%' . JString::strtolower($keyword) . '%');
                        $items[] = 'LOWER(`document`.`title`) LIKE ' . $keyword;
                        $items[] = 'LOWER(`document`.`description`) LIKE ' . $keyword;
                        $items[] = 'LOWER(`file`.`path`) LIKE ' . $keyword;
                        $items[] = 'LOWER(`file`.`content`) LIKE ' . $keyword;
                        $parts[] = implode(' OR ', $items);
                    }
                }
                if (isset($parts)) {
                    if ($phrase == 'all') {
                        $where[] = '(' . implode(') AND (', $parts) . ') ';
                    } else {
                        $where[] = '(' . implode(') OR (', $parts) . ') ';
                    }
                }
                break;
        }

        // published properties, if file hasn't document values are null
        $where[] = '(`document`.`state` = ' . JOOMDOC_STATE_PUBLISHED . ' OR `document`.`state` IS NULL)';
        $where[] = '(`document`.`publish_up` = ' . $nullDate . ' OR `document`.`publish_up` <= ' . $now . ' OR `document`.`publish_up` IS NULL)';
        $where[] = '(`document`.`publish_down` = ' . $nullDate . ' OR `document`.`publish_down` >= ' . $now . ' OR `document`.`publish_down` IS NULL)';
        // user access level
        if (count($groups)) {
            $where[] = '(`document`.`access` IN (' . implode(',', $groups) . ') OR `document`.`access` IS NULL)';
        }

        // only for last versions
        $where[] = '(`document`.`id` IN (SELECT MIN(`id`) FROM `#__joomdoc` GROUP BY `path`) OR `document`.`id` IS NULL)';
        $where[] = '(`file`.`id` IN (SELECT MAX(`id`) FROM `#__joomdoc_file` GROUP BY `path`))';

        $where = ' WHERE ' . implode(' AND ', $where) . ' ';

        // prepare SQL ORDER BY criteria
        switch ($ordering) {
            case 'oldest':
                // oldest items first (oldest documents created or oldest file uploaded)
                $order = ' ORDER BY `document`.`created` ASC, `file`.`upload` ASC ';
                break;

            case 'popular':
                // most hits items first
                $order = ' ORDER BY `file`.`hits` DESC, `document`.`title` ASC, `file`.`path` ASC ';
                break;

            case 'alpha':
                // alphabetically (document title or file name)
                $order = ' ORDER BY `document`.`title` ASC, `file`.`path` ASC ';
                break;

            case 'category':
                // by parent folder (parent folder name or document title)
                $order = ' ORDER BY `document_parent`.`title` ASC, `file_parent`.`path` ASC ';
                break;

            case 'newest':
            default:
                // newest items first (newest documents created or file uploaded)
                $order = ' ORDER BY `document`.`created` DESC, `file`.`upload` DESC ';
                break;
        }

        // prepare SQL QUERY
        // document title or file name as search item title (document title primary)
        $query = 'SELECT COALESCE(`document`.`title`,`file`.`path`) AS `title`,  ';
        // document description or file content as text
        $query .= 'COALESCE(`document`.`description`,`file`.`content`) AS `text`, ';
        // document created or file upload as search item created date (document created primary)
        $query .= 'COALESCE(`document`.`created`,`file`.`upload`) AS `created`,  ';
        // document parent folder document title or file name as search item section (parent folder document primary)
        $query .= 'COALESCE(`document_parent`.`title`,`file_parent`.`path`) AS `section`,  ';
        // still on same browser target page on open search item
        $query .= '2 AS `browsernav`,  ';
        // document alias or file path as param to URL
        $query .= '`document`.`full_alias`, `file`.`path` ';

        $query .= 'FROM `#__joomdoc_file` AS `file`  ';
        $query .= 'LEFT JOIN `#__joomdoc` AS `document` ON `file`.`path` = `document`.`path` ';
        // search for items parents
        $query .= 'LEFT JOIN `#__joomdoc` AS `document_parent` ON `document`.`parent_path` = `document_parent`.`path`  ';
        $query .= 'LEFT JOIN `#__joomdoc_file` AS `file_parent` ON `document`.`parent_path` = `file_parent`.`path`  ';

        // add WHERE, GROUP BY and ORDER BY criteria
        $query .= $where . 'GROUP BY `document`.`path`, `file`.`path`' . $order;

        // get db query output
        $db->setQuery($query);
        $rows =& $db->loadObjectList();

        // add to found items URL
        foreach ($rows as $row) {
            $GLOBALS['joomDOCPath'] = $row->path;
            if (is_null($row->full_alias))
                // item hasn't document - title is path
                $row->title = JFile::getName($row->title);
            $row->href = JRoute::_(JoomDOCRoute::viewDocuments($row->path, false));
        }

        unset($GLOBALS['joomDOCPath']);

        return $rows;
    }
}
?>