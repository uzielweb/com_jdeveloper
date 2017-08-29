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
 * Joomla Captcha plugin
 *
 * @package     Joomla.Plugin
 * @subpackage  Captcha.##name##
 */
class PlgCaptcha##Name## extends JPlugin
{
	/**
	 * Initialise the captcha
	 *
	 * @param   string  $id  The id of the field.
	 *
	 * @return  Boolean	True on success, false otherwise
	 *
	 * @since  2.5
	 */
	public function onInit($id)
	{
		return true;
	}

	/**
	 * Gets the challenge HTML
	 *
	 * @param   string  $name   The name of the field.
	 * @param   string  $id     The id of the field.
	 * @param   string  $class  The class of the field.
	 *
	 * @return  string  The HTML to be embedded in the form.
	 *
	 * @since  2.5
	 */
	public function onDisplay($name, $id, $class)
	{
		return "";
	}

	/**
	  * Check answer
	  *
	  * @param	string	$code	The code
	  *
	  * @return	boolean	True if the answer is correct, false otherwise
	  *
	  * @since  2.5
	  */
	public function onCheckAnswer($code)
	{
		return true;
	}
}