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
 * Pro_critical View class for the User_agent_os_list
 */
class Pro_criticalViewUser_agent_os_list extends JViewLegacy
{
	/**
	 * User_agent_os_list view display method
	 * @return void
	 */
	function display($tpl = null)
	{
		if ($this->getLayout() !== 'modal')
		{
			// Include helper submenu
			Pro_criticalHelper::addSubmenu('user_agent_os_list');
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
		$this->canDo = Pro_criticalHelper::getActions('user_agent_os');
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
		JToolBarHelper::title(JText::_('COM_PRO_CRITICAL_USER_AGENT_OS_LIST'), 'joomla');
		JHtmlSidebar::setAction('index.php?option=com_pro_critical&view=user_agent_os_list');
		JFormHelper::addFieldPath(JPATH_COMPONENT . '/models/fields');

		if ($this->canCreate)
		{
			JToolBarHelper::addNew('user_agent_os.add');
		}

		// Only load if there are items
		if (Pro_criticalHelper::checkArray($this->items))
		{
			if ($this->canEdit)
			{
				JToolBarHelper::editList('user_agent_os.edit');
			}

			if ($this->canState)
			{
				JToolBarHelper::publishList('user_agent_os_list.publish');
				JToolBarHelper::unpublishList('user_agent_os_list.unpublish');
				JToolBarHelper::archiveList('user_agent_os_list.archive');

				if ($this->canDo->get('core.admin'))
				{
					JToolBarHelper::checkin('user_agent_os_list.checkin');
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
				JToolbarHelper::deleteList('', 'user_agent_os_list.delete', 'JTOOLBAR_EMPTY_TRASH');
			}
			elseif ($this->canState && $this->canDelete)
			{
				JToolbarHelper::trash('user_agent_os_list.trash');
			}
		}

		// set help url for this view if found
		$help_url = Pro_criticalHelper::getHelpUrl('user_agent_os_list');
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

		// [Interpretation 17054] Set Name Selection
		$this->nameOptions = $this->getTheNameSelections();
		// [Interpretation 17059] We do some sanitation for Name filter
		if (Pro_criticalHelper::checkArray($this->nameOptions) &&
			isset($this->nameOptions[0]->value) &&
			!Pro_criticalHelper::checkString($this->nameOptions[0]->value))
		{
			unset($this->nameOptions[0]);
		}
		// [Interpretation 17075] Only load Name filter if it has values
		if (Pro_criticalHelper::checkArray($this->nameOptions))
		{
			// [Interpretation 17083] Name Filter
			JHtmlSidebar::addFilter(
				'- Select '.JText::_('COM_PRO_CRITICAL_USER_AGENT_OS_NAME_LABEL').' -',
				'filter_name',
				JHtml::_('select.options', $this->nameOptions, 'value', 'text', $this->state->get('filter.name'))
			);

			if ($this->canBatch && $this->canCreate && $this->canEdit)
			{
				// [Interpretation 17101] Name Batch Selection
				JHtmlBatch_::addListSelection(
					'- Keep Original '.JText::_('COM_PRO_CRITICAL_USER_AGENT_OS_NAME_LABEL').' -',
					'batch[name]',
					JHtml::_('select.options', $this->nameOptions, 'value', 'text')
				);
			}
		}

		// [Interpretation 17054] Set Code Selection
		$this->codeOptions = $this->getTheCodeSelections();
		// [Interpretation 17059] We do some sanitation for Code filter
		if (Pro_criticalHelper::checkArray($this->codeOptions) &&
			isset($this->codeOptions[0]->value) &&
			!Pro_criticalHelper::checkString($this->codeOptions[0]->value))
		{
			unset($this->codeOptions[0]);
		}
		// [Interpretation 17075] Only load Code filter if it has values
		if (Pro_criticalHelper::checkArray($this->codeOptions))
		{
			// [Interpretation 17083] Code Filter
			JHtmlSidebar::addFilter(
				'- Select '.JText::_('COM_PRO_CRITICAL_USER_AGENT_OS_CODE_LABEL').' -',
				'filter_code',
				JHtml::_('select.options', $this->codeOptions, 'value', 'text', $this->state->get('filter.code'))
			);

			if ($this->canBatch && $this->canCreate && $this->canEdit)
			{
				// [Interpretation 17101] Code Batch Selection
				JHtmlBatch_::addListSelection(
					'- Keep Original '.JText::_('COM_PRO_CRITICAL_USER_AGENT_OS_CODE_LABEL').' -',
					'batch[code]',
					JHtml::_('select.options', $this->codeOptions, 'value', 'text')
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
		$this->document->setTitle(JText::_('COM_PRO_CRITICAL_USER_AGENT_OS_LIST'));
		$this->document->addStyleSheet(JURI::root() . "administrator/components/com_pro_critical/assets/css/user_agent_os_list.css", (Pro_criticalHelper::jVersion()->isCompatible('3.8.0')) ? array('version' => 'auto') : 'text/css');
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
			'a.name' => JText::_('COM_PRO_CRITICAL_USER_AGENT_OS_NAME_LABEL'),
			'a.code' => JText::_('COM_PRO_CRITICAL_USER_AGENT_OS_CODE_LABEL'),
			'a.id' => JText::_('JGRID_HEADING_ID')
		);
	}

	protected function getTheNameSelections()
	{
		// [Interpretation 16761] Get a db connection.
		$db = JFactory::getDbo();

		// [Interpretation 16765] Create a new query object.
		$query = $db->getQuery(true);

		// [Interpretation 16801] Select the text.
		$query->select($db->quoteName('name'));
		$query->from($db->quoteName('#__pro_critical_user_agent_os'));
		$query->order($db->quoteName('name') . ' ASC');

		// [Interpretation 16812] Reset the query using our newly populated query object.
		$db->setQuery($query);

		$results = $db->loadColumn();

		if ($results)
		{
			$results = array_unique($results);
			$_filter = array();
			foreach ($results as $name)
			{
				// [Interpretation 16888] Now add the name and its text to the options array
				$_filter[] = JHtml::_('select.option', $name, $name);
			}
			return $_filter;
		}
		return false;
	}

	protected function getTheCodeSelections()
	{
		// [Interpretation 16761] Get a db connection.
		$db = JFactory::getDbo();

		// [Interpretation 16765] Create a new query object.
		$query = $db->getQuery(true);

		// [Interpretation 16801] Select the text.
		$query->select($db->quoteName('code'));
		$query->from($db->quoteName('#__pro_critical_user_agent_os'));
		$query->order($db->quoteName('code') . ' ASC');

		// [Interpretation 16812] Reset the query using our newly populated query object.
		$db->setQuery($query);

		$results = $db->loadColumn();

		if ($results)
		{
			$results = array_unique($results);
			$_filter = array();
			foreach ($results as $code)
			{
				// [Interpretation 16888] Now add the code and its text to the options array
				$_filter[] = JHtml::_('select.option', $code, $code);
			}
			return $_filter;
		}
		return false;
	}
}
