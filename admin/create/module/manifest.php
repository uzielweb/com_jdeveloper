<?php
/**
 * @package     JDeveloper
 * @subpackage  Create.Module
 *
 * @copyright  	Copyright (C) 2014, Tilo-Lars Flasche. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;
JDeveloperLoader::import("module", JDeveloperCREATE);

/**
 * Module Create Class
 *
 * @package     JDeveloper
 * @subpackage  Create.Modue
 */
class JDeveloperCreateModuleManifest extends JDeveloperCreateModule
{		
	/**
	 * The template file
	 *
	 * @var	string
	 */
	protected $templateFile = "module.xml";
	
	public function initialize()
	{
		$this->template->addPlaceHolders(
			array(
				"configfields" => $this->getConfigFields(),
				"configform" => $this->getForm($this->item->form_id),
				"languages" => $this->lang()
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
		$fields = $model->getChildren($this->item->form_id, 1);
	
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

	/**
	 * Get languages
	 * 
	 * @return string
	 */
	private function lang()
	{
		$buffer = '';
		$name = $this->item->name;
		$languages = $this->item->params["languages"];

		foreach ($languages as $lang)
		{
			$buffer .= "\n\t\t<language tag=\"$lang\">language/$lang.mod_$name.ini</language>";
			$buffer .= "\n\t\t<language tag=\"$lang\">language/$lang.mod_$name.sys.ini</language>";
		}

		return $buffer;
	}

}