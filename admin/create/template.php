<?php
/**
 * @package     JDeveloper
 * @subpackage  Create.Template
 *
 * @copyright  	Copyright (C) 2014, Tilo-Lars Flasche. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;
require_once JDeveloperLIB.DS. 'create.php';

/**
 * Basic Template Create Class
 *
 * @package     JDeveloper
 * @subpackage  Create.Template
 */
class JDeveloperCreateTemplate extends JDeveloperCreate
{	
	/*
	 * The component folder
	 *
	 * @var	string
	 */
	protected $createDir;
	
	/*
	 * The template item
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
		if (!isset($config['item_id']) || empty($config['item_id'])) throw new Exception($this->_name . ": No template id given");
				
		// Get template data
		$this->item = $this->getModel('template')->getItem($config['item_id']);
		
		// Create template directory
		$this->createDir = $this->item->createDir;

		// Set template base dirs
		$this->templateDirs[0] = JDeveloperXTD . "/templates/template";
		$this->templateDirs[1] = JDeveloperTEMPLATES . "/template";
		$this->template = $this->getTemplate();
		
		if ($this->template === false)
		{
			throw new JDeveloperException($this->getErrors());
		}

		// Get the template header
		$params = JComponentHelper::getParams('com_jdeveloper');
		$header = new JDeveloperTemplate(JDeveloperTEMPLATES . '/fileheader.txt');
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
		$item = JModelLegacy::getInstance("Template", "JDeveloperModel")->getItem($config['item_id']);
		$dir = $item->createDir;
		
		// Create folders
		JFolder::create($dir . "/css");
		JFolder::create($dir . "/html");
		JFolder::create($dir . "/images");
		JFolder::create($dir . "/js");
		JFolder::create($dir . "/less");
		
		// Copy files
		JFile::copy(JDeveloperTEMPLATES . "/template/template.css", $dir . "/css/template.css");
		
		foreach (JFolder::files(JDeveloperCREATE . "/template", "php$") as $file)
		{
			$class = JDeveloperCreate::getInstance("template." . JFile::stripExt($file), $config);
			if (!$class->create())
			{
				$errors = $class->getErrors();
				if (!empty($errors)) throw new JDeveloperException($errors);
			}
		}
				
		JDeveloperCreate::getInstance("language.template", $config)->create();
		JDeveloperCreate::getInstance("language.template.sys", $config)->create();

		JFile::copy(JDeveloperTEMPLATES . "/template/favicon.ico", $dir . "/favicon.ico");
	}

	/**
	 * @see	JDeveloperCreate
	 */
	protected function getDefaultAreas()
	{
		$areas = array(
			"header" =>	false
		);
		
		return array_merge(parent::getDefaultAreas(), $areas);
	}

	/**
	 * @see	JDeveloperCreate
	 */
	protected function getDefaultPlaceholders()
	{
		$placeholders = array(
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
		return JDeveloperLanguage::getStaticInstance("tpl_" . $this->item->name);
	}
	
	/**
	 * @see	JDeveloperCreate
	 */
	protected function loadOverride($type = "", $item_id = "", $name = "")
	{
		$type = $type == "" ? "template" : $type;
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
			$path = str_replace('#name#', $this->item->name, $path);
			$path = str_replace('#version#', $this->item->version, $path);

			$path = $this->createDir . "/" . $path;
			$path = strtolower($path);
		}
		
		return parent::write(strtolower($path));
	}
}