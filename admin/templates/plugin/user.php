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
 * Joomla User plugin
 *
 * @package     Joomla.Plugin
 * @subpackage  User.##name##
 */
class plgUser##Name## extends JPlugin
{
	/**
	 * Method is called before user data is stored in the database
	 *
	 * @param	array	$user	Holds the old user data.
	 * @param	boolean	$isnew	True if a new user is stored.
	 * @param	array	$new	Holds the new user data.
	 *
	 * @since	1.6
	 */
	public function onUserBeforeSave($user, $isnew, $new)
	{
		$app = JFactory::getApplication();
	}

	/**
	 * Method is called after user data is stored in the database
	 *
	 * @param	array		$user		The user data.
	 * @param	boolean		$isNew		True if a new user is stored.
	 * @param	boolean		$success	True if user was succesfully stored in the database.
	 * @param	string		$msg		Message.
	 *
	 * @since	1.6
	 */
	public function onUserAfterSave($user, $isNew, $success, $msg)
	{
		$app = JFactory::getApplication();
	}

	/**
	 * Method is called before user data is deleted from the database
	 *
	 * @param	array	$user	The user data.
	 *
	 * @since	1.6
	 */
	public function onUserBeforeDelete($user)
	{
		$app = JFactory::getApplication();
	}

	/**
	 * Method is called after user data is deleted from the database
	 *
	 * @param	array		$user	The user data.
	 * @param	boolean		$succes	True if user was succesfully stored in the database.
	 * @param	string		$msg	Message.
	 *
	 * @since	1.6
	 */
	public function onUserAfterDelete($user, $succes, $msg)
	{
		$app = JFactory::getApplication();
	}

	/**
	 * This method is called after user login
	 *
	 * @param	array	$user		Holds the user data.
	 * @param	array	$options	Extra options.
	 *
	 * @return	boolean		True on success, false otherwise
	 *
	 * @since	1.5
	 */
	public function onUserLogin($user, $options)
	{
		return true;
	}

	/**
	 * This method is called after user logout
	 *
	 * @param	array	$user	Holds the user data.
	 *
	 * @return	boolean		True on success, false otherwise
	 *
	 * @since	1.5
	 */
	public function onUserLogout($user)
	{
		return true;
	}
}