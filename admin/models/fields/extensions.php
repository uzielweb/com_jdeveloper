<?php
/**
 * @package     JDeveloper
 * @subpackage  Fields
 *
 * @copyright  	Copyright (C) 2014, Tilo-Lars Flasche. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('JPATH_BASE') or die();
JFormHelper::loadFieldClass('groupedlist');
jimport("joomla.filesystem.folder");

/**
 * List of installed extensions
 *
 * @package		JDeveloper
 * @subpackage	Fields
 */
class JFormFieldExtensions extends JFormFieldGroupedList
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  11.1
	 */
	protected $type = 'extensions';

	/**
	 * Method to get the field options.
	 *
	 * @return  array  The field option objects.
	 *
	 * @since   11.1
	 */
	protected function getGroups()
	{
		// Two dimension array (1st dimension is group, 2nd dimension is option)
		$groups = array();
		$groups["default"] = array();

		// get components
		if ($this->getAttribute("components", "true") == "true") {
			$groups[JText::_("COM_JDEVELOPER_COMPONENTS")] = array();
			$components = array();
			
			foreach (JFolder::folders(JPATH_ROOT . "/administrator/components", "com_[a-z0-9_]*") as $component)
			{
				$groups[JText::_("COM_JDEVELOPER_COMPONENTS")][] = JHtml::_('select.option', "admin." . strtolower($component), ucfirst(str_replace("com_", "", $component)));
				$components[$component] = 1;
			}
			
			foreach (JFolder::folders(JPATH_ROOT . "/components", "com_[a-z0-9_]*") as $component)
			{
				if (!array_key_exists($component, $components)) {
					$groups[JText::_("COM_JDEVELOPER_COMPONENTS")][] = JHtml::_('select.option', "admin." . strtolower($component), ucfirst(str_replace("com_", "", $component)));
				}
			}
		}

		// get modules
		if ($this->getAttribute("modules", "true") == "true") {
			$groups[JText::_("COM_JDEVELOPER_MODULES")] = array();
	
			foreach (JFolder::folders(JPATH_ROOT . "/administrator/modules", "mod_[a-z0-9_]*") as $module)
			{
				$groups[JText::_("COM_JDEVELOPER_MODULES")][] = JHtml::_('select.option', "admin." . strtolower($module), ucfirst(str_replace("mod_", "", $module)) . $this->styleClient("Backend"));
			}
	
			foreach (JFolder::folders(JPATH_ROOT . "/modules", "mod_[a-z0-9_]*") as $module)
			{
				$groups[JText::_("COM_JDEVELOPER_MODULES")][] = JHtml::_('select.option', "site." . strtolower($module), ucfirst(str_replace("mod_", "", $module)) . $this->styleClient("Frontend"));
			}
		}

		// get plugins
		if ($this->getAttribute("plugins", "true") == "true") {
			foreach (JFolder::folders(JPATH_ROOT . "/plugins") as $folder) {
				$groups[JText::_("COM_JDEVELOPER_PLUGINS") . " - " . $folder] = array();
					
				foreach (JFolder::folders(JPATH_ROOT . "/plugins/" . $folder) as $plugin) {
					$groups[JText::_("COM_JDEVELOPER_PLUGINS") . " - " . $folder][] = JHtml::_('select.option', "site.plg_" . strtolower($folder) . "_" . strtolower($plugin), $plugin);
				}
			}
		}

		// get plugins
		if ($this->getAttribute("templates", "true") == "true") {
			$groups[JText::_("COM_JDEVELOPER_TEMPLATES")] = array();
				
			foreach (JFolder::folders(JPATH_ROOT . "/administrator/templates") as $template)
			{
				$groups[JText::_("COM_JDEVELOPER_TEMPLATES")][] = JHtml::_('select.option', "admin.tpl_" . strtolower($template), ucfirst($template) . $this->styleClient("Backend"));
			}
				
			foreach (JFolder::folders(JPATH_ROOT . "/templates") as $template)
			{
				$groups[JText::_("COM_JDEVELOPER_TEMPLATES")][] = JHtml::_('select.option', "site.tpl_" . strtolower($template), ucfirst($template) . $this->styleClient("Frontend"));
			}
		}
		
		return array_merge(parent::getGroups(), $groups);
	}
	
	private function styleClient($text) {
		$text = " <span style=\"color:#999999;\">(" . $text . ")</span>";
		return $text;
	}
}
?>