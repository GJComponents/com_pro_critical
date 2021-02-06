<?php
/*----------------------------------------------------------------------------------|  www.vdm.io  |----/
				Gartes 
/-------------------------------------------------------------------------------------------------------/

	@version		1.x.x
	@build			11th ноября, 2019
	@created		5th мая, 2019
	@package		proCritical
	@subpackage		script.php
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

JHTML::_('behavior.modal');

/**
 * Script File of Pro_critical Component
 */
class com_pro_criticalInstallerScript
{
	/**
	 * Constructor
	 *
	 * @param   JAdapterInstance  $parent  The object responsible for running this script
	 */
	public function __construct(JAdapterInstance $parent) {}

	/**
	 * Called on installation
	 *
	 * @param   JAdapterInstance  $parent  The object responsible for running this script
	 *
	 * @return  boolean  True on success
	 */
	public function install(JAdapterInstance $parent) {}

	/**
	 * Called on uninstallation
	 *
	 * @param   JAdapterInstance  $parent  The object responsible for running this script
	 */
	public function uninstall(JAdapterInstance $parent)
	{
		// little notice as after service, in case of bad experience with component.
		echo '<h2>Did something go wrong? Are you disappointed?</h2>
		<p>Please let me know at <a href="mailto:sad.net79@gmail.com">sad.net79@gmail.com</a>.
		<br />We at Gartes are committed to building extensions that performs proficiently! You can help us, really!
		<br />Send me your thoughts on improvements that is needed, trust me, I will be very grateful!
		<br />Visit us at <a href="https://nobd.ml" target="_blank">https://nobd.ml</a> today!</p>';
	}

	/**
	 * Called on update
	 *
	 * @param   JAdapterInstance  $parent  The object responsible for running this script
	 *
	 * @return  boolean  True on success
	 */
	public function update(JAdapterInstance $parent){}

	/**
	 * Called before any type of action
	 *
	 * @param   string  $type  Which action is happening (install|uninstall|discover_install|update)
	 * @param   JAdapterInstance  $parent  The object responsible for running this script
	 *
	 * @return  boolean  True on success
	 */
	public function preflight($type, JAdapterInstance $parent)
	{


		// get application
		$app = JFactory::getApplication();
		// is redundant or so it seems ...hmmm let me know if it works again
		if ($type === 'uninstall')
		{
			return true;
		}
		// the default for both install and update
		$jversion = new JVersion();
		if (!$jversion->isCompatible('3.8.0'))
		{
			$app->enqueueMessage('Please upgrade to at least Joomla! 3.8.0 before continuing!', 'error');
			return false;
		}
		// do any updates needed
		if ($type === 'update')
		{
		}
		// do any install needed
		if ($type === 'install')
		{
		}
		return true;
	}

	/**
	 * Called after any type of action
	 *
	 * @param   string  $type  Which action is happening (install|uninstall|discover_install|update)
	 * @param   JAdapterInstance  $parent  The object responsible for running this script
	 *
	 * @return  boolean  True on success
	 */
	public function postflight($type, JAdapterInstance $parent)
	{
		// get application
		$app = JFactory::getApplication();
		// set the default component settings
		if ($type === 'install')
		{
			// [Interpretation 4993] Install the global extenstion params.
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			// [Interpretation 4997] Field to update.
			$fields = array(
				$db->quoteName('params') . ' = ' .
                $db->quote('{
                  "autorName": "Nikolaychuk Oleg",
                  "autorEmail": "sad.net79@gmail.com",
                  "shorten_setting": {
                    "shorten_setting0":{"view_component":"css_file_list","length":255},
                    "shorten_setting1":{"view_component":"css_list","length":255},
                    "shorten_setting2":{"view_component":"user_agent_list","length":255},
                    "shorten_setting3":{"view_component":"url_list","length":255},
                    "shorten_setting4":{"view_component":"css_style_list","length":100}
                  },
                  "external_cache_directory": "/media/com_pro_critical/cashe_access",
                  "gnzlib_path_file_corejs": "/libraries/GNZ11/assets/js/gnz11.js",
                  "gnzlib_debug_off": "1",
                  "gnzlib_path_file_corejs_min": "/libraries/GNZ11/assets/js/gnz11.min.js",
                  "gnzlib_path_modules": "/libraries/GNZ11/assets/js/modules",
                  "gnzlib_path_plugins": "/libraries/GNZ11/assets/js/plugins",
                  "css_style_load_method": "1",
                  "check_in": "-1 day"
                  }'
                ),
			);
			// [Interpretation 5001] Condition.
			$conditions = array(
				$db->quoteName('element') . ' = ' . $db->quote('com_pro_critical')
			);
			$query->update($db->quoteName('#__extensions'))->set($fields)->where($conditions);
			$db->setQuery($query);
			$allDone = $db->execute();

			echo '<a target="_blank" href="https://nobd.ml" title="proCritical">
				<img src="components/com_pro_critical/assets/images/vdm-component.jpg"/>
				</a>';
		}
		// do any updates needed
		if ($type === 'update')
		{
			echo '<a target="_blank" href="https://nobd.ml" title="proCritical">
				<img src="components/com_pro_critical/assets/images/vdm-component.jpg"/>
				</a>
				<h3>Upgrade to Version 1.4.72 Was Successful! Let us know if anything is not working as expected.</h3>';
		}
		return true;
	}
}
