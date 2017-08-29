<?php
/**
 * @package     JDeveloper
 * @subpackage  Create.Form
 *
 * @copyright  	Copyright (C) 2014, Tilo-Lars Flasche. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;
JDeveloperLoader::import("form", JDeveloperCREATE);

/**
 * Form Create Class
 *
 * @package     JDeveloper
 * @subpackage  Create.Form
 */
class JDeveloperCreateFormForm extends JDeveloperCreateForm
{		
	/**
	 * The template file
	 *
	 * @var	string
	 */
	protected $templateFile = "form.xml";

	/**
	 * @see	JDeveloperCreate
	 */
	protected function initialize() {
		$model = $this->getModel("Forms");
		$model->setState("parent_id", $this->item->id);
		$children = $model->getItems(); 
		
		$this->template->addPlaceholders(array(
			"form" => $this->getXML($this->item->id)
		));

		return parent::initialize();
	}
	
	/**
	 * Create XML
	 */
	private function getXML($id) {
		$model = $this->getModel("Form");
		$item = $model->getItem($id);
		$buffer = "";

		// Add attributes
		$std_attribs = array("name", "label", "type", "description", "default", "class", "maxlength",
				"validation", "filter", "disabled", "readonly", "required");
		$buffer .= str_repeat("\t", $item->level) . "<" . $item->tag;
		
		if ($item->level > 1) {
			foreach ($std_attribs as $attrib) {
				if ($item->$attrib)
					$buffer .= " " . $attrib . "=" . "\"" . $item->$attrib . "\"";
			}
		}

		// Get children
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select("*")
			->from("#__jdeveloper_forms AS a")
			->where("a.parent_id = " . $item->id);
		$children = $db->setQuery($query)->loadObjectList();
		
		// Create XML code for children
		if (!count($children)) {
			$buffer .= " />";
		}
		else {
			$buffer .= ">";
			foreach ($children as $child) {
				$buffer .= "\n" . $this->getXML($child->id);
			}
			$buffer .= "\n" . str_repeat("\t", $item->level) . "</" . $item->tag . ">";
		}
		
		return $buffer;
	}
}