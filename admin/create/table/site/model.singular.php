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
class JDeveloperCreateTableSiteModelSingular extends JDeveloperCreateTable
{		
	/**
	 * The template file
	 *
	 * @var	string
	 */
	protected $templateFile = "site.models.singular.php";

	/**
	 * Check whether file should be created or not
	 *
	 * @return	boolean
	 */
	protected function condition()
	{
		return $this->table->params['frontend_details'];
	}

	protected function initialize()
	{
		$this->template->addPlaceHolders(array(
			'getItem' => $this->_getItem()
		));

		return parent::initialize();
	}

	private function _getItem()
	{		
		if ($this->table->jfields["catid"])
		{
			$template = $this->loadSubtemplate('getitem_category.txt');
		}
		else
		{
			$template = $this->loadSubtemplate('getitem_default.txt');
		}
		$template->addAreas($this->getDefaultAreas());
		$template->addPlaceholders($this->getDefaultPlaceholders());
		return $template->getBuffer();
	}
}