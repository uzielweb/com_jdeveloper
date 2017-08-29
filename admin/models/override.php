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
 * JDeveloper Override Model
 *
 * @package     JDeveloper
 * @subpackage  Models
 */
class JDeveloperModelOverride extends JDeveloperModelAdmin
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
	public function getTable($type = 'Override', $prefix = 'JDeveloperTable', $config = array())
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
		$input = JFactory::getApplication()->input;

		$type = $input->get("type", "");
		$name = $input->get("name", "");
		$item_id = $input->get("item_id", 0);

		if (!empty($type) && !empty($name) && !empty($item_id))
		{
			$override = $this->getOverride($type, $item_id, $name);
			
			// Template override already exists
			if (is_object($override))
			{
				return $override;
			}
			else
			{
				$item = new JObject();
				$item->id = 0;
				$item->type = $type;
				$item->item_id = $item_id;
				$item->name = $name;
				$item->source = $this->_getSource($type, $name, $item_id);
				
				return $item;
			}
		}
		
		return parent::getItem($pk);
	}
	
	/**
	 * Method to get the override of an extension template.
	 *
	 * @param	string	$type		The extension type (component, module, plugin, template)
	 * @param	int		$item_id	The extension id (primary key of table shere item is stored)
	 * @param	string	$name		The template name
	 *
	 * @return  object  The override
	 */
	public function getOverride($type, $item_id, $name)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		
		$query->select("a.*")->from("#__jdeveloper_overrides AS a")
			->where("a.item_id = " . $db->quote($item_id))
			->where("a.type = " . $db->quote($type))
			->where("a.name = " . $db->quote($name));
		
		$result = $db->setQuery($query)->loadObject();
		
		return $result;
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
		JDeveloperLoader::import("create");
		
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