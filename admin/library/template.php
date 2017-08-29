<?php
/**
 * @package     JDeveloper
 * @subpackage  Library
 *
 * @copyright  	Copyright (C) 2014, Tilo-Lars Flasche. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;
jimport('joomla.filesystem.file');

/**
 * Class for working with template files
 *
 * @package     JDeveloper
 * @subpackage  Library
 */
class JDeveloperTemplate
{
	/*
	 * The areas
	 *
	 * @var array<string => boolean>
	 */
	private $areas = array();
	
	/*
	 * The replaced content
	 *
	 * @var string
	 */
	private $buffer = '';
		
	/*
	 * The template path
	 *
	 * @var string
	 */
	public $path = '';
	
	/*
	 * The template file content
	 *
	 * @var string
	 */
	private $template = '';
	
	/*
	 * The placeholders
	 *
	 * @var	array (String => String)
	 */
	private $placeholders = array();
	
	/*
	 * The errors
	 *
	 * @var	array<string>
	 */
	protected $_errors = array();
	
	/**
	 * Constructor
	 */
	public function __construct($source = '', $isFile = true)
	{		
		if ($isFile && empty($source))
		{
			$this->setError('Empty template path given');
		}
		
		if (!$isFile)
		{
			$this->template = $source;
			return $this;
		}
				
		if (!empty($source) && JFile::exists($source))
		{
			$this->template = JFile::read($source);
			$this->path = $source;
		}
		else
		{
			throw new JDeveloperException("Template <i>'$source'</i> not found");
		}
	}
	
	/*
	 * Add placehoders
	 *
	 * @param	array	$placeholders	The placeholders to look for and the values they will be replaced with
	 * @param	boolean	$case			Case sensivity
	 */
	public function addPlaceholders($placeholders = array(), $case = false)
	{
		foreach($placeholders as $key => $value)
		{
			if ($case)
			{
				$this->placeholders[ucfirst($key)] 	  = ucfirst($value);
				$this->placeholders[strtoupper($key)] = strtoupper($value);
				$this->placeholders[strtolower($key)] = strtolower($value);
				
				continue;
			}
			
			$this->placeholders[$key] = $value;
		}
	}
	
	/*
	 * Add areas
	 *
	 * @param	array	$areas	The areas to look for
	 */
	public function addAreas($areas = array())
	{
		foreach($areas as $area => $bool)
		{
			$this->areas[$area] = $bool;
		}
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
	
	/*
	 * Get all language keys from the template which match the given patterns
	 *
	 * @param	array	$patterns	The patterns
	 *
	 * @return	array	The matched elements
	 */
	public function getLanguageKeys($patterns = array())
	{
		$langkeys = array();
		
		foreach ($patterns as $pattern)
		{
			preg_match_all("/" . $pattern . "/", $this->buffer, $matches);
			$langkeys = array_merge($langkeys, $matches[0]);
		}
		
		return $langkeys;
	}
	
	/**
	 * Find all unattended areas in the buffer
	 *
	 * @return	array	The unattended areas
	 */
	public function getUnattendedAreas()
	{
		$areas = array();
		preg_match_all("/##\{start_([A-Za-z0-9_-]*)\}##/", $this->buffer, $matches);
		
		foreach ($matches[1] as $match)
		{
			if (!in_array($match, $areas))
			{
				$areas[] = $match;
			}
		}
		
		return $areas;
	}
		
	/**
	 * Find all unattended areas in the buffer
	 *
	 * @return	array	The unattended areas
	 */
	public function getUnattendedPlaceholders()
	{
		$areas = array();
		preg_match_all("/##([A-Za-z0-9_-]*)##/", $this->buffer, $matches);
		
		foreach ($matches[1] as $match)
		{
			if (!in_array($match, $areas))
			{
				$areas[] = $match;
			}
		}
		
		return $areas;
	}
		
	/**
	 * Replace the placeholders of the template by the given rules
	 * 
	 * @return	The replaced template
	 */
	public function getBuffer()
	{		
		$this->buffer = $this->template;

		/** Render areas **/
		foreach ($this->areas as $area => $bool)
		{
			if ($bool)
			{
				// Delete only start- and end tag
				$this->buffer = str_replace("##{start_".$area."}##", "", $this->buffer);
				$this->buffer = str_replace("##{end_".$area."}##", "", $this->buffer);
				
				while ( strpos($this->buffer, "##{!start_".$area."}##") !== false )
				{
					// Delete content between start- and end tag
					$start_pos = strpos($this->buffer, "##{!start_".$area."}##");
					$end_pos = strpos($this->buffer, "##{!end_".$area."}##");
					$this->buffer = substr_replace($this->buffer, '', $start_pos, $end_pos - $start_pos + strlen("##{!end_".$area."}##"));
				}
			}
			else
			{
				// Delete only start- and end tag
				$this->buffer = str_replace("##{!start_".$area."}##", "", $this->buffer);
				$this->buffer = str_replace("##{!end_".$area."}##", "", $this->buffer);
				
				while ( strpos($this->buffer, "##{start_".$area."}##") !== false )
				{
					// Delete content between start- and end tag
					$start_pos = strpos($this->buffer, "##{start_".$area."}##");
					$end_pos = strpos($this->buffer, "##{end_".$area."}##");
					$this->buffer = substr_replace($this->buffer, '', $start_pos, $end_pos - $start_pos + strlen("##{end_".$area."}##"));
				}
			}
		}
		
		/** Render placeholders **/
		foreach($this->placeholders as $key => $value)
		{
			$this->buffer = str_replace('##'.$key.'##', $value, $this->buffer);
		}
				
		return $this->buffer;
	}

	/**
	 * Set error
	 */
	public function setError($text)
	{
		$this->_errors[] = $text;
	}
}