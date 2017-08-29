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
class JDeveloperCreateTableAdminTmplPluralDefault extends JDeveloperCreateTable
{		
	/**
	 * The template file
	 *
	 * @var	string
	 */
	protected $templateFile = "admin.views.plural.tmpl.default.php";

	protected function initialize()
	{
		$this->template->addPlaceHolders(
			array(
			'table_body' => $this->tableBody(),
			'table_head' => $this->tableHead(),
			)
		);
		
		return parent::initialize();
	}
	
	private function tableHead()
	{
		$template = $this->loadSubtemplate('tablehead.txt');
		$buffer = '';
		
		if (isset($this->table->params["relations"]))
		{
			foreach ($this->table->params["relations"] as $relation)
			{
				$table = $this->getModel("Table")->getItem($relation);
				$template->addPlaceholders( $this->getDefaultPlaceholders(), true );
				$template->addPlaceholders(array("field" => strtolower($table->singular)), true);			
				$buffer .= $template->getBuffer();
			}
		}

		foreach ($this->fields as $field)
		{
			// Don`t create a table column for the mainfield
			if ( preg_match('/' . $this->fields[0]->name . '/i', $field->name) ) continue;
			
			$template->addPlaceholders( $this->getDefaultPlaceholders(), true );
			$template->addPlaceholders( array('field' => $field->name), true );
			$buffer .= $template->getBuffer();
		}

		return $buffer;
	}
	
	private function tableBody()
	{
		$template = $this->loadSubtemplate('tablebody.txt');
		$buffer = '';
				
		if (isset($this->table->params["relations"]))
		{
			foreach ($this->table->params["relations"] as $relation)
			{
				$table = $this->getModel("Table")->getItem($relation);
				$template->addPlaceholders(array("field" => strtolower($table->singular) . "_" . $table->mainfield), true);			
				$buffer .= $template->getBuffer();
			}
		}

		foreach ($this->fields as $field)
		{
			// Don`t create a table column for the mainfield
			if ( preg_match('/' . $this->fields[0]->name . '/i', $field->name) ) continue;
			
			$template->addPlaceholders( array('field' => $field->name), true );
			$buffer .= $template->getBuffer();
		}
				
		return $buffer;
	}
}