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

class J16Text {

    /**
     * Translates a string into the current language.
     *
     * @param	string			The string to translate.
     * @param	string			The alternate option for global string
     * @param	boolean|array	boolean: Make the result javascript safe. array an array of option as described in the JText::sprintf function
     * @param	boolean			To interprete backslashes (\\=\, \n=carriage return, \t=tabulation)
     * @param	boolean			To indicate that the string will be pushed in the javascript language store
     * @return	string			The translated string or the key if $script is true
     * @example	<?php echo JText::alt("JALL","language");?> it will generate a 'All' string in English but a "Toutes" string in French
     * @example	<?php echo JText::alt("JALL","module");?> it will generate a 'All' string in English but a "Tous" string in French
     * @since	1.5
     *
     */
    public static function alt ($string, $alt, $jsSafe = false, $interpreteBackSlashes = true, $script = false) {
        $lang = JFactory::getLanguage();
        if ($lang->hasKey($string . '_' . $alt)) {
            return JText::_($string . '_' . $alt, $jsSafe, $interpreteBackSlashes);
        } else {
            return JText::_($string, $jsSafe, $interpreteBackSlashes);
        }
    }

    /**
     * Like JText::sprintf but tries to pluralise the string.
     *
     * @param	string	The format string.
     * @param	int		The number of items
     * @param	mixed	Mixed number of arguments for the sprintf function. The first should be an integer.
     * @param	array	optional Array of option array('jsSafe'=>boolean, 'interpreteBackSlashes'=>boolean, 'script'=>boolean) where
     *					-jsSafe is a boolean to generate a javascript safe string
     *					-interpreteBackSlashes is a boolean to interprete backslashes \\->\, \n->new line, \t->tabulation
     *					-script is a boolean to indicate that the string will be push in the javascript language store
     * @return	string	The translated strings or the key if 'script' is true in the array of options
     * @example	<script>alert(Joomla.JText._('<?php echo JText::plural("COM_PLUGINS_N_ITEMS_UNPUBLISHED", 1, array("script"=>true));?>'));</script> will generate an alert message containing '1 plugin successfully disabled'
     * @example	<?php echo JText::plural("COM_PLUGINS_N_ITEMS_UNPUBLISHED", 1);?> it will generate a '1 plugin successfully disabled' string
     * @since	1.6
     */

    public static function plural ($string, $n) {
        if (!J16) {
            return JText::sprintf($string, $n);
        }
        $lang = JFactory::getLanguage();
        $args = func_get_args();
        $count = count($args);

        if ($count > 1) {
            // Try the key from the language plural potential suffixes
            $found = false;
            $suffixes = $lang->getPluralSuffixes((int) $n);
            foreach ($suffixes as $suffix) {
                $key = $string . '_' . $suffix;
                if ($lang->hasKey($key)) {
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                // Not found so revert to the original.
                $key = $string;
            }
            if (is_array($args[$count - 1])) {
                $args[0] = $lang->_($key, array_key_exists('jsSafe', $args[$count - 1]) ? $args[$count - 1]['jsSafe'] : false, array_key_exists('interpreteBackSlashes', $args[$count - 1]) ? $args[$count - 1]['interpreteBackSlashes'] : true);
                if (array_key_exists('script', $args[$count - 1]) && $args[$count - 1]['script']) {
                    self::$strings[$key] = call_user_func_array('sprintf', $args);
                    return $key;
                }
            } else {
                $args[0] = $lang->_($key);
            }
            return call_user_func_array('sprintf', $args);
        } elseif ($count > 0) {

            // Default to the normal sprintf handling.
            $args[0] = $lang->_($string);
            return call_user_func_array('sprintf', $args);
        }

        return '';
    }

}
?>