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
$ordering = ($listOrder == 'a.ordering');
?>
<style>
.progress {
    position: relative;
}

.bar {
    z-index: 1;
    position: absolute;
}

.progress span {
    position: absolute;
    top: 0;
    z-index: 2;
    text-align: center;
    width: 100%;
    color: black;
}
</style>

<div class="progress progress-striped active" style="display:none; opacity:0.0" id="components_create">
	<div class="bar"></div>
	<span><b class="bar-left">0</b><b> / </b><b class="bar-right">0</b> <?php echo JText::_('COM_JDEVELOPER_CREATED'); ?></span>
</div>

<div class="progress progress-striped active" style="display:none; opacity:0.0" id="components_deletezip">
	<div class="bar"></div>
	<span><b class="bar-left">0</b><b> / </b><b class="bar-right">0</b> <?php echo JText::_('COM_JDEVELOPER_DELETEZIP'); ?></span>
</div>

<?php if (null !== $item = JDeveloperHelper::getUpdate()) : ?>
	<div class="alert alert-info">
		<b>JDeveloper Update:</b> Version <?php echo $item->version; ?> is now available.
	</div>
<?php endif; ?>

<form action="<?php echo JRoute::_('index.php?option=com_jdeveloper&view=components'); ?>" method="post" name="adminForm" id="adminForm">
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
					<?php echo JHtml::_('searchtools.sort', '', 'a.ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING', 'icon-menu-2'); ?>
				</th>
				<th width="1%" class="hidden-phone">
					<?php echo JHtml::_('grid.checkall'); ?>
				</th>
				<th class="nowrap left">
					<?php echo JHtml::_('searchtools.sort', 'COM_JDEVELOPER_COMPONENT_FIELD_DISPLAYNAME_LABEL', 'a.display_name', $listDirn, $listOrder) ?>
				</th>
				<th class="nowrap left">
					<a><?php echo JText::_('COM_JDEVELOPER_COMPONENT_FIELD_ISBUILT_LABEL') ?></a>
				</th>
				<th class="nowrap left">
					<a><?php echo JText::_('COM_JDEVELOPER_COMPONENT_FIELD_INSTALLED_LABEL') ?></a>
				</th>
				<th class="nowrap left">
					<a><?php echo JText::_('COM_JDEVELOPER_COMPONENT_FIELD_SITE_LABEL') ?></a>
				</th>
				<th class="nowrap left">
					<a><?php echo JText::_('COM_JDEVELOPER_COMPONENT_FIELD_TABLES_LABEL') ?></a>
				</th>
				<th class="nowrap left">
					<a><?php echo JText::_('COM_JDEVELOPER_COMPONENT_FIELD_VERSION_LABEL') ?></a>
				</th>
				<th class="nowrap left">
					<?php echo JHtml::_('searchtools.sort',  'JAUTHOR', 'a.created_by', $listDirn, $listOrder); ?>
				</th>
				<th class="nowrap left">
					<a><?php echo JText::_('COM_JDEVELOPER_COMPONENT_FIELD_ID_LABEL') ?></a>
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