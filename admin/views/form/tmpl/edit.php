<?php
/**
 * @author		Tilo-Lars Flasche
 * @copyright	Copyright (C) 2014, Tilo-Lars Flasche. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html
 */

defined("_JEXEC") or die("Restricted access");

// necessary libraries
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');

$input = JFactory::getApplication()->input;

$selector = "#jform_options";

JFactory::getDocument()->addScriptDeclaration("
(function($){
	$(document).ready(function ()
	{
		// Method to add tags pressing enter
		$('" . $selector . "_chzn input').keyup(function(event)
		{
			// Enter pressed
			if (event.which === 13 || event.which === 188)
			{				
				var option = $('<option>');
				option.text(this.value).val(this.value);
				option.attr('selected','selected');

				// Append the option an repopulate the chosen field
				$('" . $selector . "').append(option);

				this.value = '';
				$('" . $selector . "').trigger('liszt:updated');
			}
		});
	});
})(jQuery);
");
?>

<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'form.cancel' || document.formvalidator.isValid(document.id('form-form')))
		{
			Joomla.submitform(task, document.getElementById('form-form'));
		}
	}
</script>

<form action="<?php JRoute::_('index.php?option=com_jdeveloper&id=' . (int)$this->item->id); ?>" method="post" name="adminForm" id="form-form" class="form-validate">

	<?php if ($input->get("id", 0) == 0 && $input->get("parent_id", 0) < 2) : ?>
		<h2><?php echo JText::_("COM_JDEVELOPER_FORM_NEW_FORM"); ?></h2>
	<?php elseif ($input->get("id", 0) != 0 && $input->get("parent_id", 0) < 2) : ?>
		<h2><?php echo JText::_("COM_JDEVELOPER_FORM_EDIT_FORM"); ?></h2>
	<?php elseif ($input->get("id", 0) == 0 && $input->get("parent_id", 0) > 1) : ?>
		<h2><?php echo JText::_("COM_JDEVELOPER_FORM_NEW_FIELD"); ?></h2>
	<?php elseif ($input->get("id", 0) != 0 && $input->get("parent_id", 0) > 1) : ?>
		<h2><?php echo JText::_("COM_JDEVELOPER_FORM_EDIT_FIELD"); ?></h2>
	<?php endif; ?>
	
	<div class="form-inline form-inline-header">
		<div class="control-group">
			<div class="control-label"><?php echo $this->form->getLabel('name'); ?></div>
			<div class="controls"><?php echo $this->form->getInput('name'); ?></div>
		</div>
	</div>
	
	<div class="form-horizontal">
	<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'details')); ?>
		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'details', 'Form', $this->item->id, true); ?>
		<div class="row-fluid">
			<?php echo $this->form->getInput('relation', '', $input->get("relation")); ?>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('tag'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('tag'); ?></div>
			</div>			
			<?php if ($input->get("parent_id", 0) > 0 || $input->get("id", 0) > 0) : ?>
			<div class="span4">
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('type'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('type'); ?></div>
				</div>			
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('label'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('label'); ?></div>
				</div>			
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('description'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('description'); ?></div>
				</div>			
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('default'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('default'); ?></div>
				</div>			
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('maxlength'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('maxlength'); ?></div>
				</div>			
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('class'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('class'); ?></div>
				</div>
			</div>
			<div class="span5">
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('validation'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('validation'); ?></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('filter'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('filter'); ?></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('disabled'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('disabled'); ?></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('readonly'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('readonly'); ?></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('required'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('required'); ?></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('options'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('options'); ?></div>
				</div>
			</div>
			<?php else : ?>
				<div class="span9">
				</div>
			<?php endif; ?>
			<div class="span3">
				<?php echo JLayoutHelper::render('joomla.edit.global', $this); ?>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('lft'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('lft'); ?></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('rgt'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('rgt'); ?></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('level'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('level'); ?></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('path'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('path'); ?></div>
				</div>
			</div>
		</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>

		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'publishing', JText::_('JGLOBAL_FIELDSET_PUBLISHING', true)); ?>
		<div class="row-fluid form-horizontal-desktop">
			<div class="span6">
				<?php echo JLayoutHelper::render('joomla.edit.publishingdata', $this); ?>
			</div>
			<div class="span6">
				<?php echo JLayoutHelper::render('joomla.edit.metadata', $this); ?>
			</div>
		</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>
		
		<?php echo JLayoutHelper::render('joomla.edit.params', $this); ?>
		
	<?php echo JHtml::_('bootstrap.endTabSet'); ?>
	</div>
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>