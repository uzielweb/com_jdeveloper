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
 * Table Create Class
 *
 * @package     JDeveloper
 * @subpackage  Create.Component
 */
class JDeveloperCreateComponentSiteViewComponent extends JDeveloperCreateComponent
{		
	/**
	 * The template file
	 *
	 * @var	string
	 */
	protected $templateFile = "site.views.component.view.html.php";

	/**
	 * Check whether file should be created or not
	 *
	 * @return	boolean
	 */
	protected function condition()
	{
		return empty($this->tables);
	}
}