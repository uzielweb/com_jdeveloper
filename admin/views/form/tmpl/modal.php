<?php
/**
 * @author		Tilo-Lars Flasche
 * @copyright	Copyright (C) 2014, Tilo-Lars Flasche. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html
 */

defined("_JEXEC") or die("Restricted access");

// Include the HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
JHtml::_('formbehavior.chosen', 'select');

$app = JFactory::getApplication();
$input = $app->input;
$assoc = JLanguageAssociations::isEnabled();
?>
<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'form.cancel' || document.formvalidator.isValid(document.id('form-form')))
		{
			if (window.opener && (task == 'form.save' || task == 'form.cancel'))
			{
				window.opener.document.closeEditWindow = self;
				window.opener.setTimeout('window.document.closeEditWindow.close()', 1000);
			}

			Joomla.submitform(task, document.getElementById('form-form'));
		}
	}
</script>
<div class="container-popup"></div>

<div class="pull-right">
	<button class="btn btn-primary" type="button" onclick="Joomla.submitbutton('form.apply');"><?php echo JText::_('JTOOLBAR_APPLY') ?></button>
	<button class="btn btn-primary" type="button" onclick="Joomla.submitbutton('form.save');"><?php echo JText::_('JTOOLBAR_SAVE') ?></button>
	<button class="btn" type="button" onclick="Joomla.submitbutton('form.cancel');"><?php echo JText::_('JCANCEL') ?></button>
</div>

<div class="clearfix"> </div>
<hr class="hr-condensed" />

<form action="<?php echo JRoute::_('index.php?option=com_jdeveloper&layout=modal&tmpl=component&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="form-form" class="form-validate form-horizontal">
	<?php echo JLayoutHelper::render('joomla.edit.title_alias', $this); ?>

	<div class="form-horizontal">
		<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'details')); ?>

		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'details', empty($this->item->id) ? JText::_('COM_JDEVELOPER_NEW_NEWSFEED', true) : JText::_('COM_JDEVELOPER_EDIT_NEWSFEED', true)); ?>
		<div class="row-fluid">
			<div class="span9">
				<div class="form-vertical">
					<?php echo $this->form->getControlGroup('name'); ?>
				</div>
			</div>
			<div class="span3">
				<?php echo JLayoutHelper::render('joomla.edit.global', $this); ?>
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

		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'attrib-jbasic', JText::_('JGLOBAL_FIELDSET_DISPLAY_OPTIONS', true)); ?>
			<?php $this->fieldset = 'jbasic'; ?>
			<?php echo JLayoutHelper::render('joomla.edit.fieldset', $this); ?>
		<?php echo JHtml::_('bootstrap.endTab'); ?>

		<?php $this->set('ignore_fieldsets', array('jbasic')); ?>
		<?php echo JLayoutHelper::render('joomla.edit.params', $this); ?>

		<?php echo JHtml::_('bootstrap.endTabSet'); ?>
	</div>
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>
