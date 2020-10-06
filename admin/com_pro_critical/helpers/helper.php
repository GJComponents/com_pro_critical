<?php
	
	namespace Com_pro_critical\Helpers;
	use Exception;
	use Joomla\CMS\Factory as JFactory;
	
	// No direct access to this file
	defined( '_JEXEC' ) or die( 'Restricted access' );
	
	/**
	 * @since       version
	 * @package     com_pro_critical\Helpers
	 *
	 */
	class Helper
	{
		// private $app ;
		
		
		
		/**
		 * Helper constructor.
		 * @since 3.9
		 */
		public function __construct () { }
		
		/**
		 * установка ресурсов для админ панели
		 * @throws Exception
		 * @since version
		 */
		public static function settingsAdminViews (){
			$app = JFactory::getApplication() ;
			$doc = JFactory::getDocument() ;


			$option = $app->input->get( 'option' ) ;
			$view = $app->input->get( 'view' ) ;


			switch( $option )
			{
				case 'com_config' :
					# Страница настройки компонента
					if( $app->input->get( 'component' ) == 'com_pro_critical' )
					{
						$doc->addScript( '/plugins/system/pro_critical/assets/js/admin_com_config.js' );
					}#END IF
					break;
			}
			
			$doc->addStyleSheet( '/plugins/system/pro_critical/assets/css/admin_css.css' );
			$doc->addScript( '/plugins/system/pro_critical/assets/js/admin_script.js' );
			$data = [
				'option' => $option ,
				'view' => $view ,
			];
			$doc->addScriptOptions( 'PlgProCritical' , $data );
			
		}
		
		
	}