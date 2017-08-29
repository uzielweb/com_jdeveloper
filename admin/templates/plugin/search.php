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
 * Content Search plugin
 *
 * @package     Joomla.Plugin
 * @subpackage  Search.content
 * @since       1.6
 */
class PlgSearch##Name## extends JPlugin
{
	/**
	 * Method to handle the onContentSearchAreas event
	 *
	 * @return	array	An array of search areas
	 */
	public function onContentSearchAreas()
	{
		static $areas = array();
		
		return $areas;
	}

	/**
	 * Contacts Search method
	 *
	 * The sql must return the following fields that are used in a common display
	 * routine: href, title, section, created, text, browsernav
	 *
	 * @param	string	$text		Target search string
	 * @param	string	$phrase		matching option, exact|any|all
	 * @param	string	$ordering	ordering option, newest|oldest|popular|alpha|categoryd
	 * @param	array	$areas		areas
	 *
	 * @return	array	The results
	 */
	public function onContentSearch($text, $phrase = '', $ordering = '', $areas = null)
	{
		$results = array();
		
		return $results;
	}
}
