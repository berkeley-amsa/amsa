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
jimport( 'joomla.application.component.modellist' );

class PhocaDownloadCpModelPhocaDownloadUserStats extends JModelList
{
	protected	$option 		= 'com_phocadownload';
	//public 		$context		= 'com_phocadownload.phocadownloadcoimgs';
	protected function populateState()
	{
		// Initialise variables.
		$app = JFactory::getApplication('administrator');

		// Load the filter state.
		$search = $app->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);
/*
		$accessId = $app->getUserStateFromRequest($this->context.'.filter.access', 'filter_access', null, 'int');
		$this->setState('filter.access', $accessId);

		$state = $app->getUserStateFromRequest($this->context.'.filter.state', 'filter_published', '', 'string');
		$this->setState('filter.state', $state);
*/
		$id = JRequest::getVar( 'id', '', '', 'int');
		if ((int)$id > 0) {
			$this->setState('filter.filestat_id', $id);
		} else {
			//$fileStatId = $app->getUserStateFromRequest($this->context.'.filter.filestat_id', 'filter_filestat_id', $id);
			$this->setState('filter.filestat_id', 0);
		}
/*
		$language = $app->getUserStateFromRequest($this->context.'.filter.language', 'filter_language', '');
		$this->setState('filter.language', $language);*/

		// Load the parameters.
		$params = JComponentHelper::getParams('com_phocadownload');
		$this->setState('params', $params);

		// List state information.
		parent::populateState('uc.name', 'asc');
	}
	
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id	.= ':'.$this->getState('filter.search');
		/*$id	.= ':'.$this->getState('filter.access');
		$id	.= ':'.$this->getState('filter.state');
		$id	.= ':'.$this->getState('filter.category_id');*/
		$id	.= ':'.$this->getState('filter.filestat_id');

		return parent::getStoreId($id);
	}
	
	
	protected function getListQuery()
	{
		/*$query = ' SELECT a.id, a.userid, a.fileid, d.filename AS filename, d.title AS filetitle, a.count, a.date, u.name AS uname, u.username AS username, 0 AS checked_out'
			. ' FROM #__phocadownload_user_stat AS a '
			. ' LEFT JOIN #__phocadownload AS d ON d.id = a.fileid '
			. ' LEFT JOIN #__users AS u ON u.id = a.userid '
			. $where
			. ' GROUP by a.id'
			. $orderby;
		*/
		// Create a new query object.
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);

		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select',
				'a.*'
			)
		);
		$query->from('`#__phocadownload_user_stat` AS a');

		// Join over the language
		//$query->select('l.title AS language_title');
		//$query->join('LEFT', '`#__languages` AS l ON l.lang_code = a.language');

		// Join over the users for the checked out user.
		
		
		//$query->select('uc.name AS editor');
		//$query->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');
		
	

		// Join over the asset groups.
		//$query->select('ag.title AS access_level');
		//$query->join('LEFT', '#__viewlevels AS ag ON ag.id = a.access');

		// Join over the categories.
		$query->select('d.filename AS filename, d.title AS filetitle, a.date');
		$query->join('LEFT', '#__phocadownload AS d ON d.id = a.fileid');
		
		$query->select('ua.id AS userid, ua.username AS username, ua.name AS usernameno');
		$query->join('LEFT', '#__users AS ua ON ua.id = a.userid');
		
		//$query->select('v.average AS ratingavg');
		//$query->join('LEFT', '#__phocadownload_img_votes_statistics AS v ON v.imgid = a.id');

		// Filter by access level.
		//if ($access = $this->getState('filter.access')) {
		//	$query->where('a.access = '.(int) $access);
		//}

		// Filter by published state.
		/*$published = $this->getState('filter.state');
		if (is_numeric($published)) {
			$query->where('a.published = '.(int) $published);
		}
		else if ($published === '') {
			$query->where('(a.published IN (0, 1))');
		}

		// Filter by category.*/
		$fileStatId = $this->getState('filter.filestat_id');
		
		if (is_numeric($fileStatId)) {
			$query->where('a.fileid = ' . (int) $fileStatId);
		}

		// Filter by search in title
		$search = $this->getState('filter.search');
		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0) {
				$query->where('a.id = '.(int) substr($search, 3));
			}
			else
			{
				$search = $db->Quote('%'.$db->getEscaped($search, true).'%');
				$query->where('( a.title LIKE '.$search.' OR a.filename LIKE '.$search.')');
			}
		}
		
		$query->group('a.id');

		// Add the list ordering clause.
		$orderCol	= $this->state->get('list.ordering');
		$orderDirn	= $this->state->get('list.direction');
		
		if ($orderCol == 'uc.name' ) {
			$orderCol =  'a.ordering';
		}
		
		$query->order($db->getEscaped($orderCol.' '.$orderDirn));

		
		return $query;
	}
 
 
/* 
defined('_JEXEC') or die();
jimport('joomla.application.component.model');

class PhocaDownloadCpModelPhocaDownloadUserStat extends JModel
{
	var $_id;
	var $_data = null;
	var $_total = null;
	var $_pagination = null;
	var $_context		= 'com_phocadownload.phocadownloadut';

	function __construct() {
		parent::__construct();		
		$app = JFactory::getApplication();
		
		$array = JRequest::getVar('cid',  0, '', 'array');
		$this->setId((int)$array[0]);	
		
		// Get the pagination request variables
		$limit	= $app->getUserStateFromRequest( $this->_context.'.list.limit', 'limit', $app->getCfg('list_limit'), 'int' );
		$limitstart	= $app->getUserStateFromRequest( $this->_context.'.limitstart', 'limitstart',	0, 'int' );
		// In case limit has been changed, adjust limitstart accordingly
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
	}
	
	function setId($id) {
		$this->_id		= $id;
		$this->_data	= null;
	}

	function getData() {
		if (empty($this->_data)) {
			$query = $this->_buildQuery();
			$this->_data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
		}
		return $this->_data;
	}

	function getTotal() {
		if (empty($this->_total)) {
			$query = $this->_buildQuery();
			$this->_total = $this->_getListCount($query);
		}
		return $this->_total;
	}

	function getPagination() {
		if (empty($this->_pagination)) {
			jimport('joomla.html.pagination');
			$this->_pagination = new JPagination( $this->getTotal(), $this->getState('limitstart'), $this->getState('limit') );
		}
		return $this->_pagination;
	}
	

	function _buildQuery() {
		$where		= $this->_buildContentWhere();
		$orderby	= $this->_buildContentOrderBy();
		$query = ' SELECT a.id, a.userid, a.fileid, d.filename AS filename, d.title AS filetitle, a.count, a.date, u.name AS uname, u.username AS username, 0 AS checked_out'
			. ' FROM #__phocadownload_user_stat AS a '
			. ' LEFT JOIN #__phocadownload AS d ON d.id = a.fileid '
			. ' LEFT JOIN #__users AS u ON u.id = a.userid '
			. $where
			. ' GROUP by a.id'
			. $orderby;

		return $query;
	}

	function _buildContentOrderBy(){
		$app = JFactory::getApplication();
		$filter_order		= $app->getUserStateFromRequest( $this->_context.'.filter_order',	'filter_order',	'a.count','cmd' );
		$filter_order_Dir	= $app->getUserStateFromRequest( $this->_context.'.filter_order_Dir',	'filter_order_Dir',	'DESC',	'word' );

		if ($filter_order == 'a.ordering'){
			$orderby 	= ' ORDER BY  a.ordering '.$filter_order_Dir;
		} else {
			$orderby 	= ' ORDER BY '.$filter_order.' '.$filter_order_Dir.' , a.count, a.ordering ';
		}
		return $orderby;
	}

	function _buildContentWhere() {
		$app = JFactory::getApplication();

		
		$filter_state		= $app->getUserStateFromRequest( $this->_context.'.filter_state',	'filter_state',	'',	'word' );
		$filter_order		= $app->getUserStateFromRequest( $this->_context.'.filter_order',	'filter_order',	'a.ordering','cmd' );
		$filter_order_Dir	= $app->getUserStateFromRequest( $this->_context.'.filter_order_Dir',	'filter_order_Dir',	'',	'word' );
		$search				= $app->getUserStateFromRequest( $this->_context.'.search','search','',	'string' );
		$search				= JString::strtolower( $search );

		$where = array();
		
		$where[] = 'a.fileid ='.(int)$this->_id;

		if ($search) {
			$where[] = 'LOWER(d.title) LIKE '.$this->_db->Quote('%'.$search.'%')
					   .' OR LOWER(d.filename) LIKE '.$this->_db->Quote('%'.$search.'%')
					   .' OR LOWER(u.name) LIKE '.$this->_db->Quote('%'.$search.'%')
					   .' OR LOWER(u.username) LIKE '.$this->_db->Quote('%'.$search.'%');
		}

		$where 		= ( count( $where ) ? ' WHERE '. implode( ' AND ', $where ) : '' );
		return $where;
	}*/
	
	
	
	function reset($cid = array()) {		
		if (count( $cid )) {
			JArrayHelper::toInteger($cid);
			$cids = implode( ',', $cid );
			$date = gmdate('Y-m-d H:i:s');
			//Delete it from DB
			$query = 'UPDATE '.$this->_db->nameQuote('#__phocadownload_user_stat')
					.' SET count = 0,'
					.' date = '.$this->_db->Quote($date)
					.' WHERE id IN ( '.$cids.' )';
					
			$this->_db->setQuery( $query );
			if(!$this->_db->query()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}
		return true;
	}
}
?>