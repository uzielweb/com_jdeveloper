<?php
/**
 * @package     JDeveloper
 * @subpackage  JDeveloper
 *
 * @copyright  	Copyright (C) 2014, Tilo-Lars Flasche. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

/**
 * JDeveloper config class.
 *
 * @package     JDeveloper
 * @subpackage  JDeveloper
 */
class JDeveloperConfig
{	
	/**
	 * Load content from a config file and decode it
	 * 
	 * @param	string	$name	The configuration file name
	 * @return	mixed	either an array or an object
	 */
	public static function getConfig($name) {
		$file = JDeveloperCONFIG . "/" . $name . ".json";
		$json = JFile::read($file);
		return json_decode($json);
	}
}