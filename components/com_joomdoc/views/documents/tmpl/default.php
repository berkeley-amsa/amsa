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

$config = JoomDOCConfig::getInstance();

echo '<form action="' . JRoute::_(JoomDOCRoute::viewDocuments($this->access->relativePath, $this->access->alias)) . '" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">';
echo '<div id="documents">';

// if path is not root display back link
if (!$this->access->inRoot && $this->access->relativePath) {
    $parentAlias = $this->root->parent ? $this->root->parent->full_alias : null;
    echo '<a class="back" href="' . JRoute::_(JoomDOCRoute::viewDocuments(JoomDOCFileSystem::getParentPath($this->access->relativePath), $parentAlias)) . '" title="">' . JText::_('JOOMDOC_BACK') . '</a>';
}

if ($this->access->inRoot && !$this->access->docid && $config->defaultTitle) {
    // in root display default title if root hasn't document
    echo '<h1>' . $config->defaultTitle . '</h1>';
} else {
    // otherwise display document title or path as title
    echo '<h1>' . ($this->access->docid ? $this->root->document->title : $this->access->relativePath) . '</h1>';
}

if ($this->access->inRoot && !$this->access->docid && $config->defaultDescription) {
    // in root display default description if root hasn't document
    echo '<p>' . $config->defaultDescription . '</p>';
} elseif ($this->access->docid && ($description = JString::trim($this->root->document->description))) {
    // otherwise display document description if available
    echo '<p>' . $description . '</p>';
}

if (JoomDOCAccessFileSystem::uploadFile($this->access->docid, $this->access->relativePath)) {
    echo '<div class="upload">';
    echo '<label for="upload" title="' . $this->getTooltip('JOOMDOC_DOCUMENTS_UPLOADFILE') . '">' . JText::_('JOOMDOC_DOCUMENTS_UPLOADFILE') . '</label>';
    echo '<input type="file" name="upload" id="upload" />';
    echo '<input type="checkbox" name="iszip" id="iszip" value="1" />';
    echo '<label for="iszip" title="">' . JText::_('JOOMDOC_DOCUMENTS_UNPACK_ZIP') . '</label>';
    echo '<button type="submit" onclick="return JoomDOC.upload(this)">' . JText::_('JOOMDOC_UPLOAD') . '</button>';
    echo '</div>';
}

$this->root->initIteration();

// check if document allow display files without documents
$filesWithouDoc = true;
if ($this->access->docid) {
    $params = new JRegistry($this->root->document->params);
    $filesWithouDoc = (int) $params->getValue('files_without_doc', 1);
}

$class = null;

if (!$this->root->hasNext()) {
    // folder is empty
    echo '<p class="empty">' . JText::_('JOOMDOC_EMPTY_FOLDER') . '</p>';
}

$files = $folders = array();

while ($this->root->hasNext()) {

    $item = $this->root->getNext();
    $access = new JoomDOCAccessHelper($item);

    // save files/folders names for next using
    $access->isFile ? $files[] = $item->getFileName() : $folders[] = $item->getFileName();

    // no subfolders
    if ($access->isFolder && !$config->showSubfolders)
        continue;

    // no display files without doc	
    if (!$access->docid && (!$filesWithouDoc || !$config->filesWithoutDoc))
        continue;

    // document is unpublish and user cannot edit document or document is trashed
    if ($access->docid && $item->document->published == JOOMDOC_STATE_UNPUBLISHED && !$access->canAnyEditOp)
        continue;

    if ($config->showFileicon)
        $class = $access->isFile ? JoomDOCHelper::getFileIconClass($access->relativePath) : 'folder';

    // url to open item detail
    $viewDocuments = JRoute::_(JoomDOCRoute::viewDocuments($access->relativePath, $access->alias));

    echo '<div class="document' . ($access->isFavorite ? ' favorite' : '') . '">';
    echo '<h2 ' . ($class ? 'class="icon ' . $class . '"' : '') . '>';
    if ($access->canOpenFile || $access->canOpenFolder) {
        // link to open file/subfolder
        echo '<a href="' . $viewDocuments . '" title="">';
    }
    // as item title use document title or file name
    echo $access->docid ? $item->document->title : $item->getFileName();
    if ($access->canOpenFile || $access->canOpenFolder) {
        echo '</a>';
    }
    echo '</h2>';

    if ($access->canViewFileInfo && (($access->docid && $this->access->canShowFileDates) || (!$access->isFolder && $this->access->canShowFileInfo) || $access->isFavorite)) {
        echo '<div class="info">';
        if ($access->isFavorite) {
            echo '<span class="favorite">' . JText::_('JOOMDOC_FAVORITE') . '</span>';
        }
        if ($config->showFilesize && !$access->isFolder) {
            echo '<span class="filesize">' . JText::sprintf('JOOMDOC_FILESIZE', JoomDOCFileSystem::getFileSize($access->absolutePath)) . '</span>';
        }
        if ($access->docid) {
            if ($config->showCreated) {
                echo '<span class="created">' . JText::sprintf('JOOMDOC_CREATED', JHtml::date($item->document->created, JText::_('JOOMDOC_UPLOADED_DATE_' . JTAG))) . '</span>';
            }
            if ($config->showModified && JoomDOCHelper::canViewModified($item->document->created, $item->document->modified)) {
                echo '<span class="modified">' . JText::sprintf('JOOMDOC_MODIFIED', JHtml::date($item->document->modified, JText::_('JOOMDOC_UPLOADED_DATE_' . JTAG))) . '</span>';

            }
        }
        if ($config->showHits && !$access->isFolder) {
            echo '<span class="hits">' . JText::sprintf('JOOMDOC_HITS_INFO', JoomDOCHelper::number($item->hits)) . '</span>';
        }
        echo '<div class="clr"></div>';
        echo '</div>';
    }
    if ($access->docid && ($description = JString::trim($item->document->description)) && ($this->access->canShowAllDesc || ($access->isFolder && $config->showFolderDesc) || ($access->isFile && $config->showFileDesc))) {
        echo '<p>' . JoomDOCString::crop($description, 200) . '</p>';
    }
    if ($access->canOpenFolder || $access->canOpenFile || $access->canDeleteDoc || $access->canEdit || $access->canCreate || $access->canDeleteFile || $access->canDownload) {
        echo '<div class="toolbar">';
        if ($access->canOpenFolder) {
            echo '<a class="open" href="' . $viewDocuments . '" title="">' . JText::_('JOOMDOC_DISPLAY_FOLDER') . '</a>';
        }
        if ($access->canOpenFile) {
            echo '<a class="open" href="' . $viewDocuments . '" title="">' . JText::_('JOOMDOC_DISPLAY_FILE') . '</a>';
        }
        if ($access->canDownload) {
            echo '<a class="download" href="' . JRoute::_(JoomDOCRoute::download($access->relativePath, $access->alias)) . '" title="">' . JText::_('JOOMDOC_DOWNLOAD_FILE') . '</a>';
        }
        if ($access->canCreate) {
            echo '<a class="add" href="' . JRoute::_(JoomDOCRoute::add($access->relativePath, $access->alias)) . '" title="">' . JText::_('JOOMDOC_ADD_DOCUMENT') . '</a>';
        }
        if ($access->canEdit) {
            echo '<a class="edit" href="' . JRoute::_(JoomDOCRoute::edit($access->relativePath, $access->alias)) . '" title="">' . JText::_('JOOMDOC_EDIT_DOC') . '</a>';
        }
        if ($access->canEditState) {
            if ($item->document->state == JOOMDOC_STATE_UNPUBLISHED) {
                echo '<a class="publish" href="' . JRoute::_(JoomDOCRoute::publish($access->relativePath, $access->alias)) . '" title="">' . JText::_('JOOMDOC_PUBLISH') . '</a>';
            } elseif ($item->document->state == JOOMDOC_STATE_PUBLISHED) {
                echo '<a class="unpublish" href="' . JRoute::_(JoomDOCRoute::unpublish($access->relativePath, $access->alias)) . '" title="">' . JText::_('JOOMDOC_UNPUBLISH') . '</a>';
            }
        }
        if ($access->canDeleteFile) {
            echo '<a class="delete" href="javascript:void(0)" onclick="JoomDOC.confirm(\'' . addslashes(JRoute::_(JoomDOCRoute::deletefile($access->relativePath, $access->alias))) . '\')" title="">' . JText::_('JOOMDOC_DELETE_ITEM') . '</a>';
        }
        if ($access->canDeleteDoc) {
            echo '<a class="deleteDocument" href="javascript:void(0)" onclick="JoomDOC.confirm(\'' . addslashes(JRoute::_(JoomDOCRoute::delete($access->relativePath, $access->alias))) . '\')" title="">' . JText::_('JOOMDOC_DELETE_DOCUMENT') . '</a>';
        }
        echo '<div class="clr"></div>';
        echo '</div>';
    }
    echo '</div>';
}
echo '</div>';
echo '<input type="hidden" name="task" value="" />';
echo '<input type="hidden" id="joomdocToken" name="' . JUtility::getToken() . '" value="1" />';
echo '</form>';
JoomDOCHelper::jsArray('joomDOCFiles', $files);
JoomDOCHelper::jsArray('joomDOCFolders', $folders);
?>