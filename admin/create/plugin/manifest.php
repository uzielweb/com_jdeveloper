<?php
/**
 * @package     JDeveloper
 * @subpackage  Create.Plugin
 *
 * @copyright  	Copyright (C) 2014, Tilo-Lars Flasche. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;
JDeveloperLoader::import("plugin", JDeveloperCREATE);

/**
 * Plugin Create Class
 *
 * @package     JDeveloper
 * @subpackage  Create.Plugin
 */
class JDeveloperCreatePluginManifest extends JDeveloperCreatePlugin
{		
	/**
	 * The template file
	 *
	 * @var	string
	 */
	protected $templateFile = "manifest.xml";

	protected function initialize()
	{
		$this->template->addPlaceHolders(
			array( 
			"author" 			=> $this->item->get('author'),
			"author_email" 		=> $this->item->get('author_email'),
			"author_url" 		=> $this->item->get('author_url'),
			"configfields"		=> $this->getConfigFields(),
			"configform"		=> $this->getForm($this->item->form_id),
			"copyright" 		=> $this->item->get('copyright'),
			"creationdate" 		=> date("M Y"),
			"licence"	 		=> $this->item->get('licence'),
			"languages"	 		=> $this->lang(),
			"version"	 		=> $this->item->get('version'),
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
	 * Get language
	 * 
	 * @return string
	 */
	private function lang()
	{
		$buffer = '';
		$tname = $this->item->name;
		
		foreach ($this->item->params['languages'] as $lang)
		{
			$buffer .= "\n\t\t<language tag=\"$lang\">$lang." . $this->type . ".ini</language>";
			$buffer .= "\n\t\t<language tag=\"$lang\">$lang." . $this->type . ".sys.ini</language>";
		}

		return $buffer;
	}
}