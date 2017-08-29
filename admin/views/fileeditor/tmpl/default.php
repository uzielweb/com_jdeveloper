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

<form action="<?php JRoute::_('index.php?option=com_jdeveloper&task=fileeditor.save'); ?>" method="post" name="adminForm" id="override-form" class="form-validate form-horizontal">
	<h3><?php echo JText::_("COM_JDEVELOPER_FILEEDITOR"); ?></h3>
	<table class="table table-striped">
		<tr>
			<td><?php echo $this->form->getLabel('client'); ?></td>
			<td><?php echo $this->form->getInput('client'); ?></td>
		</tr>
		<tr>
			<td><?php echo $this->form->getLabel('type'); ?></td>
			<td><?php echo $this->form->getInput('type'); ?></td>
		</tr>
		<tr>
			<td><?php echo $this->form->getLabel('name'); ?></td>
			<td><?php echo $this->form->getInput('name'); ?></td>
		</tr>
		<tr>
			<td><?php echo $this->form->getLabel('path'); ?></td>
			<td><?php echo $this->form->getInput('path'); ?></td>
		</tr>
		<tr>
			<td><?php echo $this->form->getLabel('filepath'); ?></td>
			<td><?php echo $this->form->getInput('filepath'); ?></td>
		</tr>
	</table>
	<h4><?php echo $this->form->getLabel('source'); ?></h4>
	<?php echo $this->form->getInput('source'); ?>
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>