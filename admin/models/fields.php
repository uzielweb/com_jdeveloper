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
 * JDeveloper Fields Model
 *
 * @package     JDeveloper
 * @subpackage  Models
 */
class JDeveloperModelFields extends JModelList
{
	/**
	 * Table fields
	 *
	 * @var	array<array[int table_id => object field]>
	 */
	private static $fields = array();
	
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
				'name', 'a.name',
				'type', 'a.type',
				'label', 'a.label',
				'created_by', 'a.created_by',
				'ordering', 'a.ordering'
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
	protected function populateState($ordering = 'ordering', $direction = 'ASC')
	{
		$search = $this->getUserStateFromRequest($this->context.'filter.search', 'filter_search', '', 'string');
		$this->setState('filter.search', $search);
		
		$componentId = $this->getUserStateFromRequest($this->context.'.filter.table', 'filter_table', '');
		$this->setState('filter.table', $componentId);
		
		$fieldtype = $this->getUserStateFromRequest($this->context.'.filter.fieldtype', 'filter_fieldtype', '');
		$this->setState('filter.fieldtype', $fieldtype);
		
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
		$id .= ':' . $this->getState('filter.access');
		$id .= ':' . $this->getState('filter.state');

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
			$db = JFactory::getDbo();

			$db->setQuery("SELECT c.name AS 'component' FROM `#__jdeveloper_components` AS c WHERE c.id = " . $item->table_id);
			$item->component = $db->loadResult();
			
			$registry = new JRegistry();
			$registry->loadString($item->params);
			$item->params = $registry->toArray();
		}
		
		// Add the items to the internal cache.
		$this->cache[$store] = $items;
		return $this->cache[$store];
	}
	
	/**
	 * Get the fields which belong to a given table id
	 *
	 * @param  int	$table_id	The table id
	 *
	 * @return	array	The fields
	 */
	public function getTableFields($table_id)
	{
		if (!isset(self::$fields[$table_id]))
		{
			$db = JFactory::getDbo();
			$model = JModelLegacy::getInstance('Field', 'JDeveloperModel');
			$user = JFactory::getUser();
			$query = $db->getQuery(true)->select("a.id, a.table, a.name")->from("#__jdeveloper_fields as a")->where("a.table = " . $db->quote($table_id))->order("a.ordering ASC");
		
			if (!$user->authorise('core.admin', 'com_jdeveloper'))
			{
				$query->where('a.created_by = ' . $user->get('id'));
			}

			$fieldIDs = $db->setQuery($query)->loadAssocList("id");
			$fields = array();

			foreach ($fieldIDs as $id) {
				$fields[] = $model->getItem($id);
			}
			
			self::$fields[$table_id] = $fields;
		}
		
		return self::$fields[$table_id];
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
		$query->select('a.*')->from($db->quoteName('#__jdeveloper_fields') . 'AS a');
		
		//Join over tables
		$query->select('t.name AS '. $db->quote('table'))
			->select('t.id AS ' . $db->quote('table_id'))
			->join('LEFT', '#__jdeveloper_tables AS t ON t.id = a.table');

		// Join over the jdeveloper formfields
		$query->select('ff.id AS jdeveloper_formfield_id')
			->join('LEFT', '#__jdeveloper_formfields AS ff ON ff.name = a.type');

		// Join over the jdeveloper formrules
		$query->select('fr.id AS jdeveloper_formrule_id')
			->join('LEFT', '#__jdeveloper_formrules AS fr ON fr.name = a.rule');

		// Join over the users for the author.
		$query->select('ua.name AS author_name')
			->join('LEFT', '#__users AS ua ON ua.id = a.created_by');

		// Filter by table.
		$tableID = $this->getState('filter.table');
		if (is_numeric($tableID) && !empty($tableID))
		{
			$query->where('a.table = ' . (int) $tableID);
		}
		
		// Filter by author
		$authorId = $this->getState('filter.author_id');
		$user = JFactory::getUser();
		
		if (!$user->authorise('core.admin', 'com_jdeveloper'))
		{
			$query->where('a.created_by = ' . $user->get('id'));
		}
		elseif (is_numeric($authorId))
		{
			$type = $this->getState('filter.author_id.include', true) ? '= ' : '<>';
			$query->where('a.created_by ' . $type . (int) $authorId);
		}

		// Filter by fieldtype.
		$fieldtype = $this->getState('filter.fieldtype');
		if (!empty($fieldtype))
		{
			$query->where('a.type = ' . $db->quote($fieldtype));
		}
		
		// Filter by search
		$search = $this->getState('filter.search');
		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0)
			{
				$query->where('a.id = ' . (int) substr($search, strlen("id:")));
			}
			else
			{
				$search = $db->quote('%' . $db->escape($search, true) . '%');
				$query->where('(a.name LIKE ' . $search . ')');
			}
		}

		$sort = $this->getState('list.ordering', 'a.id');
		$order = $this->getState('list.direction', 'ASC');
		$query->order($db->escape($sort).' '.$db->escape($order));

		return $query;
	}
}