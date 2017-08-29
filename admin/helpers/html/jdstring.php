<?php
/**
 * @package     JDeveloper
 * @subpackage  Helpers
 *
 * @copyright  	Copyright (C) 2014, Tilo-Lars Flasche. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

/**
 * JDString Html class
 *
 * @package     JDeveloper
 * @subpackage  Helpers
 */
class JHtmlJDString
{
	/**
	 * Fill left
	 *
	 * @param	string	$text		Edit this text
	 * @param	int		$number		Fill text with $number elements
	 * @param	string	$fill		The element to fill the text with
	 *
	 * @return	string	The edited text
	 */
	public static function fillleft($text, $number, $fill)
	{
		$text = explode("\n", $text);
		
		foreach ($text as $line)
		{
			for ($i = 0; $i < $number; $i++)
				$text[$i] = $fill . $line;
		}
		
		return implode("\n", $text);
	}
}