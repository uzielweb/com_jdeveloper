<?php
/**
 * @package     JDeveloper
 * @subpackage  Views
 *
 * @copyright  	Copyright (C) 2014, Tilo-Lars Flasche. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

$model_forms = JModelLegacy::getInstance("Forms", "JDeveloperModel");
$model_form = JModelLegacy::getInstance("Form", "JDeveloperModel");

$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
?>
<h2><?php echo JText::_("COM_JDEVELOPER_PLUGIN_FORMS"); ?></h2>
<ul class="nav nav-pills nav-justified">
	<li class="active"><a href="#formConfig" data-toggle="pill">config.xml</a></li>
</ul>
<div class="tab-content">
	<div class="tab-pane active" id="formConfig">
		<table class="table" id="fieldListConfig">
			<thead>
				<tr>
					<th class="nowrap left">
						<?php echo JHtml::_('searchtools.sort', 'COM_JDEVELOPER_FIELD_FIELD_NAME_LABEL', 'a.name', $listDirn, $listOrder) ?>
					</th>
					<th class="nowrap left">
						<?php echo JHtml::_('searchtools.sort', 'COM_JDEVELOPER_FIELD_FIELD_TYPE_LABEL', 'a.type', $listDirn, $listOrder) ?>
					</th>
					<th class="nowrap left">
						<?php echo JHtml::_('searchtools.sort', 'COM_JDEVELOPER_FIELD_FIELD_RULE_LABEL', 'a.rule', $listDirn, $listOrder) ?>
					</th>
					<th class="nowrap left hidden-phone">
						<?php echo JHtml::_('searchtools.sort', 'COM_JDEVELOPER_FIELD_FIELD_LABEL_LABEL', 'a.label', $listDirn, $listOrder) ?>
					</th>
					<th class="nowrap left hidden-phone">
						<a><?php echo JText::_('COM_JDEVELOPER_FIELD_FIELD_DESCRIPTION_LABEL') ?></a>
					</th>
					<th class="nowrap left"><?php echo JText::_('COM_JDEVELOPER_PLUGIN_FIELD_EDIT_LABEL'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($model_form->getTable()->getTree($this->item->form_id) as $field) : ?>
					<?php if ($field->level != "1") : ?>
					<tr>
						<td class="nowrap has-context">
							<div class="pull-left">
								<?php echo str_repeat('<span class="gi" style="color:#ffffff;">|&mdash;</span>', ($field->level - 2) * 2) ?>
								<span class="badge badge-warning"><?php echo ucfirst($field->tag); ?> : </span> 
								<a href="<?php echo JRoute::_('index.php?option=com_jdeveloper&task=form.edit&id='.(int) $field->id); ?>">
									<?php echo $this->escape($field->name); ?>
								</a>
							</div>
							<?php if ($field->tag != "field") : ?>
							<div class="pull-right">
								<?php if ($field->tag == "fields") : ?>
								<a href="<?php echo JRoute::_("index.php?option=com_jdeveloper&task=form.add&tag=fields&relation=plugin." . $this->item->id . ".config&parent_id=" . $field->id); ?>"class="btn btn-small btn-success" style="width:65px; margin:1px;">
									<i class="icon-new"></i> Fields
								</a>
								<a href="<?php echo JRoute::_("index.php?option=com_jdeveloper&task=form.add&tag=fieldset&relation=plugin." . $this->item->id . ".config&parent_id=" . $field->id); ?>"class="btn btn-small btn-success" style="width:65px; margin:1px;">
									<i class="icon-new"></i> Fieldset
								</a>
								<?php endif; ?>
								<?php if ($field->tag == "fieldset") : ?>
								<a href="<?php echo JRoute::_("index.php?option=com_jdeveloper&task=form.add&tag=field&relation=plugin." . $this->item->id . ".config&parent_id=" . $field->id); ?>"class="btn btn-small btn-success" style="width:65px; margin:1px;">
									<i class="icon-new"></i> Field
								</a>
								<?php endif; ?>
							</div>
							<?php endif; ?>
						</td>
						<td><?php echo $field->type ?></td>						
						<td><?php echo $field->validation; ?></td>
						<td><?php echo $field->label ?></td>						
						<td><?php echo $field->description ?></td>						
						<td>
							<a href="<?php echo JRoute::_("index.php?option=com_jdeveloper&task=form.delete&id=" . $field->id); ?>" style="color:#ff0000;"><i class="icon-delete"></i></a>
						</td>
						</tr>
					<?php endif; ?>
				<?php endforeach; ?>
				</tbody>
		</table>
		<a href="<?php echo JRoute::_("index.php?option=com_jdeveloper&task=form.add&tag=fields&relation=plugin." . $this->item->id . ".config&parent_id=" . $this->item->form_id); ?>"class="btn btn-small btn-success" style="width:65px; margin:1px;">
			<i class="icon-new"></i> Fields
		</a>
		<a href="<?php echo JRoute::_("index.php?option=com_jdeveloper&task=form.add&tag=field&relation=plugin." . $this->item->id . ".config&parent_id=" . $this->item->form_id); ?>"class="btn btn-small btn-success" style="width:65px; margin:1px;">
			<i class="icon-new"></i> Field
		</a>
	</div>
</div>