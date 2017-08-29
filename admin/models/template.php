<?php
/**
 * @package     JDeveloper
 * @subpackage  Models
 *
 * @copyright  	Copyright (C) 2014, Tilo-Lars Flasche. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;
JDeveloperLoader::import("models.extension");

/**
 * JDeveloper Template Model
 *
 * @package     JDeveloper
 * @subpackage  Models
 */
class JDeveloperModelTemplate extends JDeveloperModelExtension
{
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
	public function getTable($type = 'Template', $prefix = 'JDeveloperTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
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

		// Get related config form id
		$table = JTable::getInstance("Form", "JDeveloperTable");
		
		if ($table->load(array("tag" => "config", "relation" => "template." . $item->id . ".config"), true)) {
			$item->form_id = $table->id;
		}
		else {
			$item->form_id = 0;
		}
		
		$item->installed = JDeveloperInstall::isInstalled("template", "tpl_" . $item->name);
		$item->createDir = JDeveloperArchive::getArchiveDir() . "/" . JDeveloperArchive::getArchiveName("tpl_", $item->name, $item->version);
		
		$params = JComponentHelper::getParams("com_jdeveloper");
			
		if (empty($item->params['author']))			$item->params['author'] = $params->get("author", "");
		if (empty($item->params['author_email']))	$item->params['author_email'] = $params->get("author_email", "");
		if (empty($item->params['author_url']))		$item->params['author_url'] = $params->get("author_url", "");
		if (empty($item->params['copyright']))		$item->params['copyright'] = $params->get("copyright", "");
		if (empty($item->params['license']))		$item->params['license'] = $params->get("license", "");

		return $item;
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
}