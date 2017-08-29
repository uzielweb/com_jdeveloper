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

defined('_JEXEC') or die;

require_once JPATH_ROOT . '/components/com_##component##/helpers/route.php';

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');

JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.framework', true);

$input     = JFactory::getApplication()->input;
$function  = JFactory::getApplication()->input->getCmd('function', 'jSelect##Singular##');
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));
?>
<div id="j-main-container">
<form action="<?php echo JRoute::_('index.php?option=com_##component##&view=##plural##&layout=modal&tmpl=component&function='.$function);?>" method="post" name="adminForm" id="adminForm" class="form-inline">
	<fieldset class="filter clearfix">
		<div class="btn-toolbar">
			<div class="btn-group pull-left">
				<label for="filter_search">
					<?php echo JText::_('JSEARCH_FILTER_LABEL'); ?>
				</label>
				<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" size="30" title="<?php echo JText::_('COM_##COMPONENT##_FILTER_SEARCH_DESC'); ?>" />
			</div>
			<div class="btn-group pull-left">
				<button type="submit" class="btn hasTooltip" title="<?php echo JHtml::tooltipText('JSEARCH_FILTER_SUBMIT'); ?>" data-placement="bottom">
					<i class="icon-search"></i></button>
				<button type="button" class="btn hasTooltip" title="<?php echo JHtml::tooltipText('JSEARCH_FILTER_CLEAR'); ?>" data-placement="bottom" onclick="document.id('filter_search').value='';this.form.submit();">
					<i class="icon-remove"></i></button>
			</div>
			<div class="clearfix"></div>
		</div>
		<hr class="hr-condensed" />

		<div class="filters pull-left">##{start_access}##
			<select name="filter_access" class="input-medium" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('JOPTION_SELECT_ACCESS');?></option>
				<?php echo JHtml::_('select.options', JHtml::_('access.assetgroups'), 'value', 'text', $this->state->get('filter.access'));?>
			</select>##{end_access}####{start_published}##
			<select name="filter_published" class="input-medium" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('JOPTION_SELECT_PUBLISHED');?></option>
				<?php echo JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter.published'), true);?>
			</select>##{end_published}####{start_table_nested}##
			<select name="filter_level" class="input-medium" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('JOPTION_SELECT_MAX_LEVELS');?></option>
				<?php echo JHtml::_('select.options', array(1,2,3,4,5,6,7,8,9,10), 'value', 'text', $this->state->get('filter.level'), true);?>
			</select>##{end_table_nested}##
			<?php if ($this->state->get('filter.forcedLanguage')) : ?>##{start_catid}##
			<select name="filter_category_id" class="input-medium" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('JOPTION_SELECT_CATEGORY');?></option>
				<?php echo JHtml::_('select.options', JHtml::_('category.options', 'com_##component##', array('filter.language' => array('*', $this->state->get('filter.forcedLanguage')))), 'value', 'text', $this->state->get('filter.category_id'));?>
			</select>##{end_catid}####{start_language}##
			<input type="hidden" name="forcedLanguage" value="<?php echo $this->escape($this->state->get('filter.forcedLanguage')); ?>" />
			<input type="hidden" name="filter_language" value="<?php echo $this->escape($this->state->get('filter.language')); ?>" />##{end_language}##
			<?php else : ?>##{start_catid}##
			<select name="filter_category_id" class="input-medium" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('JOPTION_SELECT_CATEGORY');?></option>
				<?php echo JHtml::_('select.options', JHtml::_('category.options', 'com_##component##'), 'value', 'text', $this->state->get('filter.category_id'));?>
			</select>##{end_catid}####{start_language}##
			<select name="filter_language" class="input-medium" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('JOPTION_SELECT_LANGUAGE');?></option>
				<?php echo JHtml::_('select.options', JHtml::_('contentlanguage.existing', true, true), 'value', 'text', $this->state->get('filter.language'));?>
			</select>##{end_language}##
			<?php endif; ?>
		</div>
	</fieldset>

	<?php if (empty($this->items)) : ?>
		<div class="alert alert-no-items">
			<?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
		</div>
	<?php endif; ?>

	<table class="table table-striped table-condensed">
		<thead>
			<tr>
				<th class="title">
					<?php echo JHtml::_('grid.sort', 'JGLOBAL_TITLE', 'a.##mainfield##', $listDirn, $listOrder); ?>
				</th>##{start_access}##
				<th width="15%" class="nowrap center">
					<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ACCESS', 'access_level', $listDirn, $listOrder); ?>
				</th>##{end_access}####{start_catid}##
				<th width="15%" class="nowrap center">
					<?php echo JHtml::_('grid.sort', 'JCATEGORY', 'a.catid', $listDirn, $listOrder); ?>
				</th>##{end_catid}####{start_language}##
				<th width="5%" class="nowrap center">
					<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_LANGUAGE', 'language', $listDirn, $listOrder); ?>
				</th>##{end_language}##
				<th width="1%" class="nowrap center">
					<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'a.##pk##', $listDirn, $listOrder); ?>
				</th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($this->items as $i => $item) : ?>##{start_language}##
			<?php if ($item->language && JLanguageMultilang::isEnabled())
			{
				$tag = strlen($item->language);
				if ($tag == 5)
				{
					$lang = substr($item->language, 0, 2);
				}
				elseif ($tag == 6)
				{
					$lang = substr($item->language, 0, 3);
				}
				else {
					$lang = "";
				}
			}
			elseif (!JLanguageMultilang::isEnabled())
			{
				$lang = "";
			}
			?>##{end_language}##
			<tr class="row<?php echo $i % 2; ?>">
				<td>##{start_table_nested}##
					<?php echo str_repeat('<span class="gi">|&mdash;</span>', $item->level - 1) ?>##{end_table_nested}##
					<?php
						$catid = (isset($item->catid)) ? $item->catid : 0;
						$language = (isset($item->language)) ? $item->language : 0;
					?>
					<a href="javascript:void(0)" onclick="if (window.parent) window.parent.<?php echo $this->escape($function);?>('<?php echo $item->id; ?>', '<?php echo $this->escape(addslashes($item->title)); ?>', '<?php echo $this->escape($catid); ?>', null, '<?php echo $this->escape(##Component##HelperRoute::get##Singular##Route($item->id, $catid, $language)); ?>', '<?php echo $this->escape($lang); ?>', null);">
					<?php echo $this->escape($item->##mainfield##); ?></a>
				</td>##{start_access}##
				<td class="center">
					<?php echo $this->escape($item->access_level); ?>
				</td>##{end_access}####{start_catid}##
				<td class="center">
					<?php echo $this->escape($item->category_title); ?>
				</td>##{end_catid}####{start_language}##
				<td class="center">
					<?php if ($item->language == '*'):?>
						<?php echo JText::alt('JALL', 'language'); ?>
					<?php else:?>
						<?php echo $item->language_title ? $this->escape($item->language_title) : JText::_('JUNDEFINED'); ?>
					<?php endif;?>
				</td>##{end_language}##
				<td align="center">
					<?php echo (int) $item->##pk##; ?>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="15">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
	</table>

	<div>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
</div>
