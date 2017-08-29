<?php
/**
 * @package     JDeveloper
 * @subpackage  Library
 *
 * @copyright  	Copyright (C) 2014, Tilo-Lars Flasche. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;
JDeveloperLoader::import("language");
JDeveloperLoader::import("template");

/**
 * Class for working with templates
 *
 * @package     JDeveloper
 * @subpackage  Library
 */
abstract class JDeveloperCreate
{	
	/**
	 * The configuration
	 *
	 * @var	array
	 */
	protected $config = array();
		
	/**
	 * The template object
	 *
	 * @var	JDeveloperTemplate
	 */
	protected $template = null;
		
	/**
	 * The template base paths
	 *
	 * @var	array
	 */
	protected $templateDirs = array();
		
	/**
	 * The template file path
	 *
	 * @var	string
	 */
	protected $templateFile = "";
		
	/**
	 * The extensions type (important for overrides)
	 *
	 * @var	string
	 */
	protected $type = "";
		
	/**
	 * The errors
	 *
	 * @var	array<string>
	 */
	protected $_errors = array();
	
	/**
	 * Registered models
	 *
	 * @var array<JModelLegacy>
	 */
	protected $_models = array();

	/**
	 * The class name
	 *
	 * @var	string
	 */
	protected $_name = "";
	
	/**
	 * Constructor
	 *
	 * @param	array	$config		The configuration
	 */
	public function __construct($config = array())
	{
		$this->config = $config;
		$this->_name = get_class($this);
	}
		
	/**
	 * Method to get certain otherwise inaccessible properties from the create object.
	 *
	 * @param   string  $name  The property name for which to the the value.
	 *
	 * @return  mixed  The property value or null.
	 */
	public function __get($name)
	{
		switch ($name)
		{			
			case 'templateFile':
				return $this->$name;
		}

		return null;
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
	 * Get the default areas for the template
	 *
	 * @return	array<string area => boolean keep/delete>
	 */
	protected function getDefaultAreas() {
		return array();
	}
	
	/**
	 * Get default placeholders for the template
	 *
	 * @return	array<string placeholder => string replacement>
	 */
	protected function getDefaultPlaceholders()
	{
		$params = JComponentHelper::getParams('com_jdeveloper');

		return array(
			'author' 		=> $params->get('author'),
			'author_email' 	=> $params->get('email'),
			'author_url' 	=> $params->get('website'),
			'copyright' 	=> $params->get('copyright'),
			'creationdate' 	=> date("Y") . ucfirst(date(" F ")) . date("d"),
			'license' 		=> $params->get('license'),
		);
	}
	
	/**
	 * Get the handled template
	 *
	 * @return	mixed	The rendered template buffer or false
	 */
	public function getBuffer()
	{
		$buffer = $this->initialize()->template->getBuffer();
		$areas = $this->template->getUnattendedAreas();
		$placeholders = $this->template->getUnattendedPlaceholders();
		
		// Check for placeholders and areas which have not been catched
		if (count($areas))
		{
			$this->setError("The following areas have not been handled in <b>$this->templateFile</b>: " . implode(", ", $areas));
		}
		if (count($placeholders))
		{
			$this->setError("The following placeholders have not been handled in <b>$this->templateFile</b>: " . implode(", ", $placeholders));
		}

		return $buffer;
	}
	
	/**
	 * Get the edited template of this create instance
	 *
	 * @return	string	The errors
	 */
	public function getErrors()
	{
		return implode("<br>\n", $this->_errors);
	}
	
	/**
	 * Get an JDeveloperCreate object
	 *
	 * @param	string	$name	The class name
	 * @param	array	$config	The configuration data
	 *
	 * @return	mixed	instance of JDeveloperCreate on success, false otherwise
	 */
	public static function getInstance($name, $config = array())
	{
		$class = "JDeveloperCreate" . JDeveloperLoader::getClassname($name);
		
		if (!class_exists($class))
		{
			JDeveloperLoader::import($name, JDeveloperCREATE);
		}
		
		if (class_exists($class))
		{
			return new $class($config);
		}
		else
		{
			throw new JDeveloperException("Could not find class <i>$class</i>");
		}
	}

	/**
	 * Get the language object
	 *
	 * @param	string	$name	The instance name
	 *
	 * @return	JDeveloperLanguage
	 */
	protected function getLanguage($name = "")
	{
		return null;
	}
	
	/**
	 * Method to get the model object
	 *
	 * @param   string  $name  The name of the model
	 *
	 * @return  JModelLegacy object on success, false otherwise
	 */
	protected function getModel($name, $prefix = 'JDeveloperModel')
	{
		if (!isset($this->_models[strtolower($name)]))
		{
			$model = JModelLegacy::getInstance($name, $prefix);
			
			if (!is_object($model))
			{
				$this->setError($this->name . ": Error while loading model <i>$name, $prefix </i>");
			}
			
			$this->setModel($model);
		}
		
		return $this->_models[strtolower($name)];
	}
	
	/**
	 * Get the template object
	 *
	 * @return	JDeveloperTemplate
	 */
	protected function getTemplate()
	{
		if (null != $override = $this->loadOverride())
		{
			return new JDeveloperTemplate($override->source, false);
		}
		
		if ($this->template instanceof JDeveloperTemplate)
		{
			return $this->template;
		}
		
		if (is_array($this->templateDirs) && !empty($this->templateDirs))
		{			
			foreach ($this->templateDirs as $templateDir)
			{
				$path = JDeveloperPath::dots2ds($templateDir . "/" . $this->templateFile);

				if ($path !== false)
				{
					return new JDeveloperTemplate($path);
				}
			}

			return false;
		}
		else
		{
			$this->setError($this->_name . ": No template base directions given.");
			return false;
		}
	}
	
	/**
	 * Initialize the template
	 *
	 * @return	JDeveloperCreate	this
	 */
	protected function initialize()
	{
		// standart placeholders
		$this->template->addAreas($this->getDefaultAreas());
		$this->template->addPlaceHolders($this->getDefaultPlaceholders(), true);
		
		return $this;
	}
	
	/**
	 * Load override
	 *
	 * @param	string	$type		The extension type (component, module, plugin, template)
	 * @param	int		$item_id	The extension id (primary key of table shere item is stored)
	 * @param	string	$name		The template name
	 *
	 * @return	JDeveloperTemplate		The subtemplate object
	 */
	protected function loadOverride($type = "", $item_id = "", $name = "")
	{
		return JModelLegacy::getInstance("Override", "JDeveloperModel")->getOverride($type, $item_id, $name);
	}

	/**
	 * Load subtemplate
	 *
	 * @param	string	$filename	The name of the subtemplate
	 * @param	string	$sep		The seperator
	 *
	 * @return	JDeveloperTemplate		The subtemplate object
	 */
	protected function loadSubtemplate($filename, $sep = '.')
	{
		$templateFile = JFile::stripExt($this->templateFile) . $sep . $filename;
		
		foreach ($this->templateDirs as $templateDir)
		{
			$dir = JDeveloperPath::dots2ds($templateDir . "/" . $templateFile);
			
			if ($dir !== false)
			{
				return new JDeveloperTemplate($dir);
			}
		}
		
		return false;
	}
	
	/**
	 * Set error
	 */
	public function setError($text)
	{
		$this->_errors[] = $text;
	}

	/**
	 * Method to add a model to the create object.
	 *
	 * @param   JModelLegacy  $model    The model to add.
	 *
	 * @return  object   The added model.
	 */
	protected function setModel($model)
	{
		$name = strtolower($model->getName());
		$this->_models[$name] = $model;

		return $this->_models[$name];
	}
	
	/**
	 * Method to set the template object.
	 *
	 * @param   JDeveloperTemplate  $template    The template to add.
	 *
	 * @return  object   The template.
	 */
	protected function setTemplate($template)
	{
		if (!$template instanceof JDeveloperTemplate)
		{
			throw new JDeveloperException($this->name . ": Template must be instance of JDeveloperTemplate");
		}

		$this->template = $template;
		return $this->template;
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
		
		if (empty($path)) 
		{
			$this->setError($this->_name . " : Empty write path given");
			return false;
		}
		
		$buffer = $this->getBuffer();
		
		if (empty($buffer))
		{
			$this->setError($this->_name . ": No file content given");
			return false;
		}
				
		if (!JFile::write($path, $buffer, true))
		{
			$this->setError($this->_name . ": Error while creating file <i>$path</i>");
			return false;
		}
		
		return true;
	}
}