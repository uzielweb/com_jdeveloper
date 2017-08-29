<?php
/**
 * @package     JDeveloper
 * @subpackage  Views
 *
 * @copyright  	Copyright (C) 2014, Tilo-Lars Flasche. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */
defined('_JEXEC') or die;

$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
$saveOrder = ($listOrder == 'a.ordering');
?>
<?php foreach ($this->items as $i => $item) : ?>
    <tr class="row<?php echo $i % 2; ?>">
        <?php if ($this->state->get("filter.table", "") != "") : ?>
            <td class="order nowrap center hidden-phone"><?php echo JHtml::_("jdgrid.ordering", $saveOrder, $item->ordering); ?></td>
        <?php endif; ?>
        <td class="center"><?php echo JHtml::_('grid.id', $i, $item->id); ?></td>
        <td>
            <a href="<?php echo JRoute::_('index.php?option=com_jdeveloper&task=field.edit&id=' . $item->id); ?>"><?php echo $this->escape($item->name); ?></a>
        </td>
        <td>
            <?php
            echo $this->escape($item->table) . '<br><i style="color:grey; font-size:12px">' . $this->escape($item->component) . '</i>';
            ?>
        </td>
        <td>
            <?php if (!empty($item->jdeveloper_formfield_id)) : ?>
                <a href="<?php echo JRoute::_("index.php?option=com_jdeveloper&task=formfield.edit&id=" . $this->escape($item->jdeveloper_formfield_id)) ?>">
                    <?php echo ucfirst($this->escape($item->type)); ?>
                </a>
            <?php else : ?>
                <?php echo ucfirst($this->escape($item->type)); ?>
            <?php endif; ?>
            <br><i style="color:grey; font-size:12px;"><?php echo $this->escape($item->dbtype) . '(' . $item->maxlength . ')'; ?></i>
        </td>
        <td>
            <?php if (!empty($item->jdeveloper_formrule_id)) : ?>
                <a href="<?php echo JRoute::_("index.php?option=com_jdeveloper&task=formrule.edit&id=" . $this->escape($item->jdeveloper_formrule_id)) ?>">
                    <?php echo ucfirst($this->escape($item->rule)); ?>
                </a>
            <?php else : ?>
                <?php echo ucfirst($this->escape($item->rule)); ?>
            <?php endif; ?>
        </td>
        <td><?php echo $this->escape($item->label); ?></td>
        <td>
            <?php
            if (strlen($this->escape($item->description)) > 50)
                echo substr($this->escape($item->description), 0, 50) . " ...";
            else
                echo $this->escape($item->description);
            ?>
        </td>
        <td><?php echo JHtml::_("jdgrid.author", $item->created_by, $item->author_name); ?></td>
        <td><?php echo $this->escape($item->id); ?></td>
    </tr>
<?php endforeach; ?>
