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
 * Form field for form parent nodes.
 *
 * @package		JDeveloper
 * @subpackage	Fields
 */
class JFormFieldFormParent extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var        string
	 * @since   1.6
	 */
	protected $type = 'FormParent';

	/**
	 * Method to get the field options.
	 *
	 * @return  array  The field option objects.
	 * @since   1.6
	 */
	protected function getOptions()
	{
		$options = array();

		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('a.id AS value, a.name AS text, a.level')
			->from('#__jdeveloper_forms AS a')
			->join('LEFT', $db->quoteName('#__jdeveloper_forms') . ' AS b ON a.lft > b.lft AND a.rgt < b.rgt')
			->where('a.parent_id > 0');

		// Prevent parenting to children of this item.
		if ($id = $this->form->getValue('id'))
		{
			$query->join('LEFT', $db->quoteName('#__jdeveloper_forms') . ' AS p ON p.id = ' . (int) $id)
				->where('NOT(a.lft >= p.lft AND a.rgt <= p.rgt)');
		}

		$query->group('a.id, a.name, a.level, a.lft, a.rgt, a.parent_id');
		$query->order('a.lft ASC');

		// Get the options.
		$db->setQuery($query);

		try
		{
			$root = new JObject(array("value" => 1, "text" => JText::_("JGLOBAL_ROOT_PARENT"), "level" => 0));
			$options = array_merge(array($root), $db->loadObjectList());
		}
		catch (RuntimeException $e)
		{
			throw new Exception($e->getMessage());
		}

		// Pad the option text with spaces using depth level as a multiplier.
		for ($i = 0, $n = count($options); $i < $n; $i++)
		{
			$options[$i]->text = str_repeat('- ', $options[$i]->level) . $options[$i]->text;
		}

		// Merge any additional options in the XML definition.
		$options = array_merge(parent::getOptions(), $options);

		return $options;
	}
}
