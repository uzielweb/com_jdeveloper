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
 * Form field for jdeveloper existing components.
 *
 * @package		JDeveloper
 * @subpackage	Fields
 */
class JFormFieldLiveComponent extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  11.1
	 */
	protected $type = 'livecomponent';

	/**
	 * Method to get the field options.
	 *
	 * @return  array  The field option objects.
	 *
	 * @since   11.1
	 */
	protected function getOptions()
	{
		jimport("joomla.filesystem.folder");
		
		$db = JFactory::getDbo();
		$options = array();
		$folders = JFolder::folders(JPATH_ADMINISTRATOR . "/components", "com_");
		
		foreach ($folders as $folder)
		{
			// Create a new option object based on the <option /> element.
			$tmp = JHtml::_('select.option', $folder, ucfirst(str_replace("com_", "", $folder)));

			// Add the option object to the result set.
			$options[] = $tmp;
		}

		// Merge any additional options in the XML definition.
		$options = array_merge(parent::getOptions(), $options);

		return $options;
	}
}
?>