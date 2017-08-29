<?php
/**
 * @package     JDeveloper
 * @subpackage  Models
 *
 * @copyright  	Copyright (C) 2014, Tilo-Lars Flasche. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

/**
 * JDeveloper Overrides Model
 *
 * @package     JDeveloper
 * @subpackage  Models
 */
class JDeveloperModelOverrides extends JModelList
{
	/*
	 * Constructor.
	 *
	 * @param   array  An optional associative array of configuration settings.
	 * @see     JController
	 * @since   1.6
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id', 'a.id',
				'type', 'a.type',
				'name', 'a.name'
			);
		}

		parent::__construct($config);
	}

	/**
	 * Stock method to auto-populate the model state.
	 *
	 * @return  void
	 *
	 * @since   12.2
	 */
	protected function populateState($ordering = 'name', $direction = 'ASC')
	{
		$search = $this->getUserStateFromRequest($this->context.'filter.search', 'filter_search', '', 'string');
		$this->setState('filter.search', $search);
		
		$type = $this->getUserStateFromRequest($this->context.'filter.type', 'filter_type', '', 'string');
		$this->setState('filter.type', $type);
		
		$name = $this->getUserStateFromRequest($this->context.'filter.name', 'filter_name', '', 'string');
		$this->setState('filter.name', $name);
		
		$item_id = $this->getUserStateFromRequest($this->context.'filter.item_id', 'filter_item_id', '', 'string');
		$this->setState('filter.item_id', $item_id);
		
		parent::populateState($ordering, $direction);
	}

	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param   string  $id    A prefix for the store id.
	 *
	 * @return  string  A store id.
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id .= ':' . $this->getState('filter.search');
		$id .= ':' . $this->getState('filter.type');
		$id .= ':' . $this->getState('filter.name');
		$id .= ':' . $this->getState('filter.item_id');

		return parent::getStoreId($id);
	}
	
	/**
	 * Method to get an array of data items.
	 *
	 * @return  array  An array of data items
	 *
	 * @since   12.2
	 */
	public function getItems()
	{
		// Get a storage key.
		$store = $this->getStoreId('getItems');
		
		// Load the list items.
		$items = parent::getItems();

		// If emtpy or an error, just return.
		if (empty($items))
		{
			return array();
		}
		
		// Get the params as an array
		foreach ($items as $item)
		{
			// Edit items
		}
		
		// Add the items to the internal cache.
		$this->cache[$store] = $items;
		return $this->cache[$store];
	}

	/**
	 * Method to get the overrides of an extension template.
	 *
	 * @param	string	$type		The extension type (component, module, plugin, template)
	 * @param	int		$item_id	The extension id (primary key of table shere item is stored)
	 *
	 * @return  array  The overrides
	 */
	public function getOverrides($type, $item_id)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		
		$query->select("a.id, a.name")->from("#__jdeveloper_overrides AS a")
			->where("a.item_id = " . $db->quote($item_id))
			->where("a.type = " . $db->quote($type));
		
		$result = $db->setQuery($query)->loadObjectList();
		
		return $result;
	}
	
	/**
	 * Method to get a JDatabaseQuery object for retrieving the data set from a database.
	 *
	 * @return  JDatabaseQuery   A JDatabaseQuery object to retrieve the data set.
	 *
	 * @since   12.2
	 */
	protected function getListQuery()
	{
		$db = $this->getDbo();

		$query = $db->getQuery(true);
		$query->select('a.*')->from($db->quoteName('#__jdeveloper_overrides') . 'AS a');
		
		// Filter by type.
		$type = $this->getState('filter.type');
		if (!empty($type))
		{
			$query->where('a.type = ' . $db->quote($type));
		}
		
		// Filter by name.
		$name = $this->getState('filter.name');
		if (!empty($name))
		{
			$query->where('a.name = ' . $db->quote($name));
		}
		
		// Filter by item_id.
		$item_id = $this->getState('filter.item_id');
		if (is_numeric($item_id) && !empty($item_id))
		{
			$query->where('a.item_id = ' . (int) $item_id);
		}
		
		// Filter by search
		$search = $this->getState('filter.search');
		if (!empty($search))
		{
			$search = $db->quote('%' . $db->escape($search, true) . '%');
			$query->where('(a.name LIKE ' . $search . ')');
		}

		$sort = $this->getState('list.ordering', 'a.id');
		$order = $this->getState('list.direction', 'ASC');
		$query->order($db->escape($sort).' '.$db->escape($order));

		return $query;
	}
}