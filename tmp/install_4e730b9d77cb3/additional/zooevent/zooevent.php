<?php
/**
* @package   com_zoo Component
* @file      zooevent.php
* @version   2.4.9 May 2011
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.plugin.plugin');

class plgSystemZooevent extends JPlugin {


	public $app;

	/**
	 * onAfterInitialise handler
	 *
	 * Adds ZOO event listeners
	 *
	 * @access	public
	 * @return null
	 */
	function onAfterInitialise() {

		// load ZOO config
		require_once(JPATH_ADMINISTRATOR.'/components/com_zoo/config.php');

		// Here are a number of events for demonstration purposes.
		// Have a look at administrator/components/com_zoo/config.php
		// and also at administrator/components/com_zoo/events/

		// Get the ZOO App instance
		$zoo = App::getInstance('zoo');

		// register event
		$zoo->event->dispatcher->connect('item:saved', array('plgSystemZooevent', 'itemSaved'));
		
		// register and connect events
		
		//$zoo->event->register('ApplicationEvent');
		//$zoo->event->dispatcher->connect('application:init', array('ApplicationEvent', 'init'));
		//$zoo->event->dispatcher->connect('application:saved', array('ApplicationEvent', 'saved'));
		//$zoo->event->dispatcher->connect('application:deleted', array('ApplicationEvent', 'deleted'));

		//$zoo->event->register('CategoryEvent');
		//$zoo->event->dispatcher->connect('category:init', array('CategoryEvent', 'init'));
		//$zoo->event->dispatcher->connect('category:saved', array('CategoryEvent', 'saved'));
		//$zoo->event->dispatcher->connect('category:deleted', array('CategoryEvent', 'deleted'));
		//$zoo->event->dispatcher->connect('category:stateChanged', array('CategoryEvent', 'stateChanged'));

		//$zoo->event->register('ItemEvent');
		//$zoo->event->dispatcher->connect('item:init', array('ItemEvent', 'init'));
		//$zoo->event->dispatcher->connect('item:saved', array('ItemEvent', 'saved'));
		//$zoo->event->dispatcher->connect('item:deleted', array('ItemEvent', 'deleted'));
		//$zoo->event->dispatcher->connect('item:stateChanged', array('ItemEvent', 'stateChanged'));
		//$zoo->event->dispatcher->connect('item:beforedisplay', array('ItemEvent', 'beforeDisplay'));
		//$zoo->event->dispatcher->connect('item:afterdisplay', array('ItemEvent', 'afterDisplay'));

		//$zoo->event->register('CommentEvent');
		//$zoo->event->dispatcher->connect('comment:init', array('CommentEvent', 'init'));
		//$zoo->event->dispatcher->connect('comment:saved', array('CommentEvent', 'saved'));
		//$zoo->event->dispatcher->connect('comment:deleted', array('CommentEvent', 'deleted'));
		//$zoo->event->dispatcher->connect('comment:stateChanged', array('CommentEvent', 'stateChanged'));

		//$zoo->event->register('SubmissionEvent');
		//$zoo->event->dispatcher->connect('submission:init', array('SubmissionEvent', 'init'));
		//$zoo->event->dispatcher->connect('submission:saved', array('SubmissionEvent', 'saved'));
		//$zoo->event->dispatcher->connect('submission:deleted', array('SubmissionEvent', 'deleted'));

		//$zoo->event->register('ElementEvent');
		//$zoo->event->dispatcher->connect('element:download', array('ElementEvent', 'download'));
		//$zoo->event->dispatcher->connect('element:configform', array('ElementEvent', 'configForm'));
		//$zoo->event->dispatcher->connect('element:configxml', array('ElementEvent', 'configXML'));
		//$zoo->event->dispatcher->connect('element:afterdisplay', array('ElementEvent', 'afterDisplay'));
		//$zoo->event->dispatcher->connect('element:beforedisplay', array('ElementEvent', 'beforeDisplay'));
		//$zoo->event->dispatcher->connect('element:afteredit', array('ElementEvent', 'afterEdit'));
		
	}

	function itemSaved($event) {

		//$item = $event->getSubject();
		//$new = $event['new'];

		// do whatever you'd like to do

	}
	
}