<?php
/**
 * @package     JDeveloper
 * @subpackage  Create.Table
 *
 * @copyright  	Copyright (C) 2014, Tilo-Lars Flasche. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;
JDeveloperLoader::import("table", JDeveloperCREATE);;

/**
 * Table Create Class
 *
 * @package     JDeveloper
 * @subpackage  Create.Table
 */
/**	
 * Create class for admin view form tmpl edit
 */
class JDeveloperCreateTableAdminTmplSingularEdit extends JDeveloperCreateTable
{		
	/**
	 * The template file
	 *
	 * @var	string
	 */
	protected $templateFile = "admin.views.singular.tmpl.edit.php";

	protected function initialize()
	{		
		$this->template->addPlaceHolders(
			array(
			'fieldboxes' 	=> $this->fieldboxes(),
			)
		);
		
		return parent::initialize();
	}

	private function fieldboxes()
	{
		$template = $this->loadSubtemplate('fieldbox.txt');
		$buffer = '';
		
		if (isset($this->table->params["relations"]))
		{
			foreach ($this->table->params["relations"] as $relation)
			{
				$table = $this->getModel("Table")->getItem($relation);
				$template->addPlaceholders( array('field' => strtolower($table->singular)), true );
				$buffer .= $template->getBuffer();
			}
		}
		foreach ($this->fields as $field)
		{
			if ($field->name == $this->fields[0]->name) continue;
			
			$template->addPlaceholders( array('field' => $field->name), true );
			$buffer .= $template->getBuffer();
		}

		return $buffer;
	}
}