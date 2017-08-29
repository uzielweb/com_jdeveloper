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
JFormHelper::loadFieldClass('groupedlist');

/**
 * Describe this field
 */
class JFormField##Name## extends JFormFieldGroupedList
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since	1.6
	 */
	protected $type = '##name##';
	
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
		// Two dimension array (1st dimension is group, 2nd dimension is option)
		$groups = array();
		
		return array_merge(parent::getGroups(), $groups);
	}
}