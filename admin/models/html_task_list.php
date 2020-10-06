<?php
/*----------------------------------------------------------------------------------|  www.vdm.io  |----/
				Gartes 
/-------------------------------------------------------------------------------------------------------/

	@version		1.x.x
	@build			23rd августа, 2020
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
				'a.html_processing','html_processing',
				'a.type_device_id','type_device_id'
			);
		}

		parent::__construct($config);
	}

    /**
     * Метод автоматического заполнения состояния модели.
     * Method to auto-populate the model state.
     *
     * @return  void
     * @throws Exception
     * @since 3.9
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

		$html_processing = $this->getUserStateFromRequest($this->context . '.filter.html_processing', 'filter_html_processing');
		$this->setState('filter.html_processing', $html_processing);

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
     * Метод получения массива элементов данных.
	 * Method to get an array of data items.
	 *
	 * @return  mixed  An array of data items on success, false on failure.
     * @since 3.9
	 */
	public function getItems()
	{
		// [Interpretation 19606] check in items
		$this->checkInNow();

		// load parent items
		$items = parent::getItems();

		// [Interpretation 20424] set selection value to a translatable value
		if (Pro_criticalHelper::checkArray($items))
		{
			foreach ($items as $nr => &$item)
			{
				// [Interpretation 20438] convert html_processing
				$item->html_processing = $this->selectionTranslation($item->html_processing, 'html_processing');
			}
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
		// [Interpretation 20478] Array of html_processing language strings
		if ($name === 'html_processing')
		{
			$html_processingArray = array(
				'element_temlating' => 'COM_PRO_CRITICAL_HTML_TASK_ELEMENT_TO_TEMLATES',
				'fire_action' => 'COM_PRO_CRITICAL_HTML_TASK_FIRE_ELEMENTS',
				'remove_attr' => 'COM_PRO_CRITICAL_HTML_TASK_REMOVE_ATTRIBUTE',
				'tabs_effect' => 'COM_PRO_CRITICAL_HTML_TASK_TABS_EFFECT'
			);
			// [Interpretation 20515] Now check if value is found in this array
			if (isset($html_processingArray[$value]) && Pro_criticalHelper::checkString($html_processingArray[$value]))
			{
				return $html_processingArray[$value];
			}
		}
		return $value;
	}
	
	/**
     * Метод построения SQL-запроса для загрузки данных списка.
	 * Method to build an SQL query to load the list data.
	 *
	 * @return	string	An SQL query
     * @since 3.9
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
		$query->from($db->quoteName('#__pro_critical_html_task', 'a'));

		// [Interpretation 14839] From the yeightflq_pro_critical_directory_views table.
		$query->select($db->quoteName('g.view_component','component_view_id_view_component'));
		$query->join('LEFT', $db->quoteName('#__pro_critical_directory_views', 'g') . ' ON (' . $db->quoteName('a.component_view_id') . ' = ' . $db->quoteName('g.id') . ')');

		// [Interpretation 14839] From the yeightflq_pro_critical_type_device table.
		$query->select($db->quoteName('h.type_device','type_device_id_type_device'));
		$query->join('LEFT', $db->quoteName('#__pro_critical_type_device', 'h') . ' ON (' . $db->quoteName('a.type_device_id') . ' = ' . $db->quoteName('h.id') . ')');

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
				$query->where('(a.task_id LIKE '.$search.' OR a.short_description LIKE '.$search.' OR a.selector LIKE '.$search.')');
			}
		}

		// [Interpretation 14911] Filter by Task_id.
		if ($task_id = $this->getState('filter.task_id'))
		{
			$query->where('a.task_id = ' . $db->quote($db->escape($task_id)));
		}
		// [Interpretation 14911] Filter by Short_description.
		if ($short_description = $this->getState('filter.short_description'))
		{
			$query->where('a.short_description = ' . $db->quote($db->escape($short_description)));
		}
		// [Interpretation 14896] Filter by component_view_id.
		if ($component_view_id = $this->getState('filter.component_view_id'))
		{
			$query->where('a.component_view_id = ' . $db->quote($db->escape($component_view_id)));
		}
		// [Interpretation 14911] Filter by Html_processing.
		if ($html_processing = $this->getState('filter.html_processing'))
		{
			$query->where('a.html_processing = ' . $db->quote($db->escape($html_processing)));
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
     * Метод получения идентификатора магазина на основе состояния конфигурации модели.
	 * Method to get a store id based on model configuration state.
	 *
	 * @return  string  A store id.
	 * @since 3.9
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
		$id .= ':' . $this->getState('filter.task_id');
		$id .= ':' . $this->getState('filter.short_description');
		$id .= ':' . $this->getState('filter.component_view_id');
		$id .= ':' . $this->getState('filter.html_processing');
		$id .= ':' . $this->getState('filter.type_device_id');

		return parent::getStoreId($id);
	}

	/**
     * Создайте SQL-запрос, чтобы проверять все элементы, оставленные извлеченными дольше установленного времени.
	 * Build an SQL query to checkin all items left checked out longer then a set time.
	 *
	 * @return bool
	 * @since 3.9
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
			$query->from($db->quoteName('#__pro_critical_html_task'));
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
				$query->update($db->quoteName('#__pro_critical_html_task'))->set($fields)->where($conditions); 

				$db->setQuery($query);

				$db->execute();
			}
		}

		return false;
	}
}
