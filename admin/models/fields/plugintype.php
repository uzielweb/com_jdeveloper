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
 * Form field for joomla plugin types.
 *
 * @package		JDeveloper
 * @subpackage	Fields
 */
class JFormFieldPlugintype extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since	1.6
	 */
	protected $type = 'plugintype';
	
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
		$db->setQuery($db->getQuery(true)->select("DISTINCT `folder` FROM #__extensions"));
		$results = $db->loadRowList();
		$options = array();
		
		foreach ($results as $result) {
			$options[$result[0]] = JHtml::_('select.option', $result[0], ucfirst($result[0]));
		}
		
		foreach (JFolder::folders(JPATH_ROOT . "/plugins") as $folder) {
			if (!isset($options[$folder]))
				$options[$folder] = JHtml::_('select.option', $folder, ucfirst($folder));
		}
		
		// Merge any additional options in the XML definition.
		$options = array_merge(parent::getOptions(), $options);
		ksort($options);

		return $options;
	}
}