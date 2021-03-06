<?php
/**
* @package   com_zoo Component
* @file      install.php
* @version   2.4.9 May 2011
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

/*
   Class: InstallHelper
   The Helper Class for installs
*/
class InstallHelper extends AppHelper {

	public function uninstallApplication(Application $application) {
		
		$group = $application->getGroup();
		
		if ($this->_applicationExists($group)) {
			throw new InstallHelperException('Delete existing applications first.');
		}
		
		if (!($directory = $this->app->path->path("applications:$group")) || !JFolder::delete($directory)) {
			throw new InstallHelperException('Unable to delete directory: (' . $directory . ')');
		}

	}
	
	protected function _applicationExists($group) {
		$result = $this->app->table->application->first(array('conditions' => 'application_group = '.$this->app->database->Quote($group)));
		return !empty($result);
	}
	
	/*
		Function: installApplicationFromUserfile
			Installs an Application from a user upload.

		Parameters:
			$userfile - uploaded userfile

		Returns:
			Mixed - true on success
	*/
	public function installApplicationFromUserfile($userfile) {
		// Make sure that file uploads are enabled in php
		if (!(bool) ini_get('file_uploads')) {
			throw new InstallHelperException('Fileuploads are not enabled in php.');
		}

		// If there is no uploaded file, we have a problem...
		if (!is_array($userfile) ) {
			throw new InstallHelperException('No file selected.');
		}

		// Check if there was a problem uploading the file.
		if ( $userfile['error'] || $userfile['size'] < 1 ) {
			throw new InstallHelperException('Upload error occured.');
		}

		// Temporary folder to extract the archive into
		$tmp_directory = $this->app->path->path('tmp:').'/';
		$archivename = $tmp_directory.$userfile['name'];
		
		if (!JFile::upload($userfile['tmp_name'], $archivename)) {
			throw new InstallHelperException("Could not move uploaded file to ($archivename)");
		}

		// Clean the paths to use for archive extraction
		$extractdir = $tmp_directory.uniqid('install_');

		jimport('joomla.filesystem.archive');

		// do the unpacking of the archive
		if (!JArchive::extract($archivename, $extractdir)) {
			throw new InstallHelperException("Could not extract zip file to ($tmp_directory)");
		}
		
		return $this->installApplicationFromFolder($extractdir);
		
	}
	
	/*
		Function: installApplicationFromFolder
			Installs an Application from a folder.

		Parameters:
			$folder - application folder

		Returns:
			Mixed - true on success
	*/
	public function installApplicationFromFolder($folder) {
		$folder = rtrim($folder, "\\/") . '/';

		if (!($manifest = $this->findManifest($folder))) {
			throw new InstallHelperException('No valid xml file found in the directory');
		}

		if (($group = $this->getGroup($manifest)) && empty($group)) {
			throw new InstallHelperException('No app group in application.xml specified.');
		}

		$update = false;

		$write_directory = $this->app->path->path('applications:').'/'.$group.'/';
		if (JFolder::exists($write_directory)) {

			$files = $this->app->filesystem->readDirectoryFiles($folder.'types/', '', '/\.xml$/', false);
			foreach ($files as $file) {
				if (JFile::exists($write_directory.'types/'.$file)) {
					JFile::delete($folder.'types/'.$file);
				}
			}

			$files = $this->app->filesystem->readDirectoryFiles($folder, '', '/positions\.config$/', true);
			foreach ($files as $file) {
				if (JFile::exists($write_directory.$file)) {
					JFile::delete($folder.$file);
				}
			}

			$update = true;
		}

		if (!JFolder::copy($folder, $write_directory, '', true)) {
			throw new InstallHelperException('Unable to write to folder: ' . $write_directory);
		}

		return $update ? 2 : 1;
	}

	public function getGroup(AppXMLElement $manifest) {
		return (string) $manifest->group;
	}
	
	public function getVersion(AppXMLElement $manifest) {
		return (string) $manifest->version;
	}
	
	public function findManifest($path) {
		$path = rtrim($path, "\\/") . '/';
		foreach ($this->app->filesystem->readDirectoryFiles($path, $path, '/\.xml$/', false) as $file) {
			if (($xml = $this->app->xml->loadFile($file)) && $this->isManifest($xml)) {
				return $xml;
			}
		}
		
		return false;

	}

	public function isManifest(AppXMLElement $xml) {
		return $xml->getName() == 'application';
	}
	
}

/*
	Class: InstallHelperException
*/
class InstallHelperException extends AppException {}