<?php
/**
 * @package     JDeveloper
 * @subpackage  JDeveloper
 *
 * @copyright  	Copyright (C) 2014, Tilo-Lars Flasche. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;
?>
<div class="container" style="background-color: #184A7D; background-image: linear-gradient(to bottom, #17568C, #1A3867); background-repeat: repeat-x; border-top: 1px solid rgba(255, 255, 255, 0.196); padding: 5px 25px;">
	<div class="row-fluid" style="color: #ffffff;">
		<div class="span12">
			<h1>JDeveloper <?php echo $parent->get("manifest")->version; ?></h1>
			<p>Easy Joomla extension development</p>
		</div>
	</div>
</div>
<br>
<div class="container" style="color:#333333; font-size:20px;">
	<div class="row-fluid">
		<div class="span12">
			<p>Component successfully upgraded.</p>
			<p><a href="<?php echo JRoute::_("index.php?option=com_jdeveloper", false); ?>" class="btn btn-primary">Go to</a></p>
		</div>
	</div>
</div>
<br>