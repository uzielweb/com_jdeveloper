<?php##{start_header}##
/**
 * @package     JDeveloper
 * @subpackage  Templates.Component
 *
 * @copyright  	Copyright (C) 2014, Tilo-Lars Flasche. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;##{end_header}##
##Header##

require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/##component##.php';##{start_site}####{start_catid}##
require_once JPATH_COMPONENT_SITE.'/helpers/category.php';##{end_catid}##
require_once JPATH_COMPONENT_SITE.'/helpers/route.php';##{end_site}##

$controller	= JControllerLegacy::getInstance('##Component##');
$input = JFactory::getApplication()->input;##{start_site}##

$lang = JFactory::getLanguage();
$lang->load('joomla', JPATH_ADMINISTRATOR);

JHtml::_('bootstrap.loadCss');
JHtml::_('bootstrap.framework');##{end_site}####{start_admin}##

if (!JFactory::getUser()->authorise('core.manage', 'com_jdeveloper'))
{
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}##{end_admin}##

try {
	$controller->execute($input->get('task'));
} catch (Exception $e) {
	$controller->setRedirect(JURI::base(), $e->getMessage(), 'error');
}

$controller->redirect();
?>