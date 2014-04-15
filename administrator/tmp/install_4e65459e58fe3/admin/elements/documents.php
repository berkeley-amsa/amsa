<?php

/**
 * Popup element to select document.
 *
 * @version		$Id$
 * @package		ARTIO Booking
 * @subpackage  elements
 * @copyright	Copyright (C) 2010 ARTIO s.r.o.. All rights reserved.
 * @author 		ARTIO s.r.o., http://www.artio.net
 * @license     GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * @link        http://www.artio.net Official website
 */

defined('_JEXEC') or die('Restricted access');

JHTML::_('behavior.modal', 'a.modal');

JLoader::register('JoomDOCRoute', JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_joomdoc' . DS . 'libraries' . DS . 'joomdoc' . DS . 'application' . DS . 'route.php');

include_once(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_joomdoc' . DS . 'defines.php');

class JElementDocuments extends JElement {

    /**
     * Display button to open popup window.
     *
     * @param string $name
     * @param mixed  $value 
     * @param mixed  $node
     * @param string $control_name
     */
    function fetchElement ($name, $value, $node, $control_name = null) {
        $db =& JFactory::getDbo();
        /* @var $db JDatabase */
        $doc =& JFactory::getDocument();
        /* @var $doc JDocumentHTML */

        $name = htmlspecialchars($name, ENT_QUOTES);

        if ($control_name) {
            $fieldName = $control_name . '[' . $name . ']';
        } else {
            $fieldName = $name;
        }

        $script[] = '	function jSelectJoomdocDocument(id, title) {';
        $script[] = '		document.getElementById("' . $name . '_id").value = id;';
        $script[] = '		document.getElementById("' . $name . '_name").value = title;';
        $script[] = '		SqueezeBox.close();';
        $script[] = '	}';
        $script[] = '	function jResetJoomdocDocument() {';
        $script[] = '		document.getElementById("' . $name . '_id").value = "";';
        $script[] = '		document.getElementById("' . $name . '_name").value = "";';
        $script[] = '	}';

        $doc->addScriptDeclaration(implode(PHP_EOL, $script));

        $db->setQuery('SELECT `title` FROM `#__joomdoc` WHERE `path` = ' . $db->quote($value) . ' ORDER BY `version` DESC', 0, 1);
        $title = $db->loadResult();
        if (is_null($title)) {
            $title = $value;
        }

        $html = '<div style="float: left; height: 19px; padding-top: 3px;">';
        $html .= '  <input style="color: #000000;" size="40" type="text" id="' . $name . '_name" value="' . htmlspecialchars($title, ENT_QUOTES) . '" disabled="disabled" />';
        $html .= '</div>';
        $html .= '<div class="button2-left">';
        $html .= '  <div class="blank">';
        $select = htmlspecialchars(JText::_('Select'), ENT_QUOTES);
        $html .= '    <a class="modal" title="' . $select . '" href="' . JRoute::_(JoomDOCRoute::modalDocuments()) . '" rel="{handler: \'iframe\', size: {x: 1000, y: 600}}">' . $select . '</a>';
        $html .= '  </div>';
        $html .= '</div>';
        $html .= '<div class="button2-left">';
        $html .= '  <div class="blank">';
        $reset = htmlspecialchars(JText::_('Reset'), ENT_QUOTES);
        $html .= '    <a title="' . $reset . '" href="javascript:void(0)" onclick="jResetJoomdocDocument()">' . $reset . '</a>';
        $html .= '  </div>';
        $html .= '</div>';
        $html .= '<input type="hidden" id="' . $name . '_id" name="' . $fieldName . '" value="' . htmlspecialchars($value, ENT_QUOTES) . '" />';

        return $html;
    }
}
?>