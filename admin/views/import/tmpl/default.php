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

$active = JFactory::getApplication()->input->get("active", "component");

?>
<?php if (!empty( $this->sidebar)) : ?>
<div id="j-sidebar-container" class="span2">
	<?php echo $this->sidebar; ?>
</div>
<div id="j-main-container" class="span10">
<?php else : ?>
<div id="j-main-container">	
<?php endif;?>
	<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => $active)); ?>
		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'component', JText::_('COM_JDEVELOPER_IMPORT_FIELDSET_COMPONENT'), true); ?>
		<div class="well">
			<form action="<?php echo JRoute::_('index.php?option=com_jdeveloper&task=import.componentfrominstallation', false); ?>" method="post" class="form-validate form-horizontal" name="adminForm" id="adminForm"  enctype="multipart/form-data">
				<h4><?php echo JText::_("COM_JDEVELOPER_IMPORT_COMPONENT_FROM_INSTALLATION"); ?></h4>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('extension'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('extension'); ?></div>
				</div>
				<input type="submit" class="btn btn-primary" value="<?php echo JText::_("JTOOLBAR_IMPORT"); ?>" onclick="this.form.submit()" />
			</form>
		</div>
		<div class="well">
			<form action="<?php echo JRoute::_('index.php?option=com_jdeveloper&task=import.componentfrommanifest', false); ?>" method="post" class="form-validate form-horizontal" name="adminForm" id="adminForm"  enctype="multipart/form-data">
				<h4><?php echo JText::_("COM_JDEVELOPER_IMPORT_COMPONENT_FROM_MANIFEST"); ?></h4>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('manifest'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('manifest'); ?></div>
				</div>
				<input type="submit" class="btn btn-primary" value="<?php echo JText::_("JTOOLBAR_IMPORT"); ?>" onclick="this.form.submit()" />
			</form>
		</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>
	
		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'table', JText::_('COM_JDEVELOPER_IMPORT_FIELDSET_TABLE'), true); ?>
		<div class="well">
			<form action="<?php echo JRoute::_('index.php?option=com_jdeveloper&task=import.tablefromdb', false); ?>" method="post" class="form-validate form-horizontal">
				<h4><?php echo JText::_("COM_JDEVELOPER_IMPORT_TABLE_FROM_DATABASE"); ?></h4>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('component'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('component'); ?></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('dbtable'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('dbtable'); ?></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('table_plural'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('table_plural'); ?></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('table_singular'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('table_singular'); ?></div>
				</div>
				<input type="submit" class="btn btn-primary" value="<?php echo JText::_("JTOOLBAR_IMPORT"); ?>" onclick="this.form.submit()" />
			</form>
		</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>
	
		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'fields', JText::_('COM_JDEVELOPER_IMPORT_FIELDSET_FIELD'), true); ?>
		<div class="well">
			<form action="<?php echo JRoute::_('index.php?option=com_jdeveloper&task=import.fieldsfromform', false); ?>" method="post" class="form-validate form-horizontal" name="adminForm" id="adminForm"  enctype="multipart/form-data">
				<h4><?php echo JText::_("COM_JDEVELOPER_IMPORT_FIELDS_FROM_FORM"); ?></h4>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('table'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('table'); ?></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('formfile'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('formfile'); ?></div>
				</div>
				<input type="submit" class="btn btn-primary" value="<?php echo JText::_("JTOOLBAR_IMPORT"); ?>" onclick="this.form.submit()" />
			</form>
		</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>
	
	<?php echo JHtml::_('bootstrap.endTabSet'); ?>
	
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</div>
