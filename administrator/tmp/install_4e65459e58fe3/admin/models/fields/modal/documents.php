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

jimport('joomla.form.formfield');

include_once (JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_joomdoc' . DS . 'defines.php');
include_once (JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_joomdoc' . DS . 'libraries' . DS . 'joomdoc' . DS . 'application' . DS . 'route.php');

class JFormFieldModal_Documents extends JFormField
{
    /**
     * The form field type.
     *
     * @var		string
     * @since	1.6
     */
    protected $type = 'Modal_Documents';

    /**
     * Method to get the field input markup.
     *
     * @return string The field input markup.
     */
    protected function getInput()
    {
        JHtml::_('behavior.modal', 'a.modal');
        
        $script[] = '	function jSelectJoomdocDocument(id, title) {';
        $script[] = '		document.id("' . $this->id . '_id").value = id;';
        $script[] = '		document.id("' . $this->id . '_name").value = title;';
        $script[] = '		SqueezeBox.close();';
        $script[] = '	}';
        $script[] = '	function jResetJoomdocDocument() {';
        $script[] = '		document.id("' . $this->id . '_id").value = "";';
        $script[] = '		document.id("' . $this->id . '_name").value = "";';
        $script[] = '	}';
        
        JFactory::getDocument()->addScriptDeclaration(implode("\n", $script));
        
        $link = JoomDOCRoute::modalDocuments();
        
        $db = JFactory::getDBO();
        /* @var $db = JDatabaseMySQL */
        $db->setQuery('SELECT `title` FROM `#__joomdoc`' . ' WHERE `path` = ' . $db->quote($this->value));
        $title = $db->loadResult();
        
        if (empty($title))
            $title = $this->value;
        
        $title = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
        
        $html[] = '<div class="fltlft">';
        $html[] = '  <input type="text" id="' . $this->id . '_name" value="' . $title . '" disabled="disabled" size="35" />';
        $html[] = '</div>';
        
        $html[] = '<div class="button2-left">';
        $html[] = '  <div class="blank">';
        $html[] = '	<a class="modal" title="' . JText::_('JOOMDOC_CHANGE_DOCUMENT') . '"  href="' . JRoute::_($link) . '" rel="{handler: \'iframe\', size: {x: 800, y: 450}}">' . JText::_('JOOMDOC_CHANGE_DOCUMENT_BUTTON') . '</a>';
        $html[] = '  </div>';
        $html[] = '</div>';
        
        $html[] = '<div class="button2-left">';
        $html[] = '  <div class="blank">';
        $html[] = '     <a href="javascript:jResetJoomdocDocument()" title="">' . JText::_('JOOMDOC_RESET') . '</a>';
        $html[] = '  </div>';
        $html[] = '</div>';
        
        $class = $this->required ? ' class="required modal-value"' : '';
        
        $html[] = '<input type="hidden" id="' . $this->id . '_id"' . $class . ' name="' . $this->name . '" value="' . $this->value . '" />';
        
        return implode("\n", $html);
    }
}