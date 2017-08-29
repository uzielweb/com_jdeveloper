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
?>

<h2>
	<?php echo JText::_('COM_##COMPONENT##_##TABLE##_VIEW_##SINGULAR##_TITLE'); ?>: <i><?php echo $this->item->##mainfield##; ?></i>
	<span class="pull-right" style="font-weight:300; font-size:15px;">[<a href="<?php echo JRoute::_('index.php?option=com_##component##&task=##singular##.edit&id=' . (int) $this->item->id); ?>"><?php echo JText::_('JACTION_EDIT') ?></a>]</span>
</h2>

<table class="table table-striped">
	<tbody>##table_body##
		<tr>
			<td>##PK##</td>
			<td><?php echo $this->escape($this->item->##pk##); ?></td>
		</tr>
	</tbody>
</table>
<p><a href="index.php?option=com_##component##&view=##plural##"><?php echo JText::_('JPREVIOUS'); ?></a></p>