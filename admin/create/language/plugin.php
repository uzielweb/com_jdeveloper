<?php
/**
 * @package     JDeveloper
 * @subpackage  Create.Plugin
 *
 * @copyright  	Copyright (C) 2014, Tilo-Lars Flasche. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;
require_once JDeveloperCREATE . '/language.php';
require_once JDeveloperLIB . '/table.php';

/**
 * Plugin Language Create Class
 *
 * @package     JDeveloper
 * @subpackage  Create.Plugin
 */
class JDeveloperCreateLanguagePlugin extends JDeveloperCreateLanguage
{	
	/*
	 * The template item
	 *
	 * @var	JObject
	 */
	protected $item;

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
	protected $system = false;

	/**
	 * Constructor
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);
		
		$this->item = $this->getModel('plugin')->getItem($config['item_id']);

		$this->createDir = $this->item->createDir . "/language";
		$this->element = "plg_" . strtolower($this->item->folder) . "_" . strtolower($this->item->name);

		// Language
		$this->languages 	 = $this->item->params['languages'];
		$this->prefix 		 = "PLG_" . strtoupper($this->item->folder) . "_" . strtoupper($this->item->name);
		
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
		$this->sections[] =	$this->getINI($this->getSectionPlugin(), $this->item->name);

		if (!$this->write())
		{
			$this->setError($this->name . " : Could not create file");
			return false;
		}
		else return true;
	}
		
	/**
	 * Get the default language keys
	 *
	 * @return	string	The language keys
	 */
	public function getSectionPlugin()
	{
		$array = array();
		$array[""] = ucfirst($this->item->display_name);
		$array['XML_DESCRIPTION'] = $this->item->description;
				
		return $array;
	}
}