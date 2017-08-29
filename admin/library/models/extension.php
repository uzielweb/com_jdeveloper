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
class JDeveloperModelExtension extends JDeveloperModelAdmin
{	
	/**
	 * Create extension forms
	 * 
	 * @param int	$id	The extension id
	 * 
	 * @return boolean	true on success, false on error
	 */
	public function createForms($id) {
		$model_form = JModelLegacy::getInstance("Form", "JDeveloperModel");
			
		$form_config = array(
				"id" => 0,
				"parent_id" => "1",
				"tag" => "config",
				"relation" => $this->getName() . "." . $id . ".config",
				"name" => $this->getName() . "." . $id . ".config",
				"alias" => $this->getName() . "." . $id . ".config"
		);
			
		if (!$model_form->save($form_config)) {
			$this->setError("Couldn't create config form of " . $this->getName());
			return false;
		}
		
		return true;
	}
	
	/**
	 * Method to delete one or more records.
	 *
	 * @param   array  &$pks  An array of record primary keys.
	 *
	 * @return  boolean  True if successful, false if an error occurs.
	 */
	public function delete(&$pks)
	{
		// Delete overrides of extension
		$model_overrides = JModelLegacy::getInstance("Overrides", "JDeveloperModel");
		$model_override = JModelLegacy::getInstance("Override", "JDeveloperModel");

		foreach ($pks as $pk)
		{
			$overrides = $model_overrides->getOverrides($this->getName(), $pk);
			if (count($overrides))
			{
				foreach ($overrides as $override)
				{
					$model_override->delete($override->id);
				}
			}
		}
		
		// Delete forms which belong to the module
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
	 * Get root ids of forms which belong to an extension
	 *
	 * @param int	$id		The extension id
	 *
	 * @return array	The root ids
	 */
	public function getFormRootIds($id) {
		$db = JFactory::getDbo();
		
		$query = $db->getQuery(true)
		->select("a.id")
		->from("#__jdeveloper_forms AS a")
		->where("a.relation LIKE '" . $this->getName() . "." . $id . "%'")
		->where("a.level = 1");
		
		$db->setQuery($query);
			
		return $db->loadRowList();
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
	 * @see JModelAdmin::save()
	 */
	public function save($data)
	{
		if (!parent::save($data)) {
			return false;
		}
	
		// Create form for new item
		if (!isset($data["id"]) || empty($data["id"])) {
			$id = $this->getLastItemId();
				
			if (!$this->createForms($id)) {
				return false;
			}
		}
	
		return true;
	}
}