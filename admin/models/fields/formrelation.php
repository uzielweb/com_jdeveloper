<?php
/**
 * @package     JDeveloper
 * @subpackage  Fields
 *
 * @copyright  	Copyright (C) 2014, Tilo-Lars Flasche. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('JPATH_BASE') or die();
JFormHelper::loadFieldClass('groupedlist');

/**
 * Form field for joomla form relations.
 *
 * @package		JDeveloper
 * @subpackage	Fields
 */
class JFormFieldFormRelation extends JFormFieldGroupedlist
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since	1.6
	 */
	protected $type = 'formrelation';
	
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
		$db = JFactory::getDbo();		
		$groups = array("Components", "Tables");

		// Component relations
		$query = $db->getQuery(true)
			->select("a.relation")
			->from("#__jdeveloper_forms AS a")
			->where("a.relation LIKE 'component%'")
			->where("a.level = 1");
		$rows = $db->setQuery($query)->loadAssocList();
		$model = JModelLegacy::getInstance("Component", "JDeveloperModel");
		$group = JText::_("COM_JDEVELOPER_FORM_FILTER_GROUP_COMPONENTS");
		
		foreach ($rows as $row) {
			$id = explode(".", $row->relation)[1];
			$item = $model->getItem($id);
			$groups[$group][] = JHtml::_('select.option', $row->relation, ucfirst($item->name));
		}
		
		// Table relations
		$query = $db->getQuery(true)
			->select("a.relation")
			->from("#__jdeveloper_forms AS a")
			->where("a.relation LIKE 'table%'")
			->where("a.level = 1");
		$rows = $db->setQuery($query)->loadObjectList();
		$model = JModelLegacy::getInstance("Table", "JDeveloperModel");
		$group = JText::_("COM_JDEVELOPER_FORM_FILTER_GROUP_TABLES");
		
		foreach ($rows as $row) {
			$id = explode(".", $row->relation)[1];
			$item = $model->getItem($id);
			$groups[$group][] = JHtml::_('select.option', $row->relation, ucfirst($item->name));
		}
		
		return array_merge(parent::getGroups(), $groups);
	}
}