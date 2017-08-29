<?php
/**
 * @package     JDeveloper
 * @subpackage  Create.Table
 *
 * @copyright  	Copyright (C) 2014, Tilo-Lars Flasche. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;
JDeveloperLoader::import("create");

/**
 * Basic create class for table
 *
 * @package     JDeveloper
 * @subpackage  Create.Table
 */
class JDeveloperCreateTable extends JDeveloperCreate
{	
	/*
	 * The component data
	 *
	 * @var	JObject
	 */
	protected $component;
	
	/*
	 * The fields data
	 *
	 * @var	array<JObject>
	 */
	protected $fields;
	
	/*
	 * The direction where the file should be created
	 *
	 * @var	string
	 */
	protected $filePath;
	
	/*
	 * The current table data
	 *
	 * @var	JObject
	 */
	protected $table;
	
	/*
	 * Look for language keys in the template and add them
	 *
	 * @var	boolean
	 */
	protected $getLangKeys = false;
	
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
		if (!isset($config['item_id']) || empty($config['item_id'])) throw new JDeveloperException($this->_name . ": No table id given");
		
		// Get table data
		$this->table = $this->getModel('table')->getItem($config['item_id']);
		
		// Get component data
		$this->component = $this->getModel('component')->getItem($this->table->component);

		// Get component data
		$this->fields = $this->getModel('fields')->getTableFields($this->table->id);

		// Get component directory
		$this->createDir = $this->component->createDir;
		$this->filePath = $this->templateFile;

		// Set template base dirs
		$this->templateDirs[0] = JDeveloperXTD . "/templates/component";
		$this->templateDirs[1] = JDeveloperTEMPLATES . "/component";
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
				'License' => $params->get('license'),
				'Version' => $this->component->version,
				'Extension' => ucfirst($this->component->display_name),
			)
		);
		self::$templateHeader = $header->getBuffer();
	}

	/**
	 * Add language keys
	 *
	 * @param	array	$langkeys	The language keys
	 * @param	string	$name		The language section name
	 *
	 * @return	void
	 */
	public function addLanguageKeys($langkeys, $name = "")
	{
		$language = $this->getLanguage($name);
		$prefix = "COM_" . strtoupper($this->component->name);

		foreach ($langkeys as $langkey)
		{
			$search = str_replace($prefix, "JDEVELOPER", $langkey);
			$search = str_replace("_" . strtoupper($this->table->name), "_TABLE", $search);
			$search = str_replace("_" . strtoupper($this->table->plural), "_PLURAL", $search);
			$search = str_replace("_" . strtoupper($this->table->singular), "_SINGULAR", $search);

			$translation = JText::_($search);
			$translation = preg_replace("/##component##/i", $this->component->display_name, $translation);
			$translation = preg_replace("/##plural##/i", $this->table->plural, $translation);
			$translation = preg_replace("/##singular##/i", $this->table->singular, $translation);

			$language->addKeys(array($langkey => $translation), "", false);
		}
	}

	/**
	 * Initialize template and create the files
	 *
	 * @return	boolean	true on success, false otherwise
	 */
	public function create()
	{
		$db = JFactory::getDbo();
		
		// Check condition
		if ($this->condition())
		{
			if (!$this->initialize()->write())
			{
				$this->setError($this->_name . " : Could not create file <i>" . $this->getWritePath() . "</i>");
				return false;
			}
			
			if ($this->getLangKeys)
			{
				$this->addLanguageKeys($this->template->getLanguageKeys(array("COM_" . strtoupper($this->component->name) . "_[A-Z0-9_]*")));
			}
		}

		return true;
	}
		
	/**
	 * Execute Create Instance
	 *
	 * @param	string	$client	(admin | site)
	 * @param	array	$config	Configuration
	 */
	public static function execute($client, $config = array())
	{
		$client = str_replace(".", DS, $client);
		$component = JModelLegacy::getInstance("Component", "JDeveloperModel")->getItem($config["item_id"]);
		
		// Get each file from this folder and get instance of create class
		foreach (JFolder::files(JDeveloperCREATE . "/table/$client", "php$") as $file)
		{
			// Execute create classes for each table
			foreach (JModelLegacy::getInstance("Tables", "JDeveloperModel")->getComponentTables($component->id) as $table)
			{
				$table_config = array("item_id" => $table->id);
				$class = JDeveloperCreate::getInstance("table.$client." . JFile::stripExt($file), $table_config);

				if (!$class->create())
				{
					$errors = $class->getErrors();
					if (!empty($errors)) throw new JDeveloperException($errors);
				}
			}
		}
	}

	/**
	 * @see	JDeveloperCreate
	 */
	protected function getDefaultAreas()
	{
		$areas = array(
			'admin'				=> preg_match('/^admin/', $this->filePath),
			'site'				=> preg_match('/^site/', $this->filePath),
			'header' 			=> false,
			'publishdate'		=> $this->table->jfields["publish_up"] || $this->table->jfields["publish_down"],
			'tags'				=> false
		);
		
		// Add every Joomla core field to template areas (i.e. created_by => true)
		foreach ($this->table->jfields as $field => $val)
		{
			$areas[$field] = (bool) $val;
		}
		
		if (isset($this->table->type))
		{
			$areas["alias"] = true;
		}

		$areas["table_nested"] = isset($this->table->type) ? $this->table->type == "tree" : false;
		
		return array_merge($areas, parent::getDefaultAreas());
	}

	/**
	 * @see	JDeveloperCreate
	 */
	protected function getDefaultPlaceholders()
	{
		$placeholders = array(
			'header' 		=> self::$templateHeader,
			'component'		=> $this->component->name,
			'mainfield'		=> (isset($this->fields[0])) ? $this->fields[0]->name : $this->table->pk,
			'table'			=> $this->table->name,
			'table_db'		=> $this->table->dbname,
			'plural'		=> $this->table->plural,
			'pk'			=> $this->table->pk,
			'singular'		=> $this->table->singular
		);
	
		if (!empty($this->fields))
		{
			$placeholders["textfield"] = $this->fields[0]->name;
			foreach ($this->fields as $field)
			{
				if ($field->type == "editor" || $field->type == "textarea")
				{
					$placeholders["textfield"] = $field->name;
					break;
				}
			}
		}

		return array_merge($placeholders, parent::getDefaultPlaceholders());
	}
	
	/**
	 * @see	JDeveloperCreate
	 */
	protected function getLanguage($name = "")
	{
		$_name = "com_" . $this->component->name;

		if (!empty($name))
		{
			$_name .= "_" . $name;
		}

		return JDeveloperLanguage::getStaticInstance($_name, strtoupper("COM_" . $this->component->name));
	}

	/**
	 * @see	JDeveloperCreate
	 */
	protected function getTemplate()
	{
		$template = parent::getTemplate();
		
		if ($template === false)
		{
			// If any errors exist throw Exception
			$errors = $this->getErrors();
			if (!empty($errors))
			{
				throw new JDeveloperException($errors);
			}
			
			// Look for template with same name in admin folder
			$this->templateFile = preg_replace('/^site/', 'admin', $this->templateFile);
			$template = parent::getTemplate();
			$this->templateFile = preg_replace('/^admin/', 'site', $this->templateFile);

			if ($template === false)
			{
				$this->setError($this->_name . ": No template found");
				throw new JDeveloperException($this->getErrors());
			}
			
			$this->template = $template;
			return $this->template;
		}
		
		$this->template = $template;
		return $this->template;
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
		if (empty($type))
		{
			$type = "table.";
			$type .= preg_match("/^JDeveloperCreateTableAdmin/", $this->_name) ? "admin" : "site";
		}
		
		$item_id = $item_id == "" ? $this->table->id : $item_id;
		$name = $name == "" ? $this->templateFile : $name;
		
		return parent::loadOverride($type, $item_id, $name);
	}

	/**
	 * @see	JDeveloperCreate
	 */
	public function loadSubtemplate($filename, $sep = '.')
	{
		$sub = parent::loadSubtemplate($filename);
		
		if ($sub === false)
		{
			$templateFile = JFile::stripExt($this->templateFile) . $sep . $filename;
			$templateFile = preg_replace("/^site/", "admin", $templateFile);
			
			foreach ($this->templateDirs as $templateDir)
			{
				$dir = JDeveloperPath::dots2ds($templateDir . "/" . $templateFile);

				if ($dir !== false)
				{
					return new JDeveloperTemplate($dir);
				}
			}

			$this->setError($this->_name . ": Subtemplate <i>'$basepath.$filename'</i> not found");
			throw new JDeveloperException($this->getErrors());
		}
		
		return $sub;
	}
	
	/**
	 * @see	JDeveloperCreate
	 */
	public function write($path = '')
	{
		if ($path == '') 
		{
			$path = $this->filePath;
			$path = str_replace(".", DIRECTORY_SEPARATOR, JFile::stripExt($path)) . "." . JFile::getExt($path);
			$path = str_replace('component', $this->component->name, $path);
			$path = str_replace('view' . DS . 'feed', 'view.feed', $path);
			$path = str_replace('view' . DS . 'html', 'view.html', $path);
			$path = str_replace('plural', $this->table->plural, $path);
			$path = str_replace('singular', $this->table->singular, $path);
			
			$path = $this->createDir . "/" . $path;
		}
		
		return parent::write(strtolower($path));
	}
}