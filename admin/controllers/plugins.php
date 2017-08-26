<?php
/**
 * @package     JDeveloper
 * @subpackage  Controllers
 *
 * @copyright  	Copyright (C) 2014, Tilo-Lars Flasche. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;
JDeveloperLoader::import("controllers.list");

/**
 * JDeveloper Plugins Controller
 *
 * @package     JDeveloper
 * @subpackage  Controllers
 */
class JDeveloperControllerPlugins extends JDeveloperControllerList
{
	/**
	 * Create ZIP file of plugins
	 *
	 * @param	array	$ids	The primary keys of the items
	 */
	public function create($ids = array())
	{
		// Initialize
		$user = JFactory::getUser();
		$app = JFactory::getApplication();
		$this->setRedirect(JRoute::_('index.php?option=com_jdeveloper&view=plugin', false));
		if (empty($ids)) $ids = $app->input->get('cid', array(), 'array');
		
		// Check access
		if (!$user->authorise('plugins.create', 'com_jdeveloper'))
		{
			$this->setMessage(JText::_('COM_JDEVELOPER_ACTION_NOT_ALLOWED'), 'warning');
			return;
		}

		// Load classes
		JDeveloperLoader::import('archive');
		JDeveloperLoader::import('template');
		JDeveloperLoader::import('plugin', JDeveloperCREATE);
		jimport('joomla.filesystem.folder');

		// Create Plugin for each id
		foreach ($ids as $id)
		{
			$plugin = $this->getModel()->getItem($id);
			$path = $plugin->createDir;
			
			// Delete old archive if exists
			(JFile::exists($path.'.zip')) ? JFile::delete($path.'.zip') : null;
			
			// Create Plugin
			try {
				JDeveloperCreatePlugin::execute(array("item_id" => $id));
			}
			catch (JDeveloperException $e) {
				$this->setMessage($e->getMessage(), "error");
				return;
			}
						
			// Create HTML files for each folder, zip the folder and delete the folder
			JDeveloperArchive::html($path);
			
			// Create ZIP archive and delete folder
			JDeveloperArchive::zip($path);
			JFolder::delete($path);
		}

		$this->setMessage(JText::sprintf('COM_JDEVELOPER_PLUGIN_MESSAGE_ZIP_CREATED', count($ids)));
	}
	
	/**
	 * Create folder
	 */
	public function createFolder() {
		$data = JFactory::getApplication()->input->get("jform", array(), "array");
		
		if (isset($data["name"])) {
			if (!JFolder::create(JPATH_ROOT . "/plugins/" . $data["name"])) {
				$this->setMessage(JText::_("COM_JDEVELOPER_PLUGIN_MESSAGE_CREATE_FOLDER_SUCCESS", "error"));
			}
		}
		
		$this->setMessage(JText::_("COM_JDEVELOPER_PLUGIN_MESSAGE_CREATE_FOLDER_ERROR"));
		$this->setRedirect(JRoute::_("index.php?option=com_jdeveloper&view=plugins", false));
	}

	/**
	 * Delete ZIP files of plugin
	 */
	public function deletezip()
	{
		require_once JDeveloperLIB . "/archive.php";
		
		// Check access
		$user = JFactory::getUser();
		$this->setRedirect(JRoute::_('index.php?option=com_jdeveloper&view=plugin', false));
		
		if (!$user->authorise('plugins.deletezip', 'com_jdeveloper'))
		{
			$this->setMessage(JText::_('COM_JDEVELOPER_ACTION_NOT_ALLOWED'), 'warning');
			return;
		}

		$app = JFactory::getApplication();
		$ids = $app->input->get('cid', array(), 'array');
		
		foreach ($ids as $id)
		{
			$plugin = $this->getModel()->getItem($id);
			$files = JDeveloperArchive::getVersions("plg_", $plugin->name);
			
			foreach ($files as $file)
			{
				JFile::delete($plugin->createDir . ".zip");
			}
		}
		
		$this->setMessage(JText::sprintf('COM_JDEVELOPER_PLUGIN_MESSAGE_ZIP_DELETED', count($ids)));
	}

	/**
	 * Install plugins
	 */
	public function install()
	{
		// Check access
		$user = JFactory::getUser();
		$this->setRedirect(JRoute::_('index.php?option=com_jdeveloper&view=plugin', false));
		
		if (!$user->authorise('plugins.install', 'com_jdeveloper'))
		{
			$this->setMessage(JText::_('COM_JDEVELOPER_ACTION_NOT_ALLOWED'), 'warning');
			return;
		}
		
		// Initialize
		jimport('joomla.filesystem.folder');
		require_once JDeveloperLIB . '/install.php';
		
		$app = JFactory::getApplication();
		$ids = $app->input->get('cid', array(), 'array');
		
		// Install plugins
		foreach ($ids as $id)
		{
			$plugin = $this->getModel()->getItem($id);
			$path = $plugin->createDir . '.zip';
			$this->create(array($id));
			
			if (JDeveloperInstall::isInstalled('plugin', 'plg_' . $plugin->name, $plugin->folder))
			{
				JDeveloperInstall::install($path, true);
			}
			else
			{
				JDeveloperInstall::install($path);
			}
		}
		
		$this->setMessage(JText::sprintf('COM_JDEVELOPER_PLUGIN_MESSAGE_INSTALLED', count($ids)));
	}

	/**
	 * Uninstall plugins
	 */
	public function uninstall()
	{
		// Check access
		$user = JFactory::getUser();
		$this->setRedirect(JRoute::_('index.php?option=com_jdeveloper&view=plugin', false));
		
		if (!$user->authorise('plugins.uninstall', 'com_jdeveloper'))
		{
			$this->setMessage(JText::_('COM_JDEVELOPER_ACTION_NOT_ALLOWED'), 'warning');
			return;
		}
		
		// Initialize
		require_once JDeveloperLIB.DS. 'install.php';
		
		$app = JFactory::getApplication();
		$ids = $app->input->get('cid', array(), 'array');
		
		// Uninstall plugins
		foreach ($ids as $id)
		{
			$plugin = $this->getModel()->getItem($id);
			JDeveloperInstall::uninstall("plugin", $plugin->name, $plugin->folder);
		}
		
		$this->setMessage(JText::sprintf('COM_JDEVELOPER_PLUGIN_MESSAGE_UNINSTALLED', count($ids)));
	}
}