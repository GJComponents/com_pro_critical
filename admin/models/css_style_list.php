<?php
/*----------------------------------------------------------------------------------|  www.vdm.io  |----/
				Gartes 
/-------------------------------------------------------------------------------------------------------/

	@version		1.x.x
	@build			23rd августа, 2020
	@created		5th мая, 2019
	@package		proCritical
	@subpackage		css_style_list.php
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
 * Css_style_list Model
 */
class Pro_criticalModelCss_style_list extends JModelList
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
				'a.load','load',
				'a.minify','minify'
			);
		}

		parent::__construct($config);
	}

    /**
     * Method to auto-populate the model state.
     * @param null $ordering
     * @param null $direction
     * @throws Exception
     * @since 3.9
     * @auhtor Gartes | sad.net79@gmail.com | Skype : agroparknew | Telegram : @gartes
     * @date 25.08.2020 17:42
     *
     */
	protected function populateState($ordering = null, $direction = null)
	{
		$app = JFactory::getApplication();

		// Adjust the context to support modal layouts.
		if ($layout = $app->input->get('layout'))
		{
			$this->context .= '.' . $layout;
		}
		$load = $this->getUserStateFromRequest($this->context . '.filter.load', 'filter_load');
		$this->setState('filter.load', $load);

		$minify = $this->getUserStateFromRequest($this->context . '.filter.minify', 'filter_minify');
		$this->setState('filter.minify', $minify);
        
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
     * Метод получения массива элементов данных.
	 * Method to get an array of data items.
	 *
	 * @return  mixed  An array of data items on success, false on failure.
     * @since 3.9
	 */
	public function getItems( $byHash = false )
	{
		// [Interpretation 19606] check in items
		$this->checkInNow();

		// load parent items
		$items = parent::getItems();
        



		
		// [Interpretation 20424] set selection value to a translatable value
		if ( Pro_criticalHelper::checkArray($items) )
		{
            $byHashReturn = [] ;
			foreach ($items as $nr => &$item)
			{
				// [Interpretation 20438] convert load
				$item->load = $this->selectionTranslation($item->load, 'load');
				// [Interpretation 20438] convert minify
				$item->minify = $this->selectionTranslation($item->minify, 'minify');
                $byHashReturn[$item->hash] = $item ;

			}
            // return items
            if( $byHash )
            {
                return $byHashReturn ;
            }#END IF
		}

        
		// return items
       return $items;
	}

	/**
     * Метод преобразования значений выбора в переводимую строку.
	 * Method to convert selection values to translatable string.
	 *
	 * @return string translatable
     * @since 3.9
	 */
	public function selectionTranslation($value,$name)
	{
		// [Interpretation 20478] Array of load language strings
		if ($name === 'load')
		{
			$loadArray = array(
				1 => 'COM_PRO_CRITICAL_CSS_STYLE_YES',
				0 => 'COM_PRO_CRITICAL_CSS_STYLE_NO'
			);
			// [Interpretation 20515] Now check if value is found in this array
			if (isset($loadArray[$value]) && Pro_criticalHelper::checkString($loadArray[$value]))
			{
				return $loadArray[$value];
			}
		}
		// [Interpretation 20478] Array of minify language strings
		if ($name === 'minify')
		{
			$minifyArray = array(
				1 => 'COM_PRO_CRITICAL_CSS_STYLE_YES',
				0 => 'COM_PRO_CRITICAL_CSS_STYLE_NO'
			);
			// [Interpretation 20515] Now check if value is found in this array
			if (isset($minifyArray[$value]) && Pro_criticalHelper::checkString($minifyArray[$value]))
			{
				return $minifyArray[$value];
			}
		}
		return $value;
	}

    /**
     * Method to build an SQL query to load the list data.
     * @return JDatabaseQuery|string An SQL query
     * @since 3.9
     * @auhtor Gartes | sad.net79@gmail.com | Skype : agroparknew | Telegram : @gartes
     * @date 25.08.2020 17:49
     *
     */
	protected function getListQuery()
	{
		// [Interpretation 14604] Get the user object.
		$user = JFactory::getUser();
		// [Interpretation 14606] Create a new query object.
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);

		// [Interpretation 14611] Select some fields
		$query->select('a.*');

		// [Interpretation 14621] From the pro_critical_item table
		$query->from($db->quoteName('#__pro_critical_css_style', 'a'));

		// [Interpretation 14640] Filter by published state
		$published = $this->getState('filter.published');
		if (is_numeric($published))
		{
			$query->where('a.published = ' . (int) $published);
		}
		elseif ($published === '')
		{
			$query->where('(a.published = 0 OR a.published = 1)');
		}
		// [Interpretation 14779] Filter by search.
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
				$query->where('(a.content LIKE '.$search.' OR a.hash LIKE '.$search.')');
			}
		}

		// [Interpretation 14911] Filter by Load.
		if ($load = $this->getState('filter.load'))
		{
			$query->where('a.load = ' . $db->quote($db->escape($load)));
		}
		// [Interpretation 14911] Filter by Minify.
		if ($minify = $this->getState('filter.minify'))
		{
			$query->where('a.minify = ' . $db->quote($db->escape($minify)));
		}

		// [Interpretation 14727] Add the list ordering clause.
		$orderCol = $this->state->get('list.ordering', 'a.id');
		$orderDirn = $this->state->get('list.direction', 'asc');	

		if ($orderCol != '')
		{
			$query->order( $db->escape($db->quoteName( $orderCol ) . ' ' . $orderDirn));
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
		// [Interpretation 18987] Compile the store id.
		$id .= ':' . $this->getState('filter.id');
		$id .= ':' . $this->getState('filter.search');
		$id .= ':' . $this->getState('filter.published');
		$id .= ':' . $this->getState('filter.ordering');
		$id .= ':' . $this->getState('filter.created_by');
		$id .= ':' . $this->getState('filter.modified_by');
		$id .= ':' . $this->getState('filter.load');
		$id .= ':' . $this->getState('filter.minify');

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
		// [Interpretation 19624] Get set check in time
		$time = JComponentHelper::getParams('com_pro_critical')->get('check_in');

		if ($time)
		{

			// [Interpretation 19632] Get a db connection.
			$db = JFactory::getDbo();
			// [Interpretation 19635] reset query
			$query = $db->getQuery(true);
			$query->select('*');
			$query->from($db->quoteName('#__pro_critical_css_style'));
			$db->setQuery($query);
			$db->execute();
			if ($db->getNumRows())
			{
				// [Interpretation 19646] Get Yesterdays date
				$date = JFactory::getDate()->modify($time)->toSql();
				// [Interpretation 19650] reset query
				$query = $db->getQuery(true);

				// [Interpretation 19654] Fields to update.
				$fields = array(
					$db->quoteName('checked_out_time') . '=\'0000-00-00 00:00:00\'',
					$db->quoteName('checked_out') . '=0'
				);

				// [Interpretation 19663] Conditions for which records should be updated.
				$conditions = array(
					$db->quoteName('checked_out') . '!=0', 
					$db->quoteName('checked_out_time') . '<\''.$date.'\''
				);

				// [Interpretation 19672] Check table
				$query->update($db->quoteName('#__pro_critical_css_style'))->set($fields)->where($conditions); 

				$db->setQuery($query);

				$db->execute();
			}
		}

		return false;
	}
}
