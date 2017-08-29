<?php
/**
 * @package     JDeveloper
 * @subpackage  Library
 *
 * @copyright  	Copyright (C) 2014, Tilo-Lars Flasche. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;
jimport('joomla.filesystem.file');

/**
 * Class for working with database tables
 *
 * @package     JDeveloper
 * @subpackage  Library
 */
class JDeveloperTable
{
	/**
	 * Get the field`s information from a db table
	 *
	 * @param	table	string	The table`s name
	 *
	 * @return	array<JRegistry>	The field`s information
	 */
	public static function getDbTableFields($table, $ignore)
	{
		$db = JFactory::getDBO();
		$fieldlist = array();
		
		$db->setQuery('SHOW COLUMNS FROM ' . $table);
		$dbfields = $db->loadAssocList();
				
		foreach ($dbfields as $dbfield)
		{
			if (in_array($dbfield['Field'], $ignore) || self::isCoreField($dbfield['Field'])) continue;
			$field = new JRegistry();
			
			$field->set('id', 0);
			$field->set('name', $dbfield['Field']);
			$field->set('label', ucfirst($dbfield['Field']));
			$field->set('description', '');
			
			$type = explode('(', $dbfield['Type']);
			$field->set('dbtype', trim(preg_replace('/\)|unsigned/', '', $type[0])));
			
			// Set field maxlength
			if (isset($type[1]))
			{
				$field->set('maxlength', trim(preg_replace('/\)|unsigned/', '', $type[1])));
			}
						
			// Set field type
			if (preg_match('/CHAR|INT|FLOAT|DOUBLE|REAL|DECIMAL/i', $type[0]))
			{
				$field->set('type', 'text');
			}
			elseif (preg_match('/DATE/i', $type[0]))
			{
				$field->set('type', 'calendar');
			}
			elseif (preg_match('/TEXT/i', $type[0]))
			{
				$field->set('type', 'textarea');
			}
			else
			{
				$field->set('type', 'text');
			}
			
			// Set params
			$form = JModelLegacy::getInstance("Field", "JDeveloperModel")->getForm();
			$params = new JRegistry();
			
			foreach ($form->getGroup("params") as $param)
			{
				$params->set($param->fieldname, "");
			}
			
			$field->set('params', $params->toString());
						
			// add field to fieldlist
			$fieldlist[] = $field;
		}
		
		return $fieldlist;
	}
	
	public static function getTableSql($id)
	{
		require_once JDeveloperLIB.DS.'template.php';
		
		$template = new JDeveloperTemplate(JDeveloperPath::dots2ds(JDeveloperTEMPLATES.DS.'com.admin.sql.install.mysql.utf8.create.sql'));
		$table = JModelLegacy::getInstance('Table', 'JDeveloperModel')->getItem($id);
		$component = JModelLegacy::getInstance('Table', 'JDeveloperModel')->getItem($table->component);
		$fields = JModelLegacy::getInstance('Fields', 'JDeveloperModel')->getTableFields($table->id);
		
		if ($component === false) $component = new JObject(array('name' => ''));
		
		$template->addAreas(array(
			'access' 			=> ( (bool) $table->get('access', 0) ),
			'alias' 			=> ( (bool) $table->get('alias', 0) ),
			'asset_id' 			=> ( (bool) $table->get('asset_id', 0) ),
			'catid' 			=> ( (bool) $table->get('catid', 0) ),
			'checked_out'		=> ( (bool) $table->get('checked_out', 0) ),
			'created' 			=> ( (bool) $table->get('created', 0) ),
			'created_by' 		=> ( (bool) $table->get('created_by', 0) ),
			'created_by_alias' 	=> ( (bool) $table->get('created_by_alias', 0) ),
			'hits' 				=> ( (bool) $table->get('hits', 0) ),
			'language' 			=> ( (bool) $table->get('language', 0) ),
			'metadata' 			=> ( (bool) $table->get('metadata', 0) ),
			'metadesc' 			=> ( (bool) $table->get('metadesc', 0) ),
			'metakey' 			=> ( (bool) $table->get('metakey', 0) ),
			'modified' 			=> ( (bool) $table->get('modified', 0) ),
			'modified_by' 		=> ( (bool) $table->get('modified_by', 0) ),
			'ordering' 			=> ( (bool) $table->get('ordering', 0) ),
			'params' 			=> ( !empty($table->params) ),
			'publish' 			=> ( (bool) $table->get('publish_up', 0) || (bool) $table->get('publish_down', 0)),
			'publish_up' 		=> ( (bool) $table->get('publish_up', 0) ),
			'publish_down' 		=> ( (bool) $table->get('publish_down', 0) ),
			'published' 		=> ( (bool) $table->get('published', 0) ),
			'tags' 				=> false,
			'version' 			=> ( (bool) $table->get('version', 0) )
		));
		$template->addPlaceholders(array(
			'table_db'		=> (JComponentHelper::getParams('com_jdeveloper')->get('add_component_name_to_table_name')) ? $component->name . '_' . $table->name : $table->name,
			'pk'			=> $table->pk,
			'sql'			=> '<br>' . self::sqlFields($fields)
		));
		
		$buffer = $template->getBuffer();

		$buffer = preg_replace('/(CREATE TABLE IF NOT EXISTS|NOT NULL|CHARACTER SET|COLLATE|AUTO_INCREMENT|PRIMARY KEY|DEFAULT|COMMENT|unsigned)/', "<span style=\"color:blue\">$0</span>", $buffer);
		$buffer = preg_replace('/`.*`/', "<span style=\"color:orange\">$0</span>", $buffer);
		$buffer = preg_replace('/\'.*\'/', "<span style=\"color:#999999\">$0</span>", $buffer);
		
		return $buffer;
	}
}