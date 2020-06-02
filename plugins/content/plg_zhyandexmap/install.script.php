<?php
/*------------------------------------------------------------------------
# plg_zhyandexmap - Zh YandexMap Plugin
# ------------------------------------------------------------------------
# author:    Dmitry Zhuk
# copyright: Copyright (C) 2011 zhuk.cc. All Rights Reserved.
# license:   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
# website:   http://zhuk.cc
# Technical Support Forum: http://forum.zhuk.cc/
-------------------------------------------------------------------------*/
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

require_once(__DIR__ . '/helpers/install.php');

class plgContentPlg_ZhYandexMapInstallerScript {
	
	function doInit()
	{
		plgZhYandexMapInstallHelper::setExtensionName('plg_zhyandexmap');
		plgZhYandexMapInstallHelper::setExtensionType('plugin');
		plgZhYandexMapInstallHelper::setExtensionFolder('content');


		//plgZhYandexMapInstallHelper::setDebugMode(false);	
		//plgZhYandexMapInstallHelper::setDebugInfo(1);
		
	}
	
	/**
	 * method to install the plugin
	 *
	 * @return void
	 */
	function install($parent) 
	{
	}
 
	/**
	 * method to uninstall the plugin
	 *
	 * @return void
	 */
	function uninstall($parent) 
	{
	}
 
	/**
	 * method to update the plugin
	 *
	 * @return void
	 */
	function update($parent) 
	{
		$manifest = $parent->getParent()->getManifest();
	}
 
	/**
	 * method to run before an install/update/uninstall method
	 *
	 * @return void
	 */
	function preflight($type, $parent) 
	{
		// $parent is the class calling this method
		// $type is the type of change (install, update or discover_install)
		plgContentPlg_ZhYandexMapInstallerScript::doInit();
	}
 
	/**
	 * method to run after an install/update/uninstall method
	 *
	 * @return void
	 */
	function postflight($type, $parent) 
	{
		// $parent is the class calling this method
		// $type is the type of change (install, update or discover_install)
		plgZhYandexMapInstallHelper::addLog('<h4>Check plugin status</h4>', 4, 0, 0);
		
		$extID = plgZhYandexMapInstallHelper::getExtensionID();
		
		if (isset($extID) && $extID != "")
		{
			//JFactory::getApplication()->enqueueMessage('Plugin is installed!', 'notice');
			$extStatus = plgZhYandexMapInstallHelper::getExtensionEnabledStatus();
			if (isset($extStatus) && $extStatus != "")
			{
				if ((int)$extStatus == 1)
				{
					//JFactory::getApplication()->enqueueMessage('Plugin is enabled!', 'message');
					plgZhYandexMapInstallHelper::addLog('<h5>plugin is enabled<h5>', 3, 0, 0);
				}
				else
				{
					JFactory::getApplication()->enqueueMessage('You should enable plugin!', 'warning');
				}
			}
			else
			{
				JFactory::getApplication()->enqueueMessage('Unable to get plugin status!', 'error');
			}
		}
		else
		{
			JFactory::getApplication()->enqueueMessage('Plugin is not installed!', 'error');
		}
		
		plgZhYandexMapInstallHelper::addLog('<h4>Plugin status checked</h4>', 4, 0, 0);
	}	
	

}