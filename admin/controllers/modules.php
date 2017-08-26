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
 * JDeveloper Modules Controller
 *
 * @package     JDeveloper
 * @subpackage  Controllers
 */
class JDeveloperControllerModules extends JDeveloperControllerList
{
	/**
	 * Create ZIP file of modules
	 *
	 * @param	array	$ids	The primary keys of the items
	 */
	public function create($ids = array())
	{
		// Initialize
		$user = JFactory::getUser();
		$app = JFactory::getApplication();
		$this->setRedirect(JRoute::_('index.php?option=com_jdeveloper&view=module', false));

		empty($ids) ? $ids = $app->input->get('cid', array(), 'array') : null;
		
		// Check access
		if (!$user->authorise('modules.create', 'com_jdeveloper'))
		{
			$this->setMessage(JText::_('COM_JDEVELOPER_ACTION_NOT_ALLOWED'), 'warning');
			return;
		}

		// Load classes
		JDeveloperLoader::import('archive');
		JDeveloperLoader::import('template');
		JDeveloperLoader::import('module', JDeveloperCREATE);
		jimport('joomla.filesystem.folder');

		// Create module for each id
		foreach ($ids as $id)
		{
			$module = $this->getModel()->getItem($id);
			$path = $module->createDir;
			
			// Delete old archive if exists
			(JFile::exists($path.'.zip')) ? JFile::delete($path.'.zip') : null;
			
			// Create module
			try {
				JDeveloperCreateModule::execute(array("item_id" => $id));
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

		$this->setMessage(JText::sprintf('COM_JDEVELOPER_MODULE_MESSAGE_ZIP_CREATED', count($ids)));
	}	

	/**
	 * Delete ZIP files of modules
	 */
	public function deletezip()
	{
		require_once JDeveloperLIB . "/archive.php";
		
		// Check access
		$user = JFactory::getUser();
		$this->setRedirect(JRoute::_('index.php?option=com_jdeveloper&view=module', false));
		
		if (!$user->authorise('modules.deletezip', 'com_jdeveloper'))
		{
			$this->setMessage(JText::_('COM_JDEVELOPER_ACTION_NOT_ALLOWED'), 'warning');
			return;
		}

		$app = JFactory::getApplication();
		$ids = $app->input->get('cid', array(), 'array');
		
		foreach ($ids as $id)
		{
			$module = $this->getModel()->getItem($id);
			$files = JDeveloperArchive::getVersions("mod_", $module->name);
			
			foreach ($files as $file)
			{
				JFile::delete($module->createDir . ".zip");
			}
		}
		
		$this->setMessage(JText::sprintf('COM_JDEVELOPER_MODULE_MESSAGE_ZIP_DELETED', count($ids)));
	}

	/**
	 * Install modules
	 */
	public function install()
	{
		// Check access
		$user = JFactory::getUser();
		$this->setRedirect(JRoute::_('index.php?option=com_jdeveloper&view=module', false));
		
		if (!$user->authorise('modules.install', 'com_jdeveloper'))
		{
			$this->setMessage(JText::_('COM_JDEVELOPER_ACTION_NOT_ALLOWED'), 'warning');
			return;
		}
		
		// Initialize
		jimport('joomla.filesystem.folder');
		require_once JDeveloperLIB . '/install.php';
		
		$app = JFactory::getApplication();
		$ids = $app->input->get('cid', array(), 'array');
		
		// Install modules
		foreach ($ids as $id)
		{
			$module = $this->getModel()->getItem($id);
			$path = $module->createDir . '.zip';
			$this->create(array($id));
			
			if (JDeveloperInstall::isInstalled('module', 'mod_' . $module->name))
			{
				JDeveloperInstall::install($path, true);
			}
			else
			{
				JDeveloperInstall::install($path);
			}
		}
		
		$this->setMessage(JText::sprintf('COM_JDEVELOPER_MODULE_MESSAGE_INSTALLED', count($ids)));
	}

	/**
	 * Uninstall modules
	 */
	public function uninstall()
	{
		// Check access
		$user = JFactory::getUser();
		$this->setRedirect(JRoute::_('index.php?option=com_jdeveloper&view=module', false));
		
		if (!$user->authorise('modules.uninstall', 'com_jdeveloper'))
		{
			$this->setMessage(JText::_('COM_JDEVELOPER_ACTION_NOT_ALLOWED'), 'warning');
			return;
		}
		
		// Initialize
		require_once JDeveloperLIB.DS. 'install.php';
		
		$app = JFactory::getApplication();
		$ids = $app->input->get('cid', array(), 'array');
		
		// Uninstall modules
		foreach ($ids as $id)
		{
			$module = $this->getModel()->getItem($id);
			JDeveloperInstall::uninstall("module", "mod_" . $module->name);
		}
		
		$this->setMessage(JText::sprintf('COM_JDEVELOPER_MODULE_MESSAGE_UNINSTALLED', count($ids)));
	}
}