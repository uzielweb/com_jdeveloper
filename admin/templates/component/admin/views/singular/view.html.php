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

require_once JPATH_COMPONENT.'/helpers/##component##.php';

/**
 * ##Singular## item view class.
 *
 * @package     ##Component##
 * @subpackage  Views
 */
class ##Component##View##Singular## extends JViewLegacy
{
	protected $item;
	protected $form;
	protected $state;

	public function display($tpl = null)
	{
		JFactory::getApplication()->input->set('hidemainmenu', true);
		
		$this->form  = $this->getModel()->getForm();
		$this->item  = $this->getModel()->getItem();
		$this->state = $this->getModel()->getState();
		
		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors));
			return false;
		}
		
		if ($this->getLayout() == 'modal')
		{##{start_language}##
			$this->form->setFieldAttribute('language', 'readonly', 'true');##{end_language}####{start_catid}##
			$this->form->setFieldAttribute('catid', 'readonly', 'true');##{end_catid}##
		}

		$this->addToolbar();
		parent::display($tpl);
	}
	
	protected function addToolbar()
	{
		$user		= JFactory::getUser();
		$isNew		= ($this->item->id == 0);
		$canDo		= ##Component##Helper::getActions();
		
		JToolBarHelper::title(JText::_('COM_##COMPONENT##_##TABLE##_VIEW_##SINGULAR##_TITLE'));

		if (isset($this->item->checked_out)) {
		    $checkedOut	= !($this->item->checked_out == 0 || $this->item->checked_out == $user->get('id'));
        } else {
            $checkedOut = false;
        }
		
		// If not checked out, can save the item.
		if (!$checkedOut && ($canDo->get('core.edit')||($canDo->get('core.create'))))
		{

			JToolBarHelper::apply('##singular##.apply', 'JTOOLBAR_APPLY');
			JToolBarHelper::save('##singular##.save', 'JTOOLBAR_SAVE');
		}
		if (!$checkedOut && ($canDo->get('core.create'))){
			JToolBarHelper::custom('##singular##.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
		}
		// If an existing item, can save to a copy.
		if (!$isNew && $canDo->get('core.create')) {
			JToolBarHelper::custom('##singular##.save2copy', 'save-copy.png', 'save-copy_f2.png', 'JTOOLBAR_SAVE_AS_COPY', false);
		}
		if (empty($this->item->id)) {
			JToolBarHelper::cancel('##singular##.cancel', 'JTOOLBAR_CANCEL');
		}
		else {
			JToolBarHelper::cancel('##singular##.cancel', 'JTOOLBAR_CLOSE');
		}
	}
}
?>