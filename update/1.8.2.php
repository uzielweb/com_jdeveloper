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

class com_jdeveloper_update_1_8_2
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
		$model_form = JModelLegacy::getInstance("Form", "JDeveloperModel");
		
		// Create module forms
		$model_modules = JModelLegacy::getInstance("Modules", "JDeveloperModel");
		$model_module = JModelLegacy::getInstance("Module", "JDeveloperModel");
		
		$modules = $model_modules->getItems();
		
		foreach ($modules as $module) {
			if (!$model_form->getTable()->load(array("relation" => "module." . $module->id . ".config"), true)) {
				$model_module->createForms($module->id);
			}
		}
		
		// Create plugin forms
		$model_plugins = JModelLegacy::getInstance("Plugins", "JDeveloperModel");
		$model_plugin = JModelLegacy::getInstance("Plugin", "JDeveloperModel");
		
		$plugins = $model_plugins->getItems();
		
		foreach ($plugins as $plugin) {
			if (!$model_form->getTable()->load(array("relation" => "plugin." . $plugin->id . ".config"), true)) {
				$model_plugin->createForms($plugin->id);
			}
		}
		
		// Create template forms
		$model_templates = JModelLegacy::getInstance("Templates", "JDeveloperModel");
		$model_template = JModelLegacy::getInstance("Template", "JDeveloperModel");
		
		$templates = $model_templates->getItems();
		
		foreach ($templates as $template) {
			if (!$model_form->getTable()->load(array("relation" => "template." . $template->id . ".config"), true)) {
				$model_template->createForms($template->id);
			}
		}
	}
}