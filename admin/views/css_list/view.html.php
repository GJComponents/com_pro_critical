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
 * Pro_critical View class for the Css_list
 */
class Pro_criticalViewCss_list extends JViewLegacy
{
	/**
	 * Css_list view display method
	 * @return void
	 */
	function display($tpl = null)
	{
		if ($this->getLayout() !== 'modal')
		{
			// Include helper submenu
			Pro_criticalHelper::addSubmenu('css_list');
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
		$this->canDo = Pro_criticalHelper::getActions('css');
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
		JToolBarHelper::title(JText::_('COM_PRO_CRITICAL_CSS_LIST'), 'joomla');
		JHtmlSidebar::setAction('index.php?option=com_pro_critical&view=css_list');
		JFormHelper::addFieldPath(JPATH_COMPONENT . '/models/fields');

		if ($this->canCreate)
		{
			JToolBarHelper::addNew('css.add');
		}

		// Only load if there are items
		if (Pro_criticalHelper::checkArray($this->items))
		{
			if ($this->canEdit)
			{
				JToolBarHelper::editList('css.edit');
			}

			if ($this->canState)
			{
				JToolBarHelper::publishList('css_list.publish');
				JToolBarHelper::unpublishList('css_list.unpublish');
				JToolBarHelper::archiveList('css_list.archive');

				if ($this->canDo->get('core.admin'))
				{
					JToolBarHelper::checkin('css_list.checkin');
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
				JToolbarHelper::deleteList('', 'css_list.delete', 'JTOOLBAR_EMPTY_TRASH');
			}
			elseif ($this->canState && $this->canDelete)
			{
				JToolbarHelper::trash('css_list.trash');
			}
		}
		if ($this->user->authorise('css.delete_all_records', 'com_pro_critical'))
		{
			// [Interpretation 3712] add Delete all records button.
			JToolBarHelper::custom('css_list.OnBtnCleanTable', 'delete', '', 'COM_PRO_CRITICAL_DELETE_ALL_RECORDS', false);
		}

		// set help url for this view if found
		$help_url = Pro_criticalHelper::getHelpUrl('css_list');
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

		// [Interpretation 11272] Set Query String Selection
		$this->query_stringOptions = $this->getTheQuery_stringSelections();
		// [Interpretation 11274] We do some sanitation for Query String filter
		if (Pro_criticalHelper::checkArray($this->query_stringOptions) &&
			isset($this->query_stringOptions[0]->value) &&
			!Pro_criticalHelper::checkString($this->query_stringOptions[0]->value))
		{
			unset($this->query_stringOptions[0]);
		}
		// [Interpretation 11281] Only load Query String filter if it has values
		if (Pro_criticalHelper::checkArray($this->query_stringOptions))
		{
			// [Interpretation 11284] Query String Filter
			JHtmlSidebar::addFilter(
				'- Select '.JText::_('COM_PRO_CRITICAL_CSS_QUERY_STRING_LABEL').' -',
				'filter_query_string',
				JHtml::_('select.options', $this->query_stringOptions, 'value', 'text', $this->state->get('filter.query_string'))
			);

			if ($this->canBatch && $this->canCreate && $this->canEdit)
			{
				// [Interpretation 11293] Query String Batch Selection
				JHtmlBatch_::addListSelection(
					'- Keep Original '.JText::_('COM_PRO_CRITICAL_CSS_QUERY_STRING_LABEL').' -',
					'batch[query_string]',
					JHtml::_('select.options', $this->query_stringOptions, 'value', 'text')
				);
			}
		}

		// [Interpretation 11230] Set Option Copmonent Name Selection
		$this->optionCopmonent_nameOptions = JFormHelper::loadFieldType('Criticalcssoption')->options;
		// [Interpretation 11232] We do some sanitation for Option Copmonent Name filter
		if (Pro_criticalHelper::checkArray($this->optionCopmonent_nameOptions) &&
			isset($this->optionCopmonent_nameOptions[0]->value) &&
			!Pro_criticalHelper::checkString($this->optionCopmonent_nameOptions[0]->value))
		{
			unset($this->optionCopmonent_nameOptions[0]);
		}
		// [Interpretation 11239] Only load Option Copmonent Name filter if it has values
		if (Pro_criticalHelper::checkArray($this->optionCopmonent_nameOptions))
		{
			// [Interpretation 11242] Option Copmonent Name Filter
			JHtmlSidebar::addFilter(
				'- Select '.JText::_('COM_PRO_CRITICAL_CSS_OPTION_LABEL').' -',
				'filter_option',
				JHtml::_('select.options', $this->optionCopmonent_nameOptions, 'value', 'text', $this->state->get('filter.option'))
			);

			if ($this->canBatch && $this->canCreate && $this->canEdit)
			{
				// [Interpretation 11251] Option Copmonent Name Batch Selection
				JHtmlBatch_::addListSelection(
					'- Keep Original '.JText::_('COM_PRO_CRITICAL_CSS_OPTION_LABEL').' -',
					'batch[option]',
					JHtml::_('select.options', $this->optionCopmonent_nameOptions, 'value', 'text')
				);
			}
		}

		// [Interpretation 11230] Set View View Component Selection
		$this->viewView_componentOptions = JFormHelper::loadFieldType('Criticalcssview')->options;
		// [Interpretation 11232] We do some sanitation for View View Component filter
		if (Pro_criticalHelper::checkArray($this->viewView_componentOptions) &&
			isset($this->viewView_componentOptions[0]->value) &&
			!Pro_criticalHelper::checkString($this->viewView_componentOptions[0]->value))
		{
			unset($this->viewView_componentOptions[0]);
		}
		// [Interpretation 11239] Only load View View Component filter if it has values
		if (Pro_criticalHelper::checkArray($this->viewView_componentOptions))
		{
			// [Interpretation 11242] View View Component Filter
			JHtmlSidebar::addFilter(
				'- Select '.JText::_('COM_PRO_CRITICAL_CSS_VIEW_LABEL').' -',
				'filter_view',
				JHtml::_('select.options', $this->viewView_componentOptions, 'value', 'text', $this->state->get('filter.view'))
			);

			if ($this->canBatch && $this->canCreate && $this->canEdit)
			{
				// [Interpretation 11251] View View Component Batch Selection
				JHtmlBatch_::addListSelection(
					'- Keep Original '.JText::_('COM_PRO_CRITICAL_CSS_VIEW_LABEL').' -',
					'batch[view]',
					JHtml::_('select.options', $this->viewView_componentOptions, 'value', 'text')
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
		$this->document->setTitle(JText::_('COM_PRO_CRITICAL_CSS_LIST'));
		$this->document->addStyleSheet(JURI::root() . "administrator/components/com_pro_critical/assets/css/css_list.css", (Pro_criticalHelper::jVersion()->isCompatible('3.8.0')) ? array('version' => 'auto') : 'text/css');
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
			'a.query_string' => JText::_('COM_PRO_CRITICAL_CSS_QUERY_STRING_LABEL'),
			'a.id' => JText::_('JGRID_HEADING_ID')
		);
	}

	protected function getTheQuery_stringSelections()
	{
		// [Interpretation 11103] Get a db connection.
		$db = JFactory::getDbo();

		// [Interpretation 11105] Create a new query object.
		$query = $db->getQuery(true);

		// [Interpretation 11124] Select the text.
		$query->select($db->quoteName('query_string'));
		$query->from($db->quoteName('#__pro_critical_css'));
		$query->order($db->quoteName('query_string') . ' ASC');

		// [Interpretation 11128] Reset the query using our newly populated query object.
		$db->setQuery($query);

		$results = $db->loadColumn();

		if ($results)
		{
			$results = array_unique($results);
			$_filter = array();
			foreach ($results as $query_string)
			{
				// [Interpretation 11172] Now add the query_string and its text to the options array
				$_filter[] = JHtml::_('select.option', $query_string, $query_string);
			}
			return $_filter;
		}
		return false;
	}
}
