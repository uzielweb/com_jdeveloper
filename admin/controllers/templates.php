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
 * JDeveloper Templates Controller
 *
 * @package     JDeveloper
 * @subpackage  Controllers
 */
class JDeveloperControllerTemplates extends JDeveloperControllerList
{
	/**
	 * Create ZIP file of template
	 *
	 * @param	array	$ids	The primary keys of the items
	 */
	public function create($ids = array())
	{
		// Initialize
		$user = JFactory::getUser();
		$app = JFactory::getApplication();
		$this->setRedirect(JRoute::_('index.php?option=com_jdeveloper&view=template', false));
		if (empty($ids)) $ids = $app->input->get('cid', array(), 'array');
		
		// Check access
		if (!$user->authorise('templates.create', 'com_jdeveloper'))
		{
			$this->setMessage(JText::_('COM_JDEVELOPER_ACTION_NOT_ALLOWED'), 'warning');
			return;
		}

		// Load classes
		JDeveloperLoader::import('archive');
		JDeveloperLoader::import('template');
		JDeveloperLoader::import('template', JDeveloperCREATE);
		jimport('joomla.filesystem.folder');

		// Create template for each id
		foreach ($ids as $id)
		{
			$template = $this->getModel()->getItem($id);
			$path = $template->createDir;
			
			// Delete old archive if exists
			(JFile::exists($path.'.zip')) ? JFile::delete($path.'.zip') : null;
			
			// Create template
			try {
				JDeveloperCreateTemplate::execute(array("item_id" => $id));
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

		$this->setMessage(JText::sprintf('COM_JDEVELOPER_TEMPLATE_MESSAGE_ZIP_CREATED', count($ids)));
	}	

	/**
	 * Delete ZIP files of template
	 */
	public function deletezip()
	{
		require_once JDeveloperLIB . "/archive.php";
		
		// Check access
		$user = JFactory::getUser();
		$this->setRedirect(JRoute::_('index.php?option=com_jdeveloper&view=template', false));
		
		if (!$user->authorise('templates.deletezip', 'com_jdeveloper'))
		{
			$this->setMessage(JText::_('COM_JDEVELOPER_ACTION_NOT_ALLOWED'), 'warning');
			return;
		}

		$app = JFactory::getApplication();
		$ids = $app->input->get('cid', array(), 'array');
		
		foreach ($ids as $id)
		{
			$template = $this->getModel()->getItem($id);
			$files = JDeveloperArchive::getVersions("tpl_", $template->name);
			
			foreach ($files as $file)
			{
				JFile::delete(JDeveloperArchive::getArchiveDir() . "/" . $file);
			}
		}
		
		$this->setMessage(JText::sprintf('COM_JDEVELOPER_TEMPLATE_MESSAGE_ZIP_DELETED', count($ids)));
	}

	/**
	 * Install templates
	 */
	public function install()
	{
		// Check access
		$user = JFactory::getUser();
		$this->setRedirect(JRoute::_('index.php?option=com_jdeveloper&view=template', false));
		
		if (!$user->authorise('templates.install', 'com_jdeveloper'))
		{
			$this->setMessage(JText::_('COM_JDEVELOPER_ACTION_NOT_ALLOWED'), 'warning');
			return;
		}
		
		// Initialize
		jimport('joomla.filesystem.folder');
		require_once JDeveloperLIB . '/install.php';
		
		$app = JFactory::getApplication();
		$ids = $app->input->get('cid', array(), 'array');
		
		// Install templates
		foreach ($ids as $id)
		{
			$template = $this->getModel()->getItem($id);
			$path = $template->createDir . ".zip";
			$this->create(array($id));
			
			if (JDeveloperInstall::isInstalled('template', $template->name))
			{
				JDeveloperInstall::install($path, true);
			}
			else
			{
				JDeveloperInstall::install($path);
			}
		}
		
		$this->setMessage(JText::sprintf('COM_JDEVELOPER_TEMPLATE_MESSAGE_INSTALLED', count($ids)));
	}

	/**
	 * Uninstall templates
	 */
	public function uninstall()
	{
		// Check access
		$user = JFactory::getUser();
		$this->setRedirect(JRoute::_('index.php?option=com_jdeveloper&view=template', false));
		
		if (!$user->authorise('templates.uninstall', 'com_jdeveloper'))
		{
			$this->setMessage(JText::_('COM_JDEVELOPER_ACTION_NOT_ALLOWED'), 'warning');
			return;
		}
		
		// Initialize
		require_once JDeveloperLIB.DS. 'install.php';
		
		$app = JFactory::getApplication();
		$ids = $app->input->get('cid', array(), 'array');
		
		// Uninstall templates
		foreach ($ids as $id)
		{
			$template = $this->getModel()->getItem($id);
			JDeveloperInstall::uninstall("template", $template->name);
		}
		
		$this->setMessage(JText::sprintf('COM_JDEVELOPER_TEMPLATE_MESSAGE_UNINSTALLED', count($ids)));
	}
}