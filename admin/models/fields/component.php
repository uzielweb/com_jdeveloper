<?php
/**
 * @package     JDeveloper
 * @subpackage  Fields
 *
 * @copyright  	Copyright (C) 2014, Tilo-Lars Flasche. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('JPATH_BASE') or die();
JFormHelper::loadFieldClass('list');

/**
 * Form field for jdeveloper components.
 *
 * @package		JDeveloper
 * @subpackage	Fields
 */
class JFormFieldComponent extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  11.1
	 */
	protected $type = 'component';

	/**
	 * Method to get the field options.
	 *
	 * @return  array  The field option objects.
	 *
	 * @since   11.1
	 */
	protected function getOptions()
	{
		$db = JFactory::getDbo();
		$user = JFactory::getUser();
		$options = array();
		
		$query = $db->getQuery(true);
		$query->select("a.id, a.created_by, a.display_name")->from("#__jdeveloper_components as a");

		if (!$user->authorise('core.admin', 'com_jdeveloper'))
		{
			$query->where('a.created_by = ' . $user->get('id'));
		}

		$db->setQuery($query);
		
		foreach ($db->loadObjectList() as $component)
		{
			// Create a new option object based on the <option /> element.
			$tmp = JHtml::_('select.option', $component->id, $component->display_name);

			// Add the option object to the result set.
			$options[] = $tmp;
		}

		// Merge any additional options in the XML definition.
		$options = array_merge(parent::getOptions(), $options);

		return $options;
	}
}
?>