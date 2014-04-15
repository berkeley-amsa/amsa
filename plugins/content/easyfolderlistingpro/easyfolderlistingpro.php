<?php
/**
* @version		1.5 (J16)
* @author		Michael A. Gilkes (jaido7@yahoo.com)
* @copyright	Michael Albert Gilkes
* @license		GNU/GPLv2
*/

/*

Easy Folder Listing Pro Plugin for Joomla! 1.6+
Copyright (C) 2010-2011  Michael Albert Gilkes

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

*/

// no direct access
defined('_JEXEC') or die('Restricted access');

//imports
jimport('joomla.filesystem.file');
jimport('joomla.environment.uri');
jimport('joomla.plugin.plugin'); //needed for MVC implementation

class plgContentEasyFolderListingPro extends JPlugin
{
	/**
	 * Constants
	 */
	//define the plugin string
	const PLUGIN_STRING = 'easyfolderlistingpro';
	//define location of files
	const SEGMENT = 'plugins/content/easyfolderlistingpro/';
	//define a text for no files to list
	const NOFILES = "no_files";
	//define a text for download to list
	const DOWNLOAD = "down_load";
	//define a text for cancel to list
	const CANCEL = "can_cel";
	
	/**
	 * Protected Properties
	 *
	 */
	protected $_params_array;
	
	
	/**
	 * Constructor
	 *
	 * @access      public
	 * @param       object  $subject The object to observe
	 * @param       array   $config  An array that holds the plugin configuration
	 * @since       1.6
	 */
	public function __construct(&$subject, $config)
	{
		// Note:
		// $config should contain the params
		parent::__construct($subject, $config);
	}
	
	/**
	 * @param	string	The context of the content being passed to the plugin.
	 * @param	object	The article object.  Note $article->text is also available
	 * @param	object	The article params
	 * @param	int		The 'page' number
	 *
	 * @return	void
	 * @since	1.6
	 */
	public function onContentPrepare($context, &$article, &$params, $page = 0)
	{  
		// simple performance check to determine whether bot should process further
		if (JString::strpos($article->text, self::PLUGIN_STRING) !== false)
		{
			$this->processInstancesFound($article, $params, $page);
		}
	}
	
	/**
	* Plugin that displays a list of the contents of a folder
	*/
	protected function processInstancesFound(&$row, &$params, $page = 0)
	{
		// expression to search for
		$regex = '/{'.self::PLUGIN_STRING.'\s*.*?}/i';
	
		// check whether plugin has been unpublished
		if (!$this->params->get('enabled', 1))
		{
			//if unpublished, remove and end processing
			$row->text = preg_replace($regex, '', $row->text);
			return true; //bye
		}
	
		// find all instances of plugin and put in $matches
		preg_match_all($regex, $row->text, $matches);
	
		// Number of plugins
		$count = count($matches[0]);
	
		// plugin only processes if there are any instances of the plugin in the text
		if ($count)
		{
			//get the hosts name
			$host = JURI::root();
			
			//get default parameters
			$defaults_array = array();
			$defaults_array['folder'] = $this->params->get('eflpro_folder');
			$defaults_array['level'] = $this->params->get('eflpro_maxlevel');
			$defaults_array['subfolders'] = $this->params->get('eflpro_show_subfolders');
			$defaults_array['charsetutf8'] = $this->params->get('eflpro_charsetutf8');
			$defaults_array['metautf8'] = $this->params->get('eflpro_metautf8');
			$defaults_array['jquery'] = $this->params->get('eflpro_jquery');
			$defaults_array['noconflict'] = $this->params->get('eflpro_noconflict');
			$defaults_array['collapse'] = $this->params->get('eflpro_collapse');
			$defaults_array['transition'] = $this->params->get('eflpro_transition');
			$defaults_array['easing'] = $this->params->get('eflpro_easing');
			$defaults_array['duration'] = $this->params->get('eflpro_duration');
			$defaults_array['icons'] = $this->params->get('eflpro_icons');
			$defaults_array['extensions'] = $this->params->get('eflpro_extensions');
			$defaults_array['filetext'] = $this->params->get('eflpro_filetext');
			$defaults_array['sizetext'] = $this->params->get('eflpro_sizetext');
			$defaults_array['datetext'] = $this->params->get('eflpro_datetext');
			$defaults_array['size'] = $this->params->get('eflpro_size');
			$defaults_array['date'] = $this->params->get('eflpro_date');
			$defaults_array['time'] = $this->params->get('eflpro_time');
			$defaults_array['dateformat'] = $this->params->get('eflpro_dateformat');
			$defaults_array['timeformat'] = $this->params->get('eflpro_timeformat');
			$defaults_array['linktofiles'] = $this->params->get('eflpro_linktofiles');
			$defaults_array['target'] = $this->params->get('eflpro_target');
			$defaults_array['download'] = $this->params->get('eflpro_download');
			$defaults_array['showempty'] = $this->params->get('eflpro_showempty');
			$defaults_array['empty'] = $this->params->get('eflpro_empty');
			$defaults_array['showfilter'] = $this->params->get('eflpro_showfilter');
			$defaults_array['filterlabel'] = $this->params->get('eflpro_filterlabel');
			$defaults_array['filtererror'] = $this->params->get('eflpro_filtererror');
			$defaults_array['filterwait'] = $this->params->get('eflpro_filterwait');
			$defaults_array['forbidden'] = $this->params->get('eflpro_forbidden');
			$defaults_array['whitelist'] = $this->params->get('eflpro_whitelist');
			$defaults_array['exfiles'] = $this->params->get('eflpro_exfiles');
			$defaults_array['exfolders'] = $this->params->get('eflpro_exfolders');
			$defaults_array['method'] = $this->params->get('eflpro_method');
			$defaults_array['liststyle'] = $this->params->get('eflpro_liststyle');
			$defaults_array['ratio'] = $this->params->get('eflpro_ratio');
			$defaults_array['sortcolumn'] = $this->params->get('eflpro_sortcolumn');
			$defaults_array['sortdirection'] = $this->params->get('eflpro_sortdirection');
			$defaults_array['oddcolor'] = $this->params->get('eflpro_oddcolor');
			$defaults_array['evencolor'] = $this->params->get('eflpro_evencolor');
			$defaults_array['headcolor'] = $this->params->get('eflpro_headcolor');
			$defaults_array['subcolor'] = $this->params->get('eflpro_subcolor');
			$defaults_array['bordercolor'] = $this->params->get('eflpro_bordercolor');
			
			//add the script references
			//**********************************			
			//add the links to the external files into the head of the webpage (note the 'administrator' in the path, which is not nescessary if you are in the frontend)
			$document =& JFactory::getDocument();
			
			//reset the page charset via php header to UTF-8
			if ($defaults_array['charsetutf8'] == true)
			{
				$document->setCharset('utf-8');
			}
			
			//add meta tag to set charset to UTF-8
			if ($defaults_array['metautf8'] == true)
			{
				$document->setMetaData('content-type', 'text/html; charset=utf-8', true);
			}
			
			//handle adding jQuery
			if ($defaults_array['jquery'] == "bundled")
			{
				$document->addScript($host.self::SEGMENT.'scripts/jquery-1.6.1.min.js');
			}
			else if ($defaults_array['jquery'] == "latest")
			{
				$document->addScript('http://code.jquery.com/jquery-latest.min.js');
			}
			else //else none, do not load
			{
				//do not load jQuery!
			}
			$document->addScript($host.self::SEGMENT.'scripts/eflpFunctions.js');
			$document->addStyleSheet($host.self::SEGMENT.'css/styles.css');
			
			//pattern to detect the li, tbody and td id strings
			$pattern = '/(eflpro_table[0-9]+sub[0-9]+|eflpro_list[0-9]+sub[0-9]+)trigger/i';
			
			//initialize javascript variable
			$javascript = "";
			
			if ($defaults_array['noconflict'] == true)
			{
				//put everything in a noConflict
				$javascript .= "jQuery.noConflict()(function(){\n\n";
			}
			
			//process each entry
			//***********************************
			for ($i=0; $i < $count; $i++)
			{
				//get the text of the local params
				$localParams = str_replace(self::PLUGIN_STRING, '', $matches[0][$i]);
				$localParams = str_replace('{', '', $localParams);
				$localParams = str_replace('}', '', $localParams);
				$localParams = trim($localParams);
				
				//separate the local params
				$local_params = $this->processLocalParams($localParams);
				
				//get the customized parameters
				$this->_params_array = $this->customizeParameters($defaults_array, $local_params);
	
				//get the formatted html
				$listingHTML = $this->processHTML($i);
				
				//extract a copy of all the li or td id's, we're ignoring the triggers
				$total = preg_match_all($pattern, $listingHTML, $variables);
				$javascript.= $this->processJavascript($i, $variables);
				
				//replace the instances of the plugin text on the page with the formatted html
				$row->text = str_replace($matches[0][$i], $listingHTML, $row->text);
			}
			
			if ($defaults_array['noconflict'] == true)
			{
				//close the noConflict
				$javascript.= "\n});\n";
			}
			
			//add the javascript to the head of the html document
			$document->addScriptDeclaration($javascript);
		}
	}
	
	protected function processLocalParams($localParams)
	{
		/***************************************
		List of valid parameters:
		folder
		level
		subfolders
		collapse
		transition
		easing
		duration
		icons
		extensions
		filetext
		sizetext
		datetext
		size
		date
		time
		dateformat
		timeformat
		linktofiles
		target
		download
		showempty
		empty
		showfilter
		filterlabel
		filtererror
		filterwait
		forbidden
		whitelist
		exfiles
		exfolders
		method
		liststyle
		ratio
		sortcolumn
		sortdirection
		oddcolor
		evencolor
		headcolor
		subcolor
		bordercolor
		***************************************/
		$params_array = array();
		
		$fields = explode("|", $localParams);
	
		foreach($fields as $value)
		{
			$value=trim($value);
			$values = explode("=",$value, 2);
			$values[0] = trim(strtolower($values[0]));
			$values=preg_replace("/^'/", '', $values);
			$values=preg_replace("/'$/", '', $values);
			//$values=preg_replace("/^&#0{0,2}39;/",'',$values);
			//$values=preg_replace("/&#0{0,2}39;$/",'',$values);
			
			//do we have a valid pair?
			if (count($values) > 1)
			{
				//trim the value of whitespaces
				$values[1] = trim($values[1]);
			}
			
			//check for valid parameters
			switch ($values[0])
			{
				case 'folder':
				case 'level':
				case 'subfolders':
				case 'collapse':
				case 'transition':
				case 'easing':
				case 'duration':
				case 'showempty':
				case 'empty':
				case 'icons':
				case 'extensions':
				case 'filetext':
				case 'sizetext':
				case 'datetext':
				case 'size':
				case 'date':
				case 'time':
				case 'dateformat':
				case 'timeformat':
				case 'linktofiles':
				case 'target':
				case 'download':
				case 'showfilter':
				case 'filterlabel':
				case 'filtererror':
				case 'filterwait':
				case 'forbidden':
				case 'whitelist':
				case 'exfiles':
				case 'exfolders':
				case 'method':
				case 'liststyle':
				case 'ratio':
				case 'sortcolumn':
				case 'sortdirection':
				case 'oddcolor':
				case 'evencolor':
				case 'headcolor':
				case 'subcolor':
				case 'bordercolor':
					//add the valid parameter
					$params_array[$values[0]] = $values[1];
					break;
				default:
					//invalid parameter
					break;
			}
		}
		
		return $params_array;
	}
	
	protected function customizeParameters($default_params, $local_params)
	{
		$custom = array();
		
		//get the user's custom parameters
		$custom['folder'] = isset($local_params['folder']) ? $local_params['folder'] : $default_params['folder'];
		$custom['level'] = isset($local_params['level']) ? $local_params['level'] : $default_params['level'];
		$custom['subfolders'] = isset($local_params['subfolders']) ? $local_params['subfolders'] : $default_params['subfolders'];
		$custom['collapse'] = isset($local_params['collapse']) ? $local_params['collapse'] : $default_params['collapse'];
		$custom['transition'] = isset($local_params['transition']) ? $local_params['transition'] : $default_params['transition'];
		$custom['easing'] = isset($local_params['easing']) ? $local_params['easing'] : $default_params['easing'];
		$custom['duration'] = isset($local_params['duration']) ? $local_params['duration'] : $default_params['duration'];
		$custom['showempty'] = isset($local_params['showempty']) ? $local_params['showempty'] : $default_params['showempty'];
		$custom['empty'] = isset($local_params['empty']) ? $local_params['empty'] : $default_params['empty'];
		$custom['icons'] = isset($local_params['icons']) ? $local_params['icons'] : $default_params['icons'];
		$custom['extensions'] = isset($local_params['extensions']) ? $local_params['extensions'] : $default_params['extensions'];
		$custom['filetext'] = isset($local_params['filetext']) ? $local_params['filetext'] : $default_params['filetext'];
		$custom['sizetext'] = isset($local_params['sizetext']) ? $local_params['sizetext'] : $default_params['sizetext'];
		$custom['datetext'] = isset($local_params['datetext']) ? $local_params['datetext'] : $default_params['datetext'];
		$custom['size'] = isset($local_params['size']) ? $local_params['size'] : $default_params['size'];
		$custom['date'] = isset($local_params['date']) ? $local_params['date'] : $default_params['date'];
		$custom['time'] = isset($local_params['time']) ? $local_params['time'] : $default_params['time'];
		$custom['dateformat'] = isset($local_params['dateformat']) ? $local_params['dateformat'] : $default_params['dateformat'];
		$custom['timeformat'] = isset($local_params['timeformat']) ? $local_params['timeformat'] : $default_params['timeformat'];
		$custom['linktofiles'] = isset($local_params['linktofiles']) ? $local_params['linktofiles'] : $default_params['linktofiles'];
		$custom['target'] = isset($local_params['target']) ? $local_params['target'] : $default_params['target'];
		$custom['download'] = isset($local_params['download']) ? $local_params['download'] : $default_params['download'];
		$custom['showfilter'] = isset($local_params['showfilter']) ? $local_params['showfilter'] : $default_params['showfilter'];
		$custom['filterlabel'] = isset($local_params['filterlabel']) ? $local_params['filterlabel'] : $default_params['filterlabel'];
		$custom['filtererror'] = isset($local_params['filtererror']) ? $local_params['filtererror'] : $default_params['filtererror'];
		$custom['filterwait'] = isset($local_params['filterwait']) ? $local_params['filterwait'] : $default_params['filterwait'];
		$custom['forbidden'] = isset($local_params['forbidden']) ? $local_params['forbidden'] : $default_params['forbidden'];
		$custom['whitelist'] = isset($local_params['whitelist']) ? $local_params['whitelist'] : $default_params['whitelist'];
		$custom['method'] = isset($local_params['method']) ? $local_params['method'] : $default_params['method'];
		$custom['liststyle'] = isset($local_params['liststyle']) ? $local_params['liststyle'] : $default_params['liststyle'];
		$custom['ratio'] = isset($local_params['ratio']) ? $local_params['ratio'] : $default_params['ratio'];
		$custom['sortcolumn'] = isset($local_params['sortcolumn']) ? $local_params['sortcolumn'] : $default_params['sortcolumn'];
		$custom['sortdirection'] = isset($local_params['sortdirection']) ? $local_params['sortdirection'] : $default_params['sortdirection'];
		$custom['oddcolor'] = isset($local_params['oddcolor']) ? $local_params['oddcolor'] : $default_params['oddcolor'];
		$custom['evencolor'] = isset($local_params['evencolor']) ? $local_params['evencolor'] : $default_params['evencolor'];
		$custom['headcolor'] = isset($local_params['headcolor']) ? $local_params['headcolor'] : $default_params['headcolor'];
		$custom['subcolor'] = isset($local_params['subcolor']) ? $local_params['subcolor'] : $default_params['subcolor'];
		$custom['bordercolor'] = isset($local_params['bordercolor']) ? $local_params['bordercolor'] : $default_params['bordercolor'];
		
		//reformat the string as an array of strings
		$temp1 = isset($local_params['exfiles']) ? $local_params['exfiles'] : $default_params['exfiles'];
		$custom['exfiles'] = explode(';', $temp1);
		$temp2 = isset($local_params['exfolders']) ? $local_params['exfolders'] : $default_params['exfolders'];
		$custom['exfolders'] = explode(';', $temp2);
		
		
		return $custom;
	}
	
	protected function processHTML($i)
	{
		$result = '';
		
		//format the display
		if ($this->_params_array['method'] == "table")
		{
			//use the html table
			$result = $this->formatTable($i);
		}
		else //use the unordered list
		{
			$result = $this->formatList($i);
		}
		
		return $result;
	}
	
	/****
	For every id variable in the generated html that relates to the contents
	of a subfolder, we will write mootools javascript to make it toggle when 
	the triggers are clicked.
	
	Sample Mootools Javascript:
	
	window.addEvent('domready', function() {
		var eflproFx0 = new Fx.Slide('eflpro_list0sub0', {
			duration: normal,
			transition: sine:in:out
		});
		
		//may have to put hide after render, since the heights get messed
		//up the lower we go in the tree.
		eflproFx0.hide();
		
		$('eflpro_list0sub0trigger').addEvent('click', function(){
			eflproFx0.toggle();
		});
	});
	
	
	Sample jQuery Javascript:
	
	//only 2 easing options: swing & linear
	//only 2 duration options: slow & fast
	jQuery(document).ready(function(){
		//hide from view at first
		jQuery('#eflpro_list0sub0').hide();
		
		//slide code
		jQuery('#eflpro_list0sub0trigger').click(function() {
			jQuery('#eflpro_list0sub0').slideToggle('slow', 'swing');
		});
		
		//OR
		
		//fade code
		jQuery('#eflpro_list0sub0trigger').click(function() {
			jQuery('#eflpro_list0sub0').fadeToggle('slow', 'swing');
		});
		
		//OR
		
		//slide+fade code
		jQuery('#eflpro_list0sub0trigger').click(function() {
			jQuery('#eflpro_list0sub0').toggle('slow', 'swing');
		});
	});
	
	*/
	function processJavascript($index, $variables)
	{
		$collapse = (($this->_params_array['collapse']==true) ? 'true': 'false');
		$js = '/* -- Start EFLPRO Javascript -- */'."\n\n";
		$js.= 'jQuery(document).ready(function(){'."\n";
		
		//variables[0] refers to triggers
		$triggers = 'var eflpro'.$index.'_triggers = new Array(';
		
		//variables[1] refers to subs
		$subs = 'var eflpro'.$index.'_subs = new Array(';
		
		//both 0 and 1 SHOULD contain the same number of children
		$total = count($variables[0]); 
		
		/****DEBUGGING****/
		//$js.= "\n\n\n\n/*".$total."\n\n".print_r($variables, true)."*/";
		/****  CODE   ****/
		
		for ($i = 0; $i < $total; $i++)
		{
			$triggers .= "'#".$variables[0][$i]."'";
			$subs .= "'#".$variables[1][$i]."'";
			
			if ($i < $total-1)
			{
				$triggers .= ", ";
				$subs .= ", ";
			}
		}
		$triggers .= ");\n";
		$subs .= ");\n";
		$js.= $triggers;
		$js.= $subs;
		$js.= "var eflpro".$index." = new eflpManager(".$index.", '".$this->_params_array['method']."', eflpro".$index."_triggers, eflpro".$index."_subs, ".$collapse.", '".$this->_params_array['transition']."', '".$this->_params_array['easing']."', '".$this->_params_array['duration']."', ".$this->_params_array['filterwait'].");\n";
		$js.= "eflpro".$index.".init();\n";
		if ($this->_params_array['showfilter'] == true)
		{
			$js.= "eflpro".$index.".setupFilter();\n";
			$js.= "eflpro".$index.".applyFilter();\n\n";
		}
		if ($this->_params_array['download'] == 'icon' ||
			$this->_params_array['download'] == 'link')
		{
			$js.= "jQuery('a.eflpro_download').click(function(e) {\n";
			$js.= "\te.preventDefault();\n";
			$js.= "\tvar href = jQuery(this).attr(\"href\");\n";
			$js.= "\teflpAjaxDownload(this, '".JURI::base().self::SEGMENT."')\n";
			$js.= "});\n";
		}
		$js.= '});'."\n\n";
		$js.= '/*  -- End EFLPRO Javascript --  */'."\n";
		
		return $js;
	}
	
	protected function formatTable($index)
	{
		//default number of columns we expect
		$columns = 1;
		
		//number of pixels for one level space
		$pixel_space = 27;
		
		$prefix = 'eflpro_table';
		$htm = '';
		
		//specify the path
		$imgpath = JURI::base().self::SEGMENT.'icons';
		
		/* add the filter */
		if ($this->_params_array['showfilter'] == true)
		{
			$htm.= '<div id="eflpro_filterdiv'.$index.'" class="eflpro_filterdiv">'."\n";
			$htm.= "\t".'<span class="eflpro_filterlabel">'.$this->_params_array['filterlabel'].'</span>'."\n";
			$htm.= "\t".'<input type="text" id="eflpro_textbox'.$index.'" class="eflpro_textboxes">'."\n";
			$htm.= "\t".'<img id="eflpro_cancelimg'.$index.'" class="eflpro_cancelimgs" src="'.$imgpath.'/'.'cancel.png" alt="Cancel Filter" />'."\n";
			$htm.= "\t".'<span id="eflpro_error'.$index.'" class="eflpro_notfound">'.$this->_params_array['filtererror'].'</span>'."\n";
			$htm.= '</div>'."\n";
		}
		
		/*  start of the table  */
		$htm.= '<table id="'.$prefix.$index.'" ';
		$htm.= 'style="';
		$htm.= 'background-color:'.$this->_params_array['bordercolor'].'; ';
		$htm.= '" ';
		$htm.= 'class="eflpro_tables" ';
		$htm.= '>'."\n";
		
		/*  table head section  */
		$html = "\t".'<thead>'."\n";
		$html.= "\t\t".'<tr ';
		$html.= 'style="';
		$html.= 'background-color:'.$this->_params_array['headcolor'].';">'."\n";
		$html.= "\t\t\t".'<th style="">'.$this->_params_array['filetext'].'</th>'."\n";
		if ($this->_params_array['size'] == true)
		{
			$columns++;
			$html.= "\t\t\t".'<th style="">'.$this->_params_array['sizetext'].'</th>'."\n";
		}
		if ($this->_params_array['date'] == true)
		{
			$columns++;
			$html.= "\t\t\t".'<th style="">'.$this->_params_array['datetext'].'</th>'."\n";
		}
		$html.= "\t\t".'</tr>'."\n";
		$html.= "\t".'</thead>'."\n";
		
		/*  body sections  */
		if ($this->_params_array['subfolders'] == true)
		{
			//get a list of the subfolders, if specified
			$subfolders = $this->customListFolderTree($this->_params_array['folder'], $this->_params_array['level']);
			$total_subfolders = count($subfolders);
			
			for ($f = 0; $f < $total_subfolders; $f++)
			{
				//initialize the trigger and responder id's
				$triggerid = $prefix.$index.'sub'.$f.'trigger';
				$responderid = $prefix.$index.'sub'.$f;
				
				//tbody for subfolder name
				$html.= "\t".'<tbody>'."\n";
				//table row for the subfolder name
				$html.= "\t\t".'<tr ';
				$html.= 'style="';
				$html.= 'background-color:'.$this->_params_array['subcolor'].';">'."\n";
				//table cell for the subfolder name
				$html.= "\t\t\t".'<td id="'.$triggerid.'" style="'; //removed padding:1px; and placed it in styles.css
				if ($subfolders[$f]['level'] > 1)
				{
					//calculate the space needed
					$space = (($subfolders[$f]['level'] - 1) * $pixel_space);
					$html.= ' padding-left:'.$space.'px;';
				}
				$html.= '" ';
				$html.= 'colspan="'.$columns.'">';
				//format the folder name and display it
				$html.= $this->formatTableFolderRow($subfolders[$f]);
				//close the tags
				$html.= '</td>'."\n";
				$html.= "\t\t".'</tr>'."\n";
				$html.= "\t".'</tbody>'."\n";
				
				//get the list of files in this subfolder and display them
				$html.= "\t".'<tbody id="'.$responderid.'">'."\n";
				$filelist = $this->sortedFileList($subfolders[$f]['relname']);
				$html.= $this->formatTableFileRows($filelist, $subfolders[$f]['relname'], $subfolders[$f]['level']);
				$html.= "\t".'</tbody>'."\n";
			}
		}
		
		//get the files in the root folder and display them
		$filelist = $this->sortedFileList($this->_params_array['folder']);
		$html.= $this->formatTableFileRows($filelist, $this->_params_array['folder'], 0);
		
		//add the colums width ratio details first
		if ($columns > 1)
		{
			$ratio = $this->_params_array['ratio'];
			$interval = 100/(($ratio-1)+$columns);
			$htm .= "\t".'<colgroup>'."\n";
			for ($j = 0; $j < $columns; $j++)
			{
				$w = ($j == 0 ? $interval*$ratio : $interval);
				$htm .= "\t\t".'<col width="'.$w.'%">'."\n";
			}
			$htm .= "\t".'</colgroup>'."\n";
		}
		
		//add the head and body of the table
		$htm .= $html;
		
		//clost the table tag
		$htm .= '</table>'."\n";
		
		return $htm;
	}
	
	protected function formatTableFolderRow($subfolder)
	{
		//specify the path
		$path = JURI::base().self::SEGMENT.'icons';
		
		$html = '';
		
		if ($this->_params_array['icons'] == true)
		{
			$html.= '<img src="'.$path.'/'.'folder_explore.png" alt="explore subfolder image" /> ';
		}
		$html.= $subfolder['name'];
		
		return $html;
	}
	
	protected function formatTableFileRows($rows, $subfolder, $level)
	{
		//initialize the $html variable
		$html = '';
		
		$pixel_space = 27;	
		$space = ($level * $pixel_space);
		
		//get the total number of rows of files we need to list
		$total = count($rows);
		
		for ($i = 0; $i < $total; $i++)
		{
			//set the colour for the odd row
			$color = $this->_params_array['oddcolor'];
			//is the row even
			if (($i+1)%2 == 0)
			{
				//set the colour for the even row
				$color = $this->_params_array['evencolor'];
			}
			
			//start the table row
			$html .= "\t".'<tr style="background-color:'.$color.';">'."\n";
			//filename column
			$html .= "\t\t".'<td style="'; //removed padding:1px; and placed it in styles.css
			//add the necessary space
			if ($space > 0)
			{
				$html.= 'padding-left:'.$space.'px;';
			}
			$html.= '">';
			//show icons?
			if ($this->_params_array['icons'] == true)
			{
				$html .= $this->attachIcon($rows[$i]['ext'], JURI::base().self::SEGMENT.'icons');
			}
			//link it?
			if ($this->_params_array['linktofiles'] == true && 
				$this->_params_array['download'] != 'link')
			{
				$url = JURI::base().$subfolder.'/'.$rows[$i]['name'].'.'.$rows[$i]['ext'];
				$html .= '<a href="'.$url.'" target="'.$this->_params_array['target'].'">';
			}
			elseif ($this->_params_array['download'] == 'link')
			{
				$url = JURI::base().$subfolder.'/'.$rows[$i]['name'].'.'.$rows[$i]['ext'];
				$html .= '<a class="eflpro_download" href="'.$url.'" target="'.$this->_params_array['target'].'">';
			}
			//show the file's name
			$html .= $rows[$i]['name'];
			//show extension?
			if ($this->_params_array['extensions'] == true)
			{
				$html .= '.'.$rows[$i]['ext'];
			}
			//close the tag, if we are linking it
			if ($this->_params_array['linktofiles'] == true || 
				$this->_params_array['download'] == 'link')
			{
				$html .= '</a>';
			}
			//force download with icon
			if ($this->_params_array['download'] == 'icon')
			{
				$url = JURI::base().$subfolder.'/'.$rows[$i]['name'].'.'.$rows[$i]['ext'];
				$html .= ' <a class="eflpro_download" href="'.$url.'" target="'.$this->_params_array['target'].'">';
				$html .= $this->attachIcon(self::DOWNLOAD, JURI::base().self::SEGMENT.'icons');
				$html .= '</a>';
			}
			//close filename column
			$html .= '</td>'."\n";
			//show size?
			if ($this->_params_array['size'] == true)
			{
				$html .= "\t\t".'<td style="">';
				$html .= $rows[$i]['size'];
				$html .= '</td>'."\n";
			}
			//show date?
			if ($this->_params_array['date'] == true)
			{
				$html .= "\t\t".'<td style="">';
				$html .= $rows[$i]['date'];
				$html .= '</td>'."\n";
			}
			//end the tag
			$html .= "\t".'</tr>'."\n";
		}
		
		if ($total == 0) //there are no files to list
		{
			if ($this->_params_array['showempty'] == true)
			{
				$columns = 1;
				if ($this->_params_array['size'] == true) { $columns++; }
				if ($this->_params_array['date'] == true) { $columns++; }
				//start the table row
				$html .= "\t".'<tr style="background-color:'.$this->_params_array['oddcolor'].';">'."\n";
				//filename column
				$html .= "\t\t".'<td colspan="'.$columns.'" style="';
				//add the necessary space
				if ($space > 0)
				{
					$html.= 'padding-left:'.$space.'px;';
				}
				$html.= '">';
				if ($this->_params_array['icons'] == true)
				{
					$html .= $this->attachIcon(self::NOFILES, JURI::base().self::SEGMENT.'icons');
				}
				$html.= '<span class="eflpro_empty">'.$this->_params_array['empty'].'</span></td>'."\n";
				$html .= "\t".'</tr>'."\n";
			}
		}
		
		return $html;
	}
	
	protected function formatList($i)
	{
		$prefix = "eflpro_list";
		$html = '';
		
		//specify the path
		$imgpath = JURI::base().self::SEGMENT.'icons';
		
		/* add the filter */
		if ($this->_params_array['showfilter'] == true)
		{
			$html.= '<div id="eflpro_filterdiv'.$i.'" class="eflpro_filterdiv">'."\n";
			$html.= "\t".'<span class="eflpro_filterlabel">'.$this->_params_array['filterlabel'].'</span>'."\n";
			$html.= "\t".'<input type="text" id="eflpro_textbox'.$i.'" class="eflpro_textboxes">'."\n";
			$html.= "\t".'<img id="eflpro_cancelimg'.$i.'" class="eflpro_cancelimgs" src="'.$imgpath.'/'.'cancel.png" alt="Cancel Filter" />'."\n";
			$html.= "\t".'<span id="eflpro_error'.$i.'" class="eflpro_notfound">'.$this->_params_array['filtererror'].'</span>'."\n";
			$html.= '</div>'."\n";
		}
		
		$html.= '<ul id="'.$prefix.$i.'" style="list-style:'.$this->_params_array['liststyle'].';" ';
		$html.= 'class="eflpro_lists" >'."\n";
		
		if ($this->_params_array['subfolders'] == true)
		{
			$index = 0;
			$html.= $this->formatListHandleSubfolders($this->_params_array['folder'], $prefix.$i."sub", $index);
		}
		$html.= $this->formatListContents($this->_params_array['folder']);
		$html.= '</ul>'."\n";
		
		return $html;
	}
	
	//recursive function
	protected function formatListHandleSubfolders($path, $prefix, &$index)
	{
		$html = '';
		
		$folders = JFolder::folders($path, '.', false, false, $this->_params_array['exfolders']);
		
		foreach ($folders as $dir)
		{
			$html.= "\t".'<li id="'.$prefix.$index.'trigger">';
			$html.= $this->formatListSubfolderName($dir, $this->_params_array['icons']);
			$html.= '</li>'."\n";
			
			$fullName = JPath::clean($path . DS . $dir);
			$relname = str_replace(JPATH_ROOT, '', $fullName);
			
			$html.= "\t".'<li id="'.$prefix.$index.'">';
			$html.= "\n\t\t".'<ul style="list-style-type:'.$this->_params_array['liststyle'].';" ';
			$html.= 'class="eflpro_lists" >'."\n";
			$index++;
			$html.= $this->formatListHandleSubfolders($relname, $prefix, $index);
			$html.= $this->formatListContents($relname);
			$html.= "\n\t\t".'</ul>'."\n";
			$html.= "\t".'</li>'."\n";
		}
		
		return $html;
	}
	
	protected function formatListSubfolderName($foldername)
	{
		//specify the path
		$path = JURI::base().self::SEGMENT.'icons';
		
		$html = '';
		
		if ($this->_params_array['icons'] == true)
		{
			$html.= '<img src="'.$path.'/'.'folder_go.png" alt="reveal subfolder image" /> ';
		}
		$html.= $foldername;
		
		return $html;
	}
	
	protected function formatListContents($relpath)
	{
		//get the files in the specified folder
		$rows = $this->sortedFileList($relpath);
		
		$total = count($rows); //get the total number of files
		$html = '';
		for ($i = 0; $i < $total; $i++)
		{
			$html .= "\t".'<li>';
			//show icons?
			if ($this->_params_array['icons'] == true)
			{
				$html .= $this->attachIcon($rows[$i]['ext'], JURI::base().self::SEGMENT.'icons');
			}
			//link it?
			if ($this->_params_array['linktofiles'] == true && 
				$this->_params_array['download'] != 'link')
			{
				$url = JURI::base().$relpath.'/'.$rows[$i]['name'].'.'.$rows[$i]['ext'];
				$html .= '<a href="'.$url.'" target="'.$this->_params_array['target'].'">';
			}
			elseif ($this->_params_array['download'] == 'link')
			{
				$url = JURI::base().$relpath.'/'.$rows[$i]['name'].'.'.$rows[$i]['ext'];
				$html .= '<a class="eflpro_download" href="'.$url.'" target="'.$this->_params_array['target'].'">';
			}
			//show the file's name
			$html .= $rows[$i]['name'];
			//show extension?
			if ($this->_params_array['extensions'] == true)
			{
				$html .= '.'.$rows[$i]['ext'];
			}
			//close the tag, if we are linking it
			if ($this->_params_array['linktofiles'] == true || 
				$this->_params_array['download'] == 'link')
			{
				$html .= '</a>';
			}
			//force download with icon
			if ($this->_params_array['download'] == 'icon')
			{
				$url = JURI::base().$relpath.'/'.$rows[$i]['name'].'.'.$rows[$i]['ext'];
				$html .= ' <a class="eflpro_download" href="'.$url.'" target="'.$this->_params_array['target'].'">';
				$html .= $this->attachIcon(self::DOWNLOAD, JURI::base().self::SEGMENT.'icons');
				$html .= '</a>';
			}
			//show size?
			if ($this->_params_array['size'] == true)
			{
				$html .= ' <div class="eflpro_filesize">'.$rows[$i]['size'].'</div>';
			}
			//show date?
			if ($this->_params_array['date'] == true)
			{
				$html .= ' <div class="eflpro_datetime">'.$rows[$i]['date'].'</div>';
			}
			//end the tag
			$html .= '</li>'."\n";
		}
		
		if ($total == 0) //there are no files to list
		{
			if ($this->_params_array['showempty'] == true)
			{
				$html.= "\t".'<li>';
				if ($this->_params_array['icons'] == true)
				{
					$html .= $this->attachIcon(self::NOFILES, JURI::base().self::SEGMENT.'icons');
				}
				$html.= '<span class="eflpro_empty">'.$this->_params_array['empty'].'</span></li>'."\n";
			}
		}
		
		return $html;
	}
	
	
	/************************
	* Comparison Functions
	*/
	static function compareName($x, $y)
	{
		if ($x['name'] == $y['name'])
		{
			return 0;
		}
		elseif ($x['name'] < $y['name'])
		{
			return -1;
		}
		else
		{
			return 1;
		}
	}
	
	static function compareSize($x, $y)
	{
		if ($x['bytes'] == $y['bytes'])
		{
			return 0;
		}
		elseif ($x['bytes'] < $y['bytes'])
		{
			return -1;
		}
		else
		{
			return 1;
		}
	}
	
	static function compareDate($x, $y)
	{
		if ($x['date'] == $y['date'])
		{
			return 0;
		}
		elseif ($x['date'] < $y['date'])
		{
			return -1;
		}
		else
		{
			return 1;
		}
	}
	/************************/
	
	
	/************************
	* Utility Functions
	*/
	protected function sortedFileList($folder)
	{
		$list = array();
		
		//get a list of all the files in the folder
		//$original = JFolder::files($folder);
		$original = JFolder::files($folder, '.', false, false, $this->_params_array['exfiles']);
		
		//create the final list that contains: name, ext, size, date
		$total = count($original);
		$index = 0;
		for($i = 0; $i < $total; $i++)
		{
			//check to see if the file type is forbidden
			if (stripos($this->_params_array['forbidden'], JFile::getExt($original[$i])) === false)
			{
				if ($this->_params_array['whitelist'] == '*' || stripos($this->_params_array['whitelist'], JFile::getExt($original[$i])) !== false)
				{
					//remove the file extension and the dot from the filename
					$list[$index]['name'] = substr($original[$i], 0, -1*(1+strlen(JFile::getExt($original[$i]))));
					//add the extension
					$list[$index]['ext'] = JFile::getExt($original[$i]);
					//get the stats for the file
					$filestats = stat($folder.'/'.$original[$i]);
					$list[$index]['size'] = plgContentEasyFolderListingPro::sizeToText($filestats['size']);
					$list[$index]['bytes'] = $filestats['size'];
					$list[$index]['date'] = $this->formatDate($filestats['mtime']);
					$index++;
				}
			}
		}
		
		//sort the array in ascending order
		if ($this->_params_array['sortcolumn'] == "name")
		{
			usort($list, array("plgContentEasyFolderListingPro", "compareName"));
		}
		elseif ($this->_params_array['sortcolumn'] == "size")
		{
			usort($list, array("plgContentEasyFolderListingPro", "compareSize"));
		}
		elseif ($this->_params_array['sortcolumn'] == "date")
		{
			usort($list, array("plgContentEasyFolderListingPro", "compareDate"));
		}
		
		//sort in descending order
		if ($this->_params_array['sortdirection'] == "desc")
		{
			$list = array_reverse($list);
		}
		
		//reset the array pointer
		reset($list);
		
		return $list;
	}
	
	//Custom function based on JFolder::listFolderTree
	protected function customListFolderTree($path, $maxLevel = 3, $level = 0, $parent = 0, $filter='.')
	{
		$dirs = array ();
		if ($level == 0)
		{
			$GLOBALS['_JFolder_folder_tree_index'] = 0;
		}
	
		if ($level < $maxLevel) 
		{
			//$folders = JFolder::folders($path, $filter);
			$folders = JFolder::folders($path, $filter, false, false, $this->_params_array['exfolders']);
			// first path, index foldernames
			foreach ($folders as $name)
			{
				$id = ++$GLOBALS['_JFolder_folder_tree_index'];
				$fullName = JPath::clean($path . DS . $name);
				$dirs[] = array(
					'id' => $id,
					'level' => $level+1,
					'parent' => $parent,
					'name' => $name,
					'fullname' => $fullName,
					'relname' => str_replace(JPATH_ROOT, '', $fullName)
				);
	
				$dirs2 = $this->customListFolderTree($fullName, $maxLevel, $level + 1, $id, $filter);
				$dirs = array_merge($dirs, $dirs2);
			}
		}
		return $dirs;
	}
	
	
	public static function sizeToText($size)
	{
		$text = "";
		$kb = 1024;
		$mb = $kb * $kb;
		$gb = $mb * $kb;
		
		if ($size >= $gb)
		{
			$size = round($size / $gb, 2);
			$text = $size." GB";
		}
		elseif ($size >= $mb)
		{
			$size = round($size / $mb, 2);
			$text = $size." MB";
		}
		elseif ($size >= $kb)
		{
			$size = round($size / $kb, 2);
			$text = $size." KB";
		}
		else
		{
			$text = $size." bytes";
		}
		return $text;
	}
	
	protected function formatDate($mtime)
	{
		$datetime = date($this->_params_array['dateformat'], $mtime);
		
		if ($this->_params_array['time'] == true)
		{
			$datetime = date($this->_params_array['timeformat'], $mtime);
		}
		
		return $datetime;
	}
	
	protected function attachIcon($ext, $path)
	{
		$html = "";
		if ($ext == self::NOFILES)
		{
			$html .= '<img src="'.$path.'/'.'exclamation.png" alt="No files to list icon" />';
		}
		elseif ($ext == self::DOWNLOAD)
		{
			$html .= '<img src="'.$path.'/'.'disk.png" alt="Download Preceding File" />';
		}
		elseif ($ext == self::CANCEL)
		{
			$html .= '<img src="'.$path.'/'.'cancel.png" alt="Cancel Filter" />';
		}
		elseif (stripos("exe", $ext) !== false)
		{
			$html .= '<img src="'.$path.'/'.'application.png" alt="An executable file" />';
		}
		elseif (stripos("m", $ext) !== false)
		{
			$html .= '<img src="'.$path.'/'.'page_code.png" alt="A code file" />';
		}
		elseif (stripos("c", $ext) !== false)
		{
			$html .= '<img src="'.$path.'/'.'page_white_c.png" alt="A C file" />';
		}
		elseif (stripos("h", $ext) !== false)
		{
			$html .= '<img src="'.$path.'/'.'page_white_h.png" alt="An H file" />';
		}
		elseif (stripos("java,py", $ext) !== false)
		{
			$html .= '<img src="'.$path.'/'.'page_white_code.png" alt="A code file" />';
		}
		elseif (stripos("wmv,mp4,mov,divx,avi,flv,mkv", $ext) !== false)
		{
			$html .= '<img src="'.$path.'/'.'film.png" alt="A video file" />';
		}
		elseif (stripos("htm,html", $ext) !== false)
		{
			$html .= '<img src="'.$path.'/'.'html.png" alt="An html file" />';
		}
		elseif (stripos("bmp", $ext) !== false)
		{
			$html .= '<img src="'.$path.'/'.'image.png" alt="An image file" />';
		}
		elseif (stripos("jpg,jpeg,png,gif,tif", $ext) !== false)
		{
			$html .= '<img src="'.$path.'/'.'picture.png" alt="An image file" />';
		}
		elseif (stripos("mp3,wma", $ext) !== false)
		{
			$html .= '<img src="'.$path.'/'.'music.png" alt="A music file" />';
		}
		elseif (stripos("pdf,ps", $ext) !== false)
		{
			$html .= '<img src="'.$path.'/'.'page_white_acrobat.png" alt="An Adobe Acrobat file" />';
		}
		elseif (stripos("zip", $ext) !== false)
		{
			$html .= '<img src="'.$path.'/'.'page_white_compressed.png" alt="A compressed ZIP file" />';
		}
		elseif (stripos("cpp", $ext) !== false)
		{
			$html .= '<img src="'.$path.'/'.'page_white_cplusplus.png" alt="A C plus plus file" />';
		}
		elseif (stripos("cs", $ext) !== false)
		{
			$html .= '<img src="'.$path.'/'.'page_white_csharp.png" alt="A C sharp file" />';
		}
		elseif (stripos("swf", $ext) !== false)
		{
			$html .= '<img src="'.$path.'/'.'page_white_flash.png" alt="A flash SWF file" />';
		}
		elseif (stripos("xls", $ext) !== false)
		{
			$html .= '<img src="'.$path.'/'.'page_white_excel.png" alt="A Microsoft Excel file" />';
		}
		elseif (stripos("xlsx", $ext) !== false)
		{
			$html .= '<img src="'.$path.'/'.'page_excel.png" alt="A Microsoft Excel file" />';
		}
		elseif (stripos("php", $ext) !== false)
		{
			$html .= '<img src="'.$path.'/'.'page_white_php.png" alt="A PHP file" />';
		}
		elseif (stripos("ppt,pptx", $ext) !== false)
		{
			$html .= '<img src="'.$path.'/'.'page_white_powerpoint.png" alt="A Microsoft Powerpoint file" />';
		}
		elseif (stripos("txt", $ext) !== false)
		{
			$html .= '<img src="'.$path.'/'.'page_white_text.png" alt="A Text file" />';
		}
		elseif (stripos("sln", $ext) !== false)
		{
			$html .= '<img src="'.$path.'/'.'page_white_visualstudio.png" alt="A Visual Studio Solution file" />';
		}
		elseif (stripos("doc", $ext) !== false)
		{
			$html .= '<img src="'.$path.'/'.'page_white_word.png" alt="A Microsoft Word file" />';
		}
		elseif (stripos("docx", $ext) !== false)
		{
			$html .= '<img src="'.$path.'/'.'page_word.png" alt="A Microsoft Word file" />';
		}
		elseif (stripos("tar.gz,tgz,tbz,tb2,tar.bz2,taz,tar.Z,tlz,tar.lz,txz,tar.xz,7z,rar", $ext) !== false)
		{
			$html .= '<img src="'.$path.'/'.'page_white_zip.png" alt="A Compressed file" />';
		}
		else
		{
			$html .= '<img src="'.$path.'/'.'page_white.png" alt="A file of unknown type" />';
		}
		
		//add a space to the end
		$html .= ' ';
		
		return $html;
	}
	
} //end of class