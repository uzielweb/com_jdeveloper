<?php
/**
 * @package     JDeveloper
 * @subpackage  Views
 *
 * @copyright  	Copyright (C) 2014, Tilo-Lars Flasche. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');

$input = JFactory::getApplication()->input;
?>

<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'override.cancel' || document.formvalidator.isValid(document.id('override-form')))
		{
			Joomla.submitform(task, document.getElementById('override-form'));
		}
	}
</script>

<form action="<?php JRoute::_('index.php?option=com_jdeveloper&id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="override-form" class="form-validate form-horizontal">
	<h3><?php echo JText::_("COM_JDEVELOPER_OVERRIDE"); ?></h3>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('id'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('id'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('type'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('type'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('name'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('name'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('item_id'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('item_id'); ?></div>
	</div>
	<h4><?php echo $this->form->getLabel('source'); ?></h4>
	<?php echo $this->form->getInput('source'); ?>
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>