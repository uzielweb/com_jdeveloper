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
<jdeveoper>
	<component>
		<name><?php echo $this->item->name; ?></name>
		<version><?php echo $this->item->version; ?></version>
		<site><?php echo $this->item->site; ?></site>
		<display_name><?php echo $this->item->display_name; ?></display_name>
		<description><?php echo $this->item->description; ?></description>
		<params><?php echo json_encode($this->item->params); ?></params>
	</component>
</jdeveoper>