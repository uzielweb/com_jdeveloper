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
 
/**
 * ##Singular## item view class.
 *
 * @package     ##Component##
 * @subpackage  Views
 */
class ##Component##View##Singular## extends JViewLegacy
{
	protected $item;
	protected $form;
	protected $state;
	
	public function display($tpl = null)
	{
		$this->state 	= $this->get('State');
		$this->item 	= $this->get('Item');
		$this->form 	= $this->get('Form');

		$app = JFactory::getApplication();
		$user = JFactory::getUser();##{start_access}##
		$levels = $user->getAuthorisedViewLevels();##{end_access}##
		
		// Check if item is empty
		if (empty($this->item))
		{
			$app->redirect(JRoute::_('index.php?option=com_##component##&view=##plural##'), JText::_('JERROR_NO_ITEMS_SELECTED'));
		}##{start_access}##
		
		// Check item access
		if ($this->item->##pk## && !in_array($this->item->access, $levels))
		{
			throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
		}##{end_access}##
			
		// Is the user allowed to create an item?
		if (!$this->item->##pk## && !$user->authorise("core.create", "com_##component##"))
		{
			throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
		}

		// Get menu params
		$menu = $app->getMenu();
		$active = $menu->getActive();
		
		if (is_object($active))
		{
			$this->state->params = $active->params;
		}

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors));
			return false;
		}		

		// Increment hits
		$model = $this->getModel();
		$model->hit($this->item->##pk##);##{end_hits}##
		
		parent::display($tpl);
	}
}
?>