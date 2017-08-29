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
<div class="modal hide fade" id="newFolderModal" style="width:40%;">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&#215;</button>
		<h3><?php echo JText::_('COM_JDEVELOPER_PLUGIN_TITLE_NEW_FOLDER');?></h3>
	</div>
	<div class="modal-body">
		<p><?php echo JText::_('COM_JDEVELOPER_PLUGIN_TITLE_NEW_FOLDER_DESC'); ?></p>
		<input type="text" name="jform[name]" value="" />
		<br><br>
	</div>
	<div class="modal-footer">
		<button class="btn" type="button" onclick="document.id('batch_site').value='';" data-dismiss="modal">
			<?php echo JText::_('JCANCEL'); ?>
		</button>
		<button class="btn btn-primary" type="submit" onclick="Joomla.submitbutton('plugins.createFolder');">
			<?php echo JText::_('JGLOBAL_BATCH_PROCESS'); ?>
		</button>
	</div>
</div>
