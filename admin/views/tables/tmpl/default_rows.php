<?php
/**
 * @package     JDeveloper
 * @subpackage  Views
 *
 * @copyright  	Copyright (C) 2014, Tilo-Lars Flasche. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;
require_once JDeveloperLIB . "/archive.php";

$listOrder = $this->state->get('list.ordering');
$listDirn = $this->state->get('list.direction');
$ordering = ($listOrder == 'ordering');
$saveOrder	= $listOrder == 'a.ordering';
?>
<?php foreach ($this->items as $i => $item) : ?>
	<tr class="row<?php echo $i % 2; ?>">
        <?php if ($this->state->get("filter.component", "") != "") : ?>
            <td class="order nowrap center hidden-phone"><?php echo JHtml::_("jdgrid.ordering", $saveOrder, $item->ordering); ?></td>
        <?php endif; ?>
		<td class="center"><?php echo JHtml::_('grid.id', $i, $item->id); ?></td>
		<td>
			<a href="<?php echo JRoute::_('index.php?option=com_jdeveloper&task=table.edit&id=' . $item->id); ?>">#__<?php echo $this->escape($item->name); ?></a>
			<br>
			<i style="color:grey; font-size:12px"><?php echo $this->escape($item->component); ?></i>
		</td>
		<td><?php echo JHtml::_("jdgrid.assigned", JRoute::_('index.php?option=com_jdeveloper&view=fields&filter[table]=' . $item->id), $this->escape($item->numberOfFields)); ?></td>
		<td>
		  <a role="menuitem" tabindex="-1" onclick="SqueezeBox.fromElement(this, {handler:'iframe', size: {x: window.getSize().x * 0.6, y: window.getSize().y * 0.8}, url:'<?php echo JRoute::_("index.php?option=com_jdeveloper&view=table&layout=form&tmpl=component&id=" . $table->id) ?>'})" class="btn btn-info">
			Form
		  </a>
		  <a role="menuitem" tabindex="-1" onclick="SqueezeBox.fromElement(this, {handler:'iframe', size: {x: window.getSize().x * 0.6, y: window.getSize().y * 0.8}, url:'<?php echo JRoute::_("index.php?option=com_jdeveloper&view=table&layout=sql&tmpl=component&id=" . $table->id) ?>'})" class="btn btn-info">
			SQL
		  </a>
		</td>
		<td><?php echo $this->escape($item->mainfield); ?></td>
		<td><?php echo $this->escape($item->plural); ?></td>
		<td><?php echo $this->escape($item->singular); ?></td>
		<td><?php echo JHtml::_("jdgrid.author", $item->created_by, $item->author_name); ?></td>
		<td><?php echo $this->escape($item->id); ?></td>
	</tr>
<?php endforeach ?>