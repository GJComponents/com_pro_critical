<?php
/*----------------------------------------------------------------------------------|  www.vdm.io  |----/
				Gartes 
/-------------------------------------------------------------------------------------------------------/

	@version		1.x.x
	@build			23rd августа, 2020
	@created		5th мая, 2019
	@package		proCritical
	@subpackage		componentnamecomhtml.php
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
 * Componentnamecomhtml Form Field class for the Pro_critical component
 */
class JFormFieldComponentnamecomhtml extends JFormFieldList
{
	/**
	 * The componentnamecomhtml field type.
	 *
	 * @var		string
	 */
	public $type = 'componentnamecomhtml';

	/**
	 * Method to get a list of options for a list input.
	 *
	 * @return	array    An array of JHtml options.
	 */
	protected function getOptions()
	{
		##############################
## Editing the Fieldtype
##  Type - Custom  
##
##
##
                $viewArr = [ 'html_task'  ];
                $app = \JFactory::getApplication() ;
		$view = $app->input->get( 'view' , false , 'RAW' );

// Get the user object.
		$user = JFactory::getUser();
		// Get the databse object.
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select($db->quoteName(array('a.id','a.copmonent_name'),array('id','id_component_copmonent_name')));
		$query->from($db->quoteName('#__pro_critical_directory_components', 'a'));
		$query->where($db->quoteName('a.published') . ' = 1');
		$query->order('a.copmonent_name ASC');
		// Implement View Level Access (if set in table)
		if (!$user->authorise('core.options', 'com_pro_critical'))
		{
			$columns = $db->getTableColumns('#__pro_critical_directory_components');
			if(isset($columns['access']))
			{
				$groups = implode(',', $user->getAuthorisedViewLevels());
				$query->where('a.access IN (' . $groups . ')');
			}
		}
		$db->setQuery((string)$query);
		$items = $db->loadObjectList();
		$options = array();
		if ($items)
		{
			$options[] = JHtml::_('select.option', '', 'Select an option');

                        # Добавить на всех компонентах
			if( in_array( $view , $viewArr )  ) $options[] = JHtml::_('select.option', '0', 'All component'); #END IF

			foreach($items as $item)
			{
				$options[] = JHtml::_('select.option', $item->id, $item->id_component_copmonent_name);
			}
		}
		return $options;
	}
}
