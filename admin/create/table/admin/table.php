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
class JDeveloperCreateTableAdminTable extends JDeveloperCreateTable
{		
	/**
	 * The template file
	 *
	 * @var	string
	 */
	protected $templateFile = "admin.tables.singular.php";

	protected function initialize()
	{
		// Table translations
		$this->getLanguage()->addKeys(array(
			'VIEW_' . $this->table->plural . '_TITLE'		=> $this->table->plural,
			'VIEW_' . $this->table->singular . '_TITLE'	=> $this->table->singular,
			'FIELD_' . $this->table->pk . '_LABEL'			=> ucfirst($this->table->pk),
			'FIELD_' . $this->table->pk . '_DESC'			=> ucfirst($this->table->pk),
			'FIELD_' . $this->table->pk . '_ORDERING_ASC'	=> JText::sprintf('COM_JDEVELOPER_ORDER_ASC', ucfirst($this->table->pk)),
			'FIELD_' . $this->table->pk . '_ORDERING_DESC'	=> JText::sprintf('COM_JDEVELOPER_ORDER_DESC', ucfirst($this->table->pk))
		), $this->table->name);

		$this->getLanguage()->addKeys(array(
			'SUBMENU_' . $this->table->name => $this->table->singular
		));

		// Fields translations
		foreach ($this->fields as $field)
		{
			$pfx = $this->table->name . '_FIELD_' . $field->name;
			
			$this->getLanguage()->addKeys(array(
				"LABEL"			=> ucfirst($field->name),
				"DESC"			=> ucfirst($field->description),
				"ORDERING_ASC"	=> JText::sprintf('COM_JDEVELOPER_ORDER_ASC', ucfirst($field->name)),
				"ORDERING_DESC"	=> JText::sprintf('COM_JDEVELOPER_ORDER_DESC', ucfirst($field->name)),
				"KEEP"			=> JText::sprintf('COM_JDEVELOPER_KEEP_VALUE', ucfirst($field->name)),
				"FILTER"		=> '- Select ' . ucfirst($field->name) . ' -'
			), $pfx);
		}

		// Relations translations
		if (isset($this->table->params["relations"]))
		{
			foreach ($this->table->params["relations"] as $relation)
			{
				$table = $this->getModel("Table")->getItem($relation);
				$pfx = $this->table->name . '_FIELD_' . $table->singular;

				$this->getLanguage()->addKeys(array(
					"LABEL"			=> ucfirst($table->singular),
					"DESC"			=> ucfirst($table->singular),
					"ORDERING_ASC"	=> JText::sprintf('COM_JDEVELOPER_ORDER_ASC', ucfirst($table->singular)),
					"ORDERING_DESC"	=> JText::sprintf('COM_JDEVELOPER_ORDER_DESC', ucfirst($table->singular)),
					"KEEP"			=> JText::sprintf('COM_JDEVELOPER_KEEP_VALUE', ucfirst($table->singular)),
					"FILTER"		=> '- Select ' . ucfirst($table->singular) . ' -'
				), $pfx);
			}
		}
		
		// System translations
		$this->getLanguage("sys")->addKeys(array(
			$this->table->plural	=> $this->table->plural,
			$this->table->singular	=> $this->table->singular
		), "", false);

		return parent::initialize();
	}
}