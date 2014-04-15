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

/* @var $this JoomDOCViewDocument */

JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');

jimport('joomla.html.pane');
jimport('joomla.html.pagination');

$config = JoomDOCConfig::getInstance();
/* @var $config JoomDOCConfig */

$tabs =& JPane::getInstance('Tabs', array('startOffset' => JRequest::getInt('bookmark')));
/* @var $tabs JPaneTabs */
$tabs->useCookies = true;

echo $tabs->startPane('tabone');
echo $tabs->startPanel(JText::_('JOOMDOC_DOCUMENT_DETAILS'), 'details');

echo '<script type="text/javascript">';
echo '//<![CDATA[';
echo 'Joomla.submitbutton = function (task) {';
echo 'if (task == \'document.cancel\' || document.formvalidator.isValid(document.getElementById(\'item-form\'))) {';
echo 'Joomla.submitform(task, document.getElementById(\'item-form\'));';
echo '} else {';
echo 'alert(\'' . $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')) . '\');';
echo '}';
echo '}';

echo 'function tableOrdering(order, dir, task) {';
echo 'document.versionForm.filter_order.value = order;';
echo 'document.versionForm.filter_order_Dir.value = dir;';
echo 'document.versionForm.submit();';
echo '}';

echo 'function submitform(pressbutton) {';
echo 'if (pressbutton) {';
echo '// toolbar work with document edit form';
echo 'if (! Joomla.submitbutton(pressbutton)) {';
echo 'return false;';
echo '}';
echo '// set task operation into hidden field task (save, apply, cancel etc.)';
echo 'document.adminForm.task.value = pressbutton;';
echo '} else {';
echo '// others task are for version table';
echo 'document.versionForm.submit();';
echo '}';
echo 'if (typeof document.adminForm.onsubmit == "function") {';
echo 'document.adminForm.onsubmit();';
echo '}';
echo 'if (pressbutton) {';
echo '// toolbar work with document edit form';
echo 'document.adminForm.submit();';
echo '}';
echo '}';
echo '//]]>';
echo '</script>';

echo '<form action="' . JRoute::_(JoomDOCRoute::saveDocument($this->document->id)) . '" method="post" name="adminForm" id="item-form" class="form-validate">';
echo '<div class="width-60 fltlft col">';
echo '<fieldset class="adminform">';
echo '<legend>' . JText::_('JOOMDOC_DOCUMENT') . '</legend>';
echo '<table class="admintable">';
echo '<tr><td class="key">' . $this->form->getLabel('title') . '</td>';
echo '<td>' . $this->form->getInput('title') . '</td></tr>';
echo '<tr><td class="key">' . $this->form->getLabel('alias') . '</td>';
echo '<td>' . $this->form->getInput('alias') . '</td></tr>';
echo '<tr><td class="key">' . $this->form->getLabel('state') . '</td>';
echo '<td>' . $this->form->getInput('state') . '</td></tr>';
echo '<tr><td class="key">' . $this->form->getLabel('favorite') . '</td>';
echo '<td>' . $this->form->getInput('favorite') . '</td></tr>';
echo '<tr><td class="key">' . $this->form->getLabel('access') . '</td>';
echo '<td>' . $this->form->getInput('access') . '</td></tr>';

if (JoomDOCAccessDocument::admin()) {
    echo '<!--';
    echo '<tr><td class="key"><span class="faux-label">' . JText::_('JGLOBAL_ACTION_PERMISSIONS_LABEL') . '</span>';
    echo '<div class="button2-left"><div class="blank">';
    echo '<button type="button" onclick="document.location.href=\'#access-rules\';">';
    echo JText::_('JGLOBAL_PERMISSIONS_ANCHOR');
    echo '</button>';
    echo '</div></div>';
    echo '</td></tr>';
    echo '-->';
}

echo '<tr><td class="key">' . $this->form->getLabel('id') . '</td>';
echo '<td>' . $this->form->getInput('id') . '</td></tr>';
echo '<tr><td class="key">' . $this->form->getLabel('path') . '</td>';
echo '<td>';
echo $this->form->getInput('path');
echo '<button onclick="return JoomDOC.copyPath(\'' . $this->escape(JFile::getName($this->form->getValue('path'))) . '\')" title="' . $this->escape(JText::_('JOOMDOC_COPY_DESC')) . '">' . JText::_('JOOMDOC_COPY') . '</button>';
echo '</td></tr>';


echo '</table>';
echo '<div class="clr"></div>';
echo $this->form->getLabel('description');
echo '<div class="clr"></div>';
echo $this->form->getInput('description');
echo '</fieldset>';
echo '<div class="clr"></div>';
echo '</div>';
echo '<div class="width-40 fltrt col">';

if (J16) {
    echo JHtml::_('sliders.start', 'content-sliders-' . $this->document->id, array('useCookie' => 1));
    echo JHtml::_('sliders.panel', JText::_('JOOMDOC_PUBLISHING'), 'publishing-details');
} else {
    $sliders =& JPane::getInstance('sliders', array('allowAllClose' => true, 'useCookie' => 1));
    /* @var $sliders JPaneSliders */
    echo $sliders->startPane('content-sliders-' . $this->document->id);
    echo $sliders->startPanel(JText::_('JOOMDOC_PUBLISHING'), 'publishing-details');
}

echo '<fieldset class="panelform">';
echo '<table class="admintable">';
echo '<tr><td class="key">' . $this->form->getLabel('created_by') . '</td>';
echo '<td>' . $this->form->getInput('created_by') . '</td></tr>';
echo '<tr><td class="key">' . $this->form->getLabel('created') . '</td>';
echo '<td>' . $this->date('created') . '</td></tr>';
echo '<tr><td class="key">' . $this->form->getLabel('publish_up') . '</td>';
echo '<td>' . $this->date('publish_up') . '</td></tr>';
echo '<tr><td class="key">' . $this->form->getLabel('publish_down') . '</td>';
echo '<td>' . $this->date('publish_down') . '</td></tr>';
if ($this->document->modified_by) {
    echo '<tr><td class="key">' . $this->form->getLabel('modified_by') . '</td>';
    echo '<td>' . $this->form->getInput('modified_by') . '</td></tr>';
    echo '<tr><td class="key">' . $this->form->getLabel('modified') . '</td>';
    echo '<td>' . $this->date('modified') . '</td></tr>';
}
echo '</table>';
echo '</fieldset>';

if (!J16) {
    echo $sliders->endPanel();
}

$fieldSets = $this->form->getFieldsets('params');
foreach ($fieldSets as $name => $fieldSet) {
    if (J16) {
        echo JHtml::_('sliders.panel', JText::_($fieldSet->label), $name . '-options');
    } else {
        echo $sliders->startPanel(JText::_($fieldSet->label), $name . '-options');
    }
    if (isset($fieldSet->description) && ($desc = JString::trim($fieldSet->description))) {
        echo '<p class="tip">' . $this->escape(JText::_($desc)) . '</p>';
    }
    echo '<fieldset class="panelform"><table class="admintable">';
    foreach ($this->form->getFieldset($name) as $field) {
        echo '<tr><td class="key">' . $field->label . '</td><td>' . $field->input . '</td></tr>';
    }
    echo '</table></fieldset>';
    if (!J16) {
        echo $sliders->endPanel();
    }
}

if (J16) {
    echo JHtml::_('sliders.end');
} else {
    echo $sliders->endPane();
}

echo '</div>';
echo '<div class="clr"></div>';
if (J16 && JoomDOCAccessDocument::admin()) {
    echo '<div class="width-100 fltlft">';
    echo JHtml::_('sliders.start', 'permissions-sliders-' . $this->document->id, array('useCookie' => 1));
    echo JHtml::_('sliders.panel', JText::_('JOOMDOC_FIELDSET_RULES'), 'access-rules');
    echo '<fieldset class="panelform">';
    echo $this->form->getLabel('rules');
    echo $this->form->getInput('rules');
    echo '</fieldset>';
    echo JHtml::_('sliders.end');
    echo '</div>';
}
echo '<div>';
echo '<input type="hidden" name="task" value="" />';
echo '<input type="hidden" name="return" value="' . JRequest::getCmd('return') . '" />';
echo JHtml::_('form.token');
echo '</div>';
echo '</form>';
echo $tabs->endPanel();
echo $tabs->endPane();
?>