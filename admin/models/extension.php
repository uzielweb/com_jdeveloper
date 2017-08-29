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
 * JDeveloper Extension Model
 *
 * @package     JDeveloper
 * @subpackage  Models
 */
class JDeveloperModelExtension extends JModelItem
{
	/**
	 * Method to get a single record.
	 *
	 * @param	integer	The id of the primary key.
	 *
	 * @return	mixed	Object on success, false on failure.
	 * @since	1.6
	 */
	public function getItem($pk = null)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		
		$query->select("a.extension_id, a.name, a.folder, a.type, a.element")
			  ->from("#__extensions AS a")
			  ->where("a.extension_id = " . $db->quote($db->escape($pk)));
		
		$db->setQuery($query);
		$item = $db->loadObject("JObject");
		
		switch ($item->type)
		{
			case "component":
				$element = "com_";
				break;
			case "module":
				$element = "mod_";
				break;
			case "template":
				$element = "tpl_";
				break;
			case "plugin":
				$element = "plg_";
				break;
		}
		
		$item->filename = (preg_match("/^" . $element . "/", $item->element)) ? $item->element : $element . $item->element;
		if ($item->type == "plugin") $item->filename = $item->name;

		return $item;
	}
}
?>