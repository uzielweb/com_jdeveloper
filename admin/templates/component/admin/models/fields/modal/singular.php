<?php##{start_header}##
/**
 * @package     JDeveloper
 * @subpackage  Templates.Component
 *
 * @copyright  	Copyright (C) 2014, Tilo-Lars Flasche. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;##{end_header}##
##Header##

/**
 * Supports a modal ##singular## picker.
 *
 * @package     ##Component##
 * @subpackage  Modal fields
 */
class JFormFieldModal_##Singular## extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since   1.6
	 */
	protected $type = 'Modal_##Singular##';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string	The field input markup.
	 * @since   1.6
	 */
	protected function getInput()
	{
		$allowEdit		= ((string) $this->element['edit'] == 'true') ? true : false;
		$allowClear		= ((string) $this->element['clear'] != 'false') ? true : false;

		// Load language
		JFactory::getLanguage()->load('com_##component##', JPATH_ADMINISTRATOR);

		// Load the javascript
		JHtml::_('behavior.framework');
		JHtml::_('behavior.modal', 'a.modal');
		JHtml::_('bootstrap.tooltip');

		// Build the script.
		$script = array();

		// Select button script
		$script[] = '	function jSelect##Singular##_'.$this->##pk##.'(id, name, object) {';
		$script[] = '		document.id("'.$this->##pk##.'_id").value = id;';
		$script[] = '		document.id("'.$this->##pk##.'_name").value = name;';

		if ($allowEdit)
		{
			$script[] = '		jQuery("#'.$this->##pk##.'_edit").removeClass("hidden");';
		}

		if ($allowClear)
		{
			$script[] = '		jQuery("#'.$this->##pk##.'_clear").removeClass("hidden");';
		}

		$script[] = '		SqueezeBox.close();';
		$script[] = '	}';

		// Clear button script
		static $scriptClear;

		if ($allowClear && !$scriptClear)
		{
			$scriptClear = true;

			$script[] = '	function jClear##Singular##(id) {';
			$script[] = '		document.getElementById(id + "_id").value = "";';
			$script[] = '		document.getElementById(id + "_name").value = "'.htmlspecialchars(JText::_('COM_##COMPONENT##_SELECT_A_##SINGULAR##', true), ENT_COMPAT, 'UTF-8').'";';
			$script[] = '		jQuery("#"+id + "_clear").addClass("hidden");';
			$script[] = '		if (document.getElementById(id + "_edit")) {';
			$script[] = '			jQuery("#"+id + "_edit").addClass("hidden");';
			$script[] = '		}';
			$script[] = '		return false;';
			$script[] = '	}';
		}

		// Add the script to the document head.
		JFactory::getDocument()->addScriptDeclaration(implode("\n", $script));

		// Setup variables for display.
		$html	= array();
		$link	= 'index.php?option=com_##component##&amp;view=##plural##&amp;layout=modal&amp;tmpl=component&amp;function=jSelect##Singular##_'.$this->##pk##;

		if (isset($this->element['language']))
		{
			$link .= '&amp;forcedLanguage='.$this->element['language'];
		}

		// Get the title of the linked chart
		if ((int) $this->value > 0)
		{
			$db = JFactory::getDbo();
			$query = $db->getQuery(true)
				->select($db->quoteName('##mainfield##'))
				->from($db->quoteName('#__##table_db##'))
				->where($db->quoteName('##pk##') . ' = ' . (int) $this->value);
			$db->setQuery($query);

			try
			{
				$title = $db->loadResult();
			}
			catch (RuntimeException $e)
			{
				JError::raiseWarning(500, $e->getMessage);
			}
		}

		if (empty($title))
		{
			$title = JText::_('COM_##COMPONENT##_SELECT_A_##SINGULAR##');
		}
		$title = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');

		// The active ##singular## ##pk## field.
		if (0 == (int) $this->value)
		{
			$value = '';
		}
		else
		{
			$value = (int) $this->value;
		}

		// The current ##singular## display field.
		$html[] = '<span class="input-append">';
		$html[] = '<input type="text" class="input-medium" id="'.$this->##pk##.'_name" value="'.$title.'" disabled="disabled" size="35" />';
		$html[] = '<a class="modal btn hasTooltip" title="'.JHtml::tooltipText('COM_##COMPONENT##_CHANGE_##SINGULAR##_BUTTON').'"  href="'.$link.'&amp;'.JSession::getFormToken().'=1" rel="{handler: \'iframe\', size: {x: 800, y: 450}}"><i class="icon-file"></i> '.JText::_('JSELECT').'</a>';

		// Edit ##singular## button
		if ($allowEdit)
		{
			$html[] = '<a class="btn hasTooltip'.($value ? '' : ' hidden').'" href="index.php?option=com_##component##&layout=modal&tmpl=component&task=##singular##.edit&##pk##=' . $value. '" target="_blank" title="'.JHtml::tooltipText('COM_##COMPONENT##_EDIT_##SINGULAR##').'" ><span class="icon-edit"></span> ' . JText::_('JACTION_EDIT') . '</a>';
		}

		// Clear ##singular## button
		if ($allowClear)
		{
			$html[] = '<button id="'.$this->##pk##.'_clear" class="btn'.($value ? '' : ' hidden').'" onclick="return jClear##Singular##(\''.$this->##pk##.'\')"><span class="icon-remove"></span> ' . JText::_('JCLEAR') . '</button>';
		}

		$html[] = '</span>';

		// class='required' for client side validation
		$class = '';

		if ($this->required)
		{
			$class = ' class="required modal-value"';
		}

		$html[] = '<input type="hidden" id="'.$this->##pk##.'_id"'.$class.' name="'.$this->name.'" value="'.$value.'" />';

		return implode("\n", $html);
	}
}
