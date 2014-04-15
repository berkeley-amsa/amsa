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

class PhotoFeedOutput
{
	var $html = "";
	var $numberOfColumns = 3;
	var $columnsCounter = 0;
	var $imageList = array();
	var $library = null;
	var $displayLinkToGallery = true;
	var $galleryUrl = null;

	function PhotoFeedOutput($params)
	{
		$this->numberOfColumns = $params->get('NumberOfColumns', 2);
		$this->defaultImage = $params->get('DefaultImage', 'media/plg_photofeed/notfound.jpg');
		$this->reverseOrder = $params->get('reverseOrder', 1);
		$this->library = $params->get('library', 'shadowbox');
		$this->displayLinkToGallery = $params->get('displayLinkToGallery', true);
		$this->columnsCounter = 0;
	}

	function addHTMLEntry($galleryUrl, $imageFullSizeUrl, $imageThumbnailUrl, $imageDescription, $imageTitle, $imageWidth, $imageHeight) {
		$this->galleryUrl = $galleryUrl;
		
		$html = '<span class="photofeedLineItem">';
		$html .= '<a href="'.$imageFullSizeUrl.'" rel="'.$this->library.'" title="'.$imageTitle.'"><img src="'.$imageThumbnailUrl.'"  alt="'.$imageTitle.'" width="'.$imageWidth.'" height="'.$imageHeight.'" /></a>';
		$html .= '</span>';

		$this->imageList[] = $html;
	}

	function addCustomHTMLEntry($html) {
		$this->imageList[] = '<span class="photofeedLineItem">'.$html.'</span>';
	}

	function createHTMLForImage($image) {
		if ($this->columnsCounter == 0) {
			if (strlen($this->html) != 0) {
				$this->html .= "</div><div class='photofeedLine'>";
			} else {
				$this->html .= "<div class='photofeedLine'>";
			}
		}
		$this->html .= $image;
		$this->columnsCounter++;

		if ($this->columnsCounter == $this->numberOfColumns) {
			$this->columnsCounter = 0;

		}
	}

	function getHtmlOutput() {
		if (sizeof($this->imageList) == 0) {
			//No images found
			$filecontent .= "<img src='".$this->defaultImage."' width='150px' height='150px' target='_blank' />";
			return $filecontent;
		} else {
				
			if ($this->reverseOrder) {
				$this->imageList = array_reverse($this->imageList);
			}
				
			foreach ($this->imageList as $image) {
				$this->createHTMLForImage($image);
			}
				
			$html = "<!-- www.waltercedric.com photofeed plugin for joomla! --><div class='photofeed'>".$this->html."</div>";
			
			if ($this->displayLinkToGallery) {
				$html .= '<div class="photofeedGallery"><a href="'.$this->galleryUrl.'">Gallery</a></div>';
			}
			
			$html .= "</div>";
			
			
			
			return $html;
		}
	}

}