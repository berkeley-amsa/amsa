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

include_once(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_joomdoc' . DS . 'defines.php');

class JElementIcons extends JElement {

    var $_name = 'Icons';

    function fetchElement ($name, $value, &$node, $control_name) {
        $field = JText::_('JOOMDOC_ICON_THEME_NOT_AVAILABLE');
        if (JFolder::exists(JOOMDOC_PATH_ICONS)) {
            $themes = JFolder::folders(JOOMDOC_PATH_ICONS, '.', false, false);
            foreach ($themes as $theme)
                $options[] = JHtml::_('select.option', $theme, $theme, 'id', 'title');
            if (isset($options)) {
                $fieldName = $control_name ? $control_name . '[' . $name . ']' : $name;
                $field = JHtml::_('select.genericlist', $options, $fieldName, '', 'id', 'title', $value);
            }
        }
        return $field;
    }
}
?>