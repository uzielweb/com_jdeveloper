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
 * JDeveloper Component View
 *
 * @package     JDeveloper
 * @subpackage  Views
 */
class JDeveloperViewComponent extends JViewLegacy
{
	protected $active;
	protected $item;
	protected $form;
	protected $state;
	protected $tables;
	
	public function display($tpl = null)
	{
		//-- Hauptmenü sperren
		$input = JFactory::getApplication()->input;	
		$this->_layout == "edit" ? $input->set('hidemainmenu', true) : null;
		
		$this->form = $this->get('Form');
		$this->item = $this->get('Item');
		$this->state = $this->get('State');
		$this->tables = JModelLegacy::getInstance("Tables", "JDeveloperModel")->getComponentTables($this->item->id);
		$this->active = $this->getActiveTab();
		
		if ($this->_layout == "default")
		{
			$model = JModelLegacy::getInstance("Overrides", "JDeveloperModel");
			$this->overrides_admin = $model->getOverrides("component.admin", $this->item->id);			
			
			if ($this->item->site)
			{
				$this->overrides_site = $model->getOverrides("component.site", $this->item->id);
			}
		}
				
		$this->sidebar = JLayoutHelper::render("sidebar", array("active" => "components"), JDeveloperLAYOUTS);
		$this->addToolbar();

		parent::display($tpl);
	}
	
	protected function addToolbar()
	{
		if ($this->_layout == "default")
		{
			JToolBarHelper::title(JText::_('COM_JDEVELOPER_COMPONENT'));
		}
		else
		{
			JToolBarHelper::title(JText::_('COM_JDEVELOPER_COMPONENT'));
			JToolBarHelper::apply('component.apply');
			JToolBarHelper::save('component.save');
			JToolBarHelper::save2copy('component.save2copy');
			JToolBarHelper::save2new('component.save2new');
			JToolBarHelper::cancel('component.cancel', 'JTOOLBAR_CANCEL');
		}
	}
	
	/**
	 * Get the active tab as an array
	 * 
	 * @return array
	 */
	protected function getActiveTab()
	{
		$input = JFactory::getApplication()->input;	
		$active = explode(".", $input->get("active", "general"));

		switch (count($active)) {
			case 1:
				if (count($this->tables))
					$active[1] = "table" . $this->tables[0]->id;			
				break;
			default:
				break;
		}
		
		return $active;
	}
}