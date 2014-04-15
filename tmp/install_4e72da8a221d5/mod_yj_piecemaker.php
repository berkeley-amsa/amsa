<?php
/*======================================================================*\
|| #################################################################### ||
|| # Package - YJ Piecemaker                                            ||
|| # Copyright (C) 2010  Youjoomla LLC. All Rights Reserved.            ||
|| # license - PHP files are licensed under  GNU/GPL V2                 ||
|| # license - CSS  - JS - IMAGE files  are Copyrighted material        ||
|| # bound by Proprietary License of Youjoomla LLC                      ||
|| # for more information visit http://www.youjoomla.com/license.html   ||
|| # Redistribution and  modification of this software                  ||
|| # is bounded by its licenses                                         ||
|| # websites - http://www.youjoomla.com | http://www.yjsimplegrid.com  ||
|| #################################################################### ||
\*======================================================================*/

// no direct access
defined('_JEXEC') or die('Restricted access');
require_once (dirname(__FILE__).DS.'helper.php');
$module_template 		=  		$params->get('module_template','Default');
	$height 					= $params->get('height');
	$width		 				= $params->get('width');
	$MenuDistanceY		 		= $params->get('MenuDistanceY');
	$DropShadowDistance		 	= $params->get('DropShadowDistance');

	if ($DropShadowDistance	< 10 ) {
		$real_height		= $height + $MenuDistanceY + 20;
		$slide_div_height 	= $real_height;
	}else{
		$real_height		= $height + $MenuDistanceY + $DropShadowDistance +5;
		$slide_div_height 	= $real_height;
	}


$piecemaker_items = modYJPiecemakerHelper::getItems($params);
require(JModuleHelper::getLayoutPath('mod_yj_piecemaker',''.$module_template.'/default'));
?>