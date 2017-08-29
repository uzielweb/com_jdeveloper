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
 * JDeveloper Override Model
 *
 * @package     JDeveloper
 * @subpackage  Models
 */
class JDeveloperModelFileeditor extends JModelForm
{
	/**
	 * Method for getting the form from the model.
	 *
	 * @param   array    $data      Data for the form.
	 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
	 *
	 * @return  mixed  A JForm object on success, false on failure
	 *
	 * @since   12.2
	 */
	public function getForm($data = array(), $loadData = true)
	{
		$options = array('control' => 'jform', 'load_data' => $loadData);
		$form = $this->loadForm('fileeditor', 'fileeditor', $options);
		$input = JFactory::getApplication()->input;
		
		if (empty($form)) {
			return false;
		}

		$form->setFieldAttribute('source', 'syntax', JFile::getExt($input->get("path")));

		return $form;
	}
	
	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return  array    The default data is an empty array.
	 *
	 * @since   12.2
	 */
	protected function loadFormData()
	{
		$app = JFactory::getApplication();
		$data = $app->getUserState('com_jdeveloper.edit.fileeditor.data', array());
		
		if (empty($data)) {
			$data = $this->getItem();
		}

		return $data;
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
		$input = JFactory::getApplication()->input;

		$type = $input->get("type", "");
		$client = $input->get("client", "");
		$extName = $input->get("name", "");
		$path = $input->get("path", "");
		
		$item = new JObject();
		$item->client = $client;
		$item->type = $type;
		$item->name = $extName;
		$item->path = $path;

		switch ($type) {
			case "component" :
				if ($client == "admin")
					$filepath = JDeveloperPath::dots2ds(JPATH_ADMINISTRATOR . "/components/com_" . $extName . "/" . $path);
				else
					$filepath = JDeveloperPath::dots2ds(JPATH_SITE . "/components/com_" . $extName . "/" . $path);

				if ($filepath === false)
					throw new Exception(JText::_("COM_JDEVELOPER_FILEMANAGER_FILE_NOT_FOUND"));
				
				$item->source = JFile::read($filepath);
				$item->filepath = $filepath;
				break;
		}
		
		return $item;
	}

	/**
	 * Get the template
	 *
	 * @param	string	$type		The extension type (component, module, plugin, template)
	 * @param	string	$name		The template name
	 * @param	int		$item_id	The extension id (primary key of table shere item is stored)
	 *
	 * @return string	The template
	 */
	private function _getSource($type, $name, $item_id)
	{		
		$input = JFactory::getApplication()->input;
		$dir = JDeveloperCREATE . "/" . implode("/", explode(".", $type));

		foreach (JFolder::files($dir, "php$") as $file)
		{
			$create = JDeveloperCreate::getInstance($type . "." . JFile::stripExt($file), array("item_id" => $item_id));
			if ($create->templateFile == $name)
			{
				return $create->getBuffer();
			}
		}
		
		return "";
	}
	
	/**
	 * Method to save the form data.
	 *
	 * @param   array  $data  The form data.
	 *
	 * @return  boolean  True on success, False on error.
	 *
	 * @since   12.2
	 */
	public function save($data)
	{
		if (empty($data['source']))
		{
			return false;
		}
		
		return parent::save($data);
	}
}