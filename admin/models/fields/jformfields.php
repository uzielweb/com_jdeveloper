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
class JFormFieldJFormFields extends JFormFieldGroupedlist
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since	1.6
	 */
	protected $type = 'jformfields';
	
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
		jimport('joomla.filesystem.folder');
		
		$groups = array();
		$paths = array(
			"Joomla" => JPATH_ROOT . "/libraries/joomla/form/fields",
			"Legacy" => JPATH_ROOT . "/libraries/legacy/form/field",
			"Cms"	 => JPATH_ROOT . "/libraries/cms/form/field",
		);

		foreach ($paths as $groupname => $path)
		{
			$fields = JFolder::files($path, "\.php");
			
			foreach ($fields as $field)
			{
				$field = str_replace(".php", "", $field);
				$groups[$groupname][] = JHtml::_('select.option', $field, ucfirst($field));
			}
		}
		
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select("a.id, a.name")->from("#__jdeveloper_formfields as a");
		$results = $db->setQuery($query)->loadObjectList();
		
		foreach ($results as $result) $groups["JDeveloper"][] = JHtml::_('select.option', $result->name, ucfirst($result->name));
		
		return array_merge(parent::getGroups(), $groups);
	}
}