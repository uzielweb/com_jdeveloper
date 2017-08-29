<?php
/**
 * @package     JDeveloper
 * @subpackage  Create.Plugin
 *
 * @copyright  	Copyright (C) 2014, Tilo-Lars Flasche. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;
require_once JDeveloperLIB . '/create.php';

/**
 * Basic Plugin Create Class
 *
 * @package     JDeveloper
 * @subpackage  Create.Plugin
 */
class JDeveloperCreatePlugin extends JDeveloperCreate
{	
	/*
	 * The component folder
	 *
	 * @var	string
	 */
	protected $createDir;
	
	/*
	 * The plugin item
	 *
	 * @var	JObject
	 */
	protected $item;
	
	/*
	 * The template header
	 *
	 * @var	string
	 */
	protected static $templateHeader;
	
	/**
	 * The constructor
	 */
	public function __construct($config = array())
	{		
		parent::__construct();

		$app = JFactory::getApplication();
		if (!isset($config['item_id']) || empty($config['item_id'])) throw new Exception($this->_name . ": No plugin id given");
		
		// Get plugin data
		$this->item = $this->getModel('plugin')->getItem($config['item_id']);
		$this->type = "plg_" . strtolower($this->item->folder) . "_" . strtolower($this->item->name);
		
		// Create plugin directory
		$this->createDir = $this->item->createDir;
				
		// Set template base dirs
		$this->templateDirs[0] = JDeveloperXTD . "/templates/plugin";
		$this->templateDirs[1] = JDeveloperTEMPLATES . "/plugin";
		$this->template = $this->getTemplate();
		
		if ($this->template === false)
		{
			throw new JDeveloperException($this->getErrors());
		}

		// Get the plugin header
		$params = JComponentHelper::getParams('com_jdeveloper');
		$header = new JDeveloperTemplate(JDeveloperTEMPLATES.DS . 'fileheader.txt');
		$header->addPlaceholders(
			array(
				'Author' => $params->get('author'),
				'Copyright' => $params->get('copyright'),
				'Extension' => ucfirst($this->item->name),
				'License' => $params->get('license'),
				'Version' => $this->item->version,
			)
		);
		self::$templateHeader = $header->getBuffer();
	}
	
	/**
	 * Check whether file should be created or not
	 *
	 * @return	boolean
	 */
	protected function condition()
	{
		return true;
	}

	/**
	 * Initialize and write files
	 */
	protected function create()
	{
		return $this->initialize()->write();
	}

	/**
	 * Execute Create Instance
	 *
	 * @param	string	$path	Path of create files
	 */
	public static function execute($config = array())
	{
		foreach (JFolder::files(JDeveloperCREATE . "/plugin", "php$") as $file)
		{			
			$class = JDeveloperCreate::getInstance("plugin." . JFile::stripExt($file), $config);
			if (!$class->create())
			{
				$errors = $class->getErrors();
				if (!empty($errors)) throw new JDeveloperException($errors);
			}
		}
		
		JDeveloperCreate::getInstance("language.plugin", $config)->create();
		JDeveloperCreate::getInstance("language.plugin.sys", $config)->create();
	}

	/**
	 * @see	JDeveloperCreate
	 */
	protected function getDefaultAreas()
	{
		$areas = array(
			"header"	=> false
		);
		
		return array_merge(parent::getDefaultAreas(), $areas);
	}

	/**
	 * @see	JDeveloperCreate
	 */
	protected function getDefaultPlaceholders()
	{
		$placeholders = array(
			'folder' 	=> $this->item->folder,
			'header' 	=> self::$templateHeader,
			'name' 		=> $this->item->name
		);
		
		return array_merge($placeholders, parent::getDefaultPlaceholders());
	}
	
	/**
	 * @see	JDeveloperCreate
	 */
	protected function getLanguage($name = "")
	{
		return JDeveloperLanguage::getStaticInstance("plg_" . $this->item->name);
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
		$type = $type == "" ? "plugin" : $type;
		$item_id = $item_id == "" ? $this->item->id : $item_id;
		$name = $name == "" ? $this->templateFile : $name;
		
		return parent::loadOverride($type, $item_id, $name);
	}

	/**
	 * @see	JDeveloperCreate
	 */
	public function write($path = '')
	{
		if ($path == '') 
		{
			$path = $this->templateFile;
			$path = str_replace(".", DS, JFile::stripExt($path)) . "." . JFile::getExt($path);
			$path = str_replace('manifest.xml', $this->item->name . ".xml", $path);
			$path = str_replace('#version#', $this->item->version, $path);

			$path = $this->createDir . "/" . $path;
			$path = strtolower($path);
		}
		
		return parent::write(strtolower($path));
	}
}