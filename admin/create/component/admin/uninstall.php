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
class JDeveloperCreateComponentAdminUninstall extends JDeveloperCreateComponent
{		
	protected function condition()
	{
		return !empty($this->tables);
	}
	
	public function initialize()
	{
		$buffer = array();
		
		foreach ($this->tables as $table)
		{
			$buffer[] = "DROP TABLE IF EXISTS #__". $table->dbname .";";
		}

		$this->template->addPlaceholders(array("code" => implode("\n", $buffer)));
		
		return parent::initialize();
	}
	
	protected function getTemplate()
	{
		return new JDeveloperTemplate("##code##", false);
	}
	
	public function write($path = "")
	{
		return parent::write($this->createDir . "/admin/sql/uninstall.mysql.utf8.sql");		
	}
}