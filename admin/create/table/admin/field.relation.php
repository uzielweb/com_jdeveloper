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
class JDeveloperCreateTableAdminFieldRelation extends JDeveloperCreateTable
{		
	/**
	 * The template file
	 *
	 * @var	string
	 */
	protected $templateFile = "admin.models.fields.relation.php";

	public function create()
	{
		$template = $this->getTemplate();

		if (isset($this->table->params["relations"]))
		{
			foreach ($this->table->params["relations"] as $relation)
			{
				$table = $this->getModel("Table")->getItem($relation);
				
				$template->addPlaceholders(array(
					"header" => self::$templateHeader,
					"rel_name" => strtolower($table->singular),
					"rel_mainfield" => $table->mainfield,
					"rel_pk" => $table->pk,
					"rel_table_db" => $table->dbname,
				), true);
			
				$template->addAreas(array(
					"catidORaccess" => $table->jfields["catid"] || $table->jfields["access"],
					"access" => $table->jfields["access"],
					"catid" => $table->jfields["catid"],
					"created_by" => $table->jfields["created_by"]
				));
			
				$buffer = $template->getBuffer();
				
				parent::write($this->createDir . "/admin/models/fields/" . strtolower($table->singular) . ".php");
			}
		}
	}
	
	public function write($path = "")
	{
		return true;
	}
}