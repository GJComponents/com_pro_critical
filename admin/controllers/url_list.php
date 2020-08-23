<?php
/*----------------------------------------------------------------------------------|  www.vdm.io  |----/
				Gartes 
/-------------------------------------------------------------------------------------------------------/

	@version		1.5.19
	@build			23rd декабря, 2019
	@created		5th мая, 2019
	@package		proCritical
	@subpackage		url_list.php
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
 * Url_list Controller
 */
class Pro_criticalControllerUrl_list extends JControllerAdmin
{
	/**
	 * The prefix to use with controller messages.
	 *
	 * @var    string
	 * @since  1.6
	 */
	protected $text_prefix = 'COM_PRO_CRITICAL_URL_LIST';

	/**
	 * Method to get a model object, loading it if required.
	 *
	 * @param   string  $name    The model name. Optional.
	 * @param   string  $prefix  The class prefix. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return  JModelLegacy  The model.
	 *
	 * @since   1.6
	 */
	public function getModel($name = 'Url', $prefix = 'Pro_criticalModel', $config = array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, $config);
	}

#Custom Buttons PHP List view (controller methods) [url]
	/**
	 * Задача Очистить все записи Справочник URL страниц
	 * 
	 * @return bool
	 * @throws Exception
	 * @since 3.9 
	 */
	public function OnBtnCleanTable( )
	{
		#  Delete all records
		$view = 'url';
		
		JLoader::registerNamespace( 'Plg\Pro_critical' , JPATH_PLUGINS . '/system/pro_critical/Helpers' , $reset = false , $prepend = false , $type = 'psr4' );
		# Очистить хранилище файлов AllCSS
		## \Plg\Pro_critical\Helpers\Assets\CriticalCss\Files::clearCacheFiles();
		
		$prefix = 'pro_critical';
		$app    = \JFactory::getApplication();
		$db     = JFactory::getDbo();
		$db->truncateTable( '#__' . $prefix . '_' . $view );
		$app->enqueueMessage( 'Записи удалены!' );
		$app->redirect( JRoute::_( 'index.php?' . 'option=com_' . $prefix . '&view=' . $app->input->get( 'view' , false , 'RAW' ) , false ) );
		
		return true;
	}
}
