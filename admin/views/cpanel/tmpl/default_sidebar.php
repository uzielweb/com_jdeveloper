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

<div class="cpanel-links">
	<div class="sidebar-nav quick-icons">
		<div class="j-links-groups">
			<h2 class="nav-header"><?php echo JText::_("COM_JDEVELOPER_SUBMENU_COMPONENTS"); ?></h2>
			<ul class="j-links-group nav nav-list">
			  <li>
				<a href="<?php echo JRoute::_("index.php?option=com_jdeveloper&view=components") ?>">
				  <i class="icon-cube"></i>
				  <span class="j-links-link"><?php echo JText::_("COM_JDEVELOPER_SUBMENU_COMPONENTS"); ?></span>
				</a>
			  </li>
			  <li>
				<a href="<?php echo JRoute::_("index.php?option=com_jdeveloper&view=tables") ?>">
				  <i class="icon-cube"></i>
				  <span class="j-links-link"><?php echo JText::_("COM_JDEVELOPER_SUBMENU_TABLES"); ?></span>
				</a>
			  </li>
			  <li>
				<a href="<?php echo JRoute::_("index.php?option=com_jdeveloper&view=fields") ?>">
				  <i class="icon-cube"></i>
				  <span class="j-links-link"><?php echo JText::_("COM_JDEVELOPER_SUBMENU_FIELDS"); ?></span>
				</a>
			  </li>
			  <li>
				<a href="<?php echo JRoute::_("index.php?option=com_jdeveloper&view=formfields") ?>">
				  <i class="icon-cube"></i>
				  <span class="j-links-link"><?php echo JText::_("COM_JDEVELOPER_SUBMENU_FORMFIELDS"); ?></span>
				</a>
			  </li>
			  <li>
				<a href="<?php echo JRoute::_("index.php?option=com_jdeveloper&view=formrules") ?>">
				  <i class="icon-cube"></i>
				  <span class="j-links-link"><?php echo JText::_("COM_JDEVELOPER_SUBMENU_FORMRULES"); ?></span>
				</a>
			  </li>
			</ul>
			<h2 class="nav-header"><?php echo JText::_("COM_JDEVELOPER_SUBMENU_EXTENSIONS"); ?></h2>
			<ul class="j-links-group nav nav-list">
			  <li>
				<a href="<?php echo JRoute::_("index.php?option=com_jdeveloper&view=modules") ?>">
				  <i class="icon-cube"></i>
				  <span class="j-links-link"><?php echo JText::_("COM_JDEVELOPER_SUBMENU_MODULES"); ?></span>
				</a>
			  </li>
			  <li>
				<a href="<?php echo JRoute::_("index.php?option=com_jdeveloper&view=plugins") ?>">
				  <i class="icon-cube"></i>
				  <span class="j-links-link"><?php echo JText::_("COM_JDEVELOPER_SUBMENU_PLUGINS"); ?></span>
				</a>
			  </li>
			  <li>
				<a href="<?php echo JRoute::_("index.php?option=com_jdeveloper&view=templates") ?>">
				  <i class="icon-cube"></i>
				  <span class="j-links-link"><?php echo JText::_("COM_JDEVELOPER_SUBMENU_TEMPLATES"); ?></span>
				</a>
			  </li>
			  <li>
				<a href="<?php echo JRoute::_("index.php?option=com_jdeveloper&view=packages") ?>">
				  <i class="icon-cube"></i>
				  <span class="j-links-link"><?php echo JText::_("COM_JDEVELOPER_SUBMENU_PACKAGES"); ?></span>
				</a>
			  </li>
			</ul>
			<h2 class="nav-header"><?php echo JText::_("COM_JDEVELOPER_SUBMENU_FUNCTIONS"); ?></h2>
			<ul class="j-links-group nav nav-list">
			  <li>
				<a href="<?php echo JRoute::_("index.php?option=com_jdeveloper&view=import") ?>">
				  <i class="icon-download"></i>
				  <span class="j-links-link"><?php echo JText::_("COM_JDEVELOPER_SUBMENU_IMPORT"); ?></span>
				</a>
			  </li>
			  <li>
				<a href="<?php echo JRoute::_("index.php?option=com_jdeveloper&view=extensions") ?>">
				  <i class="icon-list-view"></i>
				  <span class="j-links-link"><?php echo JText::_("COM_JDEVELOPER_SUBMENU_EXTENSIONS"); ?></span>
				</a>
			  </li>
			</ul>
			<h2 class="nav-header"><?php echo JText::_("COM_JDEVELOPER_SUBMENU_WEB"); ?></h2>
			<ul class="j-links-group nav nav-list">
			  <li>
				<a href="http://www.joommaster.bplaced.net/index.php/jdeveloper-introduction" target="blank">
				  <i class="icon-stack"></i>
				  <span class="j-links-link"><?php echo JText::_("COM_JDEVELOPER_SUBMENU_DOCUMENTATION"); ?></span>
				</a>
			  </li>
			  <li>
				<a href="http://www.joommaster.bplaced.net/index.php/contact" target="blank">
				  <i class="icon-comments-2"></i>
				  <span class="j-links-link"><?php echo JText::_("COM_JDEVELOPER_SUBMENU_CONTACT"); ?></span>
				</a>
			  </li>
			</ul>
		</div>
	</div>
</div>