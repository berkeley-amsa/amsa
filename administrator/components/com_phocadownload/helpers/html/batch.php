<?php
/*
 * @package		Joomla.Framework
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 *
 * @component Phoca Component
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License version 2 or later;
 */

defined('JPATH_PLATFORM') or die;

abstract class PhocaDownloadBatch
{
	
	public static function item($published)
	{
		// Create the copy/move options.
		$options = array(
			JHtml::_('select.option', 'c', JText::_('JLIB_HTML_BATCH_COPY')),
			JHtml::_('select.option', 'm', JText::_('JLIB_HTML_BATCH_MOVE'))
		);
		
		$db = &JFactory::getDBO();

       //build the list of categories
		$query = 'SELECT a.title AS text, a.id AS value, a.parent_id as parentid'
		. ' FROM #__phocadownload_categories AS a'
		// TODO. ' WHERE a.published = '.(int)$published
		. ' ORDER BY a.ordering';
		$db->setQuery( $query );
		$data = $db->loadObjectList();
		$tree = array();
		$text = '';
		$catId= -1;
		$tree = PhocaDownloadHelper::CategoryTreeOption($data, $tree, 0, $text, $catId);

		// Create the batch selector to change select the category by which to move or copy.
		$lines = array(
			'<label id="batch-choose-action-lbl" for="batch-choose-action">',
			JText::_('JLIB_HTML_BATCH_MENU_LABEL'),
			'</label>',
			'<fieldset id="batch-choose-action" class="combo">',
				'<select name="batch[category_id]" class="inputbox" id="batch-category-id">',
					'<option value="">'.JText::_('JSELECT').'</option>',
					/*JHtml::_('select.options',	JHtml::_('category.options', $extension, array('published' => (int) $published))),*/
					JHTML::_('select.options',  $tree ),
				'</select>',
				JHTML::_( 'select.radiolist', $options, 'batch[move_copy]', '', 'value', 'text', 'm'),
			'</fieldset>'
		);

		return implode("\n", $lines);
	}
}
