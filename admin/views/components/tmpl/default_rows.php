<?php
/**
 * @package     JDeveloper
 * @subpackage  Views
 *
 * @copyright  	Copyright (C) 2014, Tilo-Lars Flasche. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */
defined('_JEXEC') or die;

$listOrder = $this->state->get('list.ordering');
$listDirn = $this->state->get('list.direction');
$saveOrder = $listOrder == "a.ordering";

if ($saveOrder)
{
	$saveOrderingUrl = 'index.php?option=com_jdeveloper&task=fields.saveOrderAjax&tmpl=component';
	JHtml::_('sortablelist.sortable', 'fieldList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}
?>
<?php foreach ($this->items as $i => $item) : ?>
    <tr>
        <td class="center"><?php echo JHtml::_('grid.id', $i, $item->id); ?></td>
        <td>
            <?php
            $link = JRoute::_('index.php?option=com_jdeveloper&view=component&id=' . $item->id);
            echo '<a href="' . $link . '">' . $this->escape($item->display_name) . '</a><br>';
            ?>
        </td>
        <td><?php echo JHtml::_("jdgrid.archives", "com_", $item->name); ?></td>
        <td>
            <?php if (empty($item->installed)) : ?>
                <i class="icon-unpublish"></i>
            <?php else : ?>
                <?php if ($item->site) : ?>
                    <a href="<?php echo JURI::root(); ?>index.php?option=com_<?php echo $item->name; ?>"><?php echo JText::_("COM_JDEVELOPER_FRONTEND") ?></a><br>
                <?php endif; ?>
                <a href="<?php echo JURI::base(); ?>index.php?option=com_<?php echo $item->name; ?>"><?php echo JText::_("COM_JDEVELOPER_BACKEND") ?></a>
            <?php endif; ?>
        </td>
        <td><?php echo JHtml::_("jdgrid.published", $item->site); ?></td>
        <td><?php echo JHtml::_("jdgrid.assigned", JRoute::_('index.php?option=com_jdeveloper&view=tables&filter[component]=' . $item->id), $this->escape($item->numberOfTables)); ?></td>
        <td><?php echo $this->escape($item->version); ?></td>
        <td><?php echo JHtml::_("jdgrid.author", $item->created_by, $item->author_name); ?></td>
        <td><?php echo $this->escape($item->id); ?></td>
    </tr>
<?php endforeach ?>