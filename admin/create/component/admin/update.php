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
JDeveloperLoader::importHelper("table");

/**
 * Component Create Class
 *
 * @package     JDeveloper
 * @subpackage  Create.Component
 */
class JDeveloperCreateComponentAdminUpdate extends JDeveloperCreateComponent
{		
	protected function condition()
	{
		return !empty($this->tables);
	}
	
	public function initialize()
	{
		$buffer = array();
		$model = $this->getModel("Field");
		$db = JFactory::getDbo();
		
		foreach ($this->tables as $table)
		{
			if (in_array($table->dbname, $db->getTableList()))
			{
				$add = JDeveloperHelperTable::missingExtern($table->id, "#__" . $table->dbname);
				$drop = JDeveloperHelperTable::missingIntern($table->id, "#__" . $table->dbname);
				
				foreach ($add as $field) $buffer[] = "ALTER TABLE #__$table->dbname ADD " . $model->toSQL($field) . ";";
				foreach ($drop as $column) $buffer[] = "ALTER TABLE #__$table->dbname DROP `" . $column->Field . "`;";
			}
		}
		
		if (!empty($buffer))
		{
			$this->template->addPlaceholders(array("code" => implode("\n", $buffer)));
		}
		else
		{
			$this->template->addPlaceholders(array("code" => "\n"));
		}

		return parent::initialize();
	}	
	
	protected function getTemplate()
	{
		return new JDeveloperTemplate("##code##", false);
	}
	
	public function write($path = "")
	{
		return parent::write($this->createDir . "/admin/sql/updates/" . $this->component->version . ".sql");		
	}
}