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
 * JDeveloper Form Table
 *
 * @package     JDeveloper
 * @subpackage  Tables
 */
class JDeveloperTableForm extends JTableNested
{
	/**
	 * Constructor
	 *
	 * @param   JDatabaseDriver  &$db  A database connector object
	 */
	public function __construct(&$db)
	{
		parent::__construct('#__jdeveloper_forms', 'id', $db);
	}

	/**
     * Overloaded check function
     */
    public function check()
	{
        //If there is an ordering column and this is a new row then get the next ordering value
        if (property_exists($this, 'ordering') && $this->id == 0) {
            $this->ordering = self::getNextOrder();
        }

		// Check alias
		if (empty($this->alias))
		{
			$this->alias = $this->name;
		}
		$this->alias = JApplication::stringURLSafe($this->alias);

        return parent::check();
    }

	/**
	 * Method to bind an associative array or object to the JTable instance.
	 *
	 * @see JTable
	 */
	public function bind($array, $ignore = '')
	{
		if (isset($array['name']))
		{
			$array['name'] = str_replace(' ', '_', strtolower($array['name']));
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

		// If this table has a column named 'params', save all param fields as JSON string in this column
		if ( isset($array['params']) && is_array($array['params']) )
		{
			$registry = new JRegistry();
			$registry->loadArray($array['params']);
			$array['params'] = (string) $registry;
		}

		return parent::bind($array, $ignore);
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
		// Transform the params field
		if (is_array($this->params))
		{
			$registry = new JRegistry;
			$registry->loadArray($this->params);
			$this->params = (string) $registry;
		}
		
		$date	= JFactory::getDate();
		$user	= JFactory::getUser();
		
		if ($this->id)
		{
			// Existing item
			
			
		}
		else
		{
			// New item. An item created and created_by field can be set by the user,
			// so we don't touch either of these if they are set.
			
			if (empty($this->created_by))
			{
				$this->created_by = $user->get('id');
			}
		}
		
		// Verify that the alias is unique
		$table = JTable::getInstance('Form', 'JDeveloperTable');
		if ($table->load(array('alias' => $this->alias, 'parent_id' => $this->parent_id)) && ($table->id != $this->id || $this->id == 0))
		{
			$this->setError(JText::_('UNIQUE_ALIAS'));
			return false;
		}
		
		return parent::store($updateNulls);
	}
}
?>