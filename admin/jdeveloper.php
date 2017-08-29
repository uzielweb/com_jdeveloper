<?php
/**
 * JDeveloper execute script
 *
 * @package     JDeveloper
 * @subpackage  JDeveloper
 *
 * @copyright  	Copyright (C) 2014, Tilo-Lars Flasche. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

require_once JPATH_COMPONENT_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'defines.php';
require_once JPATH_COMPONENT_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'import.php';

JPluginHelper::importPlugin('jdeveloper');

JLoader::register('JDeveloperHelper', __DIR__ . '/helpers/jdeveloper.php');
$controller	= JControllerLegacy::getInstance('JDeveloper');

// Check access rights
if (!JFactory::getUser()->authorise('core.manage', 'com_jdeveloper'))
{
	$controller->setRedirect(JRoute::_('index.php', false), JText::_('JERROR_ALERTNOAUTHOR'), 'error');
}

// Execute updates
$folder = JPATH_COMPONENT_ADMINISTRATOR . "/updates";

if (JFolder::exists($folder)) {
	$updates = JFolder::files($folder);
	
	if (is_array($updates)) {
		foreach ($updates as $update) {
			require_once JPATH_COMPONENT_ADMINISTRATOR . "/updates/" . $update;
			$classname = "com_jdeveloper_update_" . str_replace(".", "_", basename($update));
			$classname = str_replace("_php", "", $classname);
			$class = new $classname();
			$class->update();
			JFile::delete(JPATH_COMPONENT_ADMINISTRATOR . "/updates/" . $update);
		}
	}
}

// Execute task
try {
	$controller->execute(JFactory::getApplication()->input->get('task'));
} catch (Exception $e) {
	$controller->setRedirect(JRoute::_('index.php?option=com_jdeveloper&view=components', false), $e->getMessage(), 'error');
}

$controller->redirect();