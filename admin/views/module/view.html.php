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
 * JDeveloper Module View
 *
 * @package     JDeveloper
 * @subpackage  Views
 */
class JDeveloperViewModule extends JViewLegacy
{
	protected $item;
	protected $form;
	protected $state;
	
	public function display($tpl = null)
	{
		$input = JFactory::getApplication()->input;
		$this->_layout == "edit" ? $input->set('hidemainmenu', true) : null;
		
		$this->form = $this->get('Form');
		$this->item = $this->get('Item');
		$this->state = $this->get('State');
		
		if ($this->_layout == "default")
		{
			$model = JModelLegacy::getInstance("Overrides", "JDeveloperModel");
			$this->overrides = $model->getOverrides("module", $this->item->id);
			
			$model = JModelLegacy::getInstance("Modules", "JDeveloperModel");
			$this->items = $model->getItems();
		}
				
		$this->sidebar = JLayoutHelper::render("sidebar", array("active" => "modules"), JDeveloperLAYOUTS);
		$this->addToolbar();
		parent::display($tpl);
	}
	
	protected function addToolbar()
	{
		if ($this->_layout == "default")
		{
			JToolBarHelper::title(JText::_('COM_JDEVELOPER_MODULE'));
		}
		else
		{
			JToolBarHelper::title(JText::_('COM_JDEVELOPER_MODULE'));
			JToolBarHelper::apply('module.apply');
			JToolBarHelper::save('module.save');
			JToolBarHelper::save2copy('module.save2copy');
			JToolBarHelper::save2new('module.save2new');
			JToolBarHelper::cancel('module.cancel', 'JTOOLBAR_CANCEL');
		}
	}
}