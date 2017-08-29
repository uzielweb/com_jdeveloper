<?php##{start_header}##
/**
 * @package     JDeveloper
 * @subpackage  Fields
 *
 * @copyright  	Copyright (C) 2014, Tilo-Lars Flasche. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */
##{end_header}##

defined('JPATH_BASE') or die();
JFormHelper::loadFieldClass('list');

/**
 * Describe this field
 */
class JFormField##Name## extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  11.1
	 */
	protected $type = '##name##';

	/**
	 * Method to get the field options.
	 *
	 * @return  array  The field option objects.
	 *
	 * @since   11.1
	 */
	protected function getOptions()
	{
		// The HTML option elements - do not edit this one
		$options = array();

		// The list items - enter your list items here (like "key_1" => "val_1", "key_2" => "val_2", ...)
		$data = array();
		
		foreach ($data as $key => $value)
		{
			// Create a new option object based on the <option /> element.
			$tmp = JHtml::_('select.option', $key, $value);

			// Add the option object to the result set.
			$options[$key] = $tmp;
		}

		// Merge any additional options in the XML definition.
		$options = array_merge(parent::getOptions(), $options);

		return $options;
	}
}
?>