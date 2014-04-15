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
jimport('joomla.application.component.modeladmin');


class PhocaDownloadCpModelPhocaDownloadCat extends JModelAdmin
{

	protected	$option 		= 'com_phocadownload';
	protected 	$text_prefix	= 'com_phocadownload';
	
	
	protected function canDelete($record)
	{
		$user = JFactory::getUser();

		if ($record->catid) {
			return $user->authorise('core.delete', 'com_phocadownload.phocadownloadcat.'.(int) $record->catid);
		} else {
			return parent::canDelete($record);
		}
	}
	
	protected function canEditState($record)
	{
		$user = JFactory::getUser();

		if ($record->catid) {
			return $user->authorise('core.edit.state', 'com_phocadownload.phocadownloadcat.'.(int) $record->catid);
		} else {
			return parent::canEditState($record);
		}
	}
	
	public function getTable($type = 'PhocaDownloadCat', $prefix = 'Table', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}
	
	public function getForm($data = array(), $loadData = true) {
		
		$app	= JFactory::getApplication();
		$form 	= $this->loadForm('com_phocadownload.phocadownloadcat', 'phocadownloadcat', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}
		return $form;
	}
	
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_phocadownload.edit.phocadownloadcat.data', array());

		if (empty($data)) {
			$data = $this->getItem();
		}

		return $data;
	}
	
	public function getItem($pk = null)
	{
		if ($item = parent::getItem($pk)) {
			// Convert the params field to an array.
			$registry = new JRegistry;
			$registry->loadJSON($item->metadata);
			$item->metadata = $registry->toArray();
		}

		return $item;
	}
	
	protected function prepareTable(&$table)
	{
		jimport('joomla.filter.output');
		$date = JFactory::getDate();
		$user = JFactory::getUser();

		$table->title		= htmlspecialchars_decode($table->title, ENT_QUOTES);
		$table->alias		= JApplication::stringURLSafe($table->alias);

		if (empty($table->alias)) {
			$table->alias = JApplication::stringURLSafe($table->title);
		}

		if (empty($table->id)) {
			// Set the values
			//$table->created	= $date->toMySQL();

			// Set ordering to the last item if not set
			if (empty($table->ordering)) {
				$db = JFactory::getDbo();
				$db->setQuery('SELECT MAX(ordering) FROM #__phocadownload_categories');
				$max = $db->loadResult();

				$table->ordering = $max+1;
			}
		}
		else {
			// Set the values
			//$table->modified	= $date->toMySQL();
			//$table->modified_by	= $user->get('id');
		}
	}
	
	
	protected function getReorderConditions($table = null)
	{
		$condition = array();
		$condition[] = 'parent_id = '. (int) $table->parent_id;
		//$condition[] = 'state >= 0';
		return $condition;
	}
	
	
	
	
	
	
	/*
	 * Custom Save method - libraries/joomla/application/component/modeladmin.php
	 */
	public function save($data)
	{
		
		// = = = = = = = = = = 
		// Default VALUES FOR Rights in FRONTEND
		// ACCESS -  0: all users can see the category (registered or not registered)
		//             if registered or not registered it will be set in ACCESS LEVEL not here)
		//			   if -1 - user was not selected so every registered or special users can see category
		// UPLOAD - -2: nobody can upload or add images in front (if 0 - every users can do it)
		// DELETE - -2: nobody can upload or add images in front (if 0 - every users can do it)
		if(!isset($data['accessuserid'])) { $data['accessuserid'] = array();}
		if(!isset($data['uploaduserid'])) { $data['uploaduserid'] = array();}
		if(!isset($data['deleteuserid'])) { $data['deleteuserid'] = array();}
		$accessUserIdArray	= PhocaDownloadHelper::toArray($data['accessuserid']);
		$uploadUserIdArray	= PhocaDownloadHelper::toArray($data['uploaduserid']);
		$deleteUserIdArray	= PhocaDownloadHelper::toArray($data['deleteuserid']);

		if (isset($data['access']) && (int)$data['access'] > 0 && (int)$accessUserIdArray[0] == 0) {		
			$accessUserId[0]	= -1;
		}
		$data['accessuserid'] = implode(',',$accessUserIdArray);
		$data['uploaduserid'] = implode(',',$uploadUserIdArray);
		$data['deleteuserid'] = implode(',',$deleteUserIdArray);
	
		// = = = = = = = = = = 
		
		
		// Initialise variables;
		$dispatcher = JDispatcher::getInstance();
		$table		= $this->getTable();
		$pk			= (!empty($data['id'])) ? $data['id'] : (int)$this->getState($this->getName().'.id');
		$isNew		= true;

		// Include the content plugins for the on save events.
		JPluginHelper::importPlugin('content');

		// Load the row if saving an existing record.
		if ($pk > 0) {
			$table->load($pk);
			$isNew = false;
		}

		// Bind the data.
		if (!$table->bind($data)) {
			$this->setError($table->getError());
			return false;
		}
		
		if(intval($table->date) == 0) {
			$table->date = JFactory::getDate()->toMySQL();
		}

		// Prepare the row for saving
		$this->prepareTable($table);

		// Check the data.
		if (!$table->check()) {
			$this->setError($table->getError());
			return false;
		}

		// Trigger the onContentBeforeSave event.
		/*$result = $dispatcher->trigger($this->event_before_save, array($this->option.'.'.$this->name, $table, $isNew));
		if (in_array(false, $result, true)) {
			$this->setError($table->getError());
			return false;
		}*/

		// Store the data.
		if (!$table->store()) {
			$this->setError($table->getError());
			return false;
		}

		// Clean the cache.
		$cache = JFactory::getCache($this->option);
		$cache->clean();

		// Trigger the onContentAfterSave event.
		//$dispatcher->trigger($this->event_after_save, array($this->option.'.'.$this->name, $table, $isNew));

		$pkName = $table->getKeyName();
		if (isset($table->$pkName)) {
			$this->setState($this->getName().'.id', $table->$pkName);
		}
		$this->setState($this->getName().'.new', $isNew);

		
		
		return true;
	}
	
	
	
	
	

	function delete($cid = array()) {
		$app	= JFactory::getApplication();
		$db 	= JFactory::getDBO();
		
		$result = false;
		if (count( $cid )) {
			JArrayHelper::toInteger($cid);
			$cids = implode( ',', $cid );
			
			// FIRST - if there are subcategories - - - - - 	
			$query = 'SELECT c.id, c.name, c.title, COUNT( s.parent_id ) AS numcat'
			. ' FROM #__phocadownload_categories AS c'
			. ' LEFT JOIN #__phocadownload_categories AS s ON s.parent_id = c.id'
			. ' WHERE c.id IN ( '.$cids.' )'
			. ' GROUP BY c.id'
			;
			$db->setQuery( $query );
				
			if (!($rows2 = $db->loadObjectList())) {
				JError::raiseError( 500, $db->stderr('Load Data Problem') );
				return false;
			}

			// Add new CID without categories which have subcategories (we don't delete categories with subcat)
			$err_cat = array();
			$cid 	 = array();
			foreach ($rows2 as $row) {
				if ($row->numcat == 0) {
					$cid[] = (int) $row->id;
				} else {
					$err_cat[] = $row->title;
				}
			}
			// - - - - - - - - - - - - - - -
			
			// Images with new cid - - - - -
			if (count( $cid )) {
				JArrayHelper::toInteger($cid);
				$cids = implode( ',', $cid );
			
				// Select id's from phocadownload tables. If the category has some images, don't delete it
				$query = 'SELECT c.id, c.name, c.title, COUNT( s.catid ) AS numcat'
				. ' FROM #__phocadownload_categories AS c'
				. ' LEFT JOIN #__phocadownload AS s ON s.catid = c.id'
				. ' WHERE c.id IN ( '.$cids.' )'
				. ' GROUP BY c.id';
			
				$db->setQuery( $query );

				if (!($rows = $db->loadObjectList())) {
					JError::raiseError( 500, $db->stderr('Load Data Problem') );
					return false;
				}
				
				$err_img = array();
				$cid 	 = array();
				foreach ($rows as $row) {
					if ($row->numcat == 0) {
						$cid[] = (int) $row->id;
					} else {
						$err_img[] = $row->title;
					}
				}
				
				if (count( $cid )) {
					$cids = implode( ',', $cid );
					$query = 'DELETE FROM #__phocadownload_categories'
					. ' WHERE id IN ( '.$cids.' )';
					$db->setQuery( $query );
					if (!$db->query()) {
						$this->setError($this->_db->getErrorMsg());
						return false;
					}
					
					// Delete items in phocadownload_user_category
				/*	$query = 'DELETE FROM #__phocadownload_user_category'
					. ' WHERE catid IN ( '.$cids.' )';
					$db->setQuery( $query );
					if (!$db->query()) {
						$this->setError($this->_db->getErrorMsg());
						return false;
					}*/
				}
			}
			
			// There are some images in the category - don't delete it
			$msg = '';
			if (count( $err_cat ) || count( $err_img )) {
				if (count( $err_cat )) {
					$cids_cat = implode( ", ", $err_cat );
					$msg .= JText::plural( 'COM_PHOCADOWNLOAD_ERROR_DELETE_CONTAIN_CAT', $cids_cat );
				}
				
				if (count( $err_img )) {
					$cids_img = implode( ", ", $err_img );
					$msg .= JText::plural( 'COM_PHOCADOWNLOAD_ERROR_DELETE_CONTAIN_FILE', $cids_img );
				}
				$link = 'index.php?option=com_phocadownload&view=phocadownloadcats';
				$app->redirect($link, $msg);
			}
		}
		return true;
	}
	
}
?>