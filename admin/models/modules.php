<?php
/**
 * @package     JDeveloper
 * @subpackage  Models
 *
 * @copyright  	Copyright (C) 2014, Tilo-Lars Flasche. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;
require_once JDeveloperLIB . "/install.php";

/**
 * JDeveloper Modules Model
 *
 * @package     JDeveloper
 * @subpackage  Models
 */
class JDeveloperModelModules extends JModelList
{
	/**
	 * Constructor
	 *
	 * @param	array	$config		The Configuration
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array('name','a.name','ordering','a.ordering','id','a.id');
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
		$app = JFactory::getApplication();
		
		$search = $this->getUserStateFromRequest($this->context.'filter.search', 'filter_search', '', 'string');
		$this->setState('filter.search', $search);
		
		$authorId = $app->getUserStateFromRequest($this->context . '.filter.author_id', 'filter_author_id');
		$this->setState('filter.author_id', $authorId);

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
		$id .= ':' . $this->getState('filter.author_id');
		
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
		JDeveloperLoader::import("archive");
		$extension = JTable::getInstance("Extension", "JTable");
		
		// Get a storage key.
		$store = $this->getStoreId('getItems');
		
		// Load the list items.
		$items = parent::getItems();

		// Add information
		foreach ($items as $item)
		{
			// Is Component already installed?
			$item->createDir = JDeveloperArchive::getArchiveDir() . "/" . JDeveloperArchive::getArchiveName("mod_", $item->name, $item->version);
			$item->installed = JDeveloperInstall::isInstalled("module", "mod_" . $item->name);
			
			if ($item->installed)
			{
				$extension->load(array("name" => "mod_" . $item->name, "type" => "module"));
				$item->enabled = (bool) $extension->enabled;
			}
			else
			{
				$item->enabled = false;
			}
		}

		// If emtpy or an error, just return.
		if (empty($items))
		{
			return array();
		}
				
		// Add the items to the internal cache.
		$this->cache[$store] = $items;
		return $this->cache[$store];
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
		$query->select('a.*')->from('#__jdeveloper_modules AS a');
		$search = $this->getState('filter.search');
		
		// Join over the users for the author.
		$query->select('ua.name AS author_name')
			->join('LEFT', '#__users AS ua ON ua.id = a.created_by');

		// Filter by search
		$search = $this->getState('filter.search');
		$s = $db->quote('%'.$db->escape($search, true).'%');
		
		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0)
			{
				$query->where('a.id = ' . (int) substr($search, strlen('id:')));
			}
			elseif (stripos($search, 'name:') === 0)
			{
				$search = $db->quote('%' . $db->escape(substr($search, strlen('name:')), true) . '%');
				$query->where('(a.name LIKE ' . $search);
			}
			elseif (stripos($search, 'author:') === 0)
			{
				$search = $db->quote('%' . $db->escape(substr($search, 7), true) . '%');
				$query->where('(ua.name LIKE ' . $search . ' OR ua.username LIKE ' . $search . ')');
			}
			else
			{
				$search = $db->quote('%' . $db->escape($search, true) . '%');
				$query->where('(a.name LIKE ' . $search . ')');
			}
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

		$sort = $this->getState('list.ordering', 'a.id');
		$order = $this->getState('list.direction', 'ASC');
		$query->order($db->escape($sort).' '.$db->escape($order));

		return $query;
	}
	
	/**
	 * Build a list of authors
	 *
	 * @return  array
	 *
	 * @since   1.6
	 */
	public function getAuthors()
	{
		// Create a new query object.
		$db = $this->getDbo();
		$query = $db->getQuery(true);

		// Construct the query
		$query->select('u.id AS value, u.name AS text')
			->from('#__users AS u')
			->join('INNER', '#__jdeveloper_modules AS a ON a.created_by = u.id')
			->group('u.id, u.name')
			->order('u.name');

		// Setup the query
		$db->setQuery($query);

		// Return the result
		return $db->loadObjectList();
	}
}