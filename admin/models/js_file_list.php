<?php
/*----------------------------------------------------------------------------------|  www.vdm.io  |----/
				Gartes 
/-------------------------------------------------------------------------------------------------------/

	@version		1.5.19
	@build			23rd декабря, 2019
	@created		5th мая, 2019
	@package		proCritical
	@subpackage		js_file_list.php
	@author			Nikolaychuk Oleg <https://nobd.ml>	
	@copyright		Copyright (C) 2019. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
  ____  _____  _____  __  __  __      __       ___  _____  __  __  ____  _____  _  _  ____  _  _  ____ 
 (_  _)(  _  )(  _  )(  \/  )(  )    /__\     / __)(  _  )(  \/  )(  _ \(  _  )( \( )( ___)( \( )(_  _)
.-_)(   )(_)(  )(_)(  )    (  )(__  /(__)\   ( (__  )(_)(  )    (  )___/ )(_)(  )  (  )__)  )  (   )(  
\____) (_____)(_____)(_/\/\_)(____)(__)(__)   \___)(_____)(_/\/\_)(__)  (_____)(_)\_)(____)(_)\_) (__) 

/------------------------------------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Js_file_list Model
 */
class Pro_criticalModelJs_file_list extends JModelList
{
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
        {
			$config['filter_fields'] = array(
				'a.id','id',
				'a.published','published',
				'a.ordering','ordering',
				'a.created_by','created_by',
				'a.modified_by','modified_by',
				'a.file','file',
				'a.load','load',
				'a.override','override',
				'a.minify','minify',
				'a.no_external','no_external'
			);
		}
        parent::__construct($config);
	}

#Custom Buttons PHP List view (model methods) [js_file]

    /**
     * Соритировка cтолбцов таблицы в Joomla Backend
     *
     * Метод автоматического заполнения состояния модели.
     * Method to auto-populate the model state.
     *
     * Этот метод должен вызываться только один раз для каждого экземпляра и предназначен для вызова
     * при первом вызове метода getState (), если не установлен флаг конфигурации модели для игнорирования запроса.
     * Заметка. Вызов getState в этом методе приведет к рекурсии.
     *
     * @see url : https://docs.joomla.org/Adding_sortable_columns_to_a_table_in_a_component
     * @return  void
     * @throws Exception
     * @since 3.9
     * @param null $ordering    Необязательное поле для заказа.
     * @param null $direction   Необязательное направление (asc | desc).
     *
     *
     * @throws Exception
     * @since version
     */
	protected function populateState($ordering = null, $direction = null)
	{
		$app = JFactory::getApplication();

		// Adjust the context to support modal layouts.
		if ($layout = $app->input->get('layout'))
		{
			$this->context .= '.' . $layout;
		}
		$file = $this->getUserStateFromRequest($this->context . '.filter.file', 'filter_file');
		$this->setState('filter.file', $file);

		$load = $this->getUserStateFromRequest($this->context . '.filter.load', 'filter_load');
		$this->setState('filter.load', $load);

		$override = $this->getUserStateFromRequest($this->context . '.filter.override', 'filter_override');
		$this->setState('filter.override', $override);

		$minify = $this->getUserStateFromRequest($this->context . '.filter.minify', 'filter_minify');
		$this->setState('filter.minify', $minify);

		$no_external = $this->getUserStateFromRequest($this->context . '.filter.no_external', 'filter_no_external');
		$this->setState('filter.no_external', $no_external);
        
		$sorting = $this->getUserStateFromRequest($this->context . '.filter.sorting', 'filter_sorting', 0, 'int');
		$this->setState('filter.sorting', $sorting);
        
		$access = $this->getUserStateFromRequest($this->context . '.filter.access', 'filter_access', 0, 'int');
		$this->setState('filter.access', $access);
        
		$search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$published = $this->getUserStateFromRequest($this->context . '.filter.published', 'filter_published', '');
		$this->setState('filter.published', $published);
        
		$created_by = $this->getUserStateFromRequest($this->context . '.filter.created_by', 'filter_created_by', '');
		$this->setState('filter.created_by', $created_by);

		$created = $this->getUserStateFromRequest($this->context . '.filter.created', 'filter_created');
		$this->setState('filter.created', $created);

		// List state information.
		parent::populateState($ordering, $direction);
	}
	
	/**
	 * Method to get an array of data items.
	 *
	 * @return  mixed  An array of data items on success, false on failure.
	 */
	public function getItems()
	{
		// [Interpretation 12857] check in items
		$this->checkInNow();

		// load parent items
		$items = parent::getItems();

#Add PHP (getItems Method - before translation fix & decryption) *

		// [Interpretation 13351] set selection value to a translatable value
		if (Pro_criticalHelper::checkArray($items))
		{
			foreach ($items as $nr => &$item)
			{
				// [Interpretation 13358] convert load
				$item->load = $this->selectionTranslation($item->load, 'load');
				// [Interpretation 13358] convert override
				$item->override = $this->selectionTranslation($item->override, 'override');
				// [Interpretation 13358] convert minify
				$item->minify = $this->selectionTranslation($item->minify, 'minify');
				// [Interpretation 13358] convert no_external
				$item->no_external = $this->selectionTranslation($item->no_external, 'no_external');
			}
		}

#Add PHP (getItems Method - after all) *
        
		// return items
		return $items;
	}

    /**
     * Method to convert selection values to translatable string.
     * @param $value
     * @param $name
     * @return mixed|string
     * @since 3.9
     * @auhtor Gartes | sad.net79@gmail.com | Skype : agroparknew | Telegram : @gartes
     * @date 26.08.2020 07:55
     *
     */
	public function selectionTranslation($value,$name)
	{
		// [Interpretation 13384] Array of load language strings
		if ($name === 'load')
		{
			$loadArray = array(
				1 => 'JYES',
				0 => 'JNO'
			);
			// [Interpretation 13415] Now check if value is found in this array
			if (isset($loadArray[$value]) && Pro_criticalHelper::checkString($loadArray[$value]))
			{
				return $loadArray[$value];
			}
		}
		// [Interpretation 13384] Array of override language strings
		if ($name === 'override')
		{
			$overrideArray = array(
				1 => 'JYES',
				0 => 'JNO'
			);
			// [Interpretation 13415] Now check if value is found in this array
			if (isset($overrideArray[$value]) && Pro_criticalHelper::checkString($overrideArray[$value]))
			{
				return $overrideArray[$value];
			}
		}
		// [Interpretation 13384] Array of minify language strings
		if ($name === 'minify')
		{
			$minifyArray = array(
				1 => 'JYES',
				0 => 'JNO'
			);
			// [Interpretation 13415] Now check if value is found in this array
			if (isset($minifyArray[$value]) && Pro_criticalHelper::checkString($minifyArray[$value]))
			{
				return $minifyArray[$value];
			}
		}
		// [Interpretation 13384] Array of no_external language strings
		if ($name === 'no_external')
		{
			$no_externalArray = array(
				1 => 'JYES',
				0 => 'JNO'
			);
			// [Interpretation 13415] Now check if value is found in this array
			if (isset($no_externalArray[$value]) && Pro_criticalHelper::checkString($no_externalArray[$value]))
			{
				return $no_externalArray[$value];
			}
		}
		return $value;
	}
	
	/**
	 * Method to build an SQL query to load the list data.
	 *
	 * @return	string	An SQL query
	 */
	protected function getListQuery()
	{
		// [Interpretation 9588] Get the user object.
		$user = JFactory::getUser();
		// [Interpretation 9590] Create a new query object.
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);

		// [Interpretation 9593] Select some fields
		$query->select('a.*');

		// [Interpretation 9600] From the pro_critical_item table
		$query->from($db->quoteName('#__pro_critical_js_file', 'a'));

#Add PHP (getListQuery - JModelList) *

		// [Interpretation 9611] Filter by published state
		$published = $this->getState('filter.published');
		if (is_numeric($published))
		{
			$query->where('a.published = ' . (int) $published);
		}
		elseif ($published === '')
		{
			$query->where('(a.published = 0 OR a.published = 1)');
		}
		// [Interpretation 9708] Filter by search.
		$search = $this->getState('filter.search');
		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0)
			{
				$query->where('a.id = ' . (int) substr($search, 3));
			}
			else
			{
				$search = $db->quote('%' . $db->escape($search) . '%');
				$query->where('(a.file LIKE '.$search.' OR a.minify_file LIKE '.$search.' OR a.override_file LIKE '.$search.')');
			}
		}

		// [Interpretation 9776] Filter by Load.
		if ($load = $this->getState('filter.load'))
		{
			$query->where('a.load = ' . $db->quote($db->escape($load)));
		}
		// [Interpretation 9776] Filter by Override.
		if ($override = $this->getState('filter.override'))
		{
			$query->where('a.override = ' . $db->quote($db->escape($override)));
		}
		// [Interpretation 9776] Filter by Minify.
		if ($minify = $this->getState('filter.minify'))
		{
			$query->where('a.minify = ' . $db->quote($db->escape($minify)));
		}
		// [Interpretation 9776] Filter by No_external.
		if ($no_external = $this->getState('filter.no_external'))
		{
			$query->where('a.no_external = ' . $db->quote($db->escape($no_external)));
		}

		// [Interpretation 9667] Add the list ordering clause.
		$orderCol = $this->state->get('list.ordering', 'a.id');
		$orderDirn = $this->state->get('list.direction', 'asc');	
		if ($orderCol != '')
		{
			$query->order($db->escape($db->quoteName( $orderCol ) . ' ' . $orderDirn));
		}

		return $query;
	}
	
	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * @return  string  A store id.
	 *
	 */
	protected function getStoreId($id = '')
	{
		// [Interpretation 12459] Compile the store id.
		$id .= ':' . $this->getState('filter.id');
		$id .= ':' . $this->getState('filter.search');
		$id .= ':' . $this->getState('filter.published');
		$id .= ':' . $this->getState('filter.ordering');
		$id .= ':' . $this->getState('filter.created_by');
		$id .= ':' . $this->getState('filter.modified_by');
		$id .= ':' . $this->getState('filter.file');
		$id .= ':' . $this->getState('filter.load');
		$id .= ':' . $this->getState('filter.override');
		$id .= ':' . $this->getState('filter.minify');
		$id .= ':' . $this->getState('filter.no_external');

		return parent::getStoreId($id);
	}

	/**
	 * Build an SQL query to checkin all items left checked out longer then a set time.
	 *
	 * @return  a bool
	 *
	 */
	protected function checkInNow()
	{
		// [Interpretation 12873] Get set check in time
		$time = JComponentHelper::getParams('com_pro_critical')->get('check_in');

		if ($time)
		{

			// [Interpretation 12877] Get a db connection.
			$db = JFactory::getDbo();
			// [Interpretation 12879] reset query
			$query = $db->getQuery(true);
			$query->select('*');
			$query->from($db->quoteName('#__pro_critical_js_file'));
			$db->setQuery($query);
			$db->execute();
			if ($db->getNumRows())
			{
				// [Interpretation 12887] Get Yesterdays date
				$date = JFactory::getDate()->modify($time)->toSql();
				// [Interpretation 12889] reset query
				$query = $db->getQuery(true);

				// [Interpretation 12891] Fields to update.
				$fields = array(
					$db->quoteName('checked_out_time') . '=\'0000-00-00 00:00:00\'',
					$db->quoteName('checked_out') . '=0'
				);

				// [Interpretation 12896] Conditions for which records should be updated.
				$conditions = array(
					$db->quoteName('checked_out') . '!=0', 
					$db->quoteName('checked_out_time') . '<\''.$date.'\''
				);

				// [Interpretation 12901] Check table
				$query->update($db->quoteName('#__pro_critical_js_file'))->set($fields)->where($conditions); 

				$db->setQuery($query);

				$db->execute();
			}
		}

		return false;
	}
}
