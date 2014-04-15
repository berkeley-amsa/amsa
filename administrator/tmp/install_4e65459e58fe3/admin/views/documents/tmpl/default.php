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

// component configuration
$config =& JoomDOCConfig::getInstance();


// list order criteria from user state
$listOrder = $this->escape($this->state->get(JOOMDOC_FILTER_ORDERING));
$listDirn = $this->escape($this->state->get(JOOMDOC_FILTER_DIRECTION));

// browse list allow set items ordering
$ordering = $listOrder == JOOMDOC_ORDER_ORDERING;

$taskOrderUp = JoomDOCHelper::getTask(JOOMDOC_DOCUMENTS, JOOMDOC_TASK_ORDERUP);
$taskOrderDown = JoomDOCHelper::getTask(JOOMDOC_DOCUMENTS, JOOMDOC_TASK_ORDERDOWN);

$user =& JFactory::getUser();
/* @var $user JUser logged user */

// favorite states
$states[0]['task'] = 'favorite';
$states[1]['task'] = 'unfavorite';

$states[0]['text'] = 'JOOMDOC_SET_FAVORITE';
$states[1]['text'] = 'JOOMDOC_SET_UNFAVORITE';

$states[0]['icon'] = JURI::base(true) . '/images/publish_x.png';
$states[1]['icon'] = JURI::base(true) . '/images/tick.png';

$states[0]['active_title'] = $states[1]['inactive_title'] = 'JOOMDOC_STANDARD_TTL';
$states[1]['active_title'] = $states[0]['inactive_title'] = 'JOOMDOC_FAVORITE_TTL';

$states[0]['active_class'] = $states[0]['inactive_class'] = 'notdefault';
$states[1]['active_class'] = $states[1]['inactive_class'] = 'default';

$states[0]['tip'] = $states[1]['tip'] = true;

$files = $folders = array();

echo '<div id="joomdoc">';

echo '<div id="pathway">';

$separator = '';
$separatorValue = JText::_('JOOMDOC_PATHWAY_SEPARATOR');
$breadCrumbs = JoomDOCFileSystem::getPathBreadCrumbs($this->access->relativePath);
foreach ($breadCrumbs as $breadCrumb) {
    echo '<span class="item">';
    echo $separator;
    echo '<a href="' . JRoute::_(JoomDOCRoute::viewDocuments($breadCrumb->path)) . '" class="hasTip" title="' . $this->getTooltip($breadCrumb->path, 'JOOMDOC_DOCUMENTS_OPEN_FOLDER') . '">' . $breadCrumb->name . '</a>';
    echo '</span>';
    $separator = $separatorValue;
}
$count = count($breadCrumbs);
if (!$this->access->inRoot && $count > 1) {
    $breadCrumb = $breadCrumbs[$count - 2];
    echo '<a class="back" href="' . JRoute::_(JoomDOCRoute::viewDocuments($breadCrumb->path)) . '" title="">' . JText::_('JOOMDOC_BACK') . '</a>';
}
echo '</div>';

echo '<form action="' . JRoute::_(JoomDOCRoute::viewDocuments($this->access->relativePath)) . '" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">';
echo '<fieldset id="filter-bar">';
echo '<div>';
echo '<label style="padding: 0px 4px 0px 10px;" for="filter" class="hasTip filter-search-lbl" title="' . $this->getTooltip('JOOMDOC_DOCUMENTS_FILTER') . '">' . JText::_('JOOMDOC_DOCUMENTS_FILTER') . ':</label>';
echo '<input type="text" name="filter" id="filter" value="' . $this->escape($this->filter) . '" />';
echo '<button type="submit" class="btn">' . JText::_('JSEARCH_FILTER_SUBMIT') . '</button>';
echo '<button type="button" onclick="var f=this.form;f.filter.value=\'\';f.submit();">' . JText::_('JSEARCH_FILTER_CLEAR') . '</button>';

if ($this->access->canUpload) {
    echo '<label style="padding: 0px 4px 0px 10px;" for="upload" class="hasTip filter-search-lbl" title="' . $this->getTooltip('JOOMDOC_DOCUMENTS_UPLOADFILE') . '">' . JText::_('JOOMDOC_DOCUMENTS_UPLOADFILE') . ':</label>';
    echo '<input type="file" name="upload" id="upload" />';
    echo '<input type="checkbox" name="iszip" id="iszip" value="1" style="position: relative; top: 3px;border: none;" />';
    echo '<label style="padding: 0px 10px 0px 4px;" class="hasTip filter-search-lbl" for="iszip" title="' . $this->getTooltip('JOOMDOC_DOCUMENTS_UNPACK_ZIP') . '">' . JText::_('JOOMDOC_DOCUMENTS_UNPACK_ZIP') . '</label>';
    echo '<button type="submit" class="btn" onclick="return JoomDOC.upload(this)">' . JText::_('JOOMDOC_UPLOAD') . '</button>';
}
if ($this->access->canCreateFolder) {

    $method = 'return JoomDOC.mkdir(this, \'' . addslashes(JoomDOCHelper::getTask(JOOMDOC_DOCUMENTS, JOOMDOC_TASK_NEWFOLDER)) . '\', \'' . JText::_('JOOMDOC_MKDIR_EMPTY', true) . '\',\'' . JText::_('JOOMDOC_MKDIR_FILE_EXISTS', true) . '\',\'' . JText::_('JOOMDOC_MKDIR_DIR_EXISTS', true) . '\')';

    echo '<label style="padding: 0px 4px 0px 10px;" class="hasTip filter-search-lbl" for="newfolder" title="' . $this->getTooltip('JOOMDOC_DOCUMENTS_NEW_FOLDER') . '">' . JText::_('JOOMDOC_DOCUMENTS_NEW_FOLDER') . ':</label>';
    echo '<input type="text" name="newfolder" id="newfolder" value="' . $this->escape(JRequest::getString('newfolder')) . '" onchange="' . $method . '" />';
    echo '<button type="submit" class="btn" onclick="' . $method . '">' . JText::_('JOOMDOC_CREATE') . '</button>';
}
echo '</div>';
echo '</fieldset>';
echo '<table class="adminlist" cellspacing="1">';
echo '<thead>';
echo '<tr>';
echo '<th width="1%"><input type="checkbox" name="checkall-toggle" value="" onclick="checkAll(this)" /></th>';
echo '<th>' . JHtml::_('grid.sort', 'JOOMDOC_DOCUMENTS_ITEM', JOOMDOC_ORDER_PATH, $listDirn, $listOrder) . '</th>';
echo '<th width="1%">&nbsp;</th>';
echo '<th width="1%">' . JHtml::_('grid.sort', 'JOOMDOC_UPLOADED', JOOMDOC_ORDER_UPLOAD, $listDirn, $listOrder) . '</th>';
echo '<th width="1%">' . JText::_('JOOMDOC_SIZE') . '</th>';
echo '<th width="20%">' . JHtml::_('grid.sort', 'JOOMDOC_DOCUMENT', JOOMDOC_ORDER_TITLE, $listDirn, $listOrder) . '</th>';
echo '<th width="1%">' . JText::_('JOOMDOC_PUBLISHED') . '</th>';
echo '<th width="1%">' . JText::_('JOOMDOC_FAVORITE') . '</th>';
echo '<th width="10%">';
echo JHtml::_('grid.sort', 'JGRID_HEADING_ORDERING', JOOMDOC_ORDER_ORDERING, $listDirn, $listOrder);
$this->pagination->total = $this->root->getItemsCount();
if ($this->access->canEditState && $ordering) {
    // create a fake array of browse list items
    for ($i = 0; $i < $this->pagination->total; $i++)
        $fake[] = $i;
    echo JHtml::_('grid.order', isset($fake) ? $fake : array(), 'filesave.png', JoomDOCHelper::getTask(JOOMDOC_DOCUMENTS, JOOMDOC_TASK_SAVEORDER));
}
echo '</th>';
echo '<th width="1%">' . JText::_('JOOMDOC_ACCESS') . '</th>';
echo '<th width="1%">' . JText::_('JOOMDOC_ID') . '</th>';
echo '<th width="1%">' . JHtml::_('grid.sort', 'JOOMDOC_HITS', JOOMDOC_ORDER_HITS, $listDirn, $listOrder) . '</th>';
echo '</tr>';
echo '</thead>';
echo '<tbody id="tdoc">';
$this->root->initIteration();
$i = 0;
while ($this->root->hasNext()) {
    // previous item
    $prevItemDocid = JoomDOCHelper::getDocumentID($this->root->getNext(JOOMDOC_ORDER_PREV));
    // next item
    $nextItemDocid = JoomDOCHelper::getDocumentID($this->root->getNext(JOOMDOC_ORDER_NEXT));
    // current item
    $item = $this->root->getNext();
    // access rules
    $access = new JoomDOCAccessHelper($item);
    // save files/folders names for next using
    $access->isFile ? $files[] = $access->name : $folders[] = $access->name;

    echo '<tr class="row' . ($i % 2) . '">';
    echo '<td class="center">';

    if ($access->docid && $access->isChecked) {
        echo JHtml::_('jgrid.checkedout', $i, $item->document->editor, $item->document->checked_out_time, 'documents.', (JoomDOCAccessDocument::manage($item->document->checked_out) && JoomDOCAccess::manage()));
    }

    echo '<input type="checkbox" name="paths[]" id="cbb' . $i . '" value="' . $this->escape($access->relativePath) . '" class="blind" />';
    if (!$access->isChecked && !$access->isLocked) {
        echo '<input type="checkbox" name="cid[]" id="cb' . $i . '" value="' . $access->docid . '" onclick="isChecked(this.checked);JoomDOC.check(this,' . $i . ')" />';
    }
    echo '</td>';
    echo '<td class="filepath">';
    if ($access->isFolder) {
        if ($access->canEnterFolder) {
            echo '<a class="hasTip folder" href="' . JRoute::_(JoomDOCRoute::viewDocuments($access->relativePath)) . '" title="' . $this->getTooltip($access->relativePath, 'JOOMDOC_DOCUMENTS_OPEN_FOLDER') . '">' . $access->name . '</a>';
        } else {
            echo '<a class="folder noLink" href="javascript:void(0)" title="">' . $access->name . '</a>';
        }
    } else {
        if ($access->canDownload) {

            echo '<a href="' . JRoute::_(JoomDOCRoute::download($access->relativePath)) . '" class="hasTip file" title="' . $this->getTooltip($access->relativePath, 'JOOMDOC_DOWNLOAD_FILE') . '">' . $access->name . '</a>';
        } else {
            echo '<a href="javascript:void(0)" class="file noLink" title="">' . $access->name . '</a>';
        }
    }
    if ($access->canRename) {

        echo '<div class="rename blind">';
        echo '<input type="text" name="rename" value="' . $this->escape($access->name) . '" />';
        echo '<button onclick="return JoomDOC.rename(this, \'' . addslashes(JoomDOCHelper::getTask(JOOMDOC_DOCUMENT, JOOMDOC_TASK_RENAME)) . '\', \'' . addslashes($access->name) . '\', \'' . addslashes($access->relativePath) . '\',\'' . JText::_('JOOMDOC_RENAME_SAME_NAME', true) . '\',\'' . JText::_('JOOMDOC_RENAME_EMPTY_NAME', true) . '\', \'' . JText::_('JOOMDOC_RENAME_FILE_EXISTS', true) . '\', \'' . JText::_('JOOMDOC_RENAME_DIR_EXISTS', true) . '\')">' . JText::_('JOOMDOC_RENAME_SAVE') . '</button>';
        echo '<button onclick="return JoomDOC.closeRename(this, \'' . addslashes($access->name) . '\')">' . JText::_('JOOMDOC_RENAME_CLOSE') . '</button>';
        echo '</div>';
    }
    echo '</td>';

    echo '<td class="rename">';
    if ($access->canRename) {
        echo '<a href="javascript:void(0)" class="rename" id="openRename' . $i . '" onclick="JoomDOC.openRename(' . $i . ')" title="' . JText::_('JOOMDOC_RENAME', true) . '"></a>';
    }
    echo '</td>';

    
    
    echo '<td>';
    if ($access->canViewFileInfo) {
        echo isset($item->upload) ? JoomDOCHelper::uploaded($item->upload, false) : '';
    }
    echo '</td>';
    echo '<td>';
    if ($access->canViewFileInfo) {
        echo $access->isFolder ? '-' : $item->getFileSize();
    }
    echo '</td>';

    if ($access->docid) {

        echo '<td>';

        if ($access->canEdit) {
            echo '<a href="' . JRoute::_(JoomDOCRoute::editDocument($access->docid)) . '" title="' . $this->getTooltip($item->document->title, 'JOOMDOC_EDIT_DOCUMENT') . '" class="hasTip">' . $this->escape($item->document->title) . '</a>';
        } else {
            echo $this->escape($item->document->title);
        }

        echo '</td>';

        echo '<td class="center" align="center">';
        if (!J16 && $item->document->state == JOOMDOC_STATE_TRASHED) {
            echo '<span title="' . $this->escape(JText::_('JTRASHED')) . '">' . JHtmlImage::administrator('menu/icon-16-trash.png') . '</span>';
        } else {
            echo JHtml::_('jgrid.published', $item->document->state, $i, 'documents.', $access->canEditState, 'cb', $item->document->publish_up, $item->document->publish_down);
        }
        echo '</td>';

        echo '<td class="center" align="center">';
        echo JHtml::_('jgrid.state', $states, $item->document->favorite, $i, 'documents.', $access->canEditState, true);
        echo '</td>';

        echo '<td class="order">';
        if ($access->canEditState) {
            if ($ordering) {
                if ($listDirn == 'asc') {
                    echo '<span>' . $this->pagination->orderUpIcon($i, $prevItemDocid, $taskOrderUp, 'JLIB_HTML_MOVE_UP', $ordering) . '</span>';
                    echo '<span>' . $this->pagination->orderDownIcon($i, $this->pagination->total, $nextItemDocid, $taskOrderDown, 'JLIB_HTML_MOVE_DOWN', $ordering) . '</span>';
                } elseif ($listDirn == 'desc') {
                    echo '<span>' . $this->pagination->orderUpIcon($i, $prevItemDocid, $taskOrderDown, 'JLIB_HTML_MOVE_UP', $ordering) . '</span>';
                    echo '<span>' . $this->pagination->orderDownIcon($i, $this->pagination->total, $nextItemDocid, $taskOrderUp, 'JLIB_HTML_MOVE_DOWN', $ordering) . '</span>';
                }
            }
            echo '<input type="text" name="order[' . $access->docid . ']" size="5" value="' . $item->document->ordering . '" if (!$ordering) {disabled="disabled"} class="text-area-order" />';
        } else {
            echo $item->document->ordering;
        }
        echo '</td>';

        echo '<td class="center">' . $this->escape($item->document->access_title) . '</td>';
        echo '<td class="center">' . JoomDOCHelper::number($item->document->id) . '</td>';

    } else {
        echo '<td colspan="6">';
        if ($access->canCreate) {
            echo '<a href="' . JRoute::_(JoomDOCRoute::addDocument($access->relativePath)) . '" class="hasTip addDocument" title="' . $this->getTooltip('JOOMDOC_ADD_DOCUMENT') . '"></a>';
        }
        echo '</td>';
    }
    echo '<td class="center">' . JoomDOCHelper::number($item->hits) . '</td>';
    echo '</tr>';
    $i++;
}
if (empty($files) && empty($folders)) {
    echo '<tr><td colspan="20">' . JText::_('JOOMDOC_EMPTY_FOLDER') . '</td></tr>';
}
echo '</tbody>';
echo '</table>';
echo '<input type="hidden" name="task" value="" />';
echo '<input type="hidden" name="boxchecked" value="" />';
echo '<input type="hidden" name="filter_order" value="' . $listOrder . '" />';
echo '<input type="hidden" name="filter_order_Dir" value="' . $listDirn . '" />';
echo '<input type="hidden" name="renamePath" value="" />';
echo '<input type="hidden" name="newName" value="" />';
echo JHtml::_('form.token');
echo '</form>';
echo '</div>';
JoomDOCHelper::jsArray('joomDOCFiles', $files);
JoomDOCHelper::jsArray('joomDOCFolders', $folders);
