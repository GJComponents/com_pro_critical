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

		// [Interpretation 16971] Set Pro Critical Url Id Url Page Selection
		$this->pro_critical_url_idUrl_pageOptions = JFormHelper::loadFieldType('Subjectsprocriticalurlid')->options;
		// [Interpretation 16977] We do some sanitation for Pro Critical Url Id Url Page filter
		if (Pro_criticalHelper::checkArray($this->pro_critical_url_idUrl_pageOptions) &&
			isset($this->pro_critical_url_idUrl_pageOptions[0]->value) &&
			!Pro_criticalHelper::checkString($this->pro_critical_url_idUrl_pageOptions[0]->value))
		{
			unset($this->pro_critical_url_idUrl_pageOptions[0]);
		}
		// [Interpretation 16993] Only load Pro Critical Url Id Url Page filter if it has values
		if (Pro_criticalHelper::checkArray($this->pro_critical_url_idUrl_pageOptions))
		{
			// [Interpretation 17001] Pro Critical Url Id Url Page Filter
			JHtmlSidebar::addFilter(
				'- Select '.JText::_('COM_PRO_CRITICAL_CSS_PRO_CRITICAL_URL_ID_LABEL').' -',
				'filter_pro_critical_url_id',
				JHtml::_('select.options', $this->pro_critical_url_idUrl_pageOptions, 'value', 'text', $this->state->get('filter.pro_critical_url_id'))
			);

			if ($this->canBatch && $this->canCreate && $this->canEdit)
			{
				// [Interpretation 17018] Pro Critical Url Id Url Page Batch Selection
				JHtmlBatch_::addListSelection(
					'- Keep Original '.JText::_('COM_PRO_CRITICAL_CSS_PRO_CRITICAL_URL_ID_LABEL').' -',
					'batch[pro_critical_url_id]',
					JHtml::_('select.options', $this->pro_critical_url_idUrl_pageOptions, 'value', 'text')
				);
			}
		}

		// [Interpretation 16971] Set Option Copmonent Name Selection
		$this->optionCopmonent_nameOptions = JFormHelper::loadFieldType('Criticalcssoption')->options;
		// [Interpretation 16977] We do some sanitation for Option Copmonent Name filter
		if (Pro_criticalHelper::checkArray($this->optionCopmonent_nameOptions) &&
			isset($this->optionCopmonent_nameOptions[0]->value) &&
			!Pro_criticalHelper::checkString($this->optionCopmonent_nameOptions[0]->value))
		{
			unset($this->optionCopmonent_nameOptions[0]);
		}
		// [Interpretation 16993] Only load Option Copmonent Name filter if it has values
		if (Pro_criticalHelper::checkArray($this->optionCopmonent_nameOptions))
		{
			// [Interpretation 17001] Option Copmonent Name Filter
			JHtmlSidebar::addFilter(
				'- Select '.JText::_('COM_PRO_CRITICAL_CSS_OPTION_LABEL').' -',
				'filter_option',
				JHtml::_('select.options', $this->optionCopmonent_nameOptions, 'value', 'text', $this->state->get('filter.option'))
			);

			if ($this->canBatch && $this->canCreate && $this->canEdit)
			{
				// [Interpretation 17018] Option Copmonent Name Batch Selection
				JHtmlBatch_::addListSelection(
					'- Keep Original '.JText::_('COM_PRO_CRITICAL_CSS_OPTION_LABEL').' -',
					'batch[option]',
					JHtml::_('select.options', $this->optionCopmonent_nameOptions, 'value', 'text')
				);
			}
		}

		// [Interpretation 16971] Set View View Component Selection
		$this->viewView_componentOptions = JFormHelper::loadFieldType('Criticalcssview')->options;
		// [Interpretation 16977] We do some sanitation for View View Component filter
		if (Pro_criticalHelper::checkArray($this->viewView_componentOptions) &&
			isset($this->viewView_componentOptions[0]->value) &&
			!Pro_criticalHelper::checkString($this->viewView_componentOptions[0]->value))
		{
			unset($this->viewView_componentOptions[0]);
		}
		// [Interpretation 16993] Only load View View Component filter if it has values
		if (Pro_criticalHelper::checkArray($this->viewView_componentOptions))
		{
			// [Interpretation 17001] View View Component Filter
			JHtmlSidebar::addFilter(
				'- Select '.JText::_('COM_PRO_CRITICAL_CSS_VIEW_LABEL').' -',
				'filter_view',
				JHtml::_('select.options', $this->viewView_componentOptions, 'value', 'text', $this->state->get('filter.view'))
			);

			if ($this->canBatch && $this->canCreate && $this->canEdit)
			{
				// [Interpretation 17018] View View Component Batch Selection
				JHtmlBatch_::addListSelection(
					'- Keep Original '.JText::_('COM_PRO_CRITICAL_CSS_VIEW_LABEL').' -',
					'batch[view]',
					JHtml::_('select.options', $this->viewView_componentOptions, 'value', 'text')
				);
			}
		}

		// [Interpretation 16971] Set Type Device Id Type Device Selection
		$this->type_device_idType_deviceOptions = JFormHelper::loadFieldType('Typedeviceid')->options;
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
				'- Select '.JText::_('COM_PRO_CRITICAL_CSS_TYPE_DEVICE_ID_LABEL').' -',
				'filter_type_device_id',
				JHtml::_('select.options', $this->type_device_idType_deviceOptions, 'value', 'text', $this->state->get('filter.type_device_id'))
			);

			if ($this->canBatch && $this->canCreate && $this->canEdit)
			{
				// [Interpretation 17018] Type Device Id Type Device Batch Selection
				JHtmlBatch_::addListSelection(
					'- Keep Original '.JText::_('COM_PRO_CRITICAL_CSS_TYPE_DEVICE_ID_LABEL').' -',
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
			'a.id' => JText::_('JGRID_HEADING_ID')
		);
	}
}
