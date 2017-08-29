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
class JDeveloperCreateTableSiteModelPlural extends JDeveloperCreateTable
{		
	/**
	 * The template file
	 *
	 * @var	string
	 */
	protected $templateFile = "site.models.plural.php";

	/**
	 * Check whether file should be created or not
	 *
	 * @return	boolean
	 */
	protected function condition()
	{
		return $this->table->params['frontend'] || $this->table->params['frontend_categories'];
	}

	protected function initialize()
	{
		$this->template->addPlaceHolders(
			array(
			'filter_fields' => $this->filterFields(),
			'filterby' 		=> $this->filterBy(),
			'relations' => $this->relations(),
			'setstates' 	=> $this->setStates(),
			'where_clause' 	=> $this->search()
			)
		);
		
		$this->template->addAreas(
			array(
			'catidORaccess' => ((bool) $this->table->jfields['catid'] || (bool) $this->table->jfields['access'])
			)
		);
		
		return parent::initialize();
	}
	
	private function filterFields()
	{
		$filter_fields = array('\'state\'');
		
		foreach ($this->fields as $field)
		{
			if ( ! (int) $field->get('sortable', 0) ) continue;
			$filter_fields[] = "'" . $field->name . "'";
		}
		
		return implode(", ", $filter_fields);
	}
	
	private function filterBy()
	{
		$template = $this->loadSubtemplate('filterby.txt');
		$buffer = '';
		
		if (isset($this->table->params["relations"]))
		{
			foreach ($this->table->params["relations"] as $relation)
			{
				$table = $this->getModel("Table")->getItem($relation);
				$template->addPlaceholders(array("field" => strtolower($table->singular)));			
				$buffer .= $template->getBuffer();
			}
		}
				
		foreach ($this->fields as $field)
		{
			if ( !(int) $field->get('listfilter', 0) ) continue;
			$template->addPlaceholders( array('field' => $field->name) );
			$buffer .= $template->getBuffer();
		}
		
		return $buffer;
	}
	
	private function relations()
	{				
		$template = $this->loadSubtemplate('relations.txt');
		$buffer = '';
		
		if (isset($this->table->params["relations"]))
		{
			foreach ($this->table->params["relations"] as $relation)
			{
				$table = $this->getModel("Table")->getItem($relation);
				
				$template->addPlaceholders(array(
					"rel_alias" => $table->name,
					"rel_mainfield" => $table->mainfield,
					"rel_mainfield_alias" => strtolower($table->singular) . "_" . $table->mainfield,
					"rel_plural" => $table->plural,
					"rel_pk" => $table->pk,
					"table_relfield" => strtolower($table->singular),
					"rel_table_db" => $table->dbname,
				));
			
				$buffer .= $template->getBuffer();
			}
		}

		return $buffer;
	}
	
	private function search()
	{
		$search = array();
		
		foreach ($this->fields as $field)
		{
			if ( ! (int) $field->get('searchable', 0) ) continue;
			$search[] = "a.$field->name LIKE \$s ";
		}
		
		return (empty($search)) ? "" : "\$query->where(\"" . implode(". ' OR ", $search) . "\");";
	}
	
	private function setStates()
	{		
		$template = $this->loadSubtemplate('setstate.txt');
		$buffer = '';
		
		if (isset($this->table->params["relations"]))
		{
			foreach ($this->table->params["relations"] as $relation)
			{
				$table = $this->getModel("Table")->getItem($relation);
				$template->addPlaceholders(array("field" => strtolower($table->singular)));			
				$buffer .= $template->getBuffer();
			}
		}
		
		foreach ($this->fields as $field)
		{
			if ( !(int) $field->get('listfilter', 0) ) continue;
			$template->addPlaceholders( array('field' => $field->name) );
			$buffer .= $template->getBuffer();
		}

		return $buffer;
	}
}