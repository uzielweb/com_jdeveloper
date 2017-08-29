<?php
/**
 * @package     JDeveloper
 * @subpackage  Library
 *
 * @copyright  	Copyright (C) 2014, Tilo-Lars Flasche. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;
require_once JDeveloperLIB . "/path.php";

/**
 * Static class for loading class files
 *
 * @package     JDeveloper
 * @subpackage  Library
 */
class JDeveloperLoader
{
	/**
	 * Translates a classname
	 *
	 * @param	string	$path	The dot-seperated classname
	 *
	 * @return	string	The Classname
	 */
	public static function getClassname($name)
	{
		$name = explode(".", $name);
		$classname = "";
		
		foreach ($name as $part) {
			$classname .= ucfirst(strtolower($part));
		}
		
		return $classname;
	}
	
	/**
	 * Import class
	 *
	 * @param	string	The classname
	 * @param	string	The base path where to look for the class file
	 */
	public static function import($class, $basepath = "")
	{
		if (empty($basepath))
		{
			$basepath = JDeveloperLIB;
		}
		
		$path = JDeveloperPath::dots2ds($basepath . "/" . $class . ".php");
		
		if ($path === false)
		{
			throw new JDeveloperException(get_class() . ": Path <i>$class</i> not found in <i>$basepath</i>");
		}
		
		require_once($path);
	}
	
	/**
	 * Import helper class
	 *
	 * @param	string	$name	Helper class name
	 */
	public static function importHelper($name)
	{
		self::import($name, JPATH_COMPONENT_ADMINISTRATOR . "/helpers");
	}
}