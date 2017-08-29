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
class JFormFieldFormFieldOptions extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  11.1
	 */
	protected $type = 'formfieldoptions';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   11.1
	 */
	protected function getInput()
	{
		$input = JFactory::getApplication()->input;
		$model = JModelLegacy::getInstance($this->element["model"], "JDeveloperModel");
		$id = $input->get("id", 0, "int");
		$field = $model->getItem($id);
		
		$input = "<table class=\"field-options\">";
		$input .= "<tr><th>" . JText::_("COM_JDEVELOPER_FORM_OPTION_KEY") . "</th>";
		$input .= "<th>" . JText::_("COM_JDEVELOPER_FORM_OPTION_VALUE") . "</th></tr>";
		
		if (isset($field->options["keys"]))
		{
			for ($i = 0; $i < count($field->options["keys"]); $i++)
			{
				$input .= '<tr><td><input name="jform[options][keys][]" value="' . $field->options["keys"][$i] . '"/></td>';
				$input .= '<td><input name="jform[options][values][]" value="' . $field->options["values"][$i] . '"/></td></tr>';
			}
		}
		
		$input .= '<tr><td><input name="jform[options][keys][]" value=""/></td>';
		$input .= '<td><input name="jform[options][values][]" value=""/></td></tr>';
		$input .= "</table>";
		
		$input .= "<a onclick=\"
		jQuery(document).ready(function(){
			jQuery('table.field-options').html(
				jQuery('table.field-options').html()
				+ '<tr><td><input name=\'jform[options][keys][]\' value=\'\'></td>'
				+ '<td><input name=\'jform[options][values][]\' value=\'\'></td></tr>'
			);
		}
		);\"
		class=\"btn btn-success\">" . JText::_("COM_JDEVELOPER_FORM_ADD_OPTION") . "</a>";
		
		return $input;
	}

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
		$model = JModelLegacy::getInstance("Form", "JDeveloperModel");
		$id = $input->get("id", 0, "int");
		$field = $model->getItem($id);
		
		if (!isset($field->options) || !is_array($field->options))
		{
			return array();
		}
		
		$options = array();
		foreach ($field->options as $key => $value)
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