<?php
/**
 * @package     JDeveloper
 * @subpackage  Create.Language
 *
 * @copyright  	Copyright (C) 2014, Tilo-Lars Flasche. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;
require_once JDeveloperLIB . '/create.php';
jimport('joomla.filesystem.file');

/**
 * Language Create Class
 *
 * @package     JDeveloper
 * @subpackage  Create.Language
 */
abstract class JDeveloperCreateLanguage extends JDeveloperCreate
{
	/*
	 * The extension element (see database #__extensions column 'element')
	 *
	 * @var	string
	 */
	protected $element;
		
	/*
	 * The languages
	 *
	 * @var	array
	 */
	protected $languages = array();
		
	/*
	 * The language key prefix
	 *
	 * @var	string
	 */
	protected $prefix;
	
	/*
	 * The language sections
	 *
	 * @var	array
	 */
	protected $sections = array();

	/*
	 * Is this a system language file (file ending sys.ini) ? 
	 *
	 * @var	boolean
	 */
	protected $system = false;

	/**
	 * Constructor
	 *
	 * @param	array	$config		The configuration
	 */
	public function __construct($config = array())
	{
		$this->_name = get_class($this);
	}

	/**
	 * Get buffer
	 *
	 * @return	string	The file content
	 */
	public function getBuffer()
	{
		return implode("\n\n", $this->sections);
	}

	/**
	 * Get INI encoded language translation of section
	 *
	 * @param	array	$elements	The elements (language key => translation)
	 * @param	string	$name		The section name
	 * @param	string	$pfx		Additional prefix
	 * @param	mixed	$comment	Comment for this secion (either string or array)
	 *
	 * @return	string	The INI language code
	 */
	protected function getINI($elements, $name, $pfx = "", $comment = "")
	{
		$registry = new JRegistry();
		
		foreach ($elements as $key => $translation)
		{
			$lkey = array();
			
			(!empty($this->prefix))	? $lkey[] = $this->prefix	: null;
			(!empty($pfx))			? $lkey[] = $pfx			: null;
			(!empty($key))			? $lkey[] = $key			: null;
			
			$lkey = implode("_", $lkey);
			
			$registry->set(strtoupper($lkey), $translation);
		}
		
		if (is_array($comment))
		{
			foreach($comment as $key => $line) $comment[$key] = ";;" . $line;
			$comment = implode("\n\n", $comment);
		}
		
		$ini = "[$name]\n" . $registry->toString("INI");
		$ini .= (!empty($comment)) ? $comment . "\n" : "";
		
		return $ini;
	}

	/**
	 * Create file
	 *
	 * @param	string	$path	The create path
	 *
	 * @return 	boolean	true on success, false otherwise
	 */
	protected function write($path = '')
	{
		jimport('joomla.filesystem.folder');
		
		if (!empty($path))
		{
			$this->createDir = $path;
		}
		elseif (empty($this->createDir))
		{
			$this->setError($this->_name . " : No write path given");
			return false;
		}

		if (empty($this->languages)) 
		{
			$this->setError($this->_name . " : No languages given");
			return false;
		}
		
		if (empty($this->element)) 
		{
			$this->setError($this->_name . " : No extension element given");
			return false;
		}

		$buffer = $this->getBuffer();
		JFolder::create($this->createDir);
				
		foreach ($this->languages as $language)
		{
			$path = $this->createDir . "/" . $language . "." . $this->element;
			$path .= $this->system ? ".sys.ini" : ".ini";
			
			if (!JFile::write($path, $buffer, true))
			{
				return false;
			}
		}
		
		return true;
	}
}