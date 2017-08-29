<?php
/**
 * @package     JDeveloper
 * @subpackage  Create.Form
 *
 * @copyright  	Copyright (C) 2014, Tilo-Lars Flasche. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;
JDeveloperLoader::import("create");

/**
 * Basic create class for form
 *
 * @package     JDeveloper
 * @subpackage  Create.Form
 */
class JDeveloperCreateForm extends JDeveloperCreate
{	
	/*
	 * The form data
	 *
	 * @var	array<JObject>
	 */
	protected $item;
	
	/**
	 * The constructor
	 */
	public function __construct($config = array())
	{
		parent::__construct();

		$app = JFactory::getApplication();
		if (!isset($config['item_id']) || empty($config['item_id'])) throw new JDeveloperException($this->_name . ": No form id given");
		
		// Get form data
		$this->item = $this->getModel('form')->getItem($config['item_id']);

		// Set template base dirs
		$this->templateDirs[0] = JDeveloperXTD . "/templates/form";
		$this->templateDirs[1] = JDeveloperTEMPLATES . "/form";
		$this->template = $this->getTemplate();
		
		if ($this->template === false)
		{
			throw new JDeveloperException($this->getErrors());
		}
	}

	/**
	 * Create procedure
	 */
	public function create()
	{
		return $this->initialize()->write();
	}

	/**
	 * @see	JDeveloperCreate
	 */
	protected function getDefaultAreas()
	{
		$areas = array();
		return array_merge($areas, parent::getDefaultAreas());
	}

	/**
	 * @see	JDeveloperCreate
	 */
	protected function getDefaultPlaceholders()
	{
		$placeholders = array();
		return array_merge($placeholders, parent::getDefaultPlaceholders());
	}

	/**
	 * @see	JDeveloperCreate
	 */
	protected function initialize()
	{
		// standart placeholders
		$this->template->addAreas($this->getDefaultAreas());
		$this->template->addPlaceHolders($this->getDefaultPlaceholders(), true);
		
		return parent::initialize();
	}
	
	/**
	 * @see	JDeveloperCreate
	 */
	protected function loadOverride($type = "", $item_id = "", $name = "")
	{
		$type = $type == "" ? "form" : $type;
		$item_id = $item_id == "" ? $this->item->id : $item_id;
		$name = $name == "" ? $this->templateFile : $name;
		
		return parent::loadOverride($type, $item_id, $name);
	}

	/**
	 * @see	JDeveloperCreate
	 */
	public function write($path = '')
	{
		return parent::write(strtolower($path));
	}
}