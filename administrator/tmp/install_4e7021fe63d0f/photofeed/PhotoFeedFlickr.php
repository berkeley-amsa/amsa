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

DEFINE('PF_REGEX_FLICKR', 'flickr.com');

class PhotoFeedFlickr extends PhotoFeedPlugins
{
	function __construct($config = array())
	{
		parent::__construct($config);
	}

	function getHTML($feed, $limit, $params, $PhotoFeedOutput)
	{
		$library = $params->get('library', 'lightbox2');
		$flickrSize = $params->get('flickrSize');
		$index = 1;
		PhotoFeedLog::log($params, "Flickr found ".sizeof($feed->get_items())." will output ".$limit." images" );
		
		$i = 0;
		foreach ($feed->get_items() as $item)  {
			if ($photo = $item->get_enclosure()) {
				$href= $this->getLibraryLink($item->get_description());
				$img = $this->getImageFromDescription($item->get_description());
				$thumb_url = $this->selectImage($img['src'], $flickrSize);
				
				PhotoFeedLog::log($params, "#".$index." DESC=".$item->get_description()." IMG=".$img);

				$PhotoFeedOutput->addCustomHTMLEntry('<a href="'.$href.'" rel="'.$library.'"><img id="photo_' . $i++ . '" src="' . $thumb_url . '" /></a>'."\n");
				if ($index++ >= $limit)
				{
					return;
				}
			}
		}
	}

	function selectImage($img, $size) {
		$img = explode('/', $img);
		$filename = array_pop($img);

		// The sizes listed here are the ones Flickr provides by default.  Pass the array index in the
		//$size variable to selct one.
		// 0 for square, 1 for thumb, 2 for small, etc.
		$s = array(
            '_s.', // square  
           '_t.', // thumb  
           '_m.', // small  
           '.',   // medium  
           '_b.'  // large  
		);

		$img[] = preg_replace('/(_(s|t|m|b))?\./i', $s[$size], $filename);
		return implode('/', $img);
	}
}
