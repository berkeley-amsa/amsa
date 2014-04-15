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

$listOrder = $this->escape($this->state->get(JOOMDOC_FILTER_ORDERING));
$listDirn = $this->escape($this->state->get(JOOMDOC_FILTER_DIRECTION));

$separator = JText::_('JOOMDOC_PATHWAY_SEPARATOR');
foreach (JoomDOCFileSystem::getPathBreadCrumbs($this->access->relativePath) as $i => $breadCrumb) {
    echo '<span>' . $separator . '<a href="' . JRoute::_(JoomDOCRoute::modalDocuments($breadCrumb->path)) . '" class="hasTip" title="' . $this->getTooltip($breadCrumb->path, 'JOOMDOC_DOCUMENTS_OPEN_FOLDER') . '">' . $breadCrumb->name . '</a></span>';
}

echo '<form action="' . JRoute::_(JoomDOCRoute::modalDocuments($this->access->relativePath)) . '" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">';
echo '<fieldset id="filter-bar">';
echo '<div>';
echo '<label for="filter" class="hasTip filter-search-lbl" title="' . $this->getTooltip('JOOMDOC_DOCUMENTS_FILTER') . '">' . JText::_('JOOMDOC_DOCUMENTS_FILTER') . ':</label>';
echo '<input type="text" name="filter" id="filter" value="' . $this->escape($this->filter) . '" />';
echo '<button type="submit" class="btn">' . JText::_('JSEARCH_FILTER_SUBMIT') . '</button>';
echo '<button type="button" onclick="var f=this.form;f.filter.value=\'\';f.submit();">' . JText::_('JSEARCH_FILTER_CLEAR') . '</button>';
echo '</div>';
echo '</fieldset>';
echo '<table class="adminlist">';
echo '<thead>';
echo '<tr>';
echo '<th>' . JHtml::_('grid.sort', 'JOOMDOC_DOCUMENTS_ITEM', JOOMDOC_ORDER_PATH, $listDirn, $listOrder) . '</th>';
echo '<th>' . JText::_('JOOMDOC_DOCUMENT') . '</th>';
echo '</tr>';
echo '</thead>';
echo '<tbody>';

$prefix = '/administrator/';
$prefixLength = JString::strlen($prefix);

$this->root->initIteration();
$i = 0;
while ($this->root->hasNext()) {
    $item = $this->root->getNext();
    $access = new JoomDOCAccessHelper($item);
    echo '<tr class="row' . ($i % 2) . '">';
    echo '<td>';

    $id = $this->escape($access->relativePath);
    $title = $this->escape($access->docid ? $item->document->title : $access->relativePath);
    $url = JRoute::_(JoomDOCRoute::viewDocuments($access->relativePath, $access->docid ? $item->document->full_alias : ''));
    
    if (JString::strpos($url, $prefix) === 0) {
        $url = JString::substr($url, $prefixLength);
    }

    echo '<a href="javascript:window.parent.jSelectJoomdocDocument(\'' . $id . '\', \'' . $title . '\', \'' . $url . '\')" class="hasTip addDocument" title="' . $this->getTooltip('JOOMDOC_SET_DOCUMENT') . '"></a>';

    if ($access->isFolder) {
        echo '<a class="hasTip folder" href="' . JRoute::_(JoomDOCRoute::modalDocuments($access->relativePath)) . '" title="' . $this->getTooltip($access->relativePath, 'JOOMDOC_DOCUMENTS_OPEN_FOLDER') . '">' . $this->escape($access->name) . '</a>';
    } else {
        echo '<span class="file">' . $this->escape($access->name) . '</span>';
    }
    echo '</td>';
    echo '<td>' . ($access->docid ? $this->escape($item->document->title) : '-') . '</td>';
    echo '</tr>';
    $i++;
}
echo '</tbody>';
echo '</table>';
echo '<input type="hidden" name="task" value="" />';
echo '<input type="hidden" name="boxchecked" value="" />';
echo '<input type="hidden" name="filter_order" value="' . $listOrder . '" />';
echo '<input type="hidden" name="filter_order_Dir" value="' . $listDirn . '" />';
echo JHtml::_('form.token');
echo '</form>';
?>