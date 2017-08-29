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
class JDeveloperCreateTableSiteTmplSingularEdit extends JDeveloperCreateTable
{		
	/**
	 * The template file
	 *
	 * @var	string
	 */
	protected $templateFile = "site.views.singular.tmpl.edit.php";

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
		$this->template->addPlaceHolders(
			array(
			'tablebody' 	=> $this->tablebody(),
			)
		);

		$this->template->addPlaceHolders( array( 'mainfield' => $this->fields[0]->name ), true );
		
		return parent::initialize();
	}

	private function tablebody()
	{
		$template = $this->loadSubtemplate('tablebody.txt');
		$buffer = '';
		
		if (isset($this->table->params["relations"]))
		{
			foreach ($this->table->params["relations"] as $relation)
			{
				$table = $this->getModel("Table")->getItem($relation);
				$template->addPlaceholders( array('field' => strtolower($table->singular)), true );
				$buffer .= $template->getBuffer();
			}
		}

		foreach ($this->fields as $field)
		{
			$template->addPlaceholders( array('field' => $field->name), true );
			$buffer .= $template->getBuffer();
		}

		return $buffer;
	}
}