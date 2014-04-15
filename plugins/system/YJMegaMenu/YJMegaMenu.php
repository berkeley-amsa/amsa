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
/**
 * YJ system plugin
 *
 * @package		YJSG Framework V 1.0.10
 * @subpackage	System 
 */ 

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.event.plugin' );
/**
 * YJ system plugin
 *
 * @package		Joomla
 * @subpackage	System 
 */ 
class  plgSystemYJMegaMenu extends JPlugin
{
	function onContentPrepareForm($form, $data)
	{
		if ($form->getName()=='com_menus.item')
		{
			JForm::addFormPath(JPATH_PLUGINS.DS.'system'.DS.'YJMegaMenu'.DS.'YJMegaMenu'.DS.'params');
			JForm::addFieldPath(JPATH_PLUGINS.DS.'system'.DS.'YJMegaMenu'.DS.'YJMegaMenu'.DS.'element');			
			$form->loadFile('yj_mega_menu_params', false);
		}
	}

}
?>