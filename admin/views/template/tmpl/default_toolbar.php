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
$user = JFactory::getUser();
?>
<?php if ($user->authorise("core.edit")) : ?>
	<a class="btn" href="<?php echo JRoute::_("index.php?option=com_jdeveloper&task=template.edit&id=" . $this->item->id, false); ?>"><i class="icon-edit"></i> <?php echo JText::_("JTOOLBAR_EDIT"); ?></a>
<?php endif; ?>

<?php if ($user->authorise("template.create")) : ?>
	<a class="btn" href="<?php echo JRoute::_("index.php?option=com_jdeveloper&task=templates.create&cid=" . $this->item->id, false); ?>"><i class="icon-publish"></i> <?php echo JText::_("JTOOLBAR_CREATE_ZIP"); ?></a>
<?php endif; ?>

<?php if ($user->authorise("template.deletezip")) : ?>
	<a class="btn" href="<?php echo JRoute::_("index.php?option=com_jdeveloper&task=templates.deletezip&cid=" . $this->item->id, false); ?>"><i class="icon-trash"></i> <?php echo JText::_("JTOOLBAR_DELETE_ZIP"); ?></a>
<?php endif; ?>

<?php if ($user->authorise("template.install")) : ?>
	<a class="btn" href="<?php echo JRoute::_("index.php?option=com_jdeveloper&task=templates.install&cid=" . $this->item->id, false); ?>"><i class="icon-upload"></i> <?php echo JText::_("JTOOLBAR_INSTALL"); ?></a>
<?php endif; ?>

<?php if ($user->authorise("template.uninstall")) : ?>
	<a class="btn" href="<?php echo JRoute::_("index.php?option=com_jdeveloper&task=templates.uninstall&cid=" . $this->item->id, false); ?>"><i class="icon-unpublish"></i> <?php echo JText::_("JTOOLBAR_UNINSTALL"); ?></a>
<?php endif; ?>

<?php if ($user->authorise("core.delete")) : ?>
	<button data-toggle="modal" data-target="#deleteTemplate" class="btn btn-danger"><i class="icon-delete"></i> <?php echo JText::_("JTOOLBAR_DELETE"); ?></button>
<?php endif; ?>

<button data-toggle="modal" data-target="#switchTemplate" class="btn btn-info" style="float:right"><i class="icon-list"></i> <?php echo JText::_("COM_JDEVELOPER_TEMPLATE_SWITCH"); ?></button>