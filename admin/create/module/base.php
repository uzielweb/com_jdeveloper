<?php
/**
 * @package     JDeveloper
 * @subpackage  Create.Module
 *
 * @copyright  	Copyright (C) 2014, Tilo-Lars Flasche. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;
JDeveloperLoader::import("module", JDeveloperCREATE);

/**
 * Module Create Class
 *
 * @package     JDeveloper
 * @subpackage  Create.Modue
 */
class JDeveloperCreateModuleBase extends JDeveloperCreateModule
{		
	/**
	 * The template file
	 *
	 * @var	string
	 */
	protected $templateFile = "module.php";
}