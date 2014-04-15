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

/* @var $this JoomDOCViewJoomDOC */

$data = array_change_key_case(JApplicationHelper::parseXMLInstallFile(JOOMDOC_MANIFEST), CASE_LOWER);
$xml = J16 ? JFactory::getXML(JOOMDOC_MANIFEST) : J16Factory::getXML(JOOMDOC_MANIFEST);

echo '<div id="cpanel">';
echo '<div class="icon-wrapper">';
echo '<div class="hasTip icon" title="' . $this->getTooltip('JOOMDOC_DOCUMENTS') . '">';
echo '<a href="' . JRoute::_(JoomDOCRoute::viewDocuments()) . '" title="" >';
echo '<img src="' . JOOMDOC_IMAGES . 'icon-48-documents.png" alt="" />';
echo '<span>' . JText::_('JOOMDOC_DOCUMENTS') . '</span>';
echo '</a>';
echo '</div>';
echo '<div class="hasTip icon" title="' . $this->getTooltip('JOOMDOC_UPGRADE') . '">';
echo '<a href="' . JRoute::_(JoomDOCRoute::viewUpgrade()) . '" title="" >';
echo '<img src="' . JOOMDOC_IMAGES . 'icon-48-upgrade.png" alt="" />';
echo '<span>' . JText::_('JOOMDOC_UPGRADE') . '</span>';
echo '</a>';
echo '</div>';
echo '</div>';
echo '<div class="col width-35" style="width: 35%;float: right;">';
echo '<fieldset class="adminform">';
echo '<legend>' . JText::_('JOOMDOC') . '</legend>';
echo '<table class="admintable">';
echo '<tr>';
echo '<td class="key"></td>';
echo '<td>';
echo '<a href="' . JRoute::_($data['authorurl']) . '" target="_blank" title="">';
echo '<img src="' . JOOMDOC_IMAGES . 'icon-120-joomdoc.png" alt="" />';
echo '</a>';
echo '</td>';
echo '</tr>';
echo '<tr>';
echo '<td class="key" width="120"></td>';
echo '<td><a href="' . JRoute::_($data['authorurl']) . '" target="_blank">' . JText::_('JOOMDOC') . '</a></td>';
echo '</tr>';
echo '<tr>';
echo '<td class="key">' . JText::_('JOOMDOC_VERSION') . '</td>';
echo '<td>';
echo '<strong>' . $data['version'] . '</strong> ';

 echo JText::sprintf('JOOMDOC_EXTEND', JOOMDOC_URL_FEATURES, JOOMDOC_URL_ESHOP);
 
echo '</td>';
echo '</tr>';
echo '<tr>';
echo '<td class="key">' . JText::_('JOOMDOC_DATE') . '</td>';
echo '<td>' . JHTML::date($data['creationdate'], JText::_('DATE_FORMAT_LC4')) . '</td>';
echo '</tr>';
echo '<tr>';
echo '<td class="key" valign="top">' . JText::_('JOOMDOC_COPYRIGHT') . '</td>';
echo '<td>&copy; 2006 - ' . date('Y') . ', ' . $data['author'] . '</td>';
echo '</tr>';
echo '<tr>';
echo '<td class="key">' . JText::_('JOOMDOC_AUTHOR') . '</td>';
echo '<td><a href="' . $data['authorurl'] . '" target="_blank">' . $data['author'] . '</a>,';
echo '<a href="mailto:' . $data['authoremail'] . '">' . $data['authoremail'] . '</a></td>';
echo '</tr>';
echo '<tr>';
echo '<td class="key" valign="top">' . JText::_('JOOMDOC_DESCRIPTION') . '</td>';
echo '<td>' . JText::_('JOOMDOC_DESC') . '</td>';
echo '</tr>';
echo '<tr>';
echo '<td class="key">' . JText::_('JOOMDOC_LICENSE') . '</td>';
echo '<td><a href="' . $xml->license . '" target="_blank" class="hasTip" title="' . $this->getTooltip('JOOMDOC_LICENSER') . '">' . $xml->licenser . '</a></td>';
echo '</tr>';
echo '</table>';
echo '</fieldset>';
echo '</div>';
echo '<div class="clr"></div>';
echo '</div>';
?>