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
class JDeveloperCreateTableSiteTmplSingularDefault extends JDeveloperCreateTable
{		
	/**
	 * The template file
	 *
	 * @var	string
	 */
	protected $templateFile = "site.views.singular.tmpl.default.php";

	/**
	 * Check whether file should be created or not
	 *
	 * @return	boolean
	 */
	protected function condition()
	{
		return $this->table->params['frontend_details'];
	}
	
	protected function initialize()
	{
		$this->template->addPlaceHolders(
			array(
			'table_body' => $this->tableBody()
			)
		);
		
		return parent::initialize();
	}
	
	private function tableBody()
	{
		$template = $this->loadSubtemplate('tablebody.txt');
		$buffer = '';
		
		foreach ($this->fields as $field)
		{
			if ( ! (int) $field->get('frontend_item', 0) ) continue;
			$template->addPlaceholders( array('field' => $field->name), true );
			$buffer .= $template->getBuffer();
		}
		
		return (!empty($buffer)) ? $buffer : '';
	}
}