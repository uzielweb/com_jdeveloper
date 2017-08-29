<?php
/**
 * @package     JDeveloper
 * @subpackage  Views
 *
 * @copyright  	Copyright (C) 2014, Tilo-Lars Flasche. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;
$active_css = "background-color:#0088CC; color:#FFFFFF;";
$active = $displayData["active"];
$input = JFactory::getApplication()->input;

if ($input->get("tmpl") != "component") :
?>

<div class="cpanel-links">
	<div class="sidebar-nav quick-icons">
		<div class="j-links-groups">
			<h2 class="nav-header"><?php echo JText::_("JDEVELOPER"); ?></h2>
			<ul class="j-links-group nav nav-list">
			  <li>
				<a href="<?php echo JRoute::_("index.php?option=com_jdeveloper&view=jdeveloper") ?>" style="<?php echo $active == "jdeveloper" ? $active_css : ""; ?>">
				  <i class="icon-cube"></i>
				  <span class="j-links-link"><?php echo JText::_("COM_JDEVELOPER_SUBMENU_JDEVELOPER"); ?></span> 
				  <?php if (null !== $item = JDeveloperHelper::getUpdate()) : ?><span class="label label-info"><?php echo JText::_("COM_JDEVELOPER_SUBMENU_UPDATE"); ?></span><?php endif; ?>
				</a>
			  </li>
			</ul>
			<h2 class="nav-header"><?php echo JText::_("COM_JDEVELOPER_SUBMENU_EXTENSIONS"); ?></h2>
			<ul class="j-links-group nav nav-list">
			  <li>
				<a href="<?php echo JRoute::_("index.php?option=com_jdeveloper&view=components") ?>" style="<?php echo $active == "components" ? $active_css : ""; ?>">
				  <i class="icon-cube"></i>
				  <span class="j-links-link"><?php echo JText::_("COM_JDEVELOPER_SUBMENU_COMPONENTS"); ?></span>
				</a>
			  </li>
			  <li>
				<a href="<?php echo JRoute::_("index.php?option=com_jdeveloper&view=modules") ?>" style="<?php echo $active == "modules" ? $active_css : ""; ?>">
				  <i class="icon-cube"></i>
				  <span class="j-links-link"><?php echo JText::_("COM_JDEVELOPER_SUBMENU_MODULES"); ?></span>
				</a>
			  </li>
			  <li>
				<a href="<?php echo JRoute::_("index.php?option=com_jdeveloper&view=plugins") ?>" style="<?php echo $active == "plugins" ? $active_css : ""; ?>">
				  <i class="icon-cube"></i>
				  <span class="j-links-link"><?php echo JText::_("COM_JDEVELOPER_SUBMENU_PLUGINS"); ?></span>
				</a>
			  </li>
			  <li>
				<a href="<?php echo JRoute::_("index.php?option=com_jdeveloper&view=templates") ?>" style="<?php echo $active == "templates" ? $active_css : ""; ?>">
				  <i class="icon-cube"></i>
				  <span class="j-links-link"><?php echo JText::_("COM_JDEVELOPER_SUBMENU_TEMPLATES"); ?></span>
				</a>
			  </li>
			  <li>
				<a href="<?php echo JRoute::_("index.php?option=com_jdeveloper&view=packages") ?>" style="<?php echo $active == "packages" ? $active_css : ""; ?>">
				  <i class="icon-cube"></i>
				  <span class="j-links-link"><?php echo JText::_("COM_JDEVELOPER_SUBMENU_PACKAGES"); ?></span>
				</a>
			  </li>
			</ul>
			<h2 class="nav-header"><?php echo JText::_("COM_JDEVELOPER_SUBMENU_FORMS"); ?></h2>
			<ul class="j-links-group nav nav-list">
			  <li>
				<a href="<?php echo JRoute::_("index.php?option=com_jdeveloper&view=forms") ?>" style="<?php echo $active == "forms" ? $active_css : ""; ?>">
				  <i class="icon-cube"></i>
				  <span class="j-links-link"><?php echo JText::_("COM_JDEVELOPER_SUBMENU_FORMS"); ?></span>
				</a>
			  </li>
			  <li>
				<a href="<?php echo JRoute::_("index.php?option=com_jdeveloper&view=formfields") ?>" style="<?php echo $active == "formfields" ? $active_css : ""; ?>">
				  <i class="icon-cube"></i>
				  <span class="j-links-link"><?php echo JText::_("COM_JDEVELOPER_SUBMENU_FORMFIELDS"); ?></span>
				</a>
			  </li>
			  <li>
				<a href="<?php echo JRoute::_("index.php?option=com_jdeveloper&view=formrules") ?>" style="<?php echo $active == "formrules" ? $active_css : ""; ?>">
				  <i class="icon-cube"></i>
				  <span class="j-links-link"><?php echo JText::_("COM_JDEVELOPER_SUBMENU_FORMRULES"); ?></span>
				</a>
			  </li>
			</ul>
			<h2 class="nav-header"><?php echo JText::_("COM_JDEVELOPER_SUBMENU_FUNCTIONS"); ?></h2>
			<ul class="j-links-group nav nav-list">
			  <li>
				<a href="<?php echo JRoute::_("index.php?option=com_jdeveloper&view=overrides") ?>" style="<?php echo $active == "overrides" ? $active_css : ""; ?>">
				  <i class="icon-pencil-2"></i>
				  <span class="j-links-link"><?php echo JText::_("COM_JDEVELOPER_SUBMENU_OVERRIDES"); ?></span>
				</a>
			  </li>
			  <li>
				<a href="<?php echo JRoute::_("index.php?option=com_jdeveloper&view=import") ?>" style="<?php echo $active == "import" ? $active_css : ""; ?>">
				  <i class="icon-download"></i>
				  <span class="j-links-link"><?php echo JText::_("COM_JDEVELOPER_SUBMENU_IMPORT"); ?></span>
				</a>
			  </li>
			  <li>
				<a href="<?php echo JRoute::_("index.php?option=com_jdeveloper&view=extensions") ?>" style="<?php echo $active == "extensions" ? $active_css : ""; ?>">
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
<?php endif; ?>