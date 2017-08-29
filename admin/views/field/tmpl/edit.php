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
		if (task == 'field.cancel' || document.formvalidator.isValid(document.id('field-form')))
		{
			Joomla.submitform(task, document.getElementById('field-form'));
		}
	}
</script>

<h2><?php echo !empty($this->item->name) ? $this->item->name : JText::_("COM_JDEVELOPER_FIELD_NEW"); ?></h2>
<form action="<?php JRoute::_('index.php?option=com_jdeveloper&id=' . (int)$this->item->id); ?>" method="post" name="adminForm" id="field-form" class="form-validate form-horizontal">
<div class="row-fluid">
<div id="j-main-container" class="span12">

	<?php echo JHtml::_('bootstrap.startTabSet', 'field', array('active' => 'general')); ?>
		<?php echo JHtml::_('bootstrap.addTab', 'field', 'general', (int)$this->item->id ? ucfirst($this->item->name) : JText::_('COM_JDEVELOPER_FIELD_NEW'), true); ?>
	<div class="row-fluid">
		<div class="span4">
			<h4><?php echo JText::_('COM_JDEVELOPER_FIELD_FIELDSET_DATABASE_LABEL'); ?></h4>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('component_name'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('component_name'); ?></div>
			</div>		
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('table'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('table', null, $input->get('table', $this->item->table, 'int')); ?></div>
			</div>		
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('name'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('name'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('type'); ?></div>
				<div class="controls">
					<?php echo $this->form->getInput('type'); ?><br>
					<span class="label label-info">
						<a href="http://docs.joomla.org/Standard_form_field_types" target="_blank" style="color:#fff;"><?php echo JText::_('COM_JDEVELOPER_FIELD_FIELD_TYPE_INFO') ?></a>
					</span>
					<span class="label label-warning">
						<a href="<?php echo JRoute::_("index.php?option=com_jdeveloper&view=formfields"); ?>" target="_blank" style="color:#fff;"><?php echo JText::_('COM_JDEVELOPER_FIELD_FIELD_TYPE_CREATE'); ?></a>
					</span>
				</div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('dbtype'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('dbtype'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('maxlength'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('maxlength'); ?></div>
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
				<div class="control-label"><?php echo $this->form->getLabel('id'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('id'); ?></div>
			</div>
		</div>
		<div class="span4">
			<h4><?php echo JText::_('COM_JDEVELOPER_FIELD_FIELDSET_FORM_LABEL'); ?></h4>			
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('rule'); ?></div>
				<div class="controls">
					<?php echo $this->form->getInput('rule'); ?><br>
					<span class="label label-info">
						<a href="http://docs.joomla.org/Server-side_form_validation" target="_blank" style="color:#fff;"><?php echo JText::_('COM_JDEVELOPER_FIELD_FIELD_RULE_INFO') ?></a>
					</span>
					<span class="label label-warning">
						<a href="<?php echo JRoute::_("index.php?option=com_jdeveloper&view=formrules"); ?>" target="_blank" style="color:#fff;"><?php echo JText::_('COM_JDEVELOPER_FIELD_FIELD_RULE_CREATE'); ?></a>
					</span>
				</div>
			</div>
			<?php foreach($this->form->getFieldset('field_params') as $field) : ?>
				<div class="control-group" id="<?php echo $field->name; ?>">
					<div class="control-label"><?php echo $field->label; ?></div>
					<div class="controls"><?php echo $field->input; ?></div>
				</div>
			<?php endforeach; ?>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('options'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('options'); ?></div>
			</div>
		</div>
		<div class="span4">
			<h4><?php echo JText::_('COM_JDEVELOPER_FIELD_FIELDSET_VIEW_LABEL'); ?></h4>			
			<?php foreach($this->form->getFieldset('listview_params') as $field) : ?>
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