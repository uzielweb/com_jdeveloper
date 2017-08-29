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
class JDeveloperCreateComponentAdminExec extends JDeveloperCreateComponent
{		
	/**
	 * The template file
	 *
	 * @var	string
	 */
	protected $templateFile = "admin.component.php";

	protected function getFilePath()
	{
		return $this->component->name . '.php';
	}

	protected function initialize()
	{
		// Global translations
		$this->getLanguage()->addKeys(array(
			"COM_" . strtoupper($this->component->name)	=> $this->component->display_name
		), "", false);
		$this->getLanguage()->addKeys(array(
			"N_ITEMS_UNPUBLISHED"	=> JText::_('COM_JDEVELOPER_N_ITEMS_UNPUBLISHED'),
			"N_ITEMS_ARCHIVED"		=> JText::_('COM_JDEVELOPER_N_ITEMS_ARCHIVED'),
			"N_ITEMS_TRASHED"		=> JText::_('COM_JDEVELOPER_N_ITEMS_TRASHED'),
			"N_ITEMS_DELETED"		=> JText::_('COM_JDEVELOPER_N_ITEMS_DELETED'),
			"PARAMS_FIELDSET_LABEL"	=> JText::_('COM_JDEVELOPER_PARAMS_FIELDSET_LABEL')
		));

		// System translations
		$this->getLanguage("sys")->addKeys(array(
			"COM_" . strtoupper($this->component->name)	=> $this->component->display_name
		), "", false);
		$this->getLanguage("sys")->addKeys(array(
			strtoupper($this->component->name)	=> $this->component->display_name
		), "", false);

		return parent::initialize();
	}
}