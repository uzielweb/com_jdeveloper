<?php
/**
 * @package     JDeveloper
 * @subpackage  Library
 *
 * @copyright  	Copyright (C) 2014, Tilo-Lars Flasche. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');

/**
 * Class for installing extensions
 *
 * @package     JDeveloper
 * @subpackage  Library
 */
class JDeveloperInstall
{
	/**
	 * Delete all archives in the installation directory
	 */
	public static function cleanInstallDir()
	{
		$folders = JFolder::folders(JDeveloperINSTALL);
		$files = JFolder::files(JDeveloperINSTALL);
		
		foreach ($folders as $folder) JFolder::delete(JDeveloperINSTALL.DS.$folder);
		foreach ($files as $file) if ($file != 'index.html') JFile::delete(JDeveloperINSTALL.DS.$file);
	}
	
	/**
	 * Install component
	 *
	 * @param	string	$file		Path to extension archive
	 * @param	boolean	$update		True if extension should be updated, false if it is new
	 */
	public static function install($file, $update = false)
	{
		$installer = JInstaller::getInstance();
		$tmp = JDeveloperINSTALL . "/" . JFile::stripExt( JFile::getName($file) );
		JArchive::getAdapter('zip')->extract($file, $tmp);

		if ($update)
		{
			if (!$installer->update($tmp))
			{
				return false;
			}
		}
		else
		{
			if (!$installer->install($tmp))
			{
				return false;
			}
		}
		
		self::cleanInstallDir();
		return true;
	}

	/**
	 * Check if extension is installed
	 *
	 * @param	string	$type		The extension type (component, module, ...)
	 * @param	string	$element	The extension element (see database table #__extensions)
	 * @param	string	$folder		The folder (only important for plugins)
	 *
	 * @return	boolean		True if extension is installed, false otherwise
	 */
	public static function isInstalled($type, $element, $folder = '')
	{
		$db = JFactory::getDbo();
		
		// Is Component already installed?
		$query = $db->getQuery(true)
			->select('COUNT(*)')
			->from('#__extensions')
			->where("type = '$type'")
			->where("element = '$element'");
			
		if (!empty($folder)) $query->where("folder = '$folder'");
		$db->setQuery($query);
		$result = $db->loadResult();
		
		return !empty($result);
	}
	
	/**
	 * Uninstall extension
	 *
	 * @param	string	$type		The extension type (component, module, ...)
	 * @param	string	$element	The extension element (see database table #__extensions)
	 * @param	string	$folder		The folder (only important for plugins)
	 *
	 * @return	boolean		True if extension has been uninstalled, false otherwise
	 */
	public static function uninstall($type, $element, $folder = '')
	{
		$installer = JInstaller::getInstance();
		$db = JFactory::getDbo();
		
		// Is Component already installed?
		$query = $db->getQuery(true)
			->select('extension_id')
			->from('#__extensions')
			->where("type = '$type'")
			->where("element = '$element'");

		if (!empty($folder)) $query->where("folder = '$folder'");
		$db->setQuery($query);
		
		if (0 == $id = (int) $db->loadResult()) return false;		
		$installer->uninstall($type, $id);
		
		return true;
	}
}