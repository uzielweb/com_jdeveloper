<?php
/**
 * @package     JDeveloper
 * @subpackage  Views
 *
 * @copyright  	Copyright (C) 2014, Tilo-Lars Flasche. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

?>
<div class="row-fluid">
	<div class="span6">
		<h2><?php echo JText::_("COM_JDEVELOPER_COMPONENT_INFO") ?></h2>
		<table class="table">
			<tbody>
				<tr>
					<td><?php echo JText::_("COM_JDEVELOPER_COMPONENT_FIELD_VERSION_LABEL") ?>:</td>
					<td><?php echo $this->item->version; ?></td>
				</tr>
				<tr>
					<td><?php echo JText::_("COM_JDEVELOPER_COMPONENT_FIELD_SITE_LABEL") ?>:</td>
					<td><span class="badge badge-<?php echo $this->item->site ? "success" : "important"; ?>"><?php echo $this->item->site ? JText::_("JYES") : JText::_("JNO"); ?></span></td>
				</tr>
				<tr>
					<td><?php echo JText::_("COM_JDEVELOPER_COMPONENT_FIELD_INSTALLED_LABEL") ?>:</td>
					<td>
						<span class="badge badge-<?php echo $this->item->installed ? "success" : "important"; ?>"><?php echo $this->item->installed ? JText::_("JYES") : JText::_("JNO"); ?></span>
						<?php if ($this->item->installed) : ?>
							<a class="badge badge-info" href="<?php echo JURI::base(); ?>index.php?option=com_<?php echo $this->item->name; ?>"><?php echo JText::_("COM_JDEVELOPER_BACKEND") ?></a>
							<?php if ($this->item->site) : ?>
								<a class="badge badge-info" href="<?php echo JURI::root(); ?>index.php?option=com_<?php echo $this->item->name; ?>"><?php echo JText::_("COM_JDEVELOPER_FRONTEND") ?></a>
							<?php endif; ?>
						<?php endif; ?>
					</td>
				</tr>
				<tr>
					<td><?php echo JText::_("COM_JDEVELOPER_COMPONENT_FIELD_LANGUAGES_LABEL") ?>:</td>
					<td>
					<?php foreach ($this->item->params['languages'] as $lang) : ?>
						<span class="badge badge-info"><?php echo $lang; ?></span>
					<?php endforeach; ?>
					</td>
				</tr>
				<tr>
					<td><?php echo JText::_("COM_JDEVELOPER_COMPONENT_FIELD_AUTHOR_LABEL") ?>:</td>
					<td>
						<i class="icon-user"></i>
						<?php echo $this->item->params['author']; ?>
						<?php echo !empty($this->item->params['author_email']) ? "(" . $this->item->params['author_email'] . ")" : ""; ?>
						<br>
						<i class="icon-home"></i>
						<?php echo $this->item->params['author_url']; ?>
						<br>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="span1">&nbsp;</div>
	<div class="span5">
		<h2><?php echo JText::_("COM_JDEVELOPER_COMPONENT_DOWNLOAD") ?></h2>
		<table class="table">
			<tbody>
				<tr>
					<td><?php echo JHtml::_("jdgrid.archives", "com_", $this->item->name); ?></td>
				</tr>
			</tbody>
		</table>
	</div>
</div>