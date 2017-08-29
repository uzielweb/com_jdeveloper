<?php
/**
 * @package     JDeveloper
 * @subpackage  Helpers
 *
 * @copyright  	Copyright (C) 2014, Tilo-Lars Flasche. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

/**
 * JDeveloper Field Helper
 *
 * @package     JDeveloper
 * @subpackage  Helpers
 */
class JDeveloperHelperField
{
	/**
	 * Get database field type
	 *
	 * @param	string	The joomla field type
	 *
	 * @return	string	The database field type
	 */
	public static function getDbType($type)
	{
		if ($type == "textfield" || $type == "editor") return "TEXT";
		else return "VARCHAR";
	}

	/**
	 * Get field SQL syntax
	 *
	 * @param	object	The item
	 *
	 * @return	string	The field SQL syntax
	 */
	public static function toSQL($item)
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
	public static function isCoreField($name)
	{
		return in_array($name, array('access', 'alias', 'asset_id', 'catid', 'checked_out', 'checked_out_time', 'created', 'created_by', 'created_by_alias', 'hits',
			'language',	'metadata', 'metadesc', 'metakey', 'modified', 'modified_by', 'ordering', 'params', 'publish_up', 'publish_down', 'published', 'version'));
	}

}