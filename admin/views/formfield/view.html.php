<?php
/**
 * @package     JDeveloper
 * @subpackage  Views
 *
 * @copyright  	Copyright (C) 2014, Tilo-Lars Flasche. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

/**
 * JDeveloper Formfield View
 *
 * @package     JDeveloper
 * @subpackage  Views
 */
class JDeveloperViewFormfield extends JViewLegacy
{
	protected $item;
	protected $form;
	protected $state;
	
	public function display($tpl = null)
	{		
		JFactory::getApplication()->input->set('hidemainmenu', true);
		
		$this->form = $this->get('Form');
		$this->item = $this->get('Item');
		$this->state = $this->get('State');
		
		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}
		
		$this->addToolbar();
		parent::display($tpl);
	}
	
	protected function addToolbar()
	{
		JToolBarHelper::title(JText::_('COM_JDEVELOPER_FORMFIELD'));
		
		if ($this->item->get("name", "") == "")
		{
			JToolBarHelper::apply('formfield.apply');
		}
		else
		{
			JToolBarHelper::apply('formfield.apply');		
			JToolBarHelper::save('formfield.save');
			JToolBarHelper::save2new('formfield.save2new');
			JToolBarHelper::save2copy('formfield.save2copy');
		}

		JToolBarHelper::cancel('formfield.cancel', 'JTOOLBAR_CANCEL');
	}
}