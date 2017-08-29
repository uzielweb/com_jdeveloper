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
 * Form field for language tags
 *
 * @package		JDeveloper
 * @subpackage	fields
 */
class JFormFieldLangtags extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  11.1
	 */
	protected $type = 'langtags';

	/**
	 * Method to get the field options.
	 *
	 * @return  array  The field option objects.
	 *
	 * @since   11.1
	 */
	protected function getOptions()
	{
		$content = file_get_contents('http://update.joomla.org/language/');
		preg_match_all('/[a-z]{2,3}-[A-Z]{2,3}/', $content, $matches);

		$options = array();
		
		foreach ($matches[0] as $key => $value)
		{
			// Create a new option object based on the <option /> element.
			$tmp = JHtml::_('select.option', $value, $value);

			// Add the option object to the result set.
			$options[$value] = $tmp;
		}

		// Merge any additional options in the XML definition.
		$options = array_merge(parent::getOptions(), $options);

		return $options;
	}
}
?>