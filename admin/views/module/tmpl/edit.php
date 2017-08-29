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
		if (task == 'module.cancel' || document.formvalidator.isValid(document.id('module-form')))
		{
			Joomla.submitform(task, document.getElementById('module-form'));
		}
	}
</script>

<form action="<?php JRoute::_('index.php?option=com_jdeveloper'); ?>" method="post" name="adminForm" id="module-form" class="form-validate form-horizontal">
<div class="row-fluid">

<div id="j-main-container" class="span12">
	<?php echo JHtml::_('bootstrap.startTabSet', 'module', array('active' => 'general')); ?>
		<?php echo JHtml::_('bootstrap.addTab', 'module', 'general', JText::_('COM_JDEVELOPER_MODULE_VIEW'), true); ?>
	<div class="row-fluid">
		<div class="span4">
			<h4><?php echo JText::_('COM_JDEVELOPER_MODULE_FIELDSET_LABEL'); ?></h4>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('name'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('name'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('display_name'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('display_name'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('version'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('version'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('description'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('description'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('table'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('table'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('id'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('id'); ?></div>
			</div>
		</div>
		<div class="span4">
			<h4><?php echo JText::_('COM_JDEVELOPER_COMPONENT_FIELDSET_PARAMS'); ?></h4>
			<?php foreach($this->form->getFieldset('params') as $field) : ?>
				<div class="control-group">
					<div class="control-label"><?php echo $field->label; ?></div>
					<div class="controls"><?php echo $field->input; ?></div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
		
		<?php echo JHtml::_('bootstrap.endTab'); ?>
	<?php echo JHtml::_('bootstrap.endTabSet'); ?>
	
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>