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

/**
 * Item Model for ##singular##.
 *
 * @package     ##Component##
 * @subpackage  Models
 */
class ##Component##Model##Singular## extends JModelAdmin
{
	/**
	 * @var        string    The prefix to use with controller messages.
	 * @since   1.6
	 */
	protected $text_prefix = 'COM_##COMPONENT##';

	/**
	 * The type alias for this content type.
	 *
	 * @var      string
	 * @since    3.2
	 */
	public $typeAlias = 'com_##component##.##singular##';

	/**
	 * Method to test whether a record can be deleted.
	 *
	 * @param   object    $record    A record object.
	 *
	 * @return  boolean  True if allowed to delete the record. Defaults to the permission set in the component.
	 * @since   1.6
	 */
	protected function canDelete($record)
	{
		if (!empty($record->##pk##))
		{##{start_published}##
			if ($record->published != -2)
			{
				return false;
			}
			##{end_published}##

			$user = JFactory::getUser();
			return $user->authorise('core.delete', $this->typeAlias . '.' . (int) $record->##pk##);
		}
	}##{start_asset_id}##

	/**
	 * Method to test whether a record can have its state edited.
	 *
	 * @param   object    $record    A record object.
	 *
	 * @return  boolean  True if allowed to change the state of the record. Defaults to the permission set in the component.
	 * @since   1.6
	 */
	protected function canEditState($record)
	{
		$user = JFactory::getUser();

		// Check for existing item.
		if (!empty($record->##pk##))
		{
			return $user->authorise('core.edit.state', $this->typeAlias . '.' . (int) $record->##pk##);
		}##{start_catid}##
		// New article, so check against the category.
		if (!empty($record->catid))
		{
			return $user->authorise('core.edit.state', $this->option . '.category.' . (int) $record->catid);
		}##{end_catid}##
		// Default to component settings if neither article nor category known.
		else
		{
			return parent::canEditState($this->option);
		}
	}
##{end_asset_id}##		

	/**
	 * Prepare and sanitise the table data prior to saving.
	 *
	 * @param   JTable    A JTable object.
	 *
	 * @return  void
	 * @since   1.6
	 */
	protected function prepareTable($table)
	{
		// Set the publish date to now
		$db = $this->getDbo();##{start_published}####{start_publish_up}##
		
		if ($table->published == 1 && (int) $table->publish_up == 0)
		{
			$table->publish_up = JFactory::getDate()->toSql();
		}##{end_publish_up}####{start_publish_down}##

		if ($table->published == 1 && intval($table->publish_down) == 0)
		{
			$table->publish_down = $db->getNullDate();
		}##{end_publish_down}####{end_published}####{start_version}##

		// Increment the content version number.
		$table->version++;##{end_version}##
	}

	/**
	 * Auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	protected function populateState()
	{
		$app = JFactory::getApplication('administrator');##{start_table_nested}##

		$parentId = $app->input->getInt('parent_id');
		$this->setState('##singular##.parent_id', $parentId);##{end_table_nested}##

		// Load the User state.
		$pk = $app->input->getInt('##pk##');
		$this->setState($this->getName() . '.##pk##', $pk);

		// Load the parameters.
		$params = JComponentHelper::getParams('com_##component##');
		$this->setState('params', $params);
	}##{start_admin}##

	/**
	 * Method to perform batch operations on an item or a set of items.
	 *
	 * @param   array  $commands  An array of commands to perform.
	 * @param   array  $pks       An array of item ids.
	 * @param   array  $contexts  An array of item contexts.
	 *
	 * @return  boolean  Returns true on success, false on failure.
	 *
	 * @since   12.2
	 */
	public function batch($commands, $pks, $contexts)
	{
		// Sanitize ids.
		$pks = array_unique($pks);
		JArrayHelper::toInteger($pks);

		// Remove any values of zero.
		if (array_search(0, $pks, true))
		{
			unset($pks[array_search(0, $pks, true)]);
		}

		if (empty($pks))
		{
			$this->setError(JText::_('JGLOBAL_NO_ITEM_SELECTED'));
			return false;
		}

		$done = false;

		// Set some needed variables.
		$this->user = JFactory::getUser();
		$this->table = $this->getTable();
		$this->tableClassName = get_class($this->table);
		$this->contentType = new JUcmType;
		$this->type = $this->contentType->getTypeByTable($this->tableClassName);
		$this->batchSet = true;

		if ($this->type == false)
		{
			$type = new JUcmType;
			$this->type = $type->getTypeByAlias($this->typeAlias);

		}
		if ($this->type === false)
		{
			$type = new JUcmType;
			$this->type = $type->getTypeByAlias($this->typeAlias);
			$typeAlias = $this->type->type_alias;
		}
		else
		{
			$typeAlias = $this->type->type_alias;
		}
		$this->tagsObserver = $this->table->getObserverOfClass('JTableObserverTags');##{start_catid}##

		if (!empty($commands['category_id']))
		{
			$cmd = JArrayHelper::getValue($commands, 'move_copy', 'c');

			if ($cmd == 'c')
			{
				$result = $this->batchCopy($commands['category_id'], $pks, $contexts);

				if (is_array($result))
				{
					$pks = $result;
				}
				else
				{
					return false;
				}
			}
			elseif ($cmd == 'm' && !$this->batchMove($commands['category_id'], $pks, $contexts))
			{
				return false;
			}

			$done = true;
		}##{end_catid}####{start_access}##

		if (!empty($commands['assetgroup_id']))
		{
			if (!$this->batchAccess($commands['assetgroup_id'], $pks, $contexts))
			{
				return false;
			}

			$done = true;
		}##{end_access}####{start_language}##

		if (!empty($commands['language_id']))
		{
			if (!$this->batchLanguage($commands['language_id'], $pks, $contexts))
			{
				return false;
			}

			$done = true;
		}##{end_language}####{start_tags}##

		if (!empty($commands['tag']))
		{
			if (!$this->batchTag($commands['tag'], $pks, $contexts))
			{
				return false;
			}

			$done = true;
		}##{end_tags}##

		if (!$done)
		{
			$this->setError(JText::_('JLIB_APPLICATION_ERROR_INSUFFICIENT_BATCH_INFORMATION'));
			return false;
		}

		// Clear the cache
		$this->cleanCache();

		return true;
	}##{end_admin}##
	
	/**
	 * Alias for JTable::getInstance()
	 *
	 * @param   string  $type    The type (name) of the JTable class to get an instance of.
	 * @param   string  $prefix  An optional prefix for the table class name.
	 * @param   array   $config  An optional array of configuration values for the JTable object.
	 *
	 * @return  mixed    A JTable object if found or boolean false if one could not be found.
	 */
	public function getTable($type = '##Singular##', $prefix = '##Component##Table', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}
	
	/**
	 * Method for getting the form from the model.
	 *
	 * @param   array    $data      Data for the form.
	 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
	 *
	 * @return  mixed  A JForm object on success, false on failure
	 */
	public function getForm($data = array(), $loadData = true)
	{##{start_site}##
		JForm::addFormPath(JPATH_COMPONENT_ADMINISTRATOR.'/models/forms');
		JForm::addFieldPath(JPATH_COMPONENT_ADMINISTRATOR.'/models/fields');
##{end_site}##
		JForm::addRulePath(JPATH_COMPONENT_ADMINISTRATOR.'/models/rules');		
		
		$options = array('control' => 'jform', 'load_data' => $loadData);
		$form = $this->loadForm($this->typeAlias, $this->name, $options);
		
		if(empty($form))
		{
			return false;
		}

		return $form;
	}
	
	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return  array    The default data is an empty array.
	 */
	protected function loadFormData()
	{
		$app = JFactory::getApplication();
		$data = $app->getUserState($this->option . '.edit.' . $this->name . '.data', array());
		
		if(empty($data))
		{
			$data = $this->getItem();
		}
		
		return $data;
	}
	
##getItem####{start_site}##
	
	/**
	 * Increment the hit counter for the item.
	 *
	 * @param   integer  $pk  Optional primary key of the item to increment.
	 *
	 * @return  boolean  True if successful; false otherwise and internal error set.
	 */
	public function hit($pk = 0)
	{
		$input = JFactory::getApplication()->input;
		$hitcount = $input->getInt('hitcount', 1);

		if ($hitcount)
		{
			$pk = (!empty($pk)) ? $pk : (int) $this->getState('##singular##.##pk##');

			$table = $this->getTable();
			$table->load($pk);
			$table->hit($pk);
		}

		return true;
	}##{end_site}####{start_table_nested}##
		
	/**
	 * Method to save the form data.
	 *
	 * @param   array    $data  The form data.
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   1.6
	 */
	public function save($data)
	{
		$table = $this->getTable();
		$input = JFactory::getApplication()->input;
		$pk = (!empty($data['##pk##'])) ? $data['##pk##'] : (int) $this->getState($this->getName() . '.##pk##');
		$isNew = true;

		// Load the row if saving an existing category.
		if ($pk > 0)
		{
			$table->load($pk);
			$isNew = false;
		}

		// Set the new parent id if parent id not matched OR while New/Save as Copy .
		if ($table->parent_id != $data['parent_id'] || $data['##pk##'] == 0)
		{
			$table->setLocation($data['parent_id'], 'last-child');
		}

		// Alter the ##mainfield## for save as copy
		if ($input->get('task') == 'save2copy')
				{
			list($##mainfield##, $alias) = $this->generateNewTitle($data['parent_id'], $data['alias'], $data['##mainfield##']);
			$data['##mainfied##'] = $##mainfield##;
			$data['alias'] = $alias;##{start_published}##
			$data['published'] = 0;##{end_published}##
		}

		// Bind the data.
		if (!$table->bind($data))
		{
			$this->setError($table->getError());
			return false;
		}##{start_asset_id}##

		// Bind the rules.
		if (isset($data['rules']))
		{
			$rules = new JAccessRules($data['rules']);
			$table->setRules($rules);
		}##{end_asset_id}##

		// Check the data.
		if (!$table->check())
		{
			$this->setError($table->getError());
			return false;
		}

		// Store the data.
		if (!$table->store())
		{
			$this->setError($table->getError());
			return false;
		}

		// Rebuild the path for the ##singular##:
		if (!$table->rebuildPath($table->##pk##))
		{
			$this->setError($table->getError());
			return false;
		}

		// Rebuild the paths of the ##singular##'s children:
		if (!$table->rebuild($table->##pk##, $table->lft, $table->level, $table->path))
		{
			$this->setError($table->getError());
			return false;
		}

		$this->setState($this->getName() . '.##pk##', $table->##pk##);

		// Clear the cache
		$this->cleanCache();

		return true;
	}

	/**
	 * Method to change the title & alias.
	 *
	 * @param   integer  $parent_id  The id of the parent.
	 * @param   string   $alias      The alias.
	 * @param   string   $title      The title.
	 *
	 * @return  array    Contains the modified title and alias.
	 *
	 * @since   1.7
	 */
	protected function generateNewTitle($parent_id, $alias, $title)
	{
		// Alter the title & alias
		$table = $this->getTable();
		while ($table->load(array('alias' => $alias, 'parent_id' => $parent_id)))
		{
			$title = JString::increment($title);
			$alias = JString::increment($alias, 'dash');
		}

		return array($title, $alias);
	}

	/**
	 * Method rebuild the entire nested set tree.
	 *
	 * @return  boolean  False on failure or error, true otherwise.
	 *
	 * @since   1.6
	 */
	public function rebuild()
	{
		// Get an instance of the table object.
		$table = $this->getTable();

		if (!$table->rebuild())
		{
			$this->setError($table->getError());
			return false;
		}

		// Clear the cache
		$this->cleanCache();

		return true;
	}##{end_table_nested}##
}
?>