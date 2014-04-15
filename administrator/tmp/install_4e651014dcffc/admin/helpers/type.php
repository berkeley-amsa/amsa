<?php
/**
* @package   com_zoo Component
* @file      type.php
* @version   2.4.9 May 2011
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

/*
   Class: TypeHelper
   The Helper Class for item
*/
class TypeHelper extends AppHelper {
	
	/*
		Function: setUniqueIndentifier
			Sets a unique type identifier

		Parameters:
			$type - Type object

		Returns:
			Type
	*/	
	public function setUniqueIndentifier($type) {	
		if ($type->id != $type->identifier) {
			// check identifier
			$tmp_identifier = $type->identifier;

			// build resource
			$resource = $type->getApplication()->getResource() .'types/';

			$i = 2;
			while ($this->app->path->path($resource.$tmp_identifier.'.xml')) {
				$tmp_identifier = $type->identifier . '-' . $i;
			}
			$type->identifier = $tmp_identifier;
		}
		return $type;
	}
	
}