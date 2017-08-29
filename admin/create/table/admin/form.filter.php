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
class JDeveloperCreateTableAdminFormFilter extends JDeveloperCreateTable
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
	protected $templateFile = "admin.models.forms.filter_plural.xml";

	protected function initialize()
	{
		$this->template->addPlaceHolders(
			array( 
				'filters' => $this->filters(),
				'relations' => $this->relations(),
			)
		);
		
		return parent::initialize();
	}
	
	private function filters()
	{
		$buffer = '';
		
		foreach ($this->fields as $field)
		{
			if (!isset($field->params['listfilter']) || !$field->params['listfilter']) continue;
			
			$template = $this->loadSubtemplate('filterfield.xml');
			$template->addPlaceholders( $this->getDefaultPlaceholders(), true );
			$template->addPlaceholders( array('field' => $field->name), true );
			$buffer .= $template->getBuffer();
		}

		return $buffer;
	}

	private function relations()
	{
		$template = $this->loadSubtemplate("relation.xml");
		$buffer = '';
		
		if (isset($this->table->params["relations"]))
		{
			foreach ($this->table->params["relations"] as $relation)
			{
				$table = $this->getModel("Table")->getItem($relation);

				$template->addPlaceholders($this->getDefaultPlaceholders(), true);
				
				$template->addPlaceholders(array(
					"rel_name" => strtolower($table->singular)
				), true);
				
				$buffer .= $template->getBuffer();
			}
		}

		return $buffer;
	}
}