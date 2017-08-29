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
JHtml::_('behavior.multiselect');
JHtml::_('dropdown.init');
JHtml::_('formbehavior.chosen', 'select');
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

<form action="<?php echo JRoute::_('index.php?option=com_jdeveloper&view=modules'); ?>" method="post" name="adminForm" id="adminForm">
	<?php if (!empty( $this->sidebar)) : ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
	<?php else : ?>
		<div id="j-main-container">	
	<?php endif;?>
	
	<?php
		// Search tools bar
		echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this));
	?>
	<table class="table table-striped" id="tableList">
		
		<thead>
			<tr>
				<th width="1%" class="hidden-phone">
					<input type="checkbox" name="checkall-toggle" value="" onclick="Joomla.checkAll(this)" />
				</th>
				<th class="nowrap left">
					<a><?php echo JText::_('COM_JDEVELOPER_MODULE_FIELD_NAME_LABEL'); ?></a>
				</th>
				<th class="nowrap left">
					<a><?php echo JText::_('COM_JDEVELOPER_MODULE_FIELD_TABLE_LABEL'); ?></a>
				</th>
				<th class="nowrap left">
					<a><?php echo JText::_('COM_JDEVELOPER_MODULE_FIELD_CREATED_LABEL'); ?></a>
				</th>
				<th class="nowrap left">
					<a><?php echo JText::_('COM_JDEVELOPER_MODULE_FIELD_INSTALLED_LABEL'); ?></a>
				</th>
				<th class="nowrap left">
					<a><?php echo JText::_('COM_JDEVELOPER_MODULE_FIELD_ID_LABEL'); ?></a>
				</th>
			</tr>
		</thead>
				
		<tbody>
		<?php foreach ($this->items as $i => $item) : ?>
			<tr class="row<?php echo $i % 2; ?>">
				<td class="center"><?php echo JHtml::_('grid.id', $i, $item->id); ?></td>
				<td>
					<?php
					$link = JRoute::_('index.php?option=com_jdeveloper&view=module&id=' . $item->id);
					echo '<a href="' . $link . '">' . $this->escape($item->name) . '</a><br>';
					?>
				</td>
				<td><?php echo $item->table; ?></td>
				<td><?php echo JHtml::_("jdgrid.archives", "mod_", $item->name) ?></td>
				<td><?php echo $item->installed ? "<a href=\"" . JRoute::_("index.php?option=com_modules&filter_client_id=0", false) . "\">GoTo</a>" : '<i class="icon-unpublish"></i>'; ?></td>
				<td><?php echo $this->escape($item->id); ?></td>
			</tr>
		<?php endforeach ?>
		</tbody>
	
	</table>
	
	<div>
		<input type="hidden" name="task" value=" " />
		<input type="hidden" name="boxchecked" value="0" />
		<!-- Sortierkriterien -->
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>

	</form>
</div>