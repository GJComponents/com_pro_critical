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
 * Pro_critical View class for the Directory_views_list
 */
class Pro_criticalViewDirectory_views_list extends JViewLegacy
{
	/**
	 * Directory_views_list view display method
	 * @return void
	 */
	function display($tpl = null)
	{
		if ($this->getLayout() !== 'modal')
		{
			// Include helper submenu
			Pro_criticalHelper::addSubmenu('directory_views_list');
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
		$this->canDo = Pro_criticalHelper::getActions('directory_views');
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
		JToolBarHelper::title(JText::_('COM_PRO_CRITICAL_DIRECTORY_VIEWS_LIST'), 'joomla');
		JHtmlSidebar::setAction('index.php?option=com_pro_critical&view=directory_views_list');
		JFormHelper::addFieldPath(JPATH_COMPONENT . '/models/fields');

		if ($this->canCreate)
		{
			JToolBarHelper::addNew('directory_views.add');
		}

		// Only load if there are items
		if (Pro_criticalHelper::checkArray($this->items))
		{
			if ($this->canEdit)
			{
				JToolBarHelper::editList('directory_views.edit');
			}

			if ($this->canState)
			{
				JToolBarHelper::publishList('directory_views_list.publish');
				JToolBarHelper::unpublishList('directory_views_list.unpublish');
				JToolBarHelper::archiveList('directory_views_list.archive');

				if ($this->canDo->get('core.admin'))
				{
					JToolBarHelper::checkin('directory_views_list.checkin');
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
				JToolbarHelper::deleteList('', 'directory_views_list.delete', 'JTOOLBAR_EMPTY_TRASH');
			}
			elseif ($this->canState && $this->canDelete)
			{
				JToolbarHelper::trash('directory_views_list.trash');
			}
		}

		// set help url for this view if found
		$help_url = Pro_criticalHelper::getHelpUrl('directory_views_list');
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

		// [Interpretation 17054] Set View Component Selection
		$this->view_componentOptions = $this->getTheView_componentSelections();
		// [Interpretation 17059] We do some sanitation for View Component filter
		if (Pro_criticalHelper::checkArray($this->view_componentOptions) &&
			isset($this->view_componentOptions[0]->value) &&
			!Pro_criticalHelper::checkString($this->view_componentOptions[0]->value))
		{
			unset($this->view_componentOptions[0]);
		}
		// [Interpretation 17075] Only load View Component filter if it has values
		if (Pro_criticalHelper::checkArray($this->view_componentOptions))
		{
			// [Interpretation 17083] View Component Filter
			JHtmlSidebar::addFilter(
				'- Select '.JText::_('COM_PRO_CRITICAL_DIRECTORY_VIEWS_VIEW_COMPONENT_LABEL').' -',
				'filter_view_component',
				JHtml::_('select.options', $this->view_componentOptions, 'value', 'text', $this->state->get('filter.view_component'))
			);

			if ($this->canBatch && $this->canCreate && $this->canEdit)
			{
				// [Interpretation 17101] View Component Batch Selection
				JHtmlBatch_::addListSelection(
					'- Keep Original '.JText::_('COM_PRO_CRITICAL_DIRECTORY_VIEWS_VIEW_COMPONENT_LABEL').' -',
					'batch[view_component]',
					JHtml::_('select.options', $this->view_componentOptions, 'value', 'text')
				);
			}
		}

		// [Interpretation 16971] Set Id Component Copmonent Name Selection
		$this->id_componentCopmonent_nameOptions = JFormHelper::loadFieldType('Componentnamecom')->options;
		// [Interpretation 16977] We do some sanitation for Id Component Copmonent Name filter
		if (Pro_criticalHelper::checkArray($this->id_componentCopmonent_nameOptions) &&
			isset($this->id_componentCopmonent_nameOptions[0]->value) &&
			!Pro_criticalHelper::checkString($this->id_componentCopmonent_nameOptions[0]->value))
		{
			unset($this->id_componentCopmonent_nameOptions[0]);
		}
		// [Interpretation 16993] Only load Id Component Copmonent Name filter if it has values
		if (Pro_criticalHelper::checkArray($this->id_componentCopmonent_nameOptions))
		{
			// [Interpretation 17001] Id Component Copmonent Name Filter
			JHtmlSidebar::addFilter(
				'- Select '.JText::_('COM_PRO_CRITICAL_DIRECTORY_VIEWS_ID_COMPONENT_LABEL').' -',
				'filter_id_component',
				JHtml::_('select.options', $this->id_componentCopmonent_nameOptions, 'value', 'text', $this->state->get('filter.id_component'))
			);

			if ($this->canBatch && $this->canCreate && $this->canEdit)
			{
				// [Interpretation 17018] Id Component Copmonent Name Batch Selection
				JHtmlBatch_::addListSelection(
					'- Keep Original '.JText::_('COM_PRO_CRITICAL_DIRECTORY_VIEWS_ID_COMPONENT_LABEL').' -',
					'batch[id_component]',
					JHtml::_('select.options', $this->id_componentCopmonent_nameOptions, 'value', 'text')
				);
			}
		}

		// [Interpretation 17054] Set Value View Selection
		$this->value_viewOptions = $this->getTheValue_viewSelections();
		// [Interpretation 17059] We do some sanitation for Value View filter
		if (Pro_criticalHelper::checkArray($this->value_viewOptions) &&
			isset($this->value_viewOptions[0]->value) &&
			!Pro_criticalHelper::checkString($this->value_viewOptions[0]->value))
		{
			unset($this->value_viewOptions[0]);
		}
		// [Interpretation 17075] Only load Value View filter if it has values
		if (Pro_criticalHelper::checkArray($this->value_viewOptions))
		{
			// [Interpretation 17083] Value View Filter
			JHtmlSidebar::addFilter(
				'- Select '.JText::_('COM_PRO_CRITICAL_DIRECTORY_VIEWS_VALUE_VIEW_LABEL').' -',
				'filter_value_view',
				JHtml::_('select.options', $this->value_viewOptions, 'value', 'text', $this->state->get('filter.value_view'))
			);

			if ($this->canBatch && $this->canCreate && $this->canEdit)
			{
				// [Interpretation 17101] Value View Batch Selection
				JHtmlBatch_::addListSelection(
					'- Keep Original '.JText::_('COM_PRO_CRITICAL_DIRECTORY_VIEWS_VALUE_VIEW_LABEL').' -',
					'batch[value_view]',
					JHtml::_('select.options', $this->value_viewOptions, 'value', 'text')
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
		$this->document->setTitle(JText::_('COM_PRO_CRITICAL_DIRECTORY_VIEWS_LIST'));
		$this->document->addStyleSheet(JURI::root() . "administrator/components/com_pro_critical/assets/css/directory_views_list.css", (Pro_criticalHelper::jVersion()->isCompatible('3.8.0')) ? array('version' => 'auto') : 'text/css');
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
			'a.view_component' => JText::_('COM_PRO_CRITICAL_DIRECTORY_VIEWS_VIEW_COMPONENT_LABEL'),
			'g.copmonent_name' => JText::_('COM_PRO_CRITICAL_DIRECTORY_VIEWS_ID_COMPONENT_LABEL'),
			'a.value_view' => JText::_('COM_PRO_CRITICAL_DIRECTORY_VIEWS_VALUE_VIEW_LABEL'),
			'a.id' => JText::_('JGRID_HEADING_ID')
		);
	}

	protected function getTheView_componentSelections()
	{
		// [Interpretation 16761] Get a db connection.
		$db = JFactory::getDbo();

		// [Interpretation 16765] Create a new query object.
		$query = $db->getQuery(true);

		// [Interpretation 16801] Select the text.
		$query->select($db->quoteName('view_component'));
		$query->from($db->quoteName('#__pro_critical_directory_views'));
		$query->order($db->quoteName('view_component') . ' ASC');

		// [Interpretation 16812] Reset the query using our newly populated query object.
		$db->setQuery($query);

		$results = $db->loadColumn();

		if ($results)
		{
			$results = array_unique($results);
			$_filter = array();
			foreach ($results as $view_component)
			{
				// [Interpretation 16888] Now add the view_component and its text to the options array
				$_filter[] = JHtml::_('select.option', $view_component, $view_component);
			}
			return $_filter;
		}
		return false;
	}

	protected function getTheValue_viewSelections()
	{
		// [Interpretation 16761] Get a db connection.
		$db = JFactory::getDbo();

		// [Interpretation 16765] Create a new query object.
		$query = $db->getQuery(true);

		// [Interpretation 16801] Select the text.
		$query->select($db->quoteName('value_view'));
		$query->from($db->quoteName('#__pro_critical_directory_views'));
		$query->order($db->quoteName('value_view') . ' ASC');

		// [Interpretation 16812] Reset the query using our newly populated query object.
		$db->setQuery($query);

		$results = $db->loadColumn();

		if ($results)
		{
			$results = array_unique($results);
			$_filter = array();
			foreach ($results as $value_view)
			{
				// [Interpretation 16888] Now add the value_view and its text to the options array
				$_filter[] = JHtml::_('select.option', $value_view, $value_view);
			}
			return $_filter;
		}
		return false;
	}
}
