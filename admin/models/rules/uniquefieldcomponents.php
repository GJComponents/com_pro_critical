<?php
/*----------------------------------------------------------------------------------|  www.vdm.io  |----/
				Gartes 
/-------------------------------------------------------------------------------------------------------/

	@version		1.5.19
	@build			23rd декабря, 2019
	@created		5th мая, 2019
	@package		proCritical
	@subpackage		uniquefieldcomponents.php
	@author			Nikolaychuk Oleg <https://nobd.ml>	
	@copyright		Copyright (C) 2019. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
  ____  _____  _____  __  __  __      __       ___  _____  __  __  ____  _____  _  _  ____  _  _  ____ 
 (_  _)(  _  )(  _  )(  \/  )(  )    /__\     / __)(  _  )(  \/  )(  _ \(  _  )( \( )( ___)( \( )(_  _)
.-_)(   )(_)(  )(_)(  )    (  )(__  /(__)\   ( (__  )(_)(  )    (  )___/ )(_)(  )  (  )__)  )  (   )(  
\____) (_____)(_____)(_/\/\_)(____)(__)(__)   \___)(_____)(_/\/\_)(__)  (_____)(_)\_)(____)(_)\_) (__) 

/------------------------------------------------------------------------------------------------------*/

// No direct access to this file
defined('JPATH_PLATFORM') or die;

use Joomla\CMS\Form\Form;
use Joomla\CMS\Form\FormRule;
use Joomla\Registry\Registry;

/**
 * Form Rule (Uniquefieldcomponents) class for the Joomla Platform.
 */
class JFormRuleUniquefieldcomponents extends FormRule
{
	/**
	 * Метод проверки значения поля на уникальность.
	 *
	 * @param   \SimpleXMLElement  $element  The SimpleXMLElement object representing the `<field>` tag for the form field object.
	 * @param   mixed              $value    The form field value to validate.
	 * @param   string             $group    The field name group control value. This acts as an array container for the field.
	 *                                       For example if the field has name="inetglobal" and the group value is set to "bar" then the
	 *                                       full field name would end up being "bar[inetglobal]".
	 * @param   Registry           $input    An optional Registry object with the entire data set to validate against the entire form.
	 * @param   Form               $form     The form object for which the field is being tested.
	 *
	 * @return  boolean  True if the value is valid, false otherwise.
	 *
	 * @since   11.1
	 */
	public function test(\SimpleXMLElement $element, $value, $group = null, Registry $input = null, Form $form = null)
	{
		// Get the database object and a new query object.
		$db = \JFactory::getDbo();
		$query = $db->getQuery(true);

		// Get the extra field check attribute.
		$id = ($input instanceof Registry) ? $input->get('id', null) : null;

		// get the component & table name
		$table = ($form instanceof Form) ? $form->getName() : '';

		// get the column name
		$name = (array) $element->attributes()->{'name'};
		$column = (string) trim($name[0]);
		
		// check that we have a value
		if (strlen($table) > 3 && strpos($table, 'pro_critical.') !== false)
		{
			// теперь получите имя таблицы
			$tableArray = explode('.', $table);
			// do we have two values
			if (count( (array) $tableArray) == 2)
			{
				// Build the query.
				$query->select('COUNT(*)')
					->from('#__pro_critical_' . (string) $tableArray[1])
					->where($db->quoteName($column) . ' = ' . $db->quote($value));

				// remove this item from the list
				if ($id > 0)
				{
					$query->where($db->quoteName('id') . ' <> ' . (int) $id);
				}

				// Set and query the database.
				$db->setQuery($query);
				$duplicate = (bool) $db->loadResult();

				if ($duplicate)
				{
					return false;
				}
			}
		}

		return true;
	}
}
