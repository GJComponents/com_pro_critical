<?php
/*----------------------------------------------------------------------------------|  www.vdm.io  |----/
				Gartes 
/-------------------------------------------------------------------------------------------------------/

	@version		1.x.x
	@build			23rd августа, 2020
	@created		5th мая, 2019
	@package		proCritical
	@subpackage		typedeviceidhtml.php
	@author			Nikolaychuk Oleg <https://nobd.ml>	
	@copyright		Copyright (C) 2019. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
  ____  _____  _____  __  __  __      __       ___  _____  __  __  ____  _____  _  _  ____  _  _  ____ 
 (_  _)(  _  )(  _  )(  \/  )(  )    /__\     / __)(  _  )(  \/  )(  _ \(  _  )( \( )( ___)( \( )(_  _)
.-_)(   )(_)(  )(_)(  )    (  )(__  /(__)\   ( (__  )(_)(  )    (  )___/ )(_)(  )  (  )__)  )  (   )(  
\____) (_____)(_____)(_/\/\_)(____)(__)(__)   \___)(_____)(_/\/\_)(__)  (_____)(_)\_)(____)(_)\_) (__) 

/------------------------------------------------------------------------------------------------------*/

// No direct access to this file
use Joomla\CMS\Language\Text;

defined('_JEXEC') or die('Restricted access');

// import the list field type
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

/**
 * Typedeviceidhtml Form Field class for the Pro_critical component
 */
class JFormFieldHtmltaskevents extends JFormFieldList
{
	/**
	 * The htmltaskevents field type.
	 *
	 * @var		string
     * @since 3.9
	 */
	public $type = 'htmltaskevents';



	/**
	 * Method to get a list of options for a list input.
	 *
	 * @return	array    An array of JHtml options.
     * @since 3.9
	 */
	protected function getOptions()
	{
		##############################
## Editing the Fieldtype
##  Type - Custom  
##
##
##
// Get the user object.

		$items = [
		    ['id'=>'click' , 'name' => 'COM_PRO_CRITICAL_HTML_TASK_CLICK_TO_ELEMENT'],  # Клик по элементу
		    ['id'=>'hover' , 'name' => 'COM_PRO_CRITICAL_HTML_TASK_HOVER'],  # Клик по элементу
		    ['id'=>'mouse_move' , 'name' => 'COM_PRO_CRITICAL_HTML_TASK_MOUSE_MOVE'],   # Движение мыши(первое)

            ['id'=>'scroll' , 'name' => 'COM_PRO_CRITICAL_HTML_TASK_SCROLL_WINDOW'],    # Вход в зону видимости при скроле

            ['id'=>'not_interact' , 'name' => 'COM_PRO_CRITICAL_HTML_TASK_NOT_INTERACT'],# Не взаимодействовать
            ['id'=>'removeElement' , 'name' => 'COM_PRO_CRITICAL_HTML_TASK_REMOVE_ELEMENT'],# Не взаимодействовать

        ];
		$options = array();
		if ($items)
		{
			$options[] = JHtml::_('select.option', '', 'Default');
			foreach($items as $item)
			{
				$options[] = JHtml::_('select.option', $item['id'], Text::_($item['name']));
			}
		}
		return $options;
	}
}
