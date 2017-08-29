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

/**
 * Class for operations on the filesystem of the archives
 *
 * @package     JDeveloper
 * @subpackage  Library
 */
class JDeveloperArchive
{
	protected static $filename = "#prefix##name#_v#version#";
	protected static $version_regex = "_v[0-9.]*";

	/**
	 * Copy language files of an extension to the archive
	 *
	 * @param	string	$extension		The extension name
	 * @param	string	$archivelangdir	The language folder of the archive
	 *
	 * @return	void
	 */
	public static function copyLanguageToArchive($extension, $archivelangdir = '', $client = 'admin')
	{
		$PATH_ADMIN = JDeveloperLIVE . "/" . $extension . "/" . $archivelangdir;
		
		JFolder::create($PATH_ADMIN);
		$langdir = ($client == 'admin') ? JPATH_ADMINISTRATOR : JPATH_SITE;
		
		foreach (JFolder::folders($langdir . "/language") as $tag)
		{
			$langpath = $langdir . "/language/" . $tag;
			$langfile = $tag . '.' . $extension;
			
			if (JFile::exists($langpath . "/" . $langfile . '.ini'))
			{
				JFile::copy($langpath . "/" . $langfile . '.ini', $PATH_ADMIN . "/" . $langfile . '.ini');
			}

			if (JFile::exists($langpath . "/" . $langfile . '.sys.ini'))
			{
				JFile::copy($langpath . "/" . $langfile . '.sys.ini', $PATH_ADMIN . "/" . $langfile . '.sys.ini');
			}
		}
	}

	/**
	 * Look for zip files with the given component name and delete them
	 *
	 * @param	string	$prefix		The extension`s prefix
	 * @param	string	$name		The extension`s name
	 * @param	string	$version	The version to compare
	 */
	public static function deleteOldVersions($prefix, $name, $version = "1.0.0")
	{
		jimport('joomla.filesystem.folder');
		$files = self::getVersions($prefix, $name);
		
		foreach ($files as $file)
		{			
			preg_match('/[0-9.]*/', $file, $matches);
			
			if (version_compare( $matches[(count($matches) - 1)], $version, "<"))
			{
				if (!JFile::delete(JDeveloperARCHIVE.DS . $file)) throw new JDeveloperException("Wrong path - $file");
			}
		}
	}
	
	/**
	 * Extract ZIP-file
	 *
	 * @param	string	$path	The ZIP-file
	 * @param	string	$dest	The destination
	 *
	 * @return	boolean		True on success, false otherwise
	 */
	public static function extract($path, $dest = '')
	{
		if (!JFile::exists($path) || JFile::getExt($path) != "zip") return false;
		if ($dest = '') $dest = JFile::stripExt($files);
		
		return JArchive::getAdapter('zip')->extract($path, $dest);
	}
	
	/** 
     * Add files and sub-directories in a folder to zip file. 
     *
	 * @param	string		$folder		the folder 
     * @param	ZipArchive	$zipFile	zipFile 
     * @param	string		$prefix		folder prefix
     */
	public static function folderToZip($folder, &$zipFile, $prefix)
	{ 
		$handle = opendir($folder);
		
		while (false !== $f = readdir($handle))
		{ 
			if ($f != '.' && $f != '..')
			{ 
				$filePath = $folder . DS . $f; 
				// Remove prefix from file path before add to zip. 
				$localPath = str_replace($prefix.DS, '', $filePath); 
				
				if (is_file($filePath))
				{ 
					$zipFile->addFile($filePath, $localPath); 
				}
				elseif (is_dir($filePath))
				{ 
					// Add sub-directory. 
					$zipFile->addEmptyDir($localPath); 
					self::folderToZip($filePath, $zipFile, $prefix); 
				} 
			} 
		} 
		
		closedir($handle);
	}
	
	/**
	 * Builds the archive direction
	 *
	 * @param	boolean	$url		True if path should be relative to admin base path
	 * @param	int		$user_id	The user id
	 *
	 * @return	string	The archive direction
	 */
	 public static function getArchiveDir($url = false, $user_id = 0)
	{
		$params = JComponentHelper::getParams("com_jdeveloper");
		$user = JFactory::getUser();
		
		if ($user_id == 0) {
			$user_id = $user->id;
		}
		
		$dir = $params->get("userarchives", 0) ? JDeveloperARCHIVE . "/user_" . $user_id : JDeveloperARCHIVE;
		if ($url) $dir = $params->get("userarchives", 0) ? JDeveloperARCHIVEURL . "/user_" . $user_id : JDeveloperARCHIVEURL;

		return $dir;
	}
	
	/**
	 * Finds all created zip files of the extension
	 *
	 * @param	string	$prefix		The extension prefix
	 * @param	string	$name		The extension name
	 * @param	string	$version	The extension name
	 *
	 * @return	array	The archive name
	 */
	public static function getArchiveName($prefix, $name, $version)
	{
		$filename = self::$filename;
		$filename = str_replace("#prefix#", $prefix, $filename);
		$filename = str_replace("#name#", $name, $filename);
		$filename = str_replace("#version#", $version, $filename);
		
		return $filename;
	}
	
	/**
	 * Finds all created zip files of the extension
	 *
	 * @param	string	$name		The extension name
	 * @param	string	$prefix		The extension prefix
	 *
	 * @return	array	The filenames
	 */
	public static function getVersions($prefix, $name)
	{		
		if (JFolder::exists(self::getArchiveDir()))
		{
			return JFolder::files(self::getArchiveDir(), $prefix . $name . self::$version_regex);
		}
		
		return array();
	}
	
	/**
	  *	Fill every folder in a direction with exact one html file
	  *
	  * @param	string	$folder		The directory
	  */
	public static function html($folder)
	{
		// Get stream
		if (empty($folder)) throw new JDeveloperException('No folder given');
		if (!JFolder::exists($folder)) JFolder::create($folder);
		$handle = opendir($folder);
		
		// Write an html file
		if (!$file = fopen($folder .DS. "index.html","w")) throw new JDeveloperException("Couldn`t open file $folder/index.html");
		$content = "<html><head></head><body></body></html>";
		fwrite($file, $content);
		fclose($file);
		
		// Look for other folders
		while (false !== $f = readdir($handle))
		{
			if ($f != '.' && $f != '..')
			{ 				
				$filePath = $folder .DS. $f;
				
				if (is_dir($filePath))
				{ 
					self::html($filePath);
				} 
			} 
		}
		
		closedir($handle);
	}
	
	/**
	 * Checks if there is a zip file named com_xxx in com_ccd/archive
	 *
	 * @param	string	$name		The extension`s name
	 * @param	string	$verion		The extension`s version
	 * @param	string	$prefix		The extension`s prefix
	 *
	 * @return	boolean	$version	True if exists, false if it doesn`t
	 */
	public static function isBuilt($name, $version = "1.0", $prefix = 'com_')
	{
		$path = JDeveloperARCHIVE.DS . $prefix . strtolower($name);
		$path .= '-' . $version;
		return JFile::exists($path . '.zip');
	}
	
	/**
	 * Gets the last time the component zip file has been changed
	 *
	 * @param	string	$name		The extension`s name
	 * @param	string	$verion		The extension`s version
	 * @param	string	$prefix		The extension`s prefix
	 *
	 * @return	String	Date on success
	 */
	public static function lastZip($name, $version = "1.0", $prefix = 'com_')
	{
		if (!self::isBuilt($name, $version, $prefix)) return "";
		
		$path = JDeveloperARCHIVE.DS . $prefix . strtolower($name) . '-' . $version;		
		return date ("Y-m-d - H:i A", filemtime($path));
	}
	
	/**
	 * Create a zip file from a directory
	 *
	 * @param	string	$folder		The component name
	 */
	public static function zip($folder)
	{
		if (!JFolder::exists($folder)) return false;
		
		$zipFile = new ZipArchive();
		$zipFile->open($folder . '.zip', ZipArchive::CREATE);
		self::folderToZip($folder, $zipFile, $folder);
	}
}