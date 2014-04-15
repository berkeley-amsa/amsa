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

jimport('joomla.form.formfield');

JLoader::register('JElementIcons', JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_joomdoc' . DS . 'elements' . DS . 'icons.php');

class JFormFieldModal_Icons extends JFormField {
    protected $type = 'Icons';

    protected function getInput () {
    	$fake = null;
        return JElementIcons::fetchElement($this->name, $this->value, $fake, null);
    }
}
?>