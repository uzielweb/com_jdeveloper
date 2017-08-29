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
class JFormFieldFieldoptions extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  11.1
	 */
	protected $type = 'fieldoptions';

	/**
	 * Method to get the field options.
	 *
	 * @return  array  The field option objects.
	 *
	 * @since   11.1
	 */
	protected function getOptions()
	{
		$input = JFactory::getApplication()->input;
		$model = JModelLegacy::getInstance("Field", "JDeveloperModel");
		$id = $input->get("id", 0, "int");
		$field = $model->getItem($id);
		
		if (!isset($field->params['options']) || !is_array($field->params['options']))
		{
			return array();
		}
		
		$options = array();
		foreach ($field->params['options'] as $key => $value)
		{
			// Create a new option object based on the <option /> element.
			$tmp = JHtml::_('select.option', $value, $value);

			// Add the option object to the result set.
			$options[] = $tmp;
		}

		// Merge any additional options in the XML definition.
		$options = array_merge(parent::getOptions(), $options);

		return $options;
	}
}
?>