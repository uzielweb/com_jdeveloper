<?php
/**
 * @package     JDeveloper
 * @subpackage  Create.Module
 *
 * @copyright  	Copyright (C) 2014, Tilo-Lars Flasche. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;
require_once JDeveloperCREATE . '/language.php';
require_once JDeveloperLIB . '/table.php';

/**
 * Module Language Create Class
 *
 * @package     JDeveloper
 * @subpackage  Create.Modue
 */
class JDeveloperCreateLanguageModuleSys extends JDeveloperCreateLanguage
{	
	/*
	 * The languages
	 *
	 * @var	array<String>
	 */
	protected $languages = array();

	/*
	 * The language key prefix
	 *
	 * @var	string
	 */
	protected $prefix;
	
	/*
	 * Is this a system language file (file ending sys.ini) ? 
	 *
	 * @var	boolean
	 */
	protected $system = true;

	/**
	 * Constructor
	 *
	 * @param	array	$config		The configuration
	 */
	public function __construct($config)
	{
		parent::__construct($config);
		
		$this->item			= $this->getModel('module')->getItem($config['item_id']);
		$this->languages	= $this->item->params["languages"];
		$this->prefix		= 'MOD_' . str_replace(' ', '_', strtoupper($this->item->name));
		$this->createDir	= $this->item->createDir . "/language";
		$this->element		= "mod_" . strtolower($this->item->name);
		
		if (empty($this->languages))
		{
			$this->languages = array('en-GB');
		}
	}
	
	/**
	 *	Creates the language files
	 *
	 *	@return	boolean		Have the files been created successfully?
	 */
	public function create()
	{		
		$this->sections[] =	$this->getINI($this->getSystem(), "system");
		
		if (!$this->write())
		{
			$this->setError($this->_name . " : Could not create file");
			return false;
		}
		else return true;
	}

	/**
	 * Get the system`s language keys
	 *
	 * @return	string	The language keys
	 */
	private function getSystem()
	{
		$array = array();

		$array['']					= ucfirst($this->item->name);
		$array['XML_DESCRIPTION']	= $this->item->description;
				
		return $array;
	}
}