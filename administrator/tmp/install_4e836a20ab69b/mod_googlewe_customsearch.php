<?php
/**
* @version	$Id: mod_googlewe_customsearch.php 2009-05-30  $
* @package	Google Web Elements - Custom Search
* @copyright	Copyright (C) 2009 Open4G Media. All rights reserved.
* @license	GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Direct Access to this location is not allowed.');

// Include the syndicate functions only once
require_once (dirname(__FILE__).DS.'helper.php');
if ($params->get('googlewecustomsearch_w') && $params->get('googlewecustomsearch_h'))
{
$gwe_customsearch_w =  $params->get('googlewecustomsearch_w');
$gwe_customsearch_h =  $params->get('googlewecustomsearch_h');
} else {
$gwe_customsearch_w = '100%';
$gwe_customsearch_h =  '100%';	
}
require(JModuleHelper::getLayoutPath('mod_googlewe_customsearch'));
