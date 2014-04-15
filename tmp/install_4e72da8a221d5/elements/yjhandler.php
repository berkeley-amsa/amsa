<?php
/**
 * @package		Youjoomla Extend Elements
 * @author		Youjoomla LLC
 * @website     Youjoomla.com 
 * @copyright	Copyright (c) 2007 - 2010 Youjoomla LLC.
 * @license   PHP files are GNU/GPL V2. CSS / JS / IMAGES are Copyrighted Commercial
 */

// Check to ensure this file is within the rest of the framework
defined('JPATH_BASE') or die();
JHTML::_('behavior.modal');
/**
 * Renders a spacer element
 *
 * @package 	Joomla.Framework
 * @subpackage		Parameter
 * @since		1.5
 */

class JFormFieldYjHandler extends JFormField
{
	/**
	* Element name
	*
	* @access	protected
	* @var		string
	*/
	
	var	$_name = 'YjHandler';

public function getInput(){
$document =& JFactory::getDocument();
		$document->addCustomTag('
		<style type="text/css">
		.yjspacer_holder{
			background:#fff;
			padding:5px;
			display:block;
			width:400px;
			text-align:center;
			overflow:hidden;
			margin:0 auto 10px auto;
			border:1px solid #DDDDDD;
			clear:both;
		}
		.yjspacer{
			padding:5px;
			background:#DEDEDE;
			border:1px solid #DDDDDD;
			text-shadow:1px 1px #fff;
			font-size:12px;
			color:green;
		}
		a.modal{
			margin:15px;
			font-size:13px;
		}
		#menu-pane input,#menu-pane option,#menu-pane selected{
			height:20px;
			line-height:20px;
			font-size:12px;
			padding:0 0 0 5px;
		}
		#menu-pane .inputbox{
			height:22px;
			line-height:20px;
			font-size:12px;
			}
		#menu-pane input,#menu-pane option{
			margin:0 5px 0 0;
		}
		#menu-pane .text_area{
			font-size:12px;
		}
		#menu-pane .button2-left{
			margin-top:3px;
		}
		#tidi_0 span{
			display:block;
		}
		</style>
		
		');

		// Output
		  jimport('joomla.filesystem.file');
		  $mainframe = &JFactory::getApplication();
		  $e_folder = basename(dirname(dirname(__FILE__)));

		  $destpath = JPATH_ROOT.DS."images".DS."yj_piecemaker"; 
		  JFolder::create($destpath);
		  $empty="";
 		  JFile::write($destpath.DS."index.html",$empty);
		  
		  
		 $filesrc		 		= JPATH_ROOT.DS."modules".DS."mod_yj_piecemaker".DS."images".DS;
		 $filename1 			= 'piecemaker1.png';
		 $filename2 			= 'piecemaker2.png';
		 $filefull_1			= $filesrc.$filename1;
		 $filefull_2			= $filesrc.$filename2;
		 
		if (!is_readable($filesrc) || !is_writable($filesrc)) {
	 		 JError::raiseNotice('200', 'CANOT MOVE FILES. We have tried to move few images from '.$filesrc.' folder for your default setup but folder is not readable or writable. Please fix the folder permissions. Thank you! ');
		}
		
		if (!JFile::exists($destpath.DS.$filename1) || !JFile::exists($destpath.DS.$filename2)){
  	    	JFile::copy($filefull_1, $destpath.DS.$filename1 );
			JFile::copy($filefull_2, $destpath.DS.$filename2 );
			$app =& JFactory::getApplication();
			$app->enqueueMessage( JText::_( 'Your default setup is done! First 2 slides have their default values loaded.' ));
		}
	        $uri = JURI::base ();
			$uri = str_replace("/administrator/", "/modules/", $uri);
			$helpfile = $uri.$e_folder."/images/pdoc.pdf";
		return'<a href="'.$helpfile.'" class="modal" rel="{handler: \'iframe\', size: {x: 800, y: 700}}">Piecemaker2 Script Documentation</a><br /><strong> For detail module info please hover the module parameter title.</strong> ' ;
	}
		public function getLabel() {
		return false;
	}
}
