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

DEFINE('PF_REGEX_PICASA', 'picasa');

class PhotoFeedPicasa extends PhotoFeedPlugins
{

	function PhotoFeedPicasa($config = array())
	{
		parent::__construct($config);
	}


	function getHTML($feed, $limit, $params,$PhotoFeedOutput) {
		$imageWidth     = $params->get('picasaWidth');
		$imageHeight     = $params->get('picasaHeight');

		PhotoFeedLog::log($params, "Picasa found ".sizeof($feed->get_items())." will output ".$limit." images" );

		$index = 1;
		foreach ($feed->get_items() as $item)  {
			if ($photo = $item->get_enclosure()) {
				
				$img = $this->getImageFromDescription($item->get_description());
				$imageThumbnailUrl = $img['src'];
				
				$galleryUrl = $this->getLibraryLink($item->get_description());
				
				$media_group = $item->get_item_tags('http://search.yahoo.com/mrss/', 'group');
				$media_content = $media_group[0]['child']['http://search.yahoo.com/mrss/']['content'];
				
				$imageFullSizeUrl = $media_content[0]['attribs']['']['url'];
				$imageDescription = $media_content[0]['attribs']['']['description'];
				$imageTitle = $media_content[0]['attribs']['']['title'];

				
				
				//PhotoFeedLog::log($params, "#".$index." DESC=".$item->get_description()." IMG=".$img);
				$PhotoFeedOutput->addHTMLEntry($galleryUrl, $imageFullSizeUrl, $imageThumbnailUrl, $imageDescription, $imageTitle, $imageWidth, $imageHeight);

				if ($index++ >= $limit)
				{
					return;
				}
			}
		}
	}
}