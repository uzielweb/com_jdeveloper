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
 * Form field for joomla field types.
 *
 * @package		JDeveloper
 * @subpackage	Fields
 */
class JFormFieldFieldcreate extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since	1.6
	 */
	protected $type = 'fieldcreate';
	
	/**
	 * Method to get the field options.
	 *
	 * @return  array  The field option objects.
	 *
	 * @since   11.1
	 */
	protected function getOptions()
	{
		$options = array();
		
		jimport('joomla.filesystem.file');
		jimport('joomla.filesystem.folder');
		
		foreach (JFolder::files(JDeveloperTEMPLATES . "/fields/formfields", "\.php$") as $file)
		{
			$options[JFile::stripExt($file)] = JHtml::_('select.option', JFile::stripExt($file), "JFormField" . ucfirst(JFile::stripExt($file)));
		}

		// Merge any additional options in the XML definition.
		$options = array_merge(parent::getOptions(), $options);

		return $options;
	}
}