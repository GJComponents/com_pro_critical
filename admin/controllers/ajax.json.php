<?php
/*----------------------------------------------------------------------------------|  www.vdm.io  |----/
				Gartes 
/-------------------------------------------------------------------------------------------------------/

	@version		1.x.x
	@build			23rd августа, 2020
	@created		5th мая, 2019
	@package		proCritical
	@subpackage		ajax.json.php
	@author			Nikolaychuk Oleg <https://nobd.ml>	
	@copyright		Copyright (C) 2019. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
  ____  _____  _____  __  __  __      __       ___  _____  __  __  ____  _____  _  _  ____  _  _  ____ 
 (_  _)(  _  )(  _  )(  \/  )(  )    /__\     / __)(  _  )(  \/  )(  _ \(  _  )( \( )( ___)( \( )(_  _)
.-_)(   )(_)(  )(_)(  )    (  )(__  /(__)\   ( (__  )(_)(  )    (  )___/ )(_)(  )  (  )__)  )  (   )(  
\____) (_____)(_____)(_/\/\_)(____)(__)(__)   \___)(_____)(_/\/\_)(__)  (_____)(_)\_)(____)(_)\_) (__) 

/------------------------------------------------------------------------------------------------------*/


use Joomla\CMS\Factory;

use Joomla\CMS\MVC\Controller\BaseController as JControllerLegacy; //

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Pro_critical Ajax Controller
 */
class Pro_criticalControllerAjax extends JControllerLegacy
{
	public function __construct($config)
	{
        parent::__construct($config);
		// make sure all json stuff are set
		Factory::getDocument()->setMimeEncoding( 'application/json' );
		JResponse::setHeader('Content-Disposition','attachment;filename="getajax.json"');
		JResponse::setHeader("Access-Control-Allow-Origin", "*");
		// load the tasks 
		$this->registerTask('getViewsList', 'ajax');





	}

	public function ajax()
	{
		$user 		= JFactory::getUser();
		$jinput 	= JFactory::getApplication()->input;
		// Check Token!
		$token 		= JSession::getFormToken();
		$call_token	= $jinput->get('token', 0, 'ALNUM');
		if($jinput->get($token, 0, 'ALNUM') || $token === $call_token)
		{
			$task = $this->getTask();
			switch($task)
			{

				case 'getViewsList':
					try
					{

						$returnRaw = $jinput->get('raw', false, 'BOOLEAN');
						$ajax_component_idValue = $jinput->get('ajax_component_id', NULL, 'INT');
						$result = $this->getModel('ajax')->getListViewsForComponent($ajax_component_idValue);
						if($callback = $jinput->get('callback', null, 'CMD'))
						{
							echo $callback . "(".json_encode($result).");";
						}
						elseif($returnRaw)
						{
							echo json_encode($result);
						}
						else
						{
							echo "(".json_encode($result).");";
						}
					}
					catch(Exception $e)
					{
						if($callback = $jinput->get('callback', null, 'CMD'))
						{
							echo $callback."(".json_encode($e).");";
						}
						else
						{
							echo "(".json_encode($e).");";
						}
					}
				break;
                case 'getFormSetUpTask':
                    try
                    {
                        $html_processing = $jinput->get('html_processing', NULL, 'STRING');
                        $task_data = $jinput->get('task_data', NULL, 'RAW');
                        $task_data = json_decode($task_data) ;
                        $result = $this->getModel('ajax')->getFormSetUpTask( $html_processing , $task_data ); ;
                        parent::display();
                        die(__FILE__ .' '. __LINE__ );
                    }catch(Exception $e)
                    {
                        if($callback = $jinput->get('callback', null, 'CMD'))
                        {
                            echo $callback."(".json_encode($e).");";
                        }
                        else
                        {
                            echo "(".json_encode($e).");";
                        }
                    }


                    break ;

			}
		}
		else
		{
			if($callback = $jinput->get('callback', null, 'CMD'))
			{
				echo $callback."(".json_encode(false).");";
			}
			else
			{
				echo "(".json_encode(false).");";
			}
		}
	}
}
