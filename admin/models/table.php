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
 * JDeveloper Table Model
 *
 * @package     JDeveloper
 * @subpackage  Models
 */
class JDeveloperModelTable extends JDeveloperModelAdmin
{
	/**
	 * Method to delete one or more records.
	 *
	 * @param   array  &$pks  An array of record primary keys.
	 *
	 * @return  boolean  True if successful, false if an error occurs.
	 *
	 * @since   12.2
	 */
	public function delete(&$pks)
	{
		$db = JFactory::getDbo();

		// Delete fields which belong to the table
		if ( (int) JComponentHelper::getParams('com_jdeveloper')->get('delete_fields') );
		{
			$model = JModelLegacy::getInstance('Field', 'JDeveloperModel');
			
			foreach ($pks as $pk)
			{
				$query = $db->getQuery(true);
				$db->setQuery($query->select('a.id')->from('#__jdeveloper_fields AS a')->where('a.table = ' . $db->quote((int) $pk)));
				$ids = $db->loadRowList();
				$keys = array();
				foreach ($ids as $id) $keys[] = $id[0];
				$model->delete($keys);
				
				// Delete table xml file
				if (JFile::exists(JDeveloperARCHIVE . "/tables/table_" . $pk . ".xml"))
				 JFile::delete(JDeveloperARCHIVE . "/tables/table_" . $pk . ".xml");
			}
		}
		
		// Delete forms which belong to the table
		foreach ($pks as $pk)
		{
			$model = JModelLegacy::getInstance('Form', 'JDeveloperModel');
			$ids = $this->getFormRootIds($pk);
			$keys = array();
			
			foreach ($ids as $id) {
				$keys[] = $id[0];
			}
			
			$model->delete($keys);
		}
		
		return parent::delete($pks);
	}

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
	public function getTable($type = 'Table', $prefix = 'JDeveloperTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}
	
	/**
	 * Get root ids of forms which belong to a table
	 * 
	 * @param int	$id		The table id
	 * 
	 * @return array	The root ids
	 */
	public function getFormRootIds($id) {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select("a.id")
			->from("#__jdeveloper_forms AS a")
			->where("a.relation LIKE 'table." . $id . "%'")
			->where("a.level = 1");
		$db->setQuery($query);
			
		return $db->loadRowList();
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
		JDeveloperLoader::import("create");

		if (false === $item = parent::getItem($pk))
		{
			return false;
		}
		
		$db = JFactory::getDbo();
		$params = JComponentHelper::getParams('com_jdeveloper');
		
		// Create database table name
		if (!empty($item->component)) {
			$component = JModelLegacy::getInstance('Component', 'JDeveloperModel')->getItem($item->component);
			
			// Only add component name to table name if it is not added already
			if (!preg_match("/^". $component->name ."/", $item->name)) {
				$item->dbname = $params->get('add_component_name_to_table_name') ? $component->name . '_' . $item->name : $item->name;
			}
			else {
				$item->dbname = $item->name;
			}
		}
		else {
			$item->dbname = $item->name;
		}
				
		// Set first table field as main field
		$db->setQuery("SELECT f.name FROM #__jdeveloper_fields AS f WHERE f.table = " . $db->quote($item->id) . " ORDER BY f.ordering");
		$item->mainfield = $db->loadResult();

		// Get Joomla core fields
		$registry = new JRegistry();
		$registry->loadString($item->jfields);
		$item->jfields = $registry->toArray();
		
		// Get related form item
		$table = JTable::getInstance("Form", "JDeveloperTable");
		
		if ($table->load(array("tag" => "form", "relation" => "table." . $item->id . ".form"), true)) {
			$item->form_id = $table->id;
		}
		else {
			$item->form_id = 0;
		}
		
		return $item;
	}
	
	/**
	 * Get id of last item
	 * 
	 * @return int	The id of the last item
	 */
	public function getLastItemId() {
		$db = JFactory::getDbo();
		
		$query = $db->getQuery(true)
		->select("a.id")
		->from("#__jdeveloper_" . $this->getName() . "s AS a")
		->order("a.id DESC LIMIT 1");
		
		$db->setQuery($query);

		return $db->loadResult();
	}

	/**
	 * Check if table exists in database
	 * 
	 * @param unknown $pk	id of table
	 * 
	 * @return	true if table exists, false otherwise
	 */
	public function isInstalled($pk)
	{
		$db = JFactory::getDbo();
		$list = $db->getTableList();

		if (in_array($db->getPrefix() . $this->getItem($pk)->dbname, $list))
			return true;
		
		return false;
	}
	
	/**
	 * @see JModelAdmin::save()
	 */
	public function save($data)
	{
		if (!parent::save($data))
			return false;

		// New item
		if (!isset($data["id"]) || empty($data["id"])) {
			// Get id of last item
			$db = JFactory::getDbo();
			$query = $db->getQuery(true)
				->select("a.id")
				->from("#__jdeveloper_tables AS a")
				->order("a.id DESC LIMIT 1");
			$db->setQuery($query);
			$id = $db->loadResult();
			$item = $this->getItem($id);
			
			if (!$this->createForms($id)) {
				return false;
			}
		}
		
		return true;
	}
	
	/**
	 * Create table forms
	 * 
	 * @param int	$id	The table id
	 * 
	 * @return boolean	true on success, false on error
	 */
	public function createForms($id) {
		$model = JModelLegacy::getInstance("Form", "JDeveloperModel");

		$form = array(
				"parent_id" => "1",
				"tag" => "form",
				"relation" => "table." . $id . ".form",
				"name" => "table." . $id . ".form",
				"alias" => "table." . $id . ".form"
		);
			
		if (!$model->save($form)) {
			$this->setError("Couldn't create form of table");
			return false;
		}
		
		return true;
	}
	
	/**
	 * Compare JDeveloper table to database table and get columns that don't exist in JDeveloper table
	 * 
	 * @param	int				$id			JDeveloper table id
	 * @param	string			$dbtable	Name of the table in the database
	 * @param	array<string>	$ignore		Ignore fields with this name
	 *
	 * @return	array<object>	Columns that don't exist in the JDeveloper table 	
	 */
	public function compareToDatabaseTable($id, $dbtable, $ignore = array())
	{
		$db = JFactory::getDbo();
		$columns = $db->getTableColumns($db->getPrefix() . $dbtable, false);
		$table = $this->getItem($id);
		$fields = JModelLegacy::getInstance("Fields", "JDeveloperModel")->getTableFields($id);
		$add = array();

		foreach ($columns as $column) {
			$match = false;

			// Look if there is a field with similar name in the table's fields
			foreach ($fields as $field) {			
				// Exclude relation columns from search
				if (isset($table->params["relations"])) {
					foreach ($table->params["relations"] as $relation) {
						$relation = $this->getItem($relation)->singular;
						if (strtolower($column->Field) == strtolower($relation)) {
							$match = true;
							break;
						}
					}
				}

				// Exclude ignored columns from search
				if (strtolower($column->Field) == strtolower($field->name) || in_array($column->Field, $ignore)) {
					$match = true;
					break;
				}
			}
			
			// Column hasn't been found in the table's fields
			if (!$match)
				$add[] = $column;
		}
		
		return $add;
	}
	
	/**
	 * Toggle jfield value
	 * 
	 * @param int		$id			Item id
	 * @param string	$jfield		JField name
	 * 
	 * @return boolean	true on success, false on error
	 */
	public function toggleJfield($id, $jfield) {
		$table = $this->getTable();
		
		if ($table->load(array("id" => $id), true)) {
			$jfields = json_decode($table->jfields);
			
			if (!isset($jfields->$jfield)) {
				return false;
			}
			
			if ($jfields->$jfield == "0") {
				$jfields->$jfield = "1";
			} else {
				$jfields->$jfield = "0";
			}
			
			$table->jfields = json_encode($jfields);
			$table->store();
			$table->reset();
			
			return true;
		}
		
		$this->setError("Couldn't load table item");
		return false;
	}
}