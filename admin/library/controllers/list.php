<?php
/**
 * @package     JDeveloper
 * @subpackage  Controllers
 *
 * @copyright  	Copyright (C) 2014, Tilo-Lars Flasche. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

/**
 * JDeveloper List Controller
 *
 * @package     JDeveloper
 * @subpackage  Controllers
 */
class JDeveloperControllerList extends JControllerAdmin
{
	/**
	 * Name of item model
	 * 
	 * @var string
	 */
	protected $model_item = "";
	
	/**
	 * Constructor
	 * 
	 * @param array $config		Configuration
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);
		
		// Create model item name
		if (empty($this->model_item)) {
			$this->model_item = substr($this->view_list, 0, strlen($this->view_list) - 1);
		}
	}
	
	/**
	 * Method to get a model object, loading it if required.
	 *
	 * @param   string  $name    The model name. Optional.
	 * @param   string  $prefix  The class prefix. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return  object  The model.
	 *
	 * @since   12.2
	 */
	public function getModel($name = "", $prefix = "JDeveloperModel", $config = array())
	{
		if (empty($name)) {
			$name = $this->model_item;
		}
		
		$config['ignore_request'] = true;
		$model = parent::getModel($name, $prefix, $config);
		return $model;
	}
}