<?php
/*------------------------------------------------------------------------
# com_zhyandexmap - Zh YandexMap
# ------------------------------------------------------------------------
# author:    Dmitry Zhuk
# copyright: Copyright (C) 2011 zhuk.cc. All Rights Reserved.
# license:   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
# website:   http://zhuk.cc
# Technical Support Forum: http://forum.zhuk.cc/
-------------------------------------------------------------------------*/
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * ZhYandexMap component helper.
 */
abstract class ZhYandexMapHelper
{
	/**
	 * Configure the Linkbar.
	 */
	public static function addSubmenu($submenu) 
	{
		JHtmlSidebar::addEntry(JText::_('COM_ZHYANDEXMAP_SUBMENU_MAPS'), 'index.php?option=com_zhyandexmap&view=zhyandexmaps', $submenu == 'zhyandexmaps');
		JHtmlSidebar::addEntry(JText::_('COM_ZHYANDEXMAP_SUBMENU_MAPMARKERS'), 'index.php?option=com_zhyandexmap&view=mapmarkers', $submenu == 'mapmarkers');
		JHtmlSidebar::addEntry(JText::_('COM_ZHYANDEXMAP_SUBMENU_MAPMARKERGROUPS'), 'index.php?option=com_zhyandexmap&view=mapmarkergroups', $submenu == 'mapmarkergroups');
		JHtmlSidebar::addEntry(JText::_('COM_ZHYANDEXMAP_SUBMENU_MAPROUTERS'), 'index.php?option=com_zhyandexmap&view=maprouters', $submenu == 'maprouters');
		JHtmlSidebar::addEntry(JText::_('COM_ZHYANDEXMAP_SUBMENU_MAPPATHS'), 'index.php?option=com_zhyandexmap&view=mappaths', $submenu == 'mappaths');
		JHtmlSidebar::addEntry(JText::_('COM_ZHYANDEXMAP_SUBMENU_MAPTYPES'), 'index.php?option=com_zhyandexmap&view=maptypes', $submenu == 'maptypes');
		JHtmlSidebar::addEntry(JText::_('COM_ZHYANDEXMAP_SUBMENU_CATEGORIES'), 'index.php?option=com_categories&view=categories&extension=com_zhyandexmap', $submenu == 'categories');
		JHtmlSidebar::addEntry(JText::_('COM_ZHYANDEXMAP_SUBMENU_ABOUT'), 'index.php?option=com_zhyandexmap&view=abouts', $submenu == 'abouts');
		// set some global property
		$document = JFactory::getDocument();
		$document->addStyleDeclaration('.icon-48-zhyandexmap {background-image: url(../media/com_zhyandexmap/images/map-48x48.png);}');
		if ($submenu == 'categories') 
		{
			$document->setTitle(JText::_('COM_ZHYANDEXMAP_ADMINISTRATION_CATEGORIES'));
		}
	}
	/**
	 * Get the actions
	 */
	public static function getMapActions($mapId = 0)
	{
		$user	= JFactory::getUser();
		$result	= new JObject;

		if (empty($mapId)) {
			$assetName = 'com_zhyandexmap';
		}
		else {
			$assetName = 'com_zhyandexmap';
			//$assetName = 'com_zhyandexmap.map.'.(int) $mapId;
		}

		$actions = array(
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.state', 'core.edit.own', 'core.delete'
		);

		foreach ($actions as $action) {
			$result->set($action,	$user->authorise($action, $assetName));
		}

		return $result;
	}

	public static function getMarkerActions($mapmarkerId = 0)
	{
		$user	= JFactory::getUser();
		$result	= new JObject;

		if (empty($mapmarkerId)) {
			$assetName = 'com_zhyandexmap';
		}
		else {
			$assetName = 'com_zhyandexmap';
			//$assetName = 'com_zhyandexmap.mapmarker.'.(int) $mapmarkerId;
		}

		$actions = array(
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.state', 'core.edit.own', 'core.delete'
		);

		foreach ($actions as $action) {
			$result->set($action,	$user->authorise($action, $assetName));
		}

		return $result;
	}


	public static function getMarkerGroupActions($mapmarkerGroupId = 0)
	{
		$user	= JFactory::getUser();
		$result	= new JObject;

		if (empty($mapmarkerGroupId)) {
			$assetName = 'com_zhyandexmap';
		}
		else {
			$assetName = 'com_zhyandexmap';
			//$assetName = 'com_zhyandexmap.mapmarkergroup.'.(int) $mapmarkerGroupId;
		}

		$actions = array(
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.state', 'core.edit.own', 'core.delete'
		);

		foreach ($actions as $action) {
			$result->set($action,	$user->authorise($action, $assetName));
		}

		return $result;
	}


	public static function getRouterActions($maprouterId = 0)
	{
		$user	= JFactory::getUser();
		$result	= new JObject;

		if (empty($maprouterId)) {
			$assetName = 'com_zhyandexmap';
		}
		else {
			$assetName = 'com_zhyandexmap';
			//$assetName = 'com_zhyandexmap.maprouter.'.(int) $maprouterId;
		}

		$actions = array(
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.state', 'core.edit.own', 'core.delete'
		);

		foreach ($actions as $action) {
			$result->set($action,	$user->authorise($action, $assetName));
		}

		return $result;
	}

	public static function getPathActions($mappathId = 0)
	{
		$user	= JFactory::getUser();
		$result	= new JObject;

		if (empty($mappathId)) {
			$assetName = 'com_zhyandexmap';
		}
		else {
			$assetName = 'com_zhyandexmap';
			//$assetName = 'com_zhyandexmap.mappath.'.(int) $mappathId;
		}

		$actions = array(
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.state', 'core.edit.own', 'core.delete'
		);

		foreach ($actions as $action) {
			$result->set($action,	$user->authorise($action, $assetName));
		}

		return $result;
	}

	public static function getTypeActions($maptypeId = 0)
	{
		$user	= JFactory::getUser();
		$result	= new JObject;

		if (empty($maptypeId)) {
			$assetName = 'com_zhyandexmap';
		}
		else {
			$assetName = 'com_zhyandexmap';
			//$assetName = 'com_zhyandexmap.maptype.'.(int) $maptypeId;
		}

		$actions = array(
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.state', 'core.edit.own', 'core.delete'
		);

		foreach ($actions as $action) {
			$result->set($action,	$user->authorise($action, $assetName));
		}

		return $result;
	}
	
}
