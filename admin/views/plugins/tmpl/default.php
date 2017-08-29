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

$listOrder = $this->state->get('list.ordering');
$listDirn = $this->state->get('list.direction');
$ordering = ($listOrder == 'ordering');
?>

<form action="<?php echo JRoute::_('index.php?option=com_jdeveloper&view=plugins'); ?>" method="post" name="adminForm" id="adminForm">
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
	
	<table class="table table-striped">
				
		<thead>
			<tr>
				<th width="1%" class="nowrap center hidden-phone">
					<?php echo JHtml::_('searchtools.sort', '', 'c.ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING', 'icon-menu-2'); ?>
				</th>
				<th width="1%" class="hidden-phone">
					<?php echo JHtml::_('grid.checkall'); ?>
				</th>
				<th class="nowrap left">
					<?php echo JHtml::_('searchtools.sort', JText::_('COM_JDEVELOPER_PLUGIN_FIELD_NAME_LABEL'), 'a.name', $listDirn, $listOrder) ?>
				</th>
				<th class="nowrap left">
					<?php echo JHtml::_('searchtools.sort', JText::_('COM_JDEVELOPER_PLUGIN_FIELD_FOLDER_LABEL'), 'a.folder', $listDirn, $listOrder) ?>
				</th>
				<th class="nowrap left">
					<a><?php echo JText::_('COM_JDEVELOPER_PLUGIN_FIELD_ISBUILT_LABEL') ?></a>
				</th>
				<th class="nowrap left">
					<a><?php echo JText::_('COM_JDEVELOPER_PLUGIN_FIELD_INSTALLED_LABEL') ?></a>
				</th>
				<th class="nowrap left">
					<a><?php echo JText::_('COM_JDEVELOPER_PLUGIN_FIELD_ENABLED_LABEL') ?></a>
				</th>
				<th class="nowrap left">
					<?php echo JHtml::_('searchtools.sort', JText::_('COM_JDEVELOPER_PLUGIN_FIELD_VERSION_LABEL'), 'a.version', $listDirn, $listOrder) ?>
				</th>
				<th class="nowrap left">
					<?php echo JHtml::_('searchtools.sort',  'JAUTHOR', 'a.created_by', $listDirn, $listOrder); ?>
				</th>
				<th class="nowrap left">
					<?php echo JHtml::_('searchtools.sort', JText::_('COM_JDEVELOPER_PLUGIN_FIELD_ID_LABEL'), 'a.id', $listDirn, $listOrder) ?>
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
	
	<button class="btn btn-info" onclick="jQuery('#newFolderModal').modal('show'); return true;">
		<i class="icon-new"></i>
		<?php echo JText::_("COM_JDEVELOPER_PLUGIN_TASK_NEW_FOLDER") ?>
	</button>

	<?php echo $this->loadTemplate('batch'); ?>
	<?php echo $this->loadTemplate('newfolder'); ?>
	
	<div>
		<input type="hidden" name="task" value=" " />
		<input type="hidden" name="boxchecked" value="0" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
	
</div>
</form>