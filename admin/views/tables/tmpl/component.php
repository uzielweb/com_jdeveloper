<?php
/**
 * @package     JDeveloper
 * @subpackage  Views
 *
 * @copyright  	Copyright (C) 2014, Tilo-Lars Flasche. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.framework');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.modal');
JHtml::_('behavior.multiselect');
JHtml::_('dropdown.init');
JHtml::_('formbehavior.chosen', 'select');

$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
$saveOrder	= $listOrder == 'a.ordering';

$components = JModelLegacy::getInstance("ComponentS", "JDeveloperModel")->getItems();
$component = JModelLegacy::getInstance("Component", "JDeveloperModel")->getItem($this->state->get('filter.component'));
$model_fields = JModelLegacy::getInstance("Fields", "JDeveloperModel");

if ($saveOrder)
{
	$saveOrderingUrl = 'index.php?option=com_jdeveloper&task=tables.saveOrderAjax&tmpl=component';
	JHtml::_('sortablelist.sortable', 'tableList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}
?>

<script type="text/javascript">
	Joomla.orderTable = function()
	{
		table = document.getElementById("sortTable");
		direction = document.getElementById("directionTable");
		order = table.options[table.selectedIndex].value;
		if (order != '<?php echo $listOrder; ?>')
		{
			dirn = 'asc';
		}
		else
		{
			dirn = direction.options[direction.selectedIndex].value;
		}
		Joomla.tableOrdering(order, dirn, '');
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_jdeveloper&view=tables'); ?>" method="post" name="adminForm" id="adminForm">

<?php if (!empty( $this->sidebar)) : ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
<?php else : ?>
	<div id="j-main-container">
<?php endif;?>
		<h2 style="font-size:26px;"><?php echo $component->display_name ?></h2>
		<a class="btn btn-success" href="<?php echo JRoute::_("index.php?option=com_jdeveloper&task=table.add&component=" . $component->id, false); ?>"><i class="icon-publish"></i> <?php echo JText::_("JTOOLBAR_ADD_TABLE"); ?></a>
		<a class="btn" href="<?php echo JRoute::_("index.php?option=com_jdeveloper&task=components.create&cid=$component->id", false); ?>"><i class="icon-publish"></i> <?php echo JText::_("JTOOLBAR_CREATE_ZIP"); ?></a>
		<a class="btn" href="<?php echo JRoute::_("index.php?option=com_jdeveloper&task=components.install&cid=$component->id", false); ?>"><i class="icon-publish"></i> <?php echo JText::_("JTOOLBAR_INSTALL"); ?></a>
		<a class="btn" href="<?php echo JRoute::_("index.php?option=com_jdeveloper&task=components.uninstall&cid=$component->id", false); ?>"><i class="icon-unpublish"></i> <?php echo JText::_("JTOOLBAR_UNINSTALL"); ?></a>
		<a class="btn btn-danger" href="<?php echo JRoute::_("index.php?option=com_jdeveloper&task=components.delete&cid=$component->id", false); ?>"><i class="icon-delete"></i> <?php echo JText::_("JTOOLBAR_DELETE"); ?></a>
		<button data-toggle="modal" data-target="#switchComponent" class="btn btn-info" style="float:right"><i class="icon-list"></i> <?php echo JText::_("COM_JDEVELOPER_COMPONENT_SWITCH"); ?></button>
		<p>&nbsp;</p>
		<div class="row-fluid">
			<h2><?php echo JText::_("COM_JDEVELOPER_COMPONENT_TABLES"); ?></h2>
			<ul class="nav nav-pills nav-justified">
			<?php foreach ($this->items as $i => $item) : ?>
				<li <?php if (!$i) : ?>class="active"<?php endif; ?>><a href="#table<?php echo $item->id; ?>" data-toggle="pill"><?php echo $item->name; ?></a></li>
			<?php endforeach; ?>
			</ul>
		</div>
		<div class="tab-content">
		<?php foreach ($this->items as $i => $item) : ?>
			<div class="tab-pane <?php if (!$i) : ?>active<?php endif; ?>" id="table<?php echo $item->id; ?>">
				<h3><i style="color:#999999"><?php echo $item->name; ?></i></h3>
				<table class="table table-striped table-bordered">
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
							<th class="nowrap left">
								<?php echo JHtml::_('searchtools.sort', 'COM_JDEVELOPER_FIELD_FIELD_ID_LABEL','a.id', $listDirn, $listOrder) ?>
							</th>
						</tr>
					</thead>
					<tbody>
					<?php foreach ($model_fields->getTableFields($item->id) as $i => $field) : ?>
						<tr>
							<td>
								<a href="<?php echo JRoute::_('index.php?option=com_jdeveloper&task=field.edit&id=' . $field->id); ?>"><?php echo $this->escape($field->name); ?></a>
							</td>
							<td>
								<?php if (!empty($field->jdeveloper_formfield_id)) : ?>
									<a href="<?php echo JRoute::_("index.php?option=com_jdeveloper&task=formfield.edit&id=" . $this->escape($field->jdeveloper_formfield_id)) ?>">
										<?php echo ucfirst($this->escape($field->type)); ?>
									</a>
								<?php else : ?>
									<?php echo ucfirst($this->escape($field->type)); ?>
								<?php endif; ?>
								<br><i style="color:grey; font-size:12px;"><?php echo $this->escape($field->dbtype) . '(' . $field->maxlength . ')'; ?>
							</td>
							<td>
								<?php if (!empty($field->jdeveloper_formrule_id)) : ?>
									<a href="<?php echo JRoute::_("index.php?option=com_jdeveloper&task=formrule.edit&id=" . $this->escape($field->jdeveloper_formrule_id)) ?>">
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
							<td><?php echo $this->escape($field->id); ?></td>
						</tr>
					<?php endforeach; ?>
					</tbody>
				</table>
				<a class="btn btn-warning" href="<?php echo JRoute::_("index.php?option=com_jdeveloper&task=field.add", false); ?>"><i class="icon-new"></i> <?php echo JText::_("JTOOLBAR_ADD_FIELD"); ?></a>			
			</div>
		<?php endforeach; ?>
		</div>
		<div class="modal hide fade" id="switchComponent" style="width:700px;">
			<div class="modal-header">
				<h3><?php echo JText::sprintf('COM_JDEVELOPER_COMPONENT_SWITCH');?></h3>
			</div>
			<div class="modal-body">
				<table class="table table-striped">
					<thead>
						<tr>
							<td><?php echo JText::_("COM_JDEVELOPER_COMPONENTS"); ?></td>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($components as $component) : ?>
						<tr>
							<td><a href="<?php echo JRoute::_("index.php?option=com_jdeveloper&view=tables&layout=component&filter[component]=" . $component->id, false); ?>"><?php echo $component->display_name; ?></a></td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
			<div class="modal-footer">
				<button class="btn" type="button" data-dismiss="modal">
					<?php echo JText::_('JTOOLBAR_CLOSE'); ?>
				</button>
			</div>
		</div>
		<div>
			<input type="hidden" name="task" value=" " />
			<input type="hidden" name="boxchecked" value="0" />
			<?php echo JHtml::_('form.token'); ?>
		</div>	
	</div>
</form>