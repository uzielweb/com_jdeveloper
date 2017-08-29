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
$components = JModelLegacy::getInstance("Components", "JDeveloperModel")->getItems();
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

<form action="<?php echo JRoute::_('index.php?option=com_jdeveloper&view=component'); ?>" method="post" name="adminForm" id="adminForm">
<?php if (!empty( $this->sidebar)) : ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
<?php else : ?>
	<div id="j-main-container">
<?php endif;?>
	<?php if (!empty($this->item->id)) : ?>
		<h2 style="font-size:26px;"><?php echo $this->item->display_name ?></h2>
		<?php echo $this->loadTemplate("toolbar") ?>
		<p>&nbsp;</p>
		<div class="row-fluid">
			<div class="span12">
		<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => $this->active[0])); ?>
			<!-- Tab info -->
			<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'general', JText::_('COM_JDEVELOPER_COMPONENT_COMPONENT'), true); ?>
				<?php echo $this->loadTemplate("info") ?>
			<?php echo JHtml::_('bootstrap.endTab'); ?>
			<!-- Tab tables -->
			<?php $class = count($this->tables) ? "badge-info" : ""; ?>
			<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'tables', JText::_('COM_JDEVELOPER_COMPONENT_TABLES') . " <span class=\"badge $class hasTooltip\" data-original-title=\"\">" . count($this->tables) . "</span>", false); ?>
				<?php if (!empty($this->tables)) : ?>
					<?php echo $this->loadTemplate("tables") ?>
				<?php else : ?>
				<div class="alert alert-no-items">
					<?php echo JText::_('COM_JDEVELOPER_COMPONENT_NO_TABLES'); ?>
				</div>		
				<?php endif; ?>
			<?php echo JHtml::_('bootstrap.endTab'); ?>
			<!-- Tab forms -->
			<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'forms', JText::_('COM_JDEVELOPER_COMPONENT_FORMS'), false); ?>
				<?php echo $this->loadTemplate("forms") ?>
			<?php echo JHtml::_('bootstrap.endTab'); ?>
			<!--  Tab overrides -->
			<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'overrides', JText::_('COM_JDEVELOPER_COMPONENT_OVERRIDES'), false); ?>
				<?php echo $this->loadTemplate("overrides") ?>
			<?php echo JHtml::_('bootstrap.endTab'); ?>
			<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'filemanager', JText::_('COM_JDEVELOPER_COMPONENT_FILEMANAGER'), false); ?>
				<?php if ($this->item->installed) : ?>
					<?php echo $this->loadTemplate("filemanager") ?>
				<?php else : ?>
					<div class="alert alert-info">
						<?php echo JText::_("COM_JDEVELOPER_MESSAGE_INSTALL_COMPONENT_FIRST"); ?>
					</div>
				<?php endif; ?>
			<?php echo JHtml::_('bootstrap.endTab'); ?>
			<?php echo JHtml::_('bootstrap.endTabSet'); ?>
			</div>
		</div>
		<?php echo $this->loadTemplate("modals") ?>
	<?php else : ?>
		<h2><?php echo JText::_("COM_JDEVELOPER_COMPONENT") ?></h2>
		<div class="alert alert-info">
			<?php echo JText::_("COM_JDEVELOPER_COMPONENT_NO_COMPONENT_SELECTED"); ?>
		</div>
		<button data-toggle="modal" data-target="#switchComponent" class="btn btn-info"><i class="icon-list"></i> <?php echo JText::_("COM_JDEVELOPER_COMPONENT_SWITCH"); ?></button>
	<?php endif; ?>
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
							<td><a href="<?php echo JRoute::_("index.php?option=com_jdeveloper&view=component&id=" . $component->id, false); ?>"><?php echo $component->display_name; ?></a></td>
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