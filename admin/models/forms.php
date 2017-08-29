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
 * JDeveloper Forms Model
 *
 * @package     JDeveloper
 * @subpackage  Models
 */
class JDeveloperModelForms extends JModelList
{
	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'a.name', 'name',
				'a.alias', 'alias',
				'a.created_by', 'created_by', 'author_id',
				'a.ordering', 'ordering',
				'lft', 'a.lft',
				'rgt', 'a.rgt',
				'level', 'a.level',
				'path', 'a.path','ordering', 'state', 'name', 'type', 'label', 'description', 'default', 'maxlength', 'validation'
			);
		}
		parent::__construct($config);
	}
	
	/**
	 * Method to auto-populate the model state.
	 *
	 * This method should only be called once per instantiation and is designed
	 * to be called on the first call to the getState() method unless the model
	 * configuration flag to ignore the request is set.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param   string  $ordering   An optional ordering field.
	 * @param   string  $direction  An optional direction (asc|desc).
	 *
	 * @return  void
	 */
	protected function populateState($ordering = 'name', $direction = 'ASC')
	{
		// Get the Application
		$app = JFactory::getApplication();
		
		// Set filter state for search
        $search = $app->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);

		// Set filter state for tree level
		$level = $this->getUserStateFromRequest($this->context . '.filter.level', 'filter_level');
		$this->setState('filter.level', $level);

		// Set filter state for tree level
		$relation = $this->getUserStateFromRequest($this->context . '.filter.relation', 'filter_relation');
		$this->setState('filter.relation', $relation);

		// Set filter state for author
		$authorId = $app->getUserStateFromRequest($this->context . '.filter.author_id', 'filter_author_id');
		$this->setState('filter.author_id', $authorId);

		// Load the parameters.
		$params = JComponentHelper::getParams('com_jdeveloper');
		$this->setState('params', $params);

		// List state information.
		parent::populateState($ordering, $direction);
	}
	
	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param   string  $id  A prefix for the store id.
	 *
	 * @return  string  A store id.
	 *
	 * @since   1.6
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id .= ':' . $this->getState('filter.search');
		$id .= ':' . $this->getState('filter.parent_id');
		$id .= ':' . $this->getState('filter.author_id');

		return parent::getStoreId($id);
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return  JDatabaseQuery
	 */
	protected function getListQuery()
	{
		// Get database object
		$app = JFactory::getApplication();
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select('a.*')->from('#__jdeveloper_forms AS a');				
		
		// Join over the users for the author.
		$query->select('ua.name AS author_name')
			->join('LEFT', '#__users AS ua ON ua.id = a.created_by');

		// Exclude the root category.
		$query->where('a.id > 1');
		
		// Only get children of item
		$parent_id = $app->input->get('parent_id', 0);
		
		if ($this->getState("parent_id", 0)) {
			$parent_id = $this->getState("parent_id");
		}
		
		if (is_numeric($parent_id) && !empty($parent_id))
		{
			$parent = JModelLegacy::getInstance("Form", "JDeveloperModel")->getItem($parent_id);
			$query->where('a.lft > ' . $db->escape($parent->lft) . ' AND a.rgt < ' . $db->escape($parent->rgt) . ' OR a.id = ' . $parent->id);
		}
		else
		{
			$query->where('a.level = 1');
		}

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
				$query->where('a.name LIKE' . $s . ' OR a.type LIKE' . $s . ' OR a.label LIKE' . $s . ' OR a.description LIKE' . $s . ' OR a.default LIKE' . $s . ' OR a.maxlength LIKE' . $s . ' OR a.validation LIKE' . $s );
			}
		}

		// Filter by relation.
		$relation = $this->getState("filter.relation", "none");
		if ($relation == "none")
		{
			$query->where("a.relation = ''");
		}
		elseif ($relation != "")
		{
			$query->where("a.relation = '" . $relation . "'");
		}
		
		// Filter on the level.
		if ($level = $this->getState('filter.level'))
		{
			$query->where('a.level <= ' . (int) $level);
		}
		
		// Filter by author
		$authorId = $this->getState('filter.author_id');
		if (is_numeric($authorId))
		{
			$type = $this->getState('filter.author_id.include', true) ? '= ' : '<>';
			$query->where('a.created_by ' . $type . (int) $authorId);
		}

		// Add list oredring and list direction to SQL query
		$sort = $this->getState('list.ordering', 'a.lft');
		$order = $this->getState('list.direction', 'ASC');
		$query->order($db->escape('a.lft').' '.$db->escape($order));
		
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
			->join('INNER', '#__jdeveloper_forms AS a ON a.created_by = u.id')
			->group('u.id, u.name')
			->order('u.name');

		// Setup the query
		$db->setQuery($query);

		// Return the result
		return $db->loadObjectList();
	}
	
	/**
	 * Method to get an array of data items.
	 *
	 * @return  mixed  An array of data items on success, false on failure.
	 *
	 * @since   12.2
	 */
	public function getItems()
	{
		if ($items = parent::getItems()) {
			//Do any procesing on fields here if needed
		}

		return $items;
	}
}
?>