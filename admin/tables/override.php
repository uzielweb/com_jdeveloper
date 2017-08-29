<?php
/**
 * @package     JDeveloper
 * @subpackage  Tables
 *
 * @copyright  	Copyright (C) 2014, Tilo-Lars Flasche. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

/**
 * JDeveloper Override Table
 *
 * @package     JDeveloper
 * @subpackage  Tables
 */
class JDeveloperTableOverride extends JTable
{	
	public function __construct($db)
	{
		parent::__construct('#__jdeveloper_overrides', 'id', $db);
	}
	
	public function bind($array, $ignore = '')
	{
		if (isset($array['params']) && is_array($array['params']))
		{
			ksort($array["params"]);
			$registry = new JRegistry();
			$registry->loadArray($array['params']);
			$array['params'] = (string) $registry;
		}

		return parent::bind($array, $ignore);
	}
}