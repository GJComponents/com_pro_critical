<?php
/*----------------------------------------------------------------------------------|  www.vdm.io  |----/
				Gartes 
/-------------------------------------------------------------------------------------------------------/

	@version		1.5.19
	@build			23rd декабря, 2019
	@created		5th мая, 2019
	@package		proCritical
	@subpackage		componentviewid.php
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

// import the list field type
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

/**
 * Componentviewid Form Field class for the Pro_critical component
 */
class JFormFieldComponentviewid extends JFormFieldList
{
	/**
	 * The componentviewid field type.
	 *
	 * @var		string
	 */
	public $type = 'componentviewid';

	/**
	 * Method to get a list of options for a list input.
	 *
	 * @return	array    An array of JHtml options.
	 */
	protected function getOptions()
	{
				##############################
		## Editing the Fieldtype
		##  Type - Custom - Представление компонента 
		##
		##
		##
		// Get the user object.
		$user = JFactory::getUser();
		// Get the databse object.
		$db    = JFactory::getDBO();
		$query = $db->getQuery( true );
		$query->select( $db->quoteName( [ 'a.id' , 'a.view_component' ] , [ 'id' , 'component_view_id_view_component' ] ) );
		$query->from( $db->quoteName( '#__pro_critical_directory_views' , 'a' ) );
		$query->where( $db->quoteName( 'a.published' ) . ' = 1' );
		$query->order( 'a.view_component ASC' );
		$query->group( $db->quoteName( 'view_component' ) );
		// Implement View Level Access (if set in table)
		if( !$user->authorise( 'core.options' , 'com_pro_critical' ) )
		{
			$columns = $db->getTableColumns( '#__pro_critical_directory_views' );
			if( isset( $columns[ 'access' ] ) )
			{
				$groups = implode( ',' , $user->getAuthorisedViewLevels() );
				$query->where( 'a.access IN (' . $groups . ')' );
			}
		}
		$db->setQuery( (string) $query );
		$items   = $db->loadObjectList();
		$options = [];
		if( $items )
		{
			$options[] = JHtml::_( 'select.option' , '' , 'Select an option' );
			$options[] = JHtml::_( 'select.option' , '0' , 'All View' );
			foreach( $items as $item )
			{
				$options[] = JHtml::_( 'select.option' , $item->component_view_id_view_component , $item->component_view_id_view_component );
			}
		}
		
		return $options;
		################################## END ################################

	}
}
