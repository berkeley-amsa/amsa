<?php
/**
* @package   com_zoo Component
* @file      controller.php
* @version   2.4.9 May 2011
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

/*
	Class: AppController
		The Controller Class.
*/
class AppController extends JController {

	public $app;
	public $request;
	public $option;
	public $controller;
	protected static $_views = array();

 	/*
		Function: __construct
			Class constructor
			
		Parameters:
			$app - App instance
			$config - Array
	*/
	public function __construct($app, $config = array()) {
		parent::__construct($config);

		// init vars
		$this->app        = $app;
		$this->request    = $app->request;
		$this->option     = $app->system->application->scope;
		$this->controller = $this->getName();

	}

	/*
		Function: getView
			Method to get a reference to the current view and load it if necessary.

		Parameters:
			$name - The view name. Optional, defaults to the controller
			$config - Configuration array for view. Optional.

		Returns:
			AppView
	*/
	public function getView($name = '', $config = array()) {

		// set name
		if (empty($name)) {
			$name = $this->getName();
		}

		// create view
		if (empty($this->_views[$name])) {
			$this->_views[$name] = new AppView(array_merge(array('name' => $name), $config));
		}

		// automatically pass all public class variables on to view
		foreach (get_object_vars($this) as $var => $value) {
			if (substr($var, 0, 1) != '_') {
				$this->_views[$name]->$var = $value;
			}
		}
		
		return $this->_views[$name];
	}

	/*
    	Function: bind
    	  Binds a named array/hash to a object.
		
		Parameters:
	      object - Object.
	      data   - An associative array or object.
	      ignore - An array or space separated list of fields not to bind.
	
	   Returns:
	      Void
 	*/
	public static function bind($object, $data, $ignore = array()) {

		if (!is_array($data) && !is_object($data)) {
			throw new AppException(__CLASS__.'::bind() failed. Invalid from argument');
		}

		if (is_object($data)) {
			$data = get_object_vars($data);
		}
		
		if (!is_array($ignore)) {
			$ignore = explode(' ', $ignore);
		}
		
		foreach (get_object_vars($object) as $k => $v) {
			
			// ignore protected attributes
			if ('_' == substr($k, 0, 1)) {
				continue;
			}
			
			// internal attributes of an object are ignored
			if (isset($data[$k]) && !in_array($k, $ignore)) {
				$object->$k = $data[$k];
			}
		}
	}

	/*
		Function: l
			Translates a string into the current language

		Parameters:
			$string - String to translate
			$js_safe - Make the result javascript safe

		Returns:
			Mixed
	*/	
	public function l($string, $js_safe = false) {
		return $this->app->language->l($string, $js_safe);
	}

}

/*
	Class: AppControllerException
*/
class AppControllerException extends AppException {}