<?php
/**
 * @package     JDeveloper
 * @subpackage  Library
 *
 * @copyright  	Copyright (C) 2014, Tilo-Lars Flasche. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;
jimport('joomla.filesystem.folder');

/**
 * Class for working with language files
 *
 * @package     JDeveloper
 * @subpackage  Library
 */
class JDeveloperLanguage
{
	/**
	 * The language key prefix
	 *
	 * @var string
	 */
	protected $prefix = "";

	/**
	 * The language keys
	 *
	 * @var JRegistry
	 */
	protected $registry = null;

	/**
	 * Language object container
	 *
	 * @var array<JDeveloperLanguage>
	 */
	private static $instances = array();

	/**
	 * Constructor
	 *
	 * @param	string	$prefix		An optional prefix
	 */
	public function __construct($prefix = "")
	{
		$this->prefix = strtoupper($prefix);
		$this->registry = new JRegistry();
	}
	
	/**
	 * Add language keys
	 *
	 * @param	array	$array			The language keys
	 * @param	string	$prefix			An optional prefix
	 * @param	boolean	$addpprefix		Should the standart prefix be added?
	 *
	 * @return	void
	 */
	public function addKeys($array, $prefix = "", $addPrefix = true)
	{
		foreach ($array as $key => $value)
		{
			$parts = array();
			
			if ($addPrefix)
			{
				!empty($this->prefix) ? $parts[] = $this->prefix : null;
				!empty($prefix) ? $parts[] = $prefix : null;
			}
			else
			{
				!empty($prefix) ? $parts[] = $prefix : null;
			}
			
			!empty($key) ? $parts[] = $key : null;
			
			$this->registry->set(strtoupper(implode("_", $parts)), $value);
		}
	}
	
	/**
	 * Get lannguage keys as INI string
	 *
	 * @return	string
	 */
	public function getINI()
	{
		$array = $this->registry->toArray();
		ksort($array);
		$registry = new JRegistry($array);
		
		return $registry->toString("INI");
	}

	/**
	 * Get lannguage keys as INI string
	 *
	 * @return	JDeveloperLanguage
	 */
	public static function &getStaticInstance($name, $prefix = "")
	{
		if (!isset(self::$instances[$name]))
		{
			self::$instances[$name] = new JDeveloperLanguage($prefix);
		}
		
		return self::$instances[$name];
	}
}