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
 * JDeveloper Form Controller
 *
 * @package     JDeveloper
 * @subpackage  Controllers
 */
class JDeveloperControllerForm extends JDeveloperControllerItem
{
	/**
	 * The URL view item variable.
	 *
	 * @var    string
	 * @since  12.2
	 */
	protected $view_item = 'Form';

	/**
	 * The URL view list variable.
	 *
	 * @var    string
	 * @since  12.2
	 */
	protected $view_list = 'Forms';
	
	/**
	 * Delete field
	 */
	public function delete()
	{
		$app = JFactory::getApplication();
		$input = $app->input;
		$model = JModelLegacy::getInstance("Form", "JDeveloperModel");

		$id = $input->get("id", 0, "int");
		$form = $model->getItem($id);
		
		$ids = array($id);
		$model->delete($ids);
		
		$this->setRedirect(JRoute::_("index.php?option=com_jdeveloper&view=component&id=" . $table->component . "&active=forms", false));
		$this->setMessage(JText::_("COM_JDEVELOPER_FORM_MESSAGE_ELEMENT_DELETED"));
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
		
		$id = $this->input->get('id', 0, 'int');
		if ($id) {
			$append .= "&id=" . $id;
		}
		
		$parent_id = $this->input->get('parent_id', 0, 'int');
		if ($parent_id)
		{
			$append .= "&parent_id=" . $parent_id;
		}
		
		$tag = $this->input->get('tag', '', 'string');
		if ($tag != "")
		{
			$append .= "&tag=" . $tag;
		}
		
		$relation = $this->input->get('relation', '', 'string');
		if ($relation != "")
		{
			$append .= "&relation=" . $relation;
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
		$data = $this->input->get("jform", array(), "array");

		// Item has relation to table
		if (is_array($data) && isset($data["relation"]) && !empty($data["relation"])) {
			$parts = explode(".", $data["relation"]);

			if (is_array($parts) && count($parts) >= 2 && $parts[0] == "table") {
				$component = $this->getModel("Component")->getItem(
						$this->getModel("Table")->getItem($parts[1])->component
						);
				
				$this->setRedirect(JRoute::_("index.php?option=com_jdeveloper&view=component&id=" . $component->id . "&active=forms", false));
				$this->redirect();
			}
		}
		
		$parent_id = $this->input->get('parent_id', 0, 'int');
		if ($parent_id)
		{
			$append .= "&parent_id=" . $parent_id;
		}
		
		return $append;
	}
}
?>