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

require_once(dirname( __FILE__ ).DS.'PhotoFeedCache.php');
require_once(dirname( __FILE__ ).DS.'PhotoFeedOutput.php');

require_once(dirname( __FILE__ ).DS.'PhotoFeedFlickr.php');
require_once(dirname( __FILE__ ).DS.'PhotoFeedSmugmug.php');
require_once(dirname( __FILE__ ).DS.'PhotoFeedPicasa.php');
require_once(dirname( __FILE__ ).DS.'PhotoFeedG2.php');
require_once(dirname( __FILE__ ).DS.'PhotoFeedYouTube.php');
require_once(JPATH_SITE.DS.'libraries'.DS.'simplepie'.DS.'simplepie.php');
	
class PhotoFeedHTML
{

	var $PhotoFeedCache = null;

	//this object hold the output
	var $PhotoFeedOutput = null;

	var $PhotoFeedFlickr = null;
	var $PhotoFeedSmugmug = null;
	var $PhotoFeedPicasa = null;
	var $PhotoFeedG2 = null;
	var $PhotoFeedYouTube = null;

	var $enableRssCache = null;
	var $enableHtmlCache = null;
	var $rssCachetime = null;

	function PhotoFeedHTML($params)
	{
		$this->PhotoFeedCache= & new PhotoFeedCache();
		$this->PhotoFeedOutput = & new PhotoFeedOutput($params);
		$this->PhotoFeedFlickr = & new PhotoFeedFlickr();
		$this->PhotoFeedSmugmug = & new PhotoFeedSmugmug();
		$this->PhotoFeedPicasa = & new PhotoFeedPicasa();
		$this->PhotoFeedG2 = & new PhotoFeedG2();
		$this->PhotoFeedYouTube = & new PhotoFeedYouTube();
		$this->rssCachetime     = $params->get('RssCachetime', 3600);
		$this->enableRssCache = $params->get('enableRssCache', '1');
		$this->enableHtmlCache = $params->get('enableHtmlCache', '1');
	}

	/**
	 * Ask cache and write eventually a new file version
	 * @param $feed
	 * @param $number
	 * @return unknown_type
	 */
	function withRSSFeed($params, $feed, $limit) {
		//php translates that '&' to '&amp' ! and obviously the address does not remain the same
		$feed = html_entity_decode($feed);
		//error_log("html_entity_decode".html_entity_decode($feed));
		//error_log("urldecode".urldecode($feed));
		//error_log("htmlspecialchars_decode".htmlspecialchars_decode($feed));
		
		//cache file has to be unique per feed
		$cacheid       = md5($feed . $limit);

		$filecontent = null;

		//Ask cache if it has a file
		if ($this->enableHtmlCache) {
			$filecontent = $this->PhotoFeedCache->get($params, PF_CACHE_PREFIXE.$cacheid.".html");
		}

		if ($filecontent == null)
		{
			//file not found in cache

			// a classforname would be great
			if(strpos($feed, PF_REGEX_FLICKR))
			{
				$SimplePie = new SimplePie($feed, null, $this->rssCachetime);
				$SimplePie->handle_content_type();
				$this->PhotoFeedFlickr->getHTML($SimplePie, $limit, $params, $this->PhotoFeedOutput);
			}
			else
			if(strpos($feed, PF_REGEX_SMUGMUG))
			{
				$feed = $this->clean_htmlentities ($feed);
				
				$SimplePie = new SimplePie($feed, null, $this->rssCachetime);
				$SimplePie->handle_content_type();
				$this->PhotoFeedSmugmug->getHTML($SimplePie, $limit, $params,$this->PhotoFeedOutput);
			}
			else if(strpos($feed, PF_REGEX_PICASA))
			{
				$SimplePie = new SimplePie($feed, null, $this->rssCachetime);
				$SimplePie->handle_content_type();
				$this->PhotoFeedPicasa->getHTML($SimplePie, $limit, $params, $this->PhotoFeedOutput);
			}
			else if(strpos($feed, PF_REGEX_ISTOCKPHOTO))
			{
				$SimplePie = new SimplePie($feed, null, $this->rssCachetime);
				$SimplePie->handle_content_type();
				$this->PhotoFeedG2->getHTML($SimplePie, $limit, $params, $this->PhotoFeedOutput);
			}
			else if(strpos($feed, PF_REGEX_YOUTUBE))
			{
				$SimplePie = new SimplePie($feed, null, $this->rssCachetime);
				$SimplePie->handle_content_type();
				$this->PhotoFeedYouTube->getHTML($SimplePie, $limit, $params, $this->PhotoFeedOutput);
			}
			else if(strpos($feed, PF_REGEX_G2) || true) //default
			{
				$SimplePie = new SimplePie($feed, null, $this->rssCachetime);
				$SimplePie->handle_content_type();
				$this->PhotoFeedG2->getHTML($SimplePie, $limit, $params, $this->PhotoFeedOutput);
			}

			$filecontent = $this->PhotoFeedOutput->getHtmlOutput();
			if ($this->enableHtmlCache) {
				$this->PhotoFeedCache->writeIntoCache($params, $cacheid, $filecontent);
			}
		}

		return $filecontent;
	}
	
	function clean_htmlentities ($str) {
		return str_replace(array('&amp;','&#38;'),'&',htmlentities($str));
	}
	

}
