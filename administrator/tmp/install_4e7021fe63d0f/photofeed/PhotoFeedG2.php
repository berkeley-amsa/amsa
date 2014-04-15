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

DEFINE('PF_REGEX_G2', 'g2_itemId');
DEFINE('PF_REGEX_OTHER', '.com');
DEFINE('PF_REGEX_ISTOCKPHOTO', 'www.istockphoto.com');

class PhotoFeedG2 extends PhotoFeedPlugins
{
	function __construct($config = array())
	{
		parent::__construct($config);
	}

	function getHTML($feed, $limit, $params,$PhotoFeedOutput) {
		$index = 1;
		$library = $params->get('library', 'lightbox2');
		$width     = $params->get('g2Width');
		$height     = $params->get('g2Height');
		
		PhotoFeedLog::log($params, "G2 found ".sizeof($feed->get_items())." will output ".$limit." images" );
		
		foreach ($feed->get_items() as $item)  {
			//if ($photo = $item->get_enclosure()) {}

			$img = $this->getImageFromDescription($item->get_description());
			$url = $this->getLibraryLink($item->get_description());

			PhotoFeedLog::log($params, "#".$index." DESC=".$item->get_description()." IMG=".$img);
			
			$PhotoFeedOutput->addHTMLEntry('<a href="'.$url.'" rel="'.$library.'" ><img id="photo_' . $img . '" src="' . $img . '" width="'.$width.'" height="'.$height.'" /></a>');
			if ($index++ >= $limit)
			{
				return;
			}
		}
	}

}
