<?php
/**
 * @package     JDeveloper
 * @subpackage  Controllers
 *
 * @copyright  	Copyright (C) 2014, Tilo-Lars Flasche. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

/**
 * JDeveloper Import Controller
 *
 * @package     JDeveloper
 * @subpackage  Controllers
 */
class JDeveloperControllerImport extends JControllerAdmin
{
	/**
	 * Method to get a model object, loading it if required.
	 *
	 * @param   string  $name    The model name. Optional.
	 * @param   string  $prefix  The class prefix. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return  object  The model.
	 *
	 * @since   12.2
	 */
	public function getModel($name = '', $prefix='JDeveloperModel', $config = array())
	{
		$config['ignore_request'] = true;
		$model = parent::getModel($name, $prefix, $config);
		return $model;
	}

	/**
	 * Import component from xml file
	 */
	public function componentFromManifest()
	{		
		JDeveloperLoader::import("install");
		$this->setRedirect(JRoute::_("index.php?option=com_jdeveloper&view=import&active=component", false));
		
		$files = $this->input->files->get("jform", array(), "files");
		$manifest = JFile::upload($files["manifest"]["tmp_name"], JDeveloperINSTALL . "/import_component.xml", false);
		$xml = new SimpleXMLElement(JDeveloperINSTALL . "/import_component.xml", null, true);
		
		$model = $this->getModel("ImportXml");
		if (false === $component = $model->getComponent($xml))
		{
			$this->setMessage(JText::_("COM_JDEVELOPER_IMPORT_MESSAGE_COMPONENT_ERROR") . ": " . JText::_("COM_JDEVELOPER_IMPORT_ERROR_NO_MANIFEST_FILE"), "error");
			JDeveloperInstall::cleanInstallDir();
			return;
		}
		
		$model = $this->getModel("Component");
		$model->save($component);
		
		JDeveloperInstall::cleanInstallDir();
		$this->setMessage(JText::_("COM_JDEVELOPER_IMPORT_MESSAGE_COMPONENT_SUCCESS"));
	}
	
	/**
	 * Import component from installation
	 */
	public function componentFromInstallation() {
		$this->setRedirect(JRoute::_("index.php?option=com_jdeveloper&view=import&active=component", false));

		// get data
		$data = JFactory::getApplication()->input->get("jform", array(), "array");
		$client = explode(".", $data["extension"])[0];
		$name = explode(".", $data["extension"])[1];
		$path = $client == "admin" ? JPATH_ADMINISTRATOR . "/components/" . $name : JPATH_SITE . "/components/" . $name;

		// get models
		$model_component	= $this->getModel("Component");
		$model_field		= $this->getModel("Field");
		$model_db			= $this->getModel("ImportDb");
		$model_xml			= $this->getModel("ImportXml");
		$model_table		= $this->getModel("Table");
		
		// get component manifest file and create database item for component
		if (JFolder::exists($path)) {
			// find manifest file
			$success = false;
			
			foreach (JFolder::files($path, "\.xml$", false, true) as $file) {
				try {
					$data_component = $model_xml->getComponent(new SimpleXMLElement($file, null, true));
				} catch (Exception $e) {
					continue;
				}
				
				// save the component
				if (is_array($data_component)) {
					if (!$model_component->save($data_component)) {
						$this->setMessage(JText::_("COM_JDEVELOPER_IMPORT_MESSAGE_SAVE_COMPONENT_ERROR"), "error");
						return;
					}
					
					$success = true;
				}
			} 
			
			if (!$success) {
				// manifest file not found
				$this->setMessage(JText::_("COM_JDEVELOPER_IMPORT_MESSAGE_COMPONENT_ERROR") . ": " . JText::_("COM_JDEVELOPER_IMPORT_ERROR_NO_MANIFEST_FILE"), "error");
				return;
			}
		}
		else {
			// path not found
			$this->setMessage(JText::sprintf("COM_JDEVELOPER_IMPORT_MESSAGE_PATH_NOT_FOUND", $path), "error");
			return;
		}
		
		// get sql install script and create table items
		$tables = array();

		foreach (JFolder::files($path, "^install(\.mysql)?(\.utf8)?\.sql$", true, true) as $file) {
			$sql = JFile::read($file);
			
			preg_match_all("/#__[A-Za-z0-9_-]*/", $sql, $matches);
			
			foreach ($matches[0] as $dbtable) {
				// no double tables
				if (in_array($dbtable, $tables)) {
					continue;
				}
				
				$data_table = $model_db->getDbTable($dbtable, $model_component->getLastItemId());
				
				// save the table
				if (is_array($data_table)) {
					if (!$model_table->save($data_table)) {
						$this->setMessage(JText::sprintf("COM_JDEVELOPER_IMPORT_MESSAGE_SAVE_TABLE_ERROR", $data_table["name"]), "error");
						return;
					}
					
					$tables[] = $dbtable;
				}
				
				$data_fields = $model_db->getFields($dbtable, $model_table->getLastItemId());
				
				// save the table fields
				if (is_array($data_fields)) {
					foreach ($data_fields as $data_field) {
						if (!$model_field->isCoreField($field["name"])) {
							if (!$model_field->save($data_field)) {
								$this->setMessage(JText::sprintf("COM_JDEVELOPER_IMPORT_MESSAGE_SAVE_FIELD_ERROR", $data_field["name"], $data_table["name"]), "error");
								return;
							}
						}
					}
				}
			}
		}
		
		$this->setMessage(JText::sprintf("COM_JDEVELOPER_IMPORT_COMPONENT_FROM_INSTALLATION_SUCCESS", $data_component["name"]));
	}
	
	/**
	 * Import fields from xml file
	 */
	public function fieldsFromForm()
	{		
		JDeveloperLoader::import("install");
		$this->setRedirect(JRoute::_("index.php?option=com_jdeveloper&view=import&active=fields", false));
		$jform = $this->input->post->get("jform", array(), "array");

		if (empty($jform["table"]))
		{
			$this->setMessage(JText::_("COM_JDEVELOPER_IMPORT_ERROR_NO_TABLE_GIVEN"), "error");
			return;
		}

		// Get data
		$files = $this->input->files->get("jform", array(), "files");
		$manifest = JFile::upload($files["formfile"]["tmp_name"], JDeveloperINSTALL . "/import_fields.xml", false);
		$xml = new SimpleXMLElement(JDeveloperINSTALL . "/import_fields.xml", null, true);

		// Get fields from xml file
		$model = $this->getModel("ImportXml");
		if (false === $fields = $model->getFields($xml, $jform["table"]))
		{
			$this->setMessage(JText::_("COM_JDEVELOPER_IMPORT_MESSAGE_FIELDS_ERROR") . ": " . JText::_("COM_JDEVELOPER_IMPORT_ERROR_NO_FORM_FILE"), "error");
			JDeveloperInstall::cleanInstallDir();
			return;
		}

		// Save fields
		$model = $this->getModel("Field");
		foreach ($fields as $field)
		{
			if ($field["name"] == "id") continue;
			$model->save($field);
		}
		
		JDeveloperInstall::cleanInstallDir();
		$this->setMessage(JText::_("COM_JDEVELOPER_IMPORT_MESSAGE_FIELDS_SUCCESS"));
	}
	
	/**
	 * Import table from database
	 */
	public function tableFromDb()
	{
		$this->setRedirect(JRoute::_("index.php?option=com_jdeveloper&view=import&active=table", false));
		$jform = $this->input->post->get("jform", array(), "array");
		
		if (empty($jform["component"]) || empty($jform["dbtable"]))
		{
			$msg = array();
			(empty($jform["component"])) ? $msg[] = JText::_("COM_JDEVELOPER_IMPORT_ERROR_NO_COMPONENT_GIVEN") : null;
			(empty($jform["dbtable"])) ? $msg[] = JText::_("COM_JDEVELOPER_IMPORT_ERROR_NO_TABLE_GIVEN") : null;
			
			$this->setMessage(implode("<br>\n", $msg), "error");
			return;
		}

		// Get table from database
		$model = $this->getModel("ImportDb");
		if (false === $table = $model->getDbTable($jform["dbtable"], $jform["component"]))
		{
			$this->setMessage(JText::_("COM_JDEVELOPER_IMPORT_MESSAGE_TABLE_ERROR"), "error");
			return;
		}

		// Get fields from database
		if (false === $fields = $model->getFields($jform["dbtable"], $jform["component"]))
		{
			$this->setMessage(JText::_("COM_JDEVELOPER_IMPORT_MESSAGE_TABLE_ERROR"), "error");
			return;
		}
		(!empty($jform["table_plural"])) ? $table["plural"] = $jform["table_plural"] : null;
		(!empty($jform["table_singular"])) ? $table["singular"] = $jform["table_singular"] : null;

		// Save table
		$model = $this->getModel("Table");
		
		if (!$model->save($table))
		{
			$this->setMessage(implode("<br>", $model->getErrors()));
		}
		
		$_table = JTable::getInstance("Table", "JDeveloperTable");
		$_table->load(array("name" => $table["name"]));

		// Save fields
		$model = $this->getModel("Field");
		foreach ($fields as $field)
		{
			$field["table"] = $_table->id;

			if (!$model->save($field))
			{
				$this->setMessage(implode("<br>", $model->getErrors()));
			}
		}
		
		$this->setMessage(JText::_("COM_JDEVELOPER_IMPORT_MESSAGE_TABLE_SUCCESS"));
	}
}