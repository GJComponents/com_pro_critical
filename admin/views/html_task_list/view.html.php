<?php
/*----------------------------------------------------------------------------------|  www.vdm.io  |----/
				Gartes 
/-------------------------------------------------------------------------------------------------------/

	@version		1.x.x
	@build			23rd августа, 2020
	@created		5th мая, 2019
	@package		proCritical
	@subpackage		view.html.php
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
 * Pro_critical View class for the Html_task_list
 */
class Pro_criticalViewHtml_task_list extends JViewLegacy
{
	/**
	 * Html_task_list view display method
	 * @return void
	 */
	function display($tpl = null)
	{
		if ($this->getLayout() !== 'modal')
		{
			// Include helper submenu
			Pro_criticalHelper::addSubmenu('html_task_list');
		}

		// Assign data to the view
		$this->items = $this->get('Items');
		$this->pagination = $this->get('Pagination');
		$this->state = $this->get('State');
		$this->user = JFactory::getUser();
		$this->listOrder = $this->escape($this->state->get('list.ordering'));
		$this->listDirn = $this->escape($this->state->get('list.direction'));
		$this->saveOrder = $this->listOrder == 'ordering';
		// set the return here value
		$this->return_here = urlencode(base64_encode((string) JUri::getInstance()));
		// get global action permissions
		$this->canDo = Pro_criticalHelper::getActions('html_task');
		$this->canEdit = $this->canDo->get('core.edit');
		$this->canState = $this->canDo->get('core.edit.state');
		$this->canCreate = $this->canDo->get('core.create');
		$this->canDelete = $this->canDo->get('core.delete');
		$this->canBatch = $this->canDo->get('core.batch');

		// We don't need toolbar in the modal window.
		if ($this->getLayout() !== 'modal')
		{
			$this->addToolbar();
			$this->sidebar = JHtmlSidebar::render();
			// load the batch html
			if ($this->canCreate && $this->canEdit && $this->canState)
			{
				$this->batchDisplay = JHtmlBatch_::render();
			}
		}
		
		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors), 500);
		}

		// Display the template
		parent::display($tpl);

		// Set the document
		$this->setDocument();
	}

	/**
	 * Setting the toolbar
	 */
	protected function addToolBar()
	{
		JToolBarHelper::title(JText::_('COM_PRO_CRITICAL_HTML_TASK_LIST'), 'joomla');
		JHtmlSidebar::setAction('index.php?option=com_pro_critical&view=html_task_list');
		JFormHelper::addFieldPath(JPATH_COMPONENT . '/models/fields');

		if ($this->canCreate)
		{
			JToolBarHelper::addNew('html_task.add');
		}

		// Only load if there are items
		if (Pro_criticalHelper::checkArray($this->items))
		{
			if ($this->canEdit)
			{
				JToolBarHelper::editList('html_task.edit');
			}

			if ($this->canState)
			{
				JToolBarHelper::publishList('html_task_list.publish');
				JToolBarHelper::unpublishList('html_task_list.unpublish');
				JToolBarHelper::archiveList('html_task_list.archive');

				if ($this->canDo->get('core.admin'))
				{
					JToolBarHelper::checkin('html_task_list.checkin');
				}
			}

			// Add a batch button
			if ($this->canBatch && $this->canCreate && $this->canEdit && $this->canState)
			{
				// Get the toolbar object instance
				$bar = JToolBar::getInstance('toolbar');
				// set the batch button name
				$title = JText::_('JTOOLBAR_BATCH');
				// Instantiate a new JLayoutFile instance and render the batch button
				$layout = new JLayoutFile('joomla.toolbar.batch');
				// add the button to the page
				$dhtml = $layout->render(array('title' => $title));
				$bar->appendButton('Custom', $dhtml, 'batch');
			}

			if ($this->state->get('filter.published') == -2 && ($this->canState && $this->canDelete))
			{
				JToolbarHelper::deleteList('', 'html_task_list.delete', 'JTOOLBAR_EMPTY_TRASH');
			}
			elseif ($this->canState && $this->canDelete)
			{
				JToolbarHelper::trash('html_task_list.trash');
			}
		}

		// set help url for this view if found
		$help_url = Pro_criticalHelper::getHelpUrl('html_task_list');
		if (Pro_criticalHelper::checkString($help_url))
		{
				JToolbarHelper::help('COM_PRO_CRITICAL_HELP_MANAGER', false, $help_url);
		}

		// add the options comp button
		if ($this->canDo->get('core.admin') || $this->canDo->get('core.options'))
		{
			JToolBarHelper::preferences('com_pro_critical');
		}

		if ($this->canState)
		{
			JHtmlSidebar::addFilter(
				JText::_('JOPTION_SELECT_PUBLISHED'),
				'filter_published',
				JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter.published'), true)
			);
			// only load if batch allowed
			if ($this->canBatch)
			{
				JHtmlBatch_::addListSelection(
					JText::_('COM_PRO_CRITICAL_KEEP_ORIGINAL_STATE'),
					'batch[published]',
					JHtml::_('select.options', JHtml::_('jgrid.publishedOptions', array('all' => false)), 'value', 'text', '', true)
				);
			}
		}

		JHtmlSidebar::addFilter(
			JText::_('JOPTION_SELECT_ACCESS'),
			'filter_access',
			JHtml::_('select.options', JHtml::_('access.assetgroups'), 'value', 'text', $this->state->get('filter.access'))
		);

		if ($this->canBatch && $this->canCreate && $this->canEdit)
		{
			JHtmlBatch_::addListSelection(
				JText::_('COM_PRO_CRITICAL_KEEP_ORIGINAL_ACCESS'),
				'batch[access]',
				JHtml::_('select.options', JHtml::_('access.assetgroups'), 'value', 'text')
			);
		}

		// [Interpretation 17054] Set Task Id Selection
		$this->task_idOptions = $this->getTheTask_idSelections();
		// [Interpretation 17059] We do some sanitation for Task Id filter
		if (Pro_criticalHelper::checkArray($this->task_idOptions) &&
			isset($this->task_idOptions[0]->value) &&
			!Pro_criticalHelper::checkString($this->task_idOptions[0]->value))
		{
			unset($this->task_idOptions[0]);
		}
		// [Interpretation 17075] Only load Task Id filter if it has values
		if (Pro_criticalHelper::checkArray($this->task_idOptions))
		{
			// [Interpretation 17083] Task Id Filter
			JHtmlSidebar::addFilter(
				'- Select '.JText::_('COM_PRO_CRITICAL_HTML_TASK_TASK_ID_LABEL').' -',
				'filter_task_id',
				JHtml::_('select.options', $this->task_idOptions, 'value', 'text', $this->state->get('filter.task_id'))
			);

			if ($this->canBatch && $this->canCreate && $this->canEdit)
			{
				// [Interpretation 17101] Task Id Batch Selection
				JHtmlBatch_::addListSelection(
					'- Keep Original '.JText::_('COM_PRO_CRITICAL_HTML_TASK_TASK_ID_LABEL').' -',
					'batch[task_id]',
					JHtml::_('select.options', $this->task_idOptions, 'value', 'text')
				);
			}
		}

		// [Interpretation 17054] Set Short Description Selection
		$this->short_descriptionOptions = $this->getTheShort_descriptionSelections();
		// [Interpretation 17059] We do some sanitation for Short Description filter
		if (Pro_criticalHelper::checkArray($this->short_descriptionOptions) &&
			isset($this->short_descriptionOptions[0]->value) &&
			!Pro_criticalHelper::checkString($this->short_descriptionOptions[0]->value))
		{
			unset($this->short_descriptionOptions[0]);
		}
		// [Interpretation 17075] Only load Short Description filter if it has values
		if (Pro_criticalHelper::checkArray($this->short_descriptionOptions))
		{
			// [Interpretation 17083] Short Description Filter
			JHtmlSidebar::addFilter(
				'- Select '.JText::_('COM_PRO_CRITICAL_HTML_TASK_SHORT_DESCRIPTION_LABEL').' -',
				'filter_short_description',
				JHtml::_('select.options', $this->short_descriptionOptions, 'value', 'text', $this->state->get('filter.short_description'))
			);

			if ($this->canBatch && $this->canCreate && $this->canEdit)
			{
				// [Interpretation 17101] Short Description Batch Selection
				JHtmlBatch_::addListSelection(
					'- Keep Original '.JText::_('COM_PRO_CRITICAL_HTML_TASK_SHORT_DESCRIPTION_LABEL').' -',
					'batch[short_description]',
					JHtml::_('select.options', $this->short_descriptionOptions, 'value', 'text')
				);
			}
		}

		// [Interpretation 16971] Set Component View Id View Component Selection
		$this->component_view_idView_componentOptions = JFormHelper::loadFieldType('Componentviewid')->options;
		// [Interpretation 16977] We do some sanitation for Component View Id View Component filter
		if (Pro_criticalHelper::checkArray($this->component_view_idView_componentOptions) &&
			isset($this->component_view_idView_componentOptions[0]->value) &&
			!Pro_criticalHelper::checkString($this->component_view_idView_componentOptions[0]->value))
		{
			unset($this->component_view_idView_componentOptions[0]);
		}
		// [Interpretation 16993] Only load Component View Id View Component filter if it has values
		if (Pro_criticalHelper::checkArray($this->component_view_idView_componentOptions))
		{
			// [Interpretation 17001] Component View Id View Component Filter
			JHtmlSidebar::addFilter(
				'- Select '.JText::_('COM_PRO_CRITICAL_HTML_TASK_COMPONENT_VIEW_ID_LABEL').' -',
				'filter_component_view_id',
				JHtml::_('select.options', $this->component_view_idView_componentOptions, 'value', 'text', $this->state->get('filter.component_view_id'))
			);

			if ($this->canBatch && $this->canCreate && $this->canEdit)
			{
				// [Interpretation 17018] Component View Id View Component Batch Selection
				JHtmlBatch_::addListSelection(
					'- Keep Original '.JText::_('COM_PRO_CRITICAL_HTML_TASK_COMPONENT_VIEW_ID_LABEL').' -',
					'batch[component_view_id]',
					JHtml::_('select.options', $this->component_view_idView_componentOptions, 'value', 'text')
				);
			}
		}

		// [Interpretation 17054] Set Html Processing Selection
		$this->html_processingOptions = $this->getTheHtml_processingSelections();
		// [Interpretation 17059] We do some sanitation for Html Processing filter
		if (Pro_criticalHelper::checkArray($this->html_processingOptions) &&
			isset($this->html_processingOptions[0]->value) &&
			!Pro_criticalHelper::checkString($this->html_processingOptions[0]->value))
		{
			unset($this->html_processingOptions[0]);
		}
		// [Interpretation 17075] Only load Html Processing filter if it has values
		if (Pro_criticalHelper::checkArray($this->html_processingOptions))
		{
			// [Interpretation 17083] Html Processing Filter
			JHtmlSidebar::addFilter(
				'- Select '.JText::_('COM_PRO_CRITICAL_HTML_TASK_HTML_PROCESSING_LABEL').' -',
				'filter_html_processing',
				JHtml::_('select.options', $this->html_processingOptions, 'value', 'text', $this->state->get('filter.html_processing'))
			);

			if ($this->canBatch && $this->canCreate && $this->canEdit)
			{
				// [Interpretation 17101] Html Processing Batch Selection
				JHtmlBatch_::addListSelection(
					'- Keep Original '.JText::_('COM_PRO_CRITICAL_HTML_TASK_HTML_PROCESSING_LABEL').' -',
					'batch[html_processing]',
					JHtml::_('select.options', $this->html_processingOptions, 'value', 'text')
				);
			}
		}

		// [Interpretation 16971] Set Type Device Id Type Device Selection
		$this->type_device_idType_deviceOptions = JFormHelper::loadFieldType('Typedeviceidhtml')->options;
		// [Interpretation 16977] We do some sanitation for Type Device Id Type Device filter
		if (Pro_criticalHelper::checkArray($this->type_device_idType_deviceOptions) &&
			isset($this->type_device_idType_deviceOptions[0]->value) &&
			!Pro_criticalHelper::checkString($this->type_device_idType_deviceOptions[0]->value))
		{
			unset($this->type_device_idType_deviceOptions[0]);
		}
		// [Interpretation 16993] Only load Type Device Id Type Device filter if it has values
		if (Pro_criticalHelper::checkArray($this->type_device_idType_deviceOptions))
		{
			// [Interpretation 17001] Type Device Id Type Device Filter
			JHtmlSidebar::addFilter(
				'- Select '.JText::_('COM_PRO_CRITICAL_HTML_TASK_TYPE_DEVICE_ID_LABEL').' -',
				'filter_type_device_id',
				JHtml::_('select.options', $this->type_device_idType_deviceOptions, 'value', 'text', $this->state->get('filter.type_device_id'))
			);

			if ($this->canBatch && $this->canCreate && $this->canEdit)
			{
				// [Interpretation 17018] Type Device Id Type Device Batch Selection
				JHtmlBatch_::addListSelection(
					'- Keep Original '.JText::_('COM_PRO_CRITICAL_HTML_TASK_TYPE_DEVICE_ID_LABEL').' -',
					'batch[type_device_id]',
					JHtml::_('select.options', $this->type_device_idType_deviceOptions, 'value', 'text')
				);
			}
		}
	}

	/**
	 * Method to set up the document properties
	 *
	 * @return void
	 */
	protected function setDocument()
	{
		if (!isset($this->document))
		{
			$this->document = JFactory::getDocument();
		}
		$this->document->setTitle(JText::_('COM_PRO_CRITICAL_HTML_TASK_LIST'));
		$this->document->addStyleSheet(JURI::root() . "administrator/components/com_pro_critical/assets/css/html_task_list.css", (Pro_criticalHelper::jVersion()->isCompatible('3.8.0')) ? array('version' => 'auto') : 'text/css');
	}

	/**
	 * Escapes a value for output in a view script.
	 *
	 * @param   mixed  $var  The output to escape.
	 *
	 * @return  mixed  The escaped value.
	 */
	public function escape($var)
	{
		if(strlen($var) > 50)
		{
			// use the helper htmlEscape method instead and shorten the string
			return Pro_criticalHelper::htmlEscape($var, $this->_charset, true);
		}
		// use the helper htmlEscape method instead.
		return Pro_criticalHelper::htmlEscape($var, $this->_charset);
	}

	/**
	 * Returns an array of fields the table can be sorted by
	 *
	 * @return  array  Array containing the field name to sort by as the key and display text as value
	 */
	protected function getSortFields()
	{
		return array(
			'a.sorting' => JText::_('JGRID_HEADING_ORDERING'),
			'a.published' => JText::_('JSTATUS'),
			'a.task_id' => JText::_('COM_PRO_CRITICAL_HTML_TASK_TASK_ID_LABEL'),
			'a.short_description' => JText::_('COM_PRO_CRITICAL_HTML_TASK_SHORT_DESCRIPTION_LABEL'),
			'g.view_component' => JText::_('COM_PRO_CRITICAL_HTML_TASK_COMPONENT_VIEW_ID_LABEL'),
			'a.html_processing' => JText::_('COM_PRO_CRITICAL_HTML_TASK_HTML_PROCESSING_LABEL'),
			'h.type_device' => JText::_('COM_PRO_CRITICAL_HTML_TASK_TYPE_DEVICE_ID_LABEL'),
			'a.id' => JText::_('JGRID_HEADING_ID')
		);
	}

	protected function getTheTask_idSelections()
	{
		// [Interpretation 16761] Get a db connection.
		$db = JFactory::getDbo();

		// [Interpretation 16765] Create a new query object.
		$query = $db->getQuery(true);

		// [Interpretation 16801] Select the text.
		$query->select($db->quoteName('task_id'));
		$query->from($db->quoteName('#__pro_critical_html_task'));
		$query->order($db->quoteName('task_id') . ' ASC');

		// [Interpretation 16812] Reset the query using our newly populated query object.
		$db->setQuery($query);

		$results = $db->loadColumn();

		if ($results)
		{
			$results = array_unique($results);
			$_filter = array();
			foreach ($results as $task_id)
			{
				// [Interpretation 16888] Now add the task_id and its text to the options array
				$_filter[] = JHtml::_('select.option', $task_id, $task_id);
			}
			return $_filter;
		}
		return false;
	}

	protected function getTheShort_descriptionSelections()
	{
		// [Interpretation 16761] Get a db connection.
		$db = JFactory::getDbo();

		// [Interpretation 16765] Create a new query object.
		$query = $db->getQuery(true);

		// [Interpretation 16801] Select the text.
		$query->select($db->quoteName('short_description'));
		$query->from($db->quoteName('#__pro_critical_html_task'));
		$query->order($db->quoteName('short_description') . ' ASC');

		// [Interpretation 16812] Reset the query using our newly populated query object.
		$db->setQuery($query);

		$results = $db->loadColumn();

		if ($results)
		{
			$results = array_unique($results);
			$_filter = array();
			foreach ($results as $short_description)
			{
				// [Interpretation 16888] Now add the short_description and its text to the options array
				$_filter[] = JHtml::_('select.option', $short_description, $short_description);
			}
			return $_filter;
		}
		return false;
	}

	protected function getTheHtml_processingSelections()
	{
		// [Interpretation 16761] Get a db connection.
		$db = JFactory::getDbo();

		// [Interpretation 16765] Create a new query object.
		$query = $db->getQuery(true);

		// [Interpretation 16801] Select the text.
		$query->select($db->quoteName('html_processing'));
		$query->from($db->quoteName('#__pro_critical_html_task'));
		$query->order($db->quoteName('html_processing') . ' ASC');

		// [Interpretation 16812] Reset the query using our newly populated query object.
		$db->setQuery($query);

		$results = $db->loadColumn();

		if ($results)
		{
			// [Interpretation 16826] get model
			$model = $this->getModel();
			$results = array_unique($results);
			$_filter = array();
			foreach ($results as $html_processing)
			{
				// [Interpretation 16847] Translate the html_processing selection
				$text = $model->selectionTranslation($html_processing,'html_processing');
				// [Interpretation 16854] Now add the html_processing and its text to the options array
				$_filter[] = JHtml::_('select.option', $html_processing, JText::_($text));
			}
			return $_filter;
		}
		return false;
	}
}
