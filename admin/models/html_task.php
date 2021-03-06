<?php
/*----------------------------------------------------------------------------------|  www.vdm.io  |----/
				Gartes 
/-------------------------------------------------------------------------------------------------------/

	@version		1.x.x
	@build			23rd августа, 2020
	@created		5th мая, 2019
	@package		proCritical
	@subpackage		html_task.php
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

use Joomla\Registry\Registry;

/**
 * Pro_critical Html_task Model
 */
class Pro_criticalModelHtml_task extends JModelAdmin
{
	/**
     * Массив полей макета вкладки.
	 * The tab layout fields array.
	 * @var      array
     * @since 3.9
	 */
	protected $tabLayoutFields = array(
		'manager_html_task_seting' => array(
			'left' => array(
				'selector_element_for_event',
				'selector',
				'id_component',
				'component_view_id',
				'type_device_id',
				'html_processing',
				'task_data',
				'event_show'
			),
			'above' => array(
				'task_id',
				'short_description'
			),
			'rightside' => array(
				'description'
			)
		),
		'params_query' => array(
			'left' => array(
				'query_params'
			)
		)
	);

	/**
     * Префикс для использования с сообщениями контроллера.
	 * @var        string    The prefix to use with controller messages.
	 * @since   1.6
	 */
	protected $text_prefix = 'COM_PRO_CRITICAL';

	/**
     * Псевдоним типа для этого типа контента.
	 * The type alias for this content type.
	 *
	 * @var      string
	 * @since    3.2
	 */
	public $typeAlias = 'com_pro_critical.html_task';

	/**
     * Возвращает объект Table, всегда создавая его
	 * Returns a Table object, always creating it
	 *
	 * @param   type    $type    The table type to instantiate
	 * @param   string  $prefix  A prefix for the table class name. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return  JTable  A database object
	 *
	 * @since   1.6
	 */
	public function getTable($type = 'html_task', $prefix = 'Pro_criticalTable', $config = array())
	{
		// add table path for when model gets used from other component
		$this->addTablePath(JPATH_ADMINISTRATOR . '/components/com_pro_critical/tables');
		// get instance of the table
		return JTable::getInstance($type, $prefix, $config);
	}
    
	/**
     * Метод получения единственной записи.
	 * Method to get a single record.
	 *
	 * @param   integer  $pk  The id of the primary key.
	 *
	 * @return  mixed  Object on success, false on failure.
	 *
	 * @since   1.6
	 */
	public function getItem($pk = null)
	{
		if ($item = parent::getItem($pk))
		{
			if (!empty($item->params) && !is_array($item->params))
			{
				// Convert the params field to an array.
				$registry = new Registry;
				$registry->loadString($item->params);
				$item->params = $registry->toArray();
			}

			if (!empty($item->metadata))
			{
				// Convert the metadata field to an array.
				$registry = new Registry;
				$registry->loadString($item->metadata);
				$item->metadata = $registry->toArray();
			}

			if (!empty($item->query_params))
			{
				// [Interpretation 7147] Convert the query_params field to an array.
				$query_params = new Registry;
				$query_params->loadString($item->query_params);
				$item->query_params = $query_params->toArray();
			}
			
			if (!empty($item->id))
			{
				$item->tags = new JHelperTags;
				$item->tags->getTagIds($item->id, 'com_pro_critical.html_task');
			}
		}

		return $item;
	}

	/**
     * Способ получения формы записи.
	 * Method to get the record form.
	 *
	 * @param   array    $data      Data for the form.
	 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
	 * @param   array    $options   Optional array of options for the form creation.
	 *
	 * @return  mixed  A JForm object on success, false on failure
	 *
	 * @since   1.6
	 */
	public function getForm($data = array(), $loadData = true, $options = array('control' => 'jform'))
	{
	    # установить опцию загрузки данных
		// set load data option
		$options['load_data'] = $loadData;

		# проверьте, установлен ли xpath в параметрах
		// [Interpretation 17799] check if xpath was set in options
		$xpath = false;
		if (isset($options['xpath']))
		{
			$xpath = $options['xpath'];
			unset($options['xpath']);
		}

		// проверьте, была ли установлена clear форма в настройках
		// [Interpretation 17807]  check if clear form was set in options
		$clear = false;
		if (isset($options['clear']))
		{
			$clear = $options['clear'];
			unset($options['clear']);
		}

		// [Interpretation 17815] Get the form.
		$form = $this->loadForm('com_pro_critical.html_task', 'html_task', $options, $clear, $xpath);

		if (empty($form))
		{
			return false;
		}

		$jinput = JFactory::getApplication()->input;

		# Внешний интерфейс вызывает эту модель и использует a_id,
        # чтобы избежать конфликтов идентификаторов, поэтому нам нужно сначала проверить это.
		// [Interpretation 17978]
        # The front end calls this model and uses a_id to avoid id clashes so we need to check for that first.
		if ($jinput->get('a_id'))
		{
			$id = $jinput->get('a_id', 0, 'INT');
		}

		# Серверная часть использует идентификатор, поэтому мы используем его в
        # остальное время и по умолчанию устанавливаем значение 0.
		# The back end uses id so we use that the rest of the time and set it to 0 by default.
		// [Interpretation 17986]
		else
		{
			$id = $jinput->get('id', 0, 'INT');
		}

		$user = JFactory::getUser();

		// [Interpretation 17995] Check for existing item.
		// [Interpretation 17997] Modify the form based on Edit State access controls.
		if ($id != 0 && (!$user->authorise('core.edit.state', 'com_pro_critical.html_task.' . (int) $id))
			|| ($id == 0 && !$user->authorise('core.edit.state', 'com_pro_critical')))
		{
			// [Interpretation 18029] Disable fields for display.
			$form->setFieldAttribute('ordering', 'disabled', 'true');
			$form->setFieldAttribute('published', 'disabled', 'true');
			// [Interpretation 18035] Disable fields while saving.
			$form->setFieldAttribute('ordering', 'filter', 'unset');
			$form->setFieldAttribute('published', 'filter', 'unset');
		}
		// [Interpretation 18043] If this is a new item insure the greated by is set.
		if (0 == $id)
		{
			// [Interpretation 18047] Set the created_by to this user
			$form->setValue('created_by', null, $user->id);
		}
		// [Interpretation 18052] Modify the form based on Edit Creaded By access controls.
		if (!$user->authorise('core.edit.created_by', 'com_pro_critical'))
		{
			// [Interpretation 18079] Disable fields for display.
			$form->setFieldAttribute('created_by', 'disabled', 'true');
			// [Interpretation 18083] Disable fields for display.
			$form->setFieldAttribute('created_by', 'readonly', 'true');
			// [Interpretation 18087] Disable fields while saving.
			$form->setFieldAttribute('created_by', 'filter', 'unset');
		}
		// [Interpretation 18092] Modify the form based on Edit Creaded Date access controls.
		if (!$user->authorise('core.edit.created', 'com_pro_critical'))
		{
			// [Interpretation 18118] Disable fields for display.
			$form->setFieldAttribute('created', 'disabled', 'true');
			// [Interpretation 18122] Disable fields while saving.
			$form->setFieldAttribute('created', 'filter', 'unset');
		}
		// [Interpretation 18200] Only load these values if no id is found
		if (0 == $id)
		{
			// [Interpretation 18204] Set redirected view name
			$redirectedView = $jinput->get('ref', null, 'STRING');
			// [Interpretation 18208] Set field name (or fall back to view name)
			$redirectedField = $jinput->get('field', $redirectedView, 'STRING');
			// [Interpretation 18212] Set redirected view id
			$redirectedId = $jinput->get('refid', 0, 'INT');
			// [Interpretation 18216] Set field id (or fall back to redirected view id)
			$redirectedValue = $jinput->get('field_id', $redirectedId, 'INT');
			if (0 != $redirectedValue && $redirectedField)
			{
				// [Interpretation 18223] Now set the local-redirected field default value
				$form->setValue($redirectedField, null, $redirectedValue);
			}
		}
		return $form;
	}

	/**
     * Метод получения скрипта, который необходимо включить в форму
	 * Method to get the script that have to be included on the form
	 *
	 * @return string	script files
     * @since 3.9
	 */
	public function getScript()
	{
		return 'administrator/components/com_pro_critical/models/forms/html_task.js';
	}
    
	/**
     * Метод проверки возможности удаления записи.
	 * Method to test whether a record can be deleted.
	 *
	 * @param   object  $record  A record object.
	 *
	 * @return  boolean  True if allowed to delete the record. Defaults to the permission set in the component.
	 *
	 * @since   1.6
	 */
	protected function canDelete($record)
	{
		if (!empty($record->id))
		{
			if ($record->published != -2)
			{
				return;
			}

			$user = JFactory::getUser();
			// [Interpretation 18550] The record has been set. Check the record permissions.
			return $user->authorise('core.delete', 'com_pro_critical.html_task.' . (int) $record->id);
		}
		return false;
	}

	/**
     * Метод проверки, можно ли изменить состояние записи.
	 * Method to test whether a record can have its state edited.
	 *
	 * @param   object  $record  A record object.
	 *
	 * @return  boolean  True if allowed to change the state of the record. Defaults to the permission set in the component.
	 *
	 * @since   1.6
	 */
	protected function canEditState($record)
	{
		$user = JFactory::getUser();
		$recordId = (!empty($record->id)) ? $record->id : 0;

		if ($recordId)
		{
			// [Interpretation 18674] The record has been set. Check the record permissions.
			$permission = $user->authorise('core.edit.state', 'com_pro_critical.html_task.' . (int) $recordId);
			if (!$permission && !is_null($permission))
			{
				return false;
			}
		}
		// [Interpretation 18722] In the absense of better information, revert to the component permissions.
		return parent::canEditState($record);
	}
    
	/**
     * Переопределение метода, чтобы проверить, можете ли вы редактировать существующую запись.
	 * Method override to check if you can edit an existing record.
	 *
	 * @param	array	$data	An array of input data.
	 * @param	string	$key	The name of the key for the primary key.
	 *
	 * @return	boolean
	 * @since	2.5
	 */
	protected function allowEdit($data = array(), $key = 'id')
	{
		// [Interpretation 18425] Check specific edit permission then general edit permission.

		return JFactory::getUser()->authorise('core.edit', 'com_pro_critical.html_task.'. ((int) isset($data[$key]) ? $data[$key] : 0)) or parent::allowEdit($data, $key);
	}
    
	/**
     * Подготовьте и очистите данные таблицы перед сохранением.
	 * Prepare and sanitise the table data prior to saving.
	 *
	 * @param   JTable  $table  A JTable object.
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	protected function prepareTable($table)
	{
		$date = JFactory::getDate();
		$user = JFactory::getUser();
		
		if (isset($table->name))
		{
			$table->name = htmlspecialchars_decode($table->name, ENT_QUOTES);
		}
		
		if (isset($table->alias) && empty($table->alias))
		{
			$table->generateAlias();
		}
		
		if (empty($table->id))
		{
			$table->created = $date->toSql();
			// set the user
			if ($table->created_by == 0 || empty($table->created_by))
			{
				$table->created_by = $user->id;
			}
			// Set ordering to the last item if not set
			if (empty($table->ordering))
			{
				$db = JFactory::getDbo();
				$query = $db->getQuery(true)
					->select('MAX(ordering)')
					->from($db->quoteName('#__pro_critical_html_task'));
				$db->setQuery($query);
				$max = $db->loadResult();

				$table->ordering = $max + 1;
			}
		}
		else
		{
			$table->modified = $date->toSql();
			$table->modified_by = $user->id;
		}
        
		if (!empty($table->id))
		{
			// Increment the items version number.
			$table->version++;
		}
	}

	/**
     * Метод получения данных, которые следует ввести в форму.
	 * Method to get the data that should be injected in the form.
	 *
	 * @return  mixed  The data for the form.
	 *
	 * @since   1.6
	 */
	protected function loadFormData() 
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_pro_critical.edit.html_task.data', array());

		if (empty($data))
		{
			$data = $this->getItem();
			# запустить перпроцесс данных
			// run the perprocess of the data
			$this->preprocessData('com_pro_critical.html_task', $data);
		}

		return $data;
	}

	/**
     * Метод получения уникальных полей этой таблицы.
	 * Method to get the unique fields of this table.
	 *
	 * @return  mixed  An array of field names, boolean false if none is set.
	 *
	 * @since   3.0
	 */
	protected function getUniqeFields()
	{
		return false;
	}
	
	/**
     * Метод удаления одной или нескольких записей.
	 * Method to delete one or more records.
	 *
	 * @param   array  &$pks  An array of record primary keys.
	 *
	 * @return  boolean  True if successful, false if an error occurs.
	 *
	 * @since   12.2
	 */
	public function delete(&$pks)
	{
		if (!parent::delete($pks))
		{
			return false;
		}
		
		return true;
	}

	/**
     * Метод изменения опубликованного состояния одной или нескольких записей.
	 * Method to change the published state of one or more records.
	 *
	 * @param   array    &$pks   A list of the primary keys to change.
	 * @param   integer  $value  The value of the published state.
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   12.2
	 */
	public function publish(&$pks, $value = 1)
	{
		if (!parent::publish($pks, $value))
		{
			return false;
		}
		
		return true;
        }
    
	/**
     * Метод для выполнения пакетных операций с элементом или набором элементов.
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
		$this->user			= JFactory::getUser();
		$this->table			= $this->getTable();
		$this->tableClassName		= get_class($this->table);
		$this->contentType		= new JUcmType;
		$this->type			= $this->contentType->getTypeByTable($this->tableClassName);
		$this->canDo			= Pro_criticalHelper::getActions('html_task');
		$this->batchSet			= true;

		if (!$this->canDo->get('core.batch'))
		{
			$this->setError(JText::_('JLIB_APPLICATION_ERROR_INSUFFICIENT_BATCH_INFORMATION'));
			return false;
		}
        
		if ($this->type == false)
		{
			$type = new JUcmType;
			$this->type = $type->getTypeByAlias($this->typeAlias);
		}

		$this->tagsObserver = $this->table->getObserverOfClass('JTableObserverTags');

		if (!empty($commands['move_copy']))
		{
			$cmd = JArrayHelper::getValue($commands, 'move_copy', 'c');

			if ($cmd == 'c')
			{
				$result = $this->batchCopy($commands, $pks, $contexts);

				if (is_array($result))
				{
					foreach ($result as $old => $new)
					{
						$contexts[$new] = $contexts[$old];
					}
					$pks = array_values($result);
				}
				else
				{
					return false;
				}
			}
			elseif ($cmd == 'm' && !$this->batchMove($commands, $pks, $contexts))
			{
				return false;
			}

			$done = true;
		}

		if (!$done)
		{
			$this->setError(JText::_('JLIB_APPLICATION_ERROR_INSUFFICIENT_BATCH_INFORMATION'));

			return false;
		}

		// Clear the cache
		$this->cleanCache();

		return true;
	}

	/**
     * Пакетное копирование элементов в новую категорию или текущую.
	 * Batch copy items to a new category or current.
	 *
	 * @param   integer  $values    The new values.
	 * @param   array    $pks       An array of row IDs.
	 * @param   array    $contexts  An array of item contexts.
	 *
	 * @return  mixed  An array of new IDs on success, boolean false on failure.
	 *
	 * @since 12.2
	 */
	protected function batchCopy($values, $pks, $contexts)
	{
		if (empty($this->batchSet))
		{
			// [Interpretation 9035] Set some needed variables.
			$this->user 		= JFactory::getUser();
			$this->table 		= $this->getTable();
			$this->tableClassName	= get_class($this->table);
			$this->canDo		= Pro_criticalHelper::getActions('html_task');
		}

		if (!$this->canDo->get('core.create') || !$this->canDo->get('core.batch'))
		{
			return false;
		}

		// [Interpretation 9069] get list of uniqe fields
		$uniqeFields = $this->getUniqeFields();
		// [Interpretation 9073] remove move_copy from array
		unset($values['move_copy']);

		// [Interpretation 9077] make sure published is set
		if (!isset($values['published']))
		{
			$values['published'] = 0;
		}
		elseif (isset($values['published']) && !$this->canDo->get('core.edit.state'))
		{
				$values['published'] = 0;
		}

		$newIds = array();
		// [Interpretation 9131] Parent exists so let's proceed
		while (!empty($pks))
		{
			// [Interpretation 9135] Pop the first ID off the stack
			$pk = array_shift($pks);

			$this->table->reset();

			// [Interpretation 9141] only allow copy if user may edit this item.
			if (!$this->user->authorise('core.edit', $contexts[$pk]))
			{
				// [Interpretation 9161] Not fatal error
				$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_BATCH_MOVE_ROW_NOT_FOUND', $pk));
				continue;
			}

			// [Interpretation 9168] Check that the row actually exists
			if (!$this->table->load($pk))
			{
				if ($error = $this->table->getError())
				{
					// [Interpretation 9175] Fatal error
					$this->setError($error);
					return false;
				}
				else
				{
					// [Interpretation 9183] Not fatal error
					$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_BATCH_MOVE_ROW_NOT_FOUND', $pk));
					continue;
				}
			}

			// [Interpretation 9254] Only for strings
			if (Pro_criticalHelper::checkString($this->table->task_id) && !is_numeric($this->table->task_id))
			{
				$this->table->task_id = $this->generateUniqe('task_id',$this->table->task_id);
			}

			// [Interpretation 9268] insert all set values
			if (Pro_criticalHelper::checkArray($values))
			{
				foreach ($values as $key => $value)
				{
					if (strlen($value) > 0 && isset($this->table->$key))
					{
						$this->table->$key = $value;
					}
				}
			}

			// [Interpretation 9283] update all uniqe fields
			if (Pro_criticalHelper::checkArray($uniqeFields))
			{
				foreach ($uniqeFields as $uniqeField)
				{
					$this->table->$uniqeField = $this->generateUniqe($uniqeField,$this->table->$uniqeField);
				}
			}

			// [Interpretation 9295] Reset the ID because we are making a copy
			$this->table->id = 0;

			// [Interpretation 9299] TODO: Deal with ordering?
			// [Interpretation 9301] $this->table->ordering = 1;

			// [Interpretation 9304] Check the row.
			if (!$this->table->check())
			{
				$this->setError($this->table->getError());

				return false;
			}

			if (!empty($this->type))
			{
				$this->createTagsHelper($this->tagsObserver, $this->type, $pk, $this->typeAlias, $this->table);
			}

			// [Interpretation 9320] Store the row.
			if (!$this->table->store())
			{
				$this->setError($this->table->getError());

				return false;
			}

			// [Interpretation 9330] Get the new item ID
			$newId = $this->table->get('id');

			// [Interpretation 9334] Add the new ID to the array
			$newIds[$pk] = $newId;
		}

		// [Interpretation 9339] Clean the cache
		$this->cleanCache();

		return $newIds;
	}

	/**
     * Пакетное перемещение элементов в новую категорию
	 * Batch move items to a new category
	 *
	 * @param   integer  $value     The new category ID.
	 * @param   array    $pks       An array of row IDs.
	 * @param   array    $contexts  An array of item contexts.
	 *
	 * @return  boolean  True if successful, false otherwise and internal error is set.
	 *
	 * @since 12.2
	 */
	protected function batchMove($values, $pks, $contexts)
	{
		if (empty($this->batchSet))
		{
			// [Interpretation 8757] Set some needed variables.
			$this->user		= JFactory::getUser();
			$this->table		= $this->getTable();
			$this->tableClassName	= get_class($this->table);
			$this->canDo		= Pro_criticalHelper::getActions('html_task');
		}

		if (!$this->canDo->get('core.edit') && !$this->canDo->get('core.batch'))
		{
			$this->setError(JText::_('JLIB_APPLICATION_ERROR_BATCH_CANNOT_EDIT'));
			return false;
		}

		// [Interpretation 8794] make sure published only updates if user has the permission.
		if (isset($values['published']) && !$this->canDo->get('core.edit.state'))
		{
			unset($values['published']);
		}
		// [Interpretation 8819] remove move_copy from array
		unset($values['move_copy']);

		// [Interpretation 8846] Parent exists so we proceed
		foreach ($pks as $pk)
		{
			if (!$this->user->authorise('core.edit', $contexts[$pk]))
			{
				$this->setError(JText::_('JLIB_APPLICATION_ERROR_BATCH_CANNOT_EDIT'));
				return false;
			}

			// [Interpretation 8874] Check that the row actually exists
			if (!$this->table->load($pk))
			{
				if ($error = $this->table->getError())
				{
					// [Interpretation 8881] Fatal error
					$this->setError($error);
					return false;
				}
				else
				{
					// [Interpretation 8889] Not fatal error
					$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_BATCH_MOVE_ROW_NOT_FOUND', $pk));
					continue;
				}
			}

			// [Interpretation 8897] insert all set values.
			if (Pro_criticalHelper::checkArray($values))
			{
				foreach ($values as $key => $value)
				{
					// [Interpretation 8904] Do special action for access.
					if ('access' === $key && strlen($value) > 0)
					{
						$this->table->$key = $value;
					}
					elseif (strlen($value) > 0 && isset($this->table->$key))
					{
						$this->table->$key = $value;
					}
				}
			}


			// [Interpretation 8919] Check the row.
			if (!$this->table->check())
			{
				$this->setError($this->table->getError());

				return false;
			}

			if (!empty($this->type))
			{
				$this->createTagsHelper($this->tagsObserver, $this->type, $pk, $this->typeAlias, $this->table);
			}

			// [Interpretation 8935] Store the row.
			if (!$this->table->store())
			{
				$this->setError($this->table->getError());

				return false;
			}
		}

		// [Interpretation 8946] Clean the cache
		$this->cleanCache();

		return true;
	}
	
	/**
     * Method сохранения данных формы.
	 * Method to save the form data.
	 *
	 * @param   array  $data  The form data.
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   1.6
	 */
	public function save($data)
	{
		$input	= JFactory::getApplication()->input;
		$filter	= JFilterInput::getInstance();
        
		// set the metadata to the Item Data
		if (isset($data['metadata']) && isset($data['metadata']['author']))
		{
			$data['metadata']['author'] = $filter->clean($data['metadata']['author'], 'TRIM');
            
			$metadata = new JRegistry;
			$metadata->loadArray($data['metadata']);
			$data['metadata'] = (string) $metadata;
		}

		// [Interpretation 7247] Set the query_params items to data.
		if (isset($data['query_params']) && is_array($data['query_params']))
		{
			$query_params = new JRegistry;
			$query_params->loadArray($data['query_params']);
			$data['query_params'] = (string) $query_params;
		}
		elseif (!isset($data['query_params']))
		{
			// [Interpretation 7297] Set the empty query_params to data
			$data['query_params'] = '';
		}
        
		// Set the Params Items to data
		if (isset($data['params']) && is_array($data['params']))
		{
			$params = new JRegistry;
			$params->loadArray($data['params']);
			$data['params'] = (string) $params;
		}

		// [Interpretation 9532] Alter the uniqe field for save as copy
		if ($input->get('task') === 'save2copy')
		{
			// [Interpretation 9537] Automatic handling of other uniqe fields
			$uniqeFields = $this->getUniqeFields();
			if (Pro_criticalHelper::checkArray($uniqeFields))
			{
				foreach ($uniqeFields as $uniqeField)
				{
					$data[$uniqeField] = $this->generateUniqe($uniqeField,$data[$uniqeField]);
				}
			}
		}
		
		if (parent::save($data))
		{
			return true;
		}
		return false;
	}

    /**
     * Метод создания уникального значения.
     * Method to generate a uniqe value.
     *
     * @param string $field name.
     * @param string $value data.
     *
     * @return  string  New value.
     *
     * @since   3.0
     */
	protected function generateUniqe(string $field, string $value)
	{

		// set field value uniqe 
		$table = $this->getTable();

		while ($table->load(array($field => $value)))
		{
			$value = JString::increment($value);
		}

		return $value;
	}

	/**
     * Способ изменения заголовка
	 * Method to change the title
	 *
	 * @param   string   $title   The title.
	 *
	 * @return	array   Содержит измененный заголовок и псевдоним.
     *                  Contains the modified title and alias.
	 * @since   3.0
	 */
	protected function _generateNewTitle($title)
	{

		// [Interpretation 9651] Alter the title
		$table = $this->getTable();

		while ($table->load(array('title' => $title)))
		{
			$title = JString::increment($title);
		}

		return $title;
	}
}
