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

class J16Database {

    /**
     * Get the current or query, or new JDatabaseQuery object.
     *
     * @param	boolean	False to return the last query set by setQuery, True to return a new JDatabaseQuery object.
     * @return	string	The current value of the internal SQL variable
     */
    public function getQuery ($new = false) {
        if ($new) {
            return new JDatabaseQuery;
        } else {
            $db =& JFactory::getDbo();
            /* @var $db JDatabase */
            return $db->getQuery();
        }
    }
}
?>