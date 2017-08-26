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
 * JDeveloper Override Controller
 *
 * @package     JDeveloper
 * @subpackage  Controllers
 */
class JDeveloperControllerOverride extends JDeveloperControllerItem
{
	/**
	 * Method to add a new record.
	 *
	 * @return  mixed  True if the record can be added, a error object if not.
	 *
	 * @since   12.2
	 */
	public function add()
	{
		$item_id = $this->input->get('item_id', 0, 'int');
		$type = $this->input->get('type', '', 'string');
		$name = $this->input->get('name', '', 'string');

		if ($this->allowAdd() && !empty($type) && !empty($name) && !empty($item_id))
		{
			$override = $this->getModel()->getOverride($type, $item_id, $name);

			if (!empty($override))
			{
				$this->setRedirect(
					JRoute::_(
						'index.php?option=' . $this->option . '&view=' . $this->view_item
						. $this->getRedirectToItemAppend($override->id, 'id'), false
					)
				);
			
				return true;
			}
		}
		
		parent::add();
	}
	
	/**
	 * Method to cancel an edit.
	 *
	 * @param   string  $key  The name of the primary key of the URL variable.
	 *
	 * @return  boolean  True if access level checks pass, false otherwise.
	 *
	 * @since   12.2
	 */
	public function cancel($key = null)
	{
		$result = parent::cancel();
		$data = $this->input->get("jform", array(), "array");
		$view = $data['type'];

		switch ($view)
		{
			case "" :
				$view = $this->view_list;
				break;
			case "component.admin" :
			case "component.site" :
			case "table.admin" :
			case "table.site" :
				$view = "component";
				break;
			default :
				break;
		}
		$this->setRedirect(JRoute::_("index.php?option=com_jdeveloper&view=" . $view . "&id=" . $data["item_id"], false));

		return $result;
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
		$append = parent::getRedirectToItemAppend($recordId, $urlVar);

		$item_id = $this->input->get('item_id', 0, 'int');
		if (!empty($item_id))
		{
			$append .= "&item_id=" . $item_id;
		}
		
		$type = $this->input->get('type', '', 'string');
		if (!empty($type))
		{
			$append .= "&type=" . $type;
		}
		
		$name = $this->input->get('name', '', 'string');
		if (!empty($name))
		{
			$append .= "&name=" . $name;
		}
		
		return $append;
	}

	/**
	 * Method to save a record.
	 *
	 * @param   string  $key     The name of the primary key of the URL variable.
	 * @param   string  $urlVar  The name of the URL variable if different from the primary key (sometimes required to avoid router collisions).
	 *
	 * @return  boolean  True if successful, false otherwise.
	 *
	 * @since   12.2
	 */
	public function save($key = null, $urlVar = null)
	{
		$result = parent::save();
		
		if ($this->getTask() == "save")
		{
			$data = $this->input->get("jform", array(), "array");			
			$view = $data['type'];

			switch ($view)
			{
				case "" :
					$view = $this->view_list;
					break;
				case "component.admin" :
				case "component.site" :
				case "table.admin" :
				case "table.site" :
					$view = "component";
					break;
				default :
					break;
			}

			$this->setRedirect(JRoute::_("index.php?option=com_jdeveloper&view=" . $view . "&id=" . $data["item_id"], false));
		}
		
		return $result;
	}
}