<?php
/**
 # mod_jvslideshow - JV Slideshow
 # @version		1.5.x
 # ------------------------------------------------------------------------
 # author    Open Source Code Solutions Co
 # copyright Copyright (C) 2011 joomlavi.com. All Rights Reserved.
 # @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL or later.
 # Websites: http://www.joomlavi.com
 # Technical Support:  http://www.joomlavi.com/my-tickets.html
-------------------------------------------------------------------------*/
defined('_JEXEC') or die('Restricted access');

require_once (dirname(__FILE__).DS.'helper.php');

$jvslideshow_imagespath         = $params->get('jvslideshow_imagespath', 'modules/mod_jvslideshow/assets/data/');
$jvslideshow_images             = ($params->get('jvslideshow_images')) != '' ? preg_split("/\n\r/", $params->get('jvslideshow_images')) : array();
$jvslideshow_titles             = ($params->get('jvslideshow_titles')) != '' ? preg_split("/\n\r/", $params->get('jvslideshow_titles')) : array();
$jvslideshow_description        = ($params->get('jvslideshow_descriptions')) != '' ? preg_split("/\n\r/", $params->get('jvslideshow_descriptions')) : array();
$jvslideshow_links              = ($params->get('jvslideshow_links')) != '' ? preg_split("/\n\r/", $params->get('jvslideshow_links')) : array();
$jvslideshow_source             = (int) $params->get('jvslideshow_source', 1);

$jvslideshow_source             = (int) $params->get('jvslideshow_source', 1);
$jvslideshow_display            = $params->get('jvslideshow_display', 0);
$jvslideshow_noofitems          = $params->get('jvslideshow_noofitems', 10);
$jvslideshow_target             = $params->get('jvslideshow_target', '_blank');
$jvslideshow_bannerid           = intval($params->get('jvslideshow_bannerid', 0));
$jvslideshow_bulleticon         = $params->get('jvslideshow_bulleticon', 0);
$jvslideshow_width              = $params->get('jvslideshow_width', 0);
$jvslideshow_height             = $params->get('jvslideshow_height', 0);
$jvslideshow_delay              = $params->get('jvslideshow_delay', 5000);
$jvslideshow_duration           = $params->get('jvslideshow_duration', 750);
$jvslideshow_transition         = $params->get('jvslideshow_transition', 'linear');
$jvslideshow_ease               = $params->get('jvslideshow_ease', 'easeIn');
$jvslideshow_forceeffect        = $params->get('jvslideshow_forceeffect', 1);
$jvslideshow_fxusing            = $params->get('jvslideshow_fxusing', 'swap');
$jvslideshow_fxswap             = $params->get('jvslideshow_fxswap', 'fade');
$jvslideshow_fxhslider 		    = $params->get('jvslideshow_fxhorizontalslider', 'fade');
$jvslideshow_fxvslider  	    = $params->get('jvslideshow_fxverticalslider', 'fade');
$jvslideshow_fxbslider  	    = $params->get('jvslideshow_fxboxslider', 'fade');
$jvslideshow_boxcols		    = $params->get('jvslideshow_boxcols', 8);
$jvslideshow_boxrows		    = $params->get('jvslideshow_boxrows', 4);
$jvslideshow_slices			    = $params->get('jvslideshow_slices', 20);
$jvslideshow_captionsopacity    = $params->get('jvslideshow_captionsopacity', 0.7);
$jvslideshow_captions           = $params->get('jvslideshow_captions', 1);
$jvslideshow_autohidecaptions   = $params->get('jvslideshow_autohidecaptions', 1);
$jvslideshow_arrows             = $params->get('jvslideshow_arrows', 1);
$jvslideshow_autohidearrows     = $params->get('jvslideshow_autohidearrows', 1);
$jvslideshow_firstarrow         = $params->get('jvslideshow_firstarrow', 1);
$jvslideshow_prevarrow          = $params->get('jvslideshow_prevarrow', 1);
$jvslideshow_pausearrow         = $params->get('jvslideshow_pausearrow', 1);
$jvslideshow_nextarrow          = $params->get('jvslideshow_nextarrow', 1);
$jvslideshow_lastarrow          = $params->get('jvslideshow_lastarrow', 1);
$jvslideshow_controls           = $params->get('jvslideshow_controls', 1);
$jvslideshow_autohidecontrols   = $params->get('jvslideshow_autohidecontrols', 1);
$jvslideshow_keyboard           = $params->get('jvslideshow_keyboard', 1);
$jvslideshow_autoplay           = $params->get('jvslideshow_autoplay', 1);
$jvslideshow_thumbnail          = $params->get('jvslideshow_thumbnail', 1);
$jvslideshow_thumbnailwidth     = $params->get('jvslideshow_thumbnailwidth', 50);
$jvslideshow_thumbnailheight    = $params->get('jvslideshow_thumbnailheight', 50);
$jvslideshow_mode               = $params->get('jvslideshow_thumbnails_mode', 'adaptiveresize');
$items = array();

if(!ModJVSlideShowHelper::endsWith($jvslideshow_imagespath, '/')){
	$jvslideshow_imagespath .= '/';
}

switch ($jvslideshow_source){
    case 0:
        $items = ModJVSlideShowHelper::getImagesFolder($jvslideshow_imagespath, $jvslideshow_titles, $jvslideshow_description, $jvslideshow_links);
    break;
        
    case 1:
        $items = ModJVSlideShowHelper::getImageLists($jvslideshow_images, $jvslideshow_titles, $jvslideshow_description, $jvslideshow_links, $jvslideshow_imagespath);
    break;
    
    default:
        $items = ModJVSlideShowHelper::getBanners($jvslideshow_bannerid);
    break;
}

$images_count = count($items);
$thumbnails   = array();

if($jvslideshow_thumbnail == 1){
	$thumbnails = ModJVSlideShowHelper::createThumbnails($items, $jvslideshow_mode, $jvslideshow_thumbnailwidth, $jvslideshow_thumbnailheight);
}
elseif($jvslideshow_thumbnail == 2){
	$thumbnails = ModJVSlideShowHelper::getThumbnails($items);
}

$items = json_encode($items);

$document = JFactory::getDocument();

$document->addStyleSheet(JURI::root() . 'modules/mod_jvslideshow/assets/css/jvslideshow.css');
if($jvslideshow_fxusing == 'hslider'){
	$document->addScript(JURI::root() . 'modules/mod_jvslideshow/assets/js/jvslideshow.hslider.js');
	$fxvars = $jvslideshow_fxhslider;
}
elseif($jvslideshow_fxusing == 'vslider'){	
	$document->addScript(JURI::root() . 'modules/mod_jvslideshow/assets/js/jvslideshow.vslider.js');
	$fxvars = $jvslideshow_fxvslider;
}
elseif($jvslideshow_fxusing == 'bslider'){	
	$document->addScript(JURI::root() . 'modules/mod_jvslideshow/assets/js/jvslideshow.bslider.js');
	$fxvars = $jvslideshow_fxbslider;
}
else{	
	$document->addScript(JURI::root() . 'modules/mod_jvslideshow/assets/js/jvslideshow.js');
	$fxvars = $jvslideshow_fxswap;
}
$jvslideshow = "//<![CDATA[
window.addEvent('domready', function(){
	new JVSlides.{$jvslideshow_fxusing}($('jv-slideshow-{$module->id}'), $items, {
		captions: $jvslideshow_captions,
		autoHideCaptions: $jvslideshow_autohidecaptions,
		arrows: $jvslideshow_arrows,
		autoHideArrows: $jvslideshow_autohidearrows,
		controls: $jvslideshow_controls,
		autoHideControls: $jvslideshow_autohidecontrols,
		keyboard: $jvslideshow_keyboard,
		autoPlay: $jvslideshow_autoplay,
		width: $jvslideshow_width,
		height: $jvslideshow_height,
		captionsOpacity: $jvslideshow_captionsopacity,
		display: $jvslideshow_display,
		delay: $jvslideshow_delay,
		duration: $jvslideshow_duration,
		boxCols: $jvslideshow_boxcols,
		boxRows: $jvslideshow_boxrows,
		slices: $jvslideshow_slices,
		transition: Fx.Transitions.$jvslideshow_transition" . ($jvslideshow_transition != 'linear' ? '.' . $jvslideshow_ease : '') . ",
		forceEffect: $jvslideshow_forceeffect,		
		fx: '$fxvars',		
		linkTarget: '$jvslideshow_target'
	});
});
//]]>";
$document->addScriptDeclaration($jvslideshow);
require(JModuleHelper::getLayoutPath('mod_jvslideshow'));
?>