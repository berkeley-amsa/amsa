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
$document = &JFactory::getDocument();
require_once (JPATH_SITE.DS.'components'.DS.'com_content'.DS.'helpers'.DS.'route.php');

class modYJPiecemakerHelper
{
	function getItems(&$params){
		global $mainframe, $_MAMBOTS;
	$height 					= $params->get('height');
	$media_height 				= $params->get('media_height');
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
	
	
	
	jimport('joomla.filesystem.file');
	$lastedit 			= $params->get('edit_time');
	$file 				= JPATH_ROOT.DS."modules".DS."mod_yj_piecemaker".DS."xmlfiles/piecemaker".$lastedit.".xml"; // file path
	
	
	
	$document = JFactory::getDocument();
	$document->addStyleSheet(JURI::base().'modules/mod_yj_piecemaker/css/stylesheet.css');
	$document->addScript(JURI::base() . 'modules/mod_yj_piecemaker/script/swfobject.js'); 
	$document->addScriptDeclaration("		
      var flashvars = {};
      flashvars.cssSource = \"".JURI::base()."modules/mod_yj_piecemaker/css/piecemaker.css\";
      flashvars.xmlSource = \"".JURI::base()."modules/mod_yj_piecemaker/xmlfiles/piecemaker".$lastedit.".xml\";
		
      var params = {};
      params.play = \"true\";
      params.menu = \"false\";
      params.scale = \"showall\";
      params.wmode = \"transparent\";
      params.allowfullscreen = \"true\";
      params.allowscriptaccess = \"always\";
      params.allownetworking = \"all\";
	  
      swfobject.embedSWF('".JURI::base()."modules/mod_yj_piecemaker/script/piecemaker.swf', 'piecemaker', '".$width."', '".$real_height."', '10', null, flashvars,    
      params, null);
	");
	$order = $params->get('order');
	$show_order = range(0,19);
	if($order == 1){
		srand((float)microtime()*1000000);
  		shuffle($show_order); 
	}
	/**
	 * Slides info
	 */
	$slides = array();
  	foreach ($show_order as $i)
  	{
  		$image = $params->get('slide_media_'.($i+1));
  		if (empty($image) || $image==-1)  continue;		
  		
  		$slide = array(
			'type'	 		=> $params->get('slide_type_'.($i+1)),		   
  			'title' 		=> $params->get('slide_title_'.($i+1)),
  			'desc' 			=> htmlspecialchars($params->get('slide_desc_'.($i+1)), ENT_QUOTES, "UTF-8"),
			'link' 			=> $params->get('slide_link_'.($i+1)),
			'linkt'			=> $params->get('slide_linkt_'.($i+1)),
			'iprev'			=> $params->get('slide_iprev_'.($i+1)),
			'Pieces'		=> $params->get('slide_Pieces_'.($i+1)),
			'Time'			=> $params->get('slide_Time_'.($i+1)),
			'Transition'	=> $params->get('slide_Transition_'.($i+1)),
			'Delay'			=> $params->get('slide_Delay_'.($i+1)),
			'DepthOffset'	=> $params->get('slide_DepthOffset_'.($i+1)),
			'CubeDistance'	=> $params->get('slide_CubeDistance_'.($i+1)),
			'Vautoplay'		=> $params->get('slide_autoplay_'.($i+1)),
  			'image' 		=> $image
  		);
  		$slides[] = $slide;
  	} 
// xml data
		$writexml= "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
		$writexml.= "<Piecemaker>\n";
		$writexml.= "<Contents>\n";
		
		foreach ($slides as $slide){
			// if link 
			if($slide['linkt'] == 1) {
				$link_target="Target=\"_blank\"";
			}else{
				$link_target="";
			}
			// if video
			if ($slide['type'] =='Video'){
				if ($slide['Vautoplay'] =='1'){
					$autoplay ='true';
				}else{
					$autoplay ='false';
				}
				$videox_params =" Width=\"".$width."\" Height=\"".$height."\" Autoplay=\"".$autoplay."\"";
			}else{
				$videox_params ="";
			}
			$writexml.= "<".$slide['type']." Source=\"". JURI::base()."images/yj_piecemaker/".$slide['image']."\" Title=\"".$slide['title']."\"".$videox_params.">\n";
			if($slide['type']=='Flash' || $slide['type'] =='Video') {
				$writexml.="<Image Source=\"". JURI::base()."images/yj_piecemaker/".$slide['iprev']."\" />";
			}
			if($slide['type']=='Image'){
				if($slide['desc']) {
					$writexml.="<Text>".$slide['desc']."</Text>";
				}
				if($slide['link']) {
					$writexml.= "<Hyperlink URL=\"".$slide['link']."\" ".$link_target." />\n";
				}
			}
			$writexml.= "</".$slide['type'].">";
		}
		$writexml.= "</Contents>\n";
		$writexml.= "<Transitions>";
		foreach ($slides as $slide){
			$writexml.= "<Transition 
							Pieces=\"".$slide['Pieces']."\" 
							Time=\"".$slide['Time']."\" 
							Transition=\"".$slide['Transition']."\" 
							Delay=\"".$slide['Delay']."\" 
							DepthOffset=\"".$slide['DepthOffset']."\" 
							CubeDistance=\"".$slide['CubeDistance']."\">
						</Transition>
						";
		}
      	$writexml.="</Transitions>\n";
		
		/// Settings

		$writexml.= "
		<Settings 
		ImageWidth=\"".$width."\" 
		ImageHeight=\"".$height."\" 
		LoaderColor=\"0x".$params->get('LoaderColor','333333')."\" 
		InnerSideColor=\"0x".$params->get('InnerSideColor','222222')."\" 
		SideShadowAlpha=\"".$params->get('SideShadowAlpha','0.8')."\" 
		DropShadowAlpha=\"".$params->get('DropShadowAlpha','0.7')."\" 
		DropShadowDistance=\"".$DropShadowDistance."\" 
		DropShadowScale=\"".$params->get('DropShadowScale','0.95')."\" 
		DropShadowBlurX=\"".$params->get('DropShadowBlurX','40')."\" 
		DropShadowBlurY=\"".$params->get('DropShadowBlurY','4')."\" 
		MenuDistanceX=\"".$params->get('MenuDistanceX','20')."\" 
		MenuDistanceY=\"".$MenuDistanceY."\" 
		MenuColor1=\"0x".$params->get('MenuColor1','999999')."\" 
		MenuColor2=\"0x".$params->get('MenuColor2','333333')."\" 
		MenuColor3=\"0x".$params->get('MenuColor3','FFFFFF')."\" 
		ControlSize=\"".$params->get('ControlSize','100')."\" 
		ControlDistance=\"".$params->get('ControlDistance','20')."\" 
		ControlColor1=\"0x".$params->get('ControlColor1','222222')."\" 
		ControlColor2=\"0x".$params->get('ControlColor2','FFFFFF')."\" 
		ControlAlpha=\"".$params->get('ControlAlpha','0.8')."\" 
		ControlAlphaOver=\"".$params->get('ControlAlphaOver','0.95')."\" 
		ControlsX=\"".$params->get('ControlsX','450')."\" 
		ControlsY=\"".$params->get('ControlsY','280')."\" 
		ControlsAlign=\"".$params->get('ControlsAlign','center')."\" 
		TooltipHeight=\"".$params->get('TooltipHeight','30')."\" 
		TooltipColor=\"0x".$params->get('TooltipColor','222222')."\" 
		TooltipTextY=\"".$params->get('TooltipTextY','5')."\" 
		TooltipTextStyle=\"".$params->get('TooltipTextStyle','P-Italic')."\" 
		TooltipTextColor=\"0x".$params->get('TooltipTextColor','FFFFFF')."\" 
		TooltipMarginLeft=\"".$params->get('TooltipMarginLeft','5')."\" 
		TooltipMarginRight=\"".$params->get('TooltipMarginRight','7')."\" 
		TooltipTextSharpness=\"".$params->get('TooltipTextSharpness','50')."\" 
		TooltipTextThickness=\"".$params->get('TooltipTextThickness','-100')."\" 
		InfoWidth=\"".$params->get('InfoWidth','400')."\" 
		InfoBackground=\"0x".$params->get('InfoBackground','FFFFFF')."\" 
		InfoBackgroundAlpha=\"".$params->get('InfoBackgroundAlpha','0.95')."\" 
		InfoMargin=\"".$params->get('InfoMargin','15')."\" 
		InfoSharpness=\"".$params->get('InfoSharpness','0')."\" 
		InfoThickness=\"".$params->get('InfoThickness','0')."\" 
		Autoplay=\"".$params->get('Autoplay','5')."\" 
		FieldOfView=\"".$params->get('FieldOfView','45')."\">
		</Settings>\n";	
		$writexml.= "</Piecemaker>\n";
		
		
		// instead having multiple files , let us do some cleaning
		$destpath = JPATH_ROOT.DS."modules".DS."mod_yj_piecemaker".DS."xmlfiles";
		$empty="";
		
	 		
			if (is_readable($destpath) || is_writable($destpath)) {
				if (!JFile::exists($file)&& $order == 0){
					jimport( 'joomla.filesystem.folder' );
					JFolder::delete($destpath);
					JFolder::create($destpath);
					JFile::write($destpath.DS."index.html",$empty);
					JFile::write($file, $writexml); 
				}elseif($order == 1){
					JFile::write($file, $writexml);
				}
			}else{
				echo'<div id="xmlerror">'.JText::_( 'XMLERROR' ).'</div>';
			}
	}
}
?>