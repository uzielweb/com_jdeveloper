<?php
/**
 * @package     JDeveloper
 * @subpackage  Views
 *
 * @copyright  	Copyright (C) 2014, Tilo-Lars Flasche. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.framework');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.modal');
JHtml::_('behavior.multiselect');
JHtml::_('dropdown.init');
JHtml::_('formbehavior.chosen', 'select');

$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
$components = JModelLegacy::getInstance("Components", "JDeveloperModel")->getItems();
$user = JFactory::getUser();
?>
<?php if ($user->authorise("core.create")) : ?>
	<a class="btn btn-success" href="<?php echo JRoute::_("index.php?option=com_jdeveloper&task=table.add&component=" . $this->item->id, false); ?>"><i class="icon-new"></i> <?php echo JText::_("JTOOLBAR_ADD_TABLE"); ?></a>
<?php endif; ?>

<?php if ($user->authorise("core.edit")) : ?>
	<a class="btn" href="<?php echo JRoute::_("index.php?option=com_jdeveloper&task=component.edit&id=" . $this->item->id, false); ?>"><i class="icon-edit"></i> <?php echo JText::_("JTOOLBAR_EDIT"); ?></a>
<?php endif; ?>

<?php if ($user->authorise("component.create")) : ?>
	<a class="btn" href="<?php echo JRoute::_("index.php?option=com_jdeveloper&task=components.create&cid=" . $this->item->id, false); ?>"><i class="icon-publish"></i> <?php echo JText::_("JTOOLBAR_CREATE_ZIP"); ?></a>
<?php endif; ?>

<?php if ($user->authorise("component.deletezip")) : ?>
	<a class="btn" href="<?php echo JRoute::_("index.php?option=com_jdeveloper&task=components.deletezip&cid=" . $this->item->id, false); ?>"><i class="icon-trash"></i> <?php echo JText::_("JTOOLBAR_DELETE_ZIP"); ?></a>
<?php endif; ?>

<?php if ($user->authorise("component.install")) : ?>
	<a class="btn" href="<?php echo JRoute::_("index.php?option=com_jdeveloper&task=components.install&cid=" . $this->item->id, false); ?>"><i class="icon-upload"></i> <?php echo JText::_("JTOOLBAR_INSTALL"); ?></a>
<?php endif; ?>

<?php if ($user->authorise("component.uninstall")) : ?>
	<?php if ($this->item->installed) : ?>
		<a class="btn" href="<?php echo JRoute::_("index.php?option=com_jdeveloper&task=components.uninstall&cid=" . $this->item->id, false); ?>"><i class="icon-unpublish"></i> <?php echo JText::_("JTOOLBAR_UNINSTALL"); ?></a>
	<?php endif; ?>
<?php endif; ?>

<?php if ($user->authorise("core.delete")) : ?>
	<button data-toggle="modal" data-target="#deleteComponent" class="btn btn-danger"><i class="icon-delete"></i> <?php echo JText::_("JTOOLBAR_DELETE"); ?></button>
<?php endif; ?>

<button data-toggle="modal" data-target="#switchComponent" class="btn btn-info" style="float:right"><i class="icon-list"></i> <?php echo JText::_("COM_JDEVELOPER_COMPONENT_SWITCH"); ?></button>