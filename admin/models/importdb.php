<?php
/**
 * @package     JDeveloper
 * @subpackage  Models
 *
 * @copyright  	Copyright (C) 2014, Tilo-Lars Flasche. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

/**
 * JDeveloper ImportDb Model
 *
 * @package     JDeveloper
 * @subpackage  Models
 */
class JDeveloperModelImportDb extends JModelLegacy
{
	/**
	 * Get joomla core fields from table
	 *
	 * @param	string	$table	The table
	 *
	 * @return	string	The core field information as json string
	 */
	public function getCoreFields($table)
	{
		$table = str_replace("#__", "", $table);
		$store = "corefields.$table";
		
		if (isset($this->cache[$store]))
		{
			return $this->cache[$store];
		}
		
		$db = JFactory::getDbo();
		$corefields = array();
		$sql = $db->getTableCreate("#__" . $table)["#__" . $table];
		
		$corefields["access"] = preg_match('/access/i', $sql) ? 1 : 0;
		$corefields["alias"] = preg_match('/alias/i', $sql) ? 1 : 0;
		$corefields["asset_id"] = preg_match('/asset_id/i', $sql) ? 1 : 0;
		$corefields["catid"] = preg_match('/catid/i', $sql) ? 1 : 0;
		$corefields["ordering"] = preg_match('/ordering/i', $sql) ? 1 : 0;
		$corefields["published"] = preg_match('/state|published/i', $sql) ? 1 : 0;
		$corefields["checked_out"] = preg_match('/checked_out/i', $sql) ? 1 : 0;
		$corefields["created"] = preg_match('/created/i', $sql) ? 1 : 0;
		$corefields["created_by"] = preg_match('/created_by/i', $sql) ? 1 : 0;
		$corefields["created_by_alias"] = preg_match('/created_by_alias/i', $sql) ? 1 : 0;
		$corefields["hits"] = preg_match('/hits/i', $sql) ? 1 : 0;
		$corefields["images"] = preg_match('/images/i', $sql) ? 1 : 0;
		$corefields["language"] = preg_match('/language/i', $sql) ? 1 : 0;
		$corefields["metadata"] = preg_match('/metadata/i', $sql) ? 1 : 0;
		$corefields["metadesc"] = preg_match('/metadesc/i', $sql) ? 1 : 0;
		$corefields["metakey"] = preg_match('/metakey/i', $sql) ? 1 : 0;
		$corefields["modified"] = preg_match('/modified/i', $sql) ? 1 : 0;
		$corefields["modified_by"] = preg_match('/modified_by/i', $sql) ? 1 : 0;
		$corefields["params"] = preg_match('/params/i', $sql) ? 1 : 0;
		$corefields["publish_up"] = preg_match('/publish_up/i', $sql) ? 1 : 0;
		$corefields["publish_down"] = preg_match('/publish_down/i', $sql) ? 1 : 0;
		$corefields["version"] = preg_match('/version/i', $sql) ? 1 : 0;

		// Add the items to the internal cache.
		$this->cache[$store] = $corefields;
		return $this->cache[$store];
	}

	/**
	 * Get fields from database tabe
	 *
	 * @param	string			$name		The database table name
	 * @param	int				$table		The jdeveloper table id
	 * @param	array<string>	$ignore		Ignore these field names
	 *
	 * @return	array<object>	The table fields
	 */
	public function getFields($name, $table, $ignore = array())
	{
		$store = "fields.$name";
		
		if (isset($this->cache[$store]))
		{
			return $this->cache[$store];
		}
		
		$name = str_replace("#__", "", $name);
		$db = JFactory::getDbo();
		$columns = $db->setQuery("SHOW FULL COLUMNS FROM #__" . $name)->loadObjectList();
		$fields = array();
		
		foreach ($columns as $column)
		{
			// If colums is primary key continue
			if ($column->Key == "PRI" || in_array($column->Field, $ignore) || $this->isCoreField($column->Field)) continue;
			
			preg_match("/(^[A-Za-z]*)/", $column->Type, $type);
			preg_match("/\(([0-9]*)\)/", $column->Type, $length);
			
			// Set field properties
			$field = array();
			$field["id"] = 0;
			$field["name"] = $column->Field;
			$field["table"] = $table;
			$field["type"] = $this->getFieldType($type[1]);
			$field["dbtype"] = strtoupper($type[1]);
			$field["rule"] = "";
			$field["label"] = ucfirst($column->Field);
			$field["description"] = $column->Comment;
			$field["maxlength"] = (!empty($length)) ? $length[1] : 0;
			$field["params"] = array();
			$field["params"]["filter"] = "";
			$field["params"]["size"] = 0;
			$field["params"]["default"] = $column->Default;
			$field["params"]["class"] = "inputbox";
			$field["params"]["sortable"] = 0;
			$field["params"]["frontend_list"] = 1;
			$field["params"]["frontend_item"] = 1;
			$field["params"]["searchable"] = 0;
			$field["params"]["listfilter"] = 0;
			$field["params"]["readonly"] = 0;
			$field["params"]["disabled"] = 0;
			$field["params"]["required"] = 0;
			
			$fields[] = $field;
		}
		
		// Add the items to the internal cache.
		$this->cache[$store] = $fields;
		return $this->cache[$store];
	}

	/**
	 * Get table from database
	 *
	 * @param	string	$name		The table name
	 * @param	int		$component	The component id
	 *
	 * @return	object	The table
	 */
	public function getDbTable($name, $component = 0)
	{
		$store = "dbtable.$name";
		
		if (isset($this->cache[$store]))
		{
			return $this->cache[$store];
		}
		
		$table = array();
		
		$table["id"]		= 0;
		$table["name"]		= str_replace("#__", "", $name);
		$table["component"]	= $component;
		$table["pk"]		= "id";
		$table["plural"]	= str_replace("#__", "", $name) . "s";
		$table["singular"]	= str_replace("#__", "", $name);
		$table["jfields"]	= $this->getCoreFields($name);
		$table["params"]	= array();
		$table["params"]["frontend"] = 1;
		$table["params"]["frontend_details"] = 1;
		$table["params"]["frontend_edit"] = 0;
		$table["params"]["frontend_menu_list"] = 1;
		$table["params"]["feed"] = 0;
		
		// Add the items to the internal cache.
		$this->cache[$store] = $table;
		return $this->cache[$store];
	}
	
	/**
	 * Get database field type
	 *
	 * @param	string	The joomla field type
	 *
	 * @return	string	The database field type
	 */
	public function getFieldType($type)
	{
		if (preg_match("/.*TEXT.*/i", $type)) return "textarea";
		else return "text";
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
		return in_array($name, array('access', 'alias', 'asset_id', 'catid', 'checked_out', 'checked_out_time', 'created', 'created_by', 'created_by_alias', 'featured', 'hits', 'images',
			'language',	'metadata', 'metadesc', 'metakey', 'modified', 'modified_by', 'ordering', 'params', 'publish_up', 'publish_down', 'published', 'state', 'version', 'xreference'));
	}
}