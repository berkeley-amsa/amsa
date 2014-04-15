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
jimport('joomla.application.component.controller');

$l['cp']	= 'COM_PHOCAGALLERY_CONTROL_PANEL';
$l['i']		= 'COM_PHOCAGALLERY_IMAGES';
$l['c']		= 'COM_PHOCAGALLERY_CATEGORIES';
$l['t']		= 'COM_PHOCAGALLERY_THEMES';
$l['cr']	= 'COM_PHOCAGALLERY_CATEGORY_RATING';
$l['ir']	= 'COM_PHOCAGALLERY_IMAGE_RATING';
$l['cc']	= 'COM_PHOCAGALLERY_CATEGORY_COMMENTS';
$l['ic']	= 'COM_PHOCAGALLERY_IMAGE_COMMENTS';
$l['u']		= 'COM_PHOCAGALLERY_USERS';
$l['fb']	= 'COM_PHOCAGALLERY_FB';
$l['in']	= 'COM_PHOCAGALLERY_INFO';

// Submenu view
$view	= JRequest::getVar( 'view', '', '', 'string', JREQUEST_ALLOWRAW );
if ($view == '' || $view == 'phocagallerycp') {
	JSubMenuHelper::addEntry(JText::_($l['cp']), 'index.php?option=com_phocagallery');
	JSubMenuHelper::addEntry(JText::_($l['i']), 'index.php?option=com_phocagallery&view=phocagalleryimgs');
	JSubMenuHelper::addEntry(JText::_($l['c']), 'index.php?option=com_phocagallery&view=phocagallerycs' );
	JSubMenuHelper::addEntry(JText::_($l['t']), 'index.php?option=com_phocagallery&view=phocagalleryt');
	JSubMenuHelper::addEntry(JText::_($l['cr']), 'index.php?option=com_phocagallery&view=phocagalleryra');
	JSubMenuHelper::addEntry(JText::_($l['ir']), 'index.php?option=com_phocagallery&view=phocagalleryraimg');
	JSubMenuHelper::addEntry(JText::_($l['cc']), 'index.php?option=com_phocagallery&view=phocagallerycos');
	JSubMenuHelper::addEntry(JText::_($l['ic']), 'index.php?option=com_phocagallery&view=phocagallerycoimgs');
	JSubMenuHelper::addEntry(JText::_($l['u']), 'index.php?option=com_phocagallery&view=phocagalleryusers');
	JSubMenuHelper::addEntry(JText::_($l['fb']), 'index.php?option=com_phocagallery&view=phocagalleryfbs');
	JSubMenuHelper::addEntry(JText::_($l['in']), 'index.php?option=com_phocagallery&view=phocagalleryin' );
}

if ($view == 'phocagalleryimgs') {
	JSubMenuHelper::addEntry(JText::_($l['cp']), 'index.php?option=com_phocagallery');
	JSubMenuHelper::addEntry(JText::_($l['i']), 'index.php?option=com_phocagallery&view=phocagalleryimgs', true);
	JSubMenuHelper::addEntry(JText::_($l['c']), 'index.php?option=com_phocagallery&view=phocagallerycs' );
	JSubMenuHelper::addEntry(JText::_($l['t']), 'index.php?option=com_phocagallery&view=phocagalleryt');
	JSubMenuHelper::addEntry(JText::_($l['cr']), 'index.php?option=com_phocagallery&view=phocagalleryra');
	JSubMenuHelper::addEntry(JText::_($l['ir']), 'index.php?option=com_phocagallery&view=phocagalleryraimg');
	JSubMenuHelper::addEntry(JText::_($l['cc']), 'index.php?option=com_phocagallery&view=phocagallerycos');
	JSubMenuHelper::addEntry(JText::_($l['ic']), 'index.php?option=com_phocagallery&view=phocagallerycoimgs');
	JSubMenuHelper::addEntry(JText::_($l['u']), 'index.php?option=com_phocagallery&view=phocagalleryusers');
	JSubMenuHelper::addEntry(JText::_($l['fb']), 'index.php?option=com_phocagallery&view=phocagalleryfbs');
	JSubMenuHelper::addEntry(JText::_($l['in']), 'index.php?option=com_phocagallery&view=phocagalleryin' );
}

if ($view == 'phocagallerycs') {
	JSubMenuHelper::addEntry(JText::_($l['cp']), 'index.php?option=com_phocagallery');
	JSubMenuHelper::addEntry(JText::_($l['i']), 'index.php?option=com_phocagallery&view=phocagalleryimgs');
	JSubMenuHelper::addEntry(JText::_($l['c']), 'index.php?option=com_phocagallery&view=phocagallerycs', true );
	JSubMenuHelper::addEntry(JText::_($l['t']), 'index.php?option=com_phocagallery&view=phocagalleryt');
	JSubMenuHelper::addEntry(JText::_($l['cr']), 'index.php?option=com_phocagallery&view=phocagalleryra');
	JSubMenuHelper::addEntry(JText::_($l['ir']), 'index.php?option=com_phocagallery&view=phocagalleryraimg');
	JSubMenuHelper::addEntry(JText::_($l['cc']), 'index.php?option=com_phocagallery&view=phocagallerycos');
	JSubMenuHelper::addEntry(JText::_($l['ic']), 'index.php?option=com_phocagallery&view=phocagallerycoimgs');
	JSubMenuHelper::addEntry(JText::_($l['u']), 'index.php?option=com_phocagallery&view=phocagalleryusers');
	JSubMenuHelper::addEntry(JText::_($l['fb']), 'index.php?option=com_phocagallery&view=phocagalleryfbs');
	JSubMenuHelper::addEntry(JText::_($l['in']), 'index.php?option=com_phocagallery&view=phocagalleryin' );
}

if ($view == 'phocagalleryt') {
	JSubMenuHelper::addEntry(JText::_($l['cp']), 'index.php?option=com_phocagallery');
	JSubMenuHelper::addEntry(JText::_($l['i']), 'index.php?option=com_phocagallery&view=phocagalleryimgs');
	JSubMenuHelper::addEntry(JText::_($l['c']), 'index.php?option=com_phocagallery&view=phocagallerycs' );
	JSubMenuHelper::addEntry(JText::_($l['t']), 'index.php?option=com_phocagallery&view=phocagalleryt', true );
	JSubMenuHelper::addEntry(JText::_($l['cr']), 'index.php?option=com_phocagallery&view=phocagalleryra');
	JSubMenuHelper::addEntry(JText::_($l['ir']), 'index.php?option=com_phocagallery&view=phocagalleryraimg');
	JSubMenuHelper::addEntry(JText::_($l['cc']), 'index.php?option=com_phocagallery&view=phocagallerycos');
	JSubMenuHelper::addEntry(JText::_($l['ic']), 'index.php?option=com_phocagallery&view=phocagallerycoimgs');
	JSubMenuHelper::addEntry(JText::_($l['u']), 'index.php?option=com_phocagallery&view=phocagalleryusers');
	JSubMenuHelper::addEntry(JText::_($l['fb']), 'index.php?option=com_phocagallery&view=phocagalleryfbs');
	JSubMenuHelper::addEntry(JText::_($l['in']), 'index.php?option=com_phocagallery&view=phocagalleryin' );
}

if ($view == 'phocagalleryra') {
	JSubMenuHelper::addEntry(JText::_($l['cp']), 'index.php?option=com_phocagallery');
	JSubMenuHelper::addEntry(JText::_($l['i']), 'index.php?option=com_phocagallery&view=phocagalleryimgs');
	JSubMenuHelper::addEntry(JText::_($l['c']), 'index.php?option=com_phocagallery&view=phocagallerycs' );
	JSubMenuHelper::addEntry(JText::_($l['t']), 'index.php?option=com_phocagallery&view=phocagalleryt');
	JSubMenuHelper::addEntry(JText::_($l['cr']), 'index.php?option=com_phocagallery&view=phocagalleryra', true);
	JSubMenuHelper::addEntry(JText::_($l['ir']), 'index.php?option=com_phocagallery&view=phocagalleryraimg');
	JSubMenuHelper::addEntry(JText::_($l['cc']), 'index.php?option=com_phocagallery&view=phocagallerycos');
	JSubMenuHelper::addEntry(JText::_($l['ic']), 'index.php?option=com_phocagallery&view=phocagallerycoimgs');
	JSubMenuHelper::addEntry(JText::_($l['u']), 'index.php?option=com_phocagallery&view=phocagalleryusers');
	JSubMenuHelper::addEntry(JText::_($l['fb']), 'index.php?option=com_phocagallery&view=phocagalleryfbs');
	JSubMenuHelper::addEntry(JText::_($l['in']), 'index.php?option=com_phocagallery&view=phocagalleryin' );
}

if ($view == 'phocagalleryraimg') {
	JSubMenuHelper::addEntry(JText::_($l['cp']), 'index.php?option=com_phocagallery');
	JSubMenuHelper::addEntry(JText::_($l['i']), 'index.php?option=com_phocagallery&view=phocagalleryimgs');
	JSubMenuHelper::addEntry(JText::_($l['c']), 'index.php?option=com_phocagallery&view=phocagallerycs' );
	JSubMenuHelper::addEntry(JText::_($l['t']), 'index.php?option=com_phocagallery&view=phocagalleryt');
	JSubMenuHelper::addEntry(JText::_($l['cr']), 'index.php?option=com_phocagallery&view=phocagalleryra');
	JSubMenuHelper::addEntry(JText::_($l['ir']), 'index.php?option=com_phocagallery&view=phocagalleryraimg', true);
	JSubMenuHelper::addEntry(JText::_($l['cc']), 'index.php?option=com_phocagallery&view=phocagallerycos');
	JSubMenuHelper::addEntry(JText::_($l['ic']), 'index.php?option=com_phocagallery&view=phocagallerycoimgs');
	JSubMenuHelper::addEntry(JText::_($l['u']), 'index.php?option=com_phocagallery&view=phocagalleryusers');
	JSubMenuHelper::addEntry(JText::_($l['fb']), 'index.php?option=com_phocagallery&view=phocagalleryfbs');
	JSubMenuHelper::addEntry(JText::_($l['in']), 'index.php?option=com_phocagallery&view=phocagalleryin' );
}

if ($view == 'phocagallerycos') {
	JSubMenuHelper::addEntry(JText::_($l['cp']), 'index.php?option=com_phocagallery');
	JSubMenuHelper::addEntry(JText::_($l['i']), 'index.php?option=com_phocagallery&view=phocagalleryimgs');
	JSubMenuHelper::addEntry(JText::_($l['c']), 'index.php?option=com_phocagallery&view=phocagallerycs' );
	JSubMenuHelper::addEntry(JText::_($l['t']), 'index.php?option=com_phocagallery&view=phocagalleryt' );
	JSubMenuHelper::addEntry(JText::_($l['cr']), 'index.php?option=com_phocagallery&view=phocagalleryra');
	JSubMenuHelper::addEntry(JText::_($l['ir']), 'index.php?option=com_phocagallery&view=phocagalleryraimg');
	JSubMenuHelper::addEntry(JText::_($l['cc']), 'index.php?option=com_phocagallery&view=phocagallerycos', true);
	JSubMenuHelper::addEntry(JText::_($l['ic']), 'index.php?option=com_phocagallery&view=phocagallerycoimgs');
	JSubMenuHelper::addEntry(JText::_($l['u']), 'index.php?option=com_phocagallery&view=phocagalleryusers');
	JSubMenuHelper::addEntry(JText::_($l['fb']), 'index.php?option=com_phocagallery&view=phocagalleryfbs');
	JSubMenuHelper::addEntry(JText::_($l['in']), 'index.php?option=com_phocagallery&view=phocagalleryin' );
}

if ($view == 'phocagallerycoimgs') {
	JSubMenuHelper::addEntry(JText::_($l['cp']), 'index.php?option=com_phocagallery');
	JSubMenuHelper::addEntry(JText::_($l['i']), 'index.php?option=com_phocagallery&view=phocagalleryimgs');
	JSubMenuHelper::addEntry(JText::_($l['c']), 'index.php?option=com_phocagallery&view=phocagallerycs' );
	JSubMenuHelper::addEntry(JText::_($l['t']), 'index.php?option=com_phocagallery&view=phocagalleryt' );
	JSubMenuHelper::addEntry(JText::_($l['cr']), 'index.php?option=com_phocagallery&view=phocagalleryra');
	JSubMenuHelper::addEntry(JText::_($l['ir']), 'index.php?option=com_phocagallery&view=phocagalleryraimg');
	JSubMenuHelper::addEntry(JText::_($l['cc']), 'index.php?option=com_phocagallery&view=phocagallerycos');
	JSubMenuHelper::addEntry(JText::_($l['ic']), 'index.php?option=com_phocagallery&view=phocagallerycoimgs', true);
	JSubMenuHelper::addEntry(JText::_($l['u']), 'index.php?option=com_phocagallery&view=phocagalleryusers');
	JSubMenuHelper::addEntry(JText::_($l['fb']), 'index.php?option=com_phocagallery&view=phocagalleryfbs');
	JSubMenuHelper::addEntry(JText::_($l['in']), 'index.php?option=com_phocagallery&view=phocagalleryin' );
}

if ($view == 'phocagalleryusers') {
	JSubMenuHelper::addEntry(JText::_($l['cp']), 'index.php?option=com_phocagallery');
	JSubMenuHelper::addEntry(JText::_($l['i']), 'index.php?option=com_phocagallery&view=phocagalleryimgs');
	JSubMenuHelper::addEntry(JText::_($l['c']), 'index.php?option=com_phocagallery&view=phocagallerycs' );
	JSubMenuHelper::addEntry(JText::_($l['t']), 'index.php?option=com_phocagallery&view=phocagalleryt' );
	JSubMenuHelper::addEntry(JText::_($l['cr']), 'index.php?option=com_phocagallery&view=phocagalleryra');
	JSubMenuHelper::addEntry(JText::_($l['ir']), 'index.php?option=com_phocagallery&view=phocagalleryraimg');
	JSubMenuHelper::addEntry(JText::_($l['cc']), 'index.php?option=com_phocagallery&view=phocagallerycos');
	JSubMenuHelper::addEntry(JText::_($l['ic']), 'index.php?option=com_phocagallery&view=phocagallerycoimgs');
	JSubMenuHelper::addEntry(JText::_($l['u']), 'index.php?option=com_phocagallery&view=phocagalleryusers', true);
	JSubMenuHelper::addEntry(JText::_($l['fb']), 'index.php?option=com_phocagallery&view=phocagalleryfbs');
	JSubMenuHelper::addEntry(JText::_($l['in']), 'index.php?option=com_phocagallery&view=phocagalleryin' );
}

if ($view == 'phocagalleryfbs') {
	JSubMenuHelper::addEntry(JText::_($l['cp']), 'index.php?option=com_phocagallery');
	JSubMenuHelper::addEntry(JText::_($l['i']), 'index.php?option=com_phocagallery&view=phocagalleryimgs');
	JSubMenuHelper::addEntry(JText::_($l['c']), 'index.php?option=com_phocagallery&view=phocagallerycs' );
	JSubMenuHelper::addEntry(JText::_($l['t']), 'index.php?option=com_phocagallery&view=phocagalleryt' );
	JSubMenuHelper::addEntry(JText::_($l['cr']), 'index.php?option=com_phocagallery&view=phocagalleryra');
	JSubMenuHelper::addEntry(JText::_($l['ir']), 'index.php?option=com_phocagallery&view=phocagalleryraimg');
	JSubMenuHelper::addEntry(JText::_($l['cc']), 'index.php?option=com_phocagallery&view=phocagallerycos');
	JSubMenuHelper::addEntry(JText::_($l['ic']), 'index.php?option=com_phocagallery&view=phocagallerycoimgs');
	JSubMenuHelper::addEntry(JText::_($l['u']), 'index.php?option=com_phocagallery&view=phocagalleryusers');
	JSubMenuHelper::addEntry(JText::_($l['fb']), 'index.php?option=com_phocagallery&view=phocagalleryfbs', true);
	JSubMenuHelper::addEntry(JText::_($l['in']), 'index.php?option=com_phocagallery&view=phocagalleryin' );
}

if ($view == 'phocagalleryin') {
	JSubMenuHelper::addEntry(JText::_($l['cp']), 'index.php?option=com_phocagallery');
	JSubMenuHelper::addEntry(JText::_($l['i']), 'index.php?option=com_phocagallery&view=phocagalleryimgs');
	JSubMenuHelper::addEntry(JText::_($l['c']), 'index.php?option=com_phocagallery&view=phocagallerycs' );
	JSubMenuHelper::addEntry(JText::_($l['t']), 'index.php?option=com_phocagallery&view=phocagalleryt' );
	JSubMenuHelper::addEntry(JText::_($l['cr']), 'index.php?option=com_phocagallery&view=phocagalleryra');
	JSubMenuHelper::addEntry(JText::_($l['ir']), 'index.php?option=com_phocagallery&view=phocagalleryraimg');
	JSubMenuHelper::addEntry(JText::_($l['cc']), 'index.php?option=com_phocagallery&view=phocagallerycos');
	JSubMenuHelper::addEntry(JText::_($l['ic']), 'index.php?option=com_phocagallery&view=phocagallerycoimgs');
	JSubMenuHelper::addEntry(JText::_($l['u']), 'index.php?option=com_phocagallery&view=phocagalleryusers');
	JSubMenuHelper::addEntry(JText::_($l['fb']), 'index.php?option=com_phocagallery&view=phocagalleryfbs');
	JSubMenuHelper::addEntry(JText::_($l['in']), 'index.php?option=com_phocagallery&view=phocagalleryin',true );
}

class PhocaGalleryCpController extends JController
{
	function display() {
		parent::display();
	}
}
?>