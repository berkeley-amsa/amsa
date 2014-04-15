/**
* @version		1.0
* @author		Michael A. Gilkes (jaido7@yahoo.com)
* @copyright	Michael Albert Gilkes
* @license		GNU/GPLv2
* @created		Wed May 18 2011 20:44:43 -0400
*/

/*

Easy Folder Listing Pro Plugin for Joomla! 1.6
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

/**
* These functions REQUIRE jQuery!
*/

/*
* + Check to see if jQuery was loaded!
*/
if (typeof jQuery == 'undefined')
{
	//jQuery is not loaded
	if (console.error)
	{
		console.error("EFLPRO Critical: jQuery is not loaded!");
	}
	else
	{
		alert("EFLPRO Critical: jQuery is not loaded!");
	}
}
else
{
	//jQuery is loaded!
	
	/*===================================================================*
	 * Function Declaration: eflpAjaxDownload
	 * Purpose: Handles force downloading of files by reading the href of
	 *			an anchor link, and sending it to the helper.php file for
	 *			for download.
	 *===================================================================*/
	/**
	* basepath = JURI::root() + SEGMENT
	*/
	function eflpAjaxDownload(instance, basepath)
	{
		var href = jQuery(instance).attr("href");
		
		var form_data = {
			base: basepath,
			href: href,
			ajax: '1'
		};
		
		jQuery.ajax({
			url: basepath + "helper.php",
			type: 'POST',
			data: form_data,
			success: function(msg) {
				/*alert(msg);*/
				window.location.href = msg;
			},
			error: function() {
					if (console.error)
					{
						console.error("EFLPRO Error: ajax force download error occurred.");
					}
					else
					{
						alert('EFLPRO Error: ajax force download error occurred.');
					}
				}
		});
		return false;
	}
	
	/*===================================================================*
	 * Function Operator Expression: waitToRun
	 * Purpose: Used to call a function after the user has finished 
	 *			typing. Cancels previous timeouts, and creates a new one
	 *			if called again.
	 *===================================================================*/
	var waitToRun = (function() {
		var timer = 0;
		return function(callback, milliseconds) {
			clearTimeout (timer);
			timer = setTimeout(callback, milliseconds);
		}  
	})();

	
	/*===================================================================*
	 * Filter Declaration: eflpContains
	 * Purpose: This custom jQuery filter performs a case insensitive 
	 *			searches an element's contents for a specified string
	 *			parameter.
	 *===================================================================*/
	/*
	* + Add my own custom filter "eflpContains" to jQuery
	* + It searches an element's contents (including their descendents)
	*   for a specified string parameter
	* + elem = refers to the element selector
	* + match is an array where,
	*    - match[0] = refers to the full filter string including parameter
	*    - match[1] = refers to ':'
	*    - match[2] = refers to the filter name
	*    - match[3] = refers to the parameter
	*/
	jQuery.expr[":"].eflpContains = function(elem, i, match) {
		var result = false;
		var parameter = match[3];
		if (parameter)
		{
			var search  =  new RegExp(parameter, "i");
			result = search.test(jQuery(elem).text());
		}
		
		return result;
	};
	
	/*===================================================================*
	 * Class Declaration: eflpManager
	 * Purpose: 
	 *			
	 *			
	 *===================================================================*/
	function eflpManager(listIndex, listType, triggerArray, subArray, isCollapsed, method, ease, speed, ms) {
		/** Private Constants **/
		var myTABLE_PREFIX = 'eflpro_table';
		var myUL_PREFIX = 'eflpro_list';
		var myDIV_PREFIX = 'eflpro_filterdiv';
		var myINPUT_PREFIX = 'eflpro_textbox';
		var myIMG_PREFIX = 'eflpro_cancelimg';
		var myERROR_PREFIX = 'eflpro_error';
		
		
		/** Private Properties **/
		var milliseconds = ms || 500;
		
		
		/** Public Properties **/
		//id of the table or ul that shows the listing
		this.listIndex = listIndex;
		
		//text that specifies the type of listing: 'table' or 'list'
		this.listType = listType;
		
		//array of ids of elements that when clicked hides/shows files
		this.triggerArray = triggerArray;
		
		//array of ids of elements that list subfolder files
		this.subArray = subArray;
		
		//Boolean, flags whether subfolders are initially collapsed
		this.isCollapsed = isCollapsed;
		
		//transition method: slideToggle, fadeToggle, toggle
		this.method = method;
		
		//transition ease: linear, swing
		this.ease = ease;
		
		//transition speed: fast, slow, 400 (which is the default)
		this.speed = speed || '400';
		
		
		/** Methods **/
		/*---------------------------------------------------------------
		 * Method Declaration: init
		 * Access Level: public
		 * Purpose: 
		 *---------------------------------------------------------------*/
		this.init = function() {
			//length of this.subArray = length of this.triggerArray
			for (i = 0; i < this.subArray.length; i++)
			{
				if (this.isCollapsed)
				{
					jQuery(this.subArray[i]).hide();
				}
				//Note: I really should avoid using eval here!
				//      but, I haven't figured out another way yet.
				var code = "jQuery('"+this.triggerArray[i]+"').click(function() {jQuery('"+this.subArray[i]+"')."+this.method+"('"+this.speed+"', '"+this.ease+"');});";
				eval(code);
			}
		}
		
		/*---------------------------------------------------------------
		 * Method Declaration: resetFilterText
		 * Access Level: private
		 * Purpose: 
		 *---------------------------------------------------------------*/
		function resetFilterText() {
			//get the code as text
			var code = "jQuery('#"+myINPUT_PREFIX+listIndex+"').val('');";
			if (listType == 'table')
			{
				//show all table rows
				code = code+"jQuery('#"+myTABLE_PREFIX+listIndex+" tbody').show();";
				code = code+"jQuery('#"+myTABLE_PREFIX+listIndex+" tr').show();";
			}
			else //listType == 'list'
			{
				code = code+"jQuery('#"+myUL_PREFIX+listIndex+" li').show();";
			}
			if (isCollapsed)
			{
				for (i = 0; i < subArray.length; i++)
				{
					code = code+"jQuery('"+subArray[i]+"').hide();";
				}	
			}
			
			//hide the filter error message
			code = code+"jQuery('#"+myERROR_PREFIX+listIndex+"').fadeOut('"+speed+"');";
			
			//hide the cancel search image
			code = code+"jQuery('#"+myIMG_PREFIX+listIndex+"').fadeOut('"+speed+"');";
			
			//re-focus on the textbox
			code = code+"jQuery('#"+myINPUT_PREFIX+listIndex+"').focus();";
			
			return code;
		}
		
		/*---------------------------------------------------------------
		 * Method Declaration: resetFilter
		 * Access Level: public
		 * Purpose: 
		 *---------------------------------------------------------------*/
		this.resetFilter = function() {
			var code = resetFilterText();
			eval(code);
		}
		
		/*---------------------------------------------------------------
		 * Method Declaration: setupFilterText
		 * Access Level: private
		 * Purpose: 
		 *---------------------------------------------------------------*/
		function setupFilterText() {
			// reset the search when the cancel image is clicked
			var code = "jQuery('#"+myIMG_PREFIX+listIndex+"').click(function() {";
			code = code + resetFilterText()+"});";
			 
			// cancel the search if the user presses the ESC key
			code = code + "jQuery('#"+myINPUT_PREFIX+listIndex+"').keyup(function(event) {";
			code = code + "if (event.keyCode == 27) { "+resetFilterText()+" } });";
			
			return code;
		}
		/*---------------------------------------------------------------
		 * Method Declaration: setupFilters
		 * Access Level: public
		 * Purpose: 
		 *---------------------------------------------------------------*/
		this.setupFilter = function() {
			var code = setupFilterText();
			eval(code);
		}
		
		/*---------------------------------------------------------------
		 * Method Declaration: applyFilterText
		 * Access Level: private
		 * Purpose: 
		 *---------------------------------------------------------------*/
		function applyFilterText() {
			var code = "jQuery('#"+myINPUT_PREFIX+listIndex+"').keyup(function() {";
			//wait for a set time after typing before applying filter
			code = code + "waitToRun(function() {";
			//only filter when there are 3 or more characters in the textbox
			code = code + "if (jQuery('#"+myINPUT_PREFIX+listIndex+"').val().length > 2) {";
			code = code + "if ('"+listType+"' == 'table') {";
			//show all tbodys
			code = code + "jQuery('#"+myTABLE_PREFIX+listIndex+" tbody').show();";
			//hide all rows
			code = code + "jQuery('#"+myTABLE_PREFIX+listIndex+" tr').hide();";
			// show the header row
			code = code + "jQuery('#"+myTABLE_PREFIX+listIndex+" tr:first').show();";
			// show the matching rows
			code = code + "jQuery('#"+myTABLE_PREFIX+listIndex+" tr td:eflpContains(\\''+jQuery('#"+myINPUT_PREFIX+listIndex+"').val()+'\\')').parent().show('"+speed+"');";
			code = code + "} else {";
			//listType == 'list'
			// hide all rows
			code = code + "jQuery('#"+myUL_PREFIX+listIndex+" li').hide();";
			// show the matching rows
			code = code + "jQuery('#"+myUL_PREFIX+listIndex+" li:eflpContains(\\''+jQuery('#"+myINPUT_PREFIX+listIndex+"').val()+'\\')').show('"+speed+"');";
			code = code + "}";
			// show the cancel search image
			code = code + "jQuery('#"+myIMG_PREFIX+listIndex+"').fadeIn('"+speed+"');";
			//hide the filter error message, just in case it was showing
			code = code + "jQuery('#"+myERROR_PREFIX+listIndex+"').hide();";
			code = code + "}";
			code = code + "else if (jQuery('#"+myINPUT_PREFIX+listIndex+"').val().length == 0)";
			// if the user removed all of the text, reset the filter
			code = code + "{ "+resetFilterText()+" }";
			code = code + "if ('"+listType+"' == 'table') {";
			// if there were no matching rows, tell the user
			code = code + "if (jQuery('#"+myTABLE_PREFIX+listIndex+" tr:visible').length == 1)";
			// show the filter error message
			code = code + "{ jQuery('#"+myERROR_PREFIX+listIndex+"').fadeIn('"+speed+"'); }";
			code = code + "} else {";
			//listType == 'list'
			// if there were no matching rows, tell the user
			code = code + "if (jQuery('#"+myUL_PREFIX+listIndex+" li:visible').length == 0)";
			// show the filter error message
			code = code + "{ jQuery('#"+myERROR_PREFIX+listIndex+"').fadeIn('"+speed+"'); }";
			code = code + "}";
			code = code + "}, "+milliseconds+");";
			code = code + "});";
			
			return code;
		}
		
		/*---------------------------------------------------------------
		 * Method Declaration: applyFilter
		 * Purpose: 
		 *---------------------------------------------------------------*/
		this.applyFilter = function() {
			var code = applyFilterText();
			eval(code);
		}
	}
}