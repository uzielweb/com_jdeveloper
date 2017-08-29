<?php##{start_header}##
/**
 * @package     JDeveloper
 * @subpackage  Templates.Module
 *
 * @copyright  	Copyright (C) 2014, Tilo-Lars Flasche. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;##{end_header}##
##Header##
?>

<h2><?php echo JText::_('MOD_##MODULE##_TITLE'); ?></h2>##{start_table}##

<table class="table table-striped">
	<thead>
		<tr>##thead##
		</tr>
	</thead>
	<tbody>
		<?php foreach ($items as $i => $item) : ?>
		<tr class="row<?php echo $i % 2; ?>">##tbody##
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>##{end_table}##