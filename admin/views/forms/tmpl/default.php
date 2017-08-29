<?php
/**
 * @author		Tilo-Lars Flasche
 * @copyright	Copyright (C) 2014, Tilo-Lars Flasche. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html
 */

defined("_JEXEC") or die("Restricted access");

// necessary libraries
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.framework');
JHtml::_('behavior.modal');
JHtml::_('behavior.multiselect');
JHtml::_('dropdown.init');
JHtml::_('formbehavior.chosen', 'select');

// sort ordering and direction
$app	= JFactory::getApplication();
$user	= JFactory::getUser();
$userId	= $user->get('id');
$listOrder = $this->state->get('list.ordering');
$listDirn = $this->state->get('list.direction');
$canOrder	= ($user->authorise('core.edit.state', 'com_test') && isset($this->items[0]->ordering));
$saveOrder = ($listOrder == 'ordering' && isset($this->items[0]->ordering));

if ($saveOrder)
{
	$saveOrderingUrl = 'index.php?option=com_jdeveloper&task=forms.ordering&tmpl=component';
	JHtml::_('sortablelist.sortable', 'formsList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
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

<form action="<?php echo JRoute::_('index.php?option=com_jdeveloper&view=forms'); ?>" method="post" name="adminForm" id="adminForm">

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

	<?php if ($app->input->get('parent_id', 0) != 0) : ?>
		<h2><?php echo JText::sprintf("COM_JDEVELOPER_FORMS_FIELDS", $this->items[0]->name); ?></h2>
	<?php else : ?>
		<h2><?php echo JText::sprintf("COM_JDEVELOPER_FORMS", $this->items[0]->name); ?></h2>
	<?php endif; ?>
	
	<table class="table table-striped" id="formsList">
		<thead>
			<tr>				
				<!-- item ordering -->
				<?php if (isset($this->items[0]->ordering)): ?>				
					<th width="1%" class="nowrap center hidden-phone">
						<?php echo JHtml::_('searchtools.sort', '', 'a.ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING', 'icon-menu-2'); ?>
					</th>
				<?php endif; ?>
				
				<!-- item checkbox -->
				<th width="1%" class="hidden-phone">
					<?php echo JHtml::_('grid.checkall'); ?>
				</th>
				
				<th class="nowrap left">
					<?php echo JHtml::_('searchtools.sort', JText::_('COM_JDEVELOPER_FORMS_FIELD_NAME_LABEL', 'name'), $listDirn, $listOrder) ?>
				</th>
				<th class="nowrap left">
					<?php echo JHtml::_('grid.sort', JText::_('COM_JDEVELOPER_FORMS_FIELD_TYPE_LABEL'), 'a.type', $listDirn, $listOrder) ?>
				</th>
				<th class="nowrap left">
					<?php echo JText::_('COM_JDEVELOPER_FORMS_ADD_CHILD'); ?>
				</th>
				<th class="nowrap left">
					XML Code
				</th>
				<th class="nowrap left">
					<?php echo JText::_('COM_JDEVELOPER_FORMS_CREATE_FILE'); ?>
				</th>
				<?php if ($app->input->get('parent_id', 0) != 0) : ?>
				<th class="nowrap left">
					<?php echo JHtml::_('grid.sort', JText::_('COM_JDEVELOPER_FORMS_FIELD_VALIDATION_LABEL'), 'a.validation', $listDirn, $listOrder) ?>
				</th>
				<th class="nowrap left">
					<?php echo JHtml::_('grid.sort', JText::_('COM_JDEVELOPER_FIELD_FIELD_FILTER_LABEL'), 'a.validation', $listDirn, $listOrder) ?>
				</th>
				<?php endif; ?>
				<th width="10%" class="nowrap hidden-phone">
					<?php echo JHtml::_('searchtools.sort',  'JAUTHOR', 'a.created_by', $listDirn, $listOrder); ?>
				</th>
				<th class="nowrap left">
					<?php echo JHtml::_('searchtools.sort', JText::_('COM_JDEVELOPER_FORMS_FIELD_ID_LABEL'), 'id', $listDirn, $listOrder) ?>
				</th>
			</tr>
		</thead>
				
		<tbody>
		
		<?php foreach ($this->items as $i => $item) :
		$canEdit	= $user->authorise('core.edit',       'com_JDEVELOPER.forms.'.$item->id);
		$canCheckin	= $user->authorise('core.manage',     'com_checkin');
		$canEditOwn	= $user->authorise('core.edit.own',   'com_jdeveloper.forms.'.$item->id) && $item->created_by == $userId;
		$canChange	= $user->authorise('core.edit.state', 'com_jdeveloper.forms.'.$item->id) && $canCheckin;
		?>
		
			<tr class="row<?php echo $i % 2; ?>">
				
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
				
				<!-- item checkbox -->
				<td class="center"><?php echo JHtml::_('grid.id', $i, $item->id); ?></td>
				
				<!-- item main field -->
				<td class="nowrap has-context">
					<div class="pull-left">
						<?php echo str_repeat('<span class="gi">|&mdash;</span>', $item->level - 1) ?>
						<span class="badge badge-warning"><?php echo ucfirst($item->tag); ?> : </span>
						<?php if ($canEdit || $canEditOwn) : ?>
							<a href="<?php echo JRoute::_('index.php?option=com_jdeveloper&task=form.edit&id='.(int) $item->id); ?>">
							<?php echo $this->escape($item->name); ?></a>
						<?php else : ?>
							<?php echo $this->escape($item->name); ?>
						<?php endif; ?>
						<br>
						<?php if ($app->input->get('parent_id', 0) == '0') : ?>
						<a href="<?php echo JRoute::_("index.php?option=com_jdeveloper&view=forms&parent_id=" . $item->id); ?>" style="color:#999999">
							>> <?php echo JText::_("COM_JDEVELOPER_FORM_SHOW_FIELDS"); ?>
						</a>
						<?php endif; ?>
					</div>
				</td>
				<td class="left"><?php echo $this->escape($item->type); ?></td>
				<td>
					<?php if (in_array($item->tag, array("form", "fields"))) : ?>
					<a href="<?php echo JRoute::_("index.php?option=com_jdeveloper&task=form.add&tag=fieldset&parent_id=" . $item->id); ?>"class="btn btn-small btn-success" style="width:65px; margin:1px;">
						<i class="icon-new"></i> <?php echo JText::_("COM_JDEVELOPER_FORM_ADD_FIELDSET"); ?>
					</a>
					<?php endif; ?>
					<?php if (in_array($item->tag, array("form", "fields"))) : ?>
					<a href="<?php echo JRoute::_("index.php?option=com_jdeveloper&task=form.add&tag=fields&parent_id=" . $item->id); ?>"class="btn btn-small btn-success" style="width:65px; margin:1px;">
						<i class="icon-new"></i> <?php echo JText::_("COM_JDEVELOPER_FORM_ADD_FIELDS"); ?>
					</a>
					<?php endif; ?>
					<?php if (in_array($item->tag, array("fieldset"))) : ?>
					<a href="<?php echo JRoute::_("index.php?option=com_jdeveloper&task=form.add&tag=field&parent_id=" . $item->id); ?>"class="btn btn-small btn-success" style="width:65px; margin:1px;">
						<i class="icon-new"></i> <?php echo JText::_("COM_JDEVELOPER_FORM_ADD_FIELD"); ?>
					</a>
					<?php endif; ?>
				</td>
				<td>
					<a onclick="SqueezeBox.fromElement(this, {handler:'iframe', size: {x: window.getSize().x * 0.6, y: window.getSize().y * 0.8}, url:'<?php echo JRoute::_("index.php?option=com_jdeveloper&view=form&layout=form&tmpl=component&id=" . $item->id) ?>'})" class="btn btn-small btn-info">
						XML
					</a>
				</td>
				<td>
					<?php if ($item->level == 1) : ?>
					<a href="<?php echo JRoute::_("index.php?option=com_jdeveloper&view=form&layout=form&format=xml&id=" . $item->id) ?>" class="btn btn-small">
						<?php echo JText::_("COM_JDEVELOPER_FORMS_CREATE_FILE") ?>
					</a>
					<?php endif; ?>
				</td>
				<?php if ($app->input->get('parent_id', 0) != 0) : ?>
				<td class="left"><?php echo $this->escape($item->validation); ?></td>
				<td class="left"><?php echo $this->escape($item->filter); ?></td>
				<?php endif; ?>
				<td class="small hidden-phone">
					<?php if (isset($item->created_by_alias)) : ?>
						<a href="<?php echo JRoute::_('index.php?option=com_users&task=user.edit&id='.(int) $item->created_by); ?>" title="<?php echo JText::_('JAUTHOR'); ?>">
						<?php echo $this->escape($item->author_name); ?></a>
						<p class="smallsub"> <?php echo JText::sprintf('JGLOBAL_LIST_ALIAS', $this->escape($item->created_by_alias)); ?></p>
					<?php else : ?>
						<a href="<?php echo JRoute::_('index.php?option=com_users&task=user.edit&id='.(int) $item->created_by); ?>" title="<?php echo JText::_('JAUTHOR'); ?>">
						<?php echo $this->escape($item->author_name); ?></a>
					<?php endif; ?>
				</td>
				<td class="left"><?php echo $this->escape($item->id); ?></td>
			</tr>
		<?php endforeach ?>
		</tbody>	
	</table>
	<?php endif; ?>
	<?php echo $this->pagination->getListFooter(); ?>
	<?php //Load the batch processing form. ?>
	<?php echo $this->loadTemplate('batch'); ?>
	
	<?php if ($app->input->get('parent_id', 0) != 0) : ?>
	<a href="<?php echo JRoute::_("index.php?option=com_jdeveloper&view=forms"); ?>" style="color:#333333; font-size:18px;">
		>> <?php echo JText::_("COM_JDEVELOPER_FORM_BACK_TO_FORMS"); ?>
	</a>
	<?php endif; ?>

	<div>
		<input type="hidden" name="task" value=" " />
		<input type="hidden" name="boxchecked" value="0" />
		<?php echo JHtml::_('form.token'); ?>
	</div>

	</form>
</div>