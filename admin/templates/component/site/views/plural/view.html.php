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
 * ##Plural## list view class.
 *
 * @package     ##Component##
 * @subpackage  Views
 */
class ##Component##View##Plural## extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;
	protected $toolbar;

	public function display($tpl = null)
	{
		$app = JFactory::getApplication();
		
		$this->items 		 = $this->get('Items');
		$this->state 		 = $this->get('State');
		$this->pagination 	 = $this->get('Pagination');
		$this->user		 	 = JFactory::getUser();
		$this->filterForm    = $this->get('FilterForm');
		$this->activeFilters = $this->get('ActiveFilters');
		
		
		$active = $app->getMenu()->getActive();
		if ($active)
		{
			$this->params = $active->params;
		}
		else
		{
			$this->params = new JRegistry();
		}##{start_feed}##
		
		// Add feed links
		$attribs = array('type' => 'application/rss+xml', 'title' => 'RSS 2.0');
		$this->document->addHeadLink(JRoute::_('&format=feed&type=rss'), 'alternate', 'rel', $attribs);
		$attribs = array('type' => 'application/atom+xml', 'title' => 'Atom 1.0');
		$this->document->addHeadLink(JRoute::_('&format=feed&type=atom'), 'alternate', 'rel', $attribs);##{end_feed}##
		
		// Prepare the data.
		foreach ($this->items as $item)
		{##{start_alias}##
			$item->slug	= $item->alias ? ($item->##pk##.':'.$item->alias) : $item->##pk##;
##{end_alias}####{start_params}##
			$temp = new JRegistry;
			$temp->loadString($item->params);##{end_params}##
				
			$active = $app->getMenu()->getActive();
			$item->params = clone($this->params);##{start_params}##
			$item->params->merge($temp);##{end_params}##
		}

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors));
			return false;
		}
		
		parent::display($tpl);
	}
}
?>