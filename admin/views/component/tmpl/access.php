<?php
/**
 * @package     JDeveloper
 * @subpackage  Views
 *
 * @copyright  	Copyright (C) 2014, Tilo-Lars Flasche. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;
JDeveloperLoader::import("create");

echo JHtml::_("code.form", JDeveloperCreate::getInstance("component.admin.access", array("item_id" => $this->item->id))->getBuffer());