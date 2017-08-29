<?php
/**
 * @package     JDeveloper
 * @subpackage  Fields
 *
 * @copyright  	Copyright (C) 2014, Tilo-Lars Flasche. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;
JFormHelper::loadFieldClass('list');

/**
 * Form field for database tables.
 *
 * @package		JDeveloper
 * @subpackage	Fields
 */
class JFormFieldDbtables extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  11.1
	 */
	protected $type = 'dbtables';

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
		$tables = $db->getTableList();
		$options = array();
		
		foreach ($tables as $table)
		{
			// Create a new option object based on the <option /> element.
			$tmp = JHtml::_('select.option', str_replace($db->getPrefix(), '', $table), str_replace($db->getPrefix(), '', $table));

			// Add the option object to the result set.
			$options[] = $tmp;
		}

		// Merge any additional options in the XML definition.
		$options = array_merge(parent::getOptions(), $options);

		return $options;
	}
}
?>