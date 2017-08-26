<?php
/**
 * @package     JDeveloper
 * @subpackage  Controllers
 *
 * @copyright  	Copyright (C) 2014, Tilo-Lars Flasche. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

/**
 * JDeveloper Fileeditor Controller
 *
 * @package     JDeveloper
 * @subpackage  Controllers
 */
class JDeveloperControllerFileeditor extends JControllerForm
{	
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
		$append .= "&client=" . $this->input->get("client");
		$append .= "&type=" . $this->input->get("type");
		$append .= "&name=" . $this->input->get("name");
		$append .= "&path=" . $this->input->get("path");
		
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
		$data = $this->input->get("jform", array(), "array");		
		$result = JFile::write($data["filepath"], $data["source"], true);
		
		if ($result)
			$this->setMessage("COM_JDEVELOPER_FILEEDITOR_MESSAGE_FILE_SAVED");
		else
			$this->setMessage("COM_JDEVELOPER_FILEEDITOR_MESSAGE_FAILED_TO_SAVE_FILE");
					
		$this->setRedirect(JRoute::_("index.php?option=com_jdeveloper&view=fileeditor" . $this->getRedirectToItemAppend(), false));
	}
}