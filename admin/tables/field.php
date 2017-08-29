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
 * JDeveloper Field Table
 *
 * @package     JDeveloper
 * @subpackage  Tables
 */
class JDeveloperTableField extends JTable
{
	public function __construct($db)
	{
		parent::__construct('#__jdeveloper_fields', 'id', $db);
	}
	
	public function bind($array, $ignore = '')
	{
		if (isset($array['name']))
		{
			$array['name'] = str_replace(' ', '_', strtolower($array['name']));
			$array['name'] = str_replace('-', '', strtolower($array['name']));
		}
		
		// If this table has a column named 'options', save all param fields as JSON string in this column
		if ( isset($array['options']) && is_array($array['options']) )
		{
			ksort($array["options"]);
			
			$options = array("keys" => array(), "values" => array());
			
			for ($i = 0; $i < count($array["options"]["keys"]); $i++)
			{
				if ($array["options"]["keys"][$i] != "")
				{
					$options["keys"][] = $array["options"]["keys"][$i];
					$options["values"][] = $array["options"]["values"][$i];
				}
			}
			
			$registry = new JRegistry();
			$registry->loadArray($options);
			$array['options'] = (string) $registry;
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
	
	public function check() {
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
			$this->created_by = $user->id;

			// Verify that the name is unique
			$table = JTable::getInstance('Field', 'JDeveloperTable');
			while ($table->load(array('name' => $this->name, 'table' => $this->table, 'created_by' => $user->id)))
			{
				$this->name = JString::increment($this->name);
			}
		}

		return parent::store($updateNulls);
	}
}