/**
 * @version $Id$
 * @package Joomla.Administrator
 * @subpackage JoomDOC
 * @author ARTIO s.r.o., info@artio.net, http:://www.artio.net
 * @copyright Copyright (C) 2011 Artio s.r.o.. All rights reserved.
 * @license GNU General Public License version 2 or later; see LICENSE.txt
 */

var JoomDOC = {
	renamedElement : null,
	renameOldValue : null,

	/**
	 * Copy path field into title field.
	 */
	copyPath : function(path) {
		document.getElementById('jform_title').value = path;
		return false;
	},

	/**
	 * Hide element. Add class blind with negative absolute position.
	 * 
	 * @param element
	 */
	hide : function(element) {
		this.visible(element);
		element.className = element.className != '' ? (element.className + ' blind')
				: 'blind';
	},

	/**
	 * Confirm URL sending.
	 * 
	 * @param url
	 */
	confirm : function(url) {
		if (confirm(joomDOCmsgAreYouSure)) {
			var token = $('joomdocToken');
			var separator = url.match(/\?/) ? '&' : '?';
			window.location.href = url + separator + 'token=' + token.name;
		}
	},

	/**
	 * Visible element. Remove class blind.
	 * 
	 * @param element
	 */
	visible : function(element) {
		element.className = element.className.replace(/blind/gi, '');
		element.className = element.className.trim();
	},

	/**
	 * Check file/folder checkbox. After checkin standard checkbox (for
	 * document) then check also hidden for file/folder.
	 * 
	 * @param element
	 *            HTML element standard checkbox cb#
	 * @param id
	 *            row ID
	 */
	check : function(element, id) {
		var listener = document.getElementById('cbb' + id);
		listener.checked = element.checked;
	},

	/**
	 * Upload new file into current folder.
	 * 
	 * @param element
	 *            button to start
	 * @param task
	 *            request task value
	 * @param msgEmpty
	 *            message if file fields is empty (no select file)
	 * @param msgOverwrite
	 *            message if file alreday exists (allow overwrite)
	 * @param msgDirExists
	 *            message if exist directory with the same name
	 * @returns {Boolean} false to disable automatic submit
	 */
	upload : function(element) {
		var upload = document.getElementById('upload');
		if (upload.value.trim() == '') {
			// no select file to upload
			alert(joomDOCmsgEmpty);
			return false;
		}
		// name of uploaded file (on windows full path)
		var path = upload.value;
		// convert backslashes to slashes
		path = path.replace(/\\/g, '/');
		// split to path segments to get file name without path
		path = path.split('/');
		var length = path.length;
		// get file name
		if (length > 0) {
			path = path[length - 1];
		} else {
			path = upload.value;
		}
		// control if file with the same ename already exists in current
		// directory
		for ( var i = 0; i < joomDOCFiles.length; i++) {
			if (joomDOCFiles[i] == path) {
				// name of some file in directory equals with uploaded
				if (!confirm(joomDOCmsgOverwrite)) {
					return false;
				}
				break;
			}
		}
		// control if folder with the same ename already exists in current
		// directory
		for ( var i = 0; i < joomDOCFolders.length; i++) {
			if (joomDOCFolders[i] == path) {
				// name of some folder in directory equals with uploaded
				alert(joomDOCmsgDirExists);
				return false;
			}
		}
		// OK set task and submit
		element.form.task.value = joomDOCTaskUploadFile;
		element.form.submit();
	},
	/**
	 * Open rename dialog.
	 * 
	 * @param element
	 */
	openRename : function(element) {
		if (this.renamedElement) {
			this.closeRename(this.renamedElement, this.renameOldValue);
		}
		// cells in table row
		var cell = $$('#openRename' + element).getParent();
		cell = cell[0];
		var row = cell.getParent();
		var cells = row.getChildren();
		for ( var k = 0; k < cells.length; k++) {
			// rename dialog contain cell with class filepath
			if (cells[k].className == 'filepath') {
				// hide link to file
				var link = cells[k].getElement('a');
				this.hide(link);
				// visible rename box
				var div = cells[k].getElement('div');
				this.visible(div);
				// safe renamed element to close if user will click on next
				// rename tool
				var children = div.getChildren();
				this.renameOldValue = link.innerHTML;
				this.renamedElement = children[0];
				// next step
				continue;
			}
			// hide rename start button
			if (cells[k].className == 'rename') {
				var links = cells[k].getChildren();
				this.hide(links[0]);
				// stop all is satisfied
				break;
			}
		}
	},

	/**
	 * Close rename dialog.
	 * 
	 * @param element
	 *            cancel button to get position
	 * @param oldValue
	 *            old file/folder name
	 * @returns {Boolean} false to disable automatic submit
	 */
	closeRename : function(element, oldValue) {
		// div containing rename tools
		var div = element.getParent();
		// table cell
		var cell = div.getParent();
		// input to new name
		var input = div.getElement('input');
		// reset to old value
		input.value = oldValue;
		// visible file downlad link
		this.visible(cell.getElement('a'));
		// hide rename tools
		this.hide(cell.getElement('div'));
		// file/folder table row
		var row = cell.getParent();
		var cells = row.getChildren();
		for ( var k = 0; k < cells.length; k++) {
			// visible rename start button
			if (cells[k].className == 'rename') {
				var links = cells[k].getChildren();
				this.visible(links[0]);
				// stop all is satisfied
				return false;
			}
		}
		return false;
	},
	/**
	 * Rename file/folder
	 * 
	 * @param element
	 *            start to button
	 * @param task
	 *            request task value
	 * @param oldName
	 *            old file name
	 * @param path
	 *            relative file path
	 * @param msgSameName
	 *            message if in rename input is the same name as is old file
	 *            name
	 * @param msgEmptyName
	 *            message if name is empty
	 * @param msgFileExists
	 *            message if current folder already exists file with the sane
	 *            name
	 * @param msgDirExists
	 *            message if current folder already exists subfolder with the
	 *            sane name
	 * @returns {Boolean} false to disable automatic submit
	 */
	rename : function(element, task, oldName, path, msgSameName, msgEmptyName,
			msgFileExists, msgDirExists) {
		// parent of input and button
		var parent = element.getParent();
		// input with new name
		var newName = parent.getElement('input');
		// new name and old name cannot be the same
		if (newName.value.trim() == oldName.trim()) {
			alert(msgSameName);
			return false;
		}
		// new name cannot be empty
		if (newName.value.trim() == '') {
			alert(msgEmptyName);
			return false;
		}
		// unable rename to exists file
		for ( var i = 0; i < joomDOCFiles.length; i++) {
			if (joomDOCFiles[i] == newName.value) {
				alert(msgFileExists);
				return false;
			}
		}
		// unable rename to exists folder
		for ( var i = 0; i < joomDOCFolders.length; i++) {
			if (joomDOCFolders[i] == newName.value) {
				alert(msgDirExists);
				return false;
			}
		}
		// add values into form hidden fields
		element.form.task.value = task;
		element.form.renamePath.value = path;
		element.form.newName.value = newName.value;
		// submit
		element.form.submit();
	},
	/**
	 * Create subfolder in current folder.
	 * 
	 * @param element
	 *            start button to acces form
	 * @param task
	 *            request task value
	 * @param msgEmpty
	 *            message if name is empty
	 * @param msgFileExists
	 *            message if in current folder already exist file with the same
	 *            name
	 * @param msgDirExists
	 *            message if in current folder already exist folder with the
	 *            same name
	 * @returns {Boolean} false to disable automatic submit
	 */
	mkdir : function(element, task, msgEmpty, msgFileExists, msgDirExists) {
		var name = document.getElementById('newfolder');
		if (name.value.trim() == '') {
			// subfolder name is empty
			alert(msgEmpty);
			return false;
		}
		// control if current folder already exist file with the same name
		for ( var i = 0; i < joomDOCFiles.length; i++) {
			if (joomDOCFiles[i] == name.value) {
				/*
				 * name of some file in current folder equals with new subfolder
				 * name
				 */
				alert(msgFileExists);
				return false;
			}
		}
		// control if current folder already exist subfolder with the same name
		for ( var i = 0; i < joomDOCFolders.length; i++) {
			if (joomDOCFolders[i] == name.value) {
				/*
				 * name of some subfolder in current folder equals with new
				 * subfolder name
				 */
				alert(msgDirExists);
				return false;
			}
		}
		// OK set task and submit
		element.form.task.value = task;
		element.form.submit();
	}/* <PAID> */,

	/**
	 * Start webdav editing.
	 */
	webdavInit : function() {
		// files list generated in backend
		var items = $$('#tdoc .filepath .hasTip');
		// file detail on frontend
		var editWebDav = document.getElementById('editWebDav');
		if (items.length == 0 && !editWebDav) {
			// not found any item
			return;
		}
		// files list generated by WebDav framework
		var items2 = $$('.fileList_table .editWebDav');
		if (items2.length == 0) {
			// not initaliazed, wait ...
			setTimeout('JoomDOC.webdavInit()', 500);
			return;
		}
		// cleanup from HTML
		var items2Values = this.getCleanValues(items2);
		if (editWebDav) {
			// frontend
			var editWebDavHTML = editWebDav.innerHTML;
			editWebDav.innerHTML = '';
			for ( var j = 0; j < items2Values.length; j++) {
				// search for file in WebDav files
				if (editWebDavHTML == items2Values[j]) {
					// get WebDav link to server connect
					editWebDav.appendChild(items2[j]);
					// satisfied
					return;
				}
			}
		} else {
			// backend
			var itemsValues = this.getCleanValues(items);
			for ( var i = 0; i < itemsValues.length; i++) {
				// for each file
				for ( var j = 0; j < items2Values.length; j++) {
					// search for file in WebDav files
					if (itemsValues[i] == items2Values[j]) {
						// cells in browse table to add server connect link
						var cells = items[i].getParent().getParent()
								.getChildren();
						for ( var k = 0; k < cells.length; k++) {
							// search for cell with classname edit
							if (cells[k].className == 'edit'
									&& cells[k].innerHTML.trim() == '') {
								// add WebDav link to connect server
								cells[k].adopt(items2[j]);
								// satisfied
								break;
							}
						}
						// not found
						break;
					}
				}
			}
		}
	},
	/**
	 * Get values cleanup from tags span and strong.
	 * 
	 * @param items
	 * @returns {Array}
	 */
	getCleanValues : function(items) {
		var itemsValues = new Array();
		for ( var i = 0; i < items.length; i++) {
			var html = items[i].innerHTML;
			// strip tag strong leave content
			html = html.replace(/<\/?strong[^>]*>/gi, '');
			// strip tag span with content
			html = html.replace(/<span[^>]*>[^<]*<\/span>/gi, '');
			itemsValues[i] = html;
		}
		return itemsValues;
	}
/* </PAID> */
}