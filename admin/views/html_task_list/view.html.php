<?php
/*----------------------------------------------------------------------------------|  www.vdm.io  |----/
				Gartes 
/-------------------------------------------------------------------------------------------------------/

	@version		1.5.19
	@build			23rd декабря, 2019
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

		// [Interpretation 11272] Set Task Id Selection
		$this->task_idOptions = $this->getTheTask_idSelections();
		// [Interpretation 11274] We do some sanitation for Task Id filter
		if (Pro_criticalHelper::checkArray($this->task_idOptions) &&
			isset($this->task_idOptions[0]->value) &&
			!Pro_criticalHelper::checkString($this->task_idOptions[0]->value))
		{
			unset($this->task_idOptions[0]);
		}
		// [Interpretation 11281] Only load Task Id filter if it has values
		if (Pro_criticalHelper::checkArray($this->task_idOptions))
		{
			// [Interpretation 11284] Task Id Filter
			JHtmlSidebar::addFilter(
				'- Select '.JText::_('COM_PRO_CRITICAL_HTML_TASK_TASK_ID_LABEL').' -',
				'filter_task_id',
				JHtml::_('select.options', $this->task_idOptions, 'value', 'text', $this->state->get('filter.task_id'))
			);

			if ($this->canBatch && $this->canCreate && $this->canEdit)
			{
				// [Interpretation 11293] Task Id Batch Selection
				JHtmlBatch_::addListSelection(
					'- Keep Original '.JText::_('COM_PRO_CRITICAL_HTML_TASK_TASK_ID_LABEL').' -',
					'batch[task_id]',
					JHtml::_('select.options', $this->task_idOptions, 'value', 'text')
				);
			}
		}

		// [Interpretation 11272] Set Short Description Selection
		$this->short_descriptionOptions = $this->getTheShort_descriptionSelections();
		// [Interpretation 11274] We do some sanitation for Short Description filter
		if (Pro_criticalHelper::checkArray($this->short_descriptionOptions) &&
			isset($this->short_descriptionOptions[0]->value) &&
			!Pro_criticalHelper::checkString($this->short_descriptionOptions[0]->value))
		{
			unset($this->short_descriptionOptions[0]);
		}
		// [Interpretation 11281] Only load Short Description filter if it has values
		if (Pro_criticalHelper::checkArray($this->short_descriptionOptions))
		{
			// [Interpretation 11284] Short Description Filter
			JHtmlSidebar::addFilter(
				'- Select '.JText::_('COM_PRO_CRITICAL_HTML_TASK_SHORT_DESCRIPTION_LABEL').' -',
				'filter_short_description',
				JHtml::_('select.options', $this->short_descriptionOptions, 'value', 'text', $this->state->get('filter.short_description'))
			);

			if ($this->canBatch && $this->canCreate && $this->canEdit)
			{
				// [Interpretation 11293] Short Description Batch Selection
				JHtmlBatch_::addListSelection(
					'- Keep Original '.JText::_('COM_PRO_CRITICAL_HTML_TASK_SHORT_DESCRIPTION_LABEL').' -',
					'batch[short_description]',
					JHtml::_('select.options', $this->short_descriptionOptions, 'value', 'text')
				);
			}
		}

		// [Interpretation 11230] Set Id Component Copmonent Name Selection
		$this->id_componentCopmonent_nameOptions = JFormHelper::loadFieldType('Componentnamecomhtml')->options;
		// [Interpretation 11232] We do some sanitation for Id Component Copmonent Name filter
		if (Pro_criticalHelper::checkArray($this->id_componentCopmonent_nameOptions) &&
			isset($this->id_componentCopmonent_nameOptions[0]->value) &&
			!Pro_criticalHelper::checkString($this->id_componentCopmonent_nameOptions[0]->value))
		{
			unset($this->id_componentCopmonent_nameOptions[0]);
		}
		// [Interpretation 11239] Only load Id Component Copmonent Name filter if it has values
		if (Pro_criticalHelper::checkArray($this->id_componentCopmonent_nameOptions))
		{
			// [Interpretation 11242] Id Component Copmonent Name Filter
			JHtmlSidebar::addFilter(
				'- Select '.JText::_('COM_PRO_CRITICAL_HTML_TASK_ID_COMPONENT_LABEL').' -',
				'filter_id_component',
				JHtml::_('select.options', $this->id_componentCopmonent_nameOptions, 'value', 'text', $this->state->get('filter.id_component'))
			);

			if ($this->canBatch && $this->canCreate && $this->canEdit)
			{
				// [Interpretation 11251] Id Component Copmonent Name Batch Selection
				JHtmlBatch_::addListSelection(
					'- Keep Original '.JText::_('COM_PRO_CRITICAL_HTML_TASK_ID_COMPONENT_LABEL').' -',
					'batch[id_component]',
					JHtml::_('select.options', $this->id_componentCopmonent_nameOptions, 'value', 'text')
				);
			}
		}

		// [Interpretation 11230] Set Component View Id Selection
		$this->component_view_idOptions = JFormHelper::loadFieldType('Componentviewid')->options;
		// [Interpretation 11232] We do some sanitation for Component View Id filter
		if (Pro_criticalHelper::checkArray($this->component_view_idOptions) &&
			isset($this->component_view_idOptions[0]->value) &&
			!Pro_criticalHelper::checkString($this->component_view_idOptions[0]->value))
		{
			unset($this->component_view_idOptions[0]);
		}
		// [Interpretation 11239] Only load Component View Id filter if it has values
		if (Pro_criticalHelper::checkArray($this->component_view_idOptions))
		{
			// [Interpretation 11242] Component View Id Filter
			JHtmlSidebar::addFilter(
				'- Select '.JText::_('COM_PRO_CRITICAL_HTML_TASK_COMPONENT_VIEW_ID_LABEL').' -',
				'filter_component_view_id',
				JHtml::_('select.options', $this->component_view_idOptions, 'value', 'text', $this->state->get('filter.component_view_id'))
			);

			if ($this->canBatch && $this->canCreate && $this->canEdit)
			{
				// [Interpretation 11251] Component View Id Batch Selection
				JHtmlBatch_::addListSelection(
					'- Keep Original '.JText::_('COM_PRO_CRITICAL_HTML_TASK_COMPONENT_VIEW_ID_LABEL').' -',
					'batch[component_view_id]',
					JHtml::_('select.options', $this->component_view_idOptions, 'value', 'text')
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
			'h.' => JText::_('COM_PRO_CRITICAL_HTML_TASK_COMPONENT_VIEW_ID_LABEL'),
			'a.id' => JText::_('JGRID_HEADING_ID')
		);
	}

	protected function getTheTask_idSelections()
	{
		// [Interpretation 11103] Get a db connection.
		$db = JFactory::getDbo();

		// [Interpretation 11105] Create a new query object.
		$query = $db->getQuery(true);

		// [Interpretation 11124] Select the text.
		$query->select($db->quoteName('task_id'));
		$query->from($db->quoteName('#__pro_critical_html_task'));
		$query->order($db->quoteName('task_id') . ' ASC');

		// [Interpretation 11128] Reset the query using our newly populated query object.
		$db->setQuery($query);

		$results = $db->loadColumn();

		if ($results)
		{
			$results = array_unique($results);
			$_filter = array();
			foreach ($results as $task_id)
			{
				// [Interpretation 11172] Now add the task_id and its text to the options array
				$_filter[] = JHtml::_('select.option', $task_id, $task_id);
			}
			return $_filter;
		}
		return false;
	}

	protected function getTheShort_descriptionSelections()
	{
		// [Interpretation 11103] Get a db connection.
		$db = JFactory::getDbo();

		// [Interpretation 11105] Create a new query object.
		$query = $db->getQuery(true);

		// [Interpretation 11124] Select the text.
		$query->select($db->quoteName('short_description'));
		$query->from($db->quoteName('#__pro_critical_html_task'));
		$query->order($db->quoteName('short_description') . ' ASC');

		// [Interpretation 11128] Reset the query using our newly populated query object.
		$db->setQuery($query);

		$results = $db->loadColumn();

		if ($results)
		{
			$results = array_unique($results);
			$_filter = array();
			foreach ($results as $short_description)
			{
				// [Interpretation 11172] Now add the short_description and its text to the options array
				$_filter[] = JHtml::_('select.option', $short_description, $short_description);
			}
			return $_filter;
		}
		return false;
	}
}
