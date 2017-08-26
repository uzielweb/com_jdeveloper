<?php
/**
 * @package     JDeveloper
 * @subpackage  Controllers
 *
 * @copyright  	Copyright (C) 2014, Tilo-Lars Flasche. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

/**
 * JDeveloper Package Controller
 *
 * @package     JDeveloper
 * @subpackage  Controllers
 */
class JDeveloperControllerPackage extends JControllerForm
{
	/**
	 * Redirect to edit view
	 */
	public function add()
	{
		$this->setRedirect(JRoute::_('index.php?option=com_jdeveloper&view=package&layout=edit', false));
	}
	
	/**
	 * Redirect to list view
	 */
	public function cancel($key = null)
	{
		$this->setRedirect(JRoute::_('index.php?option=com_jdeveloper&view=packages', false));
	}
	
	/**
	 * Create a package
	 */
	public function create()
	{
		jimport('joomla.filesystem.folder');
		require_once JDeveloperLIB . '/archive.php';
		require_once JDeveloperLIB . '/path.php';
		require_once JDeveloperLIB . '/template.php';
		
		$data  = $this->input->post->get('jform', array(), 'array');
		$params = JComponentHelper::getParams($this->option);
		$files = array();
		$path = JDeveloperArchive::getArchiveDir() . "/" . 'pkg_'.$data['name'];
		
		while (JFile::exists($path.'.zip'))
		{
			$data['name'] = JString::increment($data['name']);
		}
		JFolder::create($path);
		
		foreach ($data['files'] as $file)
		{
			if (preg_match('/^pkg_/', $file)) $files[] = "<file type=\"package\" id=\"". str_replace('.zip', '', $file) ."\">$file</file>";
			if (preg_match('/^com_/', $file)) $files[] = "<file type=\"component\" id=\"". str_replace('.zip', '', $file) ."\">$file</file>";
			if (preg_match('/^mod_/', $file)) $files[] = "<file type=\"module\" id=\"". str_replace('.zip', '', $file) ."\">$file</file>";
			if (preg_match('/^tpl_/', $file)) $files[] = "<file type=\"template\" id=\"". str_replace('.zip', '', $file) ."\">$file</file>";
			if (preg_match('/^plg_/', $file)) $files[] = "<file type=\"plugin\" id=\"". str_replace('.zip', '', $file) ."\">$file</file>";

			JFile::copy(JDeveloperArchive::getArchiveDir().DS.$file, $path.DS.$file, null, true);
		}

		$template = new JDeveloperTemplate(JDeveloperTEMPLATES . "/pkg/manifest.xml");
		$template->addPlaceholders(
			array(
				'author'		=> $params->get('author'),
				'author_email'	=> $params->get('email'),
				'author_url'	=> $params->get('website'),
				'copyright'		=> $params->get('copyright'),
				'date'			=> date("M Y"),
				'description'	=> $data['description'],
				'files'			=> implode("\n\t\t", $files),
				'license'		=> $params->get('license'),
				'name'			=> $data['name'],
				'version'		=> $data['version'],
			)
		);
		
		$buffer = $template->getBuffer();
		JFile::write($path . '/pkg_' . $data['name'] . '.xml', $buffer);
		
		JDeveloperArchive::html($path);
		JDeveloperArchive::zip($path);
		JFolder::delete($path);
	
		$this->setMessage(JText::sprintf('COM_JDEVELOPER_PACKAGE_CREATED', $data['name']));
		$this->setRedirect(JRoute::_('index.php?option=com_jdeveloper&view=packages', false));
	}
	
	/**
	 * Method to delete one or more records.
	 */
	public function delete()
	{
		$files = JFactory::getApplication()->input->get('cid', array(), 'array');
		
		foreach ($files as $file)
		{
			if (!JFile::delete(JDeveloperARCHIVE . "/" . $file)) throw new Exception(JText::sprintf('COM_JDEVELOPER_PACKAGE_DELETE_FAIL', $file));
		}
		
		$this->setMessage(JText::sprintf('COM_JDEVELOPER_PACKAGE_DELETE_SUCCESS', count($files)));
		$this->setRedirect(JRoute::_('index.php?option=com_jdeveloper&view=packages', false));
	}

	/**
	 * Install packages
	 */
	public function install()
	{
		require_once JDeveloperLIB.DS.'install.php';
		$this->setRedirect(JRoute::_('index.php?option=com_jdeveloper&view=packages', false));
		
		$app = JFactory::getApplication();
		$user = JFactory::getUser();
		
		if (!$user->authorise('packages.install', 'com_jdeveloper'))
		{
			$this->setMessage(JText::_('COM_JDEVELOPER_ACTION_NOT_ALLOWED'), 'warning');
			return;
		}

		$files = $app->input->get('cid', array(), 'array');

		foreach ($files as $file)
		{
			preg_match("/pkg_[A-Za-z_]*/", $file, $matches);
			
			$path = JDeveloperARCHIVE . "/" . $file;
			JDeveloperInstall::install($path, JDeveloperInstall::isInstalled('package', $matches[0]));
		}
		
		$this->setMessage(JText::sprintf('COM_JDEVELOPER_PACKAGE_INSTALLATION_SUCCESS', count($files)));
	}
}