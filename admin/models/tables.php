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
 * JDeveloper Tables Model
 *
 * @package     JDeveloper
 * @subpackage  Models
 */
class JDeveloperModelTables extends JModelList
{
	/**
	 * Component tables
	 *
	 * @var	array<array[int com_id => object table]>
	 */
	private static $tables = array();

	/**
	 * Constructor
	 *
	 * @param	array	$config		The Configuration
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array('a.name', 'a.component', 'a.id', 'a.ordering');
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
		
		$componentId = $this->getUserStateFromRequest($this->context.'.filter.component', 'filter_component', '');
		$this->setState('filter.component', $componentId);
		
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
	protected function getStoreID($id = '')
	{
		$id .= ':' . $this->getState('filter.search');
		$id .= ':' . $this->getState('filter.category_id');
		
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
		JDeveloperLoader::import("create");
		
		// Get a storage key.
		$store = $this->getStoreId('getItems');
		
		// Load the list items.
		$items = parent::getItems();

		// If emtpy or an error, just return.
		if (empty($items))
		{
			return array();
		}
		
		JDeveloperLoader::import("create");
		
		// Get the params as an array
		foreach ($items as $item)
		{
			$app = JFactory::getApplication();
			$db = JFactory::getDbo();
			
			$db->setQuery("SELECT COUNT(*) FROM `#__jdeveloper_fields` AS f WHERE f.table = " . $item->id);
			$item->numberOfFields = $db->loadResult();
			
			$db->setQuery("SELECT f.name FROM #__jdeveloper_fields AS f WHERE f.table = ".$item->id." ORDER BY f.ordering");
			$item->mainfield = $db->loadResult();
			
			$formfile = JDeveloperARCHIVE . "/tables/table_" . $item->id . ".xml";
			$item->formfile = (JFile::exists($formfile)) ? JDeveloperARCHIVEURL . "/tables/table_" . $item->id . ".xml" : "";
			
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
	 * @param  int	$com_id	The table id
	 *
	 * @return	array	The fields
	 */
	public function getComponentTables($com_id)
	{
		if (!isset(self::$tables[$com_id]))
		{
			$db = JFactory::getDbo();
			$model = JModelLegacy::getInstance('Table', 'JDeveloperModel');
			$user = JFactory::getUser();
			$query = $db->getQuery(true)->select("a.id, a.created_by, a.component")->from("#__jdeveloper_tables AS a")->where("a.component = " . $db->quote($com_id));

			if (!$user->authorise('core.admin', 'com_jdeveloper'))
			{
				$query->where('a.created_by = ' . $user->get('id'));
			}

			$tableIDs = $db->setQuery($query)->loadAssocList('id');
			$tables = array();

			foreach ($tableIDs as $id) {
				$tables[] = $model->getItem($id);
			}
			
			self::$tables[$com_id] = $tables;
		}
		
		return self::$tables[$com_id];
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
		$query->select('a.*')->from('#__jdeveloper_tables AS a');
		
		//Join over components
		$query->select('c.display_name AS ' . $db->quote('component'))
			->join('LEFT', '#__jdeveloper_components AS c ON c.id = a.component');
				
		// Join over the users for the author.
		$query->select('ua.name AS author_name')
			->join('LEFT', '#__users AS ua ON ua.id = a.created_by');

		// Filter by component.
		$componentId = $this->getState('filter.component');
		if (is_numeric($componentId) && !empty($componentId))
		{
			$query->where('a.component = ' . (int) $componentId);
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

		// Filter by search
		$search = $this->getState('filter.search');
		if (!empty($search))
		{
			$s = $db->quote('%'.$db->escape($search, true).'%');
			$query->where('a.name LIKE' . $s);
		}
				
		// Add the list ordering clause.
		$sort = $this->getState('list.ordering', 'a.id');
		$order = $this->getState('list.direction', 'ASC');
		$query->order($db->escape($sort).' '.$db->escape($order));
		
		return $query;
	}
}