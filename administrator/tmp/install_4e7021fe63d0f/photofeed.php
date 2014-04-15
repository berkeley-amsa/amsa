<?php
/**
 * @version		photofeed.php 01.03.01
 * 
 * @package
 * @copyright	Copyright (C) 2009 Cedric Walter. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

//http://picasaweb.google.de/data/feed/base/user/thomas.detlinger/albumid/5503069946101336193?alt=rss&kind=photo&hl=de

/*

<p>smugmug</p>
<p>{rss uri=http://cedricwalter.smugmug.com/hack/feed.mg?Type=gallery&amp;Data=4311718_bRCBj&amp;format=rss200 limit=6}</p>
<p>flickr</p>
<p>{rss uri=http://api.flickr.com/services/feeds/photos_public.gne?id=45007589@N00&lang=en-us&format=rss_200 limit=6}</p>



 * 
 */


// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

require_once(dirname( __FILE__ ).DS.'photofeed'.DS.'PhotoFeedHTML.php');
require_once(dirname( __FILE__ ).DS.'photofeed'.DS.'PhotoFeedLog.php');
jimport( 'joomla.plugin.plugin' );

define('PF_REGEX_MANDATORY_URL', "uri=(.*)\s*");
define('PF_REGEX_MANDATORY_LIMIT', "limit=(.*)\s*");
define('PF_REGEX_OPTION_SIZE',"(size=(.*)\s*)?");
define('PF_REGEX_OPTION_SQUARE',"(square=(.*))?}");

define('PF_REGEX_PATTERN',"~{rss\s*".PF_REGEX_MANDATORY_URL.PF_REGEX_MANDATORY_LIMIT.PF_REGEX_OPTION_SIZE.PF_REGEX_OPTION_SQUARE."~iU");

class plgContentPhotoFEED extends JPlugin
{
	public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);
		$this->loadLanguage();
		$plugin =& JPluginHelper::getPlugin('content', 'photofeed');
		$pluginParams 	= new JParameter( $plugin->params );
		$this->debug= $pluginParams->get('debug','1');
		$this->defaultThumbSize = $pluginParams->get('thumbsize', '75');
		$this->thumbBorder = $pluginParams->get('thumbborder', '3');
		$this->defaultThumbSquare = $pluginParams->get('thumbsquare', 'yes');
		$this->demo= intval($pluginParams->get('demo','1'));
		
		$document =& JFactory::getDocument();
		$document->addStyleSheet('media/plg_content_photofeed/photofeed.css');
	}
	
	var $demo = false;
	var $debug = true;
	var	$defaultThumbSize = null;
	var	$thumbBorder = null;
	var	$defaultThumbSquare = null;

	public function onContentPrepare($context, &$row, &$params, $page = 0)
	{
		$plugin =& JPluginHelper::getPlugin('content', 'photofeed');
		$pluginParams 	= new JParameter( $plugin->params );
		
		//Escape fast
		if (!$pluginParams->get('enabled', 1)) {
			return true;
		}
		
		//simple performance check to determine whether bot should process further
		if ( strpos( $row->text, '{rss uri' ) === false) {
			return true;
		}		
		
		if ($this->demo) {
			$row->text .= '<h1>Plugin <a href="http://wiki.waltercedric.com/index.php?title=Photofeed_fo_Joomla">PhotoFeed</a> in demo mode</h1>
			<font color="red">Note if you copy some example below, add the { in front of smugmugrandom to let the magic happens!</font><br />
			<h2>SmugMug</h2>
			rss uri=http://cedricwalter.smugmug.com/hack/feed.mg?Type=gallery&Data=4311718_bRCBj&format=rss200 limit=5} 
			<br />
			{rss uri=http://cedricwalter.smugmug.com/hack/feed.mg?Type=gallery&Data=4311718_bRCBj&format=rss200 limit=5} 
			<br /><h2>Flickr</h2>
			rss uri=http://api.flickr.com/services/feeds/photos_public.gne?id=45007589@N00&lang=en-us&format=rss_200 limit=12}
			<br />
			{rss uri=http://api.flickr.com/services/feeds/photos_public.gne?id=45007589@N00&lang=en-us&format=rss_200 limit=12}
			<br /><h2>Picasa</h2>
			rss uri=http://picasaweb.google.com/data/feed/base/user/115504007740680881345/albumid/5447801847496445393?alt=rss&kind=photo&hl=en_US limit=10}
			<br />
			{rss uri=http://picasaweb.google.com/data/feed/base/user/115504007740680881345/albumid/5447801847496445393?alt=rss&kind=photo&hl=en_US limit=9}
			<br />			
			';
		}
		
		preg_match_all(PF_REGEX_PATTERN, $row->text, $matches);

		//$cache = & JFactory :: getCache('plgPhotoFeed');
		//$cache->setCaching($params->get('cache','1'));

		//because of joomla fail module caching, add css in here and cache output of module myself
		//modMostReadThumbHelper::addStyleSheet();

	
		// catch and display multiple {rss...} tags in the same page
		for ($i = 0; $i < count($matches[0]); $i++) {
			$feed = (string) $matches[1][$i];
			$limit = $matches[2][$i];
			$thumbSize = $matches[3][$i];
			$thumbSquare = $matches[4][$i];

			//initialize if a square thumbnail
			if (empty($thumbSquare)) {
				$thumbSquare = $this->defaultThumbSquare;
			}
			//initialize the thumbnail size
			if (empty($thumbSize)) {
				$thumbSize = $this->defaultThumbSize;
			}

			//initialize the counter
			if (empty($limit)) {
				$limit = 10;
			}

			$PhotoFeedHTML = & new PhotoFeedHTML($pluginParams);
			
			//$p_content = $cache->call(array( 'PhotoFeedHTML', 'withRSSFeed'), pluginParams, $feed, $limit );
			
			$p_content = $PhotoFeedHTML->withRSSFeed($pluginParams, $feed, $limit );
			
			PhotoFeedLog::log($pluginParams, "Resulting HTML inserted ".$p_content);

			$row->text = str_replace($matches[0][$i], $p_content, $row->text);
		}

		return true;
	}

}
?>