<?php
/*----------------------------------------------------------------------------------|  www.vdm.io  |----/
				Gartes 
/-------------------------------------------------------------------------------------------------------/

	@version		1.x.x
	@build			23rd августа, 2020
	@created		5th мая, 2019
	@package		proCritical
	@subpackage		ajax.php
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
 * Pro_critical Ajax Model
 */
class Pro_criticalModelAjax extends JModelList
{
	protected $app_params;
	
	public function __construct() 
	{		
		parent::__construct();		
		// get params
		$this->app_params	= JComponentHelper::getParams('com_pro_critical');
		
	}

	// [Interpretation 16616] Used in html_task
####################################
### PHP Ajax Methods - Manager html task
###
### /administrator/components/com_pro_critical/models/ajax.php
####################################
	public function getListViewsForComponent ($ajax_component_idValue){
		$app = \JFactory::getApplication() ;
		$data = $app->input->get('taskData' , false , 'ARRAY');
		
		switch( $data['taskElement']){
			case 'getListView' :
				$id = $data['component_id'];
				#getVars - Получить ID записи по значению
				$model_Name = 'directory_views';
				$views_id = Pro_criticalHelper::getVars( ''.$model_Name ,   [$id] , 'id_component' , $what =  "id"   );
				
				$db = JFactory::getDbo();
				$query = $db->getQuery(true ) ;
				$query->select( $db->quoteName('id'))
					->select( $db->quoteName('view_component'))
					->from( $db->quoteName('#__pro_critical_directory_views'))
					->where( $db->quoteName('id') . 'IN' . ' (' . implode(',',$views_id) . ')' );
				
				$db->setQuery($query);
				$result = $db->loadAssocList() ;
				break ;
			default :
				$result = false ;
		}
		return  $result ;
	}#END FN
}
