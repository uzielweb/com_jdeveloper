<?php
/**
 * @package     JDeveloper
 * @subpackage  Views
 *
 * @copyright  	Copyright (C) 2014, Tilo-Lars Flasche. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

$components = JModelLegacy::getInstance("Components", "JDeveloperModel")->getItems();
$modules = JModelLegacy::getInstance("Modules", "JDeveloperModel")->getItems();
$plugins = JModelLegacy::getInstance("Plugins", "JDeveloperModel")->getItems();
$templates = JModelLegacy::getInstance("Templates", "JDeveloperModel")->getItems();
//$tables = JModelLegacy::getInstance("Tables", "JDeveloperModel")->getComponentTables($this->item->id);
?>
<div class="modal hide fade" id="switchItem" style="width:800px;">
	<div class="modal-header">
		<h3><?php echo JText::sprintf('COM_JDEVELOPER_OVERRIDE_SWITCH_ITEM');?></h3>
	</div>
	<div class="modal-body">
		<ul class="nav nav-pills nav-justified">
			<li class="active"><a href="#components" data-toggle="pill"><?php echo JText::_("COM_JDEVELOPER_OVERRIDES_COMPONENTS"); ?></a></li>
			<li><a href="#modules" data-toggle="pill"><?php echo JText::_("COM_JDEVELOPER_OVERRIDES_MODULES"); ?></a></li>
			<li><a href="#plugins" data-toggle="pill"><?php echo JText::_("COM_JDEVELOPER_OVERRIDES_PLUGINS"); ?></a></li>
			<li><a href="#templates" data-toggle="pill"><?php echo JText::_("COM_JDEVELOPER_OVERRIDES_TEMPLATES"); ?></a></li>
		</ul>
		<div class="tab-content">
			<div class="tab-pane active" id="components">
				<table class="table">
					<tbody>
						<?php foreach ($components as $component) : ?>
						<tr>
							<td><?php echo $component->display_name; ?></td>
							<td><a href="<?php echo JRoute::_("index.php?option=com_jdeveloper&view=overrides&filter[type]=component.admin&filter[item_id]=" . $component->id, false); ?>">Admin</a></td>
							<td><a href="<?php echo JRoute::_("index.php?option=com_jdeveloper&view=overrides&filter[type]=component.site&filter[item_id]=" . $component->id, false); ?>">Site</a></td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
			<div class="tab-pane" id="modules">
				<?php foreach ($modules as $module) : ?>
					<ul class="nav menu nav-list">
						<li><a href="<?php echo JRoute::_("index.php?option=com_jdeveloper&view=overrides&filter[type]=module&filter[item_id]=" . $module->id, false); ?>"><?php echo $module->display_name; ?></a></li>
					</ul>
				<?php endforeach; ?>
			</div>
			<div class="tab-pane" id="plugins">
				<?php foreach ($plugins as $plugin) : ?>
					<ul class="nav menu nav-list">
						<li><a href="<?php echo JRoute::_("index.php?option=com_jdeveloper&view=overrides&filter[type]=plugin&filter[item_id]=" . $plugin->id, false); ?>"><?php echo $plugin->display_name; ?></a></li>
					</ul>
				<?php endforeach; ?>
			</div>
			<div class="tab-pane" id="templates">
				<?php foreach ($templates as $template) : ?>
					<ul class="nav menu nav-list">
						<li><a href="<?php echo JRoute::_("index.php?option=com_jdeveloper&view=overrides&filter[type]=template&filter[item_id]=" . $template->id, false); ?>"><?php echo $template->display_name; ?></a></li>
					</ul>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button class="btn" type="button" data-dismiss="modal">
			<?php echo JText::_('JTOOLBAR_CANCEL'); ?>
		</button>
	</div>
</div>