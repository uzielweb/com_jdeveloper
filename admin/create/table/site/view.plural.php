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
class JDeveloperCreateTableSiteViewPlural extends JDeveloperCreateTable
{		
	/**
	 * The template file
	 *
	 * @var	string
	 */
	protected $templateFile = "site.views.plural.view.html.php";

	/**
	 * Check whether file should be created or not
	 *
	 * @return	boolean
	 */
	protected function condition()
	{
		return $this->table->params['frontend'];
	}
	
	protected function initialize()
	{
		$this->template->addAreas( array(
			'feed'	=> (bool) $this->table->params['frontend_feed'],
		));
		
		return parent::initialize();
	}
}