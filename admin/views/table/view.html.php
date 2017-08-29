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
 * JDeveloper Table View
 *
 * @package     JDeveloper
 * @subpackage  Views
 */
class JDeveloperViewTable extends JViewLegacy
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
		
		if ($this->_layout == "edit")
		{
			$this->addToolbar();
		}
		
		parent::display($tpl);
	}
	
	protected function addToolbar()
	{		
		JToolBarHelper::title('JDeveloper Table');
		JToolBarHelper::apply('table.apply');
		JToolBarHelper::save('table.save');
		JToolBarHelper::save2new('table.save2new');
		JToolBarHelper::save2copy('table.save2copy');
		JToolBarHelper::cancel('table.cancel', 'JTOOLBAR_CANCEL');
	}
}