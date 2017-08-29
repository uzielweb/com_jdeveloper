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
class JDeveloperCreateTableSiteTmplSingularEditMenu extends JDeveloperCreateTable
{		
	/*
	 * Look for language keys in the template and add them
	 *
	 * @var	boolean
	 */
	protected $getLangKeys = true;

	/**
	 * The template file
	 *
	 * @var	string
	 */
	protected $templateFile = "site.views.singular.tmpl.edit.xml";

	/**
	 * Check whether file should be created or not
	 *
	 * @return	boolean
	 */
	protected function condition()
	{
		return ($this->table->params['frontend'] || $this->table->params['frontend_categories']) && $this->table->params['frontend_edit'];
	}
	
	protected function initialize()
	{
		$name = strtoupper($this->component->name) . "_" . strtoupper($this->table->name);
		$this->addLanguageKeys(array("COM_" . $name . "_VIEW_FORM_TITLE", "COM_" . $name . "_VIEW_FORM_DESC"), "sys");
		return parent::initialize();
	}
}