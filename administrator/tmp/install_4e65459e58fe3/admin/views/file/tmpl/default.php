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

/* @var $this JoomDOCViewFile */

jimport('joomla.html.pagination');

$config =& JoomDOCConfig::getInstance();

$showDownload = JoomDOCAccessFileSystem::download($this->document ? $this->document->id : null, $this->filter->path);

// texts translations
$download = JText::_('JOOMDOC_DOWNLOAD_FILE');
echo '<form name="adminForm" id="adminForm" action="' . JURI::getInstance()->toString() . '" method="post">';
echo '<fieldset id="filter-bar" class="autoHeight">';
echo '<table class="fullWidth">';
echo '<tr>';
echo '<td width="1%" nowrap="nowrap">';
echo '<label class="filter-search-lbl edit" for="uploader">' . JText::_('JOOMDOC_UPLOADER') . '</label>';
echo '</td>';
echo '<td width="1%" nowrap="nowrap">';
echo '<input type="text" name="uploader" id="uploader" value="' . $this->escape($this->filter->uploader) . '" onchange="this.form.submit()" />';
echo '</td>';
echo '<td>';
echo '<button type="submit" class="btn">' . JText::_('JSEARCH_FILTER_SUBMIT') . '</button>';
echo '<button type="button" onclick="this.form.uploader.value=\'\';this.form.submit();">' . JText::_('JSEARCH_FILTER_CLEAR') . '</button>';
echo '</td>';
echo '</tr>';
echo '</table>';
echo '</fieldset>';
echo '<table class="adminlist">';
echo '<thead>';
echo '<tr>';
echo '<th>' . JHtml::_('grid.sort', 'JOOMDOC_VERSION', 'version', $this->filter->listDirn, $this->filter->listOrder) . '</th>';
if ($showDownload) {
    echo '<th>' . JText::_('JOOMDOC_FILE') . '</th>';
}
echo '<th>' . JText::_('JOOMDOC_SIZE') . '</th>';
echo '<th>' . JHtml::_('grid.sort', 'JOOMDOC_UPLOADED', 'upload', $this->filter->listDirn, $this->filter->listOrder) . '</th>';
echo '<th>' . JHtml::_('grid.sort', 'JOOMDOC_UPLOADER', 'name', $this->filter->listDirn, $this->filter->listOrder) . '</th>';
echo '<th>' . JHtml::_('grid.sort', 'JOOMDOC_HITS', 'hits', $this->filter->listDirn, $this->filter->listOrder) . '</th>';
echo '</tr>';
echo '</thead>';
echo '<tbody>';
foreach ($this->data as $item) {
    echo '<tr>';
    echo '<td>' . $item->version . '</td>';
    if ($showDownload) {
        echo '<td>';
        echo '<a href="' . JRoute::_(JoomDOCRoute::download($item->path, null, $item->version)) . '" title="" target="_blank">';
        echo $download;
        echo '</a>';
        echo '</td>';
    }
    echo '<td nowrap="nowrap">' . JoomDOCFileSystem::getFileSize(JoomDOCFileSystem::getFullPath($item->path)) . '</td>';
    echo '<td nowrap="nowrap">' . JoomDOCHelper::uploaded($item->upload, false) . '</td>';
    echo '<td nowrap="nowrap">' . $item->name . '</td>';
    echo '<td class="center" nowrap="nowrap">' . JoomDOCHelper::number($item->hits) . '</td>';
    echo '</tr>';
}
echo '</tbody>';
echo '<tfoot>';
echo '<tr>';
$pagination = new JPagination($this->filter->total, $this->filter->offset, $this->filter->limit);
echo '<td colspan="6">' . $pagination->getListFooter() . '</td>';
echo '</tr>';
echo '</tfoot>';
echo '</table>';
echo '<input type="hidden" name="filter_order" value="' . $this->filter->listOrder . '" />';
echo '<input type="hidden" name="filter_order_Dir" value="' . $this->filter->listDirn . '" />';
echo '</form>';
?>