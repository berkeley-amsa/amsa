<?php

/**
 * @version		$Id$
 * @package		Joomla.Administrator
 * @subpackage	JoomDOC
 * @author      ARTIO s.r.o., info@artio.net, http:://www.artio.net
 * @copyright	Copyright (C) 2011 Artio s.r.o.. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

class JoomDOCString extends JObject {

    /**
     * Crop string into given length.
     * Method strip HTML tags and crop string to given length after last space.
     * From croped string strip all characters which are not letter or number.
     * At the end of string add tail according to language constant JOOMDOC_CROP.
     *
     * @param string $text string to crop
     * @param int $length crop length
     * @return string
     */
    public function crop ($text, $length) {
        $chars = '~;!?.,@#$%^&*_-=+{}[]()<>:|"\'´`//\\';
        $text = strip_tags($text);
        $text = JString::trim($text);
        if (JString::strlen($text) <= $length) {
            return $text;
        }
        $text = JString::substr($text, 0, $length);
        $lastSpace = JString::strrpos($text, ' ');
        $text = JString::substr($text, 0, $lastSpace);
        $text = JString::trim($text);
        while (($length = JString::strlen($text))) {
            $lastChar = JString::substr($text, $length - 2, 1);
            if (JString::strpos($chars, $lastChar) !== false) {
                $text = JString::substr($text, 0, $length - 1);
            } else {
                break;
            }
        }
        return JText::sprintf('JOOMDOC_CROP', $text);
    }

    function urlencode ($string) {
        return urlencode($string);
    }

    function urldecode ($string) {
        return urldecode($string);
    }
}
?>