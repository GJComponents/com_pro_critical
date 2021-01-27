<?php
/*----------------------------------------------------------------------------------|  www.vdm.io  |----/
				Gartes 
/-------------------------------------------------------------------------------------------------------/

	@version		1.x.x
	@build			23rd августа, 2020
	@created		5th мая, 2019
	@package		proCritical
	@subpackage		html_task.php
	@author			Nikolaychuk Oleg <https://nobd.ml>	
	@copyright		Copyright (C) 2019. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
  ____  _____  _____  __  __  __      __       ___  _____  __  __  ____  _____  _  _  ____  _  _  ____ 
 (_  _)(  _  )(  _  )(  \/  )(  )    /__\     / __)(  _  )(  \/  )(  _ \(  _  )( \( )( ___)( \( )(_  _)
.-_)(   )(_)(  )(_)(  )    (  )(__  /(__)\   ( (__  )(_)(  )    (  )___/ )(_)(  )  (  )__)  )  (   )(  
\____) (_____)(_____)(_/\/\_)(____)(__)(__)   \___)(_____)(_/\/\_)(__)  (_____)(_)\_)(____)(_)\_) (__) 

/------------------------------------------------------------------------------------------------------*/

// No direct access to this file
use Joomla\CMS\Uri\Uri;

defined('_JEXEC') or die('Restricted access');

/**
 * Html_task Controller
 */
class Pro_criticalControllerHtml_task extends JControllerLegacy
{
	/**
	 * Current or most recently performed task.
	 *
	 * @var    string
	 * @since  12.2
	 * @note   Replaces _task.
	 */
	protected $task;

	/**
	 * Class constructor.
	 *
	 * @param   array  $config  A named array of configuration variables.
	 *
	 * @since   1.6
	 */
	public function __construct($config = array())
	{
        parent::__construct($config);
        $user 		= JFactory::getUser();
        $jinput 	= JFactory::getApplication()->input;
        // Check Token!
        $token 		= JSession::getFormToken();
        $call_token	= $jinput->get('token', 0, 'ALNUM');
        if($jinput->get($token, 0, 'ALNUM') || $token === $call_token)
        {
            $this->registerTask('getFormSetUpTask', 'ajax');
        }
        

	}
	public function getFormSetUpTask(){
        $html_processing = $this->input->get('html_processing', NULL, 'STRING');
        $view = $this->getView('html_task','json');
        $view->setLayout($html_processing);
        parent::display();


        echo'<pre>';print_r( $html_processing );echo'</pre>'.__FILE__.' '.__LINE__;
        
        

        


//	    echo'<pre>';print_r( $view );echo'</pre>'.__FILE__.' '.__LINE__;
//	    echo'<pre>';print_r( $this );echo'</pre>'.__FILE__.' '.__LINE__;



//	    die(__FILE__ .' '. __LINE__ );

    }


}
