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
 * Form Field class for the ##singular## parent id.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_##component##
 */
class JFormField##Singular##Parent extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var        string
	 * @since   1.6
	 */
	protected $type = '##Singular##Parent';

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
			->select('a.##pk## AS value, a.##mainfield## AS text, a.level')
			->from('#__##table_db## AS a')
			->join('LEFT', $db->quoteName('#__##table_db##') . ' AS b ON a.lft > b.lft AND a.rgt < b.rgt')
			->where('a.parent_id > 0');

		// Prevent parenting to children of this item.
		if ($##pk## = $this->form->getValue('##pk##'))
		{
			$query->join('LEFT', $db->quoteName('#__##table_db##') . ' AS p ON p.##pk## = ' . (int) $##pk##)
				->where('NOT(a.lft >= p.lft AND a.rgt <= p.rgt)');
		}
##{start_published}##
		$query->where('a.published != -2');##{end_published}##
		$query->group('a.##pk##, a.##mainfield##, a.level, a.lft, a.rgt, a.parent_id##{start_published}##, a.published##{end_published}##');
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
