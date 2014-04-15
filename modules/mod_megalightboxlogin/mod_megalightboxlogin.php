<?php
/*----------------------------------------------------------------------
 # mod_megalightboxlogin - Mega Lightbox Login Module For Joomla! 1.6
 #----------------------------------------------------------------------
 # author OmegaTheme.com
 # copyright Copyright(C) 2011 - OmegaTheme.com. All Rights Reserved.
 # @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 # Website: http://omegatheme.com
 # Technical support: Forum - http://omegatheme.com/forum/
------------------------------------------------------------------------*/

// no direct access
defined('_JEXEC') or die;

// Include the syndicate functions only once
require_once dirname(__FILE__).DS.'helper.php';

$params->def('greeting', 1);

$type	= modMegalightboxLoginHelper::getType();
$return	= modMegalightboxLoginHelper::getReturnURL($params, $type);
$user	= JFactory::getUser();
/*echo '<pre>';
var_dump($user);
echo '</pre>';*/
require JModuleHelper::getLayoutPath('mod_megalightboxlogin', $params->get('layout', 'default'));
