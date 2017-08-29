<?php##{start_header}##
/**
 * @package     JDeveloper
 * @subpackage  Templates.Plugin
 *
 * @copyright  	Copyright (C) 2014, Tilo-Lars Flasche. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;##{end_header}##
##Header##

/**
 * Joomla Editors plugin
 *
 * @package     Joomla.Plugin
 * @subpackage  Editors.##name##
 */
class plgEditors##Plugin## extends JPlugin
{
	/**
	 * Method to handle the onInitEditor event.
	 *
	 * @return	string	JavaScript Initialization string
	 *
	 * @since 1.5
	 */
	public function onInit()
	{
		return "";
	}

	/**
	 * Method to handle the onSave event
	 */
	function onSave()
	{
		return;
	}

	/**
	 * Get the editor content.
	 *
	 * @param	string	$id		The id of the editor field.
	 *
	 * @return	string
	 */
	function onGetContent($id)
	{
		return "";
	}

	/**
	 * Set the editor content.
	 *
	 * @param	string	$id		The id of the editor field.
	 * @param	string	$html	The content to set.
	 *
	 * @return	string
	 */
	function onSetContent($id, $html)
	{
		return "";
	}

	/**
	 * Method to handle the onInsertMethod event
	 *
	 * @param	string	$id
	 *
	 * @return	string
	 */
	function onGetInsertMethod($id)
	{
		static $done = false;

		// Do this only once.
		if (!$done) {
			$doc = JFactory::getDocument();
			$js = "\tfunction jInsertEditorText(text, editor) {
				insertAtCursor(document.getElementById(editor), text);
			}";
			$doc->addScriptDeclaration($js);
		}

		return true;
	}

	/**
	 * Display the editor area.
	 *
	 * @param	string	$name		The control name.
	 * @param	string	$html		The contents of the text area.
	 * @param	string	$width		The width of the text area (px or %).
	 * @param	string	$height		The height of the text area (px or %).
	 * @param	int		$col		The number of columns for the textarea.
	 * @param	int		$row		The number of rows for the textarea.
	 * @param	boolean	$buttons	True and the editor buttons will be displayed.
	 * @param	string	$id			An optional ID for the textarea (note: since 1.6). If not supplied the name is used.
	 * @param	string	$asset
	 * @param	object	$author
	 * @param	array	$params		Associative array of editor parameters.
	 *
	 * @return	string
	 */
	function onDisplay($name, $content, $width, $height, $col, $row, $buttons = true, $id = null, $asset = null, $author = null, $params = array())
	{
		return "";
	}
}