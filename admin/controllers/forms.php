<?php
/**
 * @package     JDeveloper
 * @subpackage  Controllers
 *
 * @copyright  	Copyright (C) 2014, Tilo-Lars Flasche. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;
JDeveloperLoader::import("controllers.list");

/**
 * JDeveloper Forms Controller
 *
 * @package     JDeveloper
 * @subpackage  Controllers
 */
class JDeveloperControllerForms extends JDeveloperControllerList
{
	/**
	 * The URL view list variable.
	 *
	 * @var    string
	 * @since  12.2
	 */
	protected $view_list = 'Forms';

	/**
	 * Rebuild the nested set tree.
	 *
	 * @return  bool  False on failure or error, true on success.
	 *
	 * @since   1.6
	 */
	public function rebuild()
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$extension = $this->input->get('extension');
		$this->setRedirect(JRoute::_('index.php?option=com_jdeveloper&view=forms', false));

		$model = $this->getModel();

		if ($model->rebuild())
		{
			// Rebuild succeeded.
			$this->setMessage(JText::_('COM_JDEVELOPER_REBUILD_SUCCESS'));

			return true;
		}
		else
		{
			// Rebuild failed.
			$this->setMessage(JText::_('COM_JDEVELOPER_REBUILD_FAILURE'));

			return false;
		}
	}
}
?>