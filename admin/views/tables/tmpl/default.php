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
JHtml::_('behavior.modal');
JHtml::_('behavior.multiselect');
JHtml::_('dropdown.init');
JHtml::_('formbehavior.chosen', 'select');

$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
$saveOrder	= $listOrder == 'a.ordering';

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

	<?php
		// Search tools bar
		echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this));
	?>
	<table class="table table-striped" id="tableList">
		
		<thead>
			<tr>
				<?php if ($this->state->get("filter.component", "") != "") : ?>
				<th width="1%" class="nowrap center hidden-phone">
					<?php echo JHtml::_('searchtools.sort', '', 'a.ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING', 'icon-menu-2'); ?>
				</th>
				<?php endif; ?>
				<th width="1%" class="hidden-phone">
					<?php echo JHtml::_('grid.checkall'); ?>
				</th>
				<th class="nowrap left hasTooltip" title="<?php echo JText::_('COM_JDEVELOPER_TABLE_FIELD_NAME_DESC') ?>">
					<?php echo JHtml::_('searchtools.sort', JText::_('COM_JDEVELOPER_TABLE_FIELD_NAME_LABEL'), 'a.name', $listDirn, $listOrder) ?>
				</th>
				<th class="nowrap left hasTooltip" title="<?php echo JText::_('COM_JDEVELOPER_TABLE_FIELD_FIELDS_DESC') ?>">
					<a><?php echo JText::_('COM_JDEVELOPER_TABLE_FIELD_FIELDS_LABEL'); ?></a>
				</th>
				<th class="nowrap left hasTooltip" title="<?php echo JText::_('COM_JDEVELOPER_TABLE_FIELD_SQL_DESC') ?>">
					<a><?php echo JText::_('COM_JDEVELOPER_TABLE_FIELD_SQL_LABEL') ?></a>
				</th>
				<th class="nowrap left hasTooltip" title="<?php echo JText::_('COM_JDEVELOPER_TABLE_FIELD_MAINFIELD_DESC') ?>">
					<a><?php echo JText::_('COM_JDEVELOPER_TABLE_FIELD_MAINFIELD_LABEL') ?></a>
				</th>
				<th class="nowrap left hasTooltip" title="<?php echo JText::_('COM_JDEVELOPER_TABLE_FIELD_PLURAL_DESC') ?>">
					<a><?php echo JText::_('COM_JDEVELOPER_TABLE_FIELD_PLURAL_LABEL') ?></a>
				</th>
				<th class="nowrap left hasTooltip" title="<?php echo JText::_('COM_JDEVELOPER_TABLE_FIELD_SINGULAR_DESC') ?>">
					<a><?php echo JText::_('COM_JDEVELOPER_TABLE_FIELD_SINGULAR_LABEL') ?></a>
				</th>
				<th width="10%" class="nowrap hidden-phone">
					<?php echo JHtml::_('searchtools.sort',  'JAUTHOR', 'a.created_by', $listDirn, $listOrder); ?>
				</th>
				<th class="nowrap left hasTooltip" title="<?php echo JText::_('COM_JDEVELOPER_TABLE_FIELD_ID_DESC') ?>">
					<?php echo JHtml::_('searchtools.sort', JText::_('COM_JDEVELOPER_TABLE_FIELD_ID_LABEL'), 'a.id', $listDirn, $listOrder) ?>
				</th>
			</tr>
		</thead>
		
		<tfoot>
			<tr>
				<!-- Pagination -->
				<td colspan="7"><?php echo $this->pagination->getListFooter(); ?></td>
			</tr>
		</tfoot>
		
		<tbody>
			<?php echo $this->loadTemplate('rows');  ?>
		</tbody>
	
	</table>

	<div>
		<input type="hidden" name="task" value=" " />
		<input type="hidden" name="boxchecked" value="0" />
		<?php echo JHtml::_('form.token'); ?>
	</div>

	<?php foreach ($this->items as $i => $item) : ?>
	<div class="modal hide fade" id="form<?php echo $item->name; ?>" style="width:700px;">
		<div class="modal-header">
			<h3><?php echo JText::sprintf('COM_JDEVELOPER_TABLE_FORM_DESC', '<i>#__'.$item->name).'</i>';?></h3>
		</div>
		<div class="modal-body">
			<?php echo JHtml::_("code.form", $item->form); ?>
		</div>
		<div class="modal-footer">
			<button class="btn" type="button" data-dismiss="modal">
				<?php echo JText::_('JTOOLBAR_CLOSE'); ?>
			</button>
		</div>
	</div>
	<div class="modal hide fade" id="sql<?php echo $item->name; ?>" style="width:700px;">
		<div class="modal-header">
			<h3><?php echo JText::sprintf('COM_JDEVELOPER_TABLE_SQL_DESC', '<i>#__'.$item->name).'</i>';?></h3>
		</div>
		<div class="modal-body">
			<?php echo JHtml::_("code.sql", $item->sql); ?>
		</div>
		<div class="modal-footer">
			<button class="btn" type="button" data-dismiss="modal">
				<?php echo JText::_('JTOOLBAR_CLOSE'); ?>
			</button>
		</div>
	</div>
	<?php endforeach; ?>

	<?php echo $this->loadTemplate('batch'); ?>
	
</div>
</form>