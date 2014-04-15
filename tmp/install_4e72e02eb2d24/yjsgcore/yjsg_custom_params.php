<?php
/*======================================================================*\
|| #################################################################### ||
|| # Package - Joomla Template based on YJSimpleGrid Framework          ||
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
defined( '_JEXEC' ) or die( 'Restricted index access' );
//$custom  					     = $this->params->get("custom");
// site bg stripe switch
$yjsg1_grid_mods = false;
for( $i=1; $i<=5; $i++ ){
	$mod_user = $this->countModules('top'.$i);
	if( $mod_user){
		$yjsg1_grid_mods = true;
		break;
	}
}
if ($yjsg1_grid_mods == false){
	$bg_stripe='bg1';
}else{
	$bg_stripe='bg2';
}
// grid 6 7 collapse
$yjsg67_grid_mods = false;
for( $i=16; $i<=25; $i++ ){
	$mod67_user = $this->countModules('user'.$i);
	if( $mod67_user){
		$yjsg67_grid_mods = true;
		break;
	}
}
?>