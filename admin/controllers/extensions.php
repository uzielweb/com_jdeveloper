<?php
/**
 * @package     JDeveloper
 * @subpackage  Controllers
 *
 * @copyright  	Copyright (C) 2014, Tilo-Lars Flasche. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

jimport('joomla.filesystem.folder');
JDeveloperLoader::import("language");
JDeveloperLoader::import("controllers.list");

/**
 * JDeveloper Extensions Controller
 *
 * @package     JDeveloper
 * @subpackage  Controllers
 */
class JDeveloperControllerExtensions extends JDeveloperControllerList
{
	/**
	 * Create ZIP file of existing extension
	 */
	public function zip()
	{
		$ids = JFactory::getApplication()->input->get('cid', array(), 'array');
		$model = JModelLegacy::getInstance("Extension", "JDeveloperModel");
		
		foreach ($ids as $id)
		{
			$extension = $model->getItem($id);
			
			switch ($extension->type)
			{
				case 'component' :
					$this->component($id);
					break;
				case 'module' :
					$this->module($id);
					break;
				case 'template' :
					$this->template($id);
					break;
				case 'plugin' :
					$this->plugin($id);
					break;
				default:
					break;
			}
		}
		
		$this->setMessage(JText::sprintf('COM_JDEVELOPER_EXTENSIONS_CREATED', count($ids)));
		$this->setRedirect(JRoute::_('index.php?option=com_jdeveloper&view=extensions', false));
	}

	/**
	 * Create ZIP file of component
	 *
	 * @param	int		$id		The extension id
	 */
	private function component($id)
	{
		$extension = JModelLegacy::getInstance("Extension", "JDeveloperModel")->getItem($id);
		$admin = false;
		$site = false;
		$PATH_ADMIN = JDeveloperLIVE . "/" . $extension->element . '/admin';
		$PATH_SITE = JDeveloperLIVE . "/" . $extension->element . '/site';
				
		if (JFile::exists(JDeveloperLIVE . "/" .  $extension->element . '.zip')) return;

		// Copy admin files
		if (JFolder::exists(JPATH_ADMINISTRATOR . "/components/" . $extension->element))
		{
			if (!JFolder::copy(JPATH_ADMINISTRATOR . '/components/' . $extension->element, $PATH_ADMIN, '', true, true))
				throw new JDeveloperException("Creating component ($id) admin files failed");
			
			$admin = true;
			JDeveloperArchive::copyLanguageToArchive($extension->element, 'admin/language', 'admin');
		}
		
		// Copy site files
		if (JFolder::exists(JPATH_SITE . '/components/' . $extension->element))
		{
			if (!JFolder::copy(JPATH_SITE.DS.'components'.DS.$extension->element, $PATH_SITE, '', true, true))
				throw new JDeveloperException("Creating component ($id) site files failed");
			
			$site = true;
			JDeveloperArchive::copyLanguageToArchive($extension->element, 'site/language', 'site');
		}
		
		// Copy manifest file
		$xmlpath = $admin ? $PATH_ADMIN : $PATH_SITE;
		$xmlfiles = JFolder::files($xmlpath, "\.xml$");
		
		foreach ($xmlfiles as $file)
		{
			$xml = new SimpleXMLElement($xmlpath.DS.$file, null, true);
			
			if ($xml->getName() == 'extension')
			{
				JFile::move($xmlpath . "/" . $file, JDeveloperLIVE . "/" . $extension->element . "/" . $file);
			}
		}
				
		// Create HTML files for each folder, zip the folder and delete the folder
		JDeveloperArchive::html($PATH_ADMIN);
		if ($site) JDeveloperArchive::html($PATH_SITE);

		// Create ZIP file for component and delete folder
		JDeveloperArchive::zip(JDeveloperLIVE . "/" . $extension->element);
		JFolder::delete(JDeveloperLIVE . "/" . $extension->element);
	}
	
	private function module($id)
	{
		$extension = JModelLegacy::getInstance("Extension", "JDeveloperModel")->getItem($id);
		
		if (JFile::exists(JDeveloperLIVE.DS. $extension->element . '.zip')) return;
		
		if (JFolder::exists(JPATH_ADMINISTRATOR.DS.'modules'.DS.$extension->element))
		{
			$PATH_MODULE = JPATH_ADMINISTRATOR.DS.'modules'.DS.$extension->element;
			$client = 'admin';
		}
		
		if (JFolder::exists(JPATH_SITE.DS.'modules'.DS.$extension->element))
		{
			$PATH_MODULE = JPATH_SITE.DS.'modules'.DS.$extension->element;
			$client = 'site';
		}
		
		if (!JFolder::copy($PATH_MODULE, JDeveloperLIVE.DS.$extension->element, '', true, true))
			throw new JDeveloperException("Copy module ($id) files failed");
			
		JDeveloperArchive::copyLanguageToArchive($extension->element, 'language', $client);
		JDeveloperArchive::html(JDeveloperLIVE.DS.$extension->element);
		JDeveloperArchive::zip(JDeveloperLIVE.DS.$extension->element);
		JFolder::delete(JDeveloperLIVE.DS.$extension->element);
	}
	
	private function template($id)
	{
		$extension = JModelLegacy::getInstance("Extension", "JDeveloperModel")->getItem($id);

		if (JFile::exists(JDeveloperLIVE.DS. $extension->element . '.zip')) return;

		if (JFolder::exists(JPATH_ADMINISTRATOR.DS.'templates'.DS.$extension->name))
		{
			$PATH_TPL = JPATH_ADMINISTRATOR.DS.'templates'.DS.$extension->name;
			$client = 'admin';
		}
		
		if (JFolder::exists(JPATH_SITE.DS.'templates'.DS.$extension->name))
		{
			$PATH_TPL = JPATH_SITE.DS.'templates'.DS.$extension->name;
			$client = 'site';
		}
		
		if (!JFolder::copy($PATH_TPL, JDeveloperLIVE.DS.$extension->filename, '', true, true))
			throw new JDeveloperException("Copy template ($id) files failed");

		JDeveloperArchive::copyLanguageToArchive($extension->filename, 'language', $client);
		JDeveloperArchive::html(JDeveloperLIVE.DS.$extension->filename);
		JDeveloperArchive::zip(JDeveloperLIVE.DS.$extension->filename);
		JFolder::delete(JDeveloperLIVE.DS.$extension->filename);		
	}
	
	private function plugin($id)
	{
		$extension = JModelLegacy::getInstance("Extension", "JDeveloperModel")->getItem($id);
		$extension_file = 'plg_'. $extension->folder .'_'. $extension->element;
		
		if (!JFolder::exists(JPATH_PLUGINS.DS.$extension->folder.DS.$extension->element)) {
			throw new Exception(JText::sprintf('COM_JDEVELOPER_LIVE_EXTENSION_NOT_FOUND', JText::_('COM_JDEVELOPER_LIVE_FIELD_TYPE_PLUGIN'), $extension->name));
		}

		$PATH_PLG = JPATH_PLUGINS.DS.$extension->folder.DS.$extension->element;
		
		if (!JFolder::copy($PATH_PLG, JDeveloperLIVE.DS.$extension_file, '', true, true))
			throw new Exception(JText::sprintf('COM_JDEVELOPER_LIVE_COPY_FAILED', JText::_('COM_JDEVELOPER_LIVE_FIELD_TYPE_PLUGIN'), $extension->name));

		JDeveloperArchive::copyLanguageToArchive($extension_file, 'language', 'admin');
		JDeveloperArchive::html(JDeveloperLIVE.DS.$extension_file);
		JDeveloperArchive::zip(JDeveloperLIVE.DS.$extension_file);
		JFolder::delete(JDeveloperLIVE.DS.$extension_file);
	}
	
	public function deletezip()
	{
		$ids = JFactory::getApplication()->input->get('cid', array(), 'array');
		$model = JModelLegacy::getInstance("Extension", "JDeveloperModel");

		$this->setMessage(JText::sprintf('COM_JDEVELOPER_EXTENSIONS_DELETED', count($ids)));
		$this->setRedirect(JRoute::_('index.php?option=com_jdeveloper&view=extensions', false));

		foreach ($ids as $id)
		{
			$item = $model->getItem($id);
			$file = JDeveloperLIVE . "/" . $item->filename . ".zip";

			if (JFile::exists($file)) JFile::delete($file);
		}
	}
}