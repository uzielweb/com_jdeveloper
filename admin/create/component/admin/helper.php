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
class JDeveloperCreateComponentAdminHelper extends JDeveloperCreateComponent
{		
	/**
	 * The template file
	 *
	 * @var	string
	 */
	protected $templateFile = "admin.helpers.component.php";

	protected function initialize()
	{		
		$this->template->addPlaceHolders(
			array( 
			'getfields' => $this->getFields(),
			'submenu' 	=> $this->submenu(),
			)
		);
		
		return parent::initialize();
	}
	
	private function getFields()
	{
		$buffer = '';
		
		foreach ($this->tables as $table)
		{
			$fields = $this->getModel('fields')->getTableFields($table->id);

			foreach ($fields as $field)
			{
				if ( ! (int) $field->get('listfilter', 0) ) continue;
				$template = $this->loadSubtemplate('getfields.txt');
				$template->addPlaceholders( $this->getDefaultPlaceholders(), true );
				$template->addPlaceholders( array('field' => $field->name, 'table' => $table->name, 'table_db' => $table->dbname), true );
				$buffer .= $template->getBuffer();
			}
		}

		return $buffer;
	}
	
	private function submenu()
	{
		$template = $this->loadSubtemplate('submenu.txt');
		$buffer = '';
		
		foreach ($this->tables as $table)
		{			
			$template->addPlaceholders( 
				array( 
				'component' => strtolower($this->component->name),
				'table' => $table->name,
				'type_list' => $table->plural
				), true );
			$buffer .= $template->getBuffer();
		}
		
		return $buffer;
	}
}