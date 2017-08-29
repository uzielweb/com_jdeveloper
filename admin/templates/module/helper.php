<?php##{start_header}##
/**
 * @package     JDeveloper
 * @subpackage  Templates.Module
 *
 * @copyright  	Copyright (C) 2014, Tilo-Lars Flasche. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;##{end_header}##
##Header##

class Mod##Module##Helper
{
	/**
	 * Get the item
	 *
	 * @return  object	The item.
	 */
	public static function getItem()
	{
		$input = JFactory::getApplication()->input;
		
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);##{start_table}##
		$query->select('a.*')
			  ->from('#__##table_db## AS a')
			  ->where('##pk## = ' . $db->quote($input->get('##pk##', 1, 'int')));
		$db->setQuery($query);	
		return $db->loadObject();
	}

	/**
	 * Get the items
	 *
	 * @return  array<object>	The items.
	 */
	public static function getItems()
	{		
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('a.*')->from('#__##table_db## AS a');
		$db->setQuery($query);	
		return $db->loadObjectList();##{end_table}##
	}
}
