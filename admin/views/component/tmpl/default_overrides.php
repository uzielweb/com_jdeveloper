<?php
/**
 * @package     JDeveloper
 * @subpackage  Views
 *
 * @copyright  	Copyright (C) 2014, Tilo-Lars Flasche. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;
$override_admin = JRoute::_("index.php?option=com_jdeveloper&task=override.add&type=component.admin&item_id=" . $this->item->id);
$override_site = JRoute::_("index.php?option=com_jdeveloper&task=override.add&type=component.site&item_id=" . $this->item->id);
$span = $this->item->site ? "span6" : "span12";
?>
		<h2><?php echo JText::_("COM_JDEVELOPER_COMPONENT_OVERRIDES") ?></h2>
		<button data-toggle="modal" data-target="#addOverride" class="btn btn-success"><i class="icon-new"></i> <?php echo JText::_("JTOOLBAR_ADD_OVERRIDE"); ?></button>
		<a href="<?php echo JRoute::_("index.php?option=com_jdeveloper&view=overrides&filter[type]=component.admin&filter[item_id]=" . $this->item->id); ?>" class="btn btn-primary"><?php echo JText::_("COM_JDEVELOPER_OVERRIDES_MANAGE") ?></a>
		<p>&nbsp;</p>
		<div class="row-fluid">
			<div class="<?php echo $span; ?>">
				<h3><?php echo JText::_("COM_JDEVELOPER_COMPONENT_OVERRIDES_ADMIN"); ?></h3>
				<table class="table table-striped">
					<tbody>
					<?php foreach ($this->overrides_admin as $override) : ?>
						<tr>
							<td><a href="<?php echo JRoute::_("index.php?option=com_jdeveloper&task=override.edit&id=" . $override->id, false); ?>"><?php echo $override->name; ?></a></td>
						</tr>
					<?php endforeach; ?>
					</tbody>
				</table>
			</div>
			<?php if ($this->item->site) : ?>
			<div class="span6">
				<h3><?php echo JText::_("COM_JDEVELOPER_COMPONENT_OVERRIDES_SITE"); ?></h3>
				<table class="table table-striped">
					<tbody>
					<?php foreach ($this->overrides_site as $override) : ?>
						<tr>
							<td><a href="<?php echo JRoute::_("index.php?option=com_jdeveloper&task=override.edit&id=" . $override->id, false); ?>"><?php echo $override->name; ?></a></td>
						</tr>
					<?php endforeach; ?>
					</tbody>
				</table>
			</div>
			<?php endif; ?>
		</div>
		<div class="modal hide fade" id="addOverride" style="width:800px;">
			<div class="modal-header">
				<h3><?php echo JText::sprintf('COM_JDEVELOPER_COMPONENT_ADD_OVERRIDE');?></h3>
			</div>
			<div class="modal-body">
				<?php echo JText::sprintf('COM_JDEVELOPER_COMPONENT_ADD_OVERRIDE_DESC');?>
				<div class="row-fluid">
					<div class="<?php echo $span; ?>">
						<table class="table table-striped">
							<thead>
								<tr>
									<th><?php echo JText::_("COM_JDEVELOPER_COMPONENT_OVERRIDES_ADMIN"); ?></th>
								</tr>					
							</thead>
							<tbody>
								<tr>
									<td><a href="<?php echo $override_admin. "&name=admin.access.xml"; ?>">access.xml</a></td>
								</tr>
								<tr>
									<td><a href="<?php echo $override_admin. "&name=admin.config.xml"; ?>">config.xml</a></td>
								</tr>
								<tr>
									<td><a href="<?php echo $override_admin. "&name=admin.component.php"; ?>"><?php echo $this->item->name; ?>.php</a></td>
								</tr>
								<tr>
									<td><a href="<?php echo $override_admin. "&name=admin.controller.php"; ?>">controller.php</a></td>
								</tr>
								<tr>
									<td><a href="<?php echo $override_admin. "&name=admin.helpers.component.php"; ?>">helpers/<?php echo $this->item->name; ?>.php</a></td>
								</tr>
							</tbody>
						</table>
					</div>
					<?php if ($this->item->site) : ?>
					<div class="span6">
						<table class="table table-striped">
							<thead>
								<tr>
									<th><?php echo JText::_("COM_JDEVELOPER_COMPONENT_OVERRIDES_SITE"); ?></th>
								</tr>					
							</thead>
							<tbody>
								<tr>
									<td><a href="<?php echo $override_site. "&name=site.component.php"; ?>"><?php echo $this->item->name; ?>.php</a></td>
								</tr>
								<tr>
									<td><a href="<?php echo $override_site. "&name=site.controller.php"; ?>">controller.php</a></td>
								</tr>
								<tr>
									<td><a href="<?php echo $override_site. "&name=site.helpers.route.php"; ?>">helpers/route.php</a></td>
								</tr>
								<tr>
									<td><a href="<?php echo $override_site. "&name=site.router.php"; ?>">router.php</a></td>
								</tr>
							</tbody>
						</table>					
					</div>
					<?php endif; ?>
				</div>
			</div>
			<div class="modal-footer">
				<button class="btn" type="button" data-dismiss="modal">
					<?php echo JText::_('JTOOLBAR_CANCEL'); ?>
				</button>
			</div>
		</div>
