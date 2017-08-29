<?php
/**
 * @package     JDeveloper
 * @subpackage  Create.Component
 *
 * @copyright  	Copyright (C) 2014, Tilo-Lars Flasche. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;
JDeveloperLoader::import("component", JDeveloperCREATE);

/**
 * Component Create Class
 *
 * @package     JDeveloper
 * @subpackage  Create.Component
 */
class JDeveloperCreateComponentAdminManifest extends JDeveloperCreateComponent
{		
	/**
	 * The template file
	 *
	 * @var	string
	 */
	protected $templateFile = "component.xml";

	protected function initialize()
	{
		$this->template->addPlaceHolders(
			array( 
			'author' 			=> $this->component->get('author'),
			'author_email' 		=> $this->component->get('author_email'),
			'author_url' 		=> $this->component->get('author_url'),
			'copyright' 		=> $this->component->get('copyright'),
			'creationdate' 		=> date("M Y"),
			'licence'	 		=> $this->component->get('licence'),
			'languages_site' 	=> $this->langSite(),
			'languages_admin' 	=> $this->langAdmin(),
			'submenus'	 		=> $this->submenus(),
			'updatefiles' 		=> $this->updateFiles(),
			'version'	 		=> $this->component->get('version'),
			)
		);
		
		$this->template->addAreas(
			array(
				'dbtables' => !empty($this->tables),
				'frontend' => $this->component->site,
				'mvc' => !empty($this->tables)
			)
		);
		
		return parent::initialize();
	}
	
	private function langSite()
	{
		$buffer = '';
		$cname = $this->component->get('name', '');
		
		foreach ($this->component->params['languages'] as $lang)
		{
			$buffer .= "\n\t\t<language tag=\"$lang\">language/$lang.com_$cname.ini</language>";
		}

		return $buffer;
	}
	
	private function langAdmin()
	{
		$buffer = '';
		$cname = $this->component->get('name', '');
		
		foreach ($this->component->params['languages'] as $lang)
		{
			$buffer .= "\n\t\t\t<language tag=\"$lang\">language/$lang.com_$cname.ini</language>";
			$buffer .= "\n\t\t\t<language tag=\"$lang\">language/$lang.com_$cname.sys.ini</language>";
		}

		return $buffer;
	}
	
	private function updateFiles()
	{
		$buffer = '';
		$cname = $this->component->get('display_name', '');
		
		foreach ($this->component->get('updatesites', array(), 'array') as $site)
		{
			$buffer .= "\n\t\t<server type=\"extension\" priority=\"1\" name=\"$cname Update Site\">$site</server>";
		}

		return $buffer;
	}

	private function submenus()
	{
		$buffer = '';
		$cname = $this->component->get('name', '');
		
		foreach ($this->tables as $table)
		{
			$tablename = $table->get('plural');
			$buffer .= "\n\t\t\t<menu link=\"option=com_$cname&amp;view=". strtolower($tablename) ."\">$tablename</menu>";
		}

		return $buffer;
	}
}