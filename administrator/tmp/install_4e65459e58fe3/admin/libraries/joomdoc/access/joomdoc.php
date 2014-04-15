<?php

/**
 * @version		$Id$
 * @package		Joomla.Administrator
 * @subpackage	JoomDOC
 * @author      ARTIO s.r.o., info@artio.net, http:://www.artio.net
 * @copyright	Copyright (C) 2011 Artio s.r.o.. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die();

class JoomDOCAccess {

    /**
     * Authorise concrete task in concrete section.
     *
     * @param string $task task name
     * @param string $section section name, default component
     * @return boolean
     */
    public static function authorise ($task, $section = JOOMDOC_OPTION, $sessionid = null) {
        if ($sessionid)
            $user = JFactory::getUser(JoomDOCUser::getUserIdBySessionId($sessionid));
        else
            $user = JFactory::getUser();
        /* Joomla 1.6.x */
        if (J16) {
            return $user->authorise($task, $section);
        }

        /* Joomla 1.5.x */

        // frontend public
        $access[JOOMDOC_CORE_ENTERFOLDER] = JOOMDOC_GROUP_PUBLIC;
        $access[JOOMDOC_CORE_VIEWFILEINFO] = JOOMDOC_GROUP_PUBLIC;
        $access[JOOMDOC_CORE_DOWNLOAD] = JOOMDOC_GROUP_PUBLIC;

        // frontend author
        $access[JOOMDOC_CORE_UPLOADFILE] = JOOMDOC_GROUP_AUTHOR;
        $access[JOOMDOC_CORE_CREATE] = JOOMDOC_GROUP_AUTHOR;
        $access[JOOMDOC_CORE_EDIT_OWN] = JOOMDOC_GROUP_AUTHOR;

        // frontend editor
        $access[JOOMDOC_CORE_EDIT] = JOOMDOC_GROUP_EDITOR;
        $access[JOOMDOC_CORE_DELETEFILE] = JOOMDOC_GROUP_EDITOR;
        $access[JOOMDOC_CORE_DELETE] = JOOMDOC_GROUP_EDITOR;
        $access[JOOMDOC_CORE_RENAME] = JOOMDOC_GROUP_EDITOR;
        $access[JOOMDOC_CORE_EDIT_WEBDAV] = JOOMDOC_GROUP_EDITOR;

        // frontend publisher
        $access[JOOMDOC_CORE_EDIT_STATE] = JOOMDOC_GROUP_PUBLISHER;

        // backend
        $access[JOOMDOC_CORE_NEWFOLDER] = JOOMDOC_GROUP_MANAGER;
        $access[JOOMDOC_CORE_VIEW_VERSIONS] = JOOMDOC_GROUP_MANAGER;

        // super admin & admin
        $access[JOOMDOC_CORE_ADMIN] = JOOMDOC_GROUP_SUPER_ADMINISTRATOR;
        $access[JOOMDOC_CORE_MANAGE] = JOOMDOC_GROUP_SUPER_ADMINISTRATOR;
        $access[JOOMDOC_CORE_COPY_MOVE] = JOOMDOC_GROUP_SUPER_ADMINISTRATOR;

        if (isset($access[$task]))
            return $access[$task] <= $user->get('gid');
        return false;
    }

    /**
     * Access acces component.
     *
     * @return boolean
     */
    public static function manage () {
        return JoomDOCAccess::authorise(JOOMDOC_CORE_MANAGE);
    }

    /**
     * Access configure component.
     *
     * @return boolean
     */
    public static function admin () {
        return JoomDOCAccess::authorise(JOOMDOC_CORE_ADMIN);
    }
}
?>