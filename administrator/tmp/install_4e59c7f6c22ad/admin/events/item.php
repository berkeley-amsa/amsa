<?php
/**
* @package   com_zoo
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

/*
	Class: ItemEvent
		Item events.
*/
class ItemEvent {

	public static function init($event) {

		$item = $event->getSubject();

	}

	public static function saved($event) {

		$item = $event->getSubject();
		$new = $event['new'];

	}

	public static function deleted($event) {

		$item = $event->getSubject();

	}

	public static function stateChanged($event) {

		$item = $event->getSubject();
		$old_state = $event['old_state'];
		
	}

	public static function beforeDisplay($event) {

		$item = $event->getSubject();

	}

	public static function afterDisplay($event) {

		$item = $event->getSubject();
		$html = $event['html'];

	}

}
