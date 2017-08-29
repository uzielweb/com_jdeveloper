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
<div class="tab-pane <?php if (isset($this->active[1]) && $this->active[1] == "access") : ?>active<?php endif; ?>" id="formAccess">
	<table class="table" id="fieldListAccess">
		<thead>
			<tr>
				<th class="nowrap left">
					<?php echo JHtml::_('searchtools.sort', 'COM_JDEVELOPER_FIELD_FIELD_NAME_LABEL', 'a.name', $listDirn, $listOrder) ?>
				</th>
				<th class="nowrap left hidden-phone">
					<a><?php echo JText::_('COM_JDEVELOPER_FIELD_FIELD_DESCRIPTION_LABEL') ?></a>
				</th>
				<th class="nowrap left"><?php echo JText::_('COM_JDEVELOPER_COMPONENT_FIELD_EDIT_LABEL'); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($model_form->getTable()->getTree($this->item->form_id_access) as $field) : ?>
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
						<?php if ($field->tag != "action") : ?>
						<div class="pull-right">
							<a href="<?php echo JRoute::_("index.php?option=com_jdeveloper&task=form.add&tag=action&relation=component." . $this->item->id . ".access&parent_id=" . $field->id); ?>"class="btn btn-small btn-success" style="width:65px; margin:1px;">
								<i class="icon-new"></i> Action
							</a>
						</div>
						<?php endif; ?>
						</td>
					<td><?php echo $field->description ?></td>						
					<td>
						<a href="<?php echo JRoute::_("index.php?option=com_jdeveloper&task=form.delete&id=" . $field->id); ?>" style="color:#ff0000;"><i class="icon-delete"></i></a>
					</td>
					</tr>
				<?php endif; ?>
			<?php endforeach; ?>
			</tbody>
	</table>
	<a href="<?php echo JRoute::_("index.php?option=com_jdeveloper&task=form.add&tag=section&relation=component." . $this->item->id . ".access&parent_id=" . $this->item->form_id_access); ?>"class="btn btn-small btn-success" style="width:65px; margin:1px;">
		<i class="icon-new"></i> <?php echo JText::_("COM_JDEVELOPER_FORM_ADD_SECTION"); ?>
	</a>
</div>
