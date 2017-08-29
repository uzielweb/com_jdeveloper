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
class JFormFieldFieldtype extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since	1.6
	 */
	protected $type = 'fieldtype';
	
	/**
	 * Method to get the field options.
	 *
	 * @return  array  The field option objects.
	 *
	 * @since   11.1
	 */
	protected function getOptions()
	{
		$options = array(
			'accesslevel', 'cachehandler', 'calendar', 'captcha', 'category', 'checkbox', 'checkboxes', 'color', 'componentlayout', 'editor', 'editors', 
			'email', 'file', 'filelist', 'folderlist', 'groupedlist', 'header tag', 'hidden', 'imagelist', 'integer', 'language', 'list', 'media', 'menu',
			'Menu Item', 'note', 'plugins', 'password', 'radio', 'rules', 'sql', 'tag', 'tel', 'templatestyle', 'text', 'textarea', 'timezone', 'URL', 'user', 'usergroup'
		);
		
		jimport('joomla.filesystem.folder');
		//$fields = JFolder::files(JPATH_COMPONENT_ADMINISTRATOR . "/templates/fields");
		
		$options[] = '';
		/*
		foreach ($fields as $field)
		{
			$options[] = str_replace('.php', '', $field);
		}
		*/
		foreach ($options as $key => $option)
		{
			$options[$key] = JHtml::_('select.option', $option, ucfirst($option));
		}		
		
		// Merge any additional options in the XML definition.
		$options = array_merge(parent::getOptions(), $options);

		return $options;
	}
}