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
class JDeveloperCreateTableAdminFields extends JDeveloperCreateTable
{
	/**
	 * Create field content
	 */
	public function create()
	{
		foreach ($this->getModel("fields")->getTableFields($this->table->id) as $field)
		{
			if (!empty($field->formfield_id))
			{
				$formfield = $this->getModel("formfield")->getItem($field->formfield_id);
				$this->setTemplate(
					new JDeveloperTemplate( preg_replace("/^[ ]*<\?php/", "<?php\n" . self::$templateHeader, $formfield->source), false)
				);
				$this->write($this->createDir . "/admin/models/fields/" . $formfield->name . ".php");
			}
		}
	}
	
	protected function getTemplate()
	{
		return null;
	}
}