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
 * JDeveloper Plugin Table
 *
 * @package     JDeveloper
 * @subpackage  Tables
 */
class JDeveloperTablePlugin extends JTable
{
	public $jfields;
	
	public function __construct($db)
	{
		parent::__construct('#__jdeveloper_plugins', 'id', $db);
	}
	
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
	
	public function check()
	{
        //If there is an ordering column and this is a new row then get the next ordering value
        if (property_exists($this, 'ordering') && $this->id == 0) {
            $this->ordering = self::getNextOrder();
        }

        return parent::check();
    }

	/**
	 * Overriden JTable::store to set modified data.
	 *
	 * @param   boolean	True to update fields even if they are null.
	 * @return  boolean  True on success.
	 * @since   1.6
	 */
	public function store($updateNulls = false)
	{
		$user = JFactory::getUser();

		if (!$this->id)
		{
			$this->created_by = JFactory::getUser()->get('id');

			// Verify that the name is unique
			$table = JTable::getInstance('Plugin', 'JDeveloperTable');
			while ($table->load(array('name' => $this->name, 'created_by' => $user->id)))
			{
				$this->name = JString::increment($this->name);
			}
		}

		return parent::store($updateNulls);
	}
}