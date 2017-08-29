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
 * JDeveloper View
 *
 * @package     JDeveloper
 * @subpackage  Views
 */
class JDeveloperViewCPanel extends JViewLegacy
{
	/**
	 * @see JViewLegacy::display()
	 */	
	public function display($tpl = null)
	{
		$this->archives = JModelLegacy::getInstance("Archive", "JDeveloperModel")->getItems();
		
		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}
		
		JDeveloperHelper::addSubmenu('jdeveloper');
		
		$this->addToolbar();
		
		parent::display($tpl);
	}
	
	/**
	 * Add toolbar
	 */
	protected function addToolbar()
	{
		JToolBarHelper::title(JText::_('COM_JDEVELOPER_JDEVELOPER'));
		JToolBarHelper::preferences('com_jdeveloper');
	}
}