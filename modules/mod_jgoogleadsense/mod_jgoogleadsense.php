<?php

/*

* @package Joomla Google AdSense Module for Joomla 1.6 from tridentsystemsgroup.com

* @copyright Copyright (C) 2011 tridentsystemsgroup.com All rights reserved.

* @license http://www.gnu.org/copyleft/gpl.html GNU General Public License version 2 or later;

* Joomla! is free software.

* This extension is made for Joomla! 1.6;

** THE BEST CMS : JOOMLA! **

*/

defined( '_JEXEC' ) or die( 'Restricted access' ) ;

	$j_adcss = $params->get('j_ad_css');

	$j_clientID = $params->get('j_ad_client');

	$j_altadurl = $params->get('j_alternate_ad_url');

	$j_adtype = $params->get('j_ad_type');

	$j_adchannel = $params->get('j_ad_channel');

	$j_uifeatures = $params->get('j_ad_uifeatures');

	$j_ad_format = $params->get('j_ad_format');

	$j_format = explode("-", $j_ad_format);

	$j_ad_width = explode("x", $j_format[0]);

	$j_ad_height = explode("_", $j_ad_width[1]);

	$j_border = $params->get('j_color_border');

	$j_bg = $params->get('j_color_bg');

	$j_link = $params->get('j_color_link');

	$j_text = $params->get('j_color_text');

	$j_url = $params->get('j_color_url');

	$j_ip_block = 1;

	if ($_SERVER["REMOTE_ADDR"] == $params->get('j_ip_block1')) $j_ip_block = 0;

	if ($_SERVER["REMOTE_ADDR"] == $params->get('j_ip_block2')) $j_ip_block = 0;

	if ($_SERVER["REMOTE_ADDR"] == $params->get('j_ip_block3')) $j_ip_block = 0;

	if ($_SERVER["REMOTE_ADDR"] == $params->get('j_ip_block4')) $j_ip_block = 0;

	if ($_SERVER["REMOTE_ADDR"] == $params->get('j_ip_block5')) $j_ip_block = 0;

	if ($_SERVER["REMOTE_ADDR"] == $params->get('j_ip_block6')) $j_ip_block = 0;

	if ($_SERVER["REMOTE_ADDR"] == $params->get('j_ip_block7')) $j_ip_block = 0;

	if ($_SERVER["REMOTE_ADDR"] == $params->get('j_ip_block8')) $j_ip_block = 0;

	if ($j_ip_block) {
	
	require_once( dirname(__FILE__).DS."elements".DS."helper.php") ;

	$j_id = modJGAHelper::correctId ($j_clientID) ;

	if ($j_adcss) { 

	echo "<div style=\"" . $j_adcss . "\">\r\n" ; 

	}
	
	if ((is_numeric($j_id)) && (strlen ($j_id) == '16')) {
	
	echo "<script type=\"text/javascript\"><!--\r\n" . "google_ad_client = \"ca-pub-" . $j_id . "\";\r\n" ;	

	if ($j_altadurl) { 

	echo "google_alternate_ad_url = \"" . $j_altadurl . "\";\r\n" ;

	}		

	echo "google_ad_width = " . $j_ad_width[0] . "; \r\n" 

	. "google_ad_height = " . $j_ad_height[0] . "; \r\n" 

	. "google_ad_format = \"" . $j_format[0] . "\"; \r\n" 

	. "google_ad_type = \"" . $j_adtype . "\"; \r\n" 

	. "google_ad_channel = \"" . $j_adchannel . "\"; \r\n" 

	. "google_color_border = \"" . $j_border . "\"; \r\n" 

	. "google_color_bg = \"" . $j_bg . "\"; \r\n" 

	. "google_color_link = \"" . $j_link . "\"; \r\n" 

	. "google_color_text = \"" . $j_text . "\"; \r\n" 

	. "google_color_url = \"" . $j_url . "\"; \r\n" 

	. "google_ui_features = \"rc:" . $j_uifeatures . "\"; \r\n" 

	. "//--> \r\n" 

	. "</script>\r\n"	

	. "<script type=\"text/javascript\" src=\"http://pagead2.googlesyndication.com/pagead/show_ads.js\">\r\n" 

	. "</script>\r\n" ;
	
	}
	else {
		
	echo '<div style="float: none; margin: 0px 0px 0px 0px;">' . "JGA Module : Invalid Pub-ID<br> \r\n" . "Must be like :<br><br>\r\n" . "ca-pub-1234567890123456<br>\r\n" . "or<br>\r\n" . "pub-1234567890123456<br>\r\n" . "or<br>\r\n" . "1234567890123456<br>\r\n" . "or<br><br>\r\n" . "Let it EMPTY for DEMO<br><br>\r\n" . '</div>';	
	
	}

	if ($j_adcss) { 

	echo '</div>' ; 

	}

	} 

	else {

	echo '<div style="float: none; margin: 0px 0px 0px 0px;">' . $params->get('ip_block_alt_code') . '</div>';

	}

?>