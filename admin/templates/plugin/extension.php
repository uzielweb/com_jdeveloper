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
 * Joomla Extension plugin
 *
 * @package     Joomla.Plugin
 * @subpackage  Extension.##name##
 */
class plgExtension##Name## extends JPlugin
{
	
	/**
	 * Handle post extension install update sites
	 *
	 * @param	JInstaller	$installer	Installer object
	 * @param	int			$eid		Extension Identifier
	 *
	 * @since	1.6
	 */
	function onExtensionAfterInstall($installer, $eid)
	{

	}

	/**
	 * Allow to processing of extension data after it is saved.
	 *
	 * @param	object	$data	The data representing the extension.
	 * @param	boolean	$isNew	True is this is new data, false if it is existing data.
	 *
	 * @since	1.6
	 */
	function onExtensionAfterSave($data, $isNew)
	{

	}

	/**
	 * Handle extension uninstall
	 *
	 * @param	JInstaller	$installer	Installer instance
	 * @param	int			$eid		extension id
	 * @param	int			$result		installation result
	 *
	 * @since	1.6
	 */
	function onExtensionAfterUninstall($installer, $eid, $result)
	{

	}

	/**
	 * After update of an extension
	 *
	 * @param	JInstaller	$installer	Installer object
	 * @param	int			$eid		Extension identifier
	 *
	 * @since	1.6
	 */
	function onExtensionAfterUpdate($installer, $eid)
	{

	}

	/**
	 * onExtensionBeforeInstall
	 *
	 * @param	?		$method		?
	 * @param	string	$type		?
	 * @param	?		$manifest	?
	 * @param	int		$eid		?
	 *
	 * @since	1.6
	 */
	function onExtensionBeforeInstall($method, $type, $manifest, $eid)
	{

	}

	/**
	 * Allow to processing of extension data before it is saved.
	 *
	 * @param	object	$data	The data representing the extension.
	 * @param	boolean	$isNew	True is this is new data, false if it is existing data.
	 *
	 * @since	1.6
	 */
	function onExtensionBeforeSave($data, $isNew)
	{
	
	}

	/**
	 * This method is called before extension is uninstalled
	 *
	 * @param	int		$eid	extension id
	 *
	 * @since	1.6
	 */
	function onExtensionBeforeUninstall($eid)
	{

	}

	/**
	 * This method is called before extension is updated
	 *
	 * @param	string	$type		The extension type
	 * @param	?		$manifest	The extension manifest
	 *
	 * @since	1.6
	 */
	function onExtensionBeforeUpdate($type, $manifest)
	{

	}
}