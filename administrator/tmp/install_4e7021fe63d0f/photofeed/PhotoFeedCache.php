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

require_once(dirname( __FILE__ ).DS.'PhotoFeedLog.php');

DEFINE('PF_CACHE_LOCATION', JPATH_SITE.DS.'cache'.DS);
DEFINE('PF_CACHE_PREFIXE', JPATH_SITE.DS.'cache'.DS.'photofeed-');

class PhotoFeedCache 
{

	function PhotoFeedCache($config = array())
	{
		
	}

	/**
	 * Accessor to get a <code>$file</code> from the cache
	 * @return unknown_type
	 */
	function get ($params , $file) {
		$htmlCachetime     = $params->get('htmlCachetime', 3600);
		$enableHtmlCache     = $params->get('enableHtmlCache', 1);

		if (file_exists($file) && $enableHtmlCache) {
			$timedif = @(time() - filemtime($file));
			PhotoFeedLog::log($params, "Timediff ".$timedif);

			if ($timedif < $htmlCachetime) {
				PhotoFeedLog::log($params, "Get $file from cache");
				return $this->readFromCache($params, $file);
			}
		}
		PhotoFeedLog::log($params, "File too old in cache, Create new file $file");
		return null;
	}


	/**
	 * Write a $file along with its content into the cache
	 * @param $file
	 * @param $filecontent
	 * @return unknown_type
	 */
	function writeIntoCache($params, $cacheid, $filecontent) {
		$file = PF_CACHE_PREFIXE.$cacheid.".html";
		
		if ($f = @fopen($file, 'w')) {
			PhotoFeedLog::log($params,"Write file $file into cache");
			fwrite ($f, $filecontent, strlen($filecontent));
			fclose($f);
		} else {
			PhotoFeedLog::log($params,"ERROR: File in cache at $file is not writable");
		}
	}


	/**
	 * Read a $file from the cache and return its content
	 * @param $file
	 * @return unknown_type
	 */
	function readFromCache($params,$file) {
		if ($fh = @fopen($file, 'r')) {
			PhotoFeedLog::log($params, "Read file ".$file." from cache");
					
			$filecontent = fread($fh, filesize($file));
			fclose($fh);
			return $filecontent;
		} else {
			PhotoFeedLog::log($params,"ERROR: File in cache at ".$file. " can not be read");
			return null;
		}
	}
}