<?php
/**
* Helper class for the pcte.ch Filebase Reader.
*
* @package pctech_modules
* @subpackage pctechfilebasereader
* @version $Id: helper.php 1 2011-01-21 23:25:54Z pctech $
* @copyright Marcel Würsch
* @license GNU/GPLv2
* @author Marcel Wuersch <info@pcte.ch>
*/
#
defined('JPATH_BASE') or die();
//defined( '_JEXEC' ) or die('Restricted access.');
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.path');
class modPctechFilebaseReaderFilesHelper
{
    protected static $_ftarr = array(
        'dir' => 'dir',
        'file' => 'file',
    );
    protected static $_extarr = array(
        'txt' => 'txt',
        'js' => 'txt',
        'php' => 'txt',
		'htm' => 'htm',
        'html' => 'htm',
		'mht' => 'htm',
		'jpeg' => 'img',
        'jpg' => 'img',
        'gif' => 'img',
        'png' => 'img',
		'ai' => 'img',
		'bmp' => 'img',
		'psd' => 'img',
		'pdf' => 'pdf',
		'flv' => 'swf',
		'swf' => 'swf',
        'mp3' => 'med',
		'wmv' => 'med',
		'avi' => 'med',
		'mpeg' => 'med',
		'3gp' => 'med',
		'mov' => 'mov',
		'zip' => 'zip',
        'rar' => 'zip',
        'gz'  => 'zip',
		'doc' => 'doc',
		'dot' => 'doc',
		'rtf' => 'doc',
		'ppt' => 'ppt',
		'pps' => 'ppt',
        'xls' => 'xls',
		'xlt' => 'xls',
		'csv' => 'xls',
		'vsd' => 'vsd',
		'vss' => 'vsd',
		'msg' => 'msg',
    );

    /**
     * Main function to return a list of the contents of a directory.
     *
     * @param array $params Params for this plugin.
     * @return array List of files in the directory specified in the params.
     */
	public static function getType()
		{
		$user = & JFactory::getUser();
		$userid = $user->get('id');
		return $userid; 
		}
	
	public static function getFileList($params)
    {
       
		$path = $params->get('pfrpath', '');

        if (!trim($path)) {
            throw new Exception(JText::_("MOD_PCTECHFILEBASEREADER_ERROR_NO_DIRECTORY_PATH_SPECIFIED"));
        }

        if (!is_dir($path)) {
            throw new Exception(JText::_("MOD_PCTECHFILEBASEREADER_ERROR_DIRECTORY_PATH_SPECIFIED_IS_NOT_A_DIRECTORY" . $path));
        }

        if (!$dh = opendir($path)) {
            throw new Exception(JText::_("MOD_PCTECHFILEBASEREADER_ERROR_COULD_NO_READ_THE_SPECIFIED_DIRECTORY" . $path));
        }

		if ($params->get('recurse') == 'yes') {
			$recurse = true;
		} else {
			$recurse = false;
		}
		// files we dont want in the list are excluded, not work
        $exclude = array('.svn', 'CVS');
       // Should we also show all hiddenfiles or not
	   if ($params->get('showhidden') != 'yes') {
        	$filter = '^[^\.]';
        } else {
        	$filter = '.';
        }
		
        # Read the directory
        /**
         * @todo Switch to using Joomla api JFolder to read directory.
         */
        # array   files  (string $path, [string $filter = '.'], [mixed $recurse = false], [boolean $fullpath = false], [array $exclude = array('.svn', 'CVS')]) 
		// nur diese Dateitypen anzeigen
		//$filelist = JFolder::files($path, '.doc$|.pdf$|.xls$|.xlt$|.dot$|.ppt$');
		// verschoben in konfiguration
		$user = & JFactory::getUser();
		$userid = $user->get('id');
		
		if ($params->get('usefilter') == 'yes') {
			if  ($user->get('id')) {
			$filelist = JFolder::files($path, ''.$params->get('adminfiletypelist').'', $recurse, true, $exclude);
			} else {
			$filelist = JFolder::files($path, ''.$params->get('userfiletypelist').'', $recurse, true, $exclude);
			}
			
		} 
		if ($params->get('usefilter') == 'no') {
		$filelist = JFolder::files($path, $filter, $recurse, true, $exclude);
		}
		
	     $fc = count($filelist);
			for ($i = 0; $i < $fc; $i++) {
			$file =& $filelist[$i];
			//$file->text = rawurlencode(& $filelist[$i] );
            # Read info about file.
            $ft = filetype($file);
            $ext = JFile::getExt($file);
			$file = utf8_encode($file);  
            # Set icon for item.
            $icon = 'file';
            if (isset(modPctechFilebaseReaderFilesHelper::$_extarr[$ext])) {
                $icon = modPctechFilebaseReaderFilesHelper::$_extarr[$ext];
            } else if (isset(modPctechFilebaseReaderFilesHelper::$_ftarr[$ft])) {
                $icon = modPctechFilebaseReaderFilesHelper::$_ftarr[$ft];
            }

            if ($params->get('showicons') == 'yes' && $icon) {
                $class = 'pfrfile_' . $icon;
            } else {
                $class = 'pfrfile';
            }
			
            $filearr = array(
                'filepath' => modPctechFilebaseReaderFilesHelper::stripPath($file, $path),
				'filename' => JFile::getName($file),
                'icon' => $icon,
                'ext' => $ext,
                'type' => $ft,
                'class' => $class,
            );
			
		
			// Remove double slashes and backslahses and convert all slashes and backslashes to slashes
			$filearr = preg_replace('#[/\\\\]+#', '/', $filearr);
		
			$file = new JObject();
			$file->setProperties($filearr);
			
        }

        # Sort the directory
       //modPctechFilebaseReaderFilesHelper::fileSort($filelist);

        return $filelist;
    }
    
    protected static function stripPath($filename, $path)
    {
    	if (strpos($filename, $path) === 0) {
    		return substr($filename, strlen($path) + 1);
    	} else {
    		return $filename;
    	}
    }

    protected static function fileSort($array)
    {
        # sort alphabetically by name
        usort($array, array('modPctechFilebaseReaderFilesHelper', 'compareFilename'));
    }

    protected static function compareFilename($a, $b)
    {
        return strnatcmp($a['filename'], $b['filename']);
    }



}
