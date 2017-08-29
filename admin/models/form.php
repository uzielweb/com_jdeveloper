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
 * JDeveloper Form Model
 *
 * @package     JDeveloper
 * @subpackage  Models
 */
class JDeveloperModelForm extends JDeveloperModelAdmin
{
	/**
	 * @var        string    The prefix to use with controller messages.
	 * @since   1.6
	 */
	protected $text_prefix = 'COM_JDEVELOPER';

	/**
	 * The type alias for this content type.
	 *
	 * @var      string
	 * @since    3.2
	 */
	public $typeAlias = 'com_jdeveloper.form';

	/**
	 * Method to test whether a record can be deleted.
	 *
	 * @param   object    $record    A record object.
	 *
	 * @return  boolean  True if allowed to delete the record. Defaults to the permission set in the component.
	 * @since   1.6
	 */
	protected function canDelete($record)
	{
		if (!empty($record->id))
		{

			$user = JFactory::getUser();
			return $user->authorise('core.delete', $this->typeAlias . '.' . (int) $record->id);
		}
	}

	/**
	 * @see JModelForm::preprocessForm()
	 */
	protected function preprocessForm(JForm $form, $data, $group = "content")
	{
		$app = JFactory::getApplication();
		$input = $app->input;
		
		$form->setFieldAttribute("tag", "default", $input->get("tag", "form", "string"));
		
		parent::preprocessForm($form, $data, $group);
	}

	/**
	 * @see JModelAdmin::prepareTable()
	 */
	protected function prepareTable($table)
	{
		// Set the publish date to now
		$db = $this->getDbo();
	}

	/**
	 * @see JModelAdmin::populateState()
	 */
	protected function populateState()
	{
		$app = JFactory::getApplication('administrator');

		$parentId = $app->input->getInt('parent_id');
		$this->setState('form.parent_id', $parentId);

		// Load the User state.
		$pk = $app->input->getInt('id');
		$this->setState($this->getName() . '.id', $pk);

		// Load the parameters.
		$params = JComponentHelper::getParams('com_jdeveloper');
		$this->setState('params', $params);
	}
	
	/**
	 * @see JModelLegacy::getTable()
	 */
	public function getTable($type = 'Form', $prefix = 'JDeveloperTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}
	
	/**
	 * @see JModelForm::getForm()
	 */
	public function getForm($data = array(), $loadData = true)
	{
		JForm::addRulePath(JPATH_COMPONENT_ADMINISTRATOR.'/models/rules');		
		
		$input = JFactory::getAPplication()->input;
		$options = array('control' => 'jform', 'load_data' => $loadData);
		$form = $this->loadForm($this->typeAlias, $this->name, $options);
		
		if(empty($form))
		{
			return false;
		}
		
		$parent_id = $input->get("parent_id", 0);
		if ($parent_id != 0)
		{
			$form->setFieldAttribute('parent_id', 'default', $parent_id);
		}
		else
		{
			$form->setFieldAttribute('parent_id', 'default', 1);
		}

		return $form;
	}
	
	/**
	 * Load tree
	 * 
	 * @param int	$parent_id	id of parent item
	 * @param int	$maxLevels	number of depth of search in tree
	 * @param array $config		configuration parameters
	 * 
	 * @return array	The results
	 */
	public function getChildren($parent_id, $maxLevels = -1, $config = array()) {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		
		if (!empty($parent_id)) {
			$item = $this->getItem($parent_id);
		} else {
			throw new Exception("Coud not load form children, because no parent id given");
		}
		
		$query->select("*")
			->from("#__jdeveloper_forms AS a")
			->where("a.lft > " . $item->lft . " AND a.rgt < " . $item->rgt)
			->order("a.lft ASC");
		
		if ($maxLevels > -1) {
			$query->where("a.level <= " . ($item->level + $maxLevels));
		}
		
		return $db->setQuery($query)->loadObjectList();
	}
	
	/**
	 * Get root id of relation
	 * 
	 * @param string $relation	Relation name
	 * 
	 * @return	int  Root id
	 */
	public function getRootId($relation)
	{
		$db = JFactory::getDbo();
		
		// Get id of last row
		$query = $db->getQuery(true)
			->select("a.id")
			->from("#__jdeveloper_forms AS a")
			->where("a.relation = '" . $relation . "'")
			->where("a.level = 1");
		$db->setQuery($query);
		$id = $db->loadAssoc()["id"];
		
		return $id;
	}
	
	/**
	 * @see JModelAdmin::getItem()
	 */
	public function getItem($pk = null)
	{
		if (!$item = parent::getItem($pk))
		{			
			throw new Exception('Failed to load item');
		}
		
		if (empty($item->id))
		{
			$item->parent_id = $this->getState('Form.parent_id');
		}

		if (!$item->id)
		{
			$item->created_by = JFactory::getUser()->get('id');
		}
		
		$registry = new JRegistry();
		$registry->loadString($item->options);
		$item->options = $registry->toArray();
		
		return $item;
	}
	
	/**
	 * Create Form from SimpleXMLElement
	 * 
	 * @param SimpleXMLElement	$form		The form
	 * @param string			$relation	Relation to other table
	 * @param string			$name		Root element name
	 * @param int				$parent_id	Id of parent item
	 * @param array				$config		Configuration
	 * @param int				$incr		Increment number
	 * 
	 * @return	boolean	true on success, false otherwise
	 */
	public function importFromXML(SimpleXMLElement $form, $parent_id = 1, $relation = "", $name = "", $config = array(), $incr = 1)
	{
		// Test for max level
		if (isset($config["maxlevel"]) && $incr > $config["maxlevel"])
			return true;
		
		// Test for ignored tags
		if (isset($config["ignore_tags"]) && is_array($config["ignore_tags"]) && in_array($form->getName(), $config["ignore_tags"]))
			return true;

		// Element data
		$data = array(
				"id" => 0,
				"parent_id" => $parent_id,
				"tag" => $form->getName(),
				"relation" => $relation
		);
		
		// Create element name
		$data["name"] = "";
		
		if ($incr > 1) {
			if (isset($form["name"]))
				$data["name"] .= $form["name"];
			elseif (isset($form["value"]))
				$data["value"] .= $form["value"];
			elseif (isset($form["label"]))
				$data["label"] .= $form["label"];
		}
		else {
			$data["name"] .= $relation;
			
			if (!empty($name))
				$data["name"] .= "." . $name;
			else
				$data["name"] .= "." . $form->getName();
		}
		
		// Create element alias
		$data["alias"] = $data["name"];

		// Check if certain attributes exist
		if (isset($form["name"]))
			$data["name"] = $form["name"];	
		if (isset($form["type"]))
			$data["type"] = $form["type"];	
		if (isset($form["label"]))
			$data["label"] = $form["label"];
		if (isset($form["description"]))
			$data["description"] = $form["description"];
		if (isset($form["default"]))
			$data["default"] = $form["default"];
		if (isset($form["class"]))
			$data["class"] = $form["class"];
		if (isset($form["maxlength"]))
			$data["maxlength"] = $form["maxlength"];
		if (isset($form["validation"]))
			$data["validation"] = $form["validation"];
		if (isset($form["filter"]))
			$data["filter"] = $form["filter"];
		if (isset($form["readonly"]))
			$data["readonly"] = $form["readonly"];
		if (isset($form["required"]))
			$data["required"] = $form["required"];

		if (!$this->save($data)) {
			$this->setError("Couldn't import element from XML: " . $form->getName());
			return false;
		}
		
		// Import children of XML element
		if ($form->count() > 0) {
			// Get parent_id
			$db = JFactory::getDbo();
			$query = $db->getQuery(true)
				->select("a.id")
				->from("#__jdeveloper_forms AS a")
				->order("a.id DESC LIMIT 1");
			
			$parent_id = $db->setQuery($query)->loadResult();
			
			foreach ($form->children() as $child) {
				$this->importFromXML($child, $parent_id, $relation, $name, $config, $incr + 1);
			}			
		}
		
		return true;
	}
		
	/**
	 * @see JModelAdmin::save()
	 */
	public function save($data)
	{
		$table = $this->getTable();
		$table->reset();
		$input = JFactory::getApplication()->input;
		$pk = (!empty($data['id'])) ? $data['id'] : (int) $this->getState($this->getName() . '.id', '0');
		$isNew = true;

		// Load the row if saving an existing category.
		if ($pk > 0)
		{
			$table->load($pk);
			$isNew = false;
		}

		// Set the new parent id if parent id not matched OR while New/Save as Copy .
		if ($table->parent_id != $data['parent_id'] || $data['id'] == 0)
		{
			$table->setLocation($data['parent_id'], 'last-child');
		}

		// Alter the name for save as copy
		if ($input->get('task') == 'save2copy')
		{
			list($name, $alias) = $this->generateNewTitle($data['parent_id'], $data['alias'], $data['name']);
			$data['name'] = $name;
			$data['alias'] = $alias;
		}

		// Bind the data.
		if (!$table->bind($data))
		{
			$this->setError($table->getError());
			return false;
		}

		// Check the data.
		if (!$table->check())
		{
			$this->setError($table->getError());
			return false;
		}

		// Store the data.
		if (!$table->store())
		{
			$this->setError($table->getError());
			return false;
		}

		// Rebuild the path for the form:
		if (!$table->rebuildPath($table->id))
		{
			$this->setError($table->getError());
			return false;
		}

		// Rebuild the paths of the form's children:
		if (!$table->rebuild($table->id, $table->lft, $table->level, $table->path))
		{
			$this->setError($table->getError());
			return false;
		}

		$this->setState($this->getName() . '.id', $table->id);

		// Clear the cache
		$this->cleanCache();

		return true;
	}

	/**
	 * @see JModelAdmin::delete()
	 */
	public function delete(&$pks)
	{
		// Look for corresponding table column and delete it
		foreach ($pks as $pk) {
			$model = JModelLegacy::getInstance("Field", "JDeveloperModel");
			$table = $model->getTable();
			$item = $this->getItem($pk);
			
			if ($item->tag == "field" && $item->level == "3") {
				$table_id = explode(".", $item->relation)[1];
				$table->load(array("table" => $table_id, "name" => $item->name), true);
				
				if (!empty($table->id)) {
					$model->delete($table->id);
				}
			}
		}
		
		return parent::delete($pks);
	}
	
	/**
	 * @see JModelAdmin::generateNewTitle()
	 */
	protected function generateNewTitle($parent_id, $alias, $title)
	{
		// Alter the title & alias
		$table = $this->getTable();
		while ($table->load(array('alias' => $alias, 'parent_id' => $parent_id)))
		{
			$title = JString::increment($title);
			$alias = JString::increment($alias, 'dash');
		}

		return array($title, $alias);
	}

	/**
	 * Method rebuild the entire nested set tree.
	 *
	 * @return  boolean  False on failure or error, true otherwise.
	 *
	 * @since   1.6
	 */
	public function rebuild()
	{
		// Get an instance of the table object.
		$table = $this->getTable();

		if (!$table->rebuild())
		{
			$this->setError($table->getError());
			return false;
		}

		// Clear the cache
		$this->cleanCache();

		return true;
	}
}
?>