<?php
/**
 * @package     JDeveloper
 * @subpackage  Views
 *
 * @copyright  	Copyright (C) 2014, Tilo-Lars Flasche. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;
$override_add = JRoute::_("index.php?option=com_jdeveloper&task=override.add&type=plugin&item_id=" . $this->item->id);
?>
<div class="row-fluid">
	<div class="span12">
		<h2><?php echo JText::_("COM_JDEVELOPER_PLUGIN_OVERRIDES") ?></h2>
		<button data-toggle="modal" data-target="#addOverride" class="btn btn-success"><i class="icon-new"></i> <?php echo JText::_("JTOOLBAR_ADD_OVERRIDE"); ?></button>
		<a href="<?php echo JRoute::_("index.php?option=com_jdeveloper&view=overrides&filter[type]=plugin&filter[item_id]=" . $this->item->id); ?>" class="btn btn-primary"><?php echo JText::_("COM_JDEVELOPER_OVERRIDES_MANAGE") ?></a>
		<p>&nbsp;</p>
		<table class="table table-striped">
			<thead>
				<tr>
					<th><?php echo JText::_("COM_JDEVELOPER_PLUGIN_FIELD_OVERRIDE_NAME_LABEL") ?></th>
					<th><?php echo JText::_("JTOOLBAR_EDIT") ?></th>
				</tr>
			</thead>
			<tbody>
			<?php foreach ($this->overrides as $override) : ?>
				<tr>
					<td><a href="<?php echo JRoute::_("index.php?option=com_jdeveloper&task=override.edit&id=" . $override->id, false); ?>"><?php echo $override->name; ?></a></td>
					<td></td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
		<div class="modal hide fade" id="addOverride" style="width:600px;">
			<div class="modal-header">
				<h3><?php echo JText::sprintf('COM_JDEVELOPER_PLUGIN_ADD_OVERRIDE');?></h3>
			</div>
			<div class="modal-body">
				<?php echo JText::sprintf('COM_JDEVELOPER_PLUGIN_ADD_OVERRIDE_DESC');?>
				<table class="table table-striped">
					<thead>
						<tr>
							<th><?php echo JText::_("COM_JDEVELOPER_PLUGIN_FIELD_OVERRIDE_NAME_LABEL"); ?></th>
						</tr>					
					</thead>
					<tbody>
						<tr>
							<td><a href="<?php echo $override_add. "&name=" . $this->item->folder . ".php"; ?>"><?php echo $this->item->folder; ?>.php</a></td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="modal-footer">
				<button class="btn" type="button" data-dismiss="modal">
					<?php echo JText::_('JTOOLBAR_CANCEL'); ?>
				</button>
			</div>
		</div>
	</div>
</div>