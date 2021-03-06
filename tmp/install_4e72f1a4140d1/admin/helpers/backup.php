<?php
/**
* @package   com_zoo Component
* @file      backup.php
* @version   2.4.9 May 2011
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

/*
   Class: BackupHelper
   	  Backup helper class.
*/
class BackupHelper extends AppHelper {

	/*
		Function: getTables
			Returns all ZOO table names

		Returns:
			Array - array containing zoo table names
	*/
	public function getTables() {
		$tables = array();
		foreach (get_defined_constants() as $key => $define) {
			if (preg_match('/^ZOO_TABLE_/', $key)) {
				$tables[$key] = $define;
			}
		}
		return $tables;
	}

	/*
		Function: backup
			Creates a backup of all ZOO tables

		Returns:
			String - filename on success, else false
	*/
	public function all($callback = true) {
		return $this->table($this->getTables(), $callback);
	}

	/*
		Function: table
			Creates a backup of tables

		Parameters:
			$tables - Table(s) to backup

		Returns:
			String - the mysql statements
	*/
	public function table($tables, $callback = false) {

		// set_time_limit doesn't work in safe mode
        if (!ini_get('safe_mode')) {
		    @set_time_limit(0);
        }

		// init vars
		$db		= $this->app->database;
		$tables = (array) $tables;
		$result = array();
		if (!empty($tables)) {
						
			foreach($tables as $table) {

				$table = $db->replacePrefix($table);

				// create comments
				$result[] = "\n\n-- --------------------------------------------------------\n";
				$result[] = '--';
				$result[] = '-- Table structure for table ' . $table;
				$result[] = "--\n";

				$rows = $db->queryAssocList('SELECT * FROM ' . $table);

				if (is_callable($callback)) {
					$rows = array_map($callback, $rows);
				}

				$result[] = 'DROP TABLE '.$table.';';
				$create = $db->queryAssoc('SHOW CREATE TABLE '.$table);
				$create = $create['Create Table'];
				$result[] = "$create;\n";

				// create comments
				$result[] = '--';
				$result[] = '-- Table data for table ' . $table;
				$result[] = '--';

				$insert = 'INSERT INTO '.$table.' VALUES(';
				foreach ($rows as $index => $row) {
				
					$result[] = $insert.'"'.implode('","', array_map(array($db, 'getEscaped'), $row))."\");";
					
				}

			}
			
			return implode("\n", $result);

		}
	}

	/*
		Function: restore
			Restores a backup from sql dump file

		Parameters:
			$file - The sql dump file

		Returns:
			Bool - true on success
	*/
	public function restore($file) {

		if (JFile::exists($file)) {

			$db = $this->app->database;

			// read index.sql
			$buffer = JFile::read($file);

			// Create an array of queries from the sql file
			jimport('joomla.installer.helper');
			$queries = JInstallerHelper::splitSql($buffer);
			if (!empty($queries)) {

				foreach($queries as $query) {

					$query = trim($query);

					try {

						if (!empty($query)) {
							$db->query($query);
						}

					} catch (AppDatabaseException $e) {
						throw new BackupException($e);
					}
				}

				return true;
			}

		}
		throw new BackupException("File not found ($file)");
	}

	/*
		Function: generateHeader
			Generates a header for the backup file

		Returns:
			String - The header for the backup file
	*/
	public function generateHeader() {

		$header[] = '-- ZOO SQL Dump';
		$header[] = '-- version ' . $this->app->zoo->version();
		$header[] = '-- http://www.yootheme.com';
		$header[] = '--';
		$header[] = '-- Host: ' . trim(isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '');
		$header[] = '-- Creation Date: ' . $this->app->date->create()->toFormat();
		$header[] = '-- Server Software: ' . trim(isset($_SERVER['SERVER_SOFTWARE']) ? $_SERVER['SERVER_SOFTWARE'] : '');
		$header[] = "\n";
		$header[] = '-- ';
		$header[] = '-- Database: '.$this->app->system->application->getCfg('db');
		$header[] = '-- ';

		return implode("\n", $header);

	}

	public function convertElementsToJSON($row) {

		if (isset($row['elements']) && isset($row['application_id'])) {
			$row['elements'] = $this->app->element->convertXMLToJSON($row['elements'], $this->app->zoo->getApplication($row['application_id']));
		}

		return $row;
		
	}

}

/*
	Class: BackupException
*/
class BackupException extends AppException {}