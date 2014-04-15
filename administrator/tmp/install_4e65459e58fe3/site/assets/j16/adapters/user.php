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

class J16User {
    /**
     * Gets an array of the authorised access levels for the user
     *
     * @return	array
     */
    function getAuthorisedViewLevels () {
        $user =& JFactory::getUser();
        switch ($user->usertype) {
            case 'Registered':
                return array(0, 1);
            case 'Author':
            case 'Editor':
            case 'Publisher':
            case 'Manager':
            case 'Administrator':
            case 'Super Administrator':
                return array(0, 1, 2);
            default:
                return array(0);
        }
    }
}
?>