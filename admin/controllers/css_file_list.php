<?php
/*----------------------------------------------------------------------------------|  www.vdm.io  |----/
				Gartes 
/-------------------------------------------------------------------------------------------------------/

	@version		1.x.x
	@build			23rd августа, 2020
	@created		5th мая, 2019
	@package		proCritical
	@subpackage		css_file_list.php
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
use Joomla\CMS\Router\Route;

defined('_JEXEC') or die('Restricted access');

/**
 * Css_file_list Controller
 */
class Pro_criticalControllerCss_file_list extends JControllerAdmin
{
	/**
	 * The prefix to use with controller messages.
	 *
	 * @var    string
	 * @since  1.6
	 */
	protected $text_prefix = 'COM_PRO_CRITICAL_CSS_FILE_LIST';

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
	public function getModel($name = 'Css_file', $prefix = 'Pro_criticalModel', $config = array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, $config);
	}

    /**
     * Очистить Справочник CSS файлов
     * Удалит все записи в таблице #__pro_critical_css_file
     * #Custom Buttons PHP List view (controller methods) [css_file]
     * @return bool
     * @since 3.9
     * @auhtor Gartes | sad.net79@gmail.com | Skype : agroparknew | Telegram : @gartes
     * @date 12.11.2020 18:41
     *
     */
    public function OnBtnCleanTable( ){

        #  Delete all records
        $view = 'css_file' ;

        $prefix = 'pro_critical' ;
        $app = Factory::getApplication() ;
        $db = Factory::getDbo();
        $db->truncateTable('#__'.$prefix.'_' . $view );
        $app->enqueueMessage('Записи удалены!');
        $app->redirect(Route::_('index.php?'
            .'option=com_'.$prefix
            .'&view='. $app->input->get( 'view' , false , 'RAW' )
            , false));
        return true ;
    }
}
