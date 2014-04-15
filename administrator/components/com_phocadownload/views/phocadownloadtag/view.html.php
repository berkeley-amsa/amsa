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
defined('_JEXEC') or die();
jimport( 'joomla.application.component.view' );

class PhocaDownloadCpViewPhocaDownloadTag extends JView
{
	protected $state;
	protected $item;
	protected $form;
	protected $tmpl;

	public function display($tpl = null) {
		
		$this->state	= $this->get('State');
		$this->form		= $this->get('Form');
		$this->item		= $this->get('Item');
		
		JHTML::stylesheet('administrator/components/com_phocadownload/assets/phocadownload.css' );

		$this->addToolbar();
		parent::display($tpl);
	}
	
	protected function addToolbar() {
		
		require_once JPATH_COMPONENT.DS.'helpers'.DS.'phocadownloadtags.php';
		JRequest::setVar('hidemainmenu', true);

		$user		= JFactory::getUser();
		$isNew		= ($this->item->id == 0);
		$checkedOut	= !($this->item->checked_out == 0 || $this->item->checked_out == $user->get('id'));
		$canDo		= PhocaDownloadTagsHelper::getActions($this->state->get('filter.tag_id'), $this->item->id);
		//$paramsC 	= JComponentHelper::getParams('COM_PHOCADOWNLOAD');

		

		$text = $isNew ? JText::_( 'COM_PHOCADOWNLOAD_NEW' ) : JText::_('COM_PHOCADOWNLOAD_EDIT');
		JToolBarHelper::title(   JText::_( 'COM_PHOCADOWNLOAD_TAG' ).': <small><small>[ ' . $text.' ]</small></small>' , 'tags.png');

		// If not checked out, can save the item.
		if (!$checkedOut && $canDo->get('core.edit')){
			JToolBarHelper::apply('phocadownloadtag.apply', 'JTOOLBAR_APPLY');
			JToolBarHelper::save('phocadownloadtag.save', 'JTOOLBAR_SAVE');
			JToolBarHelper::addNew('phocadownloadtag.save2new', 'JTOOLBAR_SAVE_AND_NEW');
		}
	
		if (empty($this->item->id))  {
			JToolBarHelper::cancel('phocadownloadtag.cancel', 'JTOOLBAR_CANCEL');
		}
		else {
			JToolBarHelper::cancel('phocadownloadtag.cancel', 'JTOOLBAR_CLOSE');
		}

		JToolBarHelper::divider();
		JToolBarHelper::help( 'screen.phocadownload', true );
	}
}
?>
