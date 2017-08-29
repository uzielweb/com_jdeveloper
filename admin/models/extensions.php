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
 * JDeveloper Extensions Model
 *
 * @package     JDeveloper
 * @subpackage  Models
 */
class JDeveloperModelExtensions extends JModelList
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
				'a.extension_id', 'extension_id',
				'a.name', 'name'
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
		
        $search = $app->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);
		
        $type = $app->getUserStateFromRequest($this->context . '.filter.type', 'filter_type');
        $this->setState('filter.type', $type);
		
        $folder = $app->getUserStateFromRequest($this->context . '.filter.folder', 'filter_folder');
        $this->setState('filter.folder', $folder);
		
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

		return parent::getStoreId($id);
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return  JDatabaseQuery
	 */
	protected function getListQuery()
	{
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select("a.extension_id, a.name, a.folder, a.type, a.element")->from("#__extensions AS a");

		// Filter by search
		$search = $this->getState('filter.search');
		$s = $db->quote('%'.$db->escape($search, true).'%');
		
		if (!empty($search))
		{
			if (stripos($search, 'extension_id:') === 0)
			{
				$query->where('a.extension_id = ' . (int) substr($search, strlen('extension_id:')));
			}
			elseif (stripos($search, 'name:') === 0)
			{
				$search = $db->quote('%' . $db->escape(substr($search, strlen('name:')), true) . '%');
				$query->where('(a.name LIKE ' . $search);
			}
			else
			{
				$search = $db->quote('%' . $db->escape($search, true) . '%');
				$query->where('(a.name LIKE ' . $search . ')');
			}
		}

		// Filter by type
		$type = $this->getState("filter.type");
		if (!empty($type))
		{
			$query->where("a.type = " . $db->quote($db->escape($type)));
		}
		else
		{
			$query->where("a.type IN ('component', 'module', 'template', 'plugin')");
		}

		// Filter by folder
		$folder = $this->getState("filter.folder");
		if (!empty($folder))
		{
			$query->where("a.folder = " . $db->quote($db->escape($folder)));
		}

		$sort = $this->getState('list.ordering', 'name');
		$order = $this->getState('list.direction', 'ASC');
		$query->order($db->escape($sort).' '.$db->escape($order));
		
		return $query;
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
		if ($items = parent::getItems())
		{
			// Add information
			foreach ($items as $item)
			{
				switch ($item->type)
				{
					case "component":
						$element = "com_";
						break;
					case "module":
						$element = "mod_";
						break;
					case "template":
						$element = "tpl_";
						break;
					case "plugin":
						$element = "plg_";
						break;
				}
				
				$item->filename = (preg_match("/^" . $element . "/", $item->element)) ? $item->element : $element . $item->element;
				if ($item->type == "plugin") $item->filename = $item->name;
			}
		}

		return $items;
	}
}
?>