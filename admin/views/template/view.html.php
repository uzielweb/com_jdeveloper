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
 * JDeveloper Template View
 *
 * @package     JDeveloper
 * @subpackage  Views
 */
class JDeveloperViewTemplate extends JViewLegacy
{
	protected $item;
	protected $form;
	protected $state;
	
	public function display($tpl = null)
	{
		//-- Hauptmenü sperren
		$input = JFactory::getApplication()->input;
		$this->_layout == "edit" ? $input->set('hidemainmenu', true) : null;
		
		$this->form = $this->get('Form');
		$this->item = $this->get('Item');
		$this->state = $this->get('State');
				
		if ($this->_layout == "default")
		{
			$model = JModelLegacy::getInstance("Overrides", "JDeveloperModel");
			$this->overrides = $model->getOverrides("template", $this->item->id);
			
			$model = JModelLegacy::getInstance("Templates", "JDeveloperModel");
			$this->items = $model->getItems();
		}
				
		$this->sidebar = JLayoutHelper::render("sidebar", array("active" => "templates"), JDeveloperLAYOUTS);
		$this->addToolbar();
		parent::display($tpl);
	}
	
	protected function addToolbar()
	{
		if ($this->_layout == "default")
		{
			JToolBarHelper::title(JText::_('COM_JDEVELOPER_TEMPLATE'));
		}
		else
		{
			JToolBarHelper::title(JText::_('COM_JDEVELOPER_TEMPLATE'));
			JToolBarHelper::apply('template.apply');
			JToolBarHelper::save('template.save');
			JToolBarHelper::save2copy('template.save2copy');
			JToolBarHelper::save2new('template.save2new');
			JToolBarHelper::cancel('template.cancel', 'JTOOLBAR_CANCEL');
		}
	}
}