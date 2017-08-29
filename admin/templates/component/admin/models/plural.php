<?php##{start_header}##
/**
 * @package     JDeveloper
 * @subpackage  Templates.Component
 *
 * @copyright  	Copyright (C) 2014, Tilo-Lars Flasche. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;##{end_header}##
##Header##

/**
 * List Model for ##plural##.
 *
 * @package     ##Component##
 * @subpackage  Models
 */
class ##Component##Model##Plural## extends JModelList
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
				'a.##mainfield##', '##mainfield##',##{start_alias}##
				'a.alias', 'alias',##{end_alias}####{start_checked_out}##
				'a.checked_out', 'checked_out',
				'a.checked_out_time', 'checked_out_time',##{end_checked_out}####{start_catid}##
				'a.catid', 'catid', 'category_id', 'category_title',##{end_catid}####{start_published}##
				'a.published', 'published',##{end_published}####{start_access}##
				'a.access', 'access', 'access_level',##{end_access}####{start_created}##
				'a.created', 'created',##{end_created}####{start_created_by}##
				'a.created_by', 'created_by', 'author_id',##{end_created_by}####{start_created_by_alias}##
				'a.created_by_alias', 'created_by_alias',##{end_created_by_alias}####{start_ordering}##
				'a.ordering', 'ordering',##{end_ordering}####{start_language}##
				'a.language', 'language',##{end_language}####{start_hits}##
				'a.hits', 'hits',##{end_hits}####{start_publish_up}##
				'a.publish_up', 'publish_up',##{end_publish_up}####{start_publish_down}##
				'a.publish_down', 'publish_down',##{end_publish_down}####{start_table_nested}##
				'lft', 'a.lft',
				'rgt', 'a.rgt',
				'level', 'a.level',
				'path', 'a.path',##{end_table_nested}####{start_tags}##
				'tag',##{end_tags}####filter_fields##
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
	protected function populateState($ordering = '##mainfield##', $direction = 'ASC')
	{
		// Get the Application
		$app = JFactory::getApplication();##{start_site}##
		$menu = $app->getMenu();##{end_site}##
		
		// Set filter state for search
        $search = $app->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);##{start_table_nested}##

		// Set filter state for tree level
		$level = $this->getUserStateFromRequest($this->context . '.filter.level', 'filter_level');
		$this->setState('filter.level', $level);##{end_table_nested}####{start_access}##

		// Set filter state for access
		$accessId = $this->getUserStateFromRequest($this->context . '.filter.access', 'filter_access', null, 'int');
		$this->setState('filter.access', $accessId);##{end_access}####{start_created_by}##

		// Set filter state for author
		$authorId = $app->getUserStateFromRequest($this->context . '.filter.author_id', 'filter_author_id');
		$this->setState('filter.author_id', $authorId);##{end_created_by}####{start_published}##

		// Set filter state for publish state
        $published = $app->getUserStateFromRequest($this->context . '.filter.published', 'filter_published', '', 'string');
        $this->setState('filter.published', $published);##{end_published}####{start_catid}##

		// Set filter state for category
		$categoryId = $this->getUserStateFromRequest($this->context . '.filter.category_id', 'filter_category_id');
		$this->setState('filter.category_id', $categoryId);##{end_catid}####{start_language}##

		// Set filter state for language
		$language = $this->getUserStateFromRequest($this->context . '.filter.language', 'filter_language', '');
		$this->setState('filter.language', $language);##{end_language}####{start_tags}##

		// Set filter state for tags
		$tag = $this->getUserStateFromRequest($this->context . '.filter.tag', 'filter_tag', '');
		$this->setState('filter.tag', $tag);##{end_tags}####setstates####{start_language}##
		
		// force a language
		$forcedLanguage = $app->input->get('forcedLanguage');

		// Set filter state for language if a language is forced
		if (!empty($forcedLanguage))
		{
			$this->setState('filter.language', $forcedLanguage);
			$this->setState('filter.forcedLanguage', $forcedLanguage);
		}##{end_language}##

		// Load the parameters.
		$params = JComponentHelper::getParams('com_##component##');##{start_site}##
		$active = $menu->getActive();
		empty($active) ? null : $params->merge($active->params);##{end_site}##
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
		$id .= ':' . $this->getState('filter.search');##{start_access}##
		$id .= ':' . $this->getState('filter.access');##{end_access}####{start_published}##
		$id .= ':' . $this->getState('filter.published');##{end_published}####{start_catid}##
		$id .= ':' . $this->getState('filter.category_id');##{end_catid}####{start_table_nested}##
		$id .= ':' . $this->getState('filter.parent_id');##{end_table_nested}####{start_created_by}##
		$id .= ':' . $this->getState('filter.author_id');##{end_created_by}####{start_language}##
		$id .= ':' . $this->getState('filter.language');##{end_language}##

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
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select('a.*')->from('#__##table_db## AS a');##relations####{start_language}##
		
		// Join over the language
		$query->select('l.title AS language_title')
			->join('LEFT', $db->quoteName('#__languages') . ' AS l ON l.lang_code = a.language');##{end_language}####{start_checked_out}##
		
		// Join over the users for the checked out user.
		$query->select('uc.name AS editor')
			->join('LEFT', '#__users AS uc ON uc.id = a.checked_out');##{end_checked_out}####{start_access}##
		
		// Join over the asset groups.
		$query->select('ag.title AS access_level')
			->join('LEFT', '#__viewlevels AS ag ON ag.id = a.access');##{end_access}####{start_catid}##
			
		// Join over the categories.
		$query->select('c.title AS category_title, c.path AS category_route, c.access AS category_access, c.alias AS category_alias')
			->join('LEFT', '#__categories AS c ON c.id = a.catid');##{start_site}##

		// Join over the categories to get parent category titles
		$query->select('parent.title as parent_title, parent.id as parent_id, parent.path as parent_route, parent.alias as parent_alias')
			->join('LEFT', '#__categories as parent ON parent.id = c.parent_id');##{end_site}####{end_catid}####{start_created_by}##
		
		// Join over the users for the author.
		$query->select('ua.name AS author_name')
			->join('LEFT', '#__users AS ua ON ua.id = a.created_by');##{end_created_by}####{start_modified_by}##

		// Join over the users for the modifier.
		$query->select('um.name AS modifier_name')
			->join('LEFT', '#__users AS um ON um.id = a.modified_by');##{end_modified_by}####{start_table_nested}##

		// Exclude the root category.
		$query->where('a.id > 1');##{end_table_nested}##

		// Filter by search
		$search = $this->getState('filter.search');
		$s = $db->quote('%'.$db->escape($search, true).'%');
		
		if (!empty($search))
		{
			if (stripos($search, '##pk##:') === 0)
			{
				$query->where('a.##pk## = ' . (int) substr($search, strlen('##pk##:')));
			}
			elseif (stripos($search, '##mainfield##:') === 0)
			{
				$search = $db->quote('%' . $db->escape(substr($search, strlen('##mainfield##:')), true) . '%');
				$query->where('(a.##mainfield## LIKE ' . $search);
			}##{start_created_by}##
			elseif (stripos($search, 'author:') === 0)
			{
				$search = $db->quote('%' . $db->escape(substr($search, 7), true) . '%');
				$query->where('(ua.name LIKE ' . $search . ' OR ua.username LIKE ' . $search . ')');
			}##{end_created_by}##
			else
			{
				$search = $db->quote('%' . $db->escape($search, true) . '%');
				##where_clause##
			}
		}##{start_table_nested}##

		// Filter on the level.
		if ($level = $this->getState('filter.level'))
		{
			$query->where('a.level <= ' . (int) $level);
		}##{end_table_nested}####{start_published}##
		
		// Filter by published state.
		$published = $this->getState('filter.published');
		if (is_numeric($published))
		{
			$query->where('a.published = ' . (int) $published);
		}
		elseif ($published === '')
		{
			// Only show items with state 'published' / 'unpublished'
			$query->where('(a.published IN (0, 1))');
		}##{end_published}####{start_language}##
		
		// Filter on the language.
		if ($language = $this->getState('filter.language'))
		{
			$query->where('a.language = ' . $db->quote($language));
		}##{end_language}####{start_tags}##
		
		// Filter by a single tag.
		$tagId = $this->getState('filter.tag');
		if (is_numeric($tagId))
		{
			$query->where($db->quoteName('tagmap.tag_id') . ' = ' . (int) $tagId)
				->join(
					'LEFT', $db->quoteName('#__contentitem_tag_map', 'tagmap')
					. ' ON ' . $db->quoteName('tagmap.content_item_id') . ' = ' . $db->quoteName('a.##pk##')
					. ' AND ' . $db->quoteName('tagmap.type_alias') . ' = ' . $db->quote('com_##component##.##singular##')
				);
		}##{end_tags}####{start_catid}##
		
		// Filter by category
		$categoryId = $this->getState('filter.category_id');
		if (is_numeric($categoryId))
		{
			$query->where('a.catid = '.(int) $categoryId);
		}##{end_catid}####{start_created_by}##
		
		// Filter by author
		$authorId = $this->getState('filter.author_id');
		if (is_numeric($authorId))
		{
			$type = $this->getState('filter.author_id.include', true) ? '= ' : '<>';
			$query->where('a.created_by ' . $type . (int) $authorId);
		}##{end_created_by}####{start_access}##
		
		// Filter by access level.
		$access = $this->getState('filter.access');
		if (!empty($access))
		{
			$query->where('a.access = ' . (int) $access);
		}##{end_access}####{start_catidORaccess}##
		
		// Implement View Level Access
		$user = JFactory::getUser();
		if (!$user->authorise('core.admin'))
		{
			$groups = implode(',', $user->getAuthorisedViewLevels());##{start_access}##
			$query->where('a.access IN (' . $groups . ')');##{end_access}####{start_catid}##
			$query->where('c.access IN (' . $groups . ')');##{end_catid}##
		}##{end_catidORaccess}####{start_tags}##
		
		// Filter by a single tag.
		$tagId = $this->getState('filter.tag');
		if (is_numeric($tagId))
		{
			$query->where($db->quoteName('tagmap.tag_id') . ' = ' . (int) $tagId)
				->join(
					'LEFT', $db->quoteName('#__contentitem_tag_map', 'tagmap')
					. ' ON ' . $db->quoteName('tagmap.content_item_id') . ' = ' . $db->quoteName('a.##pk##')
					. ' AND ' . $db->quoteName('tagmap.type_alias') . ' = ' . $db->quote('com_##component##.##singular##')
				);
		}##{end_tags}####filterby##
		
		// Add list oredring and list direction to SQL query##{start_table_nested}##
		$sort = $this->getState('list.ordering', 'a.lft');##{end_table_nested}####{!start_table_nested}##
		$sort = $this->getState('list.ordering', '##mainfield##');##{!end_table_nested}##
		$order = $this->getState('list.direction', 'ASC');
		$query->order($db->escape($sort).' '.$db->escape($order));
		
		return $query;
	}##{start_created_by}##
	
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
			->join('INNER', '#__##table_db## AS a ON a.created_by = u.id')
			->group('u.id, u.name')
			->order('u.name');

		// Setup the query
		$db->setQuery($query);

		// Return the result
		return $db->loadObjectList();
	}##{end_created_by}##
	
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