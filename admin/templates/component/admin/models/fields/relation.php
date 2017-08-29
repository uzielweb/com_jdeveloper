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
JFormHelper::loadFieldClass('list');

/**
 * Form field for ##Rel_name## items.
 *
 * @package		##Component##
 * @subpackage	Fields
 */
class JFormField##Rel_name## extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  11.1
	 */
	protected $type = '##rel_name##';

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
		$query->select("a.id, a.##rel_mainfield##")->from("#__##rel_table_db## as a");##{start_created_by}##

		if (!$user->authorise('core.admin', 'com_##component##'))
		{
			$query->where('a.created_by = ' . $user->get('id'));
		}##{end_created_by}####{start_catidORaccess}####{start_catid}##

		// Join over the categories.
		$query->select('c.access AS category_access')
			->join('LEFT', '#__categories AS c ON c.id = a.catid');##{end_catid}##

		// Implement View Level Access
		$user = JFactory::getUser();
		if (!$user->authorise('core.admin'))
		{
			$groups = implode(',', $user->getAuthorisedViewLevels());##{start_access}##
			$query->where('a.access IN (' . $groups . ')');##{end_access}####{start_catid}##
			$query->where('c.access IN (' . $groups . ')');##{end_catid}##
		}##{end_catidORaccess}##

		$db->setQuery($query);
		
		foreach ($db->loadObjectList() as $item)
		{
			// Create a new option object based on the <option /> element.
			$tmp = JHtml::_('select.option', $item->##rel_pk##, $item->##rel_mainfield##);

			// Add the option object to the result set.
			$options[] = $tmp;
		}

		// Merge any additional options in the XML definition.
		$options = array_merge(parent::getOptions(), $options);

		return $options;
	}
}
?>