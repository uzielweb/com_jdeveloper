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

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers');
$this->subtemplatename = 'items';
echo JLayoutHelper::render('joomla.content.category_default', $this);
