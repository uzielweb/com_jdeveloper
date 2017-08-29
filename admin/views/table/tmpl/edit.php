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
		if (task == 'table.cancel' || document.formvalidator.isValid(document.id('table-form')))
		{
			Joomla.submitform(task, document.getElementById('table-form'));
		}
	}
</script>

<h2><?php echo !empty($this->item->name) ? "#__" . $this->item->dbname : JText::_("COM_JDEVELOPER_TABLE_NEW"); ?></h2>
<form action="<?php JRoute::_('index.php?option=com_jdeveloper&id=' . (int)$this->item->id); ?>" method="post" name="adminForm" id="table-form" class="form-validate form-horizontal">
<div class="row-fluid">

<div id="j-main-container" class="span12">
	<?php echo JHtml::_('bootstrap.startTabSet', 'table', array('active' => 'general')); ?>
		<?php echo JHtml::_('bootstrap.addTab', 'table', 'general', JText::_('COM_JDEVELOPER_VIEW_TABLE'), true); ?>
	<div class="row-fluid">
		<div class="span4">
			<h4><?php echo JText::_('COM_JDEVELOPER_TABLE_FIELDSET_REQUIRED'); ?></h4>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('name'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('name'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('type'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('type'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('component'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('component', null, $input->get('component', $this->item->component, 'int')); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('frontend', 'params'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('frontend', 'params'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('plural'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('plural'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('singular'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('singular'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('pk'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('pk'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('relations', 'params'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('relations', 'params'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('id'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('id'); ?></div>
			</div>
		</div>
		<div class="span4">
			<h4><?php echo JText::_('COM_JDEVELOPER_TABLE_FIELDSET_VIEWS'); ?></h4>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('frontend_categories', 'params'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('frontend_categories', 'params'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('frontend_edit', 'params'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('frontend_edit', 'params'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('frontend_details', 'params'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('frontend_details', 'params'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('frontend_feed', 'params'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('frontend_feed', 'params'); ?></div>
			</div>
		</div>
	</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>
		<?php echo JHtml::_('bootstrap.addTab', 'table', 'corefunctions', JText::_('COM_JDEVELOPER_TABLE_FIELDSET_COREFUNCTIONS'), true); ?>
	<div class="row-fluid">
		<h4><?php echo JText::_('COM_JDEVELOPER_TABLE_FIELDSET_JOOMLA_DB_PATTERN'); ?></h4>
			<div class="span4">
			<?php 
				$i = 0;
				$count = count($this->form->getFieldset('jfields'));
				foreach($this->form->getFieldset('jfields') as $field) : 
				
				if ($i > $count / 3)
				{
					echo "</div><div class=\"span4\">";
					$i = 0;
				}
			?>
				<div class="control-group">
					<div class="control-label"><?php echo $field->label; ?></div>
					<div class="controls"><?php echo $field->input; ?></div>
				</div>
			<?php
				$i++;
				endforeach;
			?>
		</div>
	</div>
		
		<?php echo JHtml::_('bootstrap.endTab'); ?>
	<?php echo JHtml::_('bootstrap.endTabSet'); ?>
	
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>