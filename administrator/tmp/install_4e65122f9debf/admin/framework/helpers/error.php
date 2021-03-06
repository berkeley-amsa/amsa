<?php
/**
* @package   com_zoo Component
* @file      error.php
* @version   2.4.9 May 2011
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

/*
	Class: ErrorHelper
		Error helper class. Wrapper for JError.
*/
class ErrorHelper extends AppHelper {

	/*
		Function: __call
			Map all functions to JError class

		Parameters:
			$name - Method name
			$args - Method arguments

		Returns:
			Mixed
	*/	
    public function __call($method, $args) {
		return $this->_call(array('JError', $method), $args);
    }
	
}