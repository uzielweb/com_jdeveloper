<?php
/**
 * @package     JDeveloper
 * @subpackage  Models
 *
 * @copyright  	Copyright (C) 2014, Tilo-Lars Flasche. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;
JDeveloperLoader::import("models.admin");

/**
 * JDeveloper Formfield Model
 *
 * @package     JDeveloper
 * @subpackage  Models
 */
class JDeveloperModelFormfield extends JDeveloperModelAdmin
{
	/**
	 * Method to get a table object, load it if necessary.
	 *
	 * @param   string  $name     The table name. Optional.
	 * @param   string  $prefix   The class prefix. Optional.
	 * @param   array   $options  Configuration array for model. Optional.
	 *
	 * @return  JTable  A JTable object
	 *
	 * @since   12.2
	 * @throws  Exception
	 */
	public function getTable($type = 'Formfield', $prefix = 'JDeveloperTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}
	
	/**
	 * Prepare and sanitise the table data prior to saving.
	 *
	 * @param   JTable  $table  A reference to a JTable object.
	 *
	 * @return  void
	 *
	 * @since   12.2
	 */
	protected function prepareTable($table)
	{
		require_once JDeveloperLIB . "/template.php";
		
		$type = $table->get("type", "");
		$dir = JDeveloperTEMPLATES . "/fields/formfields/" . $type . ".php";

		if ($table->id == 0 && JFile::exists($dir))
		{
			$template = new JDeveloperTemplate($dir);
			
			$template->addAreas(array(
				"header" => false
			));
			
			$template->addPlaceholders(array(
				"name" => $table->name
			), true);
			
			$table->source = $template->getBuffer();
		}
	}
}