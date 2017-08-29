<?php
/**
 * @package     JDeveloper
 * @subpackage  Create.Component
 *
 * @copyright  	Copyright (C) 2014, Tilo-Lars Flasche. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;
JDeveloperLoader::import("component", JDeveloperCREATE);

/**
 * Component Create Class
 *
 * @package     JDeveloper
 * @subpackage  Create.Component
 */
class JDeveloperCreateComponentAdminConfig extends JDeveloperCreateComponent
{		
	/**
	 * The template file
	 *
	 * @var	string
	 */
	protected $templateFile = "admin.config.xml";
	
	/**
	 * @see JDeveloperCreateComponent::initialize()
	 */
	protected function initialize() {
		$this->template->addPlaceholders(
			array(
				"fields" => $this->getConfigFields(),
				"form" => $this->getForm($this->component->form_id)
			)
		);
	
		return parent::initialize();
	}
	
	/**
	 * Get config form fields as xml
	 * 
	 * @return string
	 */
	private function getConfigFields() {
		$model = $this->getModel("Form");
		$fields = $model->getChildren($this->component->form_id, 1);
		
		$buffer = "";

		foreach ($fields as $field) {
			if ($field->level == "2" && $field->tag == "field") {
				$create = JDeveloperCreate::getInstance("form.field", array("item_id" => $field->id));
				$buffer .= $create->getBuffer();
			}
		}
		
		$buffer = explode("\n", $buffer);
		return implode("\n\t\t", $buffer);
	}
	
	/**
	 * Get config form
	 * 
	 * @return string
	 */
	private function getForm($parent_id) {
		$model = $this->getModel("Form");
		$table = $model->getTable();
		$buffer = "";
		
		foreach ($model->getChildren($parent_id, 1) as $child) {
			if ($child->level == 2 && $child->tag == "field")
				continue;
			
			$name = $child->tag == "field" ? "form.field" : "form.child";
			$create = JDeveloperCreate::getInstance($name, array("item_id" => $child->id));
			
			if (!$table->isLeaf($child->id)) {
				$create->getTemplate()->addPlaceholders(array(
					"children" => $this->getForm($child->id)
				));
			}
			else {
				$create->getTemplate()->addPlaceholders(array(
					"children" => ""
				));
			}
			
			$result = $create->getBuffer();
			$result = explode("\n", $result);
			$buffer .= implode("\n\t", $result);
		}
		
		return $buffer;
	}
}