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

$document	= JFactory::getDocument();

$componentApiVersion = $this->mapapiversion;
if ($componentApiVersion == "")
{
	$componentApiVersion = '2.x';
}

if ($componentApiVersion == '2.x'
    || $componentApiVersion == '2.0.x'
    || $componentApiVersion == '2.1.x')
{
	// ***** Init Section Begin ***********************************
		$this->MapXsuffix = "ZhYMCOM";
		
		$this->use_object_manager = 0;
		
		$this->current_custom_js_path = JURI::root() .'components/com_zhyandexmap/assets/js/';	
		$current_custom_js_path = $this->current_custom_js_path;			
	// ***** Init Section End *************************************


	// ***** Settings Begin *************************************

	$map = $this->item;

	$markers = $this->markers;
	$paths = $this->paths;
	$routers = $this->routers;
	$maptypes = $this->maptypes;
	
	$markergroups = $this->markergroups;
	$mgrgrouplist = $markergroups;
	
	$centerplacemarkid = '';
	$centerplacemarkactionid = '';
	$centerplacemarkaction = '';
	$externalmarkerlink = (int)$this->externalmarkerlink;

	$placemarklistid = '';
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
	$this->usermarkersfilter = "";
	
	// //////////////////////////////////////////
	
	
	// addition parameters
	if ($this->usermarkersfilter == "")
	{
		$usermarkersfilter = (int)$map->usermarkersfilter;
	}
	else
	{
		$usermarkersfilter = (int)$this->usermarkersfilter;
	}
	
	$placemarkTitleTag = $this->placemarktitletag;
	
	$this->useObjectStructure = 1;
	$useObjectStructure = $this->useObjectStructure;
	
	
	// -- -- extending ------------------------------------------
	// class suffix, for example for module use
	$cssClassSuffix = "";

	// -- -- -- component options - begin -----------------------
	$this->urlProtocol = "http";
	if ($this->httpsprotocol != "")
	{
		if ((int)$this->httpsprotocol == 1)
		{
			$this->urlProtocol = 'https';
		}
	}	

	
	switch ($componentApiVersion)
	{
	    case "2.0.x": 
		$mapVersion = "2.0"; 
		break;
	    case "2.1.x": 
		$mapVersion = "2.1"; 
		$this->urlProtocol = "https";
		break;			    
	    case "2.x": 
		$mapVersion = "2.1"; 
		$this->urlProtocol = "https";
		break;
	    default:
		$mapVersion = "2.1"; 
		$this->urlProtocol = "https";
		break;   
	}

	$compatiblemode = $this->mapcompatiblemode;
	$compatiblemodersf = $this->mapcompatiblemodersf;

	$licenseinfo = $this->licenseinfo;
	
	$apikey = $this->mapapikey;
	
	$urlProtocol = $this->urlProtocol;	

        $mapMapWidth = "";

        $mapMapHeight = "";
                        
	// -- -- -- component options - end -------------------------

	// ***** Settings End ***************************************
	
	
	require_once(JPATH_SITE.'/components/com_zhyandexmap/views/zhyandexmap/tmpl/v2x_display_map_data.php');

		
	$use_object_manager = $this->use_object_manager;

	$useObjectStructure = $this->useObjectStructure;

	$current_custom_js_path = $this->current_custom_js_path;	
	
	require_once(JPATH_SITE.'/components/com_zhyandexmap/views/zhyandexmap/tmpl/v2x_display_script.php');
	
}
else
{
	require_once(JPATH_SITE.'/components/com_zhyandexmap/views/zhyandexmap/tmpl/v1x.php');
}


