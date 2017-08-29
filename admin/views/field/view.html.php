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
 * JDeveloper Field View
 *
 * @package     JDeveloper
 * @subpackage  Views
 */
class JDeveloperViewField extends JViewLegacy
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
		JToolBarHelper::title('JDeveloper Field');
		JToolBarHelper::apply('field.apply');
		JToolBarHelper::save('field.save');
		JToolBarHelper::save2new('field.save2new');
		JToolBarHelper::save2copy('field.save2copy');
		JToolBarHelper::cancel('field.cancel', 'JTOOLBAR_CANCEL');
	}
}