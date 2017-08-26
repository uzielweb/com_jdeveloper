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
 * JDeveloper Components Controller
 *
 * @package     JDeveloper
 * @subpackage  Controllers
 */
class JDeveloperControllerComponents extends JDeveloperControllerList
{
	/**
	 * Install components
	 */
	public function install()
	{
		// Check access
		$user = JFactory::getUser();
		$this->setRedirect(JRoute::_('index.php?option=com_jdeveloper&view=component', false));

		// Check if action is allowed
		if (!$user->authorise('components.install', 'com_jdeveloper'))
		{
			$this->setMessage(JText::_('COM_JDEVELOPER_ACTION_NOT_ALLOWED'), 'warning');
			return;
		}
		
		jimport('joomla.filesystem.folder');
		require_once JDeveloperLIB.DS. 'install.php';
		
		$app = JFactory::getApplication();
		$ids = $app->input->get('cid', array(), 'array');
				
		// Install components
		foreach ($ids as $id)
		{
			$component = $this->getModel()->getItem($id);
			$path = $component->createDir . '.zip';
			$this->create(array($id));
			
			if (JDeveloperInstall::isInstalled('component', 'com_' . $component->name))
			{
				JDeveloperInstall::install($path, true);
			}
			else
			{
				JDeveloperInstall::install($path);
			}
		}
				
		$this->setMessage(JText::sprintf('COM_JDEVELOPER_COMPONENT_INSTALLED', count($ids)));
	}
	
	/**
	 * Uninstall components
	 */
	public function uninstall()
	{
		// Check access
		$user = JFactory::getUser();
		$this->setRedirect(JRoute::_('index.php?option=com_jdeveloper&view=component', false));
		
		if (!$user->authorise('components.uninstall', 'com_jdeveloper'))
		{
			$this->setMessage(JText::_('COM_JDEVELOPER_ACTION_NOT_ALLOWED'), 'warning');
			return;
		}

		require_once JDeveloperLIB.DS. 'install.php';
		
		$app = JFactory::getApplication();
		$ids = $app->input->get('cid', array(), 'array');
		
		foreach ($ids as $id)
		{
			$component = $this->getModel()->getItem($id);
			JDeveloperInstall::uninstall("component", "com_" . $component->name);
		}
		
		$this->setMessage(JText::sprintf('COM_JDEVELOPER_COMPONENT_UNINSTALLED', count($ids)));
	}
	
	/**
	 * Create component ids
	 *
	 * @param	array	$ids	The component 
	 */
	public function create($ids = array())
	{
		// Initialize
		$user = JFactory::getUser();
		$app = JFactory::getApplication();
		$this->setRedirect(JRoute::_('index.php?option=com_jdeveloper&view=component', false));

		if (empty($ids))
		{
			$ids = $app->input->get('cid', array(), 'array');
		}
		
		// Check access
		if (!$user->authorise('components.create', 'com_jdeveloper'))
		{
			$this->setMessage(JText::_('COM_JDEVELOPER_ACTION_NOT_ALLOWED'), 'warning');
			return;
		}

		// Load classes
		JDeveloperLoader::import('archive');
		JDeveloperLoader::import('template');
		JDeveloperLoader::import('component', JDeveloperCREATE);
		JDeveloperLoader::import('table', JDeveloperCREATE);
		jimport('joomla.filesystem.folder');

		// Create component for each id
		foreach ($ids as $id)
		{
			$component = $this->getModel()->getItem($id);
			$path = $component->createDir;

			// Delete old archive if exists
			JFile::exists($path . '.zip') ? JFile::delete($path . '.zip') : null;
			
			// Create component
			JDeveloperCreateComponent::execute("admin", array("item_id" => $id));
			JDeveloperCreateTable::execute("admin", array("item_id" => $id));
			
			// Create component for frontend
			if ($component->get('site', 0))
			{
				JDeveloperCreateComponent::execute("site", array("item_id" => $id));
				JDeveloperCreateTable::execute("site", array("item_id" => $id));
			}
			
			// Get language files content
			$buffer = JDeveloperLanguage::getStaticInstance("com_" . $component->name)->getINI();
			$buffer_sys = JDeveloperLanguage::getStaticInstance("com_" . $component->name . "_sys")->getINI();

			// Write language files
			foreach ($component->params["languages"] as $lang)
			{
				JFile::write($component->createDir . "/admin/language/$lang.com_" . strtolower($component->name) . ".ini", $buffer);
				JFile::write($component->createDir . "/admin/language/$lang.com_" . strtolower($component->name) . ".sys.ini", $buffer_sys);
				$component->site ? JFile::write($component->createDir . "/site/language/$lang.com_" . strtolower($component->name) . ".ini", $buffer) : null;
			}
			
			// Create HTML files for each folder
			JDeveloperArchive::html($path . '/admin');
			((int) $component->get('site', 0)) ? JDeveloperArchive::html($path . '/site') : null;
			
			// Create ZIP archive and delete folder
			JDeveloperArchive::zip($path);
			JFolder::delete($path);
		}

		$this->setMessage(JText::sprintf('COM_JDEVELOPER_COMPONENT_CREATED', count($ids)));
	}
	
	/**
	 * Delete ZIP files of component
	 */
	public function deletezip()
	{
		// Check access
		$user = JFactory::getUser();
		$this->setRedirect(JRoute::_('index.php?option=com_jdeveloper&view=component', false));
		
		if (!$user->authorise('components.deletezip', 'com_jdeveloper'))
		{
			$this->setMessage(JText::_('COM_JDEVELOPER_ACTION_NOT_ALLOWED'), 'warning');
			return;
		}

		$app = JFactory::getApplication();
		$ids = $app->input->get('cid', array(), 'array');
		
		foreach ($ids as $id)
		{
			$component = $this->getModel()->getItem($id);
			$files = JDeveloperArchive::getVersions("com_", $component->name);
			
			foreach ($files as $file)
			{
				JFile::delete(JDeveloperArchive::getArchiveDir() . "/" . $file);
			}
		}
		
		$this->setMessage(JText::sprintf('COM_JDEVELOPER_COMPONENT_ZIP_DELETED', count($ids)));
	}
}