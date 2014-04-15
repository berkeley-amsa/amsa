<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright 2007 - 2011 YOOtheme GmbH
* @license   YOOtheme Proprietary Use License (http://www.yootheme.com/license)
*/

printf('<textarea %s>%s</textarea>', $control->attributes(array_merge($node->attr(), compact('name')), array('label', 'description', 'default')), htmlspecialchars($value));