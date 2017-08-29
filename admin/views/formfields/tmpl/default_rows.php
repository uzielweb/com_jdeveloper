<?php
/**
 * @package     JDeveloper
 * @subpackage  Views
 *
 * @copyright  	Copyright (C) 2014, Tilo-Lars Flasche. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
$saveOrder = ($listOrder == 'a.ordering');
?>

<?php foreach ($this->items as $i => $item) : ?>
	<tr class="row<?php echo $i % 2; ?>">
		<td class="center"><?php echo JHtml::_('grid.id', $i, $item->id); ?></td>
		<td>
			<a href="<?php echo JRoute::_('index.php?option=com_jdeveloper&task=formfield.edit&id=' . $item->id); ?>"><?php echo $this->escape($item->name); ?></a>
		</td>
		<td>
			<?php echo $this->escape($item->id); ?>
		</td>
	</tr>
<?php endforeach ?>