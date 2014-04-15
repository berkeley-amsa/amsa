<?php
/**
 * @version		photofeed.php
 * @package
 * @copyright	Copyright (C) 2009 Cedric Walter. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

require_once(dirname( __FILE__ ).DS.'PhotoFeedPlugins.php');
require_once(dirname( __FILE__ ).DS.'PhotoFeedLog.php');

DEFINE('PF_REGEX_SMUGMUG', 'smugmug.com');

class PhotoFeedSmugmug extends PhotoFeedPlugins
{
	function PhotoFeedSmugmug($config = array())
	{
		parent::__construct($config);
	}

	function getHTML($feed, $limit, $params, $PhotoFeedOutput) {
		$library = $params->get('library', 'lightbox2');
		$smugmugSize     = $params->get('smugmugSize');

		$index = 1;
		PhotoFeedLog::log($params, "SmugMug found ".sizeof($feed->get_items())." will output ".$limit." images" );
		
		foreach ($feed->get_items() as $item)  {
			if ($photo = $item->get_enclosure()) {
				$img = $this->getImageFromDescription($item->get_description());
				$url = $this->getLibraryLink($item->get_description());
				
				PhotoFeedLog::log($params, "#".$index." DESC=".$item->get_description()." IMG=".$img);
				
				$thumb_url=substr($img, 0, -7);
				$thumb_url= $thumb_url . "-".$smugmugSize.".jpg";
					
				$PhotoFeedOutput->addCustomHTMLEntry('<a href="'.$url.'" rel="'.$library.'" ><img id="photo_' . $i . '" src="' . $img['src'] . '" /></a>');
				if ($index++ >= $limit)
				{
					return;
				}
			}
		}
	}
}