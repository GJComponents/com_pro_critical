<?php
/*----------------------------------------------------------------------------------|  www.vdm.io  |----/
				Gartes 
/-------------------------------------------------------------------------------------------------------/

	@version		1.x.x
	@build			23rd августа, 2020
	@created		5th мая, 2019
	@package		proCritical
	@subpackage		css_list.php
	@author			Nikolaychuk Oleg <https://nobd.ml>	
	@copyright		Copyright (C) 2019. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
  ____  _____  _____  __  __  __      __       ___  _____  __  __  ____  _____  _  _  ____  _  _  ____ 
 (_  _)(  _  )(  _  )(  \/  )(  )    /__\     / __)(  _  )(  \/  )(  _ \(  _  )( \( )( ___)( \( )(_  _)
.-_)(   )(_)(  )(_)(  )    (  )(__  /(__)\   ( (__  )(_)(  )    (  )___/ )(_)(  )  (  )__)  )  (   )(  
\____) (_____)(_____)(_/\/\_)(____)(__)(__)   \___)(_____)(_/\/\_)(__)  (_____)(_)\_)(____)(_)\_) (__) 

/------------------------------------------------------------------------------------------------------*/

// No direct access to this file
use Joomla\CMS\Factory;

defined('_JEXEC') or die('Restricted access');

/**
 * Css_list Controller
 */
class Pro_criticalControllerCss_list extends JControllerAdmin
{
	/**
	 * The prefix to use with controller messages.
	 *
	 * @var    string
	 * @since  1.6
	 */
	protected $text_prefix = 'COM_PRO_CRITICAL_CSS_LIST';

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
	public function getModel($name = 'Css', $prefix = 'Pro_criticalModel', $config = array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, $config);
	}

    const ALL_CSS_PATCH = JPATH_ROOT . DIRECTORY_SEPARATOR .'cache'. DIRECTORY_SEPARATOR .'_allCss'. DIRECTORY_SEPARATOR ;

    #Custom Buttons PHP List view (controller methods) [js_file]
    public function OnBtnCleanTable( ){

        $app = Factory::getApplication() ;
        $db = Factory::getDbo();

        #  Delete all records
        $view = 'css' ;
        $prefix = 'pro_critical' ;


        # Удалить все файлы AllCss из Директории /cache
        $files = glob(self::ALL_CSS_PATCH.'*');
        array_map('unlink', array_filter((array) $files ));
        $app->enqueueMessage('Кеш временных файлов очищен!');




        $db->truncateTable('#__'.$prefix.'_' . $view );





        $app->enqueueMessage('Записи удалены!');

        $app->redirect(JRoute::_('index.php?'
            .'option=com_'.$prefix
            .'&view='. $app->input->get( 'view' , false , 'RAW' )
            , false));
        return true ;
    }
}
