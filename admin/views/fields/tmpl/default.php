<?php
/**
 * @package     JDeveloper
 * @subpackage  Views
 *
 * @copyright  	Copyright (C) 2014, Tilo-Lars Flasche. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

JHtml::_('jquery.framework');
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('dropdown.init');
JHtml::_('formbehavior.chosen', 'select');

$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
$saveOrder = ($listOrder == 'a.ordering');

if ($saveOrder)
{
	$saveOrderingUrl = 'index.php?option=com_jdeveloper&task=fields.saveOrderAjax&tmpl=component';
	JHtml::_('sortablelist.sortable', 'fieldList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
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

<form action="<?php echo JRoute::_('index.php?option=com_jdeveloper&view=fields'); ?>" method="post" name="adminForm" id="adminForm">

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

	<table class="table table-striped" id="fieldList">
		
		<thead>
			<tr>
				<?php if ($this->state->get("filter.table", "") != "") : ?>
				<th width="1%" class="nowrap center hidden-phone">
					<?php echo JHtml::_('searchtools.sort', '', 'a.ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING', 'icon-menu-2'); ?>
				</th>
				<?php endif; ?>
				<th width="1%" class="hidden-phone">
					<?php echo JHtml::_('grid.checkall'); ?>
				</th>
				<th class="nowrap left">
					<?php echo JHtml::_('searchtools.sort', 'COM_JDEVELOPER_FIELD_FIELD_NAME_LABEL', 'a.name', $listDirn, $listOrder) ?>
				</th>
				<th class="nowrap left">
					<a><?php echo JText::_('COM_JDEVELOPER_FIELD_FIELD_TABLE_LABEL') ?></a>
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
				<th width="10%" class="nowrap hidden-phone">
					<?php echo JHtml::_('searchtools.sort',  'JAUTHOR', 'a.created_by', $listDirn, $listOrder); ?>
				</th>
				<th class="nowrap left">
					<?php echo JHtml::_('searchtools.sort', 'COM_JDEVELOPER_FIELD_FIELD_ID_LABEL','a.id', $listDirn, $listOrder) ?>
				</th>
			</tr>
		</thead>
		
		<tbody>
			<?php echo $this->loadTemplate('rows'); ?>
		</tbody>
		
		<tfoot>
			<tr>
				<!-- Pagination -->
				<td colspan="7"><?php echo $this->pagination->getListFooter(); ?></td>
			</tr>
		</tfoot>
	
	</table>

	<?php echo $this->loadTemplate('batch'); ?>
	
	<div>
		<input type="hidden" name="task" value=" " />
		<input type="hidden" name="boxchecked" value="0" />
		<?php echo JHtml::_('form.token'); ?>
	</div>

	</form>
</div>