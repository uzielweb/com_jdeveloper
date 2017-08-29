<?php##{start_header}##
/**
 * @package     JDeveloper
 * @subpackage  Templates.Component
 *
 * @copyright  	Copyright (C) 2014, Tilo-Lars Flasche. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;##{end_header}##
##Header##

/**
 * ##Component## Component Category Tree
 *
 * @static
 * @package     ##Component##
 * @subpackage  Helpers
 */
class ##Component##Categories extends JCategories
{
	/**
	 * Constructor
	 */
	public function __construct($options = array())
	{
		$options["extension"] = "com_##component##";
		$options["table"] = "#__##table_db##";
		$options["field"] = "catid";
		$options["key"] = "##pk##";
		$options["statefield"] = "published";##{!start_access}##		
		$options['access'] = 0;##{!end_access}####{!start_published}##
		$options['published'] = 0;##{!end_published}##

		parent::__construct($options);
	}
}