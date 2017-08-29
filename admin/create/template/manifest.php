<?php
/**
 * @package     JDeveloper
 * @subpackage  Create.Template
 *
 * @copyright  	Copyright (C) 2014, Tilo-Lars Flasche. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;
JDeveloperLoader::import("template", JDeveloperCREATE);

/**
 * Template Create Class
 *
 * @package     JDeveloper
 * @subpackage  Create.Template
 */
class JDeveloperCreateTemplateManifest extends JDeveloperCreateTemplate
{		
	/**
	 * The template file
	 *
	 * @var	string
	 */
	protected $templateFile = "templateDetails.xml";

	protected function initialize()
	{
		$this->template->addPlaceHolders(
			array( 
			'author' 			=> $this->item->get('author'),
			'author_email' 		=> $this->item->get('author_email'),
			'author_url' 		=> $this->item->get('author_url'),
			'configfields'		=> $this->getConfigFields(),
			'configform'		=> $this->getForm($this->item->form_id),
			'copyright' 		=> $this->item->get('copyright'),
			'creationdate' 		=> date("M Y"),
			'licence'	 		=> $this->item->get('licence'),
			'version'	 		=> $this->item->get('version'),
			'languages'	 		=> $this->lang()
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
			$buffer .= "\n\t\t<language tag=\"$lang\">$lang.tpl_$tname.ini</language>";
			$buffer .= "\n\t\t<language tag=\"$lang\">$lang.tpl_$tname.sys.ini</language>";
		}

		return $buffer;
	}
}