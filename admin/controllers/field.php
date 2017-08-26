<?php
/**
 * @package     JDeveloper
 * @subpackage  Controllers
 *
 * @copyright  	Copyright (C) 2014, Tilo-Lars Flasche. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;
JDeveloperLoader::import("controllers.item");

/**
 * JDeveloper Field Controller
 *
 * @package     JDeveloper
 * @subpackage  Controllers
 */
class JDeveloperControllerField extends JDeveloperControllerItem
{
	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @see     JControllerLegacy
	 * @since   12.2
	 * @throws  Exception
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);
		
		if ($this->input->get("component", 0, "int"))
		{
			$this->view_list = "component";
		}
	}
	
	/**
	 * Delete field
	 */
	public function delete()
	{
		$app = JFactory::getApplication();
		$input = $app->input;
		$model = JModelLegacy::getInstance("Field", "JDeveloperModel");

		$id = $input->get("id", 0, "int");
		$field = $model->getItem($id);
		$model_table = JModelLegacy::getInstance("Table", "JDeveloperModel");
		$table = $model_table->getItem($field->table);
		
		$ids = array($id);
		$model->delete($ids);
		
		$this->setRedirect(JRoute::_("index.php?option=com_jdeveloper&view=component&id=" . $table->component . "&active=tables." . $table->id, false));
		$this->setMessage("COM_JDEVELOPER_FIELD_MESSAGE_FIELD_DELETED");
	}
	
	/**
	 * Export field to database
	 */
	public function install()
	{
		$app = JFactory::getApplication();
		$input = $app->input;
		$db = JFactory::getDbo();
		$model = JModelLegacy::getInstance("Field", "JDeveloperModel");
		$user = JFactory::getUser();
		
		// Check if action is allowed
		if (!$user->authorise('components.install', 'com_jdeveloper'))
		{
			$this->setMessage(JText::_('COM_JDEVELOPER_ACTION_NOT_ALLOWED'), 'warning');
			return;
		}
		
		$id = $input->get("id", 0, "int");
		$item = $model->getItem($id);
		$model->exportToDatabase($item);
		
		$this->setRedirect(JRoute::_("index.php?option=com_jdeveloper&view=component", false));
		$this->setMessage("COM_JDEVELOPER_FIELD_MESSAGE_FIELD_EXPORTED");
	}

	/**
	 * Gets the URL arguments to append to an item redirect.
	 *
	 * @param   integer  $recordId  The primary key id for the item.
	 * @param   string   $urlVar    The name of the URL variable for the id.
	 *
	 * @return  string  The arguments to append to the redirect URL.
	 *
	 * @since   12.2
	 */
	protected function getRedirectToItemAppend($recordId = null, $urlVar = 'id')
	{
		$append = parent::getRedirectToItemAppend($recordId = null, $urlVar = 'id');
		$append .= "&id=" . $this->input->get("id", 0, "int");
		
		$component = $this->input->get('component', 0, 'int');
		if ($component)
		{
			$append .= "&component=" . $component;
		}
		
		$table = $this->input->get('table', 0, 'int');
		if ($table)
		{
			$append .= "&table=" . $table;
		}
		
		return $append;
	}

	/**
	 * Gets the URL arguments to append to a list redirect.
	 *
	 * @return  string  The arguments to append to the redirect URL.
	 *
	 * @since   12.2
	 */
	protected function getRedirectToListAppend()
	{
		$append = parent::getRedirectToListAppend();

		$component = $this->input->get('component', 0, 'int');
		if ($component)
		{
			$append .= "&id=" . $component;
		}
		
		$table = $this->input->get('table', 0, 'int');
		if ($table)
		{
			$append .= "&active=tables." . $table;
		}
		
		return $append;
	}		
}