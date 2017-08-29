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

$params = $this->item->params;
$blockPosition = $params->get('info_block_position', 0);
$position = $params->get("position");
?>
<dl class="article-info  muted">
<?php if ($position == 'above' && ($blockPosition == 0 || $blockPosition == 2) || $position == 'below' && ($blockPosition == 1)) : ?>
	<dt class="article-info-term">
		<?php // TODO: implement info_block_show_title param to hide article info title ?>
		<?php if ($params->get('info_block_show_title', 1)) : ?>
			<?php echo JText::_('COM_##COMPONENT##_##SINGULAR##_INFO'); ?>
		<?php endif; ?>
	</dt>##{start_created_by}##
	<?php if ($params->get('show_author', 1) && !empty($this->item->author )) : ?>
	<dd class="createdby" itemprop="author" itemscope itemtype="http://schema.org/Person">
		<?php $author = ##{start_created_by_alias}##!empty($this->item->created_by_alias) ? $this->item->created_by_alias : ##{end_created_by_alias}##$this->item->author; ?>
		<?php $author = '<span itemprop="name">' . $author . '</span>'; ?>
	</dd>
	<?php endif; ?>##{end_created_by}##
	<?php if ($params->get('show_parent_category', 1) && !empty($this->item->parent_slug)) : ?>
	<dd class="parent-category-name">
		<?php $title = $this->escape($this->item->parent_title); ?>
		<?php if ($params->get('link_parent_category', 1) && !empty($this->item->parent_slug)) : ?>
			<?php $url = '<a href="' . JRoute::_(##Component##HelperRoute::getCategoryRoute($this->item->parent_slug)) . '" itemprop="genre">' . $title . '</a>'; ?>
			<?php echo JText::_('COM_##COMPONENT##_PARENT') . ": " . $url; ?>
		<?php else : ?>
			<?php echo JText::_('COM_##COMPONENT##_PARENT') . ": " . '<span itemprop="genre">' . $title . '</span>'; ?>
		<?php endif; ?>
	</dd>
	<?php endif; ?><?php /*
	<?php if ($params->get('show_category', 1)) : ?>
	<dd class="category-name">
		<?php $title = $this->escape($this->item->category_title); ?>
		<?php if ($params->get('link_category', 1) && $this->item->catslug) : ?>
			<?php $url = '<a href="' . JRoute::_(##Component##HelperRoute::getCategoryRoute($this->item->catslug)) . '" itemprop="genre">' . $title . '</a>'; ?>
			<?php echo JText::_('COM_##COMPONENT##_CATEGORY') . ": " . $url; ?>
		<?php else : ?>
			<?php echo JText::_('COM_##COMPONENT##_CATEGORY') . ": " . '<span itemprop="genre">' . $title . '</span>'; ?>
		<?php endif; ?>
	</dd>
	<?php endif; ?> */ ?>##{start_publish_up}##
	<?php if ($params->get('show_publish_date', 1)) : ?>
	<dd class="published">
		<span class="icon-calendar"></span>
		<time datetime="<?php echo JHtml::_('date', $this->item->publish_up, 'c'); ?>" itemprop="datePublished">
			<?php echo JText::_('COM_##COMPONENT##_PUBLISHED_DATE_ON') . ": " . JHtml::_('date', $this->item->publish_up, JText::_('DATE_FORMAT_LC3')); ?>
		</time>
	</dd>
	<?php endif; ?>##{end_publish_up}##
<?php endif; ?>
<?php if ($position == 'above' && ($blockPosition == 0) || $position == 'below' && ($blockPosition == 1 || $blockPosition == 2)) : ?>##{start_created}##
	<?php if ($params->get('show_create_date', 1)) : ?>
	<dd class="create">
		<span class="icon-calendar"></span>
		<time datetime="<?php echo JHtml::_('date', $this->item->created, 'c'); ?>" itemprop="dateCreated">
			<?php echo JText::_('COM_##COMPONENT##_CREATED_DATE_ON') . ": " . JHtml::_('date', $this->item->created, JText::_('DATE_FORMAT_LC3')); ?>
		</time>
	</dd>
	<?php endif; ?>##{end_created}####{start_modified_by}##
	<?php if ($params->get('show_modify_date', 1)) : ?>
	<dd class="modified">
		<span class="icon-calendar"></span>
		<time datetime="<?php echo JHtml::_('date', $this->item->modified, 'c'); ?>" itemprop="dateModified">
			<?php echo JText::_('COM_##COMPONENT##_LAST_UPDATED') . ": " . JHtml::_('date', $this->item->modified, JText::_('DATE_FORMAT_LC3')); ?>
		</time>
	</dd>
	<?php endif; ?>##{end_modified_by}####{start_hits}##
	<?php if ($params->get('show_hits', 1)) : ?>
	<dd class="hits">
		<span class="icon-eye-open"></span>
		<meta itemprop="interactionCount" content="UserPageVisits:<?php echo $this->item->hits; ?>" />
		<?php echo JText::_('COM_##COMPONENT##_##SINGULAR##_FIELD_HITS_LABEL') . ": " . $this->item->hits; ?>
	</dd>
	<?php endif; ?>##{end_hits}##
<?php endif; ?>
</dl>