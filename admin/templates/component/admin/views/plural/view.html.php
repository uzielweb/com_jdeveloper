<?php##{start_header}##
/**
 * @package     JDeveloper
 * @subpackage  Templates.Component
 *
 * @copyright  	Copyright (C) 2014, Tilo-Lars Flasche. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;##{end_header}##
##Header##

require_once JPATH_COMPONENT.'/helpers/##component##.php';

/**
 * ##Plural## list view class.
 *
 * @package     ##Component##
 * @subpackage  Views
 */
class ##Component##View##Plural## extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;
	
	public function display($tpl = null)
	{
		$this->items		 = $this->getModel()->getItems();
		$this->state		 = $this->getModel()->getState();
		$this->pagination	 = $this->getModel()->getPagination();##{start_created_by}##
		$this->authors		 = $this->getModel()->getAuthors();##{end_created_by}##
		$this->filterForm    = $this->getModel()->getFilterForm();
		$this->activeFilters = $this->getModel()->getActiveFilters();

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors));
			return false;
		}
		
		##Component##Helper::addSubmenu('##plural##');
		
		// We don't need toolbar in the modal window.
		if ($this->getLayout() !== 'modal')
		{
			$this->addToolbar();
			$this->sidebar = JHtmlSidebar::render();
		}
		
		parent::display($tpl);
	}
	
	/**
	 *	Method to add a toolbar
	 */
	protected function addToolbar()
	{
		$state	= $this->get('State');
		$canDo	= ##Component##Helper::getActions();
		$user	= JFactory::getUser();

		// Get the toolbar object instance
		$bar = JToolBar::getInstance('toolbar');
		
		JToolBarHelper::title(JText::_('COM_##COMPONENT##_##TABLE##_VIEW_##PLURAL##_TITLE'));
		
		if ($canDo->get('core.create')##{start_catid}## || (count($user->getAuthorisedCategories('com_##component##', 'core.create'))) > 0 ##{end_catid}##)
		{
			JToolBarHelper::addNew('##singular##.add','JTOOLBAR_NEW');
		}

		if (($canDo->get('core.edit')##{start_created_by}## || $canDo->get('core.edit.own')##{end_created_by}##) && isset($this->items[0]))
		{
			JToolBarHelper::editList('##singular##.edit','JTOOLBAR_EDIT');
		}##{!start_published}##
		
		if ($canDo->get('core.delete') && isset($this->items[0]))
		{
            JToolBarHelper::deleteList('', '##plural##.delete','JTOOLBAR_DELETE');
		}##{!end_published}####{start_published}##
		
		if ($canDo->get('core.edit.state'))
		{
            if (isset($this->items[0]->published))
			{
			    JToolBarHelper::divider();
				JToolbarHelper::publish('##plural##.publish', 'JTOOLBAR_PUBLISH', true);
				JToolbarHelper::unpublish('##plural##.unpublish', 'JTOOLBAR_UNPUBLISH', true);
            } 
			else if (isset($this->items[0]))
			{
                // Show a direct delete button
                JToolBarHelper::deleteList('', '##plural##.delete','JTOOLBAR_DELETE');
            }

            if (isset($this->items[0]->published))
			{
			    JToolBarHelper::divider();
			    JToolBarHelper::archiveList('##plural##.archive','JTOOLBAR_ARCHIVE');
            }##{start_checked_out}##
            
			if (isset($this->items[0]->checked_out))
			{
				JToolbarHelper::checkin('##plural##.checkin');
            }##{end_checked_out}##
		}
		
		// Show trash and delete for components that uses the state field
        if (isset($this->items[0]->published))
		{
		    if ($state->get('filter.published') == -2 && $canDo->get('core.delete'))
			{
			    JToolBarHelper::deleteList('', '##plural##.delete','JTOOLBAR_EMPTY_TRASH');
			    JToolBarHelper::divider();
		    }
			else if ($state->get('filter.published') != -2 && $canDo->get('core.edit.state'))
			{
			    JToolBarHelper::trash('##plural##.trash','JTOOLBAR_TRASH');
			    JToolBarHelper::divider();
		    }
        }##{end_published}##
		
		// Add a batch button
		if (isset($this->items[0]) && $user->authorise('core.create', 'com_contacts') && $user->authorise('core.edit', 'com_contacts')##{start_published}## && $user->authorise('core.edit.state', 'com_contacts')##{end_published}##)
		{
			JHtml::_('bootstrap.modal', 'collapseModal');
			$title = JText::_('JTOOLBAR_BATCH');

			// Instantiate a new JLayoutFile instance and render the batch button
			$layout = new JLayoutFile('joomla.toolbar.batch');

			$dhtml = $layout->render(array('title' => $title));
			$bar->appendButton('Custom', $dhtml, 'batch');
		}
		
		if ($canDo->get('core.admin'))
		{
			JToolBarHelper::preferences('com_##component##');
		}
	}
}
?>