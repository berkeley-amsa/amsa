<?php
/**
* @package   com_zoo Component
* @file      item.php
* @version   2.4.9 May 2011
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

/*
	Class: ItemController
		The controller class for item
*/
class ItemController extends AppController {

	public $application;
	
	const MAX_MOST_USED_TAGS = 8;
	
	public function __construct($default = array()) {
		parent::__construct($default);

		// set table
		$this->table = $this->app->table->item;

		// get application
		$this->application 	= $this->app->zoo->getApplication();

		// set base url
		$this->baseurl = $this->app->link(array('controller' => $this->controller), false);

		// set user
		$this->user = $this->app->user->get();

		// register tasks
		$this->registerTask('element', 'display');
		$this->registerTask('apply', 'save');
		$this->registerTask('saveandnew', 'save' );
	}

	public function display() {

		// get app from Request (currently used in zooapplication element)
		if ($id = $this->app->request->getInt('app_id')) {
			$this->application = $this->app->table->application->get($id);
		}

		// get database
		$this->db = $this->app->database;

		// get Joomla application
		$this->joomla = $this->app->system->application;

		// set toolbar items
		$this->joomla->set('JComponentTitle', $this->application->getToolbarTitle(JText::_('Items')));
		$this->app->toolbar->publishList();
		$this->app->toolbar->unpublishList();
		$this->app->toolbar->custom('docopy', 'copy.png', 'copy_f2.png', 'Copy');
		$this->app->toolbar->deleteList();
		$this->app->toolbar->editListX();
		$this->app->toolbar->addNewX();
		$this->app->zoo->toolbarHelp();

		$this->app->html->_('behavior.tooltip');

		// get request vars
		$this->filter_item	= $this->app->request->getInt('item_filter', 0);
		$this->type_filter	= $this->app->request->get('type_filter', 'array', array());
		$state_prefix       = $this->option.'_'.$this->application->id.'.'.($this->getTask() == 'element' ? 'element' : 'item').'.';
		$filter_order	    = $this->joomla->getUserStateFromRequest($state_prefix.'filter_order', 'filter_order', 'a.created', 'cmd');
		$filter_order_Dir   = $this->joomla->getUserStateFromRequest($state_prefix.'filter_order_Dir', 'filter_order_Dir', 'desc', 'word');
		$filter_category_id = $this->joomla->getUserStateFromRequest($state_prefix.'filter_category_id', 'filter_category_id', '-1', 'string');
		$limit		        = $this->joomla->getUserStateFromRequest('global.list.limit', 'limit', $this->joomla->getCfg('list_limit'), 'int');
		$limitstart			= $this->joomla->getUserStateFromRequest($state_prefix.'limitstart', 'limitstart', 0,	'int');
		$filter_type     	= $this->joomla->getUserStateFromRequest($state_prefix.'filter_type', 'filter_type', '', 'string');
		$filter_author_id   = $this->joomla->getUserStateFromRequest($state_prefix.'filter_author_id', 'filter_author_id', 0, 'int');
		$search	            = $this->joomla->getUserStateFromRequest($state_prefix.'search', 'search', '', 'string');
		$search			    = $this->app->string->strtolower($search);

		// is filtered ?
		$this->is_filtered = $filter_category_id <> '-1' || !empty($filter_type) || !empty($filter_author_id) || !empty($search);

		// in case limit has been changed, adjust limitstart accordingly
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

		$this->users  = $this->table->getUsers($this->application->id);
		$this->groups = $this->app->zoo->getGroups();

		// select
		$select = 'a.*';

		// get from
		$from = $this->table->name.' AS a';

		// get data from the table
		$where = array();

		// application filter
		$where[] = 'a.application_id = ' . (int) $this->application->id;

		// category filter
		if ($filter_category_id > -1) {
			$select  = 'DISTINCT a.*';
			$from   .= ' LEFT JOIN '.ZOO_TABLE_CATEGORY_ITEM.' AS ci ON a.id = ci.item_id';
			$where[] = 'ci.category_id = ' . (int) $filter_category_id;
		} else if ($filter_category_id === '') {
			$from   .= ' LEFT JOIN '.ZOO_TABLE_CATEGORY_ITEM.' AS ci ON a.id = ci.item_id';
			$where[] = 'ci.item_id IS NULL';
        }

		// type filter
		if (!empty($this->type_filter)) {
			$where[] = 'a.type IN ("' . implode('", "', $this->type_filter) . '")';
		} else if (!empty($filter_type)) {
			$where[] = 'a.type = "' . (string) $filter_type . '"';
		}

		// item filter
		if ($this->filter_item > 0) {
			$where[] = 'a.id != ' . (int) $this->filter_item;
		}

		// author filter
		if ($filter_author_id > 0) {
			$where[] = 'a.created_by = ' . (int) $filter_author_id;
		}

		if ($search) {
			$where[] = 'LOWER(a.name) LIKE '.$this->db->Quote('%'.$this->db->getEscaped($search, true).'%', false);
		}

		$options = array(
			'select' => $select,
			'from' =>  $from,
			'conditions' => array(implode(' AND ', $where)),
			'order' => $filter_order.' '.$filter_order_Dir);

		$count = $this->table->count($options);
		$limitstart = $limitstart > $count ? floor($count / $limit) * $limit : $limitstart;

		$this->items = $this->table->all($limit > 0 ? array_merge($options, array('offset' => $limitstart, 'limit' => $limit)) : $options);
		$this->items = array_merge($this->items);

		$this->pagination = $this->app->pagination->create($count, $limitstart, $limit);
		
		// category select
		$options = array();
        $options[] = $this->app->html->_('select.option', '-1', '- ' . JText::_('Select Category') . ' -');
        $options[] = $this->app->html->_('select.option', '', '- ' . JText::_('uncategorized') . ' -');
		$options[] = $this->app->html->_('select.option', '0', '- '.JText::_('Frontpage'));
		$this->lists['select_category'] = $this->app->html->_('zoo.categorylist', $this->application, $options, 'filter_category_id', 'class="inputbox auto-submit"', 'value', 'text', $filter_category_id);

		// type select
		$options = array($this->app->html->_('select.option', '0', '- '.JText::_('Select Type').' -'));
		$this->lists['select_type'] = $this->app->html->_('zoo.typelist', $this->application, $options, 'filter_type', 'class="inputbox auto-submit"', 'value', 'text', $filter_type, false, false, $this->type_filter);

		// author select
		$options = array($this->app->html->_('select.option', '0', '- '.JText::_('Select Author').' -'));
		$this->lists['select_author'] = $this->app->html->_('zoo.itemauthorlist',  $options, 'filter_author_id', 'class="inputbox auto-submit"', 'value', 'text', $filter_author_id);

		// table ordering and search filter
		$this->lists['order_Dir'] = $filter_order_Dir;
		$this->lists['order']	  = $filter_order;
		$this->lists['search']    = $search;

		// display view
		$layout = $this->getTask() == 'element' ? 'element' : 'default';
		$this->getView()->setLayout($layout)->display();
	}
	
	public function loadtags() {

		// get request vars
		$tag = $this->app->request->getString('tag', '');

		echo $this->app->tag->loadTags($this->application->id, $tag);

	}	
	
	public function add() {

		// get Joomla application
		$this->joomla = $this->app->system->application;

		// set toolbar items
		$this->joomla->set('JComponentTitle', $this->application->getToolbarTitle(JText::_('Item') .': <small><small>[ '.JText::_('New').' ]</small></small>'));
		$this->app->toolbar->cancel();
		
		// get types
		$this->types = $this->application->getTypes();
		
		// no types available ?
		if (count($this->types) == 0) {
			$this->app->error->raiseNotice(0, JText::_('Please create a type first.'));
			$this->joomla->redirect($this->link_base.'&controller=manager');
		}
		
		// only one type ? then skip type selection
		if (count($this->types) == 1) {
			$type = array_shift($this->types);
			$this->joomla->redirect($this->baseurl.'&task=edit&type='.$type->id);
		}

		// display view
		$this->getView()->setLayout('add')->display();
	}

	public function edit($tpl = null) {

		// disable menu
		$this->app->request->setVar('hidemainmenu', 1);

		// get request vars
		$cid  = $this->app->request->get('cid.0', 'int');
		$edit = $cid > 0;

		// get item
		if ($edit) {
			if (!$this->item = $this->app->table->item->get($cid)) {
				$this->app->error->raiseError(500, JText::sprintf('Unable to access item with id %s', $cid));
				return;
			}
		} else {
			$this->item = $this->app->object->create('Item');
			$this->item->application_id = $this->application->id;
			$this->item->type = $this->app->request->getVar('type');
			$this->item->publish_down = $this->app->database->getNullDate();
			$this->item->access = $this->app->joomla->getDefaultAccess();
		}

		// get item params
		$this->params = $this->item->getParams();

		// set toolbar items
		$this->app->system->application->set('JComponentTitle', $this->application->getToolbarTitle(JText::_('Item').': '.$this->item->name.' <small><small>[ '.($edit ? JText::_('Edit') : JText::_('New')).' ]</small></small>'));
		$this->app->toolbar->save();
		$this->app->toolbar->custom('saveandnew', 'saveandnew', 'saveandnew', 'Save  New', false);
		$this->app->toolbar->apply();
		$this->app->toolbar->cancel('cancel', $edit ? 'Close' : 'Cancel');
		$this->app->zoo->toolbarHelp();

		// published select
		$this->lists['select_published'] = $this->app->html->_('select.booleanlist', 'state', null, $this->item->state);
		
		// published searchable
		$this->lists['select_searchable'] = $this->app->html->_('select.booleanlist', 'searchable', null, $this->item->searchable);

		// categories select
		$related_categories = $this->item->getRelatedCategoryIds();
		$this->lists['select_frontpage']  = $this->app->html->_('select.booleanlist', 'frontpage', null, in_array(0, $related_categories));
		$this->lists['select_categories'] = count($this->application->getCategoryTree()) > 1 ? 
				$this->app->html->_('zoo.categorylist', $this->application, array(), 'categories[]', 'size="15" multiple="multiple"', 'value', 'text', $related_categories)
				: '<a href="'.$this->app->link(array('controller' => 'category'), false).'" >'.JText::_('Please add categories first').'</a>';
		$this->lists['select_primary_category'] = $this->app->html->_('zoo.categorylist', $this->application, array($this->app->html->_('select.option', '', JText::_('COM_ZOO_NONE'))), 'params[primary_category]', '', 'value', 'text', $this->params->get('config.primary_category'));

		// most used tags
		$this->lists['most_used_tags'] = $this->app->table->tag->getAll($this->application->id, null, null, 'items DESC, a.name ASC', null, self::MAX_MOST_USED_TAGS);
		
		// comments enabled select
		$this->lists['select_enable_comments'] = $this->app->html->_('select.booleanlist', 'params[enable_comments]', null, $this->params->get('config.enable_comments', 1));
		
		// display view
		$this->getView()->setLayout('edit')->display();
	}	

	public function save() {

		// check for request forgeries
		$this->app->request->checkToken() or jexit('Invalid Token');
		
		// init vars
		$now        = $this->app->date->create();
		$post       = $this->app->request->get('post:', 'array');
		$frontpage  = $this->app->request->getBool('frontpage', false);
		$categories	= $this->app->request->get('categories', null);
		$details	= $this->app->request->get('details', null);
		$metadata   = $this->app->request->get('meta', null);
		$cid        = $this->app->request->get('cid.0', 'int');
		$tzoffset   = $this->app->date->getOffset();
		$post       = array_merge($post, $details);
				
		try {

			// get item
			if ($cid) {
				$item = $this->table->get($cid);
			} else {
				$item = $this->app->object->create('Item');
				$item->application_id = $this->application->id;
				$item->type = $this->app->request->getVar('type');
			}
			
			// bind item data
			$this->bind($item, $post, array('elements', 'params', 'created_by'));
            $created_by = isset($post['created_by']) ? $post['created_by'] : '';
            $item->created_by = empty($created_by) ? $this->app->user->get()->id : $created_by == 'NO_CHANGE' ? $item->created_by : $created_by;
			$tags = isset($post['tags']) ? $post['tags'] : array();
			$item->setTags($tags);

			// bind element data
			foreach ($item->getElements() as $id => $element) {
				if (isset($post['elements'][$id])) {
					$element->bindData($post['elements'][$id]);
				} else {
					$element->bindData();
				}
			}

			// set alias
			$item->alias = $this->app->item->getUniqueAlias($item->id, $this->app->string->sluggify($item->alias));

			// set modified
			$item->modified	   = $now->toMySQL();
			$item->modified_by = $this->user->get('id');

			// set created date
			if ($item->created && strlen(trim($item->created)) <= 10) {
				$item->created .= ' 00:00:00';
			}
			$date = $this->app->date->create($item->created, $tzoffset);
			$item->created = $date->toMySQL();
	
			// set publish up date
			if (strlen(trim($item->publish_up)) <= 10) {
				$item->publish_up .= ' 00:00:00';
			}
			$date = $this->app->date->create($item->publish_up, $tzoffset);
			$item->publish_up = $date->toMySQL();
	
			// set publish down date
			if (trim($item->publish_down) == JText::_('Never') || trim($item->publish_down) == '') {
				$item->publish_down = $this->app->database->getNullDate();
			} else {
				if (strlen(trim($item->publish_down)) <= 10) {
					$item->publish_down .= ' 00:00:00';
				}
				$date = $this->app->date->create($item->publish_down, $tzoffset);
				$item->publish_down = $date->toMySQL();
			}

			// get primary category
			$primary_category = @$post['params']['primary_category'];
			if (empty($primary_category) && count($categories)) {
				$primary_category = $categories[0];
			}

			// set params
			$item->getParams()
				->remove('metadata.')
				->remove('template.')
				->remove('content.')
				->remove('config.')
				->set('metadata.', @$post['params']['metadata'])
				->set('template.', @$post['params']['template'])
				->set('content.', @$post['params']['content'])
				->set('config.', @$post['params']['config'])
				->set('config.enable_comments', @$post['params']['enable_comments'])
				->set('config.primary_category', $primary_category);			
	
			// save item		
			$this->table->save($item);

			// make sure categories contain primary category
			if (!empty($primary_category) && !in_array($primary_category, $categories)) {
				$categories[] = $primary_category;
			}

			// save category relations
			if ($frontpage) {
				$categories[] = 0;
			}
			$this->app->category->saveCategoryItemRelations($item->id, $categories);

			// set redirect message
			$msg = JText::_('Item Saved');

		} catch (AppException $e) {

			// raise notice on exception
			$this->app->error->raiseNotice(0, JText::_('Error Saving Item').' ('.$e.')');
			$this->_task = 'apply';
			$msg = null;

		}
		
		$link = $this->baseurl;
		switch ($this->getTask()) {
			case 'apply' :
				$link .= '&task=edit&type='.$item->type.'&cid[]='.$item->id;
				break;
			case 'saveandnew' :
				$link .= '&task=add';
				break;
		}		

		$this->setRedirect($link, $msg);
	}

	public function docopy() {

		// check for request forgeries
		$this->app->request->checkToken() or jexit('Invalid Token');

		// init vars
		$now  = $this->app->date->create();
		$post = $this->app->request->get('post:', 'array');
		$cid  = $this->app->request->get('cid', 'array', array());

		if (count($cid) < 1) {
			$this->app->error->raiseError(500, JText::_('Select a item to copy'));
		}

		try {

			// copy items
			foreach ($cid as $id) {
				
				// get item
				$item       = $this->table->get($id);
				$elements   = $item->getElements();
				$categories = $item->getRelatedCategoryIds();
				
				// copy item
				$item->id          = 0;                         						// set id to 0, to force new item
				$item->name       .= ' ('.JText::_('Copy').')'; 						// set copied name
				$item->alias       = $this->app->item->getUniqueAlias($id, $item->alias.'-copy'); 	// set copied alias	
				$item->state       = 0;                         						// unpublish item
				$item->created	   = $now->toMySQL();
				$item->created_by  = $this->user->get('id');
				$item->modified	   = $now->toMySQL();
				$item->modified_by = $this->user->get('id');
				$item->hits		   = 0;

				// copy tags
				$item->setTags($this->app->table->tag->getItemTags($id));
				
				// save copied item/element data
				$this->table->save($item);
				
				// save category relations
				$this->app->category->saveCategoryItemRelations($item->id, $categories);
			}

			// set redirect message
			$msg = JText::_('Item Copied');

		} catch (AppException $e){

			// raise notice on exception
			$this->app->error->raiseNotice(0, JText::_('Error Copying Item').' ('.$e.')');
			$msg = null;

		}

		$this->setRedirect($this->baseurl, $msg);
	}

	public function remove() {

		// check for request forgeries
		$this->app->request->checkToken() or jexit('Invalid Token');

		// init vars
		$cid = $this->app->request->get('cid', 'array', array());

		if (count($cid) < 1) {
			$this->app->error->raiseError(500, JText::_('Select a item to delete'));
		}
		
		try {		

			// delete items
			foreach ($cid as $id) {
				$this->table->delete($this->table->get($id));
			}

			// set redirect message
			$msg = JText::_('Item Deleted');

		} catch (AppException $e) {

			// raise notice on exception
			$this->app->error->raiseWarning(0, JText::_('Error Deleting Item').' ('.$e.')');
			$msg = null;

		}

		$this->setRedirect($this->baseurl, $msg);
	}

	public function savepriority() {

		// check for request forgeries
		$this->app->request->checkToken() or jexit('Invalid Token');

		// init vars
		$msg      = JText::_('Order Priority saved');
		// init vars
		$priority = $this->app->request->get('priority', 'array', array());

		try {

			// update the priority for items
			foreach ($priority as $id => $value) {
				$item = $this->table->get((int) $id);

				// only update, if changed
				if ($item->priority != $value) {
					$item->priority = $value;
					$this->table->save($item);
				}
			}

			// set redirect message
			$msg = json_encode(array(
				'group' => 'info',
				'title' => JText::_('Success!'),
				'text'  => JText::_('Item Priorities Saved')));

		} catch (AppException $e) {

			// raise error on exception
			$msg = json_encode(array(
				'group' => 'error',
				'title' => JText::_('Error!'),
				'text'  => JText::_('Error editing item priority').' ('.$e.')'));

		}

		echo $msg;
	}
	
	public function resethits() {

		// check for request forgeries
		$this->app->request->checkToken() or jexit('Invalid Token');

		// init vars
		$msg = null;
		$cid = $this->app->request->get('cid.0', 'int');

		try {
						
			// get item
			$item = $this->table->get($cid);
	
			// reset hits
			if ($item->hits > 0) {
				$item->hits = 0;
				
				// save item
				$this->table->save($item);

				// set redirect message
				$msg = JText::_('Item Hits Reseted');
			}			

		} catch (AppException $e) {

			// raise notice on exception
			$this->app->error->raiseNotice(0, JText::_('Error Reseting Item Hits').' ('.$e.')');
			$msg = null;

		}

		$this->setRedirect($this->baseurl.'&task=edit&cid[]='.$item->id, $msg);
	}

	public function publish() {
		$this->_editState(1);
	}

	public function unpublish() {
		$this->_editState(0);
	}

	public function makeSearchable() {
		$this->_editSearchable(1);
	}

	public function makeNoneSearchable() {
		$this->_editSearchable(0);
	}
	
	public function enableComments() {
		$this->_editComments(1);
	}

	public function disableComments() {
		$this->_editComments(0);
	}

	protected function _editState($state) {

		// check for request forgeries
		$this->app->request->checkToken() or jexit('Invalid Token');

		// init vars
		$cid = $this->app->request->get('cid', 'array', array());

		if (count($cid) < 1) {
			$this->app->error->raiseError(500, JText::_('Select an item to edit publish state'));
		}
		
		try {

			// update item state
			foreach ($cid as $id) {
				$this->table->get($id)->setState($state, true);
			}

		} catch (AppException $e) {

			// raise notice on exception
			$this->app->error->raiseNotice(0, JText::_('Error editing Item Published State').' ('.$e.')');

		}

		$this->setRedirect($this->baseurl);
	}

	protected function _editSearchable($searchable) {

		// check for request forgeries
		$this->app->request->checkToken() or jexit('Invalid Token');

		// init vars
		$cid = $this->app->request->get('cid', 'array', array());

		if (count($cid) < 1) {
			$this->app->error->raiseError(500, JText::_('Select an item to edit searchable state'));
		}
		
		try {

			// update item searchable
			foreach ($cid as $id) {
				$item = $this->table->get($id);
				$item->searchable = $searchable;
				$this->table->save($item);
			}

		} catch (AppException $e) {

			// raise notice on exception
			$this->app->error->raiseNotice(0, JText::_('Error editing Item Searchable State').' ('.$e.')');

		}

		$this->setRedirect($this->baseurl);
	}
	
	protected function _editComments($enabled) {

		// check for request forgeries
		$this->app->request->checkToken() or jexit('Invalid Token');

		// init vars
		$cid = $this->app->request->get('cid', 'array', array());

		if (count($cid) < 1) {
			$this->app->error->raiseError(500, JText::_('Select an item to enable/disable comments'));
		}
		
		try {

			// update item comments
			foreach ($cid as $id) {
				$item = $this->table->get($id);

				$item->params = $item
					->getParams()
					->set('config.enable_comments', $enabled);

				$this->table->save($item);
			}

		} catch (AppException $e) {

			// raise notice on exception
			$this->app->error->raiseNotice(0, JText::_('Error enabling/disabling Item Comments').' ('.$e.')');

		}

		$this->setRedirect($this->baseurl);
	}	
	
	public function callElement() {
		
		// get request vars		
		$element_identifier = $this->app->request->getString('elm_id', '');
		$item_id			= $this->app->request->getInt('item_id', 0);
		$type	 			= $this->app->request->getString('type', '');
		$this->method 		= $this->app->request->getCmd('method', '');
		$this->args       	= $this->app->request->getVar('args', array(), 'default', 'array');
		
		JArrayHelper::toString($this->args);
						
		// load element
		if ($item_id) {
			$item = $this->table->get($item_id);
		} elseif (!empty($type)){
			$item = $this->app->object->create('Item');
			$item->application_id = $this->application->id;
			$item->type = $type;
		}
		
		// execute callback method
		if ($element = $item->getElement($element_identifier)) {
			echo $element->callback($this->method, $this->args);
		}		
		
	}		

}

/*
	Class: ItemControllerException
*/
class ItemControllerException extends AppException {}