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
class JDeveloperCreateComponentAdminInstall extends JDeveloperCreateComponent
{		
	/**
	 * The template file
	 *
	 * @var	string
	 */
	protected $templateFile = "admin.sql.install.mysql.utf8.sql";
	
	protected function condition()
	{
		return !empty($this->tables);
	}
	
	protected function initialize()
	{
		$buffer = array();
		
		foreach ($this->tables as $table)
		{
			$create = JDeveloperCreate::getInstance("table.admin.sql", array("item_id" => $table->id));
			$buffer[] = $create->getBuffer();
		}
		
		$this->template = new JDeveloperTemplate(implode("\n\n", $buffer), false);		
		return parent::initialize();
	}
}