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
 * JDeveloper Formrule View
 *
 * @package     JDeveloper
 * @subpackage  Views
 */
class JDeveloperViewFormrule extends JViewLegacy
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
		JToolBarHelper::title(JText::_('COM_JDEVELOPER_FORMRULE'));
		
		if ($this->item->get("name", "") == "")
		{
			JToolBarHelper::apply('formrule.apply');
		}
		else
		{
			JToolBarHelper::apply('formrule.apply');		
			JToolBarHelper::save('formrule.save');
			JToolBarHelper::save2new('formrule.save2new');
			JToolBarHelper::save2copy('formrule.save2copy');
		}

		JToolBarHelper::cancel('formrule.cancel', 'JTOOLBAR_CANCEL');
	}
}