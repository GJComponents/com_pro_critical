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
 * Pro_critical View class for the Css_style_list
 */
class Pro_criticalViewCss_style_list extends JViewLegacy
{
    /**
     * @var mixed
     * @since version
     */
    protected  $pagination;
    /**
     * @var mixed
     * @since version
     */
    protected  $items;
    /**
     * @var mixed
     * @since version
     */
    protected  $state;

    /**
     * Css_style_list view display method
     * @param null $tpl
     * @return Exception|mixed|void
     * @throws Exception
     * @since 3.9
     * @auhtor Gartes | sad.net79@gmail.com | Skype : agroparknew | Telegram : @gartes
     * @date 25.08.2020 17:24
     *
     */
	function display($tpl = null)
	{
		if ($this->getLayout() !== 'modal')
		{
			// Include helper submenu
			 Pro_criticalHelper::addSubmenu('css_style_list');
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
		$this->canDo = Pro_criticalHelper::getActions('css_style');
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
     * @since 3.9
	 */
	protected function addToolBar()
	{
		JToolBarHelper::title(JText::_('COM_PRO_CRITICAL_CSS_STYLE_LIST'), 'joomla');
		JHtmlSidebar::setAction('index.php?option=com_pro_critical&view=css_style_list');
		JFormHelper::addFieldPath(JPATH_COMPONENT . '/models/fields');

		if ($this->canCreate)
		{
			JToolBarHelper::addNew('css_style.add');
		}

		// Only load if there are items
		if (Pro_criticalHelper::checkArray($this->items))
		{
			if ($this->canEdit)
			{
				JToolBarHelper::editList('css_style.edit');
			}

			if ($this->canState)
			{
				JToolBarHelper::publishList('css_style_list.publish');
				JToolBarHelper::unpublishList('css_style_list.unpublish');
				JToolBarHelper::archiveList('css_style_list.archive');

				if ($this->canDo->get('core.admin'))
				{
					JToolBarHelper::checkin('css_style_list.checkin');
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
				JToolbarHelper::deleteList('', 'css_style_list.delete', 'JTOOLBAR_EMPTY_TRASH');
			}
			elseif ($this->canState && $this->canDelete)
			{
				JToolbarHelper::trash('css_style_list.trash');
			}
		}

		// set help url for this view if found
		$help_url = Pro_criticalHelper::getHelpUrl('css_style_list');
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

		// [Interpretation 17054] Set Load Selection
		$this->loadOptions = $this->getTheLoadSelections();
		// [Interpretation 17059] We do some sanitation for Load filter
		if (Pro_criticalHelper::checkArray($this->loadOptions) &&
			isset($this->loadOptions[0]->value) &&
			!Pro_criticalHelper::checkString($this->loadOptions[0]->value))
		{
			unset($this->loadOptions[0]);
		}
		// [Interpretation 17075] Only load Load filter if it has values
		if (Pro_criticalHelper::checkArray($this->loadOptions))
		{
			// [Interpretation 17083] Load Filter
			JHtmlSidebar::addFilter(
				'- Select '.JText::_('COM_PRO_CRITICAL_CSS_STYLE_LOAD_LABEL').' -',
				'filter_load',
				JHtml::_('select.options', $this->loadOptions, 'value', 'text', $this->state->get('filter.load'))
			);

			if ($this->canBatch && $this->canCreate && $this->canEdit)
			{
				// [Interpretation 17101] Load Batch Selection
				JHtmlBatch_::addListSelection(
					'- Keep Original '.JText::_('COM_PRO_CRITICAL_CSS_STYLE_LOAD_LABEL').' -',
					'batch[load]',
					JHtml::_('select.options', $this->loadOptions, 'value', 'text')
				);
			}
		}

		// [Interpretation 17054] Set Minify Selection
		$this->minifyOptions = $this->getTheMinifySelections();
		// [Interpretation 17059] We do some sanitation for Minify filter
		if (Pro_criticalHelper::checkArray($this->minifyOptions) &&
			isset($this->minifyOptions[0]->value) &&
			!Pro_criticalHelper::checkString($this->minifyOptions[0]->value))
		{
			unset($this->minifyOptions[0]);
		}
		// [Interpretation 17075] Only load Minify filter if it has values
		if (Pro_criticalHelper::checkArray($this->minifyOptions))
		{
			// [Interpretation 17083] Minify Filter
			JHtmlSidebar::addFilter(
				'- Select '.JText::_('COM_PRO_CRITICAL_CSS_STYLE_MINIFY_LABEL').' -',
				'filter_minify',
				JHtml::_('select.options', $this->minifyOptions, 'value', 'text', $this->state->get('filter.minify'))
			);

			if ($this->canBatch && $this->canCreate && $this->canEdit)
			{
				// [Interpretation 17101] Minify Batch Selection
				JHtmlBatch_::addListSelection(
					'- Keep Original '.JText::_('COM_PRO_CRITICAL_CSS_STYLE_MINIFY_LABEL').' -',
					'batch[minify]',
					JHtml::_('select.options', $this->minifyOptions, 'value', 'text')
				);
			}
		}
	}

	/**
     * Method настройки свойств документа
	 * Method to set up the document properties
	 *
	 * @return void
     * @since 3.9
	 */
	protected function setDocument()
	{
		if (!isset($this->document))
		{
			$this->document = JFactory::getDocument();
		}
		$this->document->setTitle(JText::_('COM_PRO_CRITICAL_CSS_STYLE_LIST'));
		$this->document->addStyleSheet(JURI::root() . "administrator/components/com_pro_critical/assets/css/css_style_list.css", (Pro_criticalHelper::jVersion()->isCompatible('3.8.0')) ? array('version' => 'auto') : 'text/css');
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
			'a.load' => JText::_('COM_PRO_CRITICAL_CSS_STYLE_LOAD_LABEL'),
			'a.minify' => JText::_('COM_PRO_CRITICAL_CSS_STYLE_MINIFY_LABEL'),
			'a.id' => JText::_('JGRID_HEADING_ID')
		);
	}

	protected function getTheLoadSelections()
	{
		// [Interpretation 16761] Get a db connection.
		$db = JFactory::getDbo();

		// [Interpretation 16765] Create a new query object.
		$query = $db->getQuery(true);

		// [Interpretation 16801] Select the text.
		$query->select($db->quoteName('load'));
		$query->from($db->quoteName('#__pro_critical_css_style'));
		$query->order($db->quoteName('load') . ' ASC');

		// [Interpretation 16812] Reset the query using our newly populated query object.
		$db->setQuery($query);

		$results = $db->loadColumn();

		if ($results)
		{
			// [Interpretation 16826] get model
			$model = $this->getModel();
			$results = array_unique($results);
			$_filter = array();
			foreach ($results as $load)
			{
				// [Interpretation 16847] Translate the load selection
				$text = $model->selectionTranslation($load,'load');
				// [Interpretation 16854] Now add the load and its text to the options array
				$_filter[] = JHtml::_('select.option', $load, JText::_($text));
			}
			return $_filter;
		}
		return false;
	}

	protected function getTheMinifySelections()
	{
		// [Interpretation 16761] Get a db connection.
		$db = JFactory::getDbo();

		// [Interpretation 16765] Create a new query object.
		$query = $db->getQuery(true);

		// [Interpretation 16801] Select the text.
		$query->select($db->quoteName('minify'));
		$query->from($db->quoteName('#__pro_critical_css_style'));
		$query->order($db->quoteName('minify') . ' ASC');

		// [Interpretation 16812] Reset the query using our newly populated query object.
		$db->setQuery($query);

		$results = $db->loadColumn();

		if ($results)
		{
			// [Interpretation 16826] get model
			$model = $this->getModel();
			$results = array_unique($results);
			$_filter = array();
			foreach ($results as $minify)
			{
				// [Interpretation 16847] Translate the minify selection
				$text = $model->selectionTranslation($minify,'minify');
				// [Interpretation 16854] Now add the minify and its text to the options array
				$_filter[] = JHtml::_('select.option', $minify, JText::_($text));
			}
			return $_filter;
		}
		return false;
	}
}
