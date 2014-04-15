<?php
/*
 * @package		Joomla.Framework
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 *
 * @component Phoca Component
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License version 2 or later;
 */
 
defined( '_JEXEC' ) or die();
jimport( 'joomla.application.component.modeladmin' );
jimport( 'joomla.installer.installer' );
jimport( 'joomla.installer.helper' );
jimport( 'joomla.filesystem.folder' );


class PhocaGalleryCpModelPhocaGalleryT extends JModelAdmin
{	
	protected 	$_paths 	= array();
	protected 	$_manifest 	= null;
	protected	$option 		= 'com_phocagallery';
	protected 	$text_prefix	= 'com_phocagallery';

	function __construct(){
		parent::__construct();
	}
	
	public function getForm($data = array(), $loadData = true) {
		
		$app	= JFactory::getApplication();
		$form 	= $this->loadForm('com_phocagallery.phocagalleryt', 'phocagalleryt', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}
		return $form;
	}
	
	function install($theme) {
		$app		= JFactory::getApplication();
		$db 		= JFactory::getDBO();
		$package 	= $this->_getPackageFromUpload();
	
		if (!$package) {
			JError::raiseWarning(1, JText::_('COM_PHOCAGALLERY_ERROR_FIND_INSTALL_PACKAGE'));
			$this->deleteTempFiles();
			return false;
		}
		
		if ($package['dir'] && JFolder::exists($package['dir'])) {
			$this->setPath('source', $package['dir']);
		} else {
			JError::raiseWarning(1, JText::_('COM_PHOCAGALLERY_ERROR_INSTALL_PATH_NOT_EXISTS'));
			$this->deleteTempFiles();
			return false;
		}

		// We need to find the installation manifest file
		if (!$this->_findManifest()) {
			JError::raiseWarning(1, JText::_('COM_PHOCAGALLERY_ERROR_FIND_INFO_INSTALL_PACKAGE'));
			$this->deleteTempFiles();
			return false;
		}
		
		// Files - copy files in manifest
		foreach ($this->_manifest->document->children() as $child)
		{
			if (is_a($child, 'JSimpleXMLElement') && $child->name() == 'files') {
				if ($this->parseFiles($child) === false) {
					JError::raiseWarning(1, JText::_('COM_PHOCAGALLERY_ERROR_FIND_INFO_INSTALL_PACKAGE'));
					$this->deleteTempFiles();
					return false;
				}
			}
		}
		
		// File - copy the xml file
		$copyFile 		= array();
		$path['src']	= $this->getPath( 'manifest' ); // XML file will be copied too
		$path['dest']	= JPATH_SITE.DS.'components'.DS.'com_phocagallery'.DS.'assets'.DS.'Images'.DS. basename($this->getPath('manifest')); 
		$copyFile[] 	= $path;
		$this->copyFiles($copyFile);
		
		$this->deleteTempFiles();
		
		
		// -------------------
		// Themes
		// -------------------
		// Params -  Get new themes params
		$paramsThemes = $this->getParamsThemes();
		
		// -------------------
		// Component
		// -------------------
		if (isset($theme['component']) && $theme['component'] == 1 ) {
			
			$component			=	'com_phocagallery';
			$paramsC			= JComponentHelper::getParams($component) ;
			
			foreach($paramsThemes as $keyT => $valueT) {
				$paramsC->setValue($valueT['name'], $valueT['value']);
			}
			$data['params'] 	= $paramsC->toArray();
			$table 				= JTable::getInstance('extension');
			
			$idCom				= $table->find( array('element' => $component ));
			$table->load($idCom);
			
			if (!$table->bind($data)) {
				JError::raiseWarning( 500, 'Not a valid component' );
				return false;
			}
				
			// pre-save checks
			if (!$table->check()) {
				JError::raiseWarning( 500, $table->getError('Check Problem') );
				return false;
			}

			// save the changes
			if (!$table->store()) {
				JError::raiseWarning( 500, $table->getError('Store Problem') );
				return false;
			}
		}
		
		// -------------------
		// Menu Categories
		// -------------------
		if (isset($theme['COM_PHOCAGALLERY_CATEGORIES']) && $theme['COM_PHOCAGALLERY_CATEGORIES'] == 1 ){
		
			$link		= 'index.php?option=com_phocagallery&view=categories';
			$where 		= Array();
			$where[] 	= 'link = '. $db->Quote($link);
			$query 		= 'SELECT id, params FROM #__menu WHERE '. implode(' AND ', $where);
			$db->setQuery($query);
			$itemsCat	= $db->loadObjectList();
			
			if (!empty($itemsCat)) {
				foreach($itemsCat as $keyIT => $valueIT) {
				
					$query = 'SELECT m.params FROM #__menu AS m WHERE m.id = '.(int) $valueIT->id;
					$db->setQuery( $query );
					$paramsCJSON = $db->loadResult();
					//$paramsCJSON = $valueIT->params;
					
					$paramsMc = new JParameter;
                    $paramsMc->loadJSON($paramsCJSON);
                    
					foreach($paramsThemes as $keyT => $valueT) {
						$paramsMc->setValue($valueT['name'], $valueT['value']);
					}
					$dataMc['params'] 	= $paramsMc->toArray();

					
					$table =& JTable::getInstance( 'menu' );
					
					if (!$table->load((int) $valueIT->id)) {
						JError::raiseWarning( 500, 'Not a valid table' );
						return false;
					}
					
					if (!$table->bind($dataMc)) {
						JError::raiseWarning( 500, 'Not a valid table' );
						return false;
					}
					
					// pre-save checks
					if (!$table->check()) {
						JError::raiseWarning( 500, $table->getError('Check Problem') );
						return false;
					}

					// save the changes
					if (!$table->store()) {
						JError::raiseWarning( 500, $table->getError('Store Problem') );
						return false;
					}
						
				}
			}
		}
		
		// -------------------
		// Menu Category
		// -------------------
		if (isset($theme['COM_PHOCAGALLERY_CATEGORY']) && $theme['COM_PHOCAGALLERY_CATEGORY'] == 1 ) {
			
			// Select all categories to get possible menu links
			$query = 'SELECT c.id FROM #__phocagallery_categories AS c';
			
			$db->setQuery( $query );
			$categoriesId = $db->loadObjectList();
			
			// We get id from Phoca Gallery categories and try to find menu links from these categories
			if (!empty ($categoriesId)) {
				foreach($categoriesId as $keyI => $valueI) {
				
					$link		= 'index.php?option=com_phocagallery&view=category&id='.(int)$valueI->id;
					//$link		= 'index.php?option=com_phocagallery&view=category';
					$where 		= Array();
					$where[] 	= 'link = '. $db->Quote($link);
					$query 		= 'SELECT id, params FROM #__menu WHERE '. implode(' AND ', $where);
					$db->setQuery($query);
					$itemsCat	= $db->loadObjectList();

					if (!empty ($itemsCat)) {
						foreach($itemsCat as $keyIT2 => $valueIT2) {
							
							$query = 'SELECT m.params FROM #__menu AS m WHERE m.id = '.(int) $valueIT2->id;
							$db->setQuery( $query );
							$paramsCtJSON = $db->loadResult();
							//$paramsCtJSON = $valueIT2->params;
							
							$paramsMct = new JParameter;
							$paramsMct->loadJSON($paramsCtJSON);
							
							foreach($paramsThemes as $keyT => $valueT) {
								$paramsMct->setValue($valueT['name'], $valueT['value']);
							}
							$dataMct['params'] 	= $paramsMct->toArray();
							

							$table =& JTable::getInstance( 'menu' );
							
							if (!$table->load((int) $valueIT2->id)) {
								JError::raiseWarning( 500, 'Not a valid table' );
								return false;
							}
							
							if (!$table->bind($dataMct)) {
								JError::raiseWarning( 500, 'Not a valid table' );
								return false;
							}
								
							// pre-save checks
							if (!$table->check()) {
								JError::raiseWarning( 500, $table->getError('Check Problem') );
								return false;
							}

							// save the changes
							if (!$table->store()) {
								JError::raiseWarning( 500, $table->getError('Store Problem') );
								return false;
							}	
						}
					}
				}
			}
		}
		return true;
	}
	
	function _getPackageFromUpload()
	{
		// Get the uploaded file information
		$userfile = JRequest::getVar('install_package', null, 'files', 'array' );

		// Make sure that file uploads are enabled in php
		if (!(bool) ini_get('file_uploads')) {
			JError::raiseWarning('SOME_ERROR_CODE', JText::_('COM_PHOCAGALLERY_ERROR_INSTALL_FILE_UPLOAD'));
			return false;
		}

		// Make sure that zlib is loaded so that the package can be unpacked
		if (!extension_loaded('zlib')) {
			JError::raiseWarning('SOME_ERROR_CODE', JText::_('COM_PHOCAGALLERY_ERROR_INSTALL_ZLIB'));
			return false;
		}

		// If there is no uploaded file, we have a problem...
		if (!is_array($userfile) ) {
			JError::raiseWarning('SOME_ERROR_CODE', JText::_('COM_PHOCAGALLERY_ERROR_NO_FILE_SELECTED'));
			return false;
		}

		// Check if there was a problem uploading the file.
		if ( $userfile['error'] || $userfile['size'] < 1 ) {
			JError::raiseWarning('SOME_ERROR_CODE', JText::_('COM_PHOCAGALLERY_ERROR_UPLOAD_FILE'));
			return false;
		}

		// Build the appropriate paths
		$config 	=& JFactory::getConfig();
		$tmp_dest 	= $config->getValue('config.tmp_path').DS.$userfile['name'];
		$tmp_src	= $userfile['tmp_name'];

		// Move uploaded file
		jimport('joomla.filesystem.file');
		$uploaded = JFile::upload($tmp_src, $tmp_dest);

		// Unpack the downloaded package file
		$package = JInstallerHelper::unpack($tmp_dest);
		$this->_manifest =& $manifest;
		
		$this->setPath('packagefile', $package['packagefile']);
		$this->setPath('extractdir', $package['extractdir']);
		
		return $package;
	}
	
	function getPath($name, $Default=null)
	{
		return (!empty($this->_paths[$name])) ? $this->_paths[$name] : $Default;
	}
	
	function setPath($name, $value)
	{
		$this->_paths[$name] = $value;
		
	}
	
	function _findManifest()
	{
		// Get an array of all the xml files from teh installation directory
		$xmlfiles = JFolder::files($this->getPath('source'), '.xml$', 1, true);
		
		// If at least one xml file exists
		if (count($xmlfiles) > 0) {
			foreach ($xmlfiles as $file)
			{
				// Is it a valid joomla installation manifest file?
				$manifest = $this->_isManifest($file);
				if (!is_null($manifest)) {
				
					// If the root method attribute is set to phocagallerytheme
					$root =& $manifest->document;
					if ($root->attributes('method') != 'phocagallerytheme') {
						JError::raiseWarning(1, JText::_('COM_PHOCAGALLERY_ERROR_NO_THEME_FILE'));
						return false;
					}

					// Set the manifest object and path
					$this->_manifest =& $manifest;
					$this->setPath('manifest', $file);

					// Set the installation source path to that of the manifest file
					$this->setPath('source', dirname($file));
					
					return true;
				}
			}

			// None of the xml files found were valid install files
			JError::raiseWarning(1, JText::_('COM_PHOCAGALLERY_ERROR_XML_INSTALL_PHOCA'));
			return false;
		} else {
			// No xml files were found in the install folder
			JError::raiseWarning(1, JText::_('COM_PHOCAGALLERY_ERROR_XML_INSTALL'));
			return false;
		}
	}
	
	function _isManifest($file)
	{
		// Initialize variables
		$null	= null;
		$xml	=& JFactory::getXMLParser('Simple');

		// If we cannot load the xml file return null
		if (!$xml->loadFile($file)) {
			// Free up xml parser memory and return null
			unset ($xml);
			return $null;
		}

		/*
		 * Check for a valid XML root tag.
		 * @todo: Remove backwards compatability in a future version
		 * Should be 'install', but for backward compatability we will accept 'mosinstall'.
		 */
		$root =& $xml->document;
		if (!is_object($root) || ($root->name() != 'install' )) {
			// Free up xml parser memory and return null
			unset ($xml);
			return $null;
		}

		// Valid manifest file return the object
		return $xml;
	}
	
	
	function parseFiles($element, $cid=0)
	{
		// Initialize variables
		$copyfiles = array ();

		if (!is_a($element, 'JSimpleXMLElement') || !count($element->children())) {
			// Either the tag does not exist or has no children therefore we return zero files processed.
			return 0;
		}
		
		// Get the array of file nodes to process
		$files = $element->children();
		if (count($files) == 0) {
			// No files to process
			return 0;
		}

		$source 	 = $this->getPath('source');
		$destination = JPATH_SITE.DS.'components'.DS.'com_phocagallery'.DS.'assets'.DS.'images';
		// Process each file in the $files array (children of $tagName).
		
		foreach ($files as $file)
		{
			$path['src']	= $source.DS.$file->data();
			$path['dest']	= $destination.DS.$file->data();

			// Add the file to the copyfiles array
			$copyfiles[] = $path;
		}
		return $this->copyFiles($copyfiles);
	}
	
	function copyFiles($files) {
		if (is_array($files) && count($files) > 0)
		{
			foreach ($files as $file)
			{
				// Get the source and destination paths
				$filesource	= JPath::clean($file['src']);
				$filedest	= JPath::clean($file['dest']);

				if (!file_exists($filesource)) {
					JError::raiseWarning(1, JText::sprintf('COM_PHOCAGALLERY_FILE_NOT_EXISTS', $filesource));
					return false;
				} else {
					if (!(JFile::copy($filesource, $filedest))) {
						JError::raiseWarning(1, JText::sprintf('COM_PHOCAGALLERY_ERROR_COPY_FILE_TO', $filesource, $filedest));
						return false;
					}					
				}
			}
		} else {

			JError::raiseWarning(1, JText::sprintf('COM_PHOCAGALLERY_ERROR_INSTALL_FILE'));
			return false;
		}
		
		return count($files);
	}
	
	protected function getParamsThemes() {
		// Get the manifest document root element
		$root = & $this->_manifest->document;
		
		// Get the element of the tag names
		$element =& $root->getElementByPath('params');
		if (!is_a($element, 'JSimpleXMLElement') || !count($element->children())) {
			// Either the tag does not exist or has no children therefore we return zero files processed.
			return null;
		}

		// Get the array of parameter nodes to process
		$params = $element->children();
		if (count($params) == 0) {
			// No params to process
			return null;
		}

		// Process each parameter in the $params array.
		$paramsArray = array();
		$i=0;
		foreach ($params as $param) {
			if (!$name = $param->attributes('name')) {
				continue;
			}
			if (!$value = $param->attributes('default')) {
				continue;
			}

			$paramsArray[$i]['name'] = $name;
			$paramsArray[$i]['value'] = $value;
			$i++;
		}
		return $paramsArray;
	}
	
	function getParams() {
		static $instance;
		if ($instance == null)
		{
			$table =& JTable::getInstance('component');
			$table->loadByOption( 'com_phocagallery' );

			$path	= JPATH_ADMINISTRATOR.DS.'components'.DS.'com_phocagallery'.DS.'config.xml';

			if (file_exists( $path )) {
				$instance = new JParameter( $table->params, $path );
			} else {
				$instance = new JParameter( $table->params );
			}
		}
		return $instance;
	}
	
	function deleteTempFiles () {
		// Delete Temp files
		$path = $this->getPath('source');
		if (is_dir($path)) {
			$val = JFolder::delete($path);
		} else if (is_file($path)) {
			$val = JFile::delete($path);
		}
		$packageFile = $this->getPath('packagefile');
		if (is_file($packageFile)) {
			$val = JFile::delete($packageFile);
		}
		$extractDir = $this->getPath('extractdir');
		if (is_dir($extractDir)) {
			$val = JFolder::delete($extractDir);
		}
	}
}
?>