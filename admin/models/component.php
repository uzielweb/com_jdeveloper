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
JDeveloperLoader::import("install");

/**
 * JDeveloper Component Model
 *
 * @package     JDeveloper
 * @subpackage  Models
 */
class JDeveloperModelComponent extends JDeveloperModelAdmin
{
	/**
	 * Method to delete one or more records.
	 *
	 * @param   array  &$pks  An array of record primary keys.
	 *
	 * @return  boolean  True if successful, false if an error occurs.
	 */
	public function delete(&$pks)
	{
		// Delete tables which belong to the component
		if ( (int) JComponentHelper::getParams('com_jdeveloper')->get('delete_tables') );
		{
			$model = JModelLegacy::getInstance('Table', 'JDeveloperModel');
			
			foreach ($pks as $pk)
			{
				$db = JFactory::getDbo();
				$query = $db->getQuery(true)
					->select('id')
					->from('#__jdeveloper_tables')
					->where('`component` = ' . $db->quote((int) $pk));
				$db->setQuery($query);
				
				$ids = $db->loadRowList();
				$keys = array();
				
				foreach ($ids as $id) $keys[] = $id[0];
					$model->delete($keys);
			}
		}
		
		// Delete forms which belong to the component
		foreach ($pks as $pk)
		{
			$model = JModelLegacy::getInstance('Form', 'JDeveloperModel');
			$ids = $this->getFormRootIds($pk);
			$keys = array();
			
			foreach ($ids as $id)
				$keys[] = $id[0];
		
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
	public function getTable($type = 'Component', $prefix = 'JDeveloperTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}
	
	/**
	 * Get root ids of forms which belong to a component
	 * 
	 * @param int	$id		The component id
	 * 
	 * @return array	The root ids
	 */
	public function getFormRootIds($id) {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select("a.id")
			->from("#__jdeveloper_forms AS a")
			->where("a.relation LIKE 'component." . $id . "%'")
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
		$app = JFactory::getApplication();
		$itemId = $this->getState("data.id", 0);
		$layout = $app->input->get("layout", "default", "string");

		if (empty($pk) && $layout == "default" && !empty($itemId))
		{
			$pk = $itemId;
		}

		if (false === $item = parent::getItem($pk))
		{
			return false;
		}
		
		// Is Component already installed?
		$item->installed = JDeveloperInstall::isInstalled("component", "com_" . $item->name);
		$item->createDir = JDeveloperArchive::getArchiveDir() . "/" . JDeveloperArchive::getArchiveName("com_", $item->name, $item->version);
		
		$params = JComponentHelper::getParams("com_jdeveloper");
			
		if (empty($item->params['author']))			$item->params['author'] = $params->get("author", "");
		if (empty($item->params['author_email']))	$item->params['author_email'] = $params->get("author_email", "");
		if (empty($item->params['author_url']))		$item->params['author_url'] = $params->get("author_url", "");
		if (empty($item->params['copyright']))		$item->params['copyright'] = $params->get("copyright", "");
		if (empty($item->params['license']))		$item->params['license'] = $params->get("license", "");

		// Get related config form id
		$table = JTable::getInstance("Form", "JDeveloperTable");
		
		if ($table->load(array("tag" => "config", "relation" => "component." . $item->id . ".config"), true)) {
			$item->form_id = $table->id;
		}
		else {
			$item->form_id = 0;
		}
		
		// Get related access form id		
		if ($table->load(array("tag" => "access", "relation" => "component." . $item->id . ".access"), true)) {
			$item->form_id_access = $table->id;
		}
		else {
			$item->form_id_access = 0;
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
	 * Create component forms
	 * 
	 * @param int	$id	The table id
	 * 
	 * @return boolean	true on success, false on error
	 */
	public function createForms($id) {
		// Create forms
		$model_form = JModelLegacy::getInstance("Form", "JDeveloperModel");
		
		$form_access = array(
				"id" => 0,
				"parent_id" => "1",
				"tag" => "access",
				"relation" => "component." . $id . ".access",
				"name" => "component." . $id . ".access",
				"alias" => "component." . $id . ".access"
		);
			
		$form_config = array(
				"id" => 0,
				"parent_id" => "1",
				"tag" => "config",
				"relation" => "component." . $id . ".config",
				"name" => "component." . $id . ".config",
				"alias" => "component." . $id . ".config"
		);
			
		if (!$model_form->save($form_access)) {
			$this->setError("Couldn't create access form of component");
			return false;
		}
		
		if (!$model_form->save($form_config)) {
			$this->setError("Couldn't create config form of component");
			return false;
		}
		
		return true;
	}
	
	/**
	 * Stock method to auto-populate the model state.
	 *
	 * @return  void
	 *
	 * @since   12.2
	 */
	protected function populateState()
	{
		$app = JFactory::getApplication();
		$session = JFactory::getSession();
		
		$inputId = $app->input->get("id", 0, "int");
		$sessionId = $session->get($this->getName() . ".id", 0, $this->getName() . ".data");
		$layout = $app->input->get("layout", "default", "string");

		if ($layout == "default" && !empty($inputId))
		{
			$session->set($this->getName() . '.id', $inputId, $this->getName() . ".data");
			$this->setState("data.id", $inputId);
		}
		elseif ($layout == "default" && !empty($sessionId))
		{
			$this->setState("data.id", $sessionId);			
		}
		
		parent::populateState();
	}
	
	/**
	 * @see JModelAdmin::save()
	 */
	public function save($data)
	{
		if (!parent::save($data)) {
			return false;
		}
		
		// New item
		if (!isset($data["id"]) || empty($data["id"])) {
			// Get id of last item
			$db = JFactory::getDbo();
			$query = $db->getQuery(true)
				->select("a.id")
				->from("#__jdeveloper_components AS a")
				->order("a.id DESC LIMIT 1");
			$db->setQuery($query);
			$id = $db->loadResult();
			
			if (!$this->createForms($id)) {
				return false;
			}			
		}
		
		return true;
	}
}