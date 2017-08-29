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

// Include the syndicate functions only once
require_once __DIR__ . '/helper.php';##{start_table}##
$items = Mod##Module##Helper::getItems();##{end_table}##
$item = Mod##Module##Helper::getItem();

require JModuleHelper::getLayoutPath('mod_##module##', $params->get('layout', 'default'));