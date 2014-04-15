<?php
/**
* @package   com_zoo
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

class JElementCountries extends JElement {

	function fetchElement($name, $value, $node, $control_name) {

		$app = App::getInstance('zoo');

		// get element from parent parameter form
		$element = $this->_parent->element;
		$config  = $element->getConfig();

		return $app->html->_('zoo.countryselectlist', $app->country->getIsoToNameMapping(), $control_name.'[selectable_countries][]', $config->get('selectable_countries', array()), true);
	}

}