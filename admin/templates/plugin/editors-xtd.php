<?php##{start_header}##
/**
 * @package     JDeveloper
 * @subpackage  Templates.Plugin
 *
 * @copyright  	Copyright (C) 2014, Tilo-Lars Flasche. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;##{end_header}##
##Header##

/**
 * Joomla Editor Button plugin
 *
 * @package     Joomla.Plugin
 * @subpackage  Editors-Xtd.##name##
 */
class PlgButton##Name## extends JPlugin
{
	/**
	 * Display the button
	 *
	 * @param   string  $name  The name of the button to add
	 *
	 * @return	object	The button
	 */
	public function onDisplay($name)
	{
		JHtml::_('behavior.modal');

		$link = '';

		$button = new JObject;
		$button->modal = true;
		$button->class = 'btn';
		$button->link = $link;
		$button->text  = JText::_('PLG_EDITORSXTD_##NAME##_BUTTON_##NAME##');
		$button->name = '##name##';
		$button->options = "{handler: 'iframe', size: {x: 500, y: 300}}";

		return $button;
	}
}
