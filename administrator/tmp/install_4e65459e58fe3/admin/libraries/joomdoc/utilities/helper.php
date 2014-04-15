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

class JoomDOCHelper {

    /**
     * Get current logged user allowed actions.
     *
     * @return JObject
     */
    public static function getActions ($section) {
        $user =& JFactory::getUser();
        /* @var $user JUser */
        $result = new JObject();
        foreach (get_defined_constants() as $key => $value)
            if (JString::strpos($key, 'JOOMDOC_CORE_') === 0)
                $result->set($value, $user->authorise($value, JoomDOCHelper::getAction($section)));
        return $result;
    }

    /**
     * Get task parameter format.
     *
     * @param string $entity entity name
     * @param string $task task name
     * @return string
     */
    public static function getTask ($entity, $task) {
        return $entity . '.' . $task;
    }

    /**
     * Get access action format.
     *
     * @param string $section access section name
     * @return string
     */
    public static function getAction ($section) {
        return JOOMDOC_OPTION . '.' . $section;
    }

    /**
     * Set component submenu.
     *
     * @param string $view opened view page
     * @return void
     */
    public function setSubmenu ($view) {
        JSubMenuHelper::addEntry(JText::_('JOOMDOC_CONTROL_PANEL'), JoomDOCRoute::viewJoomDOC(), $view == JOOMDOC_JOOMDOC);
        JSubMenuHelper::addEntry(JText::_('JOOMDOC_DOCUMENTS'), JoomDOCRoute::viewDocuments(), $view == JOOMDOC_DOCUMENTS);
        JSubMenuHelper::addEntry(JText::_('JOOMDOC_UPGRADE'), JoomDOCRoute::viewUpgrade(), $view == JOOMDOC_UPGRADE);
    }

    /**
     * Display number in human readable format without wrap.
     *
     * @param int $number
     * @return string
     */
    public function number ($number) {
        return JoomDOCHelper::nowrap(number_format((int) $number, 0, '', JText::_('JOOMDOC_THOUSANDS_SEPARATOR')));
    }

    /**
     * Display date uploade in human readable format without wrap.
     *
     * @param int $date unix timestamp
     * @return string
     */
    public function uploaded ($date, $timestamp = true) {
        if (!$timestamp && $date == '0000-00-00 00:00:00')
            return '';
        return JoomDOCHelper::nowrap(JHtml::date($timestamp ? date('Y-m-d H:i:s', $date) : $date, JText::_('JOOMDOC_UPLOADED_DATE_' . JTAG)));
    }

    /**
     * Make text nowrap.
     *
     * @param string $text
     * @return string
     */
    public function nowrap ($text) {
        return '<span class="nowrap">' . $text . '</span>';
    }

    /**
     * Get CSS class name for file extension icon.
     *
     * @param string $filename file name
     * @return string CSS class name for icon, empty string if icon is not available
     */
    public function getFileIconClass ($filename) {
        static $cache;
        if (is_null($cache)) {
            $document =& JFactory::getDocument();
            /* @var $document JDocumentHTML */
            $config =& JoomDOCConfig::getInstance();
            /* @var $config JoomDOCConfig */
            $iconsFolder = JOOMDOC_PATH_ICONS . DS . $config->iconTheme;
            if (JFolder::exists($iconsFolder)) {
                $icons = JFolder::files($iconsFolder, '.png$');
                foreach ($icons as $icon) {
                    $iconname = JFile::getName($icon);
                    $icontypes = str_replace('.png', '', $iconname);
                    $classname = 'joomdoc-' . str_replace('.png', '', $icontypes);
                    $style = '.' . $classname . ' { background-image: url("' . JOOMDOC_ICONS . $config->iconTheme . '/' . $iconname . '"); }';
                    $document->addStyleDeclaration($style);
                    $types = explode('-', $icontypes);
                    foreach ($types as $type)
                        $cache[$type] = $classname;
                }
            }
        }
        $extension = JFile::getExt($filename);
        if (isset($cache[$extension]))
            return $cache[$extension];
        return '';
    }

    /**
     * Crop text into set length. Before strip HTML tags. After croping add on end ender string (e.q. ...).
     * If text is shorter then legth return text without ender string. Crop end with number or letter. No by chars like: , . - _ etc.
     *
     * @param string $text   string to crop
     * @param int    $length crop length
     * @param string $ender  ender string, default ...
     * @return string
     */
    function crop ($text, $length, $ender = '...') {
        $text = strip_tags($text);
        $text = JString::trim($text);
        $strlen = JString::strlen($text);
        if ($strlen <= $length)
            return $text;
        $lastSpace = JString::strpos($text, ' ', $length);
        if ($lastSpace === false) {
            $crop = JString::substr($text, 0, $length);
        } else {
            $crop = JString::substr($text, 0, $lastSpace);
        }
        static $noLetters;
        if (is_null($noLetters)) {
            $noLetters = array('.', ',', '-', '_', '!', '?', '(', ')', '%', '', '@', '#', '$', '^', '&', '*', '+', '=', '"', '\'', '/', '\\', '§', '<', '>', ':', '{', '}', '[', ']');
        }
        do {
            $strlen = JString::strlen($crop);
            $lastChar = JString::substr($crop, ($strlen - 1), 1);
            if (in_array($lastChar, $noLetters)) {
                $crop = JString::substr($crop, 0, ($strlen - 1));
            } else {
                break;
            }
        } while (true);
        $crop = JString::trim($crop);
        if ($crop)
            $crop .= $ender;
        return $crop;
    }

    /**
     * Convert array to javascript array string and put into html head.
     *
     * @param string $name name of javascript array
     * @param array $items array items, are inserted as string safed with addslashes method
     * @return void
     */
    function jsArray ($name, $items) {
        $js = 'var ' . $name . ' = new Array(';
        if (count($items)) {
            $items = array_map('addslashes', $items);
            $js .= '"' . implode('", "', $items) . '"';
        }
        $js .= ');';
        $document =& JFactory::getDocument();
        /* @var $document JDocumentHTML */
        $document->addScriptDeclaration($js);
    }

    /**
     * Get meta description text format. Clean text and crop to 150 characters.
     *
     * @param string $text
     * @return string
     */
    function getMetaDescriptions ($text) {
        $text = JFilterOutput::cleanText($text);
        $text = JString::trim($text);
        if (JString::strlen($text) <= 150) {
            return $text;
        }
        $text = JString::substr($text, 0, 150);
        $lastFullStop = JString::strrpos($text, '.');
        if ($lastFullStop !== false) {
            $text = JString::substr($text, 0, $lastFullStop + 1);
        }
        $text = JString::trim($text);
        return $text;
    }

    /**
     * Add after title sitename to complet page title.
     *
     * @param string $title
     * @return string
     */
    function getCompletTitle ($title) {
        $mainframe =& JFactory::getApplication();
        /* @var $mainframe JApplication */
        $candidates[] = JString::trim($title);
        $candidates[] = JString::trim($mainframe->getCfg('sitename'));
        JoomDOCHelper::cleanArray($candidates);
        $completSiteName = implode(' - ', $candidates);
        return $completSiteName;
    }

    /**
     * Get first no empty item from array.
     *
     * @param array $array
     * @return mixed
     */
    function getFirstNoEmpty (&$array) {
        foreach ($array as $item) {
            if (!empty($item)) {
                return $item;
            }
        }
        return '';
    }

    /**
     * Cleanup array. Unset or empty items.
     *
     * @param array $array
     */
    function cleanArray (&$array) {
        foreach ($array as $key => $item) {
            if (empty($item)) {
                unset($array[$key]);
            }
        }
    }

    /**
     * Return boolean mark if is possible to display item modified date.
     * Modified cannot be emty and cannot be the same as created date.
     *
     * @param string $created created date in database format in GMT0
     * @param string $modified modified date in database format in GMT0
     * @return booelan
     */
    function canViewModified ($created, $modified) {
        switch (JString::strtoupper(JString::trim($modified))) {
            case JString::strtoupper(JString::trim($created)):
            case JFactory::getDbo()->getNullDate():
            case '':
                return false;
        }
        return true;
    }

    /**
     * Get document asset format.
     * For example document ID is 7:
     * return com_joomdoc.document.7
     *
     * @param int $docid document ID
     * @return string
     */
    function getDocumentAsset ($docid) {
        return sprintf('%s.%s.%d', JOOMDOC_OPTION, JOOMDOC_DOCUMENT, $docid);
    }

    /**
     * Test if given object has property named document with property id.
     *
     * @param stdClass $object
     * @return mixed document ID if found or null
     */
    function getDocumentID (&$object) {
        if (isset($object->document))
            return $object->document->id;
        return null;
    }

    /**
     * Test if given object has property named document with property full_alias.
     *
     * @param stdClass $object
     * @return mixed document alias if found or null
     */
    function getDocumentAlias (&$object) {
        if (isset($object->document->full_alias))
            return $object->document->full_alias;
        elseif (isset($object->full_alias))
            return $object->full_alias;
        return null;
    }

    /**
     * Check if item is checked.
     *
     * @param stdClass $object
     * @return boolean
     */
    function isChecked (&$object) {
        if (isset($object->document)) {
            $document =& $object->document;
        } else {
            $document =& $object;
        }
        if (isset($document->checked_out)) {
            return $document->checked_out != 0 && $document->checked_out == JFactory::getUser()->id;
        }
        return false;
    }

    function showLog () {
        $keywords = array('SELECT', 'MIN', '<br/>FROM', '<br/>WHERE', '<br/>GROUP BY', 'SUM', '<br/>LEFT JOIN', 'ASC', 'DESC', 'AS', 'ON', 'IS NULL', 'AND', '<br/>ORDER BY', 'OR', '<br/>LIMIT', 'IN', 'MAX', '(', ')', '`', '\'', '.', ',');
        foreach ($keywords as $i => $keyword) {
            $keywordsReplaces[] = '<strong>' . $keyword . '</strong>';
            $keywords[$i] = strip_tags($keyword);
        }

        $keywords[] = '#__';
        $keywordsReplaces[] = JFactory::getDbo()->getPrefix();

        foreach (JFactory::getDbo()->getLog() as $key => $query) {
            $query = preg_replace('/`([^`]*)`/', '`<span style="color: blue">$1</span>`', $query);
            $query = preg_replace('/\'([^\']*)\'/', '\'<span style="color: green">$1</span>\'', $query);
            $query = preg_replace('/(\d+)/', '<span style="color: red">$1</span>', $query);
            $query = str_replace($keywords, $keywordsReplaces, $query);
            echo '<p>' . $query . '</p>';
        }
    }

    /**
     * Show mainframe message with information about set clipboard operation.
     */
    function clipboardInfo () {
        $operation = JoomDOCFileSystem::getOperation();
        $paths = JoomDOCFileSystem::getOperationPaths();
        if (!is_null($operation) && count($paths)) {
            $mainframe =& JFactory::getApplication();
            /* @var $mainframe JApplication */
            $msg = 'JOOMDOC_CPMV_INFO_' . JString::strtoupper($operation);
            $paths = implode(', ', $paths);
            $mainframe->enqueueMessage(JText::sprintf($msg, $paths));
        }
    }

    /**
     * Show mainframe message if current folder is writable.
     *
     * @param string $path absolute path
     */
    function folderInfo ($path) {
        if (!is_writable($path)) {
            $mainframe =& JFactory::getApplication();
            /* @var $mainframe JApplication */
            $mainframe->enqueueMessage(JText::_('JOOMDOC_FOLDER_UNWRITABLE'), 'notice');
        }
    }
}
?>