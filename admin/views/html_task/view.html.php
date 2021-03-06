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
use GNZ11\Core\Js;

defined('_JEXEC') or die('Restricted access');

/**
 * Html_task View class
 */
class Pro_criticalViewHtml_task extends JViewLegacy
{
	/**
	 * display method of View
	 * @return void
	 */
	public function display($tpl = null)
	{
		// set params
		$this->params = JComponentHelper::getParams('com_pro_critical');
		// Assign the variables
		$this->form = $this->get('Form');
		$this->item = $this->get('Item');
		$this->script = $this->get('Script');
		$this->state = $this->get('State');
		// get action permissions
		$this->canDo = Pro_criticalHelper::getActions('html_task', $this->item);
		// get input
		$jinput = JFactory::getApplication()->input;





		$this->ref = $jinput->get('ref', 0, 'word');
		$this->refid = $jinput->get('refid', 0, 'int');
		$return = $jinput->get('return', null, 'base64');
		// set the referral string
		$this->referral = '';
		if ($this->refid && $this->ref)
		{
			// return to the item that referred to this item
			$this->referral = '&ref=' . (string)$this->ref . '&refid=' . (int)$this->refid;
		}
		elseif($this->ref)
		{
			// return to the list view that referred to this item
			$this->referral = '&ref=' . (string)$this->ref;
		}
		// check return value
		if (!is_null($return))
		{
			// add the return value
			$this->referral .= '&return=' . (string)$return;
		}

		// Set the toolbar
		$this->addToolBar();
		
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
		JFactory::getApplication()->input->set('hidemainmenu', true);
		$user = JFactory::getUser();
		$userId	= $user->id;
		$isNew = $this->item->id == 0;

		JToolbarHelper::title( JText::_($isNew ? 'COM_PRO_CRITICAL_HTML_TASK_NEW' : 'COM_PRO_CRITICAL_HTML_TASK_EDIT'), 'pencil-2 article-add');
		// [Interpretation 19149] Built the actions for new and existing records.
		if (Pro_criticalHelper::checkString($this->referral))
		{
			if ($this->canDo->get('core.create') && $isNew)
			{
				// [Interpretation 19174] We can create the record.
				JToolBarHelper::save('html_task.save', 'JTOOLBAR_SAVE');
			}
			elseif ($this->canDo->get('core.edit'))
			{
				// [Interpretation 19199] We can save the record.
				JToolBarHelper::save('html_task.save', 'JTOOLBAR_SAVE');
			}
			if ($isNew)
			{
				// [Interpretation 19206] Do not creat but cancel.
				JToolBarHelper::cancel('html_task.cancel', 'JTOOLBAR_CANCEL');
			}
			else
			{
				// [Interpretation 19213] We can close it.
				JToolBarHelper::cancel('html_task.cancel', 'JTOOLBAR_CLOSE');
			}
		}
		else
		{
			if ($isNew)
			{
				// [Interpretation 19223] For new records, check the create permission.
				if ($this->canDo->get('core.create'))
				{
					JToolBarHelper::apply('html_task.apply', 'JTOOLBAR_APPLY');
					JToolBarHelper::save('html_task.save', 'JTOOLBAR_SAVE');
					JToolBarHelper::custom('html_task.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
				};
				JToolBarHelper::cancel('html_task.cancel', 'JTOOLBAR_CANCEL');
			}
			else
			{
				if ($this->canDo->get('core.edit'))
				{
					// [Interpretation 19276] We can save the new record
					JToolBarHelper::apply('html_task.apply', 'JTOOLBAR_APPLY');
					JToolBarHelper::save('html_task.save', 'JTOOLBAR_SAVE');
					// [Interpretation 19282] We can save this record, but check the create permission to see
					// [Interpretation 19284] if we can return to make a new one.
					if ($this->canDo->get('core.create'))
					{
						JToolBarHelper::custom('html_task.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
					}
				}
				if ($this->canDo->get('core.create'))
				{
					JToolBarHelper::custom('html_task.save2copy', 'save-copy.png', 'save-copy_f2.png', 'JTOOLBAR_SAVE_AS_COPY', false);
				}
				JToolBarHelper::cancel('html_task.cancel', 'JTOOLBAR_CLOSE');
			}
		}
		JToolbarHelper::divider();

        JToolBarHelper::custom('setUpTask', 'upload', '', 'COM_PRO_CRITICAL_HTML_ADD_TASK_DATA', false);

		// [Interpretation 19389] set help url for this view if found
		$help_url = Pro_criticalHelper::getHelpUrl('html_task');
		if (Pro_criticalHelper::checkString($help_url))
		{
			JToolbarHelper::help('COM_PRO_CRITICAL_HELP_MANAGER', false, $help_url);
		}
	}

	/**
	 * Escapes a value for output in a view script.
	 *
	 * @param   mixed  $var  The output to escape.
	 *
	 * @return  mixed  The escaped value.
     * @since 3.9
	 */
	public function escape($var)
	{
		if(strlen($var) > 30)
		{
    		// use the helper htmlEscape method instead and shorten the string
			return Pro_criticalHelper::htmlEscape($var, $this->_charset, true, 30);
		}
		// use the helper htmlEscape method instead.
		return Pro_criticalHelper::htmlEscape($var, $this->_charset);
	}

	/**
	 * Method to set up the document properties
	 *
	 * @return void
     * @since 3.9
	 */
	protected function setDocument()
	{
		$isNew = ($this->item->id < 1);
		if (!isset($this->document))
		{
			$this->document = JFactory::getDocument();
		}
		$this->document->setTitle(JText::_($isNew ? 'COM_PRO_CRITICAL_HTML_TASK_NEW' : 'COM_PRO_CRITICAL_HTML_TASK_EDIT'));
		$this->document->addStyleSheet(JURI::root() . "administrator/components/com_pro_critical/assets/css/html_task.css", (Pro_criticalHelper::jVersion()->isCompatible('3.8.0')) ? array('version' => 'auto') : 'text/css');
		// [Interpretation 16433] Add Ajax Token
		$this->document->addScriptDeclaration("var token = '".JSession::getFormToken()."';");
		$this->document->addScript(JURI::root() . $this->script, (Pro_criticalHelper::jVersion()->isCompatible('3.8.0')) ? array('version' => 'auto') : 'text/javascript');
		$this->document->addScript(JURI::root() . "administrator/components/com_pro_critical/views/html_task/submitbutton.js", (Pro_criticalHelper::jVersion()->isCompatible('3.8.0')) ? array('version' => 'auto') : 'text/javascript');
##########################################
### Add PHP (custom document script) - Manager html task 
###
### /administrator/components/com_pro_critical/views/html_task/view.html.php
##########################################

        $app = \Joomla\CMS\Factory::getApplication();

        $this->document->addStyleSheet(JURI::root() . "administrator/components/com_pro_critical/assets/css/html_task.processingForm.css", (Pro_criticalHelper::jVersion()->isCompatible('3.8.0')) ? array('version' => 'auto') : 'text/css');

        $doc = \Joomla\CMS\Factory::getDocument();
        try
        {
            JLoader::registerNamespace( 'GNZ11' , JPATH_LIBRARIES . '/GNZ11' , $reset = false , $prepend = false , $type = 'psr4' );
            $GNZ11_js =  Js::instance();
        }
        catch( Exception $e )
        {
            if( !\Joomla\CMS\Filesystem\Folder::exists( $this->patchGnz11 ) && $this->app->isClient('administrator') )
            {
                $this->app->enqueueMessage('Должна быть установлена библиотека GNZ11' , 'error');
            }#END IF
            throw new \Exception('Должна быть установлена библиотека GNZ11' , 400 ) ;
        }


        $doc->addScriptOptions('siteUrl', JUri::root());
        $doc->addScriptOptions('isClient', $app->isClient('administrator'));
        Js::addJproLoad(\Joomla\CMS\Uri\Uri::root().'administrator/components/com_pro_critical/assets/js/html_task_setUpTask.js' ,  false ,  false );


        JText::script('view not acceptable. Error');
	}
}
