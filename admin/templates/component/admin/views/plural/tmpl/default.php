<?php##{start_header}##
/**
 * @package     JDeveloper
 * @subpackage  Templates.Component
 *
 * @copyright  	Copyright (C) 2014, Tilo-Lars Flasche. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;##{end_header}##
##Header##

// necessary libraries
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('dropdown.init');
JHtml::_('formbehavior.chosen', 'select');

// sort ordering and direction
$user	= JFactory::getUser();
$userId	= $user->get('id');
$listOrder = $this->state->get('list.ordering');
$listDirn = $this->state->get('list.direction');
##{start_published}##$archived	= $this->state->get('filter.published') == 2 ? true : false;
$trashed	= $this->state->get('filter.published') == -2 ? true : false;
##{end_published}##$canOrder	= ($user->authorise('core.edit.state', 'com_test') && isset($this->items[0]->ordering));##{start_ordering}##
$saveOrder = ($listOrder == 'ordering' && isset($this->items[0]->ordering));

if ($saveOrder)
{
	$saveOrderingUrl = 'index.php?option=com_##component##&task=##plural##.ordering&tmpl=component';
	JHtml::_('sortablelist.sortable', '##table##List', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}
##{end_ordering}##?>

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

<form action="<?php echo JRoute::_('index.php?option=com_##component##&view=##plural##'); ?>" method="post" name="adminForm" id="adminForm">

<?php if (!empty( $this->sidebar)) : ?>
	<!-- sidebar -->
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<!-- end sidebar -->
	<div id="j-main-container" class="span10">
<?php else : ?>
	<div id="j-main-container">
<?php endif;?>

	<?php
		// Search tools bar
		echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this));
	?>
	<?php if (empty($this->items)) : ?>
		<div class="alert alert-no-items">
			<?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
		</div>
	<?php else : ?>


	<table class="table table-striped" id="##table##List">
		<thead>
			<tr>
				##{start_ordering}##
				<!-- item ordering -->
				<?php if (isset($this->items[0]->ordering)): ?>				
					<th width="1%" class="nowrap center hidden-phone">
						<?php echo JHtml::_('searchtools.sort', '', 'a.ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING', 'icon-menu-2'); ?>
					</th>
				<?php endif; ?>##{end_ordering}##
				<!-- item checkbox -->
				<th width="1%">
					<?php echo JHtml::_('grid.checkall'); ?>
				</th>##{start_published}##
				<!-- item state -->
				<?php if (isset($this->items[0]->published)): ?>
					<th width="1%" class="nowrap center">
						<?php echo JHtml::_('searchtools.sort', 'JSTATUS', 'a.published', $listDirn, $listOrder); ?>
					</th>
                <?php endif; ?>##{end_published}##
				<th class="nowrap left">
					<?php echo JHtml::_('searchtools.sort', JText::_('COM_##COMPONENT##_##TABLE##_FIELD_##MAINFIELD##_LABEL', '##mainfield##'), $listDirn, $listOrder) ?>
				</th>##table_head####{start_access}##
				<th width="5%" class="nowrap hidden-phone">
					<?php echo JHtml::_('searchtools.sort', 'JGRID_HEADING_ACCESS', 'a.access', $listDirn, $listOrder); ?>
				</th>##{end_access}####{start_created_by}##
				<th width="10%" class="nowrap hidden-phone">
					<?php echo JHtml::_('searchtools.sort',  'JAUTHOR', 'a.created_by', $listDirn, $listOrder); ?>
				</th>##{end_created_by}####{start_language}##
				<th width="5%" class="nowrap hidden-phone">
					<?php echo JHtml::_('searchtools.sort', 'JGRID_HEADING_LANGUAGE', 'language', $listDirn, $listOrder); ?>
				</th>##{end_language}####{start_created}##
				<th width="10%" class="nowrap hidden-phone">
					<?php echo JHtml::_('searchtools.sort', 'JDATE', 'a.created', $listDirn, $listOrder); ?>
				</th>##{end_created}####{start_hits}##
				<th width="10%">
					<?php echo JHtml::_('searchtools.sort', 'JGLOBAL_HITS', 'a.hits', $listDirn, $listOrder); ?>
				</th>##{end_hits}##
				<th class="nowrap left">
					<?php echo JHtml::_('searchtools.sort', JText::_('COM_##COMPONENT##_##TABLE##_FIELD_##PK##_LABEL'), '##pk##', $listDirn, $listOrder) ?>
				</th>
			</tr>
		</thead>
				
		<tbody>
		
		<?php foreach ($this->items as $i => $item) :
		$canEdit	= $user->authorise('core.edit',       'com_##component##.##table##.'.$item->##pk##);
		$canCheckin	= $user->authorise('core.manage',     'com_checkin')##{start_checked_out}## || $item->checked_out == $userId || $item->checked_out == 0##{end_checked_out}##;
		$canEditOwn	= $user->authorise('core.edit.own',   'com_##component##.##table##.'.$item->##pk##)##{start_created_by}## && $item->created_by == $userId##{end_created_by}##;
		$canChange	= $user->authorise('core.edit.state', 'com_##component##.##table##.'.$item->##pk##) && $canCheckin;
		?>
		
			<tr class="row<?php echo $i % 2; ?>">
				##{start_ordering}##
				<!-- item ordering -->
				<?php if (isset($this->items[0]->ordering)): ?>
					<td class="order nowrap center hidden-phone">
					<?php if ($canChange) :
						$disableClassName = '';
						$disabledLabel	  = '';
						if (!$saveOrder) :
							$disabledLabel    = JText::_('JORDERINGDISABLED');
							$disableClassName = 'inactive tip-top';
						endif; ?>
						<span class="sortable-handler hasTooltip <?php echo $disableClassName?>" title="<?php echo $disabledLabel?>">
							<i class="icon-menu"></i>
						</span>
						<input type="text" style="display:none" name="order[]" size="5" value="<?php echo $item->ordering;?>" class="width-20 text-area-order " />
					<?php else : ?>
						<span class="sortable-handler inactive" >
							<i class="icon-menu"></i>
						</span>
					<?php endif; ?>
					</td>
                <?php endif; ?>
				##{end_ordering}##
				<!-- item checkbox -->
				<td class="center"><?php echo JHtml::_('grid.id', $i, $item->##pk##); ?></td>##{start_published}##
				<!-- item state -->
				<?php if (isset($this->items[0]->published)): ?>
					<td class="center">
						<?php echo JHtml::_('jgrid.published', $item->published, $i, '##plural##.', $canChange, 'cb'##{start_publish_up}##, $item->publish_up##{end_publish_up}####{start_publish_down}##, $item->publish_down##{end_publish_down}##); ?>
					</td>
                <?php endif; ?>##{end_published}##
				<!-- item main field -->
				<td class="nowrap has-context">
						<div class="pull-left">##{start_table_nested}##
							<?php echo str_repeat('<span class="gi">|&mdash;</span>', $item->level - 1) ?>##{end_table_nested}####{start_checked_out}##
							<?php if ($item->checked_out) : ?>
								<?php echo JHtml::_('jgrid.checkedout', $i, null, $item->checked_out_time, '##plural##.', $canCheckin); ?>
							<?php endif; ?>##{end_checked_out}##
							<?php if ($canEdit || $canEditOwn) : ?>
								<a href="<?php echo JRoute::_('index.php?option=com_##component##&task=##singular##.edit&##pk##='.(int) $item->##pk##); ?>">
								<?php echo $this->escape($item->##mainfield##); ?></a>
							<?php else : ?>
								<?php echo $this->escape($item->##mainfield##); ?>
							<?php endif; ?>##{start_catid}##
							<div class="small">
								<b><?php echo JText::_('JCATEGORY'); ?>: </b><?php echo $this->escape($item->category_title); ?>
							</div>##{end_catid}##
						</div>
						<div class="pull-left">
							<?php
								// Create dropdown items
								JHtml::_('dropdown.edit', $item->##pk##, '##singular##.');
								if (!isset($this->items[0]->published) || $this->state->get('filter.published') == -2) :
									JHtml::_('dropdown.addCustomItem', JText::_('JTOOLBAR_DELETE'), 'javascript:void(0)', "onclick=\"contextAction('cb$i', '##plural##.delete')\"");
								endif;
								JHtml::_('dropdown.divider');
##{start_published}##
								if ($item->published != 0) :
									JHtml::_('dropdown.unpublish', 'cb' . $i, '##plural##.');
								endif;
								if ($item->published != 1) :
									JHtml::_('dropdown.publish', 'cb' . $i, '##plural##.');
								endif;

								JHtml::_('dropdown.divider');
								if ($item->published != 2) :
									JHtml::_('dropdown.archive', 'cb' . $i, '##plural##.');
								endif;
##{end_published}####{start_checked_out}##
								if ($item->checked_out) :
									JHtml::_('dropdown.checkin', 'cb' . $i, '##plural##.');
								endif;
##{end_checked_out}####{start_published}##
								if ($item->published != -2 && $this->state->get('filter.published') != -2) :
									JHtml::_('dropdown.trash', 'cb' . $i, '##plural##.');
								endif;
##{end_published}##
								// render dropdown list
								echo JHtml::_('dropdown.render');
							?>
						</div>
				</td>##table_body####{start_access}##
				<td class="left">
					<?php echo $this->escape($item->access_level); ?>
				</td>##{end_access}####{start_created_by}##
				<td class="small hidden-phone">
					<?php if (isset($item->created_by_alias)) : ?>
						<a href="<?php echo JRoute::_('index.php?option=com_users&task=user.edit&id='.(int) $item->created_by); ?>" title="<?php echo JText::_('JAUTHOR'); ?>">
						<?php echo $this->escape($item->author_name); ?></a>
						<p class="smallsub"> <?php echo JText::sprintf('JGLOBAL_LIST_ALIAS', $this->escape($item->created_by_alias)); ?></p>
					<?php else : ?>
						<a href="<?php echo JRoute::_('index.php?option=com_users&task=user.edit&id='.(int) $item->created_by); ?>" title="<?php echo JText::_('JAUTHOR'); ?>">
						<?php echo $this->escape($item->author_name); ?></a>
					<?php endif; ?>
				</td>##{end_created_by}####{start_language}##
				<td class="small hidden-phone">
					<?php if ($item->language == '*'):?>
						<?php echo JText::alt('JALL', 'language'); ?>
					<?php else:?>
						<?php echo $item->language_title ? $this->escape($item->language_title) : JText::_('JUNDEFINED'); ?>
					<?php endif;?>
				</td>##{end_language}####{start_created}##
				<td class="nowrap small hidden-phone">
					<?php echo JHtml::_('date', $item->created, JText::_('DATE_FORMAT_LC4')); ?>
				</td>##{end_created}####{start_hits}##
				<td class="center">
					<?php echo (int) $item->hits; ?>
				</td>##{end_hits}##
				<td class="left"><?php echo $this->escape($item->##pk##); ?></td>
			</tr>
		<?php endforeach ?>
		</tbody>	
	</table>
	<?php endif; ?>
	<?php echo $this->pagination->getListFooter(); ?>
	<?php //Load the batch processing form. ?>
	<?php echo $this->loadTemplate('batch'); ?>
	
	<div>
		<input type="hidden" name="task" value=" " />
		<input type="hidden" name="boxchecked" value="0" />
		<?php echo JHtml::_('form.token'); ?>
	</div>

	</form>
</div>