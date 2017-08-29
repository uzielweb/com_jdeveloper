<?php
/**
 * @package     JDeveloper
 * @subpackage  Models
 *
 * @copyright  	Copyright (C) 2014, Tilo-Lars Flasche. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;
JDeveloperLoader::import("models.admin");

/**
 * JDeveloper Field Model
 *
 * @package     JDeveloper
 * @subpackage  Models
 */
class JDeveloperModelField extends JDeveloperModelAdmin
{
	/**
	 * Method to get a table object, load it if necessary.
	 *
	 * @param   string  $name     The table name. Optional.
	 * @param   string  $prefix   The class prefix. Optional.
	 * @param   array   $options  Configuration array for model. Optional.
	 *
	 * @return  JTable  A JTable object
	 *
	 * @since   12.2
	 * @throws  Exception
	 */
	public function getTable($type = 'Field', $prefix = 'JDeveloperTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}
	
	/**
	 * Method to get a single record.
	 *
	 * @param   integer  $pk  The id of the primary key.
	 *
	 * @return  mixed    Object on success, false on failure.
	 *
	 * @since   12.2
	 */
	public function getItem($pk = null)
	{
		$item = parent::getItem($pk);
		$db = JFactory::getDbo();
		$model_table = JModelLegacy::getInstance("Table", "JDeveloperModel");

		if ($item->table)
		{
			$table = JModelLegacy::getInstance("Table", "JDeveloperModel")->getItem($item->table);
			$item->component_name = "com_" . JModelLegacy::getInstance("Component", "JDeveloperModel")->getItem($table->component)->name;
		}
		
		// Exists field in database?
		if ($model_table->isInstalled($item->table))
			$item->isInstalled = $this->dbColumnExists($item, "", true);
		else
			$item->isInstalled = false;

		// Add formfield id
		$query = $db->getQuery(true)->select("id")->from("#__jdeveloper_formfields as ff")->where("ff.name = " . $db->quote($item->type));
		$item->formfield_id = (int) $db->setQuery($query)->loadResult();
		
		// Add formrule id
		$query = $db->getQuery(true)->select("id")->from("#__jdeveloper_formrules as fr")->where("fr.name = " . $db->quote($item->rule));
		$item->formrule_id = (int) $db->setQuery($query)->loadResult();
		
		// Load options
		$registry = new JRegistry();
		$registry->loadString($item->options);
		$item->options = $registry->toArray();
		
		return $item;
	}
	
	/**
	 * @see JModelAdmin::delete()
	 */
	public function delete(&$pks)
	{
		// Look for corresponding form item and delete it
		foreach ($pks as $pk) {
			$model = JModelLegacy::getInstance("Form", "JDeveloperModel");
			$table = $model->getTable();
			$item = $this->getItem($pk);
			
			if ($table->load(array("relation" => "table." . $item->table . ".field." . $item->id), true)) {
				$model->delete($table->id);
			}
		}
		
		return parent::delete($pks);
	}
	
	/**
	 * Check if a database table contains a field like this
	 * 
	 * @param	object	$field		The field object
	 * @param	string	$tablename	The table in which to look for the field
	 * @param	boolean $onlyName	Only check for equal names, otherwise check also length and type
	 * 
	 * @return boolean	true if table contains field like this, false if not
	 */
	public function dbColumnExists($field, $tablename, $onlyName = false)
	{
		$db = JFactory::getDbo();
		
		if ($tablename == "") {
			$tablename = JModelLegacy::getInstance("Table", "JDeveloperModel")->getItem($field->table)->dbname;
		}
		
		$db->setQuery("SHOW COLUMNS FROM " . $db->getPrefix() . $tablename);
		$columns = $db->loadAssocList();
		
		foreach ($columns as $column)
			if ($column["Field"] == $field->name)
				return true;
		
		return false;	
	}
	
	/**
	 * Export field to database
	 * 
	 * @param object	$field
	 * @param string	$tablename
	 */
	public function exportToDatabase($field, $tablename = "")
	{
		$db = JFactory::getDbo();
		
		if ($tablename == "") {
			$tablename = JModelLegacy::getInstance("Table", "JDeveloperModel")->getItem($field->table)->dbname;
		}
		
		$table = JModelLegacy::getInstance("Table", "JDeveloperModel")->getItem($field->table);
		$sql = "ALTER TABLE " . $db->getPrefix() . $table->dbname . " ADD " . $this->toSQL($field);
		
		$db->setQuery($sql)->execute();
	}
	
	/**
	 * Import table column from database
	 * 
	 * @param string	$tablename		The name of the table where to look for the column
	 * @param string	$columnname		The column's name
	 * 
	 * @return	mixed	column object on success, false if not found
	 */
	public function getColumnFromDatabase($tablename, $columnname)
	{
		$db = JFactory::getDbo();
		$columns = $db->getTableColumns($db->getPrefix() . $tablename, false);
				
		foreach ($columns as $column) {
			if ($column->Field == $columnname) {
				return $column;
			}
		}
		
		return false;
	}
	
	/**
	 * Get field SQL syntax
	 *
	 * @param	object	The item
	 *
	 * @return	string	The field SQL syntax
	 */
	public function toSQL($item)
	{
		$sql = "`" . $item->name . "` " . $item->dbtype;
		$sql .= (preg_match('/BINARY|INT|CHAR|DECIMAL|NUMERIC/i', $item->dbtype)) ? "(" . $item->maxlength . ")" : "";
		$sql .= " NOT NULL";
		$sql .= (!empty($item->default)) ? " DEFAULT '" . $item->default . "'" : "";				
		$sql .= (!empty($item->description)) ? " COMMENT '" . $item->description . "'" : "";
		
		return $sql;
	}

	/**
	 * Is this field name a joomla core field name?
	 *
	 * @param	string	$name	The field name
	 *
	 * @return	boolean	True if it is a joomla core fied, false if not
	 */
	public function isCoreField($name)
	{
		return in_array($name, array('access', 'alias', 'asset_id', 'catid', 'checked_out', 'checked_out_time', 'created', 'created_by', 'created_by_alias', 'hits',
			'images', 'language', 'metadata', 'metadesc', 'metakey', 'modified', 'modified_by', 'ordering', 'params', 'publish_up', 'publish_down', 'published', 'version'));
	}
}