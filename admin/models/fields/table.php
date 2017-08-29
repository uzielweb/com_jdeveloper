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
 * Form field for jdeveloper tables
 *
 * @package		JDeveloper
 * @subpackage	Fields
 */
class JFormFieldTable extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  11.1
	 */
	protected $type = 'table';

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
		$query->select("a.id, a.name")->from("#__jdeveloper_tables as a");
		(!$user->authorise("core.admin", "com_jdeveloper")) ? $query->where("a.created_by = " . $user->id) : null;
		$db->setQuery($query);
		
		foreach ($db->loadObjectList() as $table)
		{
			// Create a new option object based on the <option /> element.
			$tmp = JHtml::_('select.option', $table->id, $table->name);

			// Add the option object to the result set.
			$options[] = $tmp;
		}

		// Merge any additional options in the XML definition.
		$options = array_merge(parent::getOptions(), $options);

		return $options;
	}
}
?>