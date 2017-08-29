<?php
/**
 * @package     JDeveloper
 * @subpackage  Fields
 *
 * @copyright  	Copyright (C) 2014, Tilo-Lars Flasche. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('JPATH_BASE') or die();
JFormHelper::loadFieldClass('groupedlist');

/**
 * Form field for joomla field types.
 *
 * @package		JDeveloper
 * @subpackage	Fields
 */
class JFormFieldJDeveloperTables extends JFormFieldGroupedlist
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since	1.6
	 */
	protected $type = 'jdevelopertables';
	
	/**
	 * Method to get the field option groups.
	 *
	 * @return  array  The field option objects as a nested array in groups.
	 *
	 * @since   11.1
	 * @throws  UnexpectedValueException
	 */
	protected function getGroups()
	{		
		$db = JFactory::getDbo();
		$user = JFactory::getUser();
		$query = $db->getQuery(true);	
		
		$query->select("a.id, a.name")->from("#__jdeveloper_tables as a");
		$query->select('c.name AS component, c.display_name AS component_name')->join('LEFT', '#__jdeveloper_components AS c ON c.id = a.component');

		if (!empty($this->component))
		{
			$query->where('a.component = ' . $db->quote($this->component));
		}

		if (!empty($this->current_table))
		{
			$query->where('a.id != ' . $db->quote($this->current_table));
		}

		if (!$user->authorise('core.admin', 'com_jdeveloper'))
		{
			$query->where('a.created_by = ' . $user->get('id'));
		}

		$results = $db->setQuery($query)->loadObjectList();

		$groups = array();
		
		foreach ($results as $result)
		{
			$groups[$result->component_name][] = JHtml::_('select.option', $result->id, $result->name);
		}
		
		return array_merge(parent::getGroups(), $groups);
	}
}