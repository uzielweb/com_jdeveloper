<?php
/**
 * @package     JDeveloper
 * @subpackage  Create.Template
 *
 * @copyright  	Copyright (C) 2014, Tilo-Lars Flasche. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;
JDeveloperLoader::import("template", JDeveloperCREATE);

/**
 * Template Create Class
 *
 * @package     JDeveloper
 * @subpackage  Create.Template
 */
class JDeveloperCreateTemplateError extends JDeveloperCreateTemplate
{		
	/**
	 * The template file
	 *
	 * @var	string
	 */
	protected $templateFile = "error.php";
}