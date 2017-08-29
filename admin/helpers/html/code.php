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
 * JDeveloper Field Helper
 *
 * @package     JDeveloper
 * @subpackage  Helpers
 */
class JHtmlCode
{
	public static function form($code)
	{
		$code = htmlspecialchars($code);
		$code = preg_replace('#(&lt;[/]?)([a-zA-Z]*)(&gt;)?#i', "<span style=\"color:orange\">$1$2$3</span>", $code);
		$code = preg_replace('#/&gt;#i', "<span style=\"color:orange\">$0</span>", $code);
		$code = preg_replace('#([A-Za-z0-9_]*)=&quot;([A-Za-z0-9-_/. ]*)&quot;#i', "<span style=\"color:blue\">$1</span>=\"<span style=\"color:grey\">$2</span>\"", $code);
		$code = preg_replace('/\t/', "&nbsp;&nbsp;&nbsp;&nbsp;", $code);
		
		return nl2br($code);
	}
	
	public static function sql($code)
	{
		$code = preg_replace('/(CREATE TABLE IF NOT EXISTS|NOT NULL|CHARACTER SET|COLLATE|AUTO_INCREMENT|PRIMARY KEY|DEFAULT|COMMENT|unsigned)/', "<span style=\"color:blue\">$0</span>", $code);
		$code = preg_replace('/`.*`/', "<span style=\"color:orange\">$0</span>", $code);
		$code = preg_replace('/\'.*\'/', "<span style=\"color:#999999\">$0</span>", $code);
		$code = preg_replace('/\t/', "&nbsp;&nbsp;&nbsp;&nbsp;", $code);

		return nl2br($code);
	}
}