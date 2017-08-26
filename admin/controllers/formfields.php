<?php
/**
 * @package     JDeveloper
 * @subpackage  Controllers
 *
 * @copyright  	Copyright (C) 2014, Tilo-Lars Flasche. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;
JDeveloperLoader::import("controllers.list");

/**
 * JDeveloper Formfields Controller
 *
 * @package     JDeveloper
 * @subpackage  Controllers
 */
class JDeveloperControllerFormfields extends JDeveloperControllerList
{	
	/**
	 * Create form fields
	 */
	public function create()
	{		
		$app = JFactory::getApplication();
		$cid = $app->input->get("cid", array(), "array");
		$this->setRedirect(JRoute::_('index.php?option=com_jdeveloper&view=formfields', false));
		
		foreach ($cid as $id)
		{
			$item = $this->getModel()->getItem($id);
			echo print_r($item);
			JFile::write(JDeveloperARCHIVE . "/fields/" . strtolower($item->name) . ".php", $item->source);
		}
		
		$this->setMessage(JText::sprintf('COM_JDEVELOPER_FORMFIELDS_CREATED', count($cid)));
	}
}