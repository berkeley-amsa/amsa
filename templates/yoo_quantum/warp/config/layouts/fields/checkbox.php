<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright 2007 - 2011 YOOtheme GmbH
* @license   YOOtheme Proprietary Use License (http://www.yootheme.com/license)
*/

printf('<input %s />', $control->attributes(array_merge($node->attr(), array('type' => 'checkbox', 'name' => $name, 'value' => $value)), array('label', 'description', 'default')));