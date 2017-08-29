<?php
/**
 * @package     JDeveloper
 * @subpackage  Create.Form
 *
 * @copyright  	Copyright (C) 2014, Tilo-Lars Flasche. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;
JDeveloperLoader::import("form", JDeveloperCREATE);

/**
 * Form Create Class
 *
 * @package     JDeveloper
 * @subpackage  Create.Form
 */
class JDeveloperCreateFormAction extends JDeveloperCreateForm
{		
	/**
	 * The template file
	 *
	 * @var	string
	 */
	protected $templateFile = "form_action.xml";

	/**
	 * @see	JDeveloperCreate
	 */
	protected function initialize()
	{		
		$this->template->addPlaceholders(array(
			"name" => $this->item->name,
			"description" => $this->item->description
		));
		
		return parent::initialize();
	}
}