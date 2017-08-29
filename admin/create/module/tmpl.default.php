<?php
/**
 * @package     JDeveloper
 * @subpackage  Create.Module
 *
 * @copyright  	Copyright (C) 2014, Tilo-Lars Flasche. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;
JDeveloperLoader::import("module", JDeveloperCREATE);

/**
 * Module Create Class
 *
 * @package     JDeveloper
 * @subpackage  Create.Modue
 */
class JDeveloperCreateModuleTmplDefault extends JDeveloperCreateModule
{		
	/**
	 * The template file
	 *
	 * @var	string
	 */
	protected $templateFile = "tmpl.default.php";

	public function initialize()
	{
		$this->template->addPlaceHolders(
			array(
			'thead' => $this->thead(),
			'tbody' => $this->tbody()
			)
		);
		
		return parent::initialize();
	}

	private function thead()
	{		
		if (empty($this->item->table)) return '';
		
		$template = $this->loadSubtemplate('tablehead.txt');
		$buffer = '';
		
		$fields = JFactory::getDbo()->getTableColumns('#__' . $this->item->table);
		
		foreach ($fields as $column => $type)
		{
			if ($this->getModel("Field")->isCoreField($column)) continue;
			
			$template->addPlaceholders( $this->getDefaultPlaceholders(), true );
			$template->addPlaceholders( array('field' => $column), true );
			$buffer .= $template->getBuffer();
		}
		
		return $buffer;
	}

	private function tbody()
	{		
		if (empty($this->item->table)) return '';

		require_once JDeveloperLIB.DS.'table.php';
		
		$template = $this->loadSubtemplate('tablebody.txt');
		$buffer = '';
		$fields = JFactory::getDbo()->getTableColumns('#__' . $this->item->table);
		
		foreach ($fields as $column => $type)
		{
			if ($this->getModel("Field")->isCoreField($column)) continue;
			
			$template->addPlaceholders( $this->getDefaultPlaceholders(), true );
			$template->addPlaceholders( array('field' => $column), true );
			$buffer .= $template->getBuffer();
		}
		
		return $buffer;
	}
}