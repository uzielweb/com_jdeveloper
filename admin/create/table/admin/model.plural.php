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
class JDeveloperCreateTableAdminModelPlural extends JDeveloperCreateTable
{		
	/**
	 * The template file
	 *
	 * @var	string
	 */
	protected $templateFile = "admin.models.plural.php";

	protected function initialize()
	{
		$this->template->addPlaceHolders(
			array(
			'filter_fields' => $this->filterFields(),
			'filterby' => $this->filterBy(),
			'relations' => $this->relations(),
			'setstates' => $this->setStates(),
			'where_clause' => $this->search()
			)
		);
		
		$this->template->addAreas(
			array(
			'catidORaccess' => ((bool) $this->table->jfields['catid'] || (bool) $this->table->jfields['access'])
			)
		);

		return parent::initialize();
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
			if (!isset($field->params['listfilter']) || !$field->params['listfilter']) continue;
			$template->addPlaceholders( array('field' => $field->name) );
			$buffer .= $template->getBuffer();
		}
		
		return $buffer;
	}
	
	private function filterFields()
	{
		$filter_fields = array("'ordering'", "'state'");
				
		foreach ($this->fields as $field)
		{
			if (!isset($field->params['sortable']) || !$field->params['sortable']) continue;
			$filter_fields[] = "'" . $field->name . "'";
		}
		
		return implode(", ", $filter_fields);
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
			if (!isset($field->params['searchable']) || !$field->params['searchable']) continue;
			$search[] = 'a.' . $field->name . ' LIKE\' . $s ';
		}
		
		return (empty($search)) ? "" : "\$query->where('" . implode(". ' OR ", $search) . ");";
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
			if (!isset($field->params['listfilter']) || !$field->params['listfilter']) continue;
			$template->addPlaceholders( array('field' => $field->name) );
			$buffer .= $template->getBuffer();
		}

		return $buffer;
	}
}