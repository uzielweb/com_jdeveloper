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
 * ##Singular## table class.
 *
 * @package     ##Component##
 * @subpackage  Tables
 */
class ##Component##Table##Singular## extends JTable##{start_table_nested}##Nested##{end_table_nested}##
{
	/**
	 * Constructor
	 *
	 * @param   JDatabaseDriver  &$db  A database connector object
	 */
	public function __construct(&$db)
	{
		parent::__construct('#__##table_db##', '##pk##', $db);
	}

	/**
     * Overloaded check function
     */
    public function check()
	{##{start_ordering}##
        //If there is an ordering column and this is a new row then get the next ordering value
        if (property_exists($this, 'ordering') && $this->##pk## == 0) {
            $this->ordering = self::getNextOrder();
        }
##{end_ordering}####{start_alias}##
		// Check alias
		if (empty($this->alias))
		{
			$this->alias = $this->##mainfield##;
		}
		$this->alias = JApplication::stringURLSafe($this->alias);##{start_catid}##
		
		// check for valid category
		if (trim($this->catid) == '')
		{
			$this->setError(JText::_('JGLOBAL_CHOOSE_CATEGORY_LABEL'));

			return false;
		}##{end_catid}####{start_publish_up}##
		
		// Check the publish down date is not earlier than publish up.
		if ((int) $this->publish_down > 0 && $this->publish_down < $this->publish_up)
		{
			$this->setError(JText::_('JGLOBAL_START_PUBLISH_AFTER_FINISH'));
			return false;
		}##{end_publish_up}####{start_metakey}##
		
		// Clean up keywords -- eliminate extra spaces between phrases
		// and cr (\r) and lf (\n) characters from string
		if (!empty($this->metakey))
		{
			// Only process if not empty
			$bad_characters = array("\n", "\r", "\"", "<", ">"); // array of characters to remove
			$after_clean = JString::str_ireplace($bad_characters, "", $this->metakey); // remove bad characters
			$keys = explode(',', $after_clean); // create array using commas as delimiter
			$clean_keys = array();

			foreach($keys as $key)
			{
				if (trim($key)) {  // ignore blank keywords
					$clean_keys[] = trim($key);
				}
			}
			$this->metakey = implode(", ", $clean_keys); // put array back together delimited by ", "
		}##{end_metakey}####{start_metadesc}##

		// Clean up description -- eliminate quotes and <> brackets
		if (!empty($this->metadesc))
		{
			// Only process if not empty
			$bad_characters = array("\"", "<", ">");
			$this->metadesc = JString::str_ireplace($bad_characters, "", $this->metadesc);
		}##{end_metadesc}##

        return parent::check();
    }

	/**
	 * Method to bind an associative array or object to the JTable instance.
	 *
	 * @see JTable
	 */
	public function bind($array, $ignore = '')
	{##{start_params}##
		// If this table has a column named 'params', save all param fields as JSON string in this column
		if ( isset($array['params']) && is_array($array['params']) )
		{
			$registry = new JRegistry();
			$registry->loadArray($array['params']);
			$array['params'] = (string) $registry;
		}##{end_params}####{start_metadata}##
		
		if (isset($array['metadata']) && is_array($array['metadata']))
		{
			$registry = new JRegistry();
			$registry->loadArray($array['metadata']);
			$array['metadata'] = (string) $registry;
		}##{end_metadata}####{start_images}##
		
		if (isset($array['images']) && is_array($array['images']))
		{
			$registry = new JRegistry;
			$registry->loadArray($array['images']);
			$array['images'] = (string) $registry;
		}##{end_images}####{start_asset_id}##

		//Bind the rules for ACL
		if ( isset($array['rules']) && is_array($array['rules']) )
		{
			$rules = new JAccessRules($array['rules']);
			$this->setRules($rules);
		}##{end_asset_id}##

		return parent::bind($array, $ignore);
	}
	
	/**
	 * Overriden JTable::store to set modified data.
	 *
	 * @param   boolean	True to update fields even if they are null.
	 * @return  boolean  True on success.
	 * @since   1.6
	 */
	public function store($updateNulls = false)
	{##{start_params}##
		// Transform the params field
		if (is_array($this->params))
		{
			$registry = new JRegistry;
			$registry->loadArray($this->params);
			$this->params = (string) $registry;
		}
		##{end_params}##
		$date	= JFactory::getDate();
		$user	= JFactory::getUser();
		
		if ($this->##pk##)
		{
			// Existing item
			##{start_modified}##$this->modified		= $date->toSql();##{end_modified}##
			##{start_modified_by}##$this->modified_by	= $user->get('id');##{end_modified_by}##
		}
		else
		{
			// New item. An item created and created_by field can be set by the user,
			// so we don't touch either of these if they are set.##{start_created}##			
			if (!(int) $this->created)
			{
				$this->created = $date->toSql();
			}##{end_created}####{start_created_by}##
			
			if (empty($this->created_by))
			{
				$this->created_by = $user->get('id');
			}##{end_created_by}##
		}##{start_publish_up}##
		
		// Set publish_up to null date if not set
		if (!$this->publish_up)
		{
			$this->publish_up = $this->_db->getNullDate();
		}##{end_publish_up}####{start_publish_down}##

		// Set publish_down to null date if not set
		if (!$this->publish_down)
		{
			$this->publish_down = $this->_db->getNullDate();
		}##{end_publish_down}####{start_alias}##
		
		// Verify that the alias is unique
		$table = JTable::getInstance('##Singular##', '##Component##Table');
		if ($table->load(array('alias' => $this->alias##{start_catid}##, 'catid' => $this->catid##{end_catid}####{start_table_nested}##, 'parent_id' => $this->parent_id##{end_table_nested}##)) && ($table->##pk## != $this->##pk## || $this->##pk## == 0))
		{
			$this->setError(JText::_('UNIQUE_ALIAS'));
			return false;
		}##{end_alias}##
		
		return parent::store($updateNulls);
	}##{start_asset_id}##

	/**
     * Define a namespaced asset name for inclusion in the #__assets table
	 *
     * @return	string	The asset name 
     *
     * @see JTable::_getAssetName 
     */
    protected function _getAssetName()
	{
        $k = $this->_tbl_key;
        return 'com_##component##.##singular##.' . (int) $this->$k;
    }
	
	/**
	 * Define a title for the asset
	 *
	 * @return	string	The asset title
	 */
	protected function _getAssetTitle()
	{
		return $this->##mainfield##;
	}
	
	/**
	 * Returns the parent asset's id. If you have a tree structure, retrieve the parent's id using the external key field
     *
     * @see JTable::_getAssetParentId
	 */
	protected function _getAssetParentId(JTable $table = null, $id = null)
	{
		$asset = JTable::getInstance('asset');##{!start_catid}##
		$asset->loadByName('com_##component##');##{!end_catid}####{start_catid}##
		$asset->loadByName('com_##component##.category.' . $this->catid);##{end_catid}##
		return $asset->id;
	}##{end_asset_id}##
}
?>