<?php
/*----------------------------------------------------------------------------------|  www.vdm.io  |----/
				Gartes 
/-------------------------------------------------------------------------------------------------------/

	@version		1.x.x
	@build			23rd августа, 2020
	@created		5th мая, 2019
	@package		proCritical
	@subpackage		css_list.php
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
 * Css_list Model
 */
class Pro_criticalModelCss_list extends JModelList
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
				'a.pro_critical_url_id','pro_critical_url_id',
				'a.option','option',
				'a.view','view',
				'a.type_device_id','type_device_id'
			);
		}

		parent::__construct($config);
	}
	
	/**
	 * Method to auto-populate the model state.
	 *
	 * @return  void
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		$app = JFactory::getApplication();

		// Adjust the context to support modal layouts.
		if ($layout = $app->input->get('layout'))
		{
			$this->context .= '.' . $layout;
		}
		$pro_critical_url_id = $this->getUserStateFromRequest($this->context . '.filter.pro_critical_url_id', 'filter_pro_critical_url_id');
		$this->setState('filter.pro_critical_url_id', $pro_critical_url_id);

		$option = $this->getUserStateFromRequest($this->context . '.filter.option', 'filter_option');
		$this->setState('filter.option', $option);

		$view = $this->getUserStateFromRequest($this->context . '.filter.view', 'filter_view');
		$this->setState('filter.view', $view);

		$type_device_id = $this->getUserStateFromRequest($this->context . '.filter.type_device_id', 'filter_type_device_id');
		$this->setState('filter.type_device_id', $type_device_id);
        
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
		// [Interpretation 19606] check in items
		$this->checkInNow();

		// load parent items
		$items = parent::getItems();
        
		// return items
		return $items;
	}
	
	/**
	 * Method to build an SQL query to load the list data.
	 *
	 * @return	string	An SQL query
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
		$query->from($db->quoteName('#__pro_critical_css', 'a'));

		// [Interpretation 14839] From the yeightflq_pro_critical_url table.
		$query->select($db->quoteName('g.url_page','pro_critical_url_id_url_page'));
		$query->join('LEFT', $db->quoteName('#__pro_critical_url', 'g') . ' ON (' . $db->quoteName('a.pro_critical_url_id') . ' = ' . $db->quoteName('g.id') . ')');

		// [Interpretation 14839] From the yeightflq_pro_critical_directory_components table.
		$query->select($db->quoteName('h.copmonent_name','option_copmonent_name'));
		$query->join('LEFT', $db->quoteName('#__pro_critical_directory_components', 'h') . ' ON (' . $db->quoteName('a.option') . ' = ' . $db->quoteName('h.id') . ')');

		// [Interpretation 14839] From the yeightflq_pro_critical_directory_views table.
		$query->select($db->quoteName('i.view_component','view_view_component'));
		$query->join('LEFT', $db->quoteName('#__pro_critical_directory_views', 'i') . ' ON (' . $db->quoteName('a.view') . ' = ' . $db->quoteName('i.id') . ')');

		// [Interpretation 14839] From the yeightflq_pro_critical_type_device table.
		$query->select($db->quoteName('j.type_device','type_device_id_type_device'));
		$query->join('LEFT', $db->quoteName('#__pro_critical_type_device', 'j') . ' ON (' . $db->quoteName('a.type_device_id') . ' = ' . $db->quoteName('j.id') . ')');

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
				$query->where('(a.pro_critical_url_id LIKE '.$search.' OR g.url_page LIKE '.$search.' OR a.type_device_id LIKE '.$search.' OR j.type_device LIKE '.$search.')');
			}
		}

		// [Interpretation 14896] Filter by pro_critical_url_id.
		if ($pro_critical_url_id = $this->getState('filter.pro_critical_url_id'))
		{
			$query->where('a.pro_critical_url_id = ' . $db->quote($db->escape($pro_critical_url_id)));
		}
		// [Interpretation 14896] Filter by option.
		if ($option = $this->getState('filter.option'))
		{
			$query->where('a.option = ' . $db->quote($db->escape($option)));
		}
		// [Interpretation 14896] Filter by view.
		if ($view = $this->getState('filter.view'))
		{
			$query->where('a.view = ' . $db->quote($db->escape($view)));
		}
		// [Interpretation 14896] Filter by type_device_id.
		if ($type_device_id = $this->getState('filter.type_device_id'))
		{
			$query->where('a.type_device_id = ' . $db->quote($db->escape($type_device_id)));
		}

		// [Interpretation 14727] Add the list ordering clause.
		$orderCol = $this->state->get('list.ordering', 'a.id');
		$orderDirn = $this->state->get('list.direction', 'asc');	
		if ($orderCol != '')
		{
			$query->order($db->escape($orderCol . ' ' . $orderDirn));
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
		$id .= ':' . $this->getState('filter.pro_critical_url_id');
		$id .= ':' . $this->getState('filter.option');
		$id .= ':' . $this->getState('filter.view');
		$id .= ':' . $this->getState('filter.type_device_id');

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
			$query->from($db->quoteName('#__pro_critical_css'));
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
				$query->update($db->quoteName('#__pro_critical_css'))->set($fields)->where($conditions); 

				$db->setQuery($query);

				$db->execute();
			}
		}

		return false;
	}
}
