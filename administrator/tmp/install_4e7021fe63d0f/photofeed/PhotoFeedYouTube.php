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

DEFINE('PF_REGEX_YOUTUBE', 'youtube.com');

class PhotoFeedYouTube extends PhotoFeedPlugins
{
	function __construct($config = array())
	{
		parent::__construct($config);
	}

	function getHTML($feed, $FeedNumberOfImages, $params,$limit) {
		$library = $params->get('library', 'lightbox2');
		$index = 1;
		
		PhotoFeedLog::log($params, "YouTube found ".sizeof($feed->get_items())." will output ".$limit." images" );
		
		foreach ($feed->get_items() as $item)  {
			if ($photo = $item->get_enclosure()) {
			}
			$img = $this->getImageFromDescription($item->get_description());
			//error_log($item->get_description());
			$url = $this->getLibraryLink($item->get_description());
			
			PhotoFeedLog::log($params, "#".$index." DESC=".$item->get_description()." IMG=".$img);

			$PhotoFeedOutput->addHTMLEntry('<a href="'.$url.'" rel="'.$library.'"><img id="photo_' . $i . '" src="' . $img . '" width="'.$picasaWidth.'" height="'.$picasaHeight.'" /></a>');
			if ($index++ >= $limit)
			{
				return;
			}
			//}
		}
	}

	//YouTube specific
	function getImageFromDescription($description) {
		$content = strip_tags($description, '<img>');
		preg_match('/<img([^>]*)src="([^"]*)\.youtube\.com([^"]*)"([^>]*)>/i', $content, $m);
		$image = $m[2].'.youtube.com'.$m[3];
		return $image;
	}


}