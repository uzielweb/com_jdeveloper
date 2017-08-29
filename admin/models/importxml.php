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
 * JDeveloper ImportXml Model
 *
 * @package     JDeveloper
 * @subpackage  Models
 */
class JDeveloperModelImportXml extends JModelLegacy
{
	/**
	 * Get component from manifest file
	 *
	 * @param	SimpleXMLElement	$manifest	Manifest file
	 *
	 * @return	mixed	data array on success, false otherwise
	 */
	public function getComponent(SimpleXMLElement $manifest)
	{
		// Check if xml file contains component information
		if ($manifest->getName() != "extension") return false;
		
		// Set component properties
		$component = array();
		$component["id"] = 0;
		$component["name"] = str_replace("com_", "", $manifest->name);
		$component["display_name"] = ucfirst(str_replace("com_", "", $manifest->name));
		$component["site"] = isset($manifest->files);
		$component["version"] = (string) $manifest->version;
		$component["description"] = (string) $manifest->description;
		$component["params"] = array();
		$component["params"]["author"] = (string) $manifest->author;
		$component["params"]["author_email"] = (string) $manifest->authorEmail;
		$component["params"]["author_url"] = (string) $manifest->authorUrl;
		$component["params"]["copyright"] = (string) $manifest->copyright;
		$component["params"]["license"] = (string) $manifest->license;
		$component["params"]["languages"] = array();
		
		// Get component languages
		foreach ($manifest->administration->languages->children() as $lang)
		{
			$tag = (string) $lang->attributes()["tag"];
			
			if (!in_array($tag, $component["params"]["languages"]))
			{
				$component["params"]["languages"][] = $tag;
			}
		}
		
		return $component;
	}

	/**
	 * Get fields from form file
	 *
	 * @param	SimpleXMLElement	$form	Form file
	 *
	 * @return	mixed	Fields array on success, false otherwise
	 */
	public function getFields(SimpleXMLElement $form, $table = 0)
	{
		JDeveloperLoader::importHelper("field");
		
		// Check if xml file contains form information
		if ($form->getName() != "form") return false;
		
		// Get fields
		$fields = array();
		
		foreach ($form->fieldset->children() as $_field)
		{
			$field = array();
			$field["id"] = 0;
			$field["name"] = (string) $_field["name"];
			$field["type"] = (string) $_field["type"];
			$field["table"] = $table;
			$field["label"] = (string) ucfirst($_field["name"]);
			$field["dbtype"] = JDeveloperHelperField::getDbType((string) $_field["type"]);
			$field["rule"] = isset($_field["validate"]) ? (string) $_field["validate"] : "";
			$field["maxlength"] = isset($_field["size"]) ? (string) $_field["size"] : "10";
			$field["params"] = array();
			$field["params"]["class"] = isset($_field["class"]) ? (string) $_field["class"] : "inputbox";
			$field["params"]["default"] = isset($_field["default"]) ? (string) $_field["default"] : "";
			$field["params"]["filter"] = isset($_field["filter"]) ? (string) $_field["filter"] : "";
			$field["params"]["readonly"] = isset($_field["readonly"]) ? "1" : "0";
			$field["params"]["frontend_list"] = "1";
			$field["params"]["frontend_item"] = "1";
			$field["params"]["listfilter"] = "0";
			$field["params"]["searchable"] = "0";
			$field["params"]["sortable"] = "0";
			
			$fields[] = $field;
		}
		
		return $fields;
	}
}