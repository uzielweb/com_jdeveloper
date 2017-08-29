<?php
/**
 * @package     JDeveloper
 * @subpackage  Tables
 *
 * @copyright  	Copyright (C) 2014, Tilo-Lars Flasche. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

/**
 * JDeveloper Component Table
 *
 * @package     JDeveloper
 * @subpackage  Tables
 */
class JDeveloperTableComponent extends JTable
{
	public function __construct($db)
	{
		parent::__construct('#__jdeveloper_components', 'id', $db);
	}
	
	/**
	 * Method to bind an associative array or object to the JTable instance.
	 *
	 * @param   mixed  $src     An associative array or object to bind to the JTable instance.
	 * @param   mixed  $ignore  An optional array or space separated list of properties to ignore while binding.
	 *
	 * @return  boolean  True on success.
	 *
	 * @throws  InvalidArgumentException
	 */
	public function bind($array, $ignore = '')
	{
		if (isset($array['name']))
		{
			$array['name'] = str_replace(' ', '_', strtolower($array['name']));
		}
		
		if (isset($array['params']) && is_array($array['params']))
		{
			ksort($array["params"]);
			$registry = new JRegistry();
			$registry->loadArray($array['params']);
			$array['params'] = (string) $registry;
		}
		
		return parent::bind($array, $ignore);
	}
	
	/**
	 * Method to perform sanity checks on the JTable instance properties to ensure
	 * they are safe to store in the database.
	 *
	 * @return  boolean  True if the instance is sane and able to be stored in the database.
	 */
	public function check()
	{
        return parent::check();
    }

	/**
	 * Method to store a row in the database from the JTable instance properties.
	 *
	 * @param   boolean  $updateNulls  True to update fields even if they are null.
	 *
	 * @return  boolean  True on success.
	 */
	public function store($updateNulls = false)
	{
		$user = JFactory::getUser();
		
		if (!$this->id)
		{
			$this->created_by = $user->id;

			// Verify that the name is unique
			$table = JTable::getInstance('Component', 'JDeveloperTable');
			while ($table->load(array('name' => $this->name, 'created_by' => $user->id)))
			{
				$this->name = JString::increment($this->name);
			}
		}

		return parent::store($updateNulls);
	}
}