<?php

/**
 * @version		$Id$
 * @package		Joomla
 * @subpackage	Joomla 1.6.x adapter
 * @author      ARTIO s.r.o., info@artio.net, http:://www.artio.net
 * @copyright	Copyright (C) 2011 Artio s.r.o. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die();

class J16Controller {

    /**
     * Get controller instance.
     *
     * @return JController
     */
    function getInstance () {

        $mainframe =& JFactory::getApplication();
        /* @var $mainframe JApplication */

        // controller name from request property controller or as part of value task
        $controller = JRequest::getString('controller');
        $task = JRequest::getString('task');
        
        if (!$controller) {
            // in Joomla 1.6.x controller part of task property before dot
            $dot = JString::strpos($task, '.');
            if ($dot !== false) {
                // parse controller and task value into Joomla 1.5.x
                $controller = JString::substr($task, 0, $dot);
                $task = JString::substr($task, $dot + 1);
                // set Joomla 1.5.x task format in request
                JRequest::setVar('task', $task);
            }
        }

        // set path to controller to backend and frontend part
        if (!$controller) {
            // use default controller in component root
            $adminController = JPATH_COMPONENT_ADMINISTRATOR . DS . 'controller.php';
            $siteController = JPATH_COMPONENT_SITE . DS . 'controller.php';
        } else {
            // use concrete controller from folder controllers
            $adminController = JPATH_COMPONENT_ADMINISTRATOR . DS . 'controllers' . DS . $controller . '.php';
            $siteController = JPATH_COMPONENT_SITE . DS . 'controllers' . DS . $controller . '.php';
        }

        // get component name from option parameter
        $option = JRequest::getString('option');
        $name = JString::substr($option, 4);

        // controller classname
        $className = $name . 'controller' . $controller;

        // register controllers classnames
        if ($mainframe->isSite() && JFile::exists($siteController)) {
            // display frontend and frontend has own controllers
            JLoader::register($className, $siteController);
        } elseif ($mainframe->isAdmin() || JFile::exists($adminController)) {
            // display backend or controllers are only in backend
            JLoader::register($className, $adminController);
        }

        $instance = new $className;

        return $instance;

    }

    /**
     * Method to check whether an ID is in the edit list.
     *
     * @param	string	$context	The context for the session storage.
     * @param	int		$id			The ID of the record to add to the edit list.
     *
     * @return	boolean	True if the ID is in the edit list.
     * @since	1.6
     */
    function checkEditId ($context, $id, &$instance) {
        if (method_exists($instance, 'checkEditId')) {
            return $instance->checkEditId($context, $id);
        } else {
            if ($id) {
                $app = JFactory::getApplication();
                $values = (array) $app->getUserState($context . '.id');

                $result = in_array((int) $id, $values);

                if (JDEBUG) {
                    jimport('joomla.error.log');
                    $log = JLog::getInstance('jcontroller.log.php')->addEntry(array('comment' => sprintf('Checking edit ID %s.%s: %d %s', $context, $id, (int) $result, str_replace("\n", ' ', print_r($values, 1)))));
                }

                return $result;
            } else {
                // No id for a new item.
                return true;
            }
        }
    }

    /**
     * Method to check whether an ID is in the edit list.
     *
     * @param	string	$context	The context for the session storage.
     * @param	int		$id			The ID of the record to add to the edit list.
     *
     * @return	void
     * @since	1.6
     */
    function releaseEditId ($context, $id, &$instance) {
        if (method_exists($instance, 'releaseEditId')) {
            $instance->releaseEditId($context, $id);
        } else {
            $app = JFactory::getApplication();
            $values = (array) $app->getUserState($context . '.id');

            // Do a strict search of the edit list values.
            $index = array_search((int) $id, $values, true);

            if (is_int($index)) {
                unset($values[$index]);
                $app->setUserState($context . '.id', $values);

                if (JDEBUG) {
                    jimport('joomla.error.log');
                    $log = JLog::getInstance('jcontroller.log.php')->addEntry(array('comment' => sprintf('Releasing edit ID %s.%s %s', $context, $id, str_replace("\n", ' ', print_r($values, 1)))));
                }
            }
        }
    }

    /**
     * Method to add a record ID to the edit list.
     *
     * @param	string	$context	The context for the session storage.
     * @param	int		$id			The ID of the record to add to the edit list.
     *
     * @return	void
     * @since	1.6
     */
    function holdEditId ($context, $id, &$instance) {
        if (method_exists($instance, 'holdEditId')) {
            $instance->holdEditId($context, $id);
        }
        // Initialise variables.
        $app = JFactory::getApplication();
        $values = (array) $app->getUserState($context . '.id');

        // Add the id to the list if non-zero.
        if (!empty($id)) {
            array_push($values, (int) $id);
            $values = array_unique($values);
            $app->setUserState($context . '.id', $values);

            if (JDEBUG) {
                jimport('joomla.error.log');
                $log = JLog::getInstance('jcontroller.log.php')->addEntry(array('comment' => sprintf('Holding edit ID %s.%s %s', $context, $id, str_replace("\n", ' ', print_r($values, 1)))));
            }
        }
    }
}
?>