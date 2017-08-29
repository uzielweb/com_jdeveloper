<?php
/**
 * @package     JDeveloper
 * @subpackage  Create.Table
 *
 * @copyright  	Copyright (C) 2014, Tilo-Lars Flasche. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;
JDeveloperLoader::import("table", JDeveloperCREATE);;

/**
 * Table Create Class
 *
 * @package     JDeveloper
 * @subpackage  Create.Table
 */
class JDeveloperCreateTableAdminViewPlural extends JDeveloperCreateTable
{		
	/**
	 * The template file
	 *
	 * @var	string
	 */
	protected $templateFile = "admin.views.plural.view.html.php";

	protected function initialize()
	{
		$this->template->addPlaceHolders(
			array(
			'ordering' => (!empty($table['ordering'])) ? "'ordering' => JText::_('JGRID_HEADING_ORDERING')," . PHP_EOL : "",
			'sort_fields' => $this->sortFields()
			)
		);
		
		$this->template->addPlaceHolders( array( 'table' => $this->table->name ), true );
		
		return parent::initialize();
	}
	
	private function sortFields()
	{
		$buffer = '';
		
		foreach ($this->fields as $field)
		{
			if ( ! (int) $field->get('sortable', 0) ) continue;
			$buffer .= "\n\t\t\t'a.". strtolower($field->name) ."' => JText::_('COM_". strtoupper($this->component->name) ."_". strtoupper($this->table->plural) ."_FIELD_". strtoupper($field->name) ."_LABEL'),";
		}
		
		return $buffer;
	}
}