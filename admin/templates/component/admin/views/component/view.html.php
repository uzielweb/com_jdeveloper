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
##{start_admin}##
require_once JPATH_COMPONENT.'/helpers/##component##.php';##{end_admin}##

/**
 * ##Component## list view class.
 *
 * @package     ##Component##
 * @subpackage  Views
 */
class ##Component##View##Component## extends JViewLegacy
{
	public function display($tpl = null)
	{##{start_admin}##
		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors));
			return false;
		}
		
		##Component##Helper::addSubmenu('##component##');
		
		// We don't need toolbar in the modal window.
		if ($this->getLayout() !== 'modal')
		{
			$this->addToolbar();
			$this->sidebar = JHtmlSidebar::render();
		}
		
		parent::display($tpl);##{end_admin}##
	}##{start_admin}##
	
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
		
		JToolBarHelper::title(JText::_('COM_##COMPONENT##'));
				
		if ($canDo->get('core.admin'))
		{
			JToolBarHelper::preferences('com_##component##');
		}
	}##{end_admin}##
}
?>