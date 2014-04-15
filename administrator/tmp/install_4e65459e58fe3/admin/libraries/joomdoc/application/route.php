<?php

/**
 * @version		$Id$
 * @package		Joomla.Administrator
 * @subpackage	JoomDOC
 * @author      ARTIO s.r.o., info@artio.net, http:://www.artio.net
 * @copyright	Copyright (C) 2011 Artio s.r.o.. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

jimport('joomla.methods');

class JoomDOCRoute extends JRoute {

    /**
     * Get URL to open component.
     *
     * @return string
     */
    public function viewJoomDOC () {
        return 'index.php?option=' . JOOMDOC_OPTION;
    }

    /**
     * Get URL to open page with categories list.
     *
     * @return string
     */
    public function viewCategories () {
        return 'index.php?option=com_categories&extension=' . JOOMDOC_OPTION;
    }

    /**
     * Get URL to open page with documents.
     *
     * @param string file relative path
     * @param string document alias if false search for alias in database
     * @param boolean $short return only path and Itemid parameter
     * @return string
     */
    public function viewDocuments ($path = null, $alias = null, $short = false) {
        $itemID = null;
        JoomDOCRoute::frontend($path, $alias, $itemID);
        $query = ($path ? '&path=' . JoomDOCString::urlencode($path) : '') . ($itemID ? '&Itemid=' . $itemID : '');
        if ($short)
            return $query;
        return 'index.php?option=' . JOOMDOC_OPTION . '&view=' . JOOMDOC_DOCUMENTS . $query;
    }

    /**
     * Get URL to open edit document page.
     *
     * @param int $id document ID
     * @return string
     */
    public function editDocument ($id) {
        return 'index.php?option=' . JOOMDOC_OPTION . '&task=' . JoomDOCHelper::getTask(JOOMDOC_DOCUMENT, JOOMDOC_TASK_EDIT) . '&id=' . $id;
    }

    /**
     * Get URL to add new document.
     *
     * @param string $id file path
     */
    public function addDocument ($path) {
        return 'index.php?option=' . JOOMDOC_OPTION . '&task=' . JoomDOCHelper::getTask(JOOMDOC_DOCUMENT, JOOMDOC_TASK_ADD) . '&path=' . JoomDOCString::urlencode($path);
    }

    /**
     * Get URL to save document.
     *
     * @param int $id document ID
     * @return string
     */
    public function saveDocument ($id) {
        return 'index.php?option=' . JOOMDOC_OPTION . '&layout=edit&id=' . $id;
    }

    /**
     * Open documents list in modal window.
     *
     * @param int $id
     * @return string
     */
    public function modalDocuments ($folder = null) {
        return 'index.php?option=' . JOOMDOC_OPTION . '&view=' . JOOMDOC_DOCUMENTS . '&layout=modal&tmpl=component' . ($folder ? ('&path=' . JoomDOCString::urlencode($folder)) : '');
    }

    /**
     * Get URL to download file.
     *
     * @param string $path
     * @param string $alias document alias
     * @param int $version wanted file version
     * @return string
     */
    public function download ($path, $alias = null, $version = null) {
        $itemID = null;
        JoomDOCRoute::frontend($path, $alias, $itemID);
        return 'index.php?option=' . JOOMDOC_OPTION . '&task=' . JoomDOCHelper::getTask(JOOMDOC_DOCUMENT, JOOMDOC_TASK_DOWNLOAD) . '&path=' . JoomDOCString::urlencode($path) . ($version ? '&version=' . $version : '') . ($itemID ? '&Itemid=' . $itemID : '');
    }

    /**
     * Get URL to add file.
     *
     * @param string $path
     * @param string $alias document alias
     * @return string
     */
    public function add ($path, $alias = null) {
        return JoomDOCRoute::frontendDocumentTask(JOOMDOC_TASK_ADD, $path, $alias);
    }

    /**
     * Get URL to edit file.
     *
     * @param string $path
     * @param string $alias document alias
     * @return string
     */
    public function edit ($path, $alias = null) {
        return JoomDOCRoute::frontendDocumentTask(JOOMDOC_TASK_EDIT, $path, $alias);
    }

    /**
     * Get URL to publish document.
     *
     * @param string $path
     * @param string $alias document alias
     * @return string
     */
    public function publish ($path, $alias = null) {
        return JoomDOCRoute::frontendDocumentTask(JOOMDOC_TASK_PUBLISH, $path, $alias);
    }

    /**
     * Get URL to unpublish document.
     *
     * @param string $path
     * @param string $alias document alias
     * @return string
     */
    public function unpublish ($path, $alias = null) {
        return JoomDOCRoute::frontendDocumentTask(JOOMDOC_TASK_UNPUBLISH, $path, $alias);
    }

    /**
     * Get URL to delete file.
     *
     * @param string $path
     * @param string $alias document alias
     * @return string
     */
    public function deleteFile ($path, $alias = null) {
        return JoomDOCRoute::frontendDocumentTask(JOOMDOC_TASK_DELETEFILE, $path, $alias);
    }

    /**
     * Get URL to delete document.
     *
     * @param string $path
     * @param string $alias document alias
     * @return string
     */
    public function delete ($path, $alias = null) {
        return JoomDOCRoute::frontendDocumentTask(JOOMDOC_TASK_DELETE, $path, $alias, JOOMDOC_DOCUMENTS);
    }

    /**
     * Get URL to make task on frontend file/document.
     *
     * @param string $path
     * @param string $alias document alias
     * @return string
     */
    public function frontendDocumentTask ($task, $path, $alias, $item = JOOMDOC_DOCUMENT) {
        $itemID = null;
        JoomDOCRoute::frontend($path, $alias, $itemID);
        return 'index.php?option=' . JOOMDOC_OPTION . '&task=' . JoomDOCHelper::getTask($item, $task) . '&path=' . JoomDOCString::urlencode($path) . ($itemID ? '&Itemid=' . $itemID : '');
    }

    /**
     * Get URL to view upgrade page.
     *
     * @return string
     */
    public function viewUpgrade () {
        return 'index.php?option=' . JOOMDOC_OPTION . '&view=' . JOOMDOC_UPGRADE;
    }

    /**
     * Get URL to view file detail.
     *
     * @param string $path
     * @return string
     */
    public function viewFileInfo ($path) {
        return 'index.php?option=' . JOOMDOC_OPTION . '&view=' . JOOMDOC_FILE . '&path=' . JoomDOCString::urlencode($path);
    }

    public function frontend (&$path, $alias, &$itemID) {
        static $isSite;
        if (is_null($isSite))
            $isSite = JFactory::getApplication()->isSite();
        if (!$isSite)
            return $path;
        // if alias is false on site search for document alias by path
        if ($alias === false) {
            static $model;
            /* @var $model JoomDOCModelDocument */
            if (is_null($model))
                $model =& JModel::getInstance(JOOMDOC_DOCUMENT, JOOMDOC_MODEL_PREFIX);
            $alias = $model->searchFullAliasByPath($path);
        }
        $itemID = JoomDOCMenu::getMenuItemID($path);
        $path = $alias ? $alias : $path;
        $path = JoomDOCFileSystem::getVirtualPath($path);
    }
}
?>