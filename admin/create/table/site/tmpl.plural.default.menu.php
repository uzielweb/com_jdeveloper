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
class JDeveloperCreateTableSiteTmplPluralDefaultMenu extends JDeveloperCreateTable
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
	protected $templateFile = "site.views.plural.tmpl.default.xml";

	/**
	 * Check whether file should be created or not
	 *
	 * @return	boolean
	 */
	protected function condition()
	{
		return $this->table->params['frontend'];
	}
	
	protected function initialize()
	{
		$name = strtoupper($this->component->name) . "_" . strtoupper($this->table->name);
		$this->addLanguageKeys(array("COM_" . $name . "_VIEW_DEFAULT_TITLE", "COM_" . $name . "_VIEW_DEFAULT_DESC"), "sys");
		return parent::initialize();
	}
}