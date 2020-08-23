<?php
/*----------------------------------------------------------------------------------|  www.vdm.io  |----/
				Gartes 
/-------------------------------------------------------------------------------------------------------/

	@version		1.5.19
	@build			23rd декабря, 2019
	@created		5th мая, 2019
	@package		proCritical
	@subpackage		pro_critical.php
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
 * Pro_critical Model
 */
class Pro_criticalModelPro_critical extends JModelList
{
	public function getIcons()
	{
		// load user for access menus
		$user = JFactory::getUser();
		// reset icon array
		$icons  = array();
		// view groups array
		$viewGroups = array(
			'main' => array('png.css_list', 'png.cache_list', 'png.user_agent_list', 'png.user_agent_os_list', 'png.user_agent_browser_list', 'png.device_client_list', 'png.admins_list', 'png.html_task_list', 'png.css_file_list', 'png.css_style_list', 'png.js_file_list', 'png.js_style_list', 'jpg.url_list', 'png.help_document_data_list')
		);
		// [Interpretation 13466] view access array
		$viewAccess = array(
			'css_list.submenu' => 'css.submenu',
			'css_list.dashboard_list' => 'css.dashboard_list',
			'cache_list.dashboard_list' => 'cache.dashboard_list',
			'user_agent_list.submenu' => 'user_agent.submenu',
			'user_agent_list.dashboard_list' => 'user_agent.dashboard_list',
			'user_agent_os_list.submenu' => 'user_agent_os.submenu',
			'user_agent_os_list.dashboard_list' => 'user_agent_os.dashboard_list',
			'user_agent_browser_list.submenu' => 'user_agent_browser.submenu',
			'user_agent_browser_list.dashboard_list' => 'user_agent_browser.dashboard_list',
			'device_client_list.submenu' => 'device_client.submenu',
			'device_client_list.dashboard_list' => 'device_client.dashboard_list',
			'admins_list.submenu' => 'admins.submenu',
			'admins_list.dashboard_list' => 'admins.dashboard_list',
			'html_task_list.submenu' => 'html_task.submenu',
			'html_task_list.dashboard_list' => 'html_task.dashboard_list',
			'type_device_list.submenu' => 'type_device.submenu',
			'type_browser_list.submenu' => 'type_browser.submenu',
			'directory_components_list.submenu' => 'directory_components.submenu',
			'directory_views_list.submenu' => 'directory_views.submenu',
			'css_file_list.submenu' => 'css_file.submenu',
			'css_file_list.dashboard_list' => 'css_file.dashboard_list',
			'css_style_list.submenu' => 'css_style.submenu',
			'css_style_list.dashboard_list' => 'css_style.dashboard_list',
			'js_file_list.submenu' => 'js_file.submenu',
			'js_file_list.dashboard_list' => 'js_file.dashboard_list',
			'js_style_list.submenu' => 'js_style.submenu',
			'js_style_list.dashboard_list' => 'js_style.dashboard_list',
			'url_list.submenu' => 'url.submenu',
			'url_list.dashboard_list' => 'url.dashboard_list',
			'help_document_data_list.submenu' => 'help_document_data.submenu',
			'help_document_data_list.dashboard_list' => 'help_document_data.dashboard_list');
		// loop over the $views
		foreach($viewGroups as $group => $views)
		{
			$i = 0;
			if (Pro_criticalHelper::checkArray($views))
			{
				foreach($views as $view)
				{
					$add = false;
					// external views (links)
					if (strpos($view,'||') !== false)
					{
						$dwd = explode('||', $view);
						if (count($dwd) == 3)
						{
							list($type, $name, $url) = $dwd;
							$viewName 	= $name;
							$alt 		= $name;
							$url 		= $url;
							$image 		= $name.'.'.$type;
							$name 		= 'COM_PRO_CRITICAL_DASHBOARD_'.Pro_criticalHelper::safeString($name,'U');
						}
					}
					// internal views
					elseif (strpos($view,'.') !== false)
					{
						$dwd = explode('.', $view);
						if (count($dwd) == 3)
						{
							list($type, $name, $action) = $dwd;
						}
						elseif (count($dwd) == 2)
						{
							list($type, $name) = $dwd;
							$action = false;
						}
						if ($action)
						{
							$viewName = $name;
							switch($action)
							{
								case 'add':
									$url 	= 'index.php?option=com_pro_critical&view='.$name.'&layout=edit';
									$image 	= $name.'_'.$action.'.'.$type;
									$alt 	= $name.'&nbsp;'.$action;
									$name	= 'COM_PRO_CRITICAL_DASHBOARD_'.Pro_criticalHelper::safeString($name,'U').'_ADD';
									$add	= true;
								break;
								default:
									$url 	= 'index.php?option=com_categories&view=categories&extension=com_pro_critical.'.$name;
									$image 	= $name.'_'.$action.'.'.$type;
									$alt 	= $name.'&nbsp;'.$action;
									$name	= 'COM_PRO_CRITICAL_DASHBOARD_'.Pro_criticalHelper::safeString($name,'U').'_'.Pro_criticalHelper::safeString($action,'U');
								break;
							}
						}
						else
						{
							$viewName 	= $name;
							$alt 		= $name;
							$url 		= 'index.php?option=com_pro_critical&view='.$name;
							$image 		= $name.'.'.$type;
							$name 		= 'COM_PRO_CRITICAL_DASHBOARD_'.Pro_criticalHelper::safeString($name,'U');
							$hover		= false;
						}
					}
					else
					{
						$viewName 	= $view;
						$alt 		= $view;
						$url 		= 'index.php?option=com_pro_critical&view='.$view;
						$image 		= $view.'.png';
						$name 		= ucwords($view).'<br /><br />';
						$hover		= false;
					}
					// first make sure the view access is set
					if (Pro_criticalHelper::checkArray($viewAccess))
					{
						// setup some defaults
						$dashboard_add = false;
						$dashboard_list = false;
						$accessTo = '';
						$accessAdd = '';
						// acces checking start
						$accessCreate = (isset($viewAccess[$viewName.'.create'])) ? Pro_criticalHelper::checkString($viewAccess[$viewName.'.create']):false;
						$accessAccess = (isset($viewAccess[$viewName.'.access'])) ? Pro_criticalHelper::checkString($viewAccess[$viewName.'.access']):false;
						// set main controllers
						$accessDashboard_add = (isset($viewAccess[$viewName.'.dashboard_add'])) ? Pro_criticalHelper::checkString($viewAccess[$viewName.'.dashboard_add']):false;
						$accessDashboard_list = (isset($viewAccess[$viewName.'.dashboard_list'])) ? Pro_criticalHelper::checkString($viewAccess[$viewName.'.dashboard_list']):false;
						// check for adding access
						if ($add && $accessCreate)
						{
							$accessAdd = $viewAccess[$viewName.'.create'];
						}
						elseif ($add)
						{
							$accessAdd = 'core.create';
						}
						// check if acces to view is set
						if ($accessAccess)
						{
							$accessTo = $viewAccess[$viewName.'.access'];
						}
						// set main access controllers
						if ($accessDashboard_add)
						{
							$dashboard_add	= $user->authorise($viewAccess[$viewName.'.dashboard_add'], 'com_pro_critical');
						}
						if ($accessDashboard_list)
						{
							$dashboard_list = $user->authorise($viewAccess[$viewName.'.dashboard_list'], 'com_pro_critical');
						}
						if (Pro_criticalHelper::checkString($accessAdd) && Pro_criticalHelper::checkString($accessTo))
						{
							// check access
							if($user->authorise($accessAdd, 'com_pro_critical') && $user->authorise($accessTo, 'com_pro_critical') && $dashboard_add)
							{
								$icons[$group][$i]			= new StdClass;
								$icons[$group][$i]->url 	= $url;
								$icons[$group][$i]->name 	= $name;
								$icons[$group][$i]->image 	= $image;
								$icons[$group][$i]->alt 	= $alt;
							}
						}
						elseif (Pro_criticalHelper::checkString($accessTo))
						{
							// check access
							if($user->authorise($accessTo, 'com_pro_critical') && $dashboard_list)
							{
								$icons[$group][$i]			= new StdClass;
								$icons[$group][$i]->url 	= $url;
								$icons[$group][$i]->name 	= $name;
								$icons[$group][$i]->image 	= $image;
								$icons[$group][$i]->alt 	= $alt;
							}
						}
						elseif (Pro_criticalHelper::checkString($accessAdd))
						{
							// check access
							if($user->authorise($accessAdd, 'com_pro_critical') && $dashboard_add)
							{
								$icons[$group][$i]			= new StdClass;
								$icons[$group][$i]->url 	= $url;
								$icons[$group][$i]->name 	= $name;
								$icons[$group][$i]->image 	= $image;
								$icons[$group][$i]->alt 	= $alt;
							}
						}
						else
						{
							$icons[$group][$i]			= new StdClass;
							$icons[$group][$i]->url 	= $url;
							$icons[$group][$i]->name 	= $name;
							$icons[$group][$i]->image 	= $image;
							$icons[$group][$i]->alt 	= $alt;
						}
					}
					else
					{
						$icons[$group][$i]			= new StdClass;
						$icons[$group][$i]->url 	= $url;
						$icons[$group][$i]->name 	= $name;
						$icons[$group][$i]->image 	= $image;
						$icons[$group][$i]->alt 	= $alt;
					}
					$i++;
				}
			}
			else
			{
					$icons[$group][$i] = false;
			}
		}
		return $icons;
	}
}
