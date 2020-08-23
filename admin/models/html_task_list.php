<?php
/*----------------------------------------------------------------------------------|  www.vdm.io  |----/
				Gartes 
/-------------------------------------------------------------------------------------------------------/

	@version		1.5.19
	@build			23rd декабря, 2019
	@created		5th мая, 2019
	@package		proCritical
	@subpackage		html_task_list.php
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
 * Html_task_list Model
 */
class Pro_criticalModelHtml_task_list extends JModelList
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
				'a.task_id','task_id',
				'a.short_description','short_description',
				'a.component_view_id','component_view_id',
				'a.id_component','id_component'
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
		$task_id = $this->getUserStateFromRequest($this->context . '.filter.task_id', 'filter_task_id');
		$this->setState('filter.task_id', $task_id);

		$short_description = $this->getUserStateFromRequest($this->context . '.filter.short_description', 'filter_short_description');
		$this->setState('filter.short_description', $short_description);

		$component_view_id = $this->getUserStateFromRequest($this->context . '.filter.component_view_id', 'filter_component_view_id');
		$this->setState('filter.component_view_id', $component_view_id);

		$id_component = $this->getUserStateFromRequest($this->context . '.filter.id_component', 'filter_id_component');
		$this->setState('filter.id_component', $id_component);
        
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

		// [Interpretation 13351] set selection value to a translatable value
		if (Pro_criticalHelper::checkArray($items))
		{
			foreach ($items as $nr => &$item)
			{
				// [Interpretation 13358] convert type_html_task
				$item->type_html_task = $this->selectionTranslation($item->type_html_task, 'type_html_task');
			}
		}

        
		// return items
		return $items;
	}

	/**
	 * Method to convert selection values to translatable string.
	 *
	 * @return translatable string
	 */
	public function selectionTranslation($value,$name)
	{
		// [Interpretation 13384] Array of type_html_task language strings
		if ($name === 'type_html_task')
		{
			$type_html_taskArray = array(
				1 => 'COM_PRO_CRITICAL_HTML_TASK_IMAGES',
				2 => 'COM_PRO_CRITICAL_HTML_TASK_YOUTUBE',
				3 => 'COM_PRO_CRITICAL_HTML_TASK_LINK_PRELOADER',
				0 => 'COM_PRO_CRITICAL_HTML_TASK_CUSTOM'
			);
			// [Interpretation 13415] Now check if value is found in this array
			if (isset($type_html_taskArray[$value]) && Pro_criticalHelper::checkString($type_html_taskArray[$value]))
			{
				return $type_html_taskArray[$value];
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
		$query->from($db->quoteName('#__pro_critical_html_task', 'a'));

		// [Interpretation 9740] From the pro_critical_directory_components table.
		$query->select($db->quoteName('g.copmonent_name','id_component_copmonent_name'));
		$query->join('LEFT', $db->quoteName('#__pro_critical_directory_components', 'g') . ' ON (' . $db->quoteName('a.id_component') . ' = ' . $db->quoteName('g.id') . ')');

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
				$query->where('(a.task_id LIKE '.$search.' OR a.short_description LIKE '.$search.' OR a.selector LIKE '.$search.')');
			}
		}

		// [Interpretation 9776] Filter by Task_id.
		if ($task_id = $this->getState('filter.task_id'))
		{
			$query->where('a.task_id = ' . $db->quote($db->escape($task_id)));
		}
		// [Interpretation 9776] Filter by Short_description.
		if ($short_description = $this->getState('filter.short_description'))
		{
			$query->where('a.short_description = ' . $db->quote($db->escape($short_description)));
		}
		// [Interpretation 9768] Filter by id_component.
		if ($id_component = $this->getState('filter.id_component'))
		{
			$query->where('a.id_component = ' . $db->quote($db->escape($id_component)));
		}
		// [Interpretation 9768] Filter by component_view_id.
		if ($component_view_id = $this->getState('filter.component_view_id'))
		{
			$query->where('a.component_view_id = ' . $db->quote($db->escape($component_view_id)));
		}

		// [Interpretation 9667] Add the list ordering clause.
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
		// [Interpretation 12459] Compile the store id.
		$id .= ':' . $this->getState('filter.id');
		$id .= ':' . $this->getState('filter.search');
		$id .= ':' . $this->getState('filter.published');
		$id .= ':' . $this->getState('filter.ordering');
		$id .= ':' . $this->getState('filter.created_by');
		$id .= ':' . $this->getState('filter.modified_by');
		$id .= ':' . $this->getState('filter.task_id');
		$id .= ':' . $this->getState('filter.short_description');
		$id .= ':' . $this->getState('filter.component_view_id');
		$id .= ':' . $this->getState('filter.id_component');

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
			$query->from($db->quoteName('#__pro_critical_html_task'));
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
				$query->update($db->quoteName('#__pro_critical_html_task'))->set($fields)->where($conditions); 

				$db->setQuery($query);

				$db->execute();
			}
		}

		return false;
	}
}
