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
 * JDeveloper Formfields View
 *
 * @package     JDeveloper
 * @subpackage  Views
 */
class JDeveloperViewFormfields extends JViewLegacy
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
		
		JDeveloperHelper::addSubmenu('formfields');
		
		$this->addToolbar();
		$this->sidebar = JLayoutHelper::render("sidebar", array("active" => "formfields"), JDeveloperLAYOUTS);
		parent::display($tpl);
	}
	
	protected function addToolbar()
	{
		// Get the toolbar object instance
		$bar = JToolBar::getInstance('toolbar');

		JToolBarHelper::title(JText::_('COM_JDEVELOPER_FORMFIELDS'));
		JToolBarHelper::addNew('formfield.add');
		JToolBarHelper::editList('formfield.edit');
		JToolBarHelper::deleteList('', 'formfields.delete', 'JTOOLBAR_DELETE');

		JHtml::_('bootstrap.modal', 'collapseModal');

		// Instantiate a new JLayoutFile instance and render the batch button
		$layout = new JLayoutFile('joomla.toolbar.batch');
		$dhtml = $layout->render(array('title' => JText::_('JTOOLBAR_BATCH')));
		$bar->appendButton('Custom', $dhtml, 'batch');

		JToolBarHelper::preferences('com_jdeveloper');
	}
}