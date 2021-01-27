<?php
/*----------------------------------------------------------------------------------|  www.vdm.io  |----/
				Gartes 
/-------------------------------------------------------------------------------------------------------/

	@version		1.x.x
	@build			23rd августа, 2020
	@created		5th мая, 2019
	@package		proCritical
	@subpackage		componentnamecom.php
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
 * Componentnamecom Form Field class for the Pro_critical component
 */
class JFormFieldComponentnamecom extends JFormFieldList
{
	/**
	 * The componentnamecom field type.
	 *
	 * @var		string
     * @since 3.9
	 */
	public $type = 'componentnamecom';

	/**
	 * Override to add new button
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   3.2
	 */
	protected function getInput()
	{
		// [Fields 4762] see if we should add buttons
		$set_button = $this->getAttribute('button');
		// [Fields 4766] get html
		$html = parent::getInput();





		// [Fields 4769] if true set button
		if ($set_button === 'true')
		{
			$button = array();
			$script = array();
			$button_code_name = $this->getAttribute('name');
			// [Fields 4777] get the input from url
			$app = JFactory::getApplication();
			$jinput = $app->input;
			// [Fields 4781] get the view name & id
			$values = $jinput->getArray(array(
				'id' => 'int',
				'view' => 'word'
			));
			// [Fields 4788] check if new item
			$ref = '';
			$refJ = '';
			if (!is_null($values['id']) && strlen($values['view']))
			{
				// [Fields 4797] only load referral if not new item.
				$ref = '&amp;ref=' . $values['view'] . '&amp;refid=' . $values['id'];
				$refJ = '&ref=' . $values['view'] . '&refid=' . $values['id'];
				// [Fields 4803] get the return value.
				$_uri = (string) JUri::getInstance();
				$_return = urlencode(base64_encode($_uri));
				// [Fields 4809] load return value.
				$ref .= '&amp;return=' . $_return;
				$refJ .= '&return=' . $_return;
			}
			// [Fields 4842] get button label
			$button_label = trim($button_code_name);
			$button_label = preg_replace('/_+/', ' ', $button_label);
			$button_label = preg_replace('/\s+/', ' ', $button_label);
			$button_label = preg_replace("/[^A-Za-z ]/", '', $button_label);
			$button_label = ucfirst(strtolower($button_label));
			// [Fields 4854] get user object
			$user = JFactory::getUser();
			// [Fields 4857] only add if user allowed to create directory_components
			if ($user->authorise('core.create', 'com_pro_critical') && $app->isAdmin()) // TODO for now only in admin area.
			{
				// [Fields 4881] build Create button
				$button[] = '<a id="'.$button_code_name.'Create" class="btn btn-small btn-success hasTooltip" title="'.JText::sprintf('COM_PRO_CRITICAL_CREATE_NEW_S', $button_label).'" style="border-radius: 0px 4px 4px 0px; padding: 4px 4px 4px 7px;"
					href="index.php?option=com_pro_critical&amp;view=directory_components&amp;layout=edit'.$ref.'" >
					<span class="icon-new icon-white"></span></a>';
			}
			// [Fields 4893] only add if user allowed to edit directory_components
			if ($user->authorise('core.edit', 'com_pro_critical') && $app->isAdmin()) // TODO for now only in admin area.
			{
				// [Fields 4917] build edit button
				$button[] = '<a id="'.$button_code_name.'Edit" class="btn btn-small hasTooltip" title="'.JText::sprintf('COM_PRO_CRITICAL_EDIT_S', $button_label).'" style="display: none; padding: 4px 4px 4px 7px;" href="#" >
					<span class="icon-edit"></span></a>';
				// [Fields 4925] build script
				$script[] = "
					jQuery(document).ready(function() {
						jQuery('#adminForm').on('change', '#jform_".$button_code_name."',function (e) {
							e.preventDefault();
							var ".$button_code_name."Value = jQuery('#jform_".$button_code_name."').val();
							".$button_code_name."Button(".$button_code_name."Value);
						});
						var ".$button_code_name."Value = jQuery('#jform_".$button_code_name."').val();
						".$button_code_name."Button(".$button_code_name."Value);
					});
					function ".$button_code_name."Button(value) {
						if (value > 0) {
							// hide the create button
							jQuery('#".$button_code_name."Create').hide();
							// show edit button
							jQuery('#".$button_code_name."Edit').show();
							var url = 'index.php?option=com_pro_critical&view=directory_components_list&task=directory_components.edit&id='+value+'".$refJ."';
							jQuery('#".$button_code_name."Edit').attr('href', url);
						} else {
							// show the create button
							jQuery('#".$button_code_name."Create').show();
							// hide edit button
							jQuery('#".$button_code_name."Edit').hide();
						}
					}";
			}
			// [Fields 4968] check if button was created for directory_components field.
			if (is_array($button) && count($button) > 0)
			{
				// [Fields 4974] Load the needed script.
				$document = JFactory::getDocument();
				$document->addScriptDeclaration(implode(' ',$script));
				// [Fields 4980] return the button attached to input field.
				return '<div class="input-append">' .$html . implode('',$button).'</div>';
			}
		}
		return $html;
	}

	/**
	 * Method to get a list of options for a list input.
	 *
	 * @return	array    An array of JHtml options.
     *
     * @since 3.9
	 */
	protected function getOptions()
	{
		##############################
## Editing the Fieldtype
##  Type - Custom  
##  Component Name Com[Custom]
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
