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
class JDeveloperCreateComponentAdminAccess extends JDeveloperCreateComponent
{		
	/**
	 * The template file
	 *
	 * @var	string
	 */
	protected $templateFile = "admin.access.xml";

	protected function initialize()
	{
		$this->template->addPlaceHolders(
			array( 
				"Sections" => $this->sections(),
				"form" => $this->getForm($this->component->form_id_access)
			)
		);
		
		return parent::initialize();
	}
	
	private function sections()
	{
		$buffer = '';
		$template = $this->loadSubtemplate('section.xml');
		
		foreach ($this->tables as $table)
		{
			if ((bool) $table->jfields['asset_id'])
			{
				$template->addPlaceholders(array('singular' => strtolower($table->singular)));
				$template->addAreas(array('published' => (bool) $table->jfields['published']));
				$buffer .= $template->getBuffer();
			}
		}

		return $buffer;
	}
	
	/**
	 * Get config form
	 */
	private function getForm($parent_id) {
		$model = $this->getModel("Form");
		$table = $model->getTable();
		$buffer = "";
	
		foreach ($model->getChildren($parent_id, 1) as $child) {
			$name = $child->tag == "action" ? "form.action" : "form.child";
			$create = JDeveloperCreate::getInstance($name, array("item_id" => $child->id));
				
			if (!$table->isLeaf($child->id)) {
				$create->getTemplate()->addPlaceholders(array(
						"children" => $this->getForm($child->id)
				));
			}
			else {
				$create->getTemplate()->addPlaceholders(array(
					"children" => "",
					"component" => $this->component->name,
					"title" => str_replace(".", "_", $child->name)
				), true);
			}
				
			$result = $create->getBuffer();
			$result = explode("\n", $result);
			$buffer .= implode("\n\t", $result);
		}
	
		return $buffer;
	}
}