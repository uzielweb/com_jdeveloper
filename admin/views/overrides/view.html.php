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
 * JDeveloper Overrides View
 *
 * @package     JDeveloper
 * @subpackage  Views
 */
class JDeveloperViewOverrides extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;
	
	public function display($tpl = null)
	{		
		$this->items = $this->get('Items');
		$this->state = $this->get('State');
		$this->pagination = $this->get('Pagination');
		$this->filterForm    = $this->get('FilterForm');
		$this->activeFilters = $this->get('ActiveFilters');
		
		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}
		
		$this->addToolbar();
		$this->sidebar = JLayoutHelper::render("sidebar", array("active" => "overrides"), JDeveloperLAYOUTS);
		parent::display($tpl);
	}
	
	protected function addToolbar()
	{
		JToolBarHelper::title(JText::_('COM_JDEVELOPER_OVERRIDES'));
		JToolBarHelper::editList('template.edit', 'JTOOLBAR_EDIT');
		JToolBarHelper::deleteList('', 'overrides.delete', 'JTOOLBAR_DELETE');
		JToolBarHelper::preferences('com_jdeveloper');
	}
}