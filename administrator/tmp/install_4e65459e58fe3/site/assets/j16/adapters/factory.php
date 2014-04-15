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

class J16Factory {

    /**
     * Reads a XML file.
     *
     * @param string  $data   Full path and file name.
     * @param boolean $isFile true to load a file | false to load a string.
     *
     * @return mixed JXMLElement on success | false on error.
     * @todo This may go in a separate class - error reporting may be improved.
     */
    function getXML ($data, $isFile = true) {
        jimport('joomla.utilities.xmlelement');

        // Disable libxml errors and allow to fetch error information as needed
        libxml_use_internal_errors(true);

        if ($isFile) {
            // Try to load the xml file
            $xml = simplexml_load_file($data, 'SimpleXMLElement');
        } else {
            // Try to load the xml string
            $xml = simplexml_load_string($data, 'SimpleXMLElement');
        }

        if (empty($xml)) {
            // There was an error
            JError::raiseWarning(100, JText::_('JLIB_UTIL_ERROR_XML_LOAD'));

            if ($isFile) {
                JError::raiseWarning(100, $data);
            }

            foreach (libxml_get_errors() as $error) {
                JError::raiseWarning(100, 'XML: ' . $error->message);
            }
        }

        return $xml;
    }

}
?>