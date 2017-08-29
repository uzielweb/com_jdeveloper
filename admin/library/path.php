<?php
/**
 * @package     JDeveloper
 * @subpackage  Library
 *
 * @copyright  	Copyright (C) 2014, Tilo-Lars Flasche. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

/**
 * Static class for path translation
 *
 * @package     JDeveloper
 * @subpackage  Library
 */
class JDeveloperPath
{
	/**
	 * Translates a path
	 *
	 * @param	string	$path	The path to translate
	 *
	 * @return	mixed	Translated path on success, false otherwise
	 */
	public static function dots2ds($path)
	{
		$path = str_replace(JPATH_ROOT, "", $path);
		
		if (JFile::exists(JPATH_ROOT . $path))
		{
			return JPATH_ROOT . $path;
		}
		
		$filepath = JFile::stripExt($path);
		$ext = JFile::getExt($path);
		$count = count(explode('.', $filepath));

		for ($i = 0; $i < $count - 1; $i++)
		{
			$filepath = substr_replace($filepath, DS, strpos($filepath, '.'), 1);

			if (JFile::exists(JPATH_ROOT . $filepath . '.' . $ext))
			{
				return JPATH_ROOT . $filepath . '.' . $ext;
			}
		}

		return false;
	}
}