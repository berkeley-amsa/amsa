<?php
/*
 * @package Joomla 1.5
 * @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 *
 * @component Phoca Gallery
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined( '_JEXEC' ) or die( 'Restricted access' );

class PhocaGalleryGeo
{
	/*
	 * Geotagging
	 * If no lat or lng will be set by image, it will be automatically set by category
	 */
	function findLatLngFromCategory($categories) {
		$output['lat'] = '';
		$output['lng'] = '';
		foreach ($categories as $category) {
			if (isset($category->latitude) && isset($category->longitude)) {
				if ($category->latitude != '' && $category->latitude != '') {
					$output['lat'] = $category->latitude;
				}
				if ($category->longitude != '' && $category->longitude != '') {
					$output['lng'] = $category->longitude;
				}

				if ($output['lat'] != '' && $output['lng'] != '') {
					return $output;
				}
			} 
		}
		// If nothing will be found, paste some lng, lat
		$output['lat'] = 50.079623358200884;
		$output['lng'] = 14.429919719696045;
		return $output;
	}
	
	function getGeoCoords($filename){
      
		$lat = $long = '';
		$fileOriginal = PhocaGalleryFile::getFileOriginal($filename);
	  
	if (!function_exists('exif_read_data')) {
		return array('latitude' => 0, 'longitude' => 0);
	} else {
	  
		$exif 		= exif_read_data($fileOriginal, 0, true);
		$GPSLatDeg 	= explode('/',$exif['GPS']['GPSLatitude'][0]);
		$GPSLatMin 	= explode('/',$exif['GPS']['GPSLatitude'][1]);
		$GPSLatSec 	= explode('/',$exif['GPS']['GPSLatitude'][2]);
		$GPSLonDeg 	= explode('/',$exif['GPS']['GPSLongitude'][0]);
		$GPSLonMin 	= explode('/',$exif['GPS']['GPSLongitude'][1]);
		$GPSLonSec 	= explode('/',$exif['GPS']['GPSLongitude'][2]);

		$lat = $GPSLatDeg[0]/$GPSLatDeg[1]+
			($GPSLatMin[0]/$GPSLatMin[1])/60+
			($GPSLatSec[0]/$GPSLatSec[1])/3600;
		$long = $GPSLonDeg[0]/$GPSLonDeg[1]+
			($GPSLonMin[0]/$GPSLonMin[1])/60+
			($GPSLonSec[0]/$GPSLonSec[1])/3600;
		// $exif['GPS']['GPSLatitudeRef'] SHIROTA: N=+; S=-
		// $exif['GPS']['GPSLongitudeRef'] DOLGOTA: E=+; W=-
		if($exif['GPS']['GPSLatitudeRef'] == 'S'){$lat=$lat*(-1);}
		if($exif['GPS']['GPSLongitudeRef'] == 'W'){$long=$long*(-1);}

		return array('latitude' => $lat, 'longitude' => $long);
	  }
   }
}
?>