<?php
/**
 * JDeveloper update script
 *
 * @package     JDeveloper
 * @subpackage  JDeveloper
 *
 * @copyright  	Copyright (C) 2014, Tilo-Lars Flasche. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die('Restricted access');

class com_jdeveloper_update_1_8_0
{
	/**
	 * Custom update method for JDeveloper
	 *
	 * @param	object	$parent		The installer adapter
	 *
	 * @return  void
	 */
	public function update($config = array())
	{
		$db = JFactory::getDbo();
		$model_form = JModelLegacy::getInstance("Form", "JDeveloperModel");
		
		// Get components
		$components = $db->setQuery(
				$db->getQuery(true)->select("*")->from("#__jdeveloper_components AS a"))
			->loadObjectList();
		
		// Create component forms
		$model_component = JModelLegacy::getInstance("Component", "JDeveloperModel");
		
		foreach ($components as $component) {
			if (!$model_form->getTable()->load(array("relation" => "component." . $component->id . ".config"), true)) {
				$model_component->createForms($component->id);
			}
		}
			
		// Get tables
		$tables = $db->setQuery(
				$db->getQuery(true)->select("*")->from("#__jdeveloper_tables AS a"))
			->loadObjectList();		

		// Create table forms
		$model_table = JModelLegacy::getInstance("Table", "JDeveloperModel");
				
		foreach ($tables as $table) {
			if (!$model_form->getTable()->load(array("relation" => "table." . $table->id . ".form"), true)) {
				$model_table->createForms($table->id);
			}
		}
			
		// Get modules
		$modules = $db->setQuery(
				$db->getQuery(true)->select("*")->from("#__jdeveloper_modules AS a"))
			->loadObjectList();		
		
		
		// Create module forms
		$model_module = JModelLegacy::getInstance("Module", "JDeveloperModel");
				
		foreach ($modules as $module) {
			if (!$model_form->getTable()->load(array("relation" => "module." . $module->id . ".config"), true)) {
				$model_module->createForms($module->id);
			}
		}
			
		// Get plugins
		$plugins = $db->setQuery(
				$db->getQuery(true)->select("*")->from("#__jdeveloper_plugins AS a"))
			->loadObjectList();		
		
		
		// Create plugin forms
		$model_plugin = JModelLegacy::getInstance("Plugin", "JDeveloperModel");
				
		foreach ($plugins as $plugin) {
			if (!$model_form->getTable()->load(array("relation" => "plugin." . $plugin->id . ".config"), true)) {
				$model_plugin->createForms($plugin->id);
			}
		}
			
		// Get templates
		$templates = $db->setQuery(
				$db->getQuery(true)->select("*")->from("#__jdeveloper_templates AS a"))
			->loadObjectList();		
		
		
		// Create template forms
		$model_template = JModelLegacy::getInstance("Template", "JDeveloperModel");
				
		foreach ($templates as $template) {
			if (!$model_form->getTable()->load(array("relation" => "template." . $template->id . ".config"), true)) {
				$model_template->createForms($template->id);
			}
		}
	}
}