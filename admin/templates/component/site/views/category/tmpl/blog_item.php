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

// Create a shortcut for params.
$params = $this->item->params;
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
$canEdit = $this->item->params->get('access-edit');
JHtml::_('behavior.framework');
?>##{start_publishdate}##

<?php if (##{start_published}##$this->item->published == 0 || ##{end_published}##strtotime(##{start_publish_up}##$this->item->publish_up > strtotime(JFactory::getDate())
	|| ##{end_publish_up}####{start_publish_down}##((strtotime($this->item->publish_down) < strtotime(JFactory::getDate())) && $this->item->publish_down != '0000-00-00 00:00:00' )##{end_publish_down}##)) : ?>
	<div class="system-unpublished">
<?php endif; ?>##{end_publishdate}##

<?php if ($params->get('show_title')##{start_published}## || $this->item->published == 0##{end_published}## || ($params->get('show_author')##{start_created_by}## && !empty($this->item->author )##{end_created_by}##)) : ?>
	<div class="page-header">
		<?php if ($params->get('show_title', 1)) : ?>
		<h2 itemprop="name">
			<?php if ($params->get('link_titles') && $params->get('access-view')) : ?>
				<a href="<?php echo JRoute::_(##Component##HelperRoute::getArticleRoute($this->item->slug, $this->item->catid)); ?>" itemprop="url">
				<?php echo $this->escape($this->item->##mainfield##); ?></a>
			<?php else : ?>
				<?php echo $this->escape($this->item->##mainfield##); ?>
			<?php endif; ?>
		</h2>
		<?php endif; ?>##{start_published}##
		<?php if ($this->item->published == 0) : ?>
			<span class="label label-warning"><?php echo JText::_('JUNPUBLISHED'); ?></span>
		<?php endif; ?>##{end_published}####{start_publish_up}##
		<?php if (strtotime($this->item->publish_up) > strtotime(JFactory::getDate())) : ?>
			<span class="label label-warning"><?php echo JText::_('JNOTPUBLISHEDYET'); ?></span>
		<?php endif; ?>##{end_publish_up}####{start_publish_down}##
		<?php if ((strtotime($this->item->publish_down) < strtotime(JFactory::getDate())) && $this->item->publish_down != '0000-00-00 00:00:00') : ?>
			<span class="label label-warning"><?php echo JText::_('JEXPIRED'); ?></span>
		<?php endif; ?>##{end_publish_down}##
	</div>
<?php endif; ?>

<?php echo JLayoutHelper::render('joomla.content.icons', array('params' => $params, 'item' => $this->item, 'print' => false)); ?>

<?php $useDefList = ($params->get('show_modify_date', 1) || $params->get('show_publish_date', 1) || $params->get('show_create_date', 1)
	|| $params->get('show_hits', 1) || $params->get('show_category', 1) || $params->get('show_parent_category' , 1) || $params->get('show_author', 1) ); ?>

<?php if ($useDefList) : ?>
	<?php $this->item->params["position"] = "above"; echo $this->loadTemplate("info"); ?>
<?php endif; ?>

<?php echo $this->item->##textfield##; ?>

<?php if ($useDefList) : ?>
	<?php $this->item->params["position"] = "below"; echo $this->loadTemplate("info"); ?>
<?php  endif; ?>

<?php if ($params->get('show_readmore') && $this->item->readmore) :
	if ($params->get('access-view')) :
		$link = JRoute::_(##Component##HelperRoute::get##Singular##Route($this->item->slug, $this->item->catid));
	else :
		$menu = JFactory::getApplication()->getMenu();
		$active = $menu->getActive();
		$this->itemId = $active->id;
		$link1 = JRoute::_('index.php?option=com_users&view=login&Itemid=' . $this->itemId);
		$returnURL = JRoute::_(##Component##HelperRoute::get##Singular##Route($this->item->slug, $this->item->catid));
		$link = new JUri($link1);
		$link->setVar('return', base64_encode($returnURL));
	endif; ?>

	<p class="readmore"><a class="btn" href="<?php echo $link; ?>"> <span class="icon-chevron-right"></span>

	<?php if (!$params->get('access-view')) :
		echo JText::_('COM_##COMPONENT##_REGISTER_TO_READ_MORE');
	elseif ($readmore = $this->item->alternative_readmore) :
		echo $readmore;
		if ($params->get('show_readmore_title', 0) != 0) :
		echo JHtml::_('string.truncate', ($this->item->##mainfield##), $params->get('readmore_limit'));
		endif;
	elseif ($params->get('show_readmore_title', 0) == 0) :
		echo JText::sprintf('COM_##COMPONENT##_READ_MORE_TITLE');
	else :
		echo JText::_('COM_##COMPONENT##_READ_MORE');
		echo JHtml::_('string.truncate', ($this->item->##mainfield##), $params->get('readmore_limit'));
	endif; ?>

	</a></p>

<?php endif; ?>##{start_publishdate}##

<?php if ($this->item->published == 0 || strtotime($this->item->publish_up) > strtotime(JFactory::getDate())
	|| ((strtotime($this->item->publish_down) < strtotime(JFactory::getDate())) && $this->item->publish_down != '0000-00-00 00:00:00' )) : ?>
</div>
<?php endif; ?>##{end_publishdate}##