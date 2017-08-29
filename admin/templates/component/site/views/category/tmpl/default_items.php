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

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('behavior.framework');

// Create some shortcuts.
$params		= &$this->item->params;
$n			= count($this->items);
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
?>

<?php if (empty($this->items)) : ?>
	<p><?php echo JText::_('COM_##COMPONENT##_NO_##PLURAL##'); ?></p>
<?php else : ?>

<form action="<?php echo htmlspecialchars(JUri::getInstance()->toString()); ?>" method="post" name="adminForm" id="adminForm" class="form-inline">
	<?php if ($this->params->get('show_headings') || $this->params->get('filter_field') != 'hide' || $this->params->get('show_pagination_limit')) :?>
	<fieldset class="filters btn-toolbar clearfix">
		<?php if ($this->params->get('filter_field') != 'hide') :?>
			<div class="btn-group">
				<label class="filter-search-lbl element-invisible" for="filter-search">
					<?php echo JText::_('COM_##COMPONENT##_'.$this->params->get('filter_field').'_FILTER_LABEL').'&#160;'; ?>
				</label>
				<input type="text" name="filter-search" id="filter-search" value="<?php echo $this->escape($this->state->get('list.filter')); ?>" class="inputbox" onchange="document.adminForm.submit();" title="<?php echo JText::_('COM_##COMPONENT##_FILTER_SEARCH_DESC'); ?>" placeholder="<?php echo JText::_('COM_##COMPONENT##_'.$this->params->get('filter_field').'_FILTER_LABEL'); ?>" />
			</div>
		<?php endif; ?>
		<?php if ($this->params->get('show_pagination_limit')) : ?>
			<div class="btn-group pull-right">
				<label for="limit" class="element-invisible">
					<?php echo JText::_('JGLOBAL_DISPLAY_NUM'); ?>
				</label>
				<?php echo $this->pagination->getLimitBox(); ?>
			</div>
		<?php endif; ?>

		<input type="hidden" name="filter_order" value="" />
		<input type="hidden" name="filter_order_Dir" value="" />
		<input type="hidden" name="limitstart" value="" />
		<input type="hidden" name="task" value="" />
	</fieldset>
	<?php endif; ?>

	<table class="category table table-striped table-bordered table-hover">
		<?php if ($this->params->get('show_headings')) : ?>
		<thead>
			<tr>
				<th id="categorylist_header_title">
					<?php echo JHtml::_('grid.sort', 'JGLOBAL_TITLE', 'a.##mainfield##', $listDirn, $listOrder); ?>
				</th>##{start_created_by}##
				<?php if ($this->params->get('list_show_author', 1)) : ?>
					<th id="categorylist_header_author">
						<?php echo JHtml::_('grid.sort', 'JAUTHOR', 'author', $listDirn, $listOrder); ?>
					</th>
				<?php endif; ?>##{end_created_by}####{start_hits}##
				<?php if ($this->params->get('list_show_hits', 1)) : ?>
					<th id="categorylist_header_hits">
						<?php echo JHtml::_('grid.sort', 'JGLOBAL_HITS', 'a.hits', $listDirn, $listOrder); ?>
					</th>
				<?php endif; ?>##{end_hits}##
				<?php if ($this->user->authorise('core.edit') || $this->user->authorise('core.edit.own')) : ?>
					<th id="categorylist_header_edit"><?php echo JText::_('COM_##COMPONENT##_EDIT_ITEM'); ?></th>
				<?php endif; ?>
			</tr>
		</thead>
		<?php endif; ?>
		<tbody>
			<?php
			foreach ($this->items as $i => $item) :
			$canEdit	= $this->user->authorise('core.edit',       'com_##component##'##{start_asset_id}##.'.##singular##.'.$item->##pk####{end_asset_id}##);
			$canEditOwn	= $this->user->authorise('core.edit.own',   'com_##component##'##{start_asset_id}##.'.##singular##.'.$item->##pk####{end_asset_id}##)##{start_created_by}## && $item->created_by == $this->user->id##{end_created_by}##;
			?>
				<?php if (isset($this->items[$i]->published) && $this->items[$i]->published == 0) : ?>
				 <tr class="system-unpublished cat-list-row<?php echo $i % 2; ?>">
				<?php else: ?>
				<tr class="cat-list-row<?php echo $i % 2; ?>" >
				<?php endif; ?>
					<td headers="categorylist_header_title" class="list-title">
						<?php if (isset($item->access) && in_array($item->access, $this->user->getAuthorisedViewLevels())) : ?>
							<a href="<?php echo JRoute::_("index.php?option=com_##component##&view=##singular##&##pk##=" . $item->##pk##); ?>">
								<?php echo $this->escape($item->##mainfield##); ?>
							</a>
						<?php else: ?>
							<?php echo $this->escape($item->##mainfield##); ?>
						<?php endif; ?>##{start_published}##
						<?php if ($item->published == 0) : ?>
							<span class="list-published label label-warning">
								<?php echo JText::_('JUNPUBLISHED'); ?>
							</span>
						<?php endif; ?>##{end_published}####{start_publish_up}##
						<?php if (strtotime($item->publish_up) > strtotime(JFactory::getDate())) : ?>
							<span class="list-published label label-warning">
								<?php echo JText::_('JNOTPUBLISHEDYET'); ?>
							</span>
						<?php endif; ?>##{end_publish_up}####{start_publish_down}##
						<?php if ((strtotime($item->publish_down) < strtotime(JFactory::getDate())) && $item->publish_down != '0000-00-00 00:00:00') : ?>
							<span class="list-published label label-warning">
								<?php echo JText::_('JEXPIRED'); ?>
							</span>
						<?php endif; ?>##{end_publish_down}##
					</td>##{start_created_by}##
					<?php if ($this->params->get('list_show_author', 1)) : ?>
					<td headers="categorylist_header_author" class="list-author">
						<?php if (!empty($item->author)##{start_created_by_alias}## || !empty($item->created_by_alias)##{end_created_by_alias}##) : ?>
							<?php $author = $item->author ?>##{start_created_by_alias}##
							<?php $author = ($item->created_by_alias ? $item->created_by_alias : $author);?>##{end_created_by_alias}##
							<?php echo $author; ?>
						<?php endif; ?>
					</td>
					<?php endif; ?>##{end_created_by}####{start_hits}##
					<?php if ($this->params->get('list_show_hits', 1)) : ?>
					<td headers="categorylist_header_hits" class="list-hits">
						<span class="badge badge-info">
							<?php echo JText::sprintf('JGLOBAL_HITS_COUNT', $item->hits); ?>
						</span>
					</td>
					<?php endif; ?>##{end_hits}##
					<?php if ($this->user->authorise('core.edit') || $this->user->authorise('core.edit.own')) : ?>
					<td headers="categorylist_header_edit" class="list-edit">
						<?php if ($canEdit || $canEditOwn) : ?>
							<a href="<?php echo JRoute::_("index.php?option=com_##component##&task=##singular##.edit&##pk##=" . $item->##pk##); ?>"><i class="icon-edit"></i> <?php echo JText::_("JGLOBAL_EDIT"); ?></a>
						<?php endif; ?>
					</td>
					<?php endif; ?>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
<?php endif; ?>

<?php // Code to add a link to submit an ##singular##. ?>
<?php if ($this->category->getParams()->get('access-create')) : ?>
	<?php echo JHtml::_('icon.create', $this->category, $this->category->params); ?>
<?php  endif; ?>

<?php // Add pagination links ?>
<?php if (!empty($this->items)) : ?>
	<?php if (($this->params->def('show_pagination', 2) == 1  || ($this->params->get('show_pagination') == 2)) && ($this->pagination->pagesTotal > 1)) : ?>
	<div class="pagination">

		<?php if ($this->params->def('show_pagination_results', 1)) : ?>
			<p class="counter pull-right">
				<?php echo $this->pagination->getPagesCounter(); ?>
			</p>
		<?php endif; ?>

		<?php echo $this->pagination->getPagesLinks(); ?>
	</div>
	<?php endif; ?>
</form>
<?php  endif; ?>
