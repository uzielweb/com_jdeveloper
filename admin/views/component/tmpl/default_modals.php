<?php
/**
 * @package     JDeveloper
 * @subpackage  Views
 *
 * @copyright  	Copyright (C) 2014, Tilo-Lars Flasche. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

$components = JModelLegacy::getInstance("Components", "JDeveloperModel")->getItems();
$tables = JModelLegacy::getInstance("Tables", "JDeveloperModel")->getComponentTables($this->item->id);
?>
<div class="modal hide fade" id="deleteComponent" style="width:400px;">
	<div class="modal-header">
		<h3><?php echo JText::sprintf('COM_JDEVELOPER_COMPONENT_DELETE');?></h3>
	</div>
	<div class="modal-body">
		<?php echo JText::sprintf('COM_JDEVELOPER_COMPONENT_DELETE_DESC');?>
		<br>
		<?php echo JHtml::_('grid.id', 0, $this->item->id); ?> <?php echo JText::_("YES")?>
	</div>
	<div class="modal-footer">
		<button onclick="if (document.adminForm.boxchecked.value==0){alert('<?php echo JText::_("COM_JDEVELOPER_COMPONENT_DELETE_CONFIRM") ?>');}else{ Joomla.submitbutton('components.delete')}" class="btn btn-danger">
			<span class="icon-delete"></span> <?php echo JText::_('JTOOLBAR_DELETE'); ?>
		</button>
		<button class="btn" type="button" data-dismiss="modal">
			<?php echo JText::_('JTOOLBAR_CANCEL'); ?>
		</button>
	</div>
</div>
<?php foreach ($tables as $table) : ?>
<div class="modal hide fade" id="deleteTable<?php echo $table->id; ?>" style="width:400px;">
	<div class="modal-header">
		<h3><?php echo JText::sprintf('COM_JDEVELOPER_TABLE_DELETE');?></h3>
	</div>
	<div class="modal-body">
		<?php echo JText::sprintf('COM_JDEVELOPER_TABLE_DELETE_DESC');?>
		<br>
		<?php echo JHtml::_('grid.id', 0, $table->id); ?> <?php echo JText::_("YES")?>
	</div>
	<div class="modal-footer">
		<button onclick="if (document.adminForm.boxchecked.value==0){alert('<?php echo JText::_("COM_JDEVELOPER_TABLE_DELETE_CONFIRM") ?>');}else{ Joomla.submitbutton('tables.delete')}" class="btn btn-danger">
			<span class="icon-delete"></span> <?php echo JText::_('JTOOLBAR_DELETE'); ?>
		</button>
		<button class="btn" type="button" data-dismiss="modal">
			<?php echo JText::_('JTOOLBAR_CANCEL'); ?>
		</button>
	</div>
</div>
<?php endforeach; ?>