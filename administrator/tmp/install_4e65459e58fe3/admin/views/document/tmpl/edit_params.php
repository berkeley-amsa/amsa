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

foreach ($this->form->getFieldsets('params') as $name => $fieldSet) {
    echo JHtml::_('sliders.panel', JText::_($fieldSet->label), $name . '-params');
    if (isset($fieldSet->description) && JString::trim($fieldSet->description))
        echo '<p class="tip">' . $this->escape(JText::_($fieldSet->description)) . '</p>';
    echo '<fieldset class="panelform"><ul class="adminformlist">';
    foreach ($this->form->getFieldset($name) as $field)
        echo '<li>' . $field->label . $field->input . '</li>';
    echo '</ul></fieldset>';
}
?>