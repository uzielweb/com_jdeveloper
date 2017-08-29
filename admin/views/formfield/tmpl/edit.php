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
?>

<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'formfield.cancel' || document.formvalidator.isValid(document.id('formfield-form')))
		{
			Joomla.submitform(task, document.getElementById('formfield-form'));
		}
	}
</script>

<form action="<?php JRoute::_('index.php?option=com_jdeveloper&id=' . (int) $this->item->name); ?>" method="post" name="adminForm" id="formfield-form" class="form-validate form-horizontal">
	<div class="form-inline form-inline-header">
		<div class="control-group">
			<div class="control-label"><?php echo $this->form->getLabel('name'); ?></div>
			<div class="controls"><?php echo $this->form->getInput('name'); ?></div>
		</div>
		<div class="control-group" style="<?php if ($this->item->id) : ?>display:none<?php endif; ?>">
			<div class="control-label"><?php echo $this->form->getLabel('type'); ?></div>
			<div class="controls"><?php echo $this->form->getInput('type'); ?></div>
		</div>
	</div>
	<?php if ($this->item->get('name', 0)) : ?>
	<div class="row-fluid">
		<div class="span6">
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('source'); ?></div>
			</div>
			<?php echo $this->form->getInput('source'); ?>
		</div>
		<div class="span6">
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('xml'); ?></div>
			</div>
			<?php echo $this->form->getInput('xml'); ?>
		</div>
	</div>
	<?php endif; ?>	
	<div class="control-group" style="<?php if ($this->item->id == 0) : ?>display:none<?php endif; ?>">
		<div class="control-label"><?php echo $this->form->getLabel('id'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('id'); ?></div>
	</div>
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>