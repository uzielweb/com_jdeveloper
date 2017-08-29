<?php
/**
 * @package     JDeveloper
 * @subpackage  JDeveloper
 *
 * @copyright  	Copyright (C) 2014, Tilo-Lars Flasche. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

if (!defined('DS')) define('DS', DIRECTORY_SEPARATOR);

// Directories
define('JDeveloperARCHIVE', JPATH_COMPONENT_ADMINISTRATOR.'/archive');
define('JDeveloperCONFIG', JPATH_COMPONENT_ADMINISTRATOR.'/config');
define('JDeveloperCREATE', JPATH_COMPONENT_ADMINISTRATOR.'/create');
define('JDeveloperINSTALL', JPATH_COMPONENT_ADMINISTRATOR.'/tmp');
define('JDeveloperLAYOUTS', JPATH_COMPONENT_ADMINISTRATOR.'/layouts');
define('JDeveloperLIB', JPATH_COMPONENT_ADMINISTRATOR.'/library');
define('JDeveloperLIVE', JPATH_COMPONENT_ADMINISTRATOR.'/archive/live');
define('JDeveloperTEMPLATES', JPATH_COMPONENT_ADMINISTRATOR.'/templates');
define('JDeveloperXTD', JPATH_COMPONENT_ADMINISTRATOR.'/xtd');

// URLs
define('JDeveloperURL', JURI::root().'/administrator/components/com_jdeveloper');
define('JDeveloperARCHIVEURL', JDeveloperURL . "/archive");
define('JDeveloperLIVEURL', JDeveloperURL . "/archive/live");
?>