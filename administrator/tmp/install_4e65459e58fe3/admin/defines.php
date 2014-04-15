<?php

/**
 * @version		$Id$
 * @package		Joomla.Administrator
 * @subpackage	JoomDOC
 * @author      ARTIO s.r.o., info@artio.net, http:://www.artio.net
 * @copyright	Copyright (C) 2011 Artio s.r.o. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die();

if (!defined('JPATH_COMPONENT_ADMINISTRATOR')) {
    define('JPATH_COMPONENT_ADMINISTRATOR', JPATH_ROOT . '/administrator/components/com_joomdoc');
}
if (!defined('JPATH_COMPONENT_SITE')) {
    define('JPATH_COMPONENT_SITE', JPATH_ROOT . '/components/com_joomdoc');
}

// main
define('JOOMDOC', 'JoomDOC');
define('JOOMDOC_LOG', false);
define('JOOMDOC_OPTION', 'com_joomdoc');
define('JOOMDOC_ACCESS_PREFIX', 'JoomDOCAccess');
define('JOOMDOC_HELPER_PREFIX', 'JoomDOC');
define('JOOMDOC_MODEL_PREFIX', 'JoomDOCModel');
define('JOOMDOC_TABLE_PREFIX', 'JoomDOCTable');
define('JOOMDOC_MANIFEST', JPATH_COMPONENT_ADMINISTRATOR . DS . 'joomdoc.xml');
define('JOOMDOC_CONFIG', JPATH_COMPONENT_ADMINISTRATOR . DS . 'config.xml');
define('JOOMDOC_PARAMS_WINDOW_HEIGHT', 600);
define('JOOMDOC_PARAMS_WINDOW_WIDTH', 800);
define('JOOMDOC_VERSION_DIR', '.versions');
define('JOOMDOC_URL_FEATURES', 'http://www.artio.net/joomdoc/features');
define('JOOMDOC_URL_ESHOP', 'http://www.artio.net/e-shop/joomdoc');

//upgrade
define('ARTIO_UPGRADE_MANIFEST', JPATH_COMPONENT_ADMINISTRATOR . DS . 'joomdoc.xml');
define('ARTIO_UPGRADE_NEWEST_VERSION_URL', 'http://www.artio.cz/updates/joomla/joomdoc/version');
define('ARTIO_UPGRADE_LICENSE_URL', 'http://www.artio.net/license-check');
define('ARTIO_UPGRADE_UPGRADE_URL', 'http://www.artio.net/joomla-auto-upgrade');
define('ARTIO_UPGRADE_OPTION', 'com_joomdoc');
define('ARTIO_UPGRADE_CAT', 'joomdoc');

 define('ARTIO_UPGRADE_OPTION_PCKG', 'com_joomdoc3_std');
 

//folders
define('JOOMDOC_CONTROLLERS', JPATH_COMPONENT_ADMINISTRATOR . DS . 'controllers');
define('JOOMDOC_TABLES', JPATH_COMPONENT_ADMINISTRATOR . DS . 'tables');
define('JOOMDOC_HELPERS', JPATH_COMPONENT_ADMINISTRATOR . DS . 'libraries' . DS . 'joomdoc');
define('JOOMDOC_ACCESS', JPATH_COMPONENT_ADMINISTRATOR . DS . 'libraries' . DS . 'joomdoc' . DS . 'access');
define('JOOMDOC_MODELS', JPATH_COMPONENT_ADMINISTRATOR . DS . 'models');
define('JOOMDOC_PATH_IMAGES', JPATH_COMPONENT_SITE . DS . 'assets' . DS . 'images');
define('JOOMDOC_PATH_ICONS', JPATH_SITE . DS . 'components' . DS . JOOMDOC_OPTION . DS . 'assets' . DS . 'images' . DS . 'icons');
define('JOOMDOC_BUTTONS', JPATH_COMPONENT_ADMINISTRATOR . DS . 'libraries' . DS . 'joomla' . DS . 'html' . DS . 'toolbar' . DS . 'button');

define('JOOMDOC_ASSETS', str_replace('/administrator/', '/', JURI::base(true) . '/components/' . JOOMDOC_OPTION . '/assets/'));
//define('JOOMDOC_ASSETS', JURI::root().'components/'.JOOMDOC_OPTION.'/assets/');
define('JOOMDOC_IMAGES', JOOMDOC_ASSETS . 'images/');
define('JOOMDOC_ICONS', JOOMDOC_ASSETS . 'images/icons/');

//entities
define('JOOMDOC_JOOMDOC', 'joomdoc');
define('JOOMDOC_CATEGORIES', 'categories');
define('JOOMDOC_CATEGORY', 'category');
define('JOOMDOC_DOCUMENTS', 'documents');
define('JOOMDOC_DOCUMENT', 'document');
define('JOOMDOC_UPGRADE', 'upgrade');
define('JOOMDOC_FILE', 'file');

//tasks filesystem
define('JOOMDOC_TASK_UPLOADFILE', 'uploadfile');
define('JOOMDOC_TASK_NEWFOLDER', 'newfolder');
define('JOOMDOC_TASK_DELETEFILE', 'deletefile');

//tasks common
define('JOOMDOC_TASK_SAVEORDER', 'saveorder');
define('JOOMDOC_TASK_EDIT', 'edit');
define('JOOMDOC_TASK_ORDERUP', 'orderup');
define('JOOMDOC_TASK_ORDERDOWN', 'orderdown');
define('JOOMDOC_TASK_ADD', 'add');
define('JOOMDOC_TASK_PUBLISH', 'publish');
define('JOOMDOC_TASK_UNPUBLISH', 'unpublish');
define('JOOMDOC_TASK_ARCHIVE', 'archive');
define('JOOMDOC_TASK_CHECKIN', 'checkin');
define('JOOMDOC_TASK_DELETE', 'delete');
define('JOOMDOC_TASK_TRASH', 'trash');
define('JOOMDOC_TASK_APPLY', 'apply');
define('JOOMDOC_TASK_SAVE', 'save');
define('JOOMDOC_TASK_SAVE2NEW', 'save2new');
define('JOOMDOC_TASK_CANCEL', 'cancel');
define('JOOMDOC_TASK_SAVE2COPY', 'save2copy');
define('JOOMDOC_TASK_DOWNLOAD', 'download');
define('JOOMDOC_TASK_RENAME', 'rename');
define('JOOMDOC_TASK_COPY', 'copy');
define('JOOMDOC_TASK_MOVE', 'move');
define('JOOMDOC_TASK_PASTE', 'paste');
define('JOOMDOC_TASK_RESET', 'reset');

//actions component
define('JOOMDOC_CORE_ADMIN', 'core.admin');
define('JOOMDOC_CORE_MANAGE', 'core.manage');
//actions filesystem
define('JOOMDOC_CORE_UPLOADFILE', 'core.uploadfile');
define('JOOMDOC_CORE_NEWFOLDER', 'core.newfolder');
define('JOOMDOC_CORE_DELETEFILE', 'core.deletefile');
define('JOOMDOC_CORE_RENAME', 'core.rename');
define('JOOMDOC_CORE_COPY_MOVE', 'core.copy.move');
define('JOOMDOC_CORE_VIEWFILEINFO', 'core.viewfileinfo');
define('JOOMDOC_CORE_DOWNLOAD', 'core.download');
define('JOOMDOC_CORE_ENTERFOLDER', 'core.enterfolder');
define('JOOMDOC_CORE_EDIT_WEBDAV', 'core.edit.webdav');
//actions documents
define('JOOMDOC_CORE_CREATE', 'core.create');
define('JOOMDOC_CORE_EDIT', 'core.edit');
define('JOOMDOC_CORE_EDIT_OWN', 'core.edit.own');
define('JOOMDOC_CORE_EDIT_STATE', 'core.edit.state');
define('JOOMDOC_CORE_DELETE', 'core.delete');
define('JOOMDOC_CORE_VIEW_VERSIONS', 'core.view.versions');

//filter fields
//Joomla core
define('JOOMDOC_FILTER_ORDERING', 'list.ordering');
define('JOOMDOC_FILTER_DIRECTION', 'list.direction');
define('JOOMDOC_FILTER_START', 'list.start');
define('JOOMDOC_FILTER_LIMIT', 'list.limit');
//JoomDOC
define('JOOMDOC_FILTER_TITLE', 'title');
define('JOOMDOC_FILTER_FILENAME', 'filename');
define('JOOMDOC_FILTER_CATEGORY', 'category_id');
define('JOOMDOC_FILTER_ACCESS', 'access');
define('JOOMDOC_FILTER_STATE', 'state');
define('JOOMDOC_FILTER_PATHS', 'paths');
define('JOOMDOC_FILTER_PATH', 'path');
define('JOOMDOC_FILTER_CREATED', 'created');
define('JOOMDOC_FILTER_HITS', 'hits');
define('JOOMDOC_FILTER_UPLOAD', 'upload');
define('JOOMDOC_FILTER_ID', 'id');
define('JOOMDOC_FILTER_PUBLISH_UP', 'publish_up');
define('JOOMDOC_FILTER_PUBLISH_DOWN', 'publish_down');

//data types
define('JOOMDOC_INT', 'int');
define('JOOMDOC_STRING', 'string');
define('JOOMDOC_ARRAY', 'array');

//ordering
define('JOOMDOC_ORDER_ID', 'id');
define('JOOMDOC_ORDER_PATH', 'path');
define('JOOMDOC_ORDER_UPLOAD', 'upload');
define('JOOMDOC_ORDER_TITLE', 'title');
define('JOOMDOC_ORDER_ACCESS', 'access');
define('JOOMDOC_ORDER_STATE', 'state');
define('JOOMDOC_ORDER_ORDERING', 'ordering');
define('JOOMDOC_ORDER_PUBLISH_UP', 'publish_up');
define('JOOMDOC_ORDER_HITS', 'hits');
define('JOOMDOC_ORDER_CATEGORY', 'category');
define('JOOMDOC_ORDER_DESC', 'desc');
define('JOOMDOC_ORDER_ASC', 'asc');
define('JOOMDOC_ORDER_NEXT', 'next');
define('JOOMDOC_ORDER_PREV', 'prev');

//state
define('JOOMDOC_STATE_PUBLISHED', 1);
define('JOOMDOC_STATE_UNPUBLISHED', 0);
define('JOOMDOC_STATE_TRASHED', -2);
define('JOOMDOC_FAVORITE', 1);
define('JOOMDOC_STANDARD', 0);

// Joomla 1.5.x user groups
define('JOOMDOC_GROUP_PUBLIC', 0);
define('JOOMDOC_GROUP_REGISTERED', 18);
define('JOOMDOC_GROUP_AUTHOR', 19);
define('JOOMDOC_GROUP_EDITOR', 20);
define('JOOMDOC_GROUP_PUBLISHER', 21);
define('JOOMDOC_GROUP_MANAGER', 23);
define('JOOMDOC_GROUP_ADMINISTRATOR', 24);
define('JOOMDOC_GROUP_SUPER_ADMINISTRATOR', 25);

// user state properties
define('JOOMDOC_USER_STATE_PATHS', 'joomdoc_user_state_paths');
define('JOOMDOC_USER_STATE_OPERATION', 'joomdoc_user_state_operation');

// items operations
define('JOOMDOC_OPERATION_COPY', 'copy');
define('JOOMDOC_OPERATION_MOVE', 'move');
?>