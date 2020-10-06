<?php
/*----------------------------------------------------------------------------------|  www.vdm.io  |----/
				Gartes 
/-------------------------------------------------------------------------------------------------------/

	@version		1.x.x
	@build			23rd августа, 2020
	@created		5th мая, 2019
	@package		proCritical
	@subpackage		controller.php
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
 * General Controller of Pro_critical component
 */
class Pro_criticalController extends JControllerLegacy
{
	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 * Recognized key values include 'name', 'default_task', 'model_path', and
	 * 'view_path' (this list is not meant to be comprehensive).
	 *
	 * @since   3.0
	 */
	public function __construct($config = array())
	{
		// set the default view
		$config['default_view'] = 'pro_critical';

		parent::__construct($config);
	}

    /**
     * Display task
     * @param false $cachable
     * @param false $urlparams
     * @return false|JControllerLegacy|Pro_criticalController
     * @since 3.9
     * @auhtor Gartes | sad.net79@gmail.com | Skype : agroparknew | Telegram : @gartes
     * @date 25.08.2020 17:21
     *
     */
	function display($cachable = false, $urlparams = false)
	{
		// set default view if not set
		$view   = $this->input->getCmd('view', 'pro_critical');
		$data	= $this->getViewRelation($view);
		$layout	= $this->input->get('layout', null, 'WORD');
		$id    	= $this->input->getInt('id');





		// Check for edit form.
		if(Pro_criticalHelper::checkArray($data))
		{
			if ($data['edit'] && $layout == 'edit' && !$this->checkEditId('com_pro_critical.edit.'.$data['view'], $id))
			{
				// Somehow the person just went to the form - we don't allow that.
				$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id));
				$this->setMessage($this->getError(), 'error');
				// check if item was opend from other then its own list view
				$ref 	= $this->input->getCmd('ref', 0);
				$refid 	= $this->input->getInt('refid', 0);
				// set redirect
				if ($refid > 0 && Pro_criticalHelper::checkString($ref))
				{
					// redirect to item of ref
					$this->setRedirect(JRoute::_('index.php?option=com_pro_critical&view='.(string)$ref.'&layout=edit&id='.(int)$refid, false));
				}
				elseif (Pro_criticalHelper::checkString($ref))
				{

					// redirect to ref
					$this->setRedirect(JRoute::_('index.php?option=com_pro_critical&view='.(string)$ref, false));
				}
				else
				{
					// normal redirect back to the list view
					$this->setRedirect(JRoute::_('index.php?option=com_pro_critical&view='.$data['views'], false));
				}

				return false;
			}
		}

		return parent::display($cachable, $urlparams);
	}

	protected function getViewRelation($view)
	{
		// check the we have a value
		if (Pro_criticalHelper::checkString($view))
		{
			// the view relationships
			$views = array(
				'url' => 'url_list',
				'css' => 'css_list',
				'user_agent' => 'user_agent_list',
				'user_agent_os' => 'user_agent_os_list',
				'user_agent_browser' => 'user_agent_browser_list',
				'device_client' => 'device_client_list',
				'admins' => 'admins_list',
				'html_task' => 'html_task_list',
				'type_device' => 'type_device_list',
				'type_browser' => 'type_browser_list',
				'directory_components' => 'directory_components_list',
				'directory_views' => 'directory_views_list',
				'css_file' => 'css_file_list',
				'css_style' => 'css_style_list'
					);
			// check if this is a list view
			if (in_array($view, $views))
			{
				// this is a list view
				return array('edit' => false, 'view' => array_search($view,$views), 'views' => $view);
			}
			// check if it is an edit view
			elseif (array_key_exists($view, $views))
			{
				// this is a edit view
				return array('edit' => true, 'view' => $view, 'views' => $views[$view]);
			}
		}
		return false;
	}
}
