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

/* @var $this JoomDOCViewDocuments */


$config =& JoomDOCConfig::getInstance();

echo '<form action="' . JRoute::_(JoomDOCRoute::viewDocuments($this->access->relativePath, $this->access->alias)) . '" method="post" name="adminForm" id="adminForm">';
echo '<div id="document"' . ($this->access->isFavorite ? 'class="favorite"' : '') . '>';
if (!$this->access->inRoot && $this->access->relativePath) {
    echo '<a class="back" href="' . JRoute::_(JoomDOCRoute::viewDocuments(JoomDOCFileSystem::getParentPath($this->access->relativePath), $this->root->parent ? $this->root->parent->full_alias : null)) . '" title="">' . JText::_('JOOMDOC_BACK') . '</a>';
}
echo '<h1>' . ($this->access->docid ? $this->root->document->title : $this->root->getFileName()) . '</h1>';
if ($this->access->canViewFileInfo && (($this->access->docid && $this->access->canShowFileDates) || (!$this->access->isFolder && $this->access->canShowFileInfo) || $this->access->isFavorite)) {
    echo '<div class="info">';
    if ($this->access->canDisplayFavorite) {
        echo '<span class="favorite">' . JText::_('JOOMDOC_FAVORITE') . '</span>';
    }
    if ($config->showFilesize && !$this->access->isFolder) {
        echo '<span class="filesize">' . JText::sprintf('JOOMDOC_FILESIZE', JoomDOCFileSystem::getFileSize($this->root->getAbsolutePath())) . '</span>';
    }
    if ($this->access->docid) {
        if ($config->showCreated && !is_null($this->root->document->created)) {
            echo '<span class="created">' . JText::sprintf('JOOMDOC_CREATED', JHtml::date($this->root->document->created, JText::_('JOOMDOC_UPLOADED_DATE_' . JTAG))) . '</span>';
        }
        if ($config->showModified && JoomDOCHelper::canViewModified($this->root->document->created, $this->root->document->modified)) {
            echo '<span class="modified">' . JText::sprintf('JOOMDOC_MODIFIED', JHtml::date($this->root->document->modified, JText::_('JOOMDOC_UPLOADED_DATE_' . JTAG))) . '</span>';
        }
    }
    if ($config->showHits && !$this->access->isFolder) {
        echo '<span class="hits">' . JText::sprintf('JOOMDOC_HITS_INFO', JoomDOCHelper::number($this->root->document->hits)) . '</span>';
    }
    echo '<div class="clr"></div>';
    echo '</div>';
}
if ($this->access->docid && ($description = JString::trim($this->root->document->description))) {
    echo '<p>' . $description . '</p>';
}
if (!$this->access->isFolder) {
    if ($this->access->canDownload || $this->access->canWebDav || $this->access->canEdit || $this->access->canDeleteDoc || $this->access->canCreate || $this->access->canDeleteFile || $this->access->canEditState) {
        echo '<div class="toolbar">';
        if ($this->access->canDownload) {
            echo '<a class="download" href="' . JRoute::_(JoomDOCRoute::download($this->access->relativePath, $this->access->alias)) . '" title="">' . JText::_('JOOMDOC_DOWNLOAD_FILE') . '</a>';
        }
        if ($this->access->canCreate) {
            echo '<a class="add" href="' . JRoute::_(JoomDOCRoute::add($this->access->relativePath, $this->access->alias)) . '" title="">' . JText::_('JOOMDOC_ADD_DOCUMENT') . '</a>';
        }
        if ($this->access->canEdit) {
            echo '<a class="edit" href="' . JRoute::_(JoomDOCRoute::edit($this->access->relativePath, $this->access->alias)) . '" title="">' . JText::_('JOOMDOC_EDIT_DOC') . '</a>';
        }
        if ($this->access->canEditState) {
            if ($this->root->document->state == JOOMDOC_STATE_UNPUBLISHED) {
                echo '<a class="publish" href="' . JRoute::_(JoomDOCRoute::publish($this->access->relativePath, $this->access->alias)) . '" title="">' . JText::_('JOOMDOC_PUBLISH') . '</a>';
            } elseif ($this->root->document->state == JOOMDOC_STATE_PUBLISHED) {
                echo '<a class="unpublish" href="' . JRoute::_(JoomDOCRoute::unpublish($this->access->relativePath, $this->access->alias)) . '" title="">' . JText::_('JOOMDOC_UNPUBLISH') . '</a>';
            }
        }
        if ($this->access->canDeleteFile) {
            echo '<a class="delete" href="javascript:void(0)" onclick="JoomDOC.confirm(\'' . addslashes(JRoute::_(JoomDOCRoute::deletefile($this->access->relativePath, $this->access->alias))) . '\')" title="">' . JText::_('JOOMDOC_DELETE_ITEM') . '</a>';
        }
        if ($this->access->canDeleteDoc) {
            echo '<a class="deleteDocument" href="javascript:void(0)" onclick="JoomDOC.confirm(\'' . addslashes(JRoute::_(JoomDOCRoute::delete($this->access->relativePath, $this->access->alias))) . '\')" title="">' . JText::_('JOOMDOC_DELETE_DOCUMENT') . '</a>';
        }
                echo '<div class="clr"></div>';
        echo '</div>';
    }
}
echo '<input type="hidden" id="joomdocToken" name="' . JUtility::getToken() . '" value="1" />';
echo '</div>';
echo '</form>';
?>