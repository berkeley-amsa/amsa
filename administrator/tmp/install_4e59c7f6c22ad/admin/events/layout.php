<?php
/**
* @package   com_zoo
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

/*
	Class: LayoutEvent
		Layout events.
*/
class LayoutEvent {

	public static function init($event) {

		$app = $event->getSubject();

		$extensions = $event->getReturnValue();

		// get modules
		foreach ($app->path->dirs('modules:') as $module) {
			if ($app->path->path("modules:$module/renderer")) {
				$name = ($xml = $app->xml->loadFile($app->path->path("modules:$module/$module.xml"))) && $xml->getName() == 'install' ? (string) $xml->name : $module;
				$extensions[$name] = array('type' => 'modules', 'name' => $name, 'path' => $app->path->path("modules:$module"));
			}
		}

		// get plugins
		foreach ($app->path->dirs('plugins:') as $plugin_type) {
			foreach ($app->path->dirs('plugins:'.$plugin_type) as $plugin) {
				if ($app->path->path("plugins:$plugin_type/$plugin/renderer")) {
					$resource = "plugins:$plugin_type".(!$app->joomla->isVersion('1.5') ? '/'.$plugin : '')."/$plugin.xml";
					$name = ($xml = $app->xml->loadFile($app->path->path($resource))) && $xml->getName() == 'install' ? (string) $xml->name : $plugin;
					$name = preg_replace('/^\w* - (.*)/i', '$1', $name);
					$extensions[$name] = array('type' => 'plugin', 'name' => $name, 'path' => $app->path->path("plugins:$plugin_type/$plugin"));
				}
			}
		}

		$event->setReturnValue($extensions);

	}

}