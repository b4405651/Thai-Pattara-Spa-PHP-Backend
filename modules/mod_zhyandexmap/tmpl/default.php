<?php
/*------------------------------------------------------------------------
# mod_zhyandexmap - Zh YandexMap Module
# ------------------------------------------------------------------------
# author:    Dmitry Zhuk
# copyright: Copyright (C) 2011 zhuk.cc. All Rights Reserved.
# license:   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
# website:   http://zhuk.cc
# Technical Support Forum: http://forum.zhuk.cc/
-------------------------------------------------------------------------*/
// No direct access to this file
defined('_JEXEC') or die('Restricted access');


$document	= JFactory::getDocument();

$componentApiVersion = comZhYandexMapData::getMapAPIVersion();

if ($componentApiVersion == "")
{
	$componentApiVersion = '2.x';
}

if ($componentApiVersion == '2.x'
    || $componentApiVersion == '2.0.x'
    || $componentApiVersion == '2.1.x')
{
	// ***** Init Section Begin ***********************************
		$MapXsuffix = "ZhYMMOD";
		
		$use_object_manager = 0;
		
		$current_custom_js_path = JURI::root() .'components/com_zhyandexmap/assets/js/';			

	    $useObjectStructure = 0;	
		
	// ***** Init Section End *************************************


	$id = $params->get('mapid', '');

	$map = comZhYandexMapData::getMap((int)$id);

	// Change translation language and load translation
	$currentLanguage = JFactory::getLanguage();
	$currentLangTag = $currentLanguage->getTag();
	if (isset($map->lang) && $map->lang != "")
	{

		$currentLanguage->load('com_zhyandexmap', JPATH_SITE . '/components/com_zhyandexmap', $map->lang, true);	

		$currentLanguage->load('mod_zhyandexmap', JPATH_SITE, $map->lang, true);	

	}
	else
	{

		$currentLanguage->load('com_zhyandexmap', JPATH_SITE . '/components/com_zhyandexmap', $currentLangTag, true);	

		$currentLanguage->load('mod_zhyandexmap', JPATH_SITE, $currentLangTag, true);	
		
	}	
	
	if (isset($map) && (int)$map->id != 0)
	{

	// ***** Settings Begin *************************************
	
	// ***** Settings End ***************************************

			$centerplacemarkid = '';
			$centerplacemarkactionid = '';
			$centerplacemarkaction = '';
			$externalmarkerlink = 0;

			$placemarklistid = $params->get('placemarklistid', '');
			$explacemarklistid = '';
			$grouplistid = '';
			$categorylistid = '';
			//
			// Pass it but not use there (only in query)
			$routelistid = '';
			$exroutelistid = '';
			$routecategorylistid = '';
			$pathlistid = '';
			$expathlistid = '';
			$pathgrouplistid = '';
			$pathcategorylistid = '';
			//
			
			// under construction ///////////////////////
			$map->markerspinner = 0;
			$map->placemark_rating = 0;
			$map->markergroupctlpath = 0;
			$map->markergroupctlmarker = 1;
			$map->markergrouptype = 0;
			$map->showcreateinfo = 0;
			$map->markerorder = 0;
			$map->markergrouporder = 0;
			
			$usermarkersfilter = "";
			
			// //////////////////////////////////////////

			if ($usermarkersfilter == "")
			{
				$usermarkersfilter = (int)$map->usermarkersfilter;
			}
			else
			{
				$usermarkersfilter = (int)$usermarkersfilter;
			}
			
			
			$markers = comZhYandexMapData::getMarkers($map->id, $placemarklistid, $explacemarklistid, $grouplistid, $categorylistid, 
													  $map->usermarkers, $usermarkersfilter, $map->usercontact, $map->markerorder);
			$paths = comZhYandexMapData::getPaths($map->id, "","","","");
			$routers = comZhYandexMapData::getRouters($map->id, "","","","");
			$maptypes = comZhYandexMapData::getMapTypes();

			$markergroups = comZhYandexMapData::getMarkerGroups($map->id, $placemarklistid, $explacemarklistid, $grouplistid, $categorylistid, $map->markergrouporder);
			$mgrgrouplist = $markergroups;



			
			// -- -- extending ------------------------------------------
			// class suffix, for example for module use
			$cssClassSuffix = $params->get('moduleclass_sfx', '');
			

			// -- -- -- component options - begin -----------------------
			
			$httpsprotocol = comZhYandexMapData::getHttpsProtocol();
			$urlProtocol = 'http';
			if ($httpsprotocol != "")
			{
				if ((int)$httpsprotocol == 1)
				{
					$urlProtocol = 'https';
				}
			}
						
			
			switch ($componentApiVersion)
			{
			    case "2.0.x": 
				$mapVersion = "2.0"; 
				break;
			    case "2.1.x": 
				$mapVersion = "2.1"; 
				$urlProtocol = 'https';
				break;			    
			    case "2.x": 
				$mapVersion = "2.1"; 
				$urlProtocol = 'https';
				break;
			    default:
				$mapVersion = "2.1"; 
				$urlProtocol = 'https';
				break;   
			}
			

			$compatiblemode = comZhYandexMapData::getCompatibleMode();
			$compatiblemodersf = comZhYandexMapData::getCompatibleModeRSF();

			$licenseinfo = comZhYandexMapData::getMapLicenseInfo();
			
			$apikey = comZhYandexMapData::getMapAPIKey();
                        
                        $placemarkTitleTag = comZhYandexMapData::getPlacemarkTitleTag();

						
			$mapMapWidth = "";

			$mapMapHeight = "";	
			// -- -- -- component options - end -------------------------

	// ***** Settings End ***************************************
	
	
		
		require_once(JPATH_SITE.'/components/com_zhyandexmap/views/zhyandexmap/tmpl/v2x_display_map_data.php');

		
		require_once(JPATH_SITE.'/components/com_zhyandexmap/views/zhyandexmap/tmpl/v2x_display_script.php');
		
	}
	else
	{
	  echo JText::_( 'MOD_ZHYANDEXMAP_MAP_NOTFIND_ID' ).' '. $id;
	}	
		
}
else
{
	// require_once(JPATH_SITE.'/components/com_zhyandexmap/views/zhyandexmap/tmpl/v1x.php');
	require_once(JPATH_SITE.'/modules/mod_zhyandexmap/tmpl/v1x.php');
}
