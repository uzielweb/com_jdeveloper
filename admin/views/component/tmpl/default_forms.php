<?php
/**
 * @package     JDeveloper
 * @subpackage  Views
 *
 * @copyright  	Copyright (C) 2014, Tilo-Lars Flasche. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

$doc = JFactory::getDocument();
$input = JFactory::getApplication()->input;

$model_table = JModelLegacy::getInstance("Table", "JDeveloperModel");
$model_fields = JModelLegacy::getInstance("Fields", "JDeveloperModel");
$model_field = JModelLegacy::getInstance("Field", "JDeveloperModel");
$model_forms = JModelLegacy::getInstance("Forms", "JDeveloperModel");
$model_form = JModelLegacy::getInstance("Form", "JDeveloperModel");

$tables = $this->tables;

$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
?>
<h2><?php echo JText::_("COM_JDEVELOPER_COMPONENT_FORMS"); ?></h2>
<ul class="nav nav-pills nav-justified">
	<?php foreach ($tables as $i => $table) : ?>
		<li <?php if (isset($this->active[1]) && $table->id == $this->active[1]) : ?>class="active"<?php endif; ?>><a href="#form<?php echo $table->id; ?>" data-toggle="pill"><?php echo strtolower($table->singular); ?>.xml</a></li>
	<?php endforeach; ?>
	<li <?php if (isset($this->active[1]) && $this->active[1] == "config") : ?>class="active"<?php endif; ?>><a href="#formConfig" data-toggle="pill">config.xml</a></li>
	<li <?php if (isset($this->active[1]) && $this->active[1] == "access") : ?>class="active"<?php endif; ?>><a href="#formAccess" data-toggle="pill">access.xml</a></li>
</ul>
<div class="tab-content">
	<?php echo $this->loadTemplate("forms_access") ?>
	<?php echo $this->loadTemplate("forms_config") ?>
	<?php foreach ($tables as $i => $table) : ?>
	<div class="tab-pane <?php if (isset($this->active[1]) && $table->id == $this->active[1]) : ?>active<?php endif; ?>" id="form<?php echo $table->id; ?>">
		<table class="table" id="fieldList<?php echo $table->id ?>">
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
					<th class="nowrap left"><?php echo JText::_('COM_JDEVELOPER_COMPONENT_FIELD_EDIT_LABEL'); ?></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td class="nowrap has-context">
						<div class="pull-left">
							<span class="badge badge-warning">Fieldset : </span>
						</div>
						<div class="pull-right">						
							<a href="<?php echo JRoute::_("index.php?option=com_jdeveloper&task=form.add&tag=field&relation=table." . $table->id . ".form&parent_id=" . $table->form_id); ?>"class="btn btn-small btn-success" style="width:65px; margin:1px;">
								<i class="icon-new"></i> Field
							</a>
						</div>
					</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td class="nowrap has-context">
						<div class="pull-left">
							<?php echo str_repeat('<span class="gi" style="color:#ffffff;">|&mdash;</span>', 2) ?>
							<span class="badge badge-warning">Field : </span>
							<?php echo $table->pk; ?>
						</div>
					<td>Text</td>
					<td></td>
					<td><?php echo $table->pk; ?></td>
					<td><?php echo $table->pk; ?></td>
					<td></td>
				</tr>
				<?php foreach ($model_fields->getTableFields($table->id) as $i => $field) : ?>
				<tr>
					<td class="nowrap has-context">
						<div class="pull-left">
							<?php echo str_repeat('<span class="gi" style="color:#ffffff;">|&mdash;</span>', 2) ?>
							<span class="badge badge-warning">Field : </span>
							<?php echo $this->escape($field->name); ?>
						</div>
					</td>
					<td>
						<?php if (!empty($field->formfield_id)) : ?>
							<a href="<?php echo JRoute::_("index.php?option=com_jdeveloper&task=formfield.edit&id=" . $this->escape($field->formfield_id)) ?>">
								<?php echo ucfirst($this->escape($field->type)); ?>
							</a>
						<?php else : ?>
							<?php echo ucfirst($this->escape($field->type)); ?>
						<?php endif; ?>
					</td>
					<td>
						<?php if (!empty($field->formrule_id)) : ?>
							<a href="<?php echo JRoute::_("index.php?option=com_jdeveloper&task=formrule.edit&id=" . $this->escape($field->formrule_id)) ?>">
								<?php echo ucfirst($this->escape($field->rule)); ?>
							</a>
						<?php else : ?>
							<?php echo ucfirst($this->escape($field->rule)); ?>
						<?php endif; ?>
					</td>
					<td><?php echo $this->escape($field->label); ?></td>
					<td>
						<?php
						if (strlen($this->escape($field->description)) > 50)
							echo substr($this->escape($field->description), 0, 50) . " ...";
						else
							echo $this->escape($field->description);
						?>
					</td>
					<td></td>
				</tr>
				<?php endforeach; ?>
				<?php if ($table->jfields['params']) : ?>
					<tr>
						<td class="nowrap has-context">
							<div class="pull-left">
								<span class="badge badge-warning">Fields : </span> Params
							</div>
						</td>
						<td></td>						
						<td></td>						
						<td>Params</td>						
						<td></td>						
						<td></td>						
					</tr>
					<tr>
						<td class="nowrap has-context">
							<div class="pull-left">
								<?php echo str_repeat('<span class="gi" style="color:#ffffff;">|&mdash;</span>', 2) ?>
								<span class="badge badge-warning">Fieldset : </span> Params
							</div>
						</td>
						<td></td>						
						<td></td>						
						<td>Params</td>						
						<td></td>						
						<td></td>						
					</tr>
				<?php endif; ?>
				<?php if ($table->jfields['images']) : ?>
					<tr>
						<td class="nowrap has-context">
							<div class="pull-left">
								<span class="badge badge-warning">Fields : </span> Images
							</div>
						</td>
						<td></td>						
						<td></td>						
						<td><?php echo JText::_("JGLOBAL_FIELDSET_IMAGE_OPTIONS"); ?></td>						
						<td></td>						
						<td></td>						
					</tr>
					<tr>
						<td class="nowrap has-context">
							<div class="pull-left">
								<?php echo str_repeat('<span class="gi" style="color:#ffffff;">|&mdash;</span>', 2) ?>
								<span class="badge badge-warning">Fieldset : </span> Images
							</div>
						</td>
						<td></td>						
						<td></td>						
						<td><?php echo JText::_("JGLOBAL_FIELDSET_IMAGE_OPTIONS"); ?></td>						
						<td></td>						
						<td></td>						
					</tr>
					<tr>
						<td class="nowrap has-context">
							<div class="pull-left">
								<?php echo str_repeat('<span class="gi" style="color:#ffffff;">|&mdash;</span>', 4) ?>
								<span class="badge badge-warning">Field : </span> image_first
							</div>
						</td>
						<td>Media</td>						
						<td></td>						
						<td>First Image</td>						
						<td></td>						
						<td></td>						
					</tr>
				<?php endif; ?>
				<?php if ($table->jfields['metadata']) : ?>
					<tr>
						<td class="nowrap has-context">
							<div class="pull-left">
								<span class="badge badge-warning">Fields : </span> Metadata
							</div>
						</td>
						<td></td>						
						<td></td>						
						<td><?php echo JText::_("JGLOBAL_FIELDSET_METADATA_OPTIONS"); ?></td>						
						<td></td>						
						<td></td>						
					</tr>
					<tr>
						<td class="nowrap has-context">
							<div class="pull-left">
								<?php echo str_repeat('<span class="gi" style="color:#ffffff;">|&mdash;</span>', 2) ?>
								<span class="badge badge-warning">Fieldset : </span> Metadata
							</div>
						</td>
						<td></td>						
						<td></td>						
						<td><?php echo JText::_("JGLOBAL_FIELDSET_METADATA_OPTIONS"); ?></td>						
						<td></td>						
						<td></td>						
					</tr>
					<tr>
						<td class="nowrap has-context">
							<div class="pull-left">
								<?php echo str_repeat('<span class="gi" style="color:#ffffff;">|&mdash;</span>', 4) ?>
								<span class="badge badge-warning">Field : </span> robots
							</div>
						</td>
						<td>List</td>						
						<td></td>						
						<td><?php echo JText::_("JFIELD_METADATA_ROBOTS_LABEL"); ?></td>						
						<td><?php echo JText::_("JFIELD_METADATA_ROBOTS_DESC"); ?></td>						
						<td></td>						
					</tr>
					<tr>
						<td class="nowrap has-context">
							<div class="pull-left">
								<?php echo str_repeat('<span class="gi" style="color:#ffffff;">|&mdash;</span>', 4) ?>
								<span class="badge badge-warning">Field : </span> rights
							</div>
						</td>
						<td>Text</td>						
						<td></td>
						<td><?php echo JText::_("JFIELD_METADATA_RIGHTS_LABEL"); ?></td>						
						<td><?php echo JText::_("JFIELD_METADATA_RIGHTS_DESC"); ?></td>						
						<td></td>						
					</tr>
				<?php endif; ?>
				<?php foreach ($model_form->getTable()->getTree($table->form_id) as $field) : ?>
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
								<a href="<?php echo JRoute::_("index.php?option=com_jdeveloper&task=form.add&tag=fields&relation=table." . $table->id . ".form&parent_id=" . $field->id); ?>"class="btn btn-small btn-success" style="width:65px; margin:1px;">
									<i class="icon-new"></i> Fields
								</a>
								<a href="<?php echo JRoute::_("index.php?option=com_jdeveloper&task=form.add&tag=fieldset&relation=table." . $table->id . ".form&parent_id=" . $field->id); ?>"class="btn btn-small btn-success" style="width:65px; margin:1px;">
									<i class="icon-new"></i> Fieldset
								</a>
								<?php endif; ?>
								<?php if ($field->tag == "fieldset") : ?>
								<a href="<?php echo JRoute::_("index.php?option=com_jdeveloper&task=form.add&tag=field&relation=table." . $table->id . ".form&parent_id=" . $field->id); ?>"class="btn btn-small btn-success" style="width:65px; margin:1px;">
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
		<a href="<?php echo JRoute::_("index.php?option=com_jdeveloper&task=form.add&tag=fields&relation=table." . $table->id . ".form&parent_id=" . $table->form_id); ?>"class="btn btn-small btn-success" style="width:65px; margin:1px;">
			<i class="icon-new"></i> Fields
		</a>
	</div>
	<?php endforeach; ?>
</div>
