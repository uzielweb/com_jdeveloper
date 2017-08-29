<?php
/**
 * @package     JDeveloper
 * @subpackage  Helpers
 *
 * @copyright  	Copyright (C) 2014, Tilo-Lars Flasche. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

/**
 * JDGrid Html class
 *
 * @package     JDeveloper
 * @subpackage  Html
 */
class JHtmlJDGrid
{
	public static function archives($prefix, $name)
	{
		$html = "";

		if (count(JDeveloperArchive::getVersions($prefix, $name)))
		{
			foreach (JDeveloperArchive::getVersions($prefix, $name) as $file)
			{
				$download = JDeveloperArchive::getArchiveDir(true) . "/$file";
				$html .= '<a href="'.$download.'" class="hasTooltip" title="' . JText::_('COM_JDEVELOPER_COMPONENT_DOWNLOAD') . '">'.$file.'</a> - <span style="color:#999999;">'.date("Y.m.d H:i", filemtime(JDeveloperArchive::getArchiveDir() . "/$file")).'</span><br>';
			}
		}
		else
		{
			$html .= '<i class="icon-unpublish"></i>';
		}
		
		return $html;
	}

	public static function assigned($link, $count, $info = "")
	{
		$class = (empty($count)) ? '' : 'badge-info';
		
		return '<a sytle="color:#fff;" href="' . $link . '"><span class="badge ' . $class . ' hasTooltip" data-original-title="'
			. $info . '">' . $count . '</span></a>';
	}

	public static function author($id, $name)
	{
		return "<a href=" . JRoute::_('index.php?option=com_users&task=user.edit&id='.(int) $id) . " title=" . JText::_('JAUTHOR') . ">" . $name . "</a>";
	}

	public static function ordering($saveOrder, $ordering)
	{
		if ($saveOrder)
		{
			$html = "<span class=\"sortable-handler hasTooltip\" title=\"\"><i class=\"icon-menu\"></i></span>";
			$html .= "<input type=\"text\" style=\"display:none\" name=\"order[]\" size=\"5\" value=\"$ordering\" class=\"width-20 text-area-order\" />";
		}
		else
		{
			$html = "<span class=\"sortable-handler hasTooltip inactive\" title=\"" . JText::_('JORDERINGDISABLED') . "\"><i class=\"icon-menu\"></i></span>";
		}
		
		return $html;
	}

	public static function published($value)
	{
		$published = empty($value) ? '<i class="icon-unpublish"></i>' : '<i class="icon-publish"></i>'; 
		return $published;
	}
}