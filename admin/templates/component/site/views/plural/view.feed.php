<?php##{start_header}##
/**
 * @package     JDeveloper
 * @subpackage  Templates.Component
 *
 * @copyright  	Copyright (C) 2014, Tilo-Lars Flasche. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;##{end_header}##
##Header##

/**
 * ##Plural## feed view class.
 *
 * @package     ##Component##
 * @subpackage  Views
 */
class ##Component##View##Plural## extends JViewLegacy
{
	public function display($tpl = null)
	{
		// Parameters
		$app       = JFactory::getApplication();
		$doc       = JFactory::getDocument();

		$doc->link	= JRoute::_('index.php?option=com_##component##&view=##singular##');
		$app->input->set('limit', $app->getCfg('feed_limit'));

		$rows = $this->get('Items');

		foreach ($rows as $row)
		{
			// strip html from feed item title
			$link = JRoute::_('index.php?option=com_##component##&view=##singular##&##pk##=' . $row->##pk##);
			$link = html_entity_decode($link, ENT_COMPAT, 'UTF-8');

			// Load individual item creator class
			$item				= new JFeedItem;
			$item->title		= $row->##mainfield##;
			$item->link			= $link;##{start_publish_up}##
			$item->date			= $row->publish_up;##{end_publish_up}####{start_created_by}####{start_created_by_alias}##
			$item->author 		= $row->created_by_alias ? $row->created_by_alias : $row->author;##{end_created_by_alias}####{end_created_by}####{start_created_by}####{!start_created_by_alias}##
			$item->author 		= $row->author;##{!end_created_by_alias}####{end_created_by}##

			// Load item description and add div
			$item->description	= $row->##textfield##;

			// Loads item info into rss array
			$doc->addItem($item);
		}
	}
}
