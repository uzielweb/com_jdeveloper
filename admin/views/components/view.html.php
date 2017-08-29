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
 * JDeveloper Components View
 *
 * @package     JDeveloper
 * @subpackage  Views
 */
class JDeveloperViewComponents extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;
	
	public function display($tpl = null)
	{
		$this->items = $this->get('Items');
		$this->state = $this->get('State');
		$this->pagination = $this->get('Pagination');
		$this->filterForm = $this->get('FilterForm');
		$this->activeFilters = $this->get('ActiveFilters');
		
		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}
				
		$this->addToolbar();
		$this->sidebar = JLayoutHelper::render("sidebar", array("active" => "components"), JDeveloperLAYOUTS);
		
		parent::display($tpl);
		if (JFactory::getApplication()->input->get('ajax') == "1") exit;
	}
	
	protected function addToolbar()
	{
		// Get the toolbar object instance
		$bar = JToolBar::getInstance('toolbar');

		JToolBarHelper::title(JText::_('COM_JDEVELOPER_COMPONENTS'));
		JToolBarHelper::addNew('component.add', 'JTOOLBAR_NEW');
		JToolBarHelper::editList('component.edit', 'JTOOLBAR_EDIT');

		JToolBarHelper::publish('components.create', 'JTOOLBAR_CREATE_ZIP');
		JToolBarHelper::publish('components.install', 'JTOOLBAR_INSTALL');
		JToolBarHelper::unpublish('components.uninstall', 'JTOOLBAR_UNINSTALL');
		JToolBarHelper::deleteList('', 'components.delete', 'JTOOLBAR_DELETE');
		JToolBarHelper::deleteList('', 'components.deletezip', 'JTOOLBAR_DELETE_ZIP');
		
		JHtml::_('bootstrap.modal', 'collapseModal');

		// Instantiate a new JLayoutFile instance and render the batch button
		$layout = new JLayoutFile('joomla.toolbar.batch');
		$dhtml = $layout->render(array('title' => JText::_('JTOOLBAR_BATCH')));
		$bar->appendButton('Custom', $dhtml, 'batch');
		
		JToolBarHelper::preferences('com_jdeveloper');
	}
	
	private function custom(&$bar, $task, $text, $class = '')
	{
		$layout = new JLayoutFile('joomla.toolbar.standard');
		$dhtml = $layout->render(array('text' => $text, 'doTask' => 'doTask(\'' . $task . '\')', 'btnClass' => 'btn btn-small', 'class' => $class));
		$bar->appendButton('Custom', $dhtml, 'batch');		
	}
}