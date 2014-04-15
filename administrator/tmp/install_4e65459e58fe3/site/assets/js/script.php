<?php

/**
 * @version		$Id$
 * @package		Joomla.Administrator
 * @subpackage	JoomDOC
 * @author      ARTIO s.r.o., info@artio.net, http:://www.artio.net
 * @copyright	Copyright (C) 2011 Artio s.r.o.. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$document =& JFactory::getDocument();
/* @var $document JDocumentHTML */

$params['joomDOCTaskUploadFile'] = JoomDOCHelper::getTask(JOOMDOC_DOCUMENTS, JOOMDOC_TASK_UPLOADFILE);
$params['joomDOCmsgEmpty'] = JText::_('JOOMDOC_UPLOAD_EMPTY');
$params['joomDOCmsgOverwrite'] = JText::_('JOOMDOC_UPLOAD_OVERWRITE');
$params['joomDOCmsgDirExists'] = JText::_('JOOMDOC_UPLOAD_DIR_EXISTS');
$params['joomDOCmsgAreYouSure'] = JText::_('JOOMDOC_ARE_YOU_SURE');

foreach ($params as $param => $value)
    $document->addScriptDeclaration('var ' . $param . ' = "' . addslashes($value) . '";');
?>