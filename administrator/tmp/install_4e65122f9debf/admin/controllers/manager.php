<?php
/**
* @package   com_zoo Component
* @file      manager.php
* @version   2.4.9 May 2011
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

/*
	Class: ManagerController
		The controller class for application manager
*/
class ManagerController extends AppController {

	public $group;
	public $application;

	public function __construct($default = array()) {
		parent::__construct($default);
		
		// set base url
		$this->baseurl = $this->app->link(array('controller' => $this->controller), false);

		// get application group
		$this->group = $this->app->request->getString('group');

		// if group exists
		if ($this->group) {

			// add group to base url
			$this->baseurl .= '&group='.$this->group;

			// create application object
			$this->application = $this->app->object->create('Application');
			$this->application->setGroup($this->group);
		}

		// register tasks
		$this->registerTask('addtype', 'edittype');
		$this->registerTask('applytype', 'savetype');
		$this->registerTask('applyelements', 'saveelements');
		$this->registerTask('applyassignelements', 'saveassignelements');
		$this->registerTask('applysubmission', 'savesubmission');
	}

	public function display() {

		// set toolbar items
		$this->app->toolbar->title(JText::_('App Manager'), ZOO_ICON);
		$type = $this->app->joomla->isVersion('1.5') ? 'config' : 'options';
		JToolBar::getInstance('toolbar')->appendButton('Popup', $type, 'Check For Modifications', JRoute::_(JUri::root() . 'administrator/index.php?option='.$this->app->component->self->name.'&controller='.$this->controller.'&task=checkmodifications&tmpl=component', true, -1), 570, 550);
		JToolBar::getInstance('toolbar')->appendButton('Popup', $type, 'Check Requirements', JRoute::_(JUri::root() . 'administrator/index.php?option='.$this->app->component->self->name.'&controller='.$this->controller.'&task=checkrequirements&tmpl=component', true, -1), 570, 550);
		$this->app->toolbar->custom('dobackup', 'archive', 'Archive', 'SQL Dump', false);		
		$this->app->zoo->toolbarHelp();

		// get applications
		$this->applications = $this->app->zoo->getApplicationGroups();
		
		// display view
		$this->getView()->display();
	}

	public function info() {

		// get application metadata
		$metadata = $this->application->getMetaData();

		// set toolbar items
		$this->app->system->application->set('JComponentTitle', $this->application->getToolbarTitle(JText::_('Information').': '.$metadata['name']));
		$this->app->toolbar->custom('doexport', 'archive', 'Archive', 'Export', false);
		$this->app->toolbar->custom('uninstallapplication', 'delete', 'Delete', 'Uninstall', false);
		$this->app->toolbar->deleteList('APP_DELETE_WARNING', 'removeapplication');
		$this->app->zoo->toolbarHelp();

		// get application instances for selected group
		$this->applications = $this->app->application->getApplications($this->application->getGroup());

		// display view
		$this->getView()->setLayout('info')->display();
	}

	public function installApplication() {

		// check for request forgeries
		$this->app->request->checkToken() or jexit('Invalid Token');

		// get the uploaded file information
		$userfile = $this->app->request->getVar('install_package', null, 'files', 'array');

		try {

			$result = $this->app->install->installApplicationFromUserfile($userfile);
			$update = $result == 2 ? 'updated' : 'installed';

			// set redirect message
			$msg = JText::_('Application group '.$update.' successfully.');

		} catch (InstallHelperException $e) {

			// raise notice on exception
			$this->app->error->raiseNotice(0, JText::_('Error installing Application group').' ('.$e.')');
			$msg = null;

		}

		$this->setRedirect($this->baseurl, $msg);
	}

	public function uninstallApplication() {

		// check for request forgeries
		$this->app->request->checkToken() or jexit('Invalid Token');

		try {

			$this->app->install->uninstallApplication($this->application);

			// set redirect message
			$msg = JText::_('Application group uninstalled successful.');
			$link = $this->baseurl;
		} catch (InstallHelperException $e) {

			// raise notice on exception
			$this->app->error->raiseNotice(0, JText::_('Error uninstalling application group').' ('.$e.')');
			$msg = null;
			$link = $this->baseurl.'&task=info';

		}

		$this->setRedirect($link, $msg);
	}

	public function removeApplication() {

		// check for request forgeries
		$this->app->request->checkToken() or jexit('Invalid Token');

		// init vars
		$cid = $this->app->request->get('cid', 'array', array());

		if (count($cid) < 1) {
			$this->app->error->raiseError(500, JText::_('Select a application to delete'));
		}

		try {

			$table = $this->app->table->application;

			// delete applications
			foreach ($cid as $id) {
				$table->delete($table->get($id));
			}

			// set redirect message
			$msg = JText::_('Application Deleted');

		} catch (AppException $e) {

			// raise notice on exception
			$this->app->error->raiseNotice(0, JText::_('Error Deleting Application').' ('.$e.')');
			$msg = null;

		}

		$this->setRedirect($this->baseurl.'&task=info', $msg);
	}

	public function types() {

		// get application metadata
		$metadata = $this->application->getMetaData();

		// set toolbar items
		$this->app->system->application->set('JComponentTitle', $this->application->getToolbarTitle(JText::_('Types').': ' . $metadata['name']));
		$this->app->toolbar->custom('copytype', 'copy', '', 'Copy');
		$this->app->toolbar->deleteList('', 'removetype');
		$this->app->toolbar->editListX('edittype');
		$this->app->toolbar->addNewX('addtype');
		$this->app->zoo->toolbarHelp();

		// get types
		$this->types = $this->application->getTypes();

		// get templates
		$this->templates = $this->application->getTemplates();

		// get modules
		$this->modules = array();
		foreach ($this->app->path->dirs('modules:') as $module) {
			if ($this->app->path->path("modules:$module/renderer")) {
				$this->modules[] = array('name' => $module, 'path' => $this->app->path->path("modules:$module"));
			}
		}

		// get plugins
		$this->plugins = array();
		foreach ($this->app->path->dirs('plugins:') as $plugin_type) {
			foreach ($this->app->path->dirs('plugins:'.$plugin_type) as $plugin) {
				if ($this->app->path->path("plugins:$plugin_type/$plugin/renderer")) {
					$this->plugins[$plugin_type][] = array('name' => $plugin, 'path' => $this->app->path->path("plugins:$plugin_type/$plugin"));
				}
			}
		}

		// display view
		$this->getView()->setLayout('types')->display();
	}

	public function editType() {

		// disable menu
		$this->app->request->setVar('hidemainmenu', 1);

		// get request vars
		$cid  = $this->app->request->get('cid.0', 'string', '');
		$this->edit = $cid ? true : false;

		// get type
		if (empty($cid)) {
			$this->type = $this->app->object->create('Type', array(null, $this->application));
		} else {
			$this->type = $this->application->getType($cid);
		}

		// set toolbar items
		$this->app->system->application->set('JComponentTitle', $this->application->getToolbarTitle(JText::_('Type').': '.$this->type->name.' <small><small>[ '.($this->edit ? JText::_('Edit') : JText::_('New')).' ]</small></small>'));
		$this->app->toolbar->save('savetype');
		$this->app->toolbar->apply('applytype');
		$this->app->toolbar->cancel('types', $this->edit ?	'Close' : 'Cancel');
		$this->app->zoo->toolbarHelp();

		// display view
		$this->getView()->setLayout('edittype')->display();
	}

	public function copyType() {

		// check for request forgeries
		$this->app->request->checkToken() or jexit('Invalid Token');

		// init vars
		$cid = $this->app->request->get('cid', 'array', array());

		if (count($cid) < 1) {
			$this->app->error->raiseError(500, JText::_('Select a type to copy'));
		}

		// copy types
		foreach ($cid as $id) {
			try {

				// get type
				$type = $this->application->getType($id);

				$xml  = $type->getXML();

				// copy type
				$type->id          = null;                      // set id to null, to force new
				$type->identifier .= '-copy';                   // set copied alias
				$this->app->type->setUniqueIndentifier($type);	// set unique identifier
				$type->name       .= sprintf(' (%s)', JText::_('Copy')); // set copied name

				// save copied type
				$type->save();

				// save xml
				$type->setXML($xml)->save();

				// copy template positions
				foreach ($this->app->path->dirs($this->application->getResource().'/templates/') as $template) {
					$this->_copyPositionsConfig($id, $this->app->path->path($this->application->getResource().'/templates/'.$template), $type);
				}

				// copy module positions
				foreach ($this->app->path->dirs('modules:') as $module) {
					if ($this->app->path->path("modules:$module/renderer")) {
						$this->_copyPositionsConfig($id, $this->app->path->path("modules:$module"), $type);
					}
				}

				// copy plugin positions
				foreach ($this->app->path->dirs('plugins:') as $plugin_type) {
					foreach ($this->app->path->dirs('plugins:'.$plugin_type) as $plugin) {
						if ($this->app->path->path("plugins:$plugin_type/$plugin/renderer")) {
							$this->_copyPositionsConfig($id, $this->app->path->path("plugins:$plugin_type/$plugin"), $type);
						}
					}
				}

				$msg = JText::_('Type Copied');

			} catch (AppException $e) {

				// raise notice on exception
				$this->app->error->raiseNotice(0, JText::_('Error Copying Type').' ('.$e.')');
				$msg = null;
				break;

			}
		}

		$this->setRedirect($this->baseurl.'&task=types', $msg);
	}

	protected function _copyPositionsConfig($id, $path, $type) {
		// get renderer
		$renderer = $this->app->renderer->create('item')->addPath($path);

		// rename folder if special type
		if ($renderer->pathExists('item'.DIRECTORY_SEPARATOR.$id)) {
			$folder = $path.DIRECTORY_SEPARATOR.$renderer->getFolder().DIRECTORY_SEPARATOR.'item'.DIRECTORY_SEPARATOR;
			JFolder::copy($folder.$id, $folder.$type->id);
		}

		// get positions and config
		$config = $renderer->getConfig('item');
		$params = $config->get($this->group.'.'.$id.'.');
		$config->set($this->group.'.'.$type->id.'.', $params);
		$renderer->saveConfig($config, $path.'/renderer/item/positions.config');

	}

	public function saveType() {

		// check for request forgeries
		$this->app->request->checkToken() or jexit('Invalid Token');

		// init vars
		$post = $this->app->request->get('post:', 'array', array());
		$cid  = $this->app->request->get('cid.0', 'string', '');

		// get type
		$type = $this->application->getType($cid);

		// type is new ?
		if (!$type) {
			$type = $this->app->object->create('Type', array(null, $this->application));
		}

		// filter identifier
		$post['identifier'] = $this->app->string->sluggify($post['identifier'] == '' ? $post['name'] : $post['identifier']);

		try {

			// set post data and save type
			$type->bind($post);
 			$this->app->type->setUniqueIndentifier($type);

            // clean and save layout positions
            if (!empty($type->id) && $type->id != $type->identifier) {

				// update templates
				foreach ($this->app->path->dirs($this->application->getResource().'/templates/') as $template) {
					$this->_sanatizePositionsConfig($this->app->path->path($this->application->getResource().'/templates/'.$template), $type);
				}

				// update submissions
				$table = $this->app->table->submission;
				$applications = array_keys($this->app->application->getApplications($this->application->getGroup()));
				if (!empty($applications)) {
					$submissions = $table->all(array('conditions' => 'application_id IN ('.implode(',', $applications).')'));
					foreach($submissions as $submission) {
						$params = $submission->getParams();
						if ($tmp = $params->get('form.'.$type->id)) {
							$params->set('form.'.$type->identifier, $tmp)
								->remove('form.'.$type->id);
							$table->save($submission);
						}
					}
				}

				// update modules
				foreach ($this->app->path->dirs('modules:') as $module) {
					if ($this->app->path->path("modules:$module/renderer")) {
						$this->_sanatizePositionsConfig($this->app->path->path("modules:$module"), $type);
					}
				}

				// update plugins
				foreach ($this->app->path->dirs('plugins:') as $plugin_type) {
					foreach ($this->app->path->dirs('plugins:'.$plugin_type) as $plugin) {
						if ($this->app->path->path("plugins:$plugin_type/$plugin/renderer")) {
							$this->_sanatizePositionsConfig($this->app->path->path("plugins:$plugin_type/$plugin"), $type);
						}
					}
				}

            }

            $type->save();

			// set redirect message
			$msg = JText::_('Type Saved');

		} catch (AppException $e) {

			// raise notice on exception
			$this->app->error->raiseNotice(0, JText::_('Error Saving Type').' ('.$e.')');
			$this->_task = 'apply';
			$msg = null;

		}

		switch ($this->getTask()) {
			case 'applytype':
				$link = $this->baseurl.'&task=edittype&cid[]='.$type->id;
				break;
			case 'savetype':
			default:
				$link = $this->baseurl.'&task=types';
				break;
		}

		$this->setRedirect($link, $msg);
	}

	protected function _sanatizePositionsConfig($path, $type) {
		// get renderer
		$renderer = $this->app->renderer->create('item')->addPath($path);

		// rename folder if special type
		if ($renderer->pathExists('item'.DIRECTORY_SEPARATOR.$type->id)) {
			$folder = $path.DIRECTORY_SEPARATOR.$renderer->getFolder().DIRECTORY_SEPARATOR.'item'.DIRECTORY_SEPARATOR;
			JFolder::move($folder.$type->id, $folder.$type->identifier);
		}

		// get positions and config
		$config = $renderer->getConfig('item');
		$params = $config->get($this->group.'.'.$type->id.'.');
		$config->set($this->group.'.'.$type->identifier.'.', $params)
			->remove($this->group.'.'.$type->id.'.');
		$renderer->saveConfig($config, $path.'/renderer/item/positions.config');

	}

	public function removeType() {

		// check for request forgeries
		$this->app->request->checkToken() or jexit('Invalid Token');

		// init vars
		$cid = $this->app->request->get('cid', 'array', array());

		if (count($cid) < 1) {
			$this->app->error->raiseError(500, JText::_('Select a type to delete'));
		}

		foreach ($cid as $id) {
			try {

				// delete type
				$type = $this->application->getType($id);
				$type->delete();

				// update submissions
				$table = $this->app->table->submission;
				$applications = array_keys($this->app->application->getApplications($this->application->getGroup()));
				if (!empty($applications)) {
					$submissions = $table->all(array('conditions' => 'application_id IN ('.implode(',', $applications).')'));
					foreach($submissions as $submission) {
						$submission->getParams()
							->remove('form.'.$id);
						$table->save($submission);
					}
				}

				// set redirect message
				$msg = JText::_('Type Deleted');

			} catch (AppException $e) {

				// raise notice on exception
				$this->app->error->raiseNotice(0, JText::_('Error Deleting Type').' ('.$e.')');
				$msg = null;
				break;

			}
		}

		$this->setRedirect($this->baseurl.'&task=types', $msg);
	}

	public function editElements() {

		// disable menu
		$this->app->request->setVar('hidemainmenu', 1);

		// get request vars
		$cid = $this->app->request->get('cid.0', 'string', '');

		// get type
		$this->type = $this->application->getType($cid);

		// set toolbar items
		$this->app->system->application->set('JComponentTitle', $this->application->getToolbarTitle(JText::_('Type').': '.$this->type->name.' <small><small>[ '.JText::_('Edit elements').' ]</small></small>'));
		$this->app->toolbar->save('saveelements');
		$this->app->toolbar->apply('applyelements');
		$this->app->toolbar->cancel('types', 'Close');
		$this->app->zoo->toolbarHelp();

		// sort elements by group
		$this->elements = array();
		foreach ($this->app->element->getAll($this->application) as $element) {
			$metadata = $element->getMetaData();
			$this->elements[$metadata['group']][$element->getElementType()] = $element;
		}
		ksort($this->elements);
		foreach($this->elements as $group => $elements) {
			ksort($elements);
			$this->elements[$group] = $elements;
		}

		// display view
		$this->getView()->setLayout('editElements')->display();
	}

	public function addElement() {

		// get request vars
		$element = $this->app->request->getWord('element', 'text');

		// load element
		$this->element = $this->app->element->loadElement($element, $this->application);
		$this->element->identifier = $this->app->utility->generateUUID();

		// display view
		$this->getView()->setLayout('addElement')->display();
	}

	public function saveElements() {

		// check for request forgeries
		$this->app->request->checkToken() or jexit('Invalid Token');

		// init vars
		$post = $this->app->request->get('post:', 'array', array());
		$cid  = $this->app->request->get('cid.0', 'string', '');

		try {

			// get type
			$type = $this->application->getType($cid);

			// save elements
			$this->app->element->saveElements($post, $type);

			// save type
			$type->save();

			// reset related item search data
			$table = $this->app->table->item;
			$items = $table->getByType($type->id, $this->application->id);
			foreach ($items as $item) {
				$table->save($item);
			}

			$msg = JText::_('Elements Saved');

		} catch (AppException $e) {

			$this->app->error->raiseNotice(0, JText::_('Error Saving Elements').' ('.$e.')');
			$this->_task = 'applyelements';
			$msg = null;

		}

		switch ($this->getTask()) {
			case 'applyelements':
				$link = $this->baseurl.'&task=editelements&cid[]='.$type->id;
				break;
			case 'saveelements':
			default:
				$link = $this->baseurl.'&task=types';
				break;
		}

		$this->setRedirect($link, $msg);
	}

	public function assignElements() {

		// disable menu
		$this->app->request->setVar('hidemainmenu', 1);

		// init vars
		$type           = $this->app->request->getString('type');
		$this->template = $this->app->request->getString('template');
		$this->module   = $this->app->request->getString('module');
		$this->plugin   = $this->app->request->getString('plugin');
		$this->layout   = $this->app->request->getString('layout');

		// get type
		$this->type = $this->application->getType($type);

        if ($this->type) {
            // set toolbar items
            $this->app->system->application->set('JComponentTitle', $this->application->getToolbarTitle(JText::_('Type').': '.$this->type->name.' <small><small>[ '.JText::_('Assign elements').': '. $this->layout .' ]</small></small>'));
            $this->app->toolbar->save('saveassignelements');
            $this->app->toolbar->apply('applyassignelements');
            $this->app->toolbar->cancel('types');
            $this->app->zoo->toolbarHelp();

            // for template, module, plugin
            if ($this->template) {
                $this->path = $this->application->getPath().'/templates/'.$this->template;
            } else if ($this->module) {
                $this->path = $this->app->path->path('modules:'.$this->module);
            } else if ($this->plugin) {
                $this->path = $this->app->path->path('plugins:'.$this->plugin);
            }

            // get renderer
            $renderer = $this->app->renderer->create('item')->addPath($this->path);

            // get positions and config
            $this->config = $renderer->getConfig('item')->get($this->group.'.'.$type.'.'.$this->layout);

            $prefix = 'item.';
            if ($renderer->pathExists('item'.DIRECTORY_SEPARATOR.$type)) {
                $prefix .= $type.'.';
            }
            $this->positions = $renderer->getPositions($prefix.$this->layout);

            // display view
            $this->getView()->setLayout('assignelements')->display();

        } else {

			$this->app->error->raiseNotice(0, JText::_('Unable find type. ').' ('.$type.')');
			$this->setRedirect($this->baseurl . '&task=types&group=' . $this->application->getGroup());

		}
	}

	public function saveAssignElements() {

		// check for request forgeries
		$this->app->request->checkToken() or jexit('Invalid Token');

		// init vars
		$type      = $this->app->request->getString('type');
		$template  = $this->app->request->getString('template');
		$module    = $this->app->request->getString('module');
		$layout    = $this->app->request->getString('layout');
		$plugin    = $this->app->request->getString('plugin');
		$positions = $this->app->request->getVar('positions', array(), 'post', 'array');

		// unset unassigned position
		unset($positions['unassigned']);

		// for template, module
		if ($template) {
			$path = $this->application->getPath().'/templates/'.$template;
		} else if ($module) {
			$path = $this->app->path->path('modules:'.$module);
		} else if ($plugin) {
			$path = $this->app->path->path('plugins:'.$plugin);
		}

		// get renderer
		$renderer = $this->app->renderer->create('item')->addPath($path);

		// clean config
		$config = $renderer->getConfig('item');
		foreach ($config->toArray() as $key => $value) {
			$parts = explode('.', $key);
			if ($parts[0] == $this->group && !$this->application->getType($parts[1])) {
				$config->remove($key);
			}
		}

		// save config
		$config->set($this->group.'.'.$type.'.'.$layout, $positions);
		$renderer->saveConfig($config, $path.'/renderer/item/positions.config');

		switch ($this->getTask()) {
			case 'applyassignelements':
				$link  = $this->baseurl.'&task=assignelements&type='.$type.'&layout='.$layout;
				$link .= $template ? '&template='.$template : null;
				$link .= $module ? '&module='.$module : null;
				$link .= $plugin ? '&plugin='.$plugin : null;
				break;
			default:
				$link = $this->baseurl.'&task=types';
				break;
		}

		$this->setRedirect($link, JText::_('Elements Assigned'));
	}

	public function assignSubmission() {

		// disable menu
		$this->app->request->setVar('hidemainmenu', 1);

		// init vars
		$type           = $this->app->request->getString('type');
		$this->template = $this->app->request->getString('template');
		$this->layout   = $this->app->request->getString('layout');

		// get type
		$this->type = $this->application->getType($type);

		// set toolbar items
		$this->app->system->application->set('JComponentTitle', $this->application->getToolbarTitle(JText::_('Type').': '.$this->type->name.' <small><small>[ '.JText::_('Assign Submittable elements').': '. $this->layout .' ]</small></small>'));
		$this->app->toolbar->save('savesubmission');
		$this->app->toolbar->apply('applysubmission');
		$this->app->toolbar->cancel('types');
		$this->app->zoo->toolbarHelp();

		// for template, module, plugin
		if ($this->template) {
			$this->path = $this->application->getPath().'/templates/'.$this->template;
		}

		// get renderer
		$renderer = $this->app->renderer->create('item')->addPath($this->path);

		// get positions and config
		$this->config    = $renderer->getConfig('item')->get($this->group.'.'.$type.'.'.$this->layout);

		$prefix = 'item.';
		if ($renderer->pathExists('item'.DIRECTORY_SEPARATOR.$type)) {
			$prefix .= $type.'.';
		}
		$this->positions = $renderer->getPositions($prefix.$this->layout);

		// display view
		$this->getView()->setLayout('assignsubmission')->display();
	}

	public function saveSubmission() {

		// check for request forgeries
		$this->app->request->checkToken() or jexit('Invalid Token');

		// init vars
		$type      = $this->app->request->getString('type');
		$template  = $this->app->request->getString('template');
		$layout    = $this->app->request->getString('layout');
		$positions = $this->app->request->getVar('positions', array(), 'post', 'array');

		// unset unassigned position
		unset($positions['unassigned']);

		// for template, module
		$path = '';
		if ($template) {
			$path = $this->application->getPath().'/templates/'.$template;
		}

		// get renderer
		$renderer = $this->app->renderer->create('item')->addPath($path);

		// clean config
		$config = $renderer->getConfig('item');
		foreach ($config->toArray() as $key => $value) {
			$parts = explode('.', $key);
			if ($parts[0] == $this->group && !$this->application->getType($parts[1])) {
				$config->remove($key);
			}
		}

		// save config
		$config->set($this->group.'.'.$type.'.'.$layout, $positions);
		$renderer->saveConfig($config, $path.'/renderer/item/positions.config');

		switch ($this->getTask()) {
			case 'applysubmission':
				$link  = $this->baseurl.'&task=assignsubmission&type='.$type.'&layout='.$layout;
				$link .= $template ? '&template='.$template : null;
				break;
			default:
				$link = $this->baseurl.'&task=types';
				break;
		}

		$this->setRedirect($link, JText::_('Submitable Elements Assigned'));
	}

	public function doExport() {

		// check for request forgeries
		$this->app->request->checkToken() or jexit('Invalid Token');

		$filepath = $this->app->path->path("tmp:").'/'.$this->application->getGroup().'.zip';
		$read_directory = $this->application->getPath() . '/';
		$zip = $this->app->archive->open($filepath, 'zip');
		$files = $this->app->path->files($this->application->getResource(), true);
		$files = array_map(create_function('$file', 'return "'.$read_directory.'".$file;'), $files);
		$zip->create($files, PCLZIP_OPT_ADD_PATH, '../', PCLZIP_OPT_REMOVE_PATH, $read_directory);
		if (is_readable($filepath) && JFile::exists($filepath)) {
			$this->app->filesystem->output($filepath);
			if (!JFile::delete($filepath)) {
				$this->app->error->raiseNotice(0, JText::_('Unable to delete file').' ('.$filepath.')');
				$this->setRedirect($this->baseurl.'&task=info');
			}
		} else {
			$this->app->error->raiseNotice(0, JText::_('Unable to create file').' ('.$filepath.')');
			$this->setRedirect($this->baseurl.'&task=info');
		}

	}

    public function checkRequirements() {

		$this->app->loader->register('AppRequirements', 'installation:requirements.php');

        $requirements = $this->app->object->create('AppRequirements');
        $requirements->checkRequirements();
        $requirements->displayResults();

    }

    public function checkModifications() {

		// add system.css for
		$this->app->document->addStylesheet("root:administrator/templates/system/css/system.css");

		try {

			$this->results = $this->app->modifications->check();

			// display view
			$this->getView()->setLayout('modifications')->display();

		} catch (AppModificationsException $e) {

			$this->app->error->raiseNotice(0, $e);

		}

    }

    public function cleanModifications() {

		// check for request forgeries
		$this->app->request->checkToken() or jexit('Invalid Token');

		try {

			$this->app->modifications->clean();
			$msg = JText::_('Unknown files removed.');

		} catch (AppModificationsException $e) {

			$msg = JText::_('Error cleaning ZOO.');
			$this->app->error->raiseNotice(0, $e);

		}

		$route = JRoute::_($this->baseurl.'&task=checkmodifications&tmpl=component', false);
		$this->setRedirect($route, $msg);

    }

    public function verify() {

		$result = false;
		
		try {

			$result = $this->app->modifications->verify();

		} catch (AppModificationsException $e) {}

		echo json_encode(compact('result'));

    }

	public function doBackup() {

		if ($result = $this->app->backup->all()) {

			$result = $this->app->backup->generateHeader() . $result;

			$file = $this->app->path->path('tmp:').'/zoo-db-backup-'.time().'-'.(md5(implode(',', $this->app->backup->getTables()))).'.sql';
			if (JFile::write($file, $result)) {
				$this->app->filesystem->output($file);
				return;
			}
		}

		// raise error on exception
		$this->app->error->raiseNotice(0, JText::_('Error Creating Backup'));
		$this->setRedirect($this->baseurl);

	}

	public function restoreBackup() {

		// check for request forgeries
		$this->app->request->checkToken() or jexit('Invalid Token');

		// get the uploaded file information
		$userfile = $this->app->request->getVar('backupfile', null, 'files', 'array');

		try {

			$file = $this->app->validator->create('file')->clean($userfile);

			$this->app->backup->restore($file['tmp_name']);
			$msg = JText::_('Database backup successfully restored');

		} catch (AppValidatorException $e) {

			$msg = '';
			$this->app->error->raiseNotice(0, "Error uploading backup file. ($e)");

		} catch (BackupException $e) {

			$msg = '';
			$this->app->error->raiseNotice(0, "Error restoring ZOO backup. ($e)");

		}

		$this->setRedirect($this->baseurl, $msg);

	}

}

/*
	Class: ManagerControllerException
*/
class ManagerControllerException extends AppException {}