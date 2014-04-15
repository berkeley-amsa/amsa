<?php
/**
* @version	$Id: helper.php 2009-05-30  $
* @package	Google Web Elements - Custom Search
* @copyright	Copyright (C) 2009 Open4G Media. All rights reserved.
* @license	GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Direct Access to this location is not allowed.');

class modGoogleWebElementCustomSearchHelper
{
	function getLink(&$params)
	{
		$document =& JFactory::getDocument();

		foreach($document->_links as $link)
		{
			if(strpos($link, 'application/'.$params->get('format').'+xml')) {
				preg_match("#href=\"(.*?)\"#s", $link, $matches);
				return $matches[1];
			}
		}

	}
}