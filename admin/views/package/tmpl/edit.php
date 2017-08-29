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
		if (task == 'package.cancel' || document.formvalidator.isValid(document.id('package-form')))
		{
			Joomla.submitform(task, document.getElementById('package-form'));
		}
	}
</script>

<form action="<?php JRoute::_('index.php?option=com_jdeveloper'); ?>" method="post" name="adminForm" id="package-form" class="form-validate form-horizontal">

<div class="row-fluid">
<?php if (!empty( $this->sidebar)) : ?>
<div id="j-sidebar-container" class="span2">
	<?php echo $this->sidebar; ?>
</div>
<div id="j-main-container" class="span10">
<?php else : ?>
	<div id="j-main-container">
<?php endif;?>

	<?php echo JHtml::_('bootstrap.startTabSet', 'package', array('active' => 'general')); ?>
		<?php echo JHtml::_('bootstrap.addTab', 'package', 'general', JText::_('COM_JDEVELOPER_PACKAGE_VIEW'), true); ?>
	<div class="row-fluid">
		<div class="span4">
			<h4><?php echo JText::_('COM_JDEVELOPER_PACKAGE_FIELDSET_LABEL'); ?></h4>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('name'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('name'); ?></div>
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
				<div class="control-label"><?php echo $this->form->getLabel('files'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('files'); ?></div>
			</div>
		</div>
	</div>
		
		<?php echo JHtml::_('bootstrap.endTab'); ?>
	<?php echo JHtml::_('bootstrap.endTabSet'); ?>
	
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>