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

class ##Component##Controller extends JControllerLegacy
{
	/**
	 * The default view for the display method.
	 *
	 * @var    string
	 * @since  12.2
	 */
	protected $default_view = '##defaultview##';##{start_admin}##
	
	/**
	 * Checks whether a user can see this view.
	 *
	 * @param   string	$view	The view name.
	 *
	 * @return  boolean
	 * @since   1.6
	 */
	protected function canView($view)
	{
		$canDo	= ##Component##Helper::getActions();
		return $canDo->get('core.admin');
	}##{end_admin}##

	/**
     * Method to display a view.
     *
     * @param   boolean If true, the view output will be cached
     * @param   array   An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
     *
     * @return  JController	This object to support chaining.
     * @since   1.5
     */
    public function display($cachable = false, $urlparams = false)
	{##{start_site}##
		JForm::addFormPath(JPATH_COMPONENT_ADMINISTRATOR . '/models/forms');
		JForm::addFieldPath(JPATH_COMPONENT_ADMINISTRATOR . '/models/fields');
##{end_site}##
        // Get the document object.
		$document	= JFactory::getDocument();

		// Set the default view name and format from the Request.
		$vName   = $this->input->getCmd('view', '##defaultview##');
		$vFormat = $document->getType();
		$lName   = $this->input->getCmd('layout', 'default');##{start_admin}##
		
		// Check whether user is allowed to admin component 
		if (!$this->canView($vName))
		{
			JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
			return;
		}##{end_admin}##
		
		// Get the model and the view
		$model = $this->getModel($vName);
		$view = $this->getView($vName, $vFormat);
		
		// Push the model into the view (as default).
		$view->setModel($model, true);
		$view->setLayout($lName);
		
		// Push document object into the view.
		$view->document = $document;

		// Display the view
		$view->display();
    }
}
?>