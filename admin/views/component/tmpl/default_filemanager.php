<?php
/**
 * @package     JDeveloper
 * @subpackage  Views
 *
 * @copyright  	Copyright (C) 2014, Tilo-Lars Flasche. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */
defined ( '_JEXEC' ) or die ();

JHtml::_ ( 'bootstrap.tooltip' );
JHtml::_ ( 'behavior.framework' );
JHtml::_ ( 'behavior.formvalidation' );
JHtml::_ ( 'behavior.modal' );
JHtml::_ ( 'behavior.multiselect' );
JHtml::_ ( 'dropdown.init' );
JHtml::_ ( 'formbehavior.chosen', 'select' );

$tables = JModelLegacy::getInstance("Tables", "JDeveloperModel")->getComponentTables($this->item->id);
$model_table = JModelLegacy::getInstance("Table", "JDeveloperModel");
$span = $this->item->site ? "span6" : "span12";
?>

<h2><?php echo JText::_("COM_JDEVELOPER_COMPONENT_FILEMANAGER") ?></h2>
<p>&nbsp;</p>

<?php foreach ($tables as $table) : ?>
<?php if ($model_table->isInstalled($table->id)) : ?>
<h3><?php echo $table->name; ?></h3>
<div class="row-fluid">
	<div class="<?php echo $span; ?>">
		<h3><?php echo JText::_("COM_JDEVELOPER_COMPONENT_FILEMANAGER_ADMIN"); ?></h3>
		<table class="table table-striped">
			<tbody>
				<tr>
					<td><?php echo JText::_("COM_JDEVELOPER_COMPONENT_CONTROLLER"); ?></td>
					<td>
						<a role="menuitem" tabindex="-1" 
							onclick="SqueezeBox.fromElement(this, {handler:'iframe', size: {x: window.getSize().x * 0.6, y: window.getSize().y * 0.8},
								url:'<?php echo JRoute::_("index.php?option=com_jdeveloper&view=fileeditor&client=admin&type=component&name=" . $this->item->name . "&path=controllers." . $table->plural . ".php") ?>'})"
								class="btn">
							<i class="icon-edit"></i> <?php echo JText::_("COM_JDEVELOPER_TABLE_PLURAL"); ?>
						</a>
						<a role="menuitem" tabindex="-1" 
							onclick="SqueezeBox.fromElement(this, {handler:'iframe', size: {x: window.getSize().x * 0.6, y: window.getSize().y * 0.8},
								url:'<?php echo JRoute::_("index.php?option=com_jdeveloper&view=fileeditor&client=admin&type=component&name=" . $this->item->name . "&path=controllers." . $table->singular . ".php") ?>'})"
								class="btn">
							<i class="icon-edit"></i> <?php echo JText::_("COM_JDEVELOPER_TABLE_SINGULAR"); ?>
						</a>
					</td>
				</tr>
				<tr>
					<td><?php echo JText::_("COM_JDEVELOPER_COMPONENT_TABLE"); ?></td>
					<td>
						<a role="menuitem" tabindex="-1" 
							onclick="SqueezeBox.fromElement(this, {handler:'iframe', size: {x: window.getSize().x * 0.6, y: window.getSize().y * 0.8},
								url:'<?php echo JRoute::_("index.php?option=com_jdeveloper&view=fileeditor&client=admin&type=component&name=" . $this->item->name . "&path=tables." . $table->singular . ".php") ?>'})"
								class="btn">
							<i class="icon-edit"></i> <?php echo JText::_("JTOOLBAR_EDIT"); ?>
						</a>
					</td>
				</tr>
				<tr>
					<td><?php echo JText::_("COM_JDEVELOPER_COMPONENT_MODEL"); ?></td>
						<td>
							<a role="menuitem" tabindex="-1" 
								onclick="SqueezeBox.fromElement(this, {handler:'iframe', size: {x: window.getSize().x * 0.6, y: window.getSize().y * 0.8},
									url:'<?php echo JRoute::_("index.php?option=com_jdeveloper&view=fileeditor&client=admin&type=component&name=" . $this->item->name . "&path=models." . $table->plural . ".php") ?>'})"
									class="btn">
								<i class="icon-edit"></i> <?php echo JText::_("COM_JDEVELOPER_TABLE_PLURAL"); ?>
							</a>
							<a role="menuitem" tabindex="-1" 
								onclick="SqueezeBox.fromElement(this, {handler:'iframe', size: {x: window.getSize().x * 0.6, y: window.getSize().y * 0.8},
									url:'<?php echo JRoute::_("index.php?option=com_jdeveloper&view=fileeditor&client=admin&type=component&name=" . $this->item->name . "&path=models." . $table->singular . ".php") ?>'})"
									class="btn">
								<i class="icon-edit"></i> <?php echo JText::_("COM_JDEVELOPER_TABLE_SINGULAR"); ?>
							</a>
						</td>
					</tr>
					<tr>
						<td><?php echo JText::_("COM_JDEVELOPER_COMPONENT_FORM"); ?></td>
						<td>
							<a role="menuitem" tabindex="-1" 
								onclick="SqueezeBox.fromElement(this, {handler:'iframe', size: {x: window.getSize().x * 0.6, y: window.getSize().y * 0.8},
									url:'<?php echo JRoute::_("index.php?option=com_jdeveloper&view=fileeditor&client=admin&type=component&name=" . $this->item->name . "&path=models.forms." . $table->singular . ".xml") ?>'})"
									class="btn">
								<i class="icon-edit"></i> <?php echo JText::_("COM_JDEVELOPER_TABLE_FORM"); ?>
							</a>
							<a role="menuitem" tabindex="-1" 
								onclick="SqueezeBox.fromElement(this, {handler:'iframe', size: {x: window.getSize().x * 0.6, y: window.getSize().y * 0.8},
									url:'<?php echo JRoute::_("index.php?option=com_jdeveloper&view=fileeditor&client=admin&type=component&name=" . $this->item->name . "&path=models.forms.filter_" . $table->plural . ".xml") ?>'})"
									class="btn">
								<i class="icon-edit"></i> <?php echo JText::_("COM_JDEVELOPER_TABLE_FILTER"); ?>
							</a>
					</td>
				</tr>
				<tr>
					<td><?php echo JText::_("COM_JDEVELOPER_COMPONENT_VIEW"); ?></td>
					<td>
						<a role="menuitem" tabindex="-1" 
							onclick="SqueezeBox.fromElement(this, {handler:'iframe', size: {x: window.getSize().x * 0.6, y: window.getSize().y * 0.8},
								url:'<?php echo JRoute::_("index.php?option=com_jdeveloper&view=fileeditor&client=admin&type=component&name=" . $this->item->name . "&path=views." . $table->plural . ".view.html.php") ?>'})"
								class="btn">
							<i class="icon-edit"></i> <?php echo JText::_("COM_JDEVELOPER_TABLE_PLURAL"); ?>
						</a>
						<a role="menuitem" tabindex="-1" 
							onclick="SqueezeBox.fromElement(this, {handler:'iframe', size: {x: window.getSize().x * 0.6, y: window.getSize().y * 0.8},
								url:'<?php echo JRoute::_("index.php?option=com_jdeveloper&view=fileeditor&client=admin&type=component&name=" . $this->item->name . "&path=views." . $table->singular . ".view.html.php") ?>'})"
								class="btn">
							<i class="icon-edit"></i> <?php echo JText::_("COM_JDEVELOPER_TABLE_SINGULAR"); ?>
						</a>
					</td>
				</tr>
			</tbody>
		</table>
	</div>	
	<?php if ($this->item->site) : ?>
	<div class="span6">
		<h3><?php echo JText::_("COM_JDEVELOPER_COMPONENT_FILEMANAGER_SITE"); ?></h3>
		<table class="table table-striped">
			<tbody>
				<tr>
					<td><?php echo JText::_("COM_JDEVELOPER_COMPONENT_CONTROLLER"); ?></td>
					<td>
						<a role="menuitem" tabindex="-1" 
							onclick="SqueezeBox.fromElement(this, {handler:'iframe', size: {x: window.getSize().x * 0.6, y: window.getSize().y * 0.8},
								url:'<?php echo JRoute::_("index.php?option=com_jdeveloper&view=fileeditor&client=site&type=component&name=" . $this->item->name . "&path=controllers." . $table->plural . ".php") ?>'})"
								class="btn">
							<i class="icon-edit"></i> <?php echo JText::_("COM_JDEVELOPER_TABLE_PLURAL"); ?>
						</a>
						<a role="menuitem" tabindex="-1" 
							onclick="SqueezeBox.fromElement(this, {handler:'iframe', size: {x: window.getSize().x * 0.6, y: window.getSize().y * 0.8},
								url:'<?php echo JRoute::_("index.php?option=com_jdeveloper&view=fileeditor&client=site&type=component&name=" . $this->item->name . "&path=controllers." . $table->singular . ".php") ?>'})"
								class="btn">
							<i class="icon-edit"></i> <?php echo JText::_("COM_JDEVELOPER_TABLE_SINGULAR"); ?>
						</a>
					</td>
				</tr>
				<tr>
					<td><?php echo JText::_("COM_JDEVELOPER_COMPONENT_MODEL"); ?></td>
					<td>
						<a role="menuitem" tabindex="-1" 
							onclick="SqueezeBox.fromElement(this, {handler:'iframe', size: {x: window.getSize().x * 0.6, y: window.getSize().y * 0.8},
								url:'<?php echo JRoute::_("index.php?option=com_jdeveloper&view=fileeditor&client=site&type=component&name=" . $this->item->name . "&path=models." . $table->plural . ".php") ?>'})"
								class="btn">
							<i class="icon-edit"></i> <?php echo JText::_("COM_JDEVELOPER_TABLE_PLURAL"); ?>
						</a>
						<a role="menuitem" tabindex="-1" 
							onclick="SqueezeBox.fromElement(this, {handler:'iframe', size: {x: window.getSize().x * 0.6, y: window.getSize().y * 0.8},
								url:'<?php echo JRoute::_("index.php?option=com_jdeveloper&view=fileeditor&client=site&type=component&name=" . $this->item->name . "&path=models." . $table->singular . ".php") ?>'})"
								class="btn">
							<i class="icon-edit"></i> <?php echo JText::_("COM_JDEVELOPER_TABLE_SINGULAR"); ?>
						</a>
					</td>
					</td>
				</tr>
				<tr>
					<td><?php echo JText::_("COM_JDEVELOPER_COMPONENT_VIEW"); ?></td>
					<td>
						<a role="menuitem" tabindex="-1" 
							onclick="SqueezeBox.fromElement(this, {handler:'iframe', size: {x: window.getSize().x * 0.6, y: window.getSize().y * 0.8},
								url:'<?php echo JRoute::_("index.php?option=com_jdeveloper&view=fileeditor&client=site&type=component&name=" . $this->item->name . "&path=views." . $table->plural . ".view.html.php") ?>'})"
								class="btn">
							<i class="icon-edit"></i> <?php echo JText::_("COM_JDEVELOPER_TABLE_PLURAL"); ?>
						</a>
						<a role="menuitem" tabindex="-1" 
							onclick="SqueezeBox.fromElement(this, {handler:'iframe', size: {x: window.getSize().x * 0.6, y: window.getSize().y * 0.8},
								url:'<?php echo JRoute::_("index.php?option=com_jdeveloper&view=fileeditor&client=site&type=component&name=" . $this->item->name . "&path=views." . $table->singular . ".view.html.php") ?>'})"
								class="btn">
							<i class="icon-edit"></i> <?php echo JText::_("COM_JDEVELOPER_TABLE_SINGULAR"); ?>
						</a>
					</td>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	<?php endif; ?>	
</div>
<?php endif; ?>
<?php endforeach; ?>