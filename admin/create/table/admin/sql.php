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
class JDeveloperCreateTableAdminSql extends JDeveloperCreateTable
{		
	/**
	 * The template file
	 *
	 * @var	string
	 */
	protected $templateFile = "admin.sql.install.mysql.utf8.sql";

	protected function initialize()
	{
		$this->template->addPlaceholders(array(
			'pk' 		=> $this->table->pk, 
			'relations' => $this->relations(),
			'sql' 		=> $this->sql(),
			'table_db' 	=> $this->table->dbname
		));

		return parent::initialize();
	}
	
	private function sql()
	{
		$install = array();
		
		foreach ($this->fields as $field)
		{			
			$install[] = $this->getModel('Field')->toSQL($field);
		}
		
		if (!empty($install))
		{
			return "\n\t" . implode(",\n\t", $install) . ",";
		}
		else
		{
			return "";
		}
	}
	
	private function relations()
	{
		$buffer = "";

		if (isset($this->table->params["relations"]))
		{
			$buffer .= "\n\t";
			
			foreach ($this->table->params["relations"] as $relation)
			{
				$table = $this->getModel("Table")->getItem($relation);
				$buffer .= "`" . strtolower($table->singular) . "` int(10) unsigned NOT NULL DEFAULT '0',";
			}
		}

		return $buffer;
	}

	/**
	 * @see	JDeveloperCreate
	 */
	public function write($path = '')
	{
		return true;
	}
}