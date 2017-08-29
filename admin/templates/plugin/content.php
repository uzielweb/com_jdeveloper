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
 * Joomla Content plugin
 *
 * @package     Joomla.Plugin
 * @subpackage  Content.##name##
 */
class plgContent##Name## extends JPlugin
{
	/**
	 * Method is called by the view and the results are imploded and displayed in a placeholder
	 *
	 * @param	string	$context	The context for the content passed to the plugin.
	 * @param	object	$article	The content object.  Note $article->text is also available
	 * @param	object	$params		The content params
	 * @param	int		$limitstart	The 'page' number
	 *
	 * @return	string
	 *
	 * @since	1.6
	 */
	public function onContentAfterDisplay($context, &$article, &$params, $limitstart)
	{
		$app = JFactory::getApplication();

		return '';
	}

	/**
	 * Method is called right after the content is saved
	 *
	 * @param   string   $context  The context of the content passed to the plugin (added in 1.6)
	 * @param   object   $article  A JTableContent object
	 * @param   boolean  $isNew    If the content is just about to be created
	 *
	 * @return  boolean   true if function not enabled, is in front-end or is new. Else true or
	 *                    false depending on success of save function.
	 *
	 * @since   1.6
	 */
	public function onContentAfterSave($context, &$article, $isNew)
	{
		$app = JFactory::getApplication();

		return true;
	}

	/**
	 * Method is called by the view and the results are imploded and displayed in a placeholder
	 *
	 * @param	string	$context	The context for the content passed to the plugin.
	 * @param	object	$article	The content object.  Note $article->text is also available
	 * @param	object	$params		The content params
	 * @param	int		$limitstart	The 'page' number
	 *
	 * @return	string
	 *
	 * @since	1.6
	 */
	public function onContentAfterTitle($context, &$article, &$params, $limitstart)
	{
		$app = JFactory::getApplication();

		return '';
	}

	/**
	 * Method is called before the content is deleted
	 *
	 * @param   string  $context  The context for the content passed to the plugin.
	 * @param   object  $data     The data relating to the content that was deleted.
	 *
	 * @return  boolean
	 *
	 * @since   1.6
	 */
	public function onContentBeforeDelete($context, $data)
	{
		return true;
	}

	/**
	 * Method is called by the view and the results are imploded and displayed in a placeholder
	 *
	 * @param	string	$context	The context for the content passed to the plugin.
	 * @param	object	$article	The content object.  Note $article->text is also available
	 * @param	object	$params		The content params
	 * @param	int		$limitstart	The 'page' number
	 *
	 * @return	string
	 *
	 * @since	1.6
	 */
	public function onContentBeforeDisplay($context, &$article, &$params, $limitstart)
	{
		$app = JFactory::getApplication();

		return '';
	}

	/**
	 * Method is called right before content is saved into the database.
	 *
	 * @param	string	$context	The context of the content passed to the plugin.
	 * @param	object	$article	A JTableContent object
	 * @param	bool	$isNew		If the content is just about to be created
	 *
	 * @return	bool	If false, abort the save
	 *
	 * @since	1.6
	 */
	public function onContentBeforeSave($context, &$article, $isNew)
	{
		$app = JFactory::getApplication();

		return true;
	}

	/**
	 * Change the state in core_content if the state in a table is changed
	 *
	 * @param   string   $context  The context for the content passed to the plugin.
	 * @param   array    $pks      A list of primary key ids of the content that has changed state.
	 * @param   integer  $value    The value of the state that the content has been changed to.
	 *
	 * @return  boolean
	 *
	 * @since   3.1
	 */
	public function onContentChangeState($context, $pks, $value)
	{
		return true;
	}

	/**
	 * Prepare Content
	 *
	 * @param   string   $context   The context of the content being passed to the plugin.
	 * @param   object   &$article  The article object.  Note $article->text is also available
	 * @param   mixed    &$params   The article params
	 * @param   integer  $page      The 'page' number
	 *
	 * @return  mixed   true if there is an error. Void otherwise.
	 *
	 * @since   1.6
	 */
	public function onContentPrepare($context, &$article, &$params, $page = 0)
	{
		$app = JFactory::getApplication();
	}    
}