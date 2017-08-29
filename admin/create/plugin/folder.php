<?php
/**
 * @package     JDeveloper
 * @subpackage  Create.Plugin
 *
 * @copyright  	Copyright (C) 2014, Tilo-Lars Flasche. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;
JDeveloperLoader::import("plugin", JDeveloperCREATE);

/**
 * Plugin Create Class
 *
 * @package     JDeveloper
 * @subpackage  Create.Plugin
 */
class JDeveloperCreatePluginFolder extends JDeveloperCreatePlugin
{		
	/**
	 * Get the template object
	 *
	 * @return	JDeveloperTemplate
	 */
	protected function getTemplate()
	{
		$this->templateFile = $this->item->folder . ".php";
		return parent::getTemplate();
	}

	/**
	 * @see JDeveloperCreate
	 */
	public function write($path = "")
	{
		$path = $this->createDir . "/" . $this->item->name . ".php";
		return parent::write($path);
	}
}