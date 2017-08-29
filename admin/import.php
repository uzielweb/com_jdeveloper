<?php
/**
 * @package     JDeveloper
 * @subpackage  JDeveloper
 *
 * @copyright  	Copyright (C) 2014, Tilo-Lars Flasche. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

require_once JDeveloperLIB . "/loader.php";
require_once JPATH_COMPONENT_ADMINISTRATOR . "/config.php";

jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');

JDeveloperLoader::import("archive");
JDeveloperLoader::import("exception");
?>