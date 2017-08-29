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
 * JDeveloper Archive Model
 *
 * @package     Archive
 * @subpackage  Models
 */
class JDeveloperModelArchive extends JModelList
{
	/**
	 * Constructor
	 *
	 * @param	array	$config		The Configuration
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array();
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
		jimport("joomla.filesystem.folder");
		require_once JDeveloperLIB.DS.'archive.php';
		
		$store = $this->getStoreId('getItems');
		$items = array();
		$files = JFolder::files(JDeveloperArchive::getArchiveDir(), "^(com|mod|pkg|plg|tpl)_[A-Za-z0-9-_]*_v[0-9.]*\.zip$");

		// Get archives
		foreach ($files as $file)
		{
			$archive = new JObject();
			$archive->name = $file;
			$archive->link = JDeveloperArchive::getArchiveDir(true) . "/" . $file;
			
			$items[] = $archive;
		}
		
		// Add the items to the internal cache.
		$this->cache[$store] = $items;

		return $this->cache[$store];
	}
}