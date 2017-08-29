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
JHtml::_('behavior.keepalive');
JHtml::_('formbehavior.chosen', 'select');
?>

<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'component.cancel' || document.formvalidator.isValid(document.id('component-form')))
		{
			Joomla.submitform(task, document.getElementById('component-form'));
		}
	}
</script>

<h2><?php echo !empty($this->item->display_name) ? $this->item->display_name : JText::_("COM_JDEVELOPER_COMPONENT_NEW"); ?></h2>
	
<form action="<?php JRoute::_('index.php?option=com_jdeveloper&id=' . (int)$this->item->id); ?>" method="post" name="adminForm" id="component-form" class="form-validate form-horizontal">

<div id="j-main-container" class="span12">
	<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'general')); ?>
		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'general', JText::_('COM_JDEVELOPER_COMPONENT_COMPONENT'), true); ?>
	<div class="row-fluid">
		<div class="span4">
			<h4><?php echo JText::_('COM_JDEVELOPER_COMPONENT_FIELDSET_REQUIRED'); ?></h4>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('name'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('name'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('version'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('version'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('site'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('site'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('display_name'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('display_name'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('description'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('description'); ?></div>
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
</div>
</form>