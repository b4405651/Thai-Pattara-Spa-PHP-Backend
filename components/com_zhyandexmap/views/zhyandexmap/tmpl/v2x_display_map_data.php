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

$divmapheader ="";
$divmapfooter ="";

$currentUserInfo ="";
$allowUserMarker = 0;
$currentUserID = 0;

$scripttext = '';
$scripttextBegin = '';
$scripttextEnd = '';

$scripthead ="";

// Change translation language and load translation
$currentLanguage = JFactory::getLanguage();
$currentLangTag = $currentLanguage->getTag();

if (isset($map->lang) && $map->lang != "")
{
	$currentLanguage->load('com_zhyandexmap', JPATH_SITE, $map->lang, true);	
	$currentLanguage->load('com_zhyandexmap', JPATH_COMPONENT, $map->lang, true);	
	
	// fix translation problem on plugin call
	$currentLanguage->load('com_zhyandexmap', JPATH_SITE . '/components/com_zhyandexmap' , $map->lang, true);	
	
}
else
{
	$currentLanguage->load('com_zhyandexmap', JPATH_SITE, $currentLangTag, true);	
	$currentLanguage->load('com_zhyandexmap', JPATH_COMPONENT, $currentLangTag, true);		
	$currentLanguage->load('com_zhyandexmap', JPATH_SITE . '/components/com_zhyandexmap' , $currentLangTag, true);	
	
}

require_once JPATH_SITE . '/components/com_zhyandexmap/helpers/placemarks.php';


if (isset($MapXdoLoad) && ((int)$MapXdoLoad == 0))
{
	// all OK
	if ((int)$MapXdoLoad == 0)
	{   // ***** Plugin call *****
		//   hide loading call
		//   but passing composite ID
		if (isset($MapXArticleId) && ($MapXArticleId != ""))
		{
			$mapInitTag = $MapXArticleId;
			// Map DIV suffix
			$mapDivSuffix = '_'.$MapXArticleId;
		}
		else
		{
			if (isset($MapXsuffix) && ($MapXsuffix != ""))
			{
				$mapInitTag = $MapXsuffix;
				$mapDivSuffix = "";
			}
			else
			{
				$mapInitTag = "";
				$mapDivSuffix = "";
			}
		}
	}
	else
	{
		if (isset($MapXsuffix) && ($MapXsuffix != ""))
		{
			$mapInitTag = $MapXsuffix;
			$mapDivSuffix = "";
		}
		else
		{
			$mapInitTag = "";
			$mapDivSuffix = "";
		}
	}

}
else
{
	$MapXdoLoad = 1;

	if (isset($MapXsuffix) && ($MapXsuffix != ""))
	{
		$mapInitTag = $MapXsuffix;
		$mapDivSuffix = "";
	}
	else
	{
		$mapInitTag = "";
		$mapDivSuffix = "";
	}
}


$credits ='';

if (isset($placemarkTitleTag) && $placemarkTitleTag != "")
{
	if ($placemarkTitleTag == "h2"
	 || $placemarkTitleTag == "h3")
	{
		// it's OK. Do not change it
		//$placemarkTitleTag = $placemarkTitleTag;
	}
	else
	{
		$placemarkTitleTag ='h2';
	}
}
else
{
	$placemarkTitleTag ='h2';
}

if ($compatiblemodersf == "")
{
  $compatiblemodersf = 0;
}

if ($compatiblemode == "")
{
  $compatiblemode = 0;
}

if ($compatiblemodersf == 0)
{
	$document->addStyleSheet(JURI::root() .'administrator/components/com_zhyandexmap/assets/css/common.css');
}
else
{
	$document->addStyleSheet(JURI::root() .'components/com_zhyandexmap/assets/css/common.css');
}


if (isset($map->markerlistpos) && (int)$map->markerlistpos != 0) 
{
	$placemarkSearch = (int)$map->markerlistsearch;	
}
else
{
	$placemarkSearch = 0;
}


if ($licenseinfo == "")
{
  $licenseinfo = 102;
}

	
$custMapTypeList = explode(";", $map->custommaptypelist);
if (count($custMapTypeList) != 0)
{
	$custMapTypeFirst = $custMapTypeList[0];
}
else
{
	$custMapTypeFirst = 0;
}

if (isset($map->usermarkers) && (int)$map->usermarkers == 1) 
{
    $currentUser = JFactory::getUser();

    if ($currentUser->id == 0)
    {
		$currentUserInfo .= JText::_( 'COM_ZHYANDEXMAP_MAP_USER_NOTLOGIN' );
		$allowUserMarker = 0;
		$currentUserID = 0;

    }
    else
    {
		$currentUserInfo .= JText::_( 'COM_ZHYANDEXMAP_MAP_USER_LOGIN' ) .' '. $currentUser->name;
		$allowUserMarker = 1;
		$currentUserID = $currentUser->id;
    }
    
}
else
{
	$allowUserMarker = 0;
	$currentUserID = 0;
}


// if post data to load
if ($allowUserMarker == 1
 && isset($_POST['marker_action']))
{		
$scripttext .= '<script type="text/javascript">';
		$db = JFactory::getDBO();

		if (isset($_POST['marker_action']) && 
			($_POST['marker_action'] == "insert") ||
			($_POST['marker_action'] == "update") 
			)
		{

			$title = substr($_POST["markername"], 0, 249);
			if ($title == "")
			{
				$title = 'Placemark';
			}

			$markericon = substr($_POST["markerimage"], 0, 100);
			if ($markericon == "")
			{
				$markericon ='default#';
			}
			
			$description = $_POST["markerdescription"];
			$latitude = substr($_POST["markerlat"], 0, 100);
			$longitude = substr($_POST["markerlng"], 0, 100);

			$markerid = (int)substr($_POST["markerid"], 0, 100);
			$markerhrefimage = substr($_POST["markerhrefimage"], 0, 500);
			
			if (isset($map->showbelong) && (int)$map->showbelong == 1)
			{
				$group = substr($_POST["markergroup"], 0, 100);
				$markercatid = substr($_POST["markercatid"], 0, 100);
			}
			else
			{
				$group = '';
				$markercatid = '';
			}
			
			if (isset($map->usercontact) && (int)$map->usercontact == 1) 
			{
				$contactid = substr($_POST["contactid"], 0, 100);
			}
			else
			{
				$contactid = '';
			}
			
			$contactDoInsert = 0;
			
			if (isset($map->usercontact) && (int)$map->usercontact == 1) 
			{
				$contact_name = substr($_POST["contactname"], 0, 100);
				$contact_position = substr($_POST["contactposition"], 0, 100);
				$contact_phone = substr($_POST["contactphone"], 0, 100);
				$contact_mobile = substr($_POST["contactmobile"], 0, 100);
				$contact_fax = substr($_POST["contactfax"], 0, 100);
				$contact_address = substr($_POST["contactaddress"], 0, 100);
				
				if (($contact_name != "") 
				  ||($contact_position != "")
				  ||($contact_phone != "")
				  ||($contact_mobile != "")
				  ||($contact_fax != "")
				  ||($contact_address != "")
					)
				{
					$contactDoInsert = 1;
				}
			}

			$newRow = new stdClass;
			
			if ($_POST['marker_action'] == "insert")
			{
				$newRow->id = NULL;
				$newRow->userprotection = 0;
				$newRow->actionbyclick = 1;
				
				if ((isset($map->usercontact) && (int)$map->usercontact == 1) 
				 &&($contactDoInsert == 1))
				{				
					$newRow->showcontact = 2;
				}
				else
				{				
					$newRow->showcontact = 0;
				}
			}
			else
			{
				$newRow->id = $markerid;

				if ((isset($map->usercontact) && (int)$map->usercontact == 1) 
				 &&($contactDoInsert == 1) && ((int)$contactid == 0))
				{				
					$newRow->showcontact = 2;
				}
				
			}
			
			// Data for Contacts - begin
			if ((isset($map->usercontact) && (int)$map->usercontact == 1) 
			  &&($contactDoInsert == 1))
			{
				$newContactRow = new stdClass;
				
				if ($_POST['marker_action'] == "insert")
				{
					$newContactRow->id = NULL;
					$newContactRow->published = (int)$map->usercontactpublished;
					$newContactRow->language = '*';
					$newContactRow->access = 1;
				}
				else
				{
					if ((int)$contactid == 0)
					{
						$newContactRow->id = NULL;
						$newContactRow->published = (int)$map->usercontactpublished;
						$newContactRow->language = '*';
						$newContactRow->access = 1;
					}
					else
					{
						$newContactRow->id = $contactid;
					}
				}
				
			}			
			// Data for Contacts - end
			
			// because it (quotes) escaped
			$newRow->title = str_replace('\\','', htmlspecialchars($title, ENT_NOQUOTES, 'UTF-8'));
			$newRow->description = str_replace('\\','', htmlspecialchars($description, ENT_NOQUOTES, 'UTF-8'));
			// because it escaped
			$newRow->latitude = htmlspecialchars($latitude, ENT_QUOTES, 'UTF-8');
			$newRow->longitude = htmlspecialchars($longitude, ENT_QUOTES, 'UTF-8');
			$newRow->mapid = $map->id;
			$newRow->icontype = htmlspecialchars($markericon, ENT_QUOTES, 'UTF-8');
			
			if ($_POST['marker_action'] == "insert") {
				$newRow->published = (int)$map->usermarkerspublished;
				$newRow->createdbyuser = $currentUser->id;
			} else {
				// do not change state
			}			


			if (isset($map->showbelong) && (int)$map->showbelong == 1)
			{
				$newRow->markergroup = htmlspecialchars($group, ENT_QUOTES, 'UTF-8');
				$newRow->catid = htmlspecialchars($markercatid, ENT_QUOTES, 'UTF-8');
			}
			
			$newRow->hrefimage = htmlspecialchars($markerhrefimage, ENT_QUOTES, 'UTF-8');

			if ((isset($map->usercontact) && (int)$map->usercontact == 1) 
			  &&($contactDoInsert == 1))
			{
				$newContactRow->name = str_replace('\\','', htmlspecialchars($contact_name, ENT_NOQUOTES, 'UTF-8'));
				if ($newContactRow->name == "")
				{
					$newContactRow->name = $newRow->title;
				}
				$newContactRow->con_position = str_replace('\\','', htmlspecialchars($contact_position, ENT_NOQUOTES, 'UTF-8'));
				$newContactRow->telephone = str_replace('\\','', htmlspecialchars($contact_phone, ENT_NOQUOTES, 'UTF-8'));
				$newContactRow->mobile = str_replace('\\','', htmlspecialchars($contact_mobile, ENT_NOQUOTES, 'UTF-8'));
				$newContactRow->fax = str_replace('\\','', htmlspecialchars($contact_fax, ENT_NOQUOTES, 'UTF-8'));
				$newContactRow->address = str_replace('\\','', htmlspecialchars($contact_address, ENT_NOQUOTES, 'UTF-8'));
			}
			
			if ($_POST['marker_action'] == "insert")
			{
				if ((isset($map->usercontact) && (int)$map->usercontact == 1) 
				  &&($contactDoInsert == 1))
				{
					$dml_contact_result = $db->insertObject( '#__contact_details', $newContactRow, 'id' );
					
					$newRow->contactid = $newContactRow->id;
				}

				$dml_result = $db->insertObject( '#__zhyandexmaps_markers', $newRow, 'id' );
			}
			else
			{
				if ((isset($map->usercontact) && (int)$map->usercontact == 1) 
				  &&($contactDoInsert == 1))
				{
					if (isset($newContactRow->id))
					{
						$dml_contact_result = $db->updateObject( '#__contact_details', $newContactRow, 'id' );
					}
					else
					{
						$dml_contact_result = $db->insertObject( '#__contact_details', $newContactRow, 'id' );
						$newRow->contactid = $newContactRow->id;
					}
				}

				$dml_result = $db->updateObject( '#__zhyandexmaps_markers', $newRow, 'id' );
			}
			
			if ((!$dml_result) || 
			    (isset($map->usercontact) && (int)$map->usercontact == 1 && ($contactDoInsert == 1) && (!$dml_result))
				)
			{
				//$this->setError($db->getErrorMsg());
				$scripttext .= 'alert("Error (Insert New Marker or Update): " + "' . $db->escape($db->getErrorMsg()).'");';
			}
			else
			{
				$scripttext .= 'window.location = "'.JURI::current().'";'."\n";
				
				$new_id = $newRow->id;

			}
		}
		else if (isset($_POST['marker_action']) && $_POST['marker_action'] == "delete") 
		{

			$contactid = substr($_POST["contactid"], 0, 100);
			$markerid = substr($_POST["markerid"], 0, 100);
		
			if (isset($map->usercontact) && (int)$map->usercontact == 1) 
			{
			
				if ((int)$contactid != 0)
				{
					$query = $db->getQuery(true);

					$db->setQuery( 'DELETE FROM `#__contact_details` '.
					'WHERE `id`='.(int)$contactid);
					
					if (!$db->query()) {
						//$this->setError($db->getErrorMsg());
						$scripttext .= 'alert("Error (Delete Exist Marker Contact): " + "' . $db->escape($db->getErrorMsg()).'");';
					}
				}
			}


			$query = $db->getQuery(true);

			$db->setQuery( 'DELETE FROM `#__zhyandexmaps_markers` '.
			'WHERE `createdbyuser`='.$currentUser->id.
			' and `id`='.$markerid);

			
			if (!$db->query()) {
				//$this->setError($db->getErrorMsg());
				$scripttext .= 'alert("Error (Delete Exist Marker): " + "' . $db->escape($db->getErrorMsg()).'");';
			}
			else
			{
				$scripttext .= 'window.location = "'.JURI::current().'";'."\n";
			}
		}

$scripttext .= '</script>';


echo $scripttext;
}
else
{
// main part where not post data


	if (isset($map->lang) && ($map->lang != ""))
	{
		$apilang = $map->lang;
	}
	else
	{
		$apilang = 'ru-RU';
	}
	
	$scriptlink	= $urlProtocol.'://api-maps.yandex.ru/'.$mapVersion.'/?coordorder=longlat&amp;load=package.full&amp;lang='.$apilang;
	$loadmodules	='';
	$loadmodules_pmap = 0;

	if ($compatiblemodersf == 0)
	{
		$directoryIcons = 'administrator/components/com_zhyandexmap/assets/icons/';
		$imgpathIcons = JURI::root() .'administrator/components/com_zhyandexmap/assets/icons/';
		$imgpathUtils = JURI::root() .'administrator/components/com_zhyandexmap/assets/utils/';	
		$imgpath4size = JPATH_ADMINISTRATOR .'/components/com_zhyandexmap/assets/icons/';		
	}
	else
	{
		$directoryIcons = 'components/com_zhyandexmap/assets/icons/';
		$imgpathIcons = JURI::root() .'components/com_zhyandexmap/assets/icons/';
		$imgpathUtils = JURI::root() .'components/com_zhyandexmap/assets/utils/';		
		$imgpath4size = JPATH_SITE .'/components/com_zhyandexmap/assets/icons/';
	}


$fullWidth = 0;
$fullHeight = 0;

// Size Value 
$currentMapWidth ="do not change";
$currentMapHeight ="do not change";

$currentPlacemarkCenter = "do not change";


if ($centerplacemarkid != "")
{
	$currentPlacemarkCenter = $centerplacemarkid;
        
}

if ($mapMapWidth != "")
{
	$currentMapWidth = $mapMapWidth;
}

if ($mapMapHeight != "")
{
	$currentMapHeight = $mapMapHeight;
}

$zhymObjectManager = 0;
$ajaxLoadContent = 0;
$ajaxLoadScripts = 0;

$ajaxLoadObjects = (int)$map->useajaxobject;

$ajaxLoadObjectType = (int)$map->ajaxgetplacemark;

$featureSpider = (int)$map->markerspinner;


if (
 (isset($map->useajax) && ((int)$map->useajax !=0))
 || 
 (isset($map->placemark_rating) && ((int)$map->placemark_rating != 0))
)
{
	$ajaxLoadContent = 1;
}

if ($ajaxLoadObjects != 0)
{
	$ajaxLoadScripts = 1;
}



if ((int)$placemarkSearch != 0)
{
	$document->addStyleSheet(JURI::root() .'components/com_zhyandexmap/assets/jquery-ui/1.11.4/jquery-ui.min.css');
	$document->addScript(JURI::root() .'components/com_zhyandexmap/assets/jquery-ui/1.11.4/jquery-ui.min.js');
}

if (  ($ajaxLoadObjects != 0)
   || ($ajaxLoadContent != 0)
   || ($placemarkSearch != 0)           
   || ($featureSpider != 0)
   || ((int)$placemarkSearch != 0)
   || (isset($map->markergroupcontrol) && (int)$map->markergroupcontrol != 0)
   || (isset($map->markercluster) && (int)$map->markercluster == 1)
   || (isset($map->mapbounds) && $map->mapbounds != "")
   )
{
	$zhymObjectManager = 1;
}




$document->addScript($current_custom_js_path.'common-min.js');

if ($zhymObjectManager != 0)
{
		if (isset($useObjectStructure) && (int)$useObjectStructure == 1)
		{
			$this->use_object_manager = 1;
		}
		else
		{
			$use_object_manager = 1;
		}
}

if ((int)$map->useruser != 0)
{
				$returnText .= comZhYandexMapPlacemarksHelper::get_userinfo_for_marker(
														$currentmarker->createdbyuser, $currentmarker->showuser,
														$imgpathIcons, $imgpathUtils, $directoryIcons);
}



if (isset($map->css2load) && ($map->css2load != ""))
{
	$loadCSSList = explode(';', str_replace(array("\r", "\r\n", "\n"), ';', $map->css2load));


	for($i = 0; $i < count($loadCSSList); $i++) 
	{
		$currCSS = trim($loadCSSList[$i]);
		if ($currCSS != "")
		{
			$document->addStyleSheet($currCSS);
		}
	}
}


// Overrides - begin
//if (isset($map->override_id) && (int)$map->override_id != 0) 
//{
	//$fv_override = comZhYandexMapPlacemarksHelper::get_MapOverrides($map->override_id);
	//if (isset($fv_override))
	//{
 
            //if ((isset($fv_override->placemark_list_title) && $fv_override->placemark_list_title != ""))
            //{
            //        $fv_override_placemark_title = $fv_override->placemark_list_title;
            //}
            //else
            //{
                    $fv_override_placemark_title = JText::_( 'COM_ZHYANDEXMAP_MARKERLIST_SEARCH_FIELD');
            //}

        //}
        
//}


// Overrides - end
		
if (isset($map->usermarkers) && (int)$map->usermarkers == 1) 
{
	if ($compatiblemodersf == 0)
	{
		$document->addStyleSheet(JURI::root() .'administrator/components/com_zhyandexmap/assets/css/usermarkers.css');
	}
	else
	{
		$document->addStyleSheet(JURI::root() .'components/com_zhyandexmap/assets/css/usermarkers.css');
	}
}



if ($map->headerhtml != "")
{
        $divmapheader .= '<div id="YMapInfoHeader'.$mapDivSuffix.'">'.$map->headerhtml;
        if (isset($map->headersep) && (int)$map->headersep == 1) 
        {
            $divmapheader .= '<hr id="mapHeaderLine" />';
        }
        $divmapheader .= '</div>';
}

$divmap = "";

$divmapbefore = "";
$divmapafter = "";

if (isset($map->findcontrol) && (int)$map->findcontrol == 1)
{
	if (isset($map->findroute) && (int)$map->findroute != 0)
	{
		$service_DoDirection = 1;
	}
	else
	{
		$service_DoDirection = 0;
	}
}
else
{
	$service_DoDirection = 0;
}

if (isset($map->findcontrol) && (int)$map->findcontrol == 1) 
{
	switch ((int)$map->findpos) 
	{
		
		case 0:
			$divmapbefore .= '<div id="YMapFindAddress'.$mapDivSuffix.'">'."\n";
			$divmapbefore .= '<form action="#" onsubmit="showAddressByGeocoding'.$mapDivSuffix.'(this.findAddressField'.$mapDivSuffix.'.value);return false;">'."\n";
			//$divmapbefore .= '<p>'."\n";
            $divmapbefore .= '<input id="findAddressField'.$mapDivSuffix.'" type="text" value=""';
			if (isset($map->findwidth) && (int)$map->findwidth != 0)
			{
				$divmapbefore .= ' size="'.(int)$map->findwidth.'"';
			}
			$divmapbefore .=' />';
			
			$divmapbefore .= '<input id="findAddressButton" type="submit" value="';
			if (isset($map->findroute) && (int)$map->findroute == 1) 
			{
				$divmapbefore .= JText::_( 'COM_ZHYANDEXMAP_MAP_DOFINDBUTTONROUTE' );
			}
			else
			{
				$divmapbefore .= JText::_( 'COM_ZHYANDEXMAP_MAP_DOFINDBUTTON' );
			}
			$divmapbefore .= '" />'."\n";
			
			//$divmapbefore .= '</p>'."\n";
			$divmapbefore .= '</form>'."\n";
			$divmapbefore .= '</div>'."\n";
		break;
		case 101:
			$divmapbefore .= '<div id="YMapFindAddress'.$mapDivSuffix.'">'."\n";
			$divmapbefore .= '<form action="#" onsubmit="showAddressByGeocoding'.$mapDivSuffix.'(this.findAddressField'.$mapDivSuffix.'.value);return false;">'."\n";
			//$divmapbefore .= '<p>'."\n";
            $divmapbefore .= '<input id="findAddressField'.$mapDivSuffix.'" type="text" value=""';
			if (isset($map->findwidth) && (int)$map->findwidth != 0)
			{
				$divmapbefore .= ' size="'.(int)$map->findwidth.'"';
			}
			$divmapbefore .=' />';
			$divmapbefore .= '<input id="findAddressButton" type="submit" value="';
			if (isset($map->findroute) && (int)$map->findroute == 1) 
			{
				$divmapbefore .= JText::_( 'COM_ZHYANDEXMAP_MAP_DOFINDBUTTONROUTE' );
			}
			else
			{
				$divmapbefore .= JText::_( 'COM_ZHYANDEXMAP_MAP_DOFINDBUTTON' );
			}
			$divmapbefore .= '" />'."\n";
			//$divmapbefore .= '</p>'."\n";
			$divmapbefore .= '</form>'."\n";
			$divmapbefore .= '</div>'."\n";
		break;
		case 102:
			$divmapafter .= '<div id="YMapFindAddress'.$mapDivSuffix.'">'."\n";
			$divmapafter .= '<form action="#" onsubmit="showAddressByGeocoding'.$mapDivSuffix.'(this.findAddressField'.$mapDivSuffix.'.value);return false;">'."\n";
			//$divmapafter .= '<p>'."\n";
            $divmapafter .= '<input id="findAddressField'.$mapDivSuffix.'" type="text" value=""';
			if (isset($map->findwidth) && (int)$map->findwidth != 0)
			{
				$divmapafter .= ' size="'.(int)$map->findwidth.'"';
			}
			$divmapafter .=' />';
			$divmapafter .= '<input id="findAddressButton" type="submit" value="';
			if (isset($map->findroute) && (int)$map->findroute == 1) 
			{
				$divmapafter .= JText::_( 'COM_ZHYANDEXMAP_MAP_DOFINDBUTTONROUTE' );
			}
			else
			{
				$divmapafter .= JText::_( 'COM_ZHYANDEXMAP_MAP_DOFINDBUTTON' );
			}
			$divmapafter .= '" />'."\n";
			//$divmapafter .= '</p>'."\n";
			$divmapafter .= '</form>'."\n";
			$divmapafter .= '</div>'."\n";
		break;
		default:
		break;
	}
}

if (isset($map->autopositioncontrol) && (int)$map->autopositioncontrol == 2) 
{


	switch ((int)$map->autopositionpos) 
	{
		
		case 0:
			$divmapbefore .='<div id="geoLocation'.$mapDivSuffix.'">';
			$divmapbefore .= '  <button id="geoLocationButton'.$mapDivSuffix.'" onclick="findMyPosition'.$mapDivSuffix.'(\'Button\');return false;">';

			switch ((int)$map->autopositionbutton) 
			{
				case 1:
					$divmapbefore .= '<img src="'.$imgpathUtils.'geolocation.png" alt="'.JText::_('COM_ZHYANDEXMAP_MAP_AUTOPOSITIONBUTTON').'" style="vertical-align: middle">';
				break;
				case 2:
					$divmapbefore .= JText::_('COM_ZHYANDEXMAP_MAP_AUTOPOSITIONBUTTON');
				break;
				case 3:
					$divmapbefore .= '<img src="'.$imgpathUtils.'geolocation.png" alt="'.JText::_('COM_ZHYANDEXMAP_MAP_AUTOPOSITIONBUTTON').'" style="vertical-align: middle">';
					$divmapbefore .= JText::_('COM_ZHYANDEXMAP_MAP_AUTOPOSITIONBUTTON');
				break;
				default:
					$divmapbefore .= '<img src="'.$imgpathUtils.'geolocation.png" alt="'.JText::_('COM_ZHYANDEXMAP_MAP_AUTOPOSITIONBUTTON').'" style="vertical-align: middle">';
				break;
			}
			
			$divmapbefore .= '</button>';
			$divmapbefore .='</div>'."\n";
		break;
		case 101:
			$divmapbefore .='<div id="geoLocation">';
			$divmapbefore .= '  <button id="geoLocationButton'.$mapDivSuffix.'" onclick="findMyPosition'.$mapDivSuffix.'(\'Button\');return false;">';

			switch ((int)$map->autopositionbutton) 
			{
				case 1:
					$divmapbefore .= '<img src="'.$imgpathUtils.'geolocation.png" alt="'.JText::_('COM_ZHYANDEXMAP_MAP_AUTOPOSITIONBUTTON').'" style="vertical-align: middle">';
				break;
				case 2:
					$divmapbefore .= JText::_('COM_ZHYANDEXMAP_MAP_AUTOPOSITIONBUTTON');
				break;
				case 3:
					$divmapbefore .= '<img src="'.$imgpathUtils.'geolocation.png" alt="'.JText::_('COM_ZHYANDEXMAP_MAP_AUTOPOSITIONBUTTON').'" style="vertical-align: middle">';
					$divmapbefore .= JText::_('COM_ZHYANDEXMAP_MAP_AUTOPOSITIONBUTTON');
				break;
				default:
					$divmapbefore .= '<img src="'.$imgpathUtils.'geolocation.png" alt="'.JText::_('COM_ZHYANDEXMAP_MAP_AUTOPOSITIONBUTTON').'" style="vertical-align: middle">';
				break;
			}
			
			$divmapbefore .= '</button>';
			$divmapbefore .='</div>'."\n";
		break;
		case 102:
			$divmapafter .='<div id="geoLocation'.$mapDivSuffix.'">';
			$divmapafter .= '  <button id="geoLocationButton'.$mapDivSuffix.'" onclick="findMyPosition'.$mapDivSuffix.'(\'Button\');return false;">';

			switch ((int)$map->autopositionbutton) 
			{
				case 1:
					$divmapafter .= '<img src="'.$imgpathUtils.'geolocation.png" alt="'.JText::_('COM_ZHYANDEXMAP_MAP_AUTOPOSITIONBUTTON').'" style="vertical-align: middle">';
				break;
				case 2:
					$divmapafter .= JText::_('COM_ZHYANDEXMAP_MAP_AUTOPOSITIONBUTTON');
				break;
				case 3:
					$divmapafter .= '<img src="'.$imgpathUtils.'geolocation.png" alt="'.JText::_('COM_ZHYANDEXMAP_MAP_AUTOPOSITIONBUTTON').'" style="vertical-align: middle">';
					$divmapafter .= JText::_('COM_ZHYANDEXMAP_MAP_AUTOPOSITIONBUTTON');
				break;
				default:
					$divmapafter .= '<img src="'.$imgpathUtils.'geolocation.png" alt="'.JText::_('COM_ZHYANDEXMAP_MAP_AUTOPOSITIONBUTTON').'" style="vertical-align: middle">';
				break;
			}
			
			$divmapafter .= '</button>';
			$divmapafter .='</div>'."\n";
		break;
		default:
		break;
	}

	
}



if ($map->footerhtml != "")
{
       $divmapfooter .= '<div id="YMapInfoFooter'.$mapDivSuffix.'">';
        if (isset($map->footersep) && (int)$map->footersep == 1) 
        {
            $divmapfooter .= '<hr id="mapFooterLine" />';
        }
       $divmapfooter .= $map->footerhtml.'</div>';
}


if ($currentMapWidth == "do not change")
{
	$currentMapWidthValue = (int)$map->width;
}
else
{
	$currentMapWidthValue = (int)$currentMapWidth;
}

if ($currentMapHeight == "do not change")
{
	$currentMapHeightValue = (int)$map->height;
}
else
{
	$currentMapHeightValue = (int)$currentMapHeight;
}


if ((!isset($currentMapWidthValue)) || (isset($currentMapWidthValue) && (int)$currentMapWidthValue < 1)) 
{
	$fullWidth = 1;
}
if ((!isset($currentMapHeightValue)) || (isset($currentMapHeightValue) && (int)$currentMapHeightValue < 1)) 
{
	$fullHeight = 1;
}



if (isset($map->markergroupcontrol) && (int)$map->markergroupcontrol != 0) 
{
	if ($compatiblemodersf == 0)
	{
		$document->addStyleSheet(JURI::root() .'administrator/components/com_zhyandexmap/assets/css/markergroups.css');
	}
	else
	{
		$document->addStyleSheet(JURI::root() .'components/com_zhyandexmap/assets/css/markergroups.css');
	}
	

	switch ((int)$map->markergroupcss) 
	{
		
		case 0:
			$markergroupcssstyle = '-simple';
		break;
		case 1:
			$markergroupcssstyle = '-advanced';
		break;
		case 2:
			$markergroupcssstyle = '-external';
		break;
		default:
			$markergroupcssstyle = '-simple';
		break;
	}

	$divmarkergroup = '<div id="YMapsMenu'.$markergroupcssstyle.'" style="margin:0;padding:0;width:100%;">';

        if ($map->markergrouptitle != "")
        {
            $divmarkergroup .= '<div id="groupList'.$mapDivSuffix.'"><h2 id="groupListHeadTitle" class="groupListHead">'.htmlspecialchars($map->markergrouptitle , ENT_QUOTES, 'UTF-8').'</h2></div>';
        }
        
        if ($map->markergroupdesc1 != "")
        {
            $divmarkergroup .= '<div id="groupListBodyTopContent'.$mapDivSuffix.'" class="groupListBodyTop">'.htmlspecialchars($map->markergroupdesc1 , ENT_QUOTES, 'UTF-8').'</div>';
        }

        if (isset($map->markergroupsep1) && (int)$map->markergroupsep1 == 1) 
        {
            $divmarkergroup .= '<hr id="groupListLineTop" />';
        }

        $divmarkergroup .= '<ul id="zhym-menu'.$markergroupcssstyle.'">'."\n";

		/* 19.02.2013 
		   for flexible support group management 
		   and have ability to set off placemarks from group managenent 
		   markergroups changed to mgrgrouplist
		   */
		
        if (isset($mgrgrouplist) && !empty($mgrgrouplist)) 
		{
		
			if (isset($map->markergroupshowiconall) && ((int)$map->markergroupshowiconall!= 100))
			{
				$imgimg1 = $imgpathUtils.'checkbox1.png';
				$imgimg0 = $imgpathUtils.'checkbox0.png';

				switch ((int)$map->markergroupshowiconall) 
				{
					
					case 0:
						$divmarkergroup .= '<li id="li-all" class="zhym-markergroup-group-li-all'.$markergroupcssstyle.'">'."\n";
						$divmarkergroup .= '<div id="zhym-markergroup-all" class="zhym-markergroup-all'.$markergroupcssstyle.'">'."\n";
						$divmarkergroup .= '<div id="zhym-markergroup-div-all" class="zhym-markergroup-div-all'.$markergroupcssstyle.'">'."\n";
						$divmarkergroup .= '<div id="zhym-markergroup-a-all" class="zhym-markergroup-a-all'.$markergroupcssstyle.'"><a id="a-all" href="#" onclick="callShowAllGroup'.$mapDivSuffix.'();return false;" class="zhym-markergroup-link-all'.$markergroupcssstyle.'"><div id="zhym-markergroup-text-all" class="zhym-markergroup-text-all'.$markergroupcssstyle.'">'.JText::_('COM_ZHYANDEXMAP_MAP_DETAIL_MARKERGROUPSHOWICONALL_SHOW').'</div></a></div></div>'."\n";
						$divmarkergroup .= '<div id="zhym-markergroup-div-all" class="zhym-markergroup-div-all'.$markergroupcssstyle.'">'."\n";
						$divmarkergroup .= '<div id="zhym-markergroup-a-all" class="zhym-markergroup-a-all'.$markergroupcssstyle.'"><a id="a-all" href="#" onclick="callHideAllGroup'.$mapDivSuffix.'();return false;" class="zhym-markergroup-link-all'.$markergroupcssstyle.'"><div id="zhym-markergroup-text-all" class="zhym-markergroup-text-all'.$markergroupcssstyle.'">'.JText::_('COM_ZHYANDEXMAP_MAP_DETAIL_MARKERGROUPSHOWICONALL_HIDE').'</div></a></div></div>'."\n";
						$divmarkergroup .= '</div>'."\n";
						$divmarkergroup .= '</li>'."\n";
					break;
					case 1:
						$divmarkergroup .= '<li id="li-all" class="zhym-markergroup-group-li-all'.$markergroupcssstyle.'">'."\n";
						$divmarkergroup .= '<div id="zhym-markergroup-all" class="zhym-markergroup-all'.$markergroupcssstyle.'">'."\n";
						$divmarkergroup .= '<div id="zhym-markergroup-div-all" class="zhym-markergroup-div-all'.$markergroupcssstyle.'">'."\n";
						$divmarkergroup .= '<div id="zhym-markergroup-a-all" class="zhym-markergroup-a-all'.$markergroupcssstyle.'"><a id="a-all" href="#" onclick="callShowAllGroup'.$mapDivSuffix.'();return false;" class="zhym-markergroup-link-all'.$markergroupcssstyle.'"><div id="zhym-markergroup-img-all" class="zhym-markergroup-img-all'.$markergroupcssstyle.'"><img src="'.$imgimg1.'" alt="'.JText::_('COM_ZHYANDEXMAP_MAP_DETAIL_MARKERGROUPSHOWICONALL_SHOW').'" /></div><div id="zhym-markergroup-text-all" class="zhym-markergroup-text-all'.$markergroupcssstyle.'">'.JText::_('COM_ZHYANDEXMAP_MAP_DETAIL_MARKERGROUPSHOWICONALL_SHOW').'</div></a></div></div>'."\n";
						$divmarkergroup .= '<div id="zhym-markergroup-div-all" class="zhym-markergroup-div-all'.$markergroupcssstyle.'">'."\n";
						$divmarkergroup .= '<div id="zhym-markergroup-a-all" class="zhym-markergroup-a-all'.$markergroupcssstyle.'"><a id="a-all" href="#" onclick="callHideAllGroup'.$mapDivSuffix.'();return false;" class="zhym-markergroup-link-all'.$markergroupcssstyle.'"><div id="zhym-markergroup-img-all" class="zhym-markergroup-img-all'.$markergroupcssstyle.'"><img src="'.$imgimg0.'" alt="'.JText::_('COM_ZHYANDEXMAP_MAP_DETAIL_MARKERGROUPSHOWICONALL_HIDE').'" /></div><div id="zhym-markergroup-text-all" class="zhym-markergroup-text-all'.$markergroupcssstyle.'">'.JText::_('COM_ZHYANDEXMAP_MAP_DETAIL_MARKERGROUPSHOWICONALL_HIDE').'</div></a></div></div>'."\n";
						$divmarkergroup .= '</div>'."\n";
						$divmarkergroup .= '</li>'."\n";
					break;
					case 2:
						$divmarkergroup .= '<li id="li-all" class="zhym-markergroup-group-li-all'.$markergroupcssstyle.'">'."\n";
						$divmarkergroup .= '<div id="zhym-markergroup-all" class="zhym-markergroup-all'.$markergroupcssstyle.'">'."\n";
						$divmarkergroup .= '<div id="zhym-markergroup-div-all" class="zhym-markergroup-div-all'.$markergroupcssstyle.'">'."\n";
						$divmarkergroup .= '<div id="zhym-markergroup-a-all" class="zhym-markergroup-a-all'.$markergroupcssstyle.'"><a id="a-all" href="#" onclick="callShowAllGroup'.$mapDivSuffix.'();return false;" class="zhym-markergroup-link-all'.$markergroupcssstyle.'"><div id="zhym-markergroup-img-all" class="zhym-markergroup-img-all'.$markergroupcssstyle.'"><img src="'.$imgimg1.'" alt="'.JText::_('COM_ZHYANDEXMAP_MAP_DETAIL_MARKERGROUPSHOWICONALL_SHOW').'" /></div></a></div></div>'."\n";
						$divmarkergroup .= '<div id="zhym-markergroup-div-all" class="zhym-markergroup-div-all'.$markergroupcssstyle.'">'."\n";
						$divmarkergroup .= '<div id="zhym-markergroup-a-all" class="zhym-markergroup-a-all'.$markergroupcssstyle.'"><a id="a-all" href="#" onclick="callHideAllGroup'.$mapDivSuffix.'();return false;" class="zhym-markergroup-link-all'.$markergroupcssstyle.'"><div id="zhym-markergroup-img-all" class="zhym-markergroup-img-all'.$markergroupcssstyle.'"><img src="'.$imgimg0.'" alt="'.JText::_('COM_ZHYANDEXMAP_MAP_DETAIL_MARKERGROUPSHOWICONALL_HIDE').'" /></div></a></div></div>'."\n";
						$divmarkergroup .= '</div>'."\n";
						$divmarkergroup .= '</li>'."\n";
					break;
					default:
						$divmarkergroup .= '<li id="li-all" class="zhym-markergroup-group-li-all'.$markergroupcssstyle.'">'."\n";
						$divmarkergroup .= '<div id="zhym-markergroup-all" class="zhym-markergroup-all'.$markergroupcssstyle.'">'."\n";
						$divmarkergroup .= '<div id="zhym-markergroup-div-all" class="zhym-markergroup-div-all'.$markergroupcssstyle.'">'."\n";
						$divmarkergroup .= '<div id="zhym-markergroup-a-all" class="zhym-markergroup-a-all'.$markergroupcssstyle.'"><a id="a-all" href="#" onclick="callShowAllGroup'.$mapDivSuffix.'();return false;" class="zhym-markergroup-link-all'.$markergroupcssstyle.'"><div id="zhym-markergroup-text-all" class="zhym-markergroup-text-all'.$markergroupcssstyle.'">'.JText::_('COM_ZHYANDEXMAP_MAP_DETAIL_MARKERGROUPSHOWICONALL_SHOW').'</div></a></div></div>'."\n";
						$divmarkergroup .= '<div id="zhym-markergroup-div-all" class="zhym-markergroup-div-all'.$markergroupcssstyle.'">'."\n";
						$divmarkergroup .= '<div id="zhym-markergroup-a-all" class="zhym-markergroup-a-all'.$markergroupcssstyle.'"><a id="a-all" href="#" onclick="callHideAllGroup'.$mapDivSuffix.'();return false;" class="zhym-markergroup-link-all'.$markergroupcssstyle.'"><div id="zhym-markergroup-text-all" class="zhym-markergroup-text-all'.$markergroupcssstyle.'">'.JText::_('COM_ZHYANDEXMAP_MAP_DETAIL_MARKERGROUPSHOWICONALL_HIDE').'</div></a></div></div>'."\n";
						$divmarkergroup .= '</div>'."\n";
						$divmarkergroup .= '</li>'."\n";
					break;
				}
			}

			
			foreach ($mgrgrouplist as $key => $currentmarkergroup) 
			{
				if (((int)$currentmarkergroup->published == 1) || ($allowUserMarker == 1))
				{
					$imgimg = $imgpathIcons.str_replace("#", "%23", $currentmarkergroup->icontype).'.png';

					$markergroupname ='';
					$markergroupname = 'markergroup'. $currentmarkergroup->id;
					
					$markergroupname_article = 'markergroup'.$mapDivSuffix.'_'. $currentmarkergroup->id;

					if ((int)$currentmarkergroup->activeincluster == 1)
					{
						$markergroupactive = ' active';
					}
					else
					{
						$markergroupactive = '';
					}



					switch ((int)$map->markergroupshowicon) 
					{
						
						case 0:
							$divmarkergroup .= '<li id="li-'.$markergroupname.'" class="zhym-markergroup-group-li'.$markergroupcssstyle.'"><div id="zhym-markergroup-a-'.$markergroupname.'" class="zhym-markergroup-a'.$markergroupcssstyle.'"><a id="a-'.$markergroupname_article.'" href="#" onclick="callToggleGroup'.$mapDivSuffix.'('.$currentmarkergroup->id.');return false;" class="zhym-markergroup-link'.$markergroupcssstyle.$markergroupactive.'"><div id="zhym-markergroup-text-'.$markergroupname.'" class="zhym-markergroup-text'.$markergroupcssstyle.'">'.htmlspecialchars(str_replace('\\', '/',$currentmarkergroup->title), ENT_QUOTES, 'UTF-8').'</div></a></div></li>'."\n";
						break;
						case 1:
							$divmarkergroup .= '<li id="li-'.$markergroupname.'" class="zhym-markergroup-group-li'.$markergroupcssstyle.'"><div id="zhym-markergroup-a-'.$markergroupname.'" class="zhym-markergroup-a'.$markergroupcssstyle.'"><a id="a-'.$markergroupname_article.'" href="#" onclick="callToggleGroup'.$mapDivSuffix.'('.$currentmarkergroup->id.');return false;" class="zhym-markergroup-link'.$markergroupcssstyle.$markergroupactive.'"><div id="zhym-markergroup-img-'.$markergroupname.'" class="zhym-markergroup-img'.$markergroupcssstyle.'"><img src="'.$imgimg.'" alt="" /></div><div id="zhym-markergroup-text-'.$markergroupname.'" class="zhym-markergroup-text'.$markergroupcssstyle.'">'.htmlspecialchars(str_replace('\\', '/',$currentmarkergroup->title), ENT_QUOTES, 'UTF-8').'</div></a></div></li>'."\n";
						break;
						case 2:
							$divmarkergroup .= '<li id="li-'.$markergroupname.'" class="zhym-markergroup-group-li'.$markergroupcssstyle.'"><div id="zhym-markergroup-a-'.$markergroupname.'" class="zhym-markergroup-a'.$markergroupcssstyle.'"><a id="a-'.$markergroupname_article.'" href="#" onclick="callToggleGroup'.$mapDivSuffix.'('.$currentmarkergroup->id.');return false;" class="zhym-markergroup-link'.$markergroupcssstyle.$markergroupactive.'"><div id="zhym-markergroup-img-'.$markergroupname.'" class="zhym-markergroup-img'.$markergroupcssstyle.'"><img src="'.$imgimg.'" alt="" /></div></a></div></li>'."\n";
						break;
						default:
							$divmarkergroup .= '<li id="li-'.$markergroupname.'" class="zhym-markergroup-group-li'.$markergroupcssstyle.'"><div id="zhym-markergroup-a-'.$markergroupname.'" class="zhym-markergroup-a'.$markergroupcssstyle.'"><a id="a-'.$markergroupname_article.'" href="#" onclick="callToggleGroup'.$mapDivSuffix.'('.$currentmarkergroup->id.');return false;" class="zhym-markergroup-link'.$markergroupcssstyle.$markergroupactive.'"><div id="zhym-markergroup-text-'.$markergroupname.'" class="zhym-markergroup-text'.$markergroupcssstyle.'">'.htmlspecialchars(str_replace('\\', '/',$currentmarkergroup->title), ENT_QUOTES, 'UTF-8').'</div></a></div></li>'."\n";
						break;
					}

				}
			}
		}

        $divmarkergroup .= '</ul>'."\n";

        if (isset($map->markergroupsep2) && (int)$map->markergroupsep2 == 1) 
        {
            $divmarkergroup .= '<hr id="groupListLineBottom" />';
        }
        
        if ($map->markergroupdesc2 != "")
        {
            $divmarkergroup .= '<div id="groupListBodyBottomContent" class="groupListBodyBottom">'.htmlspecialchars($map->markergroupdesc2 , ENT_QUOTES, 'UTF-8').'</div>';
        }
        

	$divmarkergroup .= '</div>'."\n";
}


	$divwrapmapstyle = '';
	$divtabcolmapstyle = '';
	
	if ($fullWidth == 1)
	{
		$divwrapmapstyle .= 'width:100%;';
	}
	if ($fullHeight == 1)
	{
		$divwrapmapstyle .= 'height:100%;';
		$divtabcolmapstyle .= 'height:100%;';
	}
	if ($divwrapmapstyle != "")
	{
		$divwrapmapstyle = 'style="'.$divwrapmapstyle.'"';
	}
	if ($divtabcolmapstyle != "")
	{
		$divtabcolmapstyle = 'style="'.$divtabcolmapstyle.'"';
	}

// adding markerlist (div)
if (isset($map->markerlistpos) && (int)$map->markerlistpos != 0) 
{

	if ($compatiblemodersf == 0)
	{
		$document->addStyleSheet(JURI::root() .'administrator/components/com_zhyandexmap/assets/css/markerlists.css');
	}
	else
	{
		$document->addStyleSheet(JURI::root() .'components/com_zhyandexmap/assets/css/markerlists.css');
	}
	
	
	switch ((int)$map->markerlist) 
	{
		
		case 0:
			$markerlistcssstyle = 'markerList-simple';
		break;
		case 1:
			$markerlistcssstyle = 'markerList-advanced';
		break;
		case 2:
			$markerlistcssstyle = 'markerList-external';
		break;
		default:
			$markerlistcssstyle = 'markerList-simple';
		break;
	}


	$markerlistAddStyle ='';
	
	if ($map->markerlistbgcolor != "")
	{
		$markerlistAddStyle .= ' background: '.$map->markerlistbgcolor.';';
	}
	
	if ((int)$map->markerlistwidth == 0)
	{
		if ((int)$map->markerlistpos == 113
		  ||(int)$map->markerlistpos == 114
		  ||(int)$map->markerlistpos == 121)
		{
			$divMarkerlistWidth = '100%';
		}
		else
		{
			$divMarkerlistWidth = '200px';
		}
	}
	else
	{
		$divMarkerlistWidth = $map->markerlistwidth;
		$divMarkerlistWidth = $divMarkerlistWidth. 'px';
	}


	if ((int)$map->markerlistpos == 111
	  ||(int)$map->markerlistpos == 112)
	{
		if ($fullHeight == 1)
		{
			$divMarkerlistHeight = '100%';
		}
		else
		{
			$divMarkerlistHeight = $currentMapHeightValue;
			$divMarkerlistHeight = $divMarkerlistHeight. 'px';
		}
	}
	else
	{
		if ((int)$map->markerlistheight == 0)
		{
			$divMarkerlistHeight = 200;
		}
		else
		{
			$divMarkerlistHeight = $map->markerlistheight;
		}
		$divMarkerlistHeight = $divMarkerlistHeight. 'px';
	}		
	
	if ((int)$map->markerlistcontent < 100) 
	{
		$markerlisttag = '<ul id="YMapsMarkerUL'.$mapDivSuffix.'" class="zhym-ul-'.$markerlistcssstyle.'"></ul>';
	}
	else 
	{
		$markerlisttag =  '<table id="YMapsMarkerTABLE'.$mapDivSuffix.'" class="zhym-ul-table-'.$markerlistcssstyle.'" ';
		if (((int)$map->markerlistpos == 113) 
		|| ((int)$map->markerlistpos == 114) 
		|| ((int)$map->markerlistpos == 121))
		{
			if ($fullWidth == 1) 
			{
				$markerlisttag .= 'style="width:100%;" ';
			}
		}
		$markerlisttag .= '>';
		$markerlisttag .= '<tbody id="YMapsMarkerTABLEBODY'.$mapDivSuffix.'" class="zhym-ul-tablebody-'.$markerlistcssstyle.'">';
		$markerlisttag .= '</tbody>';
		$markerlisttag .= '</table>';
	}

 	if ($placemarkSearch != 0)
	{
		if ((int)$map->markerlistpos == 120)
		{
			$markerlistsearch = '<div id="YMapsMarkerListSearch" '.$mapDivSuffix.' class="zhym-search-panel-'.$markerlistcssstyle.'"';
		}
		else
		{
			$markerlistsearch = '<div id="YMapsMarkerListSearch" '.$mapDivSuffix.' class="zhym-search-'.$markerlistcssstyle.'"';
		}

		$markerlistsearch .='>';
		$markerlistsearch .= '<input id="YMapsMarkerListSearchAutocomplete'.$mapDivSuffix.'"';
		$markerlistsearch .= ' placeholder="'.$fv_override_placemark_title.'"';
		$markerlistsearch .='>';
		$markerlistsearch .= '</div>';
	}
	else
	{
		$markerlistsearch = "";
	}       
        
	// Add Placemark Search 
	$markerlisttag = $markerlistsearch . $markerlisttag;
        
	switch ((int)$map->markerlistpos) 
	{
		case 0:
			// None
		break;
		case 1:
			$divmap .= '<div id="YMMapWrapper" '.$divwrapmapstyle.' class="zhym-wrap-'.$markerlistcssstyle.'">';
			$divmap .='<div id="YMapsMarkerList'.$mapDivSuffix.'" class="zhym-list-'.$markerlistcssstyle.'" style="'.$markerlistAddStyle.' display: none; float: left; padding: 0; margin: 5px; width:'.$divMarkerlistWidth.'; height:'.$divMarkerlistHeight.';">'.$markerlisttag.'</div>';
		break;
		case 2:
			$divmap .= '<div id="YMMapWrapper" '.$divwrapmapstyle.' class="zhym-wrap-'.$markerlistcssstyle.'">';
			$divmap .='<div id="YMapsMarkerList'.$mapDivSuffix.'" class="zhym-list-'.$markerlistcssstyle.'" style="'.$markerlistAddStyle.' display: none; float: left; padding: 0; margin: 5px; width:'.$divMarkerlistWidth.'; height:'.$divMarkerlistHeight.';">'.$markerlisttag.'</div>';
		break;
		case 3:
			$divmap .= '<div id="YMMapWrapper" '.$divwrapmapstyle.' class="zhym-wrap-'.$markerlistcssstyle.'">';
			$divmap .='<div id="YMapsMarkerList'.$mapDivSuffix.'" class="zhym-list-'.$markerlistcssstyle.'" style="'.$markerlistAddStyle.' display: none; float: left; padding: 0; margin: 5px; width:'.$divMarkerlistWidth.'; height:'.$divMarkerlistHeight.';">'.$markerlisttag.'</div>';
		break;
		case 4:
			$divmap .= '<div id="YMMapWrapper" '.$divwrapmapstyle.' class="zhym-wrap-'.$markerlistcssstyle.'">';
			$divmap .='<div id="YMapsMarkerList'.$mapDivSuffix.'" class="zhym-list-'.$markerlistcssstyle.'" style="'.$markerlistAddStyle.' display: none; float: left; padding: 0; margin: 5px; width:'.$divMarkerlistWidth.'; height:'.$divMarkerlistHeight.';">'.$markerlisttag.'</div>';
		break;
		case 5:
			$divmap .= '<div id="YMMapWrapper" '.$divwrapmapstyle.' class="zhym-wrap-'.$markerlistcssstyle.'">';
			$divmap .='<div id="YMapsMarkerList'.$mapDivSuffix.'" class="zhym-list-'.$markerlistcssstyle.'" style="'.$markerlistAddStyle.' display: none; float: left; padding: 0; margin: 5px; width:'.$divMarkerlistWidth.'; height:'.$divMarkerlistHeight.';">'.$markerlisttag.'</div>';
		break;
		case 6:
			$divmap .= '<div id="YMMapWrapper" '.$divwrapmapstyle.' class="zhym-wrap-'.$markerlistcssstyle.'">';
			$divmap .='<div id="YMapsMarkerList'.$mapDivSuffix.'" class="zhym-list-'.$markerlistcssstyle.'" style="'.$markerlistAddStyle.' display: none; float: left; padding: 0; margin: 5px; width:'.$divMarkerlistWidth.'; height:'.$divMarkerlistHeight.';">'.$markerlisttag.'</div>';
		break;
		case 7:
			$divmap .= '<div id="YMMapWrapper" '.$divwrapmapstyle.' class="zhym-wrap-'.$markerlistcssstyle.'">';
			$divmap .='<div id="YMapsMarkerList'.$mapDivSuffix.'" class="zhym-list-'.$markerlistcssstyle.'" style="'.$markerlistAddStyle.' display: none; float: left; padding: 0; margin: 5px; width:'.$divMarkerlistWidth.'; height:'.$divMarkerlistHeight.';">'.$markerlisttag.'</div>';
		break;
		case 8:
			$divmap .= '<div id="YMMapWrapper" '.$divwrapmapstyle.' class="zhym-wrap-'.$markerlistcssstyle.'">';
			$divmap .='<div id="YMapsMarkerList'.$mapDivSuffix.'" class="zhym-list-'.$markerlistcssstyle.'" style="'.$markerlistAddStyle.' display: none; float: left; padding: 0; margin: 5px; width:'.$divMarkerlistWidth.'; height:'.$divMarkerlistHeight.';">'.$markerlisttag.'</div>';
		break;
		case 9:
			$divmap .= '<div id="YMMapWrapper" '.$divwrapmapstyle.' class="zhym-wrap-'.$markerlistcssstyle.'">';
			$divmap .='<div id="YMapsMarkerList'.$mapDivSuffix.'" class="zhym-list-'.$markerlistcssstyle.'" style="'.$markerlistAddStyle.' display: none; float: left; padding: 0; margin: 5px; width:'.$divMarkerlistWidth.'; height:'.$divMarkerlistHeight.';">'.$markerlisttag.'</div>';
		break;
		case 10:
			$divmap .= '<div id="YMMapWrapper" '.$divwrapmapstyle.' class="zhym-wrap-'.$markerlistcssstyle.'">';
			$divmap .='<div id="YMapsMarkerList'.$mapDivSuffix.'" class="zhym-list-'.$markerlistcssstyle.'" style="'.$markerlistAddStyle.' display: none; float: left; padding: 0; margin: 5px; width:'.$divMarkerlistWidth.'; height:'.$divMarkerlistHeight.';">'.$markerlisttag.'</div>';
		break;
		case 11:
			$divmap .= '<div id="YMMapWrapper" '.$divwrapmapstyle.' class="zhym-wrap-'.$markerlistcssstyle.'">';
			$divmap .='<div id="YMapsMarkerList'.$mapDivSuffix.'" class="zhym-list-'.$markerlistcssstyle.'" style="'.$markerlistAddStyle.' display: none; float: left; padding: 0; margin: 5px; width:'.$divMarkerlistWidth.'; height:'.$divMarkerlistHeight.';">'.$markerlisttag.'</div>';
		break;
		case 12:
			$divmap .= '<div id="YMMapWrapper" '.$divwrapmapstyle.' class="zhym-wrap-'.$markerlistcssstyle.'">';
			$divmap .='<div id="YMapsMarkerList'.$mapDivSuffix.'" class="zhym-list-'.$markerlistcssstyle.'" style="'.$markerlistAddStyle.' display: none; float: left; padding: 0; margin: 5px; width:'.$divMarkerlistWidth.'; height:'.$divMarkerlistHeight.';">'.$markerlisttag.'</div>';
		break;
		case 111:
			if ($fullWidth == 1) 
			{
				$divmap .= '<table id="YMMapTable" class="zhym-table-'.$markerlistcssstyle.'" style="width:100%;" >';
			}
			else
			{
				$divmap .= '<table id="YMMapTable" class="zhym-table-'.$markerlistcssstyle.'" >';
			}
			$divmap .= '<tbody>';
			$divmap .= '<tr>';
			$divmap .= '<td style="width:'.$divMarkerlistWidth.'">';
			$divmap .='<div id="YMapsMarkerList'.$mapDivSuffix.'" class="zhym-list-'.$markerlistcssstyle.'" style="'.$markerlistAddStyle.' float: left; padding: 0; margin: 0 10px 0 0; width:'.$divMarkerlistWidth.'; height:'.$divMarkerlistHeight.';">'.$markerlisttag.'</div>';
			$divmap .= '</td>';
			$divmap .= '<td>';
		break;
		case 112:
			if ($fullWidth == 1) 
			{
				$divmap .= '<table id="YMMapTable" class="zhym-table-'.$markerlistcssstyle.'" style="width:100%;" >';
			}
			else
			{
				$divmap .= '<table id="YMMapTable" class="zhym-table-'.$markerlistcssstyle.'" >';
			}
			$divmap .= '<tbody>';
			$divmap .= '<tr>';
			$divmap .= '<td>';
		break;
		case 113:
			$divmap .= '<div id="YMMapWrapper" '.$divwrapmapstyle.' class="zhym-wrap-'.$markerlistcssstyle.'" >';
			$divmap .='<div id="YMapsMarkerList'.$mapDivSuffix.'" class="zhym-list-'.$markerlistcssstyle.'" style="'.$markerlistAddStyle.' display: none; float: left; padding: 0; margin: 0; width:'.$divMarkerlistWidth.'; height:'.$divMarkerlistHeight.';">'.$markerlisttag.'</div>';
		break;
		case 114:
			$divmap .= '<div id="YMMapWrapper" '.$divwrapmapstyle.' class="zhym-wrap-'.$markerlistcssstyle.'" >';
		break;
		case 121:
		break;
		default:
		break;
	}

	
}



$mapDivCSSClassName = ' class="zhym-map-default"';

if (isset($map->cssclassname) && ($map->cssclassname != ""))
{
	$mapDivCSSClassName = ' class="'.$map->cssclassname . $cssClassSuffix . '"';
}
else
{
	if (isset($cssClassSuffix) && ($cssClassSuffix != ""))
	{
		$mapDivCSSClassName = ' class="'. $cssClassSuffix . '"';
	}
}

if ($fullWidth == 1) 
{
	if ($fullHeight == 1) 
	{
		$divmap .= '<div id="YMapsID'.$mapDivSuffix.'" '.$mapDivCSSClassName.' style="margin:0;padding:0;width:100%;height:100%;"></div>';
	}
	else
	{
		$divmap .= '<div id="YMapsID'.$mapDivSuffix.'" '.$mapDivCSSClassName.' style="margin:0;padding:0;width:100%;height:'.$currentMapHeightValue.'px;"></div>';
	}		

}
else
{
	if ($fullHeight == 1) 
	{
		$divmap .= '<div id="YMapsID'.$mapDivSuffix.'" '.$mapDivCSSClassName.' style="margin:0;padding:0;width:'.$currentMapWidthValue.'px;height:100%;"></div>';			
	}
	else
	{
		$divmap .= '<div id="YMapsID'.$mapDivSuffix.'" '.$mapDivCSSClassName.' style="margin:0;padding:0;width:'.$currentMapWidthValue.'px;height:'.$currentMapHeightValue.'px;"></div>';			
	}		
}



// adding markerlist (close div)
if (isset($map->markerlistpos) && (int)$map->markerlistpos != 0) 
{

	switch ((int)$map->markerlistpos) 
	{
		case 0:
			// None
		break;
		case 1:
			$divmap .='</div>';
		break;
		case 2:
			$divmap .='</div>';
		break;
		case 3:
			$divmap .='</div>';
		break;
		case 4:
			$divmap .='</div>';
		break;
		case 5:
			$divmap .='</div>';
		break;
		case 6:
			$divmap .='</div>';
		break;
		case 7:
			$divmap .='</div>';
		break;
		case 8:
			$divmap .='</div>';
		break;
		case 9:
			$divmap .='</div>';
		break;
		case 10:
			$divmap .='</div>';
		break;
		case 11:
			$divmap .='</div>';
		break;
		case 12:
			$divmap .='</div>';
		break;
		case 111:
			$divmap .= '</td>';
			$divmap .= '</tr>';
			$divmap .= '</tbody>';
			$divmap .='</table>';
		break;
		case 112:
			$divmap .= '</td>';
			$divmap .= '<td style="width:'.$divMarkerlistWidth.'">';
			$divmap .='<div id="YMapsMarkerLis'.$mapDivSuffix.'t" class="zhym-list-'.$markerlistcssstyle.'" style="'.$markerlistAddStyle.' float: left; padding: 0; margin: 0 0 0 10px; width:'.$divMarkerlistWidth.'; height:'.$divMarkerlistHeight.';">'.$markerlisttag.'</div>';
			$divmap .= '</td>';
			$divmap .= '</tr>';
			$divmap .= '</tbody>';
			$divmap .='</table>';
		break;
		case 113:
			$divmap .='</div>';
		break;
		case 114:
			$divmap .='<div id="YMapsMarkerList'.$mapDivSuffix.'" class="zhym-list-'.$markerlistcssstyle.'" style="'.$markerlistAddStyle.' display: none; float: left; padding: 0; margin: 0; width:'.$divMarkerlistWidth.'; height:'.$divMarkerlistHeight.';">'.$markerlisttag.'</div>';
			$divmap .='</div>';
		break;
		case 121:
		break;
		default:
		break;
	}


}

// Adding before and after sections

$divmap = $divmapbefore . $divmap . $divmapafter;

$scripthead .= $divmapheader . $currentUserInfo;

	$divwrapmapstyle = '';
	$divtabcolmapstyle = '';
	
	if ($fullWidth == 1)
	{
		$divwrapmapstyle .= 'width:100%;';
	}
	if ($fullHeight == 1)
	{
		$divwrapmapstyle .= 'height:100%;';
		$divtabcolmapstyle .= 'height:100%;';
	}
	if ($divwrapmapstyle != "")
	{
		$divwrapmapstyle = 'style="'.$divwrapmapstyle.'"';
	}
	if ($divtabcolmapstyle != "")
	{
		$divtabcolmapstyle = 'style="'.$divtabcolmapstyle.'"';
	}

$divmap .= '<div id="YMapsCredit'.$mapDivSuffix.'" class="zhym-credit"></div>';
	
$divmap .= '<div id="YMapsLoading'.$mapDivSuffix.'" style="display: none;" ><img class="zhym-image-loading" src="'.$imgpathUtils.'loading.gif" alt="" /></div>';
	
$divTabDivMain = '';

if (isset($map->markergroupcontrol) && (int)$map->markergroupcontrol != 0) 
{
	switch ((int)$map->markergroupcontrol) 
	{
		
		case 1:
		       if ($fullWidth == 1) 
		       {
		          $divTabDivMain .= '<table class="zhym-group-manage" '.$divwrapmapstyle.'>';
		          $divTabDivMain .= '<tr align="left" >';
		          $divTabDivMain .= '<td valign="top" width="'.(int)$map->markergroupwidth.'%">';
        	          $divTabDivMain .= $divmarkergroup;
		          $divTabDivMain .= '</td>';
		          $divTabDivMain .= '<td '.$divtabcolmapstyle.'>';
		          $divTabDivMain .= $divmap;
		          $divTabDivMain .= '</td>';
		          $divTabDivMain .= '</tr>';
		       }
		       else
		       {
		          $divTabDivMain .= '<table class="zhym-group-manage" '.$divwrapmapstyle.'>';
                          $divTabDivMain .= '<tr>';
		          $divTabDivMain .= '<td valign="top">';
        	          $divTabDivMain .= $divmarkergroup;
		          $divTabDivMain .= '</td>';
		          $divTabDivMain .= '<td '.$divtabcolmapstyle.'>';
		          $divTabDivMain .= $divmap;
		          $divTabDivMain .= '</td>';
		          $divTabDivMain .= '</tr>';
                       }
		       $divTabDivMain .= '</table>';
		break;
		case 2:
		       if ($fullWidth == 1) 
		       {
		          $divTabDivMain .= '<table class="zhym-group-manage" '.$divwrapmapstyle.'>';
		       }
		       else
		       {
		          $divTabDivMain .= '<table class="zhym-group-manage" '.$divwrapmapstyle.'>';
		       }
		       $divTabDivMain .= '<tr>';
		       $divTabDivMain .= '<td valign="top">';
		       $divTabDivMain .= $divmarkergroup;
		       $divTabDivMain .= '</td>';
		       $divTabDivMain .= '</tr>';
		       $divTabDivMain .= '<tr>';
		       $divTabDivMain .= '<td '.$divtabcolmapstyle.'>';
		       $divTabDivMain .= $divmap;
		       $divTabDivMain .= '</td>';
		       $divTabDivMain .= '</tr>';
		       $divTabDivMain .= '</table>';

		break;
		case 3:
		       if ($fullWidth == 1) 
		       {
		          $divTabDivMain .= '<table class="zhym-group-manage" '.$divwrapmapstyle.'>';
		          $divTabDivMain .= '<tr>';
		          $divTabDivMain .= '<td '.$divtabcolmapstyle.'>';
		          $divTabDivMain .= $divmap;
		          $divTabDivMain .= '</td>';
		          $divTabDivMain .= '<td valign="top" width="'.(int)$map->markergroupwidth.'%">';
		          $divTabDivMain .= $divmarkergroup;
		          $divTabDivMain .= '</td>';
		          $divTabDivMain .= '</tr>';
		       }
		       else
		       {
		          $divTabDivMain .= '<table class="zhym-group-manage" '.$divwrapmapstyle.'>';
		          $divTabDivMain .= '<tr>';
		          $divTabDivMain .= '<td '.$divtabcolmapstyle.'>';
		          $divTabDivMain .= $divmap;
		          $divTabDivMain .= '</td>';
		          $divTabDivMain .= '<td valign="top">';
		          $divTabDivMain .= $divmarkergroup;
		          $divTabDivMain .= '</td>';
		          $divTabDivMain .= '</tr>';
		       }
		       $divTabDivMain .= '</table>';

		break;
		case 4:
		       if ($fullWidth == 1) 
		       {
		          $divTabDivMain .= '<table class="zhym-group-manage" '.$divwrapmapstyle.'>';
		       }
		       else
		       {
		          $divTabDivMain .= '<table class="zhym-group-manage" '.$divwrapmapstyle.'>';
		       }
		       $divTabDivMain .= '<tr>';
		       $divTabDivMain .= '<td '.$divtabcolmapstyle.'>';
		       $divTabDivMain .= $divmap;
		       $divTabDivMain .= '</td>';
		       $divTabDivMain .= '</tr>';
		       $divTabDivMain .= '<tr>';
		       $divTabDivMain .= '<td valign="top">';
		       $divTabDivMain .= $divmarkergroup;
		       $divTabDivMain .= '</td>';
		       $divTabDivMain .= '</tr>';
		       $divTabDivMain .= '</table>';
		break;
		case 5:
		       $divTabDivMain .= '<div id="zhym-wrapper" '.$divwrapmapstyle.'>';
		       $divTabDivMain .= $divmarkergroup;
		       $divTabDivMain .= $divmap;
		       $divTabDivMain .= '</div>';
		break;
		case 6:
		       $divTabDivMain .= '<div id="zhym-wrapper" '.$divwrapmapstyle.'>';
		       $divTabDivMain .= $divmap;
		       $divTabDivMain .= $divmarkergroup;
		       $divTabDivMain .= '</div>';
		break;
		default:
			$divTabDivMain .= $divmap;
		break;
	}

	$scripthead .= $divTabDivMain;

}
else
{
    $scripthead .= $divmap;
}

$divmap4route = '<div id="YMapsMainRoutePanel'.$mapDivSuffix.'"><div id="YMapsMainRoutePanel_Total'.$mapDivSuffix.'"></div></div>';
$divmap4route .= '<div id="YMapsRoutePanel'.$mapDivSuffix.'"><div id="YMapsRoutePanel_Description'.$mapDivSuffix.'"></div><div id="YMapsRoutePanel_Total'.$mapDivSuffix.'"></div><div id="YMapsRoutePanel_Steps'.$mapDivSuffix.'"></div></div>';

$scripthead .= $divmapfooter . $divmap4route;

if (isset($MapXdoLoad) && ((int)$MapXdoLoad == 0))
{
	// all save at the end
}
else
{
	echo $scripthead;
}


//Script begin
$scripttextBegin .= '<script type="text/javascript" >/*<![CDATA[*/' ."\n";

	if (($ajaxLoadObjects == 2)
	 || (isset($map->useajax) && ((int)$map->useajax == 2))
	 || (isset($map->placemark_rating) && ((int)$map->placemark_rating == 2))
	 || ((int)$placemarkSearch != 0)
         || ($compatiblemode == 2) 
	)
	{
		$scripttext .= 'var ZhJQ'.$mapDivSuffix.' = jQuery.noConflict();'."\n";
	}

	$scripttext .= 'var map'.$mapDivSuffix.', mapcenter'.$mapDivSuffix.', mapzoom'.$mapDivSuffix.', geoResult'.$mapDivSuffix.', geoRoute'.$mapDivSuffix.';' ."\n";
	$scripttext .= 'var searchControl'.$mapDivSuffix.', searchControlPMAP'.$mapDivSuffix.';' ."\n";
	if ($zhymObjectManager != 0)
	{
		$scripttext .= 'var zhymObjMgr'.$mapDivSuffix.';' ."\n";
	}
	
	if ($externalmarkerlink == 1)
	{
		$scripttext .= 'var allPlacemarkArray'.$mapDivSuffix.' = [];' ."\n";
	}
	
	if (isset($map->usercontactattributes) && $map->usercontactattributes != "")
	{
		$userContactAttrs = str_replace(";", ',',$map->usercontactattributes);
	}
	else
	{
		$userContactAttrs = str_replace(";", ',', 'name;position;address;phone;mobile;fax;email');
	}
	$scripttext .= 'var userContactAttrs = \''.$userContactAttrs.'\';' ."\n";
	

	$scripttext .= 'var icoIcon=\''.$imgpathIcons.'\';'."\n";
	$scripttext .= 'var icoUtils=\''.$imgpathUtils.'\';'."\n";
	$scripttext .= 'var icoDir=\''.$directoryIcons.'\';'."\n";
	
	
	if ($zhymObjectManager != 0)
	{
		$scripttext .= 'zhymObjMgr'.$mapDivSuffix.' = new zhymMapObjectManager();' ."\n";

                $scripttext .= 'zhymObjMgr'.$mapDivSuffix.'.setAPIversion("'.$mapVersion.'");' ."\n";   
                
		$scripttext .= 'zhymObjMgr'.$mapDivSuffix.'.setMapID('.$map->id.');' ."\n";
		$scripttext .= 'zhymObjMgr'.$mapDivSuffix.'.setPlacemarkList("'.$placemarklistid.'");' ."\n";
		$scripttext .= 'zhymObjMgr'.$mapDivSuffix.'.setExcludePlacemarkList("'.$explacemarklistid.'");' ."\n";
		$scripttext .= 'zhymObjMgr'.$mapDivSuffix.'.setGroupList("'.$grouplistid.'");' ."\n";
		$scripttext .= 'zhymObjMgr'.$mapDivSuffix.'.setCategotyList("'.$categorylistid.'");' ."\n";
		$scripttext .= 'zhymObjMgr'.$mapDivSuffix.'.setUserMarkersFilter("'.$usermarkersfilter.'");' ."\n";

		$scripttext .= 'zhymObjMgr'.$mapDivSuffix.'.setMapLanguageTag("'.$map->lang.'");' ."\n";
		
		
		$scripttext .= 'zhymObjMgr'.$mapDivSuffix.'.setServiceDirection('.$service_DoDirection.');' ."\n";
		$scripttext .= 'zhymObjMgr'.$mapDivSuffix.'.setIcoIcon(icoIcon);' ."\n";
		$scripttext .= 'zhymObjMgr'.$mapDivSuffix.'.setIcoUtils(icoUtils);' ."\n";
		$scripttext .= 'zhymObjMgr'.$mapDivSuffix.'.setIcoDir(icoDir);' ."\n";
		$scripttext .= 'zhymObjMgr'.$mapDivSuffix.'.setArticleID("'.$mapDivSuffix.'");' ."\n";
		$scripttext .= 'zhymObjMgr'.$mapDivSuffix.'.setPlacemarkRating('.(int)$map->placemark_rating.');' ."\n";
		$scripttext .= 'zhymObjMgr'.$mapDivSuffix.'.setPlacemarkTitleTag("'.$placemarkTitleTag.'");' ."\n";
		$scripttext .= 'zhymObjMgr'.$mapDivSuffix.'.setRequestURL("'.JURI::root().'");' ."\n";
		$scripttext .= 'zhymObjMgr'.$mapDivSuffix.'.setPlacemarkCreationInfo('.(int)$map->showcreateinfo.');' ."\n";

		
		if ($ajaxLoadObjects != 0)
		{
			$scripttext .= 'zhymObjMgr'.$mapDivSuffix.'.setAjaxBufferSize('.(int)$map->ajaxbufferplacemark.');' ."\n";
		}

		$scripttext .= 'zhymObjMgr'.$mapDivSuffix.'.setContactAttrs("'.$userContactAttrs.'");' ."\n";
		$scripttext .= 'zhymObjMgr'.$mapDivSuffix.'.setUserContact('.(int)$map->usercontact.');' ."\n";
		$scripttext .= 'zhymObjMgr'.$mapDivSuffix.'.setUserUser('.(int)$map->useruser.');' ."\n";
		
		if (($ajaxLoadObjects == 2)
		 || ((int)$placemarkSearch != 0)
		 || (isset($map->useajax) && ((int)$map->useajax == 2))
	         || (isset($map->placemark_rating) && ((int)$map->placemark_rating == 2))
                 || ($compatiblemode == 2)
		 )
		{
			$scripttext .= 'zhymObjMgr'.$mapDivSuffix.'.setZhJQ(ZhJQ'.$mapDivSuffix.');' ."\n";
		}
		
		if ($compatiblemode != 0)
		{
			$scripttext .= 'zhymObjMgr'.$mapDivSuffix.'.setCompatibleMode('.(int)$compatiblemode.');' ."\n";
		
		}		
		
        if (isset($map->markerlistpos) && (int)$map->markerlistpos != 0)
		{
			if (isset($map->markerlistsync) && (int)$map->markerlistsync != 0)
			{
				$scripttext .= 'zhymObjMgr'.$mapDivSuffix.'.enablePlacemarkList();' ."\n";
			}
		}
		
		
	}
			

	// for centering placemarks
	if ($ajaxLoadObjects != 0) {
	    if ($currentPlacemarkCenter != "do not change") {
		$scripttext .= 'zhymObjMgr'.$mapDivSuffix.'.setCenterPlacemark('.(int)$currentPlacemarkCenter.');' ."\n";

	    }
	}
                    	
	$scripttext .= 'ymaps.ready(initialize'.$mapDivSuffix.');' ."\n";

	$scripttext .= 'function initialize'.$mapDivSuffix.' () {' ."\n";

	$scripttext .= '  var toShowLoading = document.getElementById("YMapsLoading'.$mapDivSuffix.'");'."\n";
	$scripttext .= '  toShowLoading.style.display = \'block\';'."\n";
	// Begin initialize function

	if (isset($mapzoom) && (int)$mapzoom != 0)
	{
		$scripttext .= 'mapzoom'.$mapDivSuffix.' ='.$mapzoom.';' ."\n";		
	}
	else
	{
		$scripttext .= 'mapzoom'.$mapDivSuffix.' ='.$map->zoom.';' ."\n";				
	}	
	


	$scripttext .= '    mapcenter'.$mapDivSuffix.' = ['.$map->longitude.', ' .$map->latitude.'];' ."\n";
	// Overrides map center
        if ($ajaxLoadObjects != 0) {
            if ($currentPlacemarkCenter != "do not change") {
                $curcenterLatLng = comZhYandexMapPlacemarksHelper::get_placemark_coordinates((int)$currentPlacemarkCenter);

                if ($curcenterLatLng != "") {
                    if ($curcenterLatLng != "geocode") {
                        $scripttext .= 'mapcenter'.$mapDivSuffix.' = '.$curcenterLatLng.';'."\n";
                    }   
                }

            }
        }	
	
	
	
        $scripttext .= '    map'.$mapDivSuffix.' = new ymaps.Map("YMapsID'.$mapDivSuffix.'", {' ."\n";
	$scripttext .= '     center: mapcenter'.$mapDivSuffix."\n";
	$scripttext .= '    ,zoom: mapzoom'.$mapDivSuffix."\n";
	
	if ($mapVersion == "2.1")
	{
	    $scripttext .= '    ,controls: []'."\n";
	}

        $mapPrefix = "";
        $mapSuffix = "";     
        $mapOption = "";
        $mapOptionFullText ="";
        

        if (isset($map->suppressmapopenblock) && (int)$map->suppressmapopenblock != 0)
        {
            $mapOption = "suppressMapOpenBlock: true";
        }

        if ($mapOption != "")
        {
            $mapPrefix = "{";
            $mapSuffix = "}";

            $mapOptionFullText = ', ' . $mapPrefix . $mapOption . $mapSuffix;
        }
        else
        {
            $mapOptionFullText = "";
        }

	$scripttext .= '    }'.$mapOptionFullText.');' ."\n";

	$scripttext .= 'geoResult'.$mapDivSuffix.' = new ymaps.GeoObjectCollection();'."\n";

    // MarkerGroups
	$placemarkGroupArray = array();
	
	if ($zhymObjectManager)
	{
		$scripttext .= 'var markerCluster0;' ."\n";

		$scripttext .= 'zhymObjMgr'.$mapDivSuffix.'.GroupStateDefine(0, 1);' ."\n";
		   
	    if (isset($markergroups) && !empty($markergroups)) 
	    {
			foreach ($markergroups as $key => $currentmarkergroup) 
			{
				$scripttext .= 'var markerCluster'.$currentmarkergroup->id.';' ."\n";
				
				array_push($placemarkGroupArray, $currentmarkergroup->id);
				
					// 24.11.2015 - bugfix - unpublished groups caused error, because there is no link element
					if (((int)$currentmarkergroup->published == 1) || ($allowUserMarker == 1))
					{
						$scripttext .= 'zhymObjMgr'.$mapDivSuffix.'.GroupStateDefine('.$currentmarkergroup->id.', '.(int)$currentmarkergroup->activeincluster.');' ."\n";
					}
			}
	    }

		if (isset($mgrgrouplist) && !empty($mgrgrouplist)) 
		{
			foreach ($mgrgrouplist as $key => $currentmarkergroup) 
			{
				if (!in_array($currentmarkergroup->id, $placemarkGroupArray))
				{
					$scripttext .= 'var markerCluster'.$currentmarkergroup->id.';' ."\n";
					
					// 24.11.2015 - bugfix - unpublished groups caused error, because there is no link element
					if (((int)$currentmarkergroup->published == 1) || ($allowUserMarker == 1))
					{
						$scripttext .= 'zhymObjMgr'.$mapDivSuffix.'.GroupStateDefine('.$currentmarkergroup->id.', '.(int)$currentmarkergroup->activeincluster.');' ."\n";
					}
				}
			}
		}	   
		  
    }
			

	if (isset($map->useajax) && (int)$map->useajax != 0)
	{
		$scripttext .= 'var ajaxmarkersLL'.$mapDivSuffix.' = [];' ."\n";
		
	}   
	
	
	if ($zhymObjectManager != 0)
	{
		$scripttext .= 'zhymObjMgr'.$mapDivSuffix.'.setMap(map'.$mapDivSuffix.');' ."\n";
	}

        
	// Creating Clusters in the beginning 
	if ((isset($map->markercluster) && (int)$map->markercluster == 1))
	{      

		$scripttext .= 'markerCluster0 = new ymaps.Clusterer({ maxZoom: '.$map->clusterzoom."\n";
		if ((isset($map->clusterdisableclickzoom) && (int)$map->clusterdisableclickzoom == 1))
		{
			$scripttext .= '  , clusterDisableClickZoom: true' ."\n";
		}
		else
		{
			$scripttext .= '  , clusterDisableClickZoom: false' ."\n";
		}
		if ((isset($map->clustersynchadd) && (int)$map->clustersynchadd == 1))
		{
			$scripttext .= '  , synchAdd: true' ."\n";
		}
		else
		{
			$scripttext .= '  , synchAdd: false' ."\n";
		}
		if ((isset($map->clusterorderalphabet) && (int)$map->clusterorderalphabet == 1))
		{
			$scripttext .= '  , showInAlphabeticalOrder: true' ."\n";
		}
		else
		{
			$scripttext .= '  , showInAlphabeticalOrder: false' ."\n";
		}

		if (isset($map->clustergridsize))
		{
			$scripttext .= '  , gridSize: '.(int)$map->clustergridsize ."\n";
		}
		if (isset($map->clusterminclustersize))
		{
			$scripttext .= '  , minClusterSize: '.(int)$map->clusterminclustersize ."\n";
		}
		
		$scripttext .= '});' ."\n";
		
		$scripttext .= 'map'.$mapDivSuffix.'.geoObjects.add(markerCluster0);' ."\n";

		if ($zhymObjectManager != 0)
		{
			$scripttext .= 'zhymObjMgr'.$mapDivSuffix.'.ClusterAdd(0, markerCluster0);' ."\n";
		}		

        if ((isset($map->markerclustergroup) && (int)$map->markerclustergroup == 1))
		{

			if (isset($markergroups) && !empty($markergroups)) 
			{
				foreach ($markergroups as $key => $currentmarkergroup) 
				{
					$scripttext .= 'markerCluster'.$currentmarkergroup->id.' = new ymaps.Clusterer({ maxZoom: '.$map->clusterzoom."\n";
					if ((isset($map->clusterdisableclickzoom) && (int)$map->clusterdisableclickzoom == 1))
					{
						$scripttext .= '  , clusterDisableClickZoom: true' ."\n";
					}
					else
					{
						$scripttext .= '  , clusterDisableClickZoom: false' ."\n";
					}
					if ((isset($map->clustersynchadd) && (int)$map->clustersynchadd == 1))
					{
						$scripttext .= '  , synchAdd: true' ."\n";
					}
					else
					{
						$scripttext .= '  , synchAdd: false' ."\n";
					}
					if ((isset($map->clusterorderalphabet) && (int)$map->clusterorderalphabet == 1))
					{
						$scripttext .= '  , showInAlphabeticalOrder: true' ."\n";
					}
					else
					{
						$scripttext .= '  , showInAlphabeticalOrder: false' ."\n";
					}

					if (isset($map->clustergridsize))
					{
						$scripttext .= '  , gridSize: '.(int)$map->clustergridsize ."\n";
					}
					if (isset($map->clusterminclustersize))
					{
						$scripttext .= '  , minClusterSize: '.(int)$map->clusterminclustersize ."\n";
					}
					$scripttext .= '});' ."\n";
					
					$scripttext .= 'map'.$mapDivSuffix.'.geoObjects.add(markerCluster'.$currentmarkergroup->id.');' ."\n";
					
					if ($zhymObjectManager != 0)
					{
						$scripttext .= 'zhymObjMgr'.$mapDivSuffix.'.ClusterAdd('.$currentmarkergroup->id.', markerCluster'.$currentmarkergroup->id.');' ."\n";
					}					
				}
			}

		}
		
	}

	
	//Double Click Zoom
	if (isset($map->doubleclickzoom) && (int)$map->doubleclickzoom == 1) 
	{
		$scripttext .= 'map'.$mapDivSuffix.'.behaviors.enable(\'dblClickZoom\');' ."\n";
	} 
	else 
	{
		$scripttext .= 'if (map'.$mapDivSuffix.'.behaviors.isEnabled(\'dblClickZoom\'))' ."\n";
		$scripttext .= 'map'.$mapDivSuffix.'.behaviors.disable(\'dblClickZoom\');' ."\n";
	}


	//Scroll Wheel Zoom		
	if (isset($map->scrollwheelzoom) && (int)$map->scrollwheelzoom == 1) 
	{
		$scripttext .= 'map'.$mapDivSuffix.'.behaviors.enable(\'scrollZoom\');' ."\n";
	} 
	else 
	{
		$scripttext .= 'if (map'.$mapDivSuffix.'.behaviors.isEnabled(\'scrollZoom\'))' ."\n";
		$scripttext .= 'map'.$mapDivSuffix.'.behaviors.disable(\'scrollZoom\');' ."\n";
	}
		

	//Zoom Control
	if (isset($map->zoomcontrol)) 
	{
                $ctrlPosition = "";
                $ctrlPositionFullText ="";
                
                $ctrlPrefix = "";
                $ctrlSuffix = "";
                if (isset($map->zoomcontrolpos)) 
                {
                    switch ($map->zoomcontrolpos)
                    {
                        case 1:
                            // TOP_LEFT
                            $ctrlPosition = "{ top: ".(int)$map->zoomcontrolofsy.", left: ".(int)$map->zoomcontrolofsx."}";
                            if ($mapVersion == "2.1")
                            {
                                $ctrlPrefix = "{ float: 'none', position: ";
                                $ctrlSuffix = "}";
                            }
                        break;
                        case 2:
                            // TOP_RIGHT
                            $ctrlPosition = "{ top: ".(int)$map->zoomcontrolofsy.", right: ".(int)$map->zoomcontrolofsx."}";
                            if ($mapVersion == "2.1")
                            {
                                $ctrlPrefix = "{ float: 'none', position: ";
                                $ctrlSuffix = "}";
                            }
                        break;
                        case 3:
                            // BOTTOM_RIGHT
                            $ctrlPosition = "{ bottom: ".(int)$map->zoomcontrolofsy.", right: ".(int)$map->zoomcontrolofsx."}";
                            if ($mapVersion == "2.1")
                            {
                                $ctrlPrefix = "{ float: 'none', position: ";
                                $ctrlSuffix = "}";
                            }
                        break;
                        case 4:
                            // BOTTOM_LEFT
                            $ctrlPosition = "{ bottom: ".(int)$map->zoomcontrolofsy.", left: ".(int)$map->zoomcontrolofsx."}";
                            if ($mapVersion == "2.1")
                            {
                                $ctrlPrefix = "{ float: 'none', position: ";
                                $ctrlSuffix = "}";
                            }
                        break;
                        case 13:
                            // FLOAT_RIGHT                            
                            if ($mapVersion == "2.1")
                            {
                                $ctrlPrefix = "{ float: 'right'";
                                $ctrlSuffix = "}";
                                $ctrlPosition = " ";
                            }
                        break;
                        case 14:
                            // FLOAT_LEFT
                            if ($mapVersion == "2.1")
                            {
                                $ctrlPrefix = "{ float: 'left'";
                                $ctrlSuffix = "}";
                                $ctrlPosition = " ";
                            }
                        break;                        
                        default:
                            $ctrlPosition = "";
                        break;
                    }
                    if ($ctrlPosition != "")
                    {
                        $ctrlPositionFullText = ', ' . $ctrlPrefix . $ctrlPosition . $ctrlSuffix;
                    }
                    else
                    {
                        $ctrlPositionFullText ="";
                    }
                }
                else
                {
                    $ctrlPositionFullText ="";
                }
            
		switch ($map->zoomcontrol) 
		{
			case 1:
				$scripttext .= 'map'.$mapDivSuffix.'.controls.add(new ymaps.control.ZoomControl()'.$ctrlPositionFullText.');' ."\n";
			break;
			case 2:
			    	if ($mapVersion == "2.0")
				{
				    $scripttext .= 'map'.$mapDivSuffix.'.controls.add(new ymaps.control.SmallZoomControl()'.$ctrlPositionFullText.');' ."\n";
				}
				else
				{
				    $scripttext .= 'map'.$mapDivSuffix.'.controls.add(new ymaps.control.ZoomControl()'.$ctrlPositionFullText.');' ."\n";
				}
				    
			break;
			default:
				$scripttext .= '' ."\n";
			break;
		}
	}

	if ($mapVersion == "2.0")
	{
	    //Scale Control
	    if (isset($map->scalecontrol) && (int)$map->scalecontrol == 1) 
	    {
                $ctrlPosition = "";
                $ctrlPositionFullText ="";

                if (isset($map->scalecontrolpos)) 
                {
                    switch ($map->scalecontrolpos)
                    {
                        case 1:
                            // TOP_LEFT
                                                        $ctrlPosition = "{ top: ".(int)$map->scalecontrolofsy.", left: ".(int)$map->scalecontrolofsx."}";
                        break;
                        case 2:
                            // TOP_RIGHT
                                                        $ctrlPosition = "{ top: ".(int)$map->scalecontrolofsy.", right: ".(int)$map->scalecontrolofsx."}";
                        break;
                        case 3:
                            // BOTTOM_RIGHT
                                                        $ctrlPosition = "{ bottom: ".(int)$map->scalecontrolofsy.", right: ".(int)$map->scalecontrolofsx."}";
                        break;
                        case 4:
                            // BOTTOM_LEFT
                                                        $ctrlPosition = "{ bottom: ".(int)$map->scalecontrolofsy.", left: ".(int)$map->scalecontrolofsx."}";
                        break;
                        default:
                            $ctrlPosition = "";
                        break;
                    }
                    if ($ctrlPosition != "")
                    {
                        $ctrlPositionFullText = ', '.$ctrlPosition;
                    }
                    else
                    {
                        $ctrlPositionFullText ="";
                    }
                }
                else
                {
                    $ctrlPositionFullText ="";
                }

                $scripttext .= 'var scaleline = new ymaps.control.ScaleLine();' ."\n";
                $scripttext .= 'map'.$mapDivSuffix.'.controls.add(scaleline'.$ctrlPositionFullText.');' ."\n";
	    }	    
	}

        

	if ((int)$map->openstreet == 1)
	{
		$scripttext .= 'osmMapType = function () { return new ymaps.Layer(' ."\n";
		$scripttext .= '\'http://tile.openstreetmap.org/%z/%x/%y.png\', {' ."\n";
		$scripttext .= '	projection: ymaps.projection.sphericalMercator' ."\n";
		$scripttext .= '});' ."\n";
		$scripttext .= '};' ."\n";

		$scripttext .= 'ymaps.mapType.storage.add(\'osmMapType\', new ymaps.MapType(' ."\n";
		$scripttext .= '	\'OSM\',' ."\n";
		$scripttext .= '	[\'osmMapType\']' ."\n";
		$scripttext .= '));' ."\n";

		$scripttext .= 'ymaps.layer.storage.add(\'osmMapType\', osmMapType);' ."\n";
		
		if ($credits != '')
		{
			$credits .= '<br />';
		}
		$credits .= 'OSM '.JText::_('COM_ZHYANDEXMAP_MAP_POWEREDBY').': ';
		$credits .= '<a href="http://www.openstreetmap.org/" target="_blank">OpenStreetMap</a>';
		
	}
	
	// Add Custom MapTypes - Begin
	if ((int)$map->custommaptype != 0)
	{
		foreach ($maptypes as $key => $currentmaptype) 
		{
			for ($i=0; $i < count($custMapTypeList); $i++)
			{
				if ($currentmaptype->id == (int)$custMapTypeList[$i])
				{
					$scripttext .= 'customMapLayer'.$currentmaptype->id.' = new ymaps.Layer(' ."\n";
					$scripttext .= '\'\', {' ."\n";

                    switch ($currentmaptype->projection)
                    {
                        case 0:
							$scripttext .= '  projection: ymaps.projection.sphericalMercator' ."\n";
                        break;
                        case 1:
							$scripttext .= '  projection: ymaps.projection.wgs84Mercator' ."\n";
                        break;
                        case 2:
							$scripttext .= '  projection: ymaps.projection.Cartesian' ."\n";
                        break;
                        default:
							$scripttext .= '  projection: ymaps.projection.sphericalMercator' ."\n";
                        break;
                    }
                    if ($currentmaptype->opacity != "")
                    {
                            $scripttext .= ', brightness: '.$currentmaptype->opacity ."\n";
                    }

                    $scripttext .= ', tileSize: ['.$currentmaptype->tilewidth.','.$currentmaptype->tileheight.']'."\n";

                    if ((int)$currentmaptype->transparent == 0)
                    {
                            $scripttext .= ', tileTransparent: false' ."\n";
                    }
                    else
                    {
                            $scripttext .= ', tileTransparent: true' ."\n";
                    }
                    
                    if ($currentmaptype->notfoundtileurl != "")
					{
						$scripttext .= ', notFoundTile: \''.$currentmaptype->notfoundtileurl .'\''."\n";
					}
					
					$scripttext .= '});' ."\n";
					
					$scripttext .= 'customMapLayer'.$currentmaptype->id.'.getTileUrl = '.$currentmaptype->gettileurl ."\n";

					$scripttext .= 'customMapType'.$currentmaptype->id.' = function () { return customMapLayer'.$currentmaptype->id.';';
					$scripttext .= '};' ."\n";

                                        if ($mapVersion == "2.0")
                                        {
                                            $scripttext .= 'customMapLayer'.$currentmaptype->id.'.getZoomRange = function () {'."\n";
                                            $scripttext .= '    var promise = new ymaps.util.Promise();'."\n";
                                            $scripttext .= '    promise.resolve(['.(int)$currentmaptype->minzoom.', '.(int)$currentmaptype->maxzoom.']);'."\n";
                                            $scripttext .= '    return promise;'."\n";
                                            $scripttext .= '};'."\n";
                                        }
                                        else
                                        {
                                            $scripttext .= 'customMapLayer'.$currentmaptype->id.'.getZoomRange = function () {'."\n";
                                            $scripttext .= '    return ymaps.vow.resolve(['.(int)$currentmaptype->minzoom.', '.(int)$currentmaptype->maxzoom.']);'."\n";
                                            $scripttext .= '};'."\n";
                                        }
                                        
					switch ($currentmaptype->overlay) 
					{
						case 0:
							$scripttext .= 'ymaps.mapType.storage.add(\'customMapType'.$currentmaptype->id.'\', new ymaps.MapType(' ."\n";
							$scripttext .= '	\''.str_replace('\'','\\\'', $currentmaptype->title).'\',' ."\n";
							$scripttext .= '	[\'customMapType'.$currentmaptype->id.'\']' ."\n";
							$scripttext .= '));' ."\n";
						break;
						case 1:
							$scripttext .= 'ymaps.mapType.storage.add(\'customMapType'.$currentmaptype->id.'\', new ymaps.MapType(' ."\n";
							$scripttext .= '	\''.str_replace('\'','\\\'', $currentmaptype->title).'\',' ."\n";
							$scripttext .= '	ymaps.mapType.storage.get(\'yandex#map\').getLayers().concat([\'customMapType'.$currentmaptype->id.'\'])' ."\n";
							$scripttext .= '));' ."\n";
						break;
						case 2:
							$scripttext .= 'ymaps.mapType.storage.add(\'customMapType'.$currentmaptype->id.'\', new ymaps.MapType(' ."\n";
							$scripttext .= '	\''.str_replace('\'','\\\'', $currentmaptype->title).'\',' ."\n";
							$scripttext .= '	ymaps.mapType.storage.get(\'yandex#satellite\').getLayers().concat([\'customMapType'.$currentmaptype->id.'\'])' ."\n";
							$scripttext .= '));' ."\n";
						break;
						case 3:
							$scripttext .= 'ymaps.mapType.storage.add(\'customMapType'.$currentmaptype->id.'\', new ymaps.MapType(' ."\n";
							$scripttext .= '	\''.str_replace('\'','\\\'', $currentmaptype->title).'\',' ."\n";
							$scripttext .= '	ymaps.mapType.storage.get(\'yandex#hybrid\').getLayers().concat([\'customMapType'.$currentmaptype->id.'\'])' ."\n";
							$scripttext .= '));' ."\n";
						break;
						case 4:
							$scripttext .= 'ymaps.mapType.storage.add(\'customMapType'.$currentmaptype->id.'\', new ymaps.MapType(' ."\n";
							$scripttext .= '	\''.str_replace('\'','\\\'', $currentmaptype->title).'\',' ."\n";
							$scripttext .= '	ymaps.mapType.storage.get(\'yandex#publicMap\').getLayers().concat([\'customMapType'.$currentmaptype->id.'\'])' ."\n";
							$scripttext .= '));' ."\n";
						break;
						case 5:
							$scripttext .= 'ymaps.mapType.storage.add(\'customMapType'.$currentmaptype->id.'\', new ymaps.MapType(' ."\n";
							$scripttext .= '	\''.str_replace('\'','\\\'', $currentmaptype->title).'\',' ."\n";
							$scripttext .= '	ymaps.mapType.storage.get(\'yandex#publicMapHybrid\').getLayers().concat([\'customMapType'.$currentmaptype->id.'\'])' ."\n";
							$scripttext .= '));' ."\n";
						break;
						case 6:
							if ((int)$map->openstreet == 1)
							{
								$scripttext .= 'ymaps.mapType.storage.add(\'customMapType'.$currentmaptype->id.'\', new ymaps.MapType(' ."\n";
								$scripttext .= '	\''.str_replace('\'','\\\'', $currentmaptype->title).'\',' ."\n";
								$scripttext .= '	ymaps.mapType.storage.get(\'osmMapType\').getLayers().concat([\'customMapType'.$currentmaptype->id.'\'])' ."\n";
								$scripttext .= '));' ."\n";
							}
							else
							{
								$scripttext .= 'ymaps.mapType.storage.add(\'customMapType'.$currentmaptype->id.'\', new ymaps.MapType(' ."\n";
								$scripttext .= '	\''.str_replace('\'','\\\'', $currentmaptype->title).'\',' ."\n";
								$scripttext .= '	[\'customMapType'.$currentmaptype->id.'\']' ."\n";
								$scripttext .= '));' ."\n";
							}
						break;
						default:
							$scripttext .= 'ymaps.mapType.storage.add(\'customMapType'.$currentmaptype->id.'\', new ymaps.MapType(' ."\n";
							$scripttext .= '	\''.str_replace('\'','\\\'', $currentmaptype->title).'\',' ."\n";
							$scripttext .= '	[\'customMapType'.$currentmaptype->id.'\']' ."\n";
							$scripttext .= '));' ."\n";
						break;
					}

					$scripttext .= 'ymaps.layer.storage.add(\'customMapType'.$currentmaptype->id.'\', customMapType'.$currentmaptype->id.');' ."\n";
				}
			}
			// End loop by Enabled CustomMapTypes
			
		}
		// End loop by All CustomMapTypes
		
	}
		
	if ((isset($map->maptypecontrol) && (int)$map->maptypecontrol == 1) 
	  || (isset($map->pmaptypecontrol) && (int)$map->pmaptypecontrol == 1) 
	  || (isset($map->custommaptype) && (int)$map->custommaptype == 2) )
	{
		$ctrlPosition = "";
		$ctrlPositionFullText ="";

                $ctrlPrefix = "";
                $ctrlSuffix = "";    		
		if (isset($map->maptypecontrolpos)) 
		{
			switch ($map->maptypecontrolpos)
			{
				case 1:
                                    // TOP_LEFT
                                    $ctrlPosition = "{ top: ".(int)$map->maptypecontrolofsy.", left: ".(int)$map->maptypecontrolofsx."}";
                                    if ($mapVersion == "2.1")
                                    {
                                        $ctrlPrefix = "{ float: 'none', position: ";
                                        $ctrlSuffix = "}";
                                    }                                    
				break;
				case 2:
                                    // TOP_RIGHT
                                    $ctrlPosition = "{ top: ".(int)$map->maptypecontrolofsy.", right: ".(int)$map->maptypecontrolofsx."}";
                                    if ($mapVersion == "2.1")
                                    {
                                        $ctrlPrefix = "{ float: 'none', position: ";
                                        $ctrlSuffix = "}";
                                    }                                     
				break;
				case 3:
                                    // BOTTOM_RIGHT
                                    $ctrlPosition = "{ bottom: ".(int)$map->maptypecontrolofsy.", right: ".(int)$map->maptypecontrolofsx."}";
                                    if ($mapVersion == "2.1")
                                    {
                                        $ctrlPrefix = "{ float: 'none', position: ";
                                        $ctrlSuffix = "}";
                                    }                                     
				break;
				case 4:
                                    // BOTTOM_LEFT
                                    $ctrlPosition = "{ bottom: ".(int)$map->maptypecontrolofsy.", left: ".(int)$map->maptypecontrolofsx."}";
                                    if ($mapVersion == "2.1")
                                    {
                                        $ctrlPrefix = "{ float: 'none', position: ";
                                        $ctrlSuffix = "}";
                                    }                                     
				break;
                                case 13:
                                    // FLOAT_RIGHT
                                    if ($mapVersion == "2.1")
                                    {
                                        $ctrlPrefix = "{ float: 'right'";
                                        $ctrlSuffix = "}";
                                        $ctrlPosition = " ";
                                    }
                                break;
                                case 14:
                                    // FLOAT_LEFT
                                    if ($mapVersion == "2.1")
                                    {
                                        $ctrlPrefix = "{ float: 'left'";
                                        $ctrlSuffix = "}";
                                        $ctrlPosition = " ";
                                    }
                                break;                                 
				default:
					$ctrlPosition = "";
				break;
			}
			if ($ctrlPosition != "")
			{
				$ctrlPositionFullText = ', ' .$ctrlPrefix . $ctrlPosition . $ctrlSuffix;
			}
			else
			{
				$ctrlPositionFullText ="";
			}
		}
		else
		{
			$ctrlPositionFullText ="";
		}

		$ctrlMapType = "";
		
		if (isset($map->maptypecontrol) && (int)$map->maptypecontrol == 1) 
		{
			if ($ctrlMapType == "")
			{
				$ctrlMapType .= '"yandex#map", "yandex#satellite", "yandex#hybrid"';
			}
			else
			{
				$ctrlMapType .= ', "yandex#map", "yandex#satellite", "yandex#hybrid"';
			}
		}
		if (isset($map->pmaptypecontrol) && (int)$map->pmaptypecontrol == 1) 
		{
			if ($ctrlMapType == "")
			{
				$ctrlMapType .= '"yandex#publicMap", "yandex#publicMapHybrid"';
			}
			else
			{
				$ctrlMapType .= ', "yandex#publicMap", "yandex#publicMapHybrid"';
			}
		}

		if ((int)$map->openstreet == 1)
		{
			if ($ctrlMapType == "")
			{
				$ctrlMapType .= '"osmMapType"' ."\n";
			}
			else
			{
				$ctrlMapType .= ', "osmMapType"' ."\n";
			}
		}
		
		// Add Custom MapTypes - Begin
		if ((int)$map->custommaptype == 2)
		{
			foreach ($maptypes as $key => $currentmaptype) 
			{
				for ($i=0; $i < count($custMapTypeList); $i++)
				{
					if ($currentmaptype->id == (int)$custMapTypeList[$i])
					{
						if ($ctrlMapType == "")
						{
							$ctrlMapType .= '"customMapType'.$currentmaptype->id.'"' ."\n";
						}
						else
						{
							$ctrlMapType .= ', "customMapType'.$currentmaptype->id.'"' ."\n";
						}
					}
				}
				// End loop by Enabled CustomMapTypes
				
			}
			// End loop by All CustomMapTypes
			
		}
								
		$scripttext .= 'map'.$mapDivSuffix.'.controls.add(new ymaps.control.TypeSelector(['.$ctrlMapType.'])'.$ctrlPositionFullText.');' ."\n";
	}


	// Map type
	if (isset($currentMapType)) 
	{
		if ($currentMapType == "do not change")
		{
			$currentMapTypeValue = $map->maptype;
		}
		else
		{
			$currentMapTypeValue = $currentMapType;
		}
	}
	else
	{
		$currentMapTypeValue = $map->maptype;
	}
	
	if (isset($currentMapTypeValue)) 
	{
		switch ($currentMapTypeValue) 
		{
			
			case 1:
				$scripttext .= 'map'.$mapDivSuffix.'.setType("yandex#map");' ."\n";
			break;
			case 2:
				$scripttext .= 'map'.$mapDivSuffix.'.setType("yandex#satellite");' ."\n";
			break;
			case 3:
				$scripttext .= 'map'.$mapDivSuffix.'.setType("yandex#hybrid");' ."\n";
			break;
			case 4:
				$scripttext .= 'map'.$mapDivSuffix.'.setType("yandex#publicMap");' ."\n";
			break;
			case 5:
				$scripttext .= 'map'.$mapDivSuffix.'.setType("yandex#publicMapHybrid");' ."\n";
			break;
			case 6:
				if ((int)$map->openstreet == 1)
				{
					$scripttext .= 'map'.$mapDivSuffix.'.setType("osmMapType");' ."\n";
				}
			break;
			case 7:
			if ((int)$map->custommaptype != 0)
			{
				foreach ($maptypes as $key => $currentmaptype) 	
				{
					for ($i=0; $i < count($custMapTypeList); $i++)
					{
						if ($currentmaptype->id == (int)$custMapTypeList[$i])
						{
							if (((int)$custMapTypeFirst != 0) && ((int)$custMapTypeFirst == $currentmaptype->id))
							{
								$scripttext .= ' map'.$mapDivSuffix.'.setType(\'customMapType'.$currentmaptype->id.'\');' ."\n";
							}
						}
					}
					// End loop by Enabled CustomMapTypes
					
				}
				// End loop by All CustomMapTypes
			}
			break;
			default:
				$scripttext .= '' ."\n";
			break;
		}
	}
		

	// MiniMap type
	if ($mapVersion == "2.0")
	{        
            if (isset($map->minimap) && (int)$map->minimap != 0) 
            {
                    if (isset($map->minimaptype)) 
                    {
                            switch ($map->minimaptype) 
                            {

                                    case 1:
                                            //MAP';
                                            $scripttextMiniMap = 'yandex#map';
                                    break;
                                    case 2:
                                            //SATELLITE';
                                            $scripttextMiniMap = 'yandex#satellite';
                                    break;
                                    case 3:
                                            //HYBRID';
                                            $scripttextMiniMap = 'yandex#hybrid';
                                    break;
                                    case 4:
                                            //PMAP';
                                            $scripttextMiniMap = 'yandex#publicMap';
                                    break;
                                    case 5:
                                            //PHYBRID';
                                            $scripttextMiniMap = 'yandex#publicMapHybrid';
                                    break;
                                    default:
                                            $scripttextMiniMap = '';
                                    break;
                            }
                    }
            }
        }

	
	// Because that we set map type
	if (isset($mapzoom) && (int)$mapzoom != 0)
	{
	    if ((int)$mapzoom != 200)
	    {
		    $scripttext .= '    map'.$mapDivSuffix.'.setZoom('.$mapzoom.');' ."\n";
	    }
	    else
	    {
		    //$scripttext .= '    map'.$mapDivSuffix.'.setZoom(map'.$mapDivSuffix.'.options.get("maxZoom"));' ."\n";
		    $scripttext .= '  map'.$mapDivSuffix.'.zoomRange.get(map'.$mapDivSuffix.'.getCenter()).then(function (range) {' ."\n";
		    $scripttext .= '    map'.$mapDivSuffix.'.setZoom(range[1]);' ."\n";
		    $scripttext .= '});' ."\n";
	    }	
	}
	else
	{	
	    if ((int)$map->zoom != 200)
	    {
		    $scripttext .= '    map'.$mapDivSuffix.'.setZoom('.(int)$map->zoom.');' ."\n";
	    }
	    else
	    {
		    //$scripttext .= '    map'.$mapDivSuffix.'.setZoom(map'.$mapDivSuffix.'.options.get("maxZoom"));' ."\n";
		    $scripttext .= '  map'.$mapDivSuffix.'.zoomRange.get(map'.$mapDivSuffix.'.getCenter()).then(function (range) {' ."\n";
		    $scripttext .= '    map'.$mapDivSuffix.'.setZoom(range[1]);' ."\n";
		    $scripttext .= '});' ."\n";
	    }				
	}	
	

	
	$scripttext .= '    map'.$mapDivSuffix.'.options.set("minZoom",'.(int)$map->minzoom.');' ."\n";
	if ((int)$map->maxzoom != 200)
	{
		$scripttext .= '    map'.$mapDivSuffix.'.options.set("maxZoom", '.(int)$map->maxzoom.');' ."\n";
	}
	
	// When changed maptype max zoom level can be other
	$scripttext .= 'map'.$mapDivSuffix.'.events.add("typechange", function (e) {' ."\n";
	//$scripttext .= '     alert("Change Type!");' ."\n";
	$scripttext .= '  map'.$mapDivSuffix.'.zoomRange.get(map'.$mapDivSuffix.'.getCenter()).then(function (range) {' ."\n";
	//$scripttext .= '  alert("range"+range[1]);';
	
	$scripttext .= '  if (map'.$mapDivSuffix.'.getZoom() > range[1] )' ."\n";
	$scripttext .= '  {	' ."\n";
	//$scripttext .= '     alert("Change Zoom!");' ."\n";
	$scripttext .= '    map'.$mapDivSuffix.'.setZoom(range[1]);' ."\n";
	$scripttext .= '  }' ."\n";
	$scripttext .= '});' ."\n";
	$scripttext .= '});' ."\n";

	if (isset($map->mapbounds) && $map->mapbounds != "")
	{
		$mapBoundsArray = explode(";", str_replace(',',';',$map->mapbounds));
		if (count($mapBoundsArray) != 4)
		{
			$scripttext .= 'alert("'.JText::_('COM_ZHYANDEXMAP_MAP_ERROR_MAPBOUNDS').'");'."\n";
		}
		else
		{
			
			if ($zhymObjectManager != 0)
			{
				$scripttext .= 'zhymObjMgr'.$mapDivSuffix.'.setMapBounds('.$mapBoundsArray[0].', '.$mapBoundsArray[1].','.$mapBoundsArray[2].', '.$mapBoundsArray[3].');' ."\n";
			}				
		}
	}	
	

	//MiniMap
	if ($mapVersion == "2.0")
	{

	    if (isset($map->minimap) && (int)$map->minimap != 0) 
	    {
		    $ctrlPosition = "";
		    $ctrlPositionFullText ="";

		    if (isset($map->minimappos)) 
		    {
			switch ($map->minimappos)
			{
			    case 1:
				// TOP_LEFT
							    $ctrlPosition = "{ top: ".(int)$map->minimapofsy.", left: ".(int)$map->minimapofsx."}";
			    break;
			    case 2:
				// TOP_RIGHT
							    $ctrlPosition = "{ top: ".(int)$map->minimapofsy.", right: ".(int)$map->minimapofsx."}";
			    break;
			    case 3:
				// BOTTOM_RIGHT
							    $ctrlPosition = "{ bottom: ".(int)$map->minimapofsy.", right: ".(int)$map->minimapofsx."}";
			    break;
			    case 4:
				// BOTTOM_LEFT
							    $ctrlPosition = "{ bottom: ".(int)$map->minimapofsy.", left: ".(int)$map->minimapofsx."}";
			    break;
			    default:
				$ctrlPosition = "";
			    break;
			}
			if ($ctrlPosition != "")
			{
			    $ctrlPositionFullText = ', '.$ctrlPosition;
			}
			else
			{
			    $ctrlPositionFullText ="";
			}
		    }
		    else
		    {
			$ctrlPositionFullText ="";
		    }

		    $scripttext .= 'minimap'.$mapDivSuffix.' = new ymaps.control.MiniMap();' ."\n";

		    if ((int)$map->minimap == 1)
		    {
			    $scripttext .= 'minimap'.$mapDivSuffix.'.expand();' ."\n";
		    }
		    else
		    {
			    $scripttext .= 'minimap'.$mapDivSuffix.'.collapse();' ."\n";
		    }

		    if ($scripttextMiniMap != "")
		    {
			    $scripttext .= 'minimap'.$mapDivSuffix.'.setType("'.$scripttextMiniMap.'");' ."\n";
		    }


		    $scripttext .= 'map'.$mapDivSuffix.'.controls.add(minimap'.$mapDivSuffix.''.$ctrlPositionFullText.');' ."\n";
	    }

	}

	//Toolbar
	if ($mapVersion == "2.0")
	{
	    if (isset($map->toolbar) && (int)$map->toolbar == 1) 
	    {
		    $ctrlPosition = "";
		    $ctrlPositionFullText ="";

		    if (isset($map->toolbarpos)) 
		    {
			switch ($map->toolbarpos)
			{
			    case 1:
				// TOP_LEFT
							    $ctrlPosition = "{ top: ".(int)$map->toolbarofsy.", left: ".(int)$map->toolbarofsx."}";
			    break;
			    case 2:
				// TOP_RIGHT
							    $ctrlPosition = "{ top: ".(int)$map->toolbarofsy.", right: ".(int)$map->toolbarofsx."}";
			    break;
			    case 3:
				// BOTTOM_RIGHT
							    $ctrlPosition = "{ bottom: ".(int)$map->toolbarofsy.", right: ".(int)$map->toolbarofsx."}";
			    break;
			    case 4:
				// BOTTOM_LEFT
							    $ctrlPosition = "{ bottom: ".(int)$map->toolbarofsy.", left: ".(int)$map->toolbarofsx."}";
			    break;
			    default:
				$ctrlPosition = "";
			    break;
			}
			if ($ctrlPosition != "")
			{
			    $ctrlPositionFullText = ', '.$ctrlPosition;
			}
			else
			{
			    $ctrlPositionFullText ="";
			}
		    }
		    else
		    {
			$ctrlPositionFullText ="";
		    }

		    $scripttext .= 'var toolbar'.$mapDivSuffix.' = new ymaps.control.MapTools();' ."\n";
		    $scripttext .= 'map'.$mapDivSuffix.'.controls.add(toolbar'.$mapDivSuffix.''.$ctrlPositionFullText.');' ."\n";



		    if (isset($map->autopositioncontrol) && (int)$map->autopositioncontrol == 1) 
		    {
				    switch ((int)$map->autopositionbutton) 
				    {
					    case 1:
						    $scripttext .= 'var btnGeoPosition'.$mapDivSuffix.' = new ymaps.control.Button({ data: { image: "'.$imgpathUtils.'geolocation.png", content: "", title: "'.JText::_('COM_ZHYANDEXMAP_MAP_AUTOPOSITIONBUTTON').'"}}, {selectOnClick: false});' ."\n";
					    break;
					    case 2:
						    $scripttext .= 'var btnGeoPosition'.$mapDivSuffix.' = new ymaps.control.Button({ data: { content: "'.JText::_('COM_ZHYANDEXMAP_MAP_AUTOPOSITIONBUTTON').'", title: "'.JText::_('COM_ZHYANDEXMAP_MAP_AUTOPOSITIONBUTTON').'"}}, {selectOnClick: false});' ."\n";
					    break;
					    case 3:
						    $scripttext .= 'var btnGeoPosition'.$mapDivSuffix.' = new ymaps.control.Button({ data: { image: "'.$imgpathUtils.'geolocation.png", content: "'.JText::_('COM_ZHYANDEXMAP_MAP_AUTOPOSITIONBUTTON').'", title: "'.JText::_('COM_ZHYANDEXMAP_MAP_AUTOPOSITIONBUTTON').'"}}, {selectOnClick: false});' ."\n";
					    break;
					    default:
						    $scripttext .= 'var btnGeoPosition'.$mapDivSuffix.' = new ymaps.control.Button({ data: { image: "'.$imgpathUtils.'geolocation.png", content: "", title: "'.JText::_('COM_ZHYANDEXMAP_MAP_AUTOPOSITIONBUTTON').'"}}, {selectOnClick: false});' ."\n";
					    break;
				    }

				    $scripttext .= 'btnGeoPosition'.$mapDivSuffix.'.events.add("click", function (e) {' ."\n";
				    $scripttext .= '	findMyPosition'.$mapDivSuffix.'("Button");' ."\n";
				    $scripttext .= '}, toolbar'.$mapDivSuffix.');' ."\n";
				    $scripttext .= 'toolbar'.$mapDivSuffix.'.add(btnGeoPosition'.$mapDivSuffix.');' ."\n";
		    }


		    if (isset($map->markerlistpos) && (int)$map->markerlistpos != 0) 
		    {

			    if ((int)$map->markerlistpos == 111
			      ||(int)$map->markerlistpos == 112
			      ||(int)$map->markerlistpos == 121
			      ) 
			    {
				    // Do not create button when table or external
			    }
			    else
			    {
				    if ((int)$map->markerlistbuttontype == 0)
				    {
                                        // Skip creation for non-button
				    }
				    else
				    {

                                        switch ($map->markerlistbuttontype) 
                                        {
                                                case 0:
                                                        $btnPlacemarkListOptions ="" ;
                                                break;
                                                case 1:
                                                        $btnPlacemarkListOptions ="" ;
                                                break;
                                                case 2:
                                                        $btnPlacemarkListOptions ="" ;
                                                break;
                                                case 11:
                                                        $btnPlacemarkListOptions ="btnPlacemarkList'.$mapDivSuffix.'.select();" ."\n";
                                                break;
                                                case 12:
                                                        $btnPlacemarkListOptions ="btnPlacemarkList'.$mapDivSuffix.'.select();" ."\n";
                                                break;
                                                default:
                                                        $btnPlacemarkListOptions ="" ;
                                                break;
                                        }		

                                        switch ((int)$map->markerlistbuttontype) 
                                        {
                                                case 1:
                                                        $scripttext .= 'var btnPlacemarkList'.$mapDivSuffix.' = new ymaps.control.Button({ data: { image: "'.$imgpathUtils.'star.png", content: "", title: "'.JText::_('COM_ZHYANDEXMAP_MAP_PLACEMARKLIST').'"}});' ."\n";
                                                break;
                                                case 2:
                                                        $scripttext .= 'var btnPlacemarkList'.$mapDivSuffix.' = new ymaps.control.Button({ data: { content: "'.JText::_('COM_ZHYANDEXMAP_MAP_PLACEMARKLIST').'", title: "'.JText::_('COM_ZHYANDEXMAP_MAP_PLACEMARKLIST').'"}});' ."\n";
                                                break;
                                                case 11:
                                                        $scripttext .= 'var btnPlacemarkList'.$mapDivSuffix.' = new ymaps.control.Button({ data: { image: "'.$imgpathUtils.'star.png", content: "", title: "'.JText::_('COM_ZHYANDEXMAP_MAP_PLACEMARKLIST').'"}});' ."\n";
                                                break;
                                                case 2:
                                                        $scripttext .= 'var btnPlacemarkList'.$mapDivSuffix.' = new ymaps.control.Button({ data: { content: "'.JText::_('COM_ZHYANDEXMAP_MAP_PLACEMARKLIST').'", title: "'.JText::_('COM_ZHYANDEXMAP_MAP_PLACEMARKLIST').'"}});' ."\n";
                                                default:
                                                        $scripttext .= 'var btnPlacemarkList'.$mapDivSuffix.' = new ymaps.control.Button({ data: { image: "'.$imgpathUtils.'star.png", content: "", title: "'.JText::_('COM_ZHYANDEXMAP_MAP_PLACEMARKLIST').'"}});' ."\n";
                                                break;
                                        }

                                        $scripttext .= 'btnPlacemarkList'.$mapDivSuffix.'.events.add("select", function (e) {' ."\n";
                                        $scripttext .= '		var toHideDiv = document.getElementById("YMapsMarkerList'.$mapDivSuffix.'");' ."\n";
                                        $scripttext .= '		toHideDiv.style.display = "block";' ."\n";
                                        $scripttext .= '}, toolbar'.$mapDivSuffix.');' ."\n";

                                        $scripttext .= 'btnPlacemarkList'.$mapDivSuffix.'.events.add("deselect", function (e) {' ."\n";
                                        $scripttext .= '		var toHideDiv = document.getElementById("YMapsMarkerList'.$mapDivSuffix.'");' ."\n";
                                        $scripttext .= '		toHideDiv.style.display = "none";' ."\n";
                                        $scripttext .= '}, toolbar'.$mapDivSuffix.');' ."\n";


                                        $scripttext .= $btnPlacemarkListOptions;

                                        $scripttext .= 'toolbar'.$mapDivSuffix.'.add(btnPlacemarkList'.$mapDivSuffix.');' ."\n";

				    }
			    }

		    }

	    }	    
	}
	else
	{
	    // Create buttons without toolbar 
            if (isset($map->autopositioncontrol) && (int)$map->autopositioncontrol == 1) 
            {

                $ctrlPosition = "";
                $ctrlPositionFullText ="";
                
                $ctrlPrefix = "";
                $ctrlSuffix = "";
                if (isset($map->autopositionpos)) 
                {
                    switch ($map->autopositionpos)
                    {
                        case 1:
                            // TOP_LEFT
                            $ctrlPosition = "{ top: ".(int)$map->autopositionposofsy.", left: ".(int)$map->autopositionposofsx."}";
                            if ($mapVersion == "2.1")
                            {
                                $ctrlPrefix = "{ float: 'none', position: ";
                                $ctrlSuffix = "}";
                            }
                        break;
                        case 2:
                            // TOP_RIGHT
                            $ctrlPosition = "{ top: ".(int)$map->autopositionposofsy.", right: ".(int)$map->autopositionposofsx."}";
                            if ($mapVersion == "2.1")
                            {
                                $ctrlPrefix = "{ float: 'none', position: ";
                                $ctrlSuffix = "}";
                            }
                        break;
                        case 3:
                            // BOTTOM_RIGHT
                            $ctrlPosition = "{ bottom: ".(int)$map->autopositionposofsy.", right: ".(int)$map->autopositionposofsx."}";
                            if ($mapVersion == "2.1")
                            {
                                $ctrlPrefix = "{ float: 'none', position: ";
                                $ctrlSuffix = "}";
                            }
                        break;
                        case 4:
                            // BOTTOM_LEFT
                            $ctrlPosition = "{ bottom: ".(int)$map->autopositionposofsy.", left: ".(int)$map->autopositionposofsx."}";
                            if ($mapVersion == "2.1")
                            {
                                $ctrlPrefix = "{ float: 'none', position: ";
                                $ctrlSuffix = "}";
                            }
                        break;
                        case 13:
                            // FLOAT_RIGHT
                            if ($mapVersion == "2.1")
                            {
                                $ctrlPrefix = "{ float: 'right'";
                                $ctrlSuffix = "}";
                                $ctrlPosition = " ";
                            }
                        break;
                        case 14:
                            // FLOAT_LEFT
                            if ($mapVersion == "2.1")
                            {
                                $ctrlPrefix = "{ float: 'left'";
                                $ctrlSuffix = "}";
                                $ctrlPosition = " ";
                            }
                        break;                        
                        default:
                            $ctrlPosition = "";
                        break;
                    }
                    if ($ctrlPosition != "")
                    {
                        $ctrlPositionFullText = ', ' . $ctrlPrefix . $ctrlPosition . $ctrlSuffix;
                    }
                    else
                    {
                        $ctrlPositionFullText ="";
                    }
                }
                else
                {
                    $ctrlPositionFullText ="";
                }
            


                switch ((int)$map->autopositionbutton) 
                {
                        case 1:
                                $scripttext .= 'var btnGeoPosition'.$mapDivSuffix.' = new ymaps.control.Button({ data: { image: "'.$imgpathUtils.'geolocation.png", content: "", title: "'.JText::_('COM_ZHYANDEXMAP_MAP_AUTOPOSITIONBUTTON').'"}, options: {selectOnClick: false}});' ."\n";
                        break;
                        case 2:
                                $scripttext .= 'var btnGeoPosition'.$mapDivSuffix.' = new ymaps.control.Button({ data: { content: "'.JText::_('COM_ZHYANDEXMAP_MAP_AUTOPOSITIONBUTTON').'", title: "'.JText::_('COM_ZHYANDEXMAP_MAP_AUTOPOSITIONBUTTON').'"}, options: {selectOnClick: false}});' ."\n";
                        break;
                        case 3:
                                $scripttext .= 'var btnGeoPosition'.$mapDivSuffix.' = new ymaps.control.Button({ data: { image: "'.$imgpathUtils.'geolocation.png", content: "'.JText::_('COM_ZHYANDEXMAP_MAP_AUTOPOSITIONBUTTON').'", title: "'.JText::_('COM_ZHYANDEXMAP_MAP_AUTOPOSITIONBUTTON').'"}, options: {selectOnClick: false}});' ."\n";
                        break;
                        case 0:
                                $scripttext .= 'var btnGeoPosition'.$mapDivSuffix.' = new ymaps.control.GeolocationControl({options: {noPlacemark: true}});' ."\n";
                        break;
                        default:
                                $scripttext .= 'var btnGeoPosition'.$mapDivSuffix.' = new ymaps.control.Button({ data: { image: "'.$imgpathUtils.'geolocation.png", content: "", title: "'.JText::_('COM_ZHYANDEXMAP_MAP_AUTOPOSITIONBUTTON').'"}, options: {selectOnClick: false}});' ."\n";
                        break;
                }
              


                $scripttext .= 'btnGeoPosition'.$mapDivSuffix.'.events.add("click", function (e) {' ."\n";
                $scripttext .= '	findMyPosition'.$mapDivSuffix.'("Button");' ."\n";
                $scripttext .= '});' ."\n";                    

                $scripttext .= 'map'.$mapDivSuffix.'.controls.add(btnGeoPosition'.$mapDivSuffix.$ctrlPositionFullText.');' ."\n";

            }


            if (isset($map->markerlistpos) && (int)$map->markerlistpos != 0) 
            {

                if ((int)$map->markerlistpos == 111
                  ||(int)$map->markerlistpos == 112
                  ||(int)$map->markerlistpos == 121
                  ) 
                {
                        // Do not create button when table or external
                }
                else
                {
                    if ((int)$map->markerlistbuttontype == 0)
                    {
                        // Skip creation for non-button
                    }
                    else
                    {

                        $ctrlPosition = "";
                        $ctrlPositionFullText ="";

                        $ctrlPrefix = "";
                        $ctrlSuffix = "";
                        if (isset($map->markerlistbuttonpos)) 
                        {
                            switch ($map->markerlistbuttonpos)
                            {
                                case 1:
                                    // TOP_LEFT
                                    $ctrlPosition = "{ top: ".(int)$map->markerlistposofsy.", left: ".(int)$map->markerlistposofsx."}";
                                    if ($mapVersion == "2.1")
                                    {
                                        $ctrlPrefix = "{ float: 'none', position: ";
                                        $ctrlSuffix = "}";
                                    }
                                break;
                                case 2:
                                    // TOP_RIGHT
                                    $ctrlPosition = "{ top: ".(int)$map->markerlistposofsy.", right: ".(int)$map->markerlistposofsx."}";
                                    if ($mapVersion == "2.1")
                                    {
                                        $ctrlPrefix = "{ float: 'none', position: ";
                                        $ctrlSuffix = "}";
                                    }
                                break;
                                case 3:
                                    // BOTTOM_RIGHT
                                    $ctrlPosition = "{ bottom: ".(int)$map->markerlistposofsy.", right: ".(int)$map->markerlistposofsx."}";
                                    if ($mapVersion == "2.1")
                                    {
                                        $ctrlPrefix = "{ float: 'none', position: ";
                                        $ctrlSuffix = "}";
                                    }
                                break;
                                case 4:
                                    // BOTTOM_LEFT
                                    $ctrlPosition = "{ bottom: ".(int)$map->markerlistposofsy.", left: ".(int)$map->markerlistposofsx."}";
                                    if ($mapVersion == "2.1")
                                    {
                                        $ctrlPrefix = "{ float: 'none', position: ";
                                        $ctrlSuffix = "}";
                                    }
                                break;
                                case 13:
                                    // FLOAT_RIGHT
                                    if ($mapVersion == "2.1")
                                    {
                                        $ctrlPrefix = "{ float: 'right'";
                                        $ctrlSuffix = "}";
                                        $ctrlPosition = " ";
                                    }
                                break;
                                case 14:
                                    // FLOAT_LEFT
                                    if ($mapVersion == "2.1")
                                    {
                                        $ctrlPrefix = "{ float: 'left'";
                                        $ctrlSuffix = "}";
                                        $ctrlPosition = " ";
                                    }
                                break;                        
                                default:
                                    //$ctrlPosition = "";
                                    // FLOAT_LEFT
                                    if ($mapVersion == "2.1")
                                    {
                                        $ctrlPrefix = "{ float: 'left'";
                                        $ctrlSuffix = "}";
                                        $ctrlPosition = " ";
                                    }                                    
                                break;
                            }
                            if ($ctrlPosition != "")
                            {
                                $ctrlPositionFullText = ', ' . $ctrlPrefix . $ctrlPosition . $ctrlSuffix;
                            }
                            else
                            {
                                $ctrlPositionFullText ="";
                            }
                        }
                        else
                        {
                            $ctrlPositionFullText ="";
                        }                    

                        switch ($map->markerlistbuttontype) 
                        {
                                case 0:
                                        $btnPlacemarkListOptions ="" ;
                                break;
                                case 1:
                                        $btnPlacemarkListOptions ="" ;
                                break;
                                case 2:
                                        $btnPlacemarkListOptions ="" ;
                                break;
                                case 11:
                                        $btnPlacemarkListOptions ="btnPlacemarkList'.$mapDivSuffix.'.select();" ."\n";
                                break;
                                case 12:
                                        $btnPlacemarkListOptions ="btnPlacemarkList'.$mapDivSuffix.'.select();" ."\n";
                                break;
                                default:
                                        $btnPlacemarkListOptions ="" ;
                                break;
                        }		

                        switch ((int)$map->markerlistbuttontype) 
                        {
                                case 1:
                                        $scripttext .= 'var btnPlacemarkList'.$mapDivSuffix.' = new ymaps.control.Button({ data: { image: "'.$imgpathUtils.'star.png", content: "", title: "'.JText::_('COM_ZHYANDEXMAP_MAP_PLACEMARKLIST').'"}});' ."\n";
                                break;
                                case 2:
                                        $scripttext .= 'var btnPlacemarkList'.$mapDivSuffix.' = new ymaps.control.Button({ data: { content: "'.JText::_('COM_ZHYANDEXMAP_MAP_PLACEMARKLIST').'", title: "'.JText::_('COM_ZHYANDEXMAP_MAP_PLACEMARKLIST').'"}});' ."\n";
                                break;
                                case 11:
                                        $scripttext .= 'var btnPlacemarkList'.$mapDivSuffix.' = new ymaps.control.Button({ data: { image: "'.$imgpathUtils.'star.png", content: "", title: "'.JText::_('COM_ZHYANDEXMAP_MAP_PLACEMARKLIST').'"}});' ."\n";
                                break;
                                case 2:
                                        $scripttext .= 'var btnPlacemarkList'.$mapDivSuffix.' = new ymaps.control.Button({ data: { content: "'.JText::_('COM_ZHYANDEXMAP_MAP_PLACEMARKLIST').'", title: "'.JText::_('COM_ZHYANDEXMAP_MAP_PLACEMARKLIST').'"}});' ."\n";
                                default:
                                        $scripttext .= 'var btnPlacemarkList'.$mapDivSuffix.' = new ymaps.control.Button({ data: { image: "'.$imgpathUtils.'star.png", content: "", title: "'.JText::_('COM_ZHYANDEXMAP_MAP_PLACEMARKLIST').'"}});' ."\n";
                                break;
                        }

                        $scripttext .= 'btnPlacemarkList'.$mapDivSuffix.'.events.add("select", function (e) {' ."\n";
                        $scripttext .= '		var toHideDiv = document.getElementById("YMapsMarkerList'.$mapDivSuffix.'");' ."\n";
                        $scripttext .= '		toHideDiv.style.display = "block";' ."\n";
                        $scripttext .= '});' ."\n";

                        $scripttext .= 'btnPlacemarkList'.$mapDivSuffix.'.events.add("deselect", function (e) {' ."\n";
                        $scripttext .= '		var toHideDiv = document.getElementById("YMapsMarkerList'.$mapDivSuffix.'");' ."\n";
                        $scripttext .= '		toHideDiv.style.display = "none";' ."\n";
                        $scripttext .= '});' ."\n";


                        $scripttext .= $btnPlacemarkListOptions;

                        $scripttext .= 'map'.$mapDivSuffix.'.controls.add(btnPlacemarkList'.$mapDivSuffix.$ctrlPositionFullText.');' ."\n";

                        }
                }

            }

	}
	

        if ($mapVersion == "2.1")
        {
            if (isset($map->fullscreencontrol) && (int)$map->fullscreencontrol == 1) 
            {

                $ctrlPosition = "";
                $ctrlPositionFullText ="";

                $ctrlPrefix = "";
                $ctrlSuffix = "";
                if (isset($map->fullscreenpos)) 
                {
                    switch ($map->fullscreenpos)
                    {
                        case 1:
                            // TOP_LEFT
                            $ctrlPosition = "{ top: ".(int)$map->fullscreenposofsy.", left: ".(int)$map->fullscreenposofsx."}";
                            if ($mapVersion == "2.1")
                            {
                                $ctrlPrefix = "{ float: 'none', position: ";
                                $ctrlSuffix = "}";
                            }
                        break;
                        case 2:
                            // TOP_RIGHT
                            $ctrlPosition = "{ top: ".(int)$map->fullscreenposofsy.", right: ".(int)$map->fullscreenposofsx."}";
                            if ($mapVersion == "2.1")
                            {
                                $ctrlPrefix = "{ float: 'none', position: ";
                                $ctrlSuffix = "}";
                            }
                        break;
                        case 3:
                            // BOTTOM_RIGHT
                            $ctrlPosition = "{ bottom: ".(int)$map->fullscreenposofsy.", right: ".(int)$map->fullscreenposofsx."}";
                            if ($mapVersion == "2.1")
                            {
                                $ctrlPrefix = "{ float: 'none', position: ";
                                $ctrlSuffix = "}";
                            }
                        break;
                        case 4:
                            // BOTTOM_LEFT
                            $ctrlPosition = "{ bottom: ".(int)$map->fullscreenposofsy.", left: ".(int)$map->fullscreenposofsx."}";
                            if ($mapVersion == "2.1")
                            {
                                $ctrlPrefix = "{ float: 'none', position: ";
                                $ctrlSuffix = "}";
                            }
                        break;
                        case 13:
                            // FLOAT_RIGHT
                            if ($mapVersion == "2.1")
                            {
                                $ctrlPrefix = "{ float: 'right'";
                                $ctrlSuffix = "}";
                                $ctrlPosition = " ";
                            }
                        break;
                        case 14:
                            // FLOAT_LEFT
                            if ($mapVersion == "2.1")
                            {
                                $ctrlPrefix = "{ float: 'left'";
                                $ctrlSuffix = "}";
                                $ctrlPosition = " ";
                            }
                        break;                        
                        default:
                            $ctrlPosition = "";
                        break;
                    }
                    if ($ctrlPosition != "")
                    {
                        $ctrlPositionFullText = ', ' . $ctrlPrefix . $ctrlPosition . $ctrlSuffix;
                    }
                    else
                    {
                        $ctrlPositionFullText ="";
                    }
                }
                else
                {
                    $ctrlPositionFullText ="";
                }



                $scripttext .= 'map'.$mapDivSuffix.'.controls.add(new ymaps.control.FullscreenControl()'.$ctrlPositionFullText.');' ."\n";


            }

            if (isset($map->rulercontrol) && (int)$map->rulercontrol == 1) 
            {

                $ctrlPosition = "";
                $ctrlPositionFullText ="";

                $ctrlPrefix = "";
                $ctrlSuffix = "";
                if (isset($map->rulerpos)) 
                {
                    switch ($map->rulerpos)
                    {
                        case 1:
                            // TOP_LEFT
                            $ctrlPosition = "{ top: ".(int)$map->rulerposofsy.", left: ".(int)$map->rulerposofsx."}";
                            if ($mapVersion == "2.1")
                            {
                                $ctrlPrefix = "{ float: 'none', position: ";
                                $ctrlSuffix = "}";
                            }
                        break;
                        case 2:
                            // TOP_RIGHT
                            $ctrlPosition = "{ top: ".(int)$map->rulerposofsy.", right: ".(int)$map->rulerposofsx."}";
                            if ($mapVersion == "2.1")
                            {
                                $ctrlPrefix = "{ float: 'none', position: ";
                                $ctrlSuffix = "}";
                            }
                        break;
                        case 3:
                            // BOTTOM_RIGHT
                            $ctrlPosition = "{ bottom: ".(int)$map->rulerposofsy.", right: ".(int)$map->rulerposofsx."}";
                            if ($mapVersion == "2.1")
                            {
                                $ctrlPrefix = "{ float: 'none', position: ";
                                $ctrlSuffix = "}";
                            }
                        break;
                        case 4:
                            // BOTTOM_LEFT
                            $ctrlPosition = "{ bottom: ".(int)$map->rulerposofsy.", left: ".(int)$map->rulerposofsx."}";
                            if ($mapVersion == "2.1")
                            {
                                $ctrlPrefix = "{ float: 'none', position: ";
                                $ctrlSuffix = "}";
                            }
                        break;
                        case 13:
                            // FLOAT_RIGHT
                            if ($mapVersion == "2.1")
                            {
                                $ctrlPrefix = "{ float: 'right'";
                                $ctrlSuffix = "}";
                                $ctrlPosition = " ";
                            }
                        break;
                        case 14:
                            // FLOAT_LEFT
                            if ($mapVersion == "2.1")
                            {
                                $ctrlPrefix = "{ float: 'left'";
                                $ctrlSuffix = "}";
                                $ctrlPosition = " ";
                            }
                        break;                        
                        default:
                            $ctrlPosition = "";
                        break;
                    }
                    if ($ctrlPosition != "")
                    {
                        $ctrlPositionFullText = ', ' . $ctrlPrefix . $ctrlPosition . $ctrlSuffix;
                    }
                    else
                    {
                        $ctrlPositionFullText ="";
                    }
                }
                else
                {
                    $ctrlPositionFullText ="";
                }



                $scripttext .= 'map'.$mapDivSuffix.'.controls.add(new ymaps.control.RulerControl()'.$ctrlPositionFullText.');' ."\n";


            }


            
        }
            
	if (isset($licenseinfo) && (int)$licenseinfo != 0) 
	{
	
		if ((int)$licenseinfo == 102 // Map-License (into credits)
		  ) 
		{
			// Do not create button when L-M, M-L or external
			if ($credits != '')
			{
				$credits .= '<br />';
			}
			$credits .= ''.JText::_('COM_ZHYANDEXMAP_MAP_POWEREDBY').': ';
			$credits .= '<a href="http://www.zhuk.cc/" target="_blank" alt="'.JText::_('COM_ZHYANDEXMAP_MAP_POWEREDBY').'">zhuk.cc</a>';
		}
		else
		{
		}
	}
	
	if ($credits != '')
	{
		$scripttext .= '  document.getElementById("YMapsCredit'.$mapDivSuffix.'").innerHTML = \''.$credits.'\';'."\n";
	}

	//Search
	if (isset($map->search) && (int)$map->search == 1) 
	{
                $ctrlPosition = "";
                $ctrlPositionFullText ="";

                $ctrlPrefix = "";
                $ctrlSuffix = "";                    
                if (isset($map->searchpos)) 
                {
                    switch ($map->searchpos)
                    {
                        case 1:
                            // TOP_LEFT
                            $ctrlPosition = "{ top: ".(int)$map->searchofsy.", left: ".(int)$map->searchofsx."}";
                            if ($mapVersion == "2.1")
                            {
                                $ctrlPrefix = "{ float: 'none', position: ";
                                $ctrlSuffix = "}";
                            }                            
                        break;
                        case 2:
                            // TOP_RIGHT
                            $ctrlPosition = "{ top: ".(int)$map->searchofsy.", right: ".(int)$map->searchofsx."}";
                            if ($mapVersion == "2.1")
                            {
                                $ctrlPrefix = "{ float: 'none', position: ";
                                $ctrlSuffix = "}";
                            }  
                        break;
                        case 3:
                            // BOTTOM_RIGHT
                            $ctrlPosition = "{ bottom: ".(int)$map->searchofsy.", right: ".(int)$map->searchofsx."}";
                            if ($mapVersion == "2.1")
                            {
                                $ctrlPrefix = "{ float: 'none', position: ";
                                $ctrlSuffix = "}";
                            }  
                        break;
                        case 4:
                            // BOTTOM_LEFT
                            $ctrlPosition = "{ bottom: ".(int)$map->searchofsy.", left: ".(int)$map->searchofsx."}";
                            if ($mapVersion == "2.1")
                            {
                                $ctrlPrefix = "{ float: 'none', position: ";
                                $ctrlSuffix = "}";
                            }  
                        break;
                        case 13:
                            // FLOAT_RIGHT
                            if ($mapVersion == "2.1")
                            {
                                $ctrlPrefix = "{ float: 'right'";
                                $ctrlSuffix = "}";
                                $ctrlPosition = " ";
                            }
                        break;
                        case 14:
                            // FLOAT_LEFT
                            if ($mapVersion == "2.1")
                            {
                                $ctrlPrefix = "{ float: 'left'";
                                $ctrlSuffix = "}";
                                $ctrlPosition = " ";
                            }
                        break;                     
                        default:
                            $ctrlPosition = "";
                        break;
                    }
                    if ($ctrlPosition != "")
                    {
                        $ctrlPositionFullText = ', ' . $ctrlPrefix . $ctrlPosition . $ctrlSuffix;
                    }
                    else
                    {
                        $ctrlPositionFullText ="";
                    }
                }
                else
                {
                    $ctrlPositionFullText ="";
                }

                if ($mapVersion == "2.0")
                {
                    $searchPrefix = "";
                    $searchSuffix = ""; 
                }
                else
                {
                    $searchPrefix = "{ options: {";
                    $searchSuffix = "}}";                      
                }
                    
                // set it by default
                $searchProvider = "";               
                $searchFullText = $searchPrefix . $searchProvider . $searchSuffix;	
                // set it by default
                $searchFullText = "";
                // set it by default
                $scripttext .= 'searchControl'.$mapDivSuffix.' = new ymaps.control.SearchControl('. $searchFullText.');' ."\n";
                $scripttext .= 'map'.$mapDivSuffix.'.controls.add(searchControl'.$mapDivSuffix.''.$ctrlPositionFullText.');' ."\n";
                
                /* After merge MAP + PMAP only one provider
                $scripttext .= 'searchControlPMAP'.$mapDivSuffix.' = new ymaps.control.SearchControl({provider: "yandex#map"});' ."\n";
                //  
				$scripttext .= '   if ((map'.$mapDivSuffix.'.getType() == "yandex#publicMap") || (map'.$mapDivSuffix.'.getType() == "yandex#publicMapHybrid"))';
				$scripttext .= '   {';
				//$scripttext .= '	  alert("PMAP");' ."\n";
                $scripttext .= '	  map'.$mapDivSuffix.'.controls.add(searchControlPMAP'.$mapDivSuffix.''.$ctrlPositionFullText.');' ."\n";
				$scripttext .= '   }';
				$scripttext .= '   else';
				$scripttext .= '   {';
				//$scripttext .= '	  alert("MAP");' ."\n";
                $scripttext .= '	  map'.$mapDivSuffix.'.controls.add(searchControl'.$mapDivSuffix.''.$ctrlPositionFullText.');' ."\n";
				$scripttext .= '   }';
				

				$scripttext .= 'map'.$mapDivSuffix.'.events.add("typechange", function (e) {' ."\n";
				$scripttext .= '   if ((map'.$mapDivSuffix.'.getType() == "yandex#publicMap") || (map'.$mapDivSuffix.'.getType() == "yandex#publicMapHybrid"))';
				$scripttext .= '   {';
				//$scripttext .= '	  alert("PMAP");' ."\n";
				$scripttext .= '	  map'.$mapDivSuffix.'.controls.remove(searchControl'.$mapDivSuffix.');' ."\n";
				$scripttext .= '	  map'.$mapDivSuffix.'.controls.add(searchControlPMAP'.$mapDivSuffix.''.$ctrlPositionFullText.');' ."\n";
				$scripttext .= '   }';
				$scripttext .= '   else';
				$scripttext .= '   {';
				//$scripttext .= '	  alert("Map");' ."\n";
				$scripttext .= '	  map'.$mapDivSuffix.'.controls.remove(searchControlPMAP'.$mapDivSuffix.');' ."\n";
				$scripttext .= '	  map'.$mapDivSuffix.'.controls.add(searchControl'.$mapDivSuffix.''.$ctrlPositionFullText.');' ."\n";
				$scripttext .= '   }';
				$scripttext .= '});' ."\n";
                
                 */
				
	}



	//Traffic
	if (isset($map->traffic) && (int)$map->traffic == 1) 
	{
                $ctrlPosition = "";
                $ctrlPositionFullText ="";

                $ctrlPrefix = "";
                $ctrlSuffix = "";                    
                if (isset($map->trafficpos)) 
                {
                    switch ($map->trafficpos)
                    {
                        case 1:
                            // TOP_LEFT
                            $ctrlPosition = "{ top: ".(int)$map->trafficofsy.", left: ".(int)$map->trafficofsx."}";
                            if ($mapVersion == "2.1")
                            {
                                $ctrlPrefix = "{ float: 'none', position: ";
                                $ctrlSuffix = "}";
                            }                            
                        break;
                        case 2:
                            // TOP_RIGHT
                            $ctrlPosition = "{ top: ".(int)$map->trafficofsy.", right: ".(int)$map->trafficofsx."}";
                            if ($mapVersion == "2.1")
                            {
                                $ctrlPrefix = "{ float: 'none', position: ";
                                $ctrlSuffix = "}";
                            }                            
                        break;
                        case 3:
                            // BOTTOM_RIGHT
                            $ctrlPosition = "{ bottom: ".(int)$map->trafficofsy.", right: ".(int)$map->trafficofsx."}";
                            if ($mapVersion == "2.1")
                            {
                                $ctrlPrefix = "{ float: 'none', position: ";
                                $ctrlSuffix = "}";
                            }                            
                        break;
                        case 4:
                            // BOTTOM_LEFT
                            $ctrlPosition = "{ bottom: ".(int)$map->trafficofsy.", left: ".(int)$map->trafficofsx."}";
                            if ($mapVersion == "2.1")
                            {
                                $ctrlPrefix = "{ float: 'none', position: ";
                                $ctrlSuffix = "}";
                            }                            
                        break;
                        case 13:
                            // FLOAT_RIGHT
                            if ($mapVersion == "2.1")
                            {
                                $ctrlPrefix = "{ float: 'right'";
                                $ctrlSuffix = "}";
                                $ctrlPosition = " ";
                            }
                        break;
                        case 14:
                            // FLOAT_LEFT
                            if ($mapVersion == "2.1")
                            {
                                $ctrlPrefix = "{ float: 'left'";
                                $ctrlSuffix = "}";
                                $ctrlPosition = " ";
                            }
                        break;                     
                        default:
                            $ctrlPosition = "";
                        break;
                    }
                    if ($ctrlPosition != "")
                    {
                        $ctrlPositionFullText = ', ' . $ctrlPrefix . $ctrlPosition . $ctrlSuffix;
                    }
                    else
                    {
                        $ctrlPositionFullText ="";
                    }
                }
                else
                {
                    $ctrlPositionFullText ="";
                }
 
                if (isset($map->trafficprovider) && (int)$map->trafficprovider == 1) 
                {
                        $trafficProvider = 'providerKey: \'traffic#archive\'';
                }
                else
                {
                        $trafficProvider = 'providerKey: \'traffic#actual\'';
                }

                if (isset($map->trafficlayer) && (int)$map->trafficlayer != 0) 
                {
                    if ($mapVersion == "2.0")
                    {
                        $trafficProvider .= ', shown: true';
                    }
                    else
                    {              
                        $trafficProvider .= ', trafficShown: true';
                    }
                }
                
                if ($mapVersion == "2.0")
                {
                    $trafficPrefix = "";
                    $trafficSuffix = ""; 
                }
                else
                {
                    $trafficPrefix = "state: {";
                    $trafficSuffix = "}";                      
                }
                    
                $trafficFullText = $trafficPrefix . $trafficProvider . $trafficSuffix;
                

                
                $scripttext .= 'var trafficControl'.$mapDivSuffix.' = new ymaps.control.TrafficControl({'.$trafficFullText.'});' ."\n";

                $scripttext .= 'map'.$mapDivSuffix.'.controls.add(trafficControl'.$mapDivSuffix.$ctrlPositionFullText.');' ."\n";
                
                if (isset($map->trafficlayer) && (int)$map->trafficlayer == 2) 
                {
                   $scripttext .= 'trafficControl'.$mapDivSuffix.'.getProvider(\'traffic#actual\').state.set(\'infoLayerShown\', true);' ."\n";
                }                
                
	}


	/*
	if (isset($map->markermanager) && (int)$map->markermanager == 1) 
	{
		$scripttext .= 'var objectManager = new YMaps.ObjectManager();'."\n";
		$scripttext .= 'map'.$mapDivSuffix.'.addOverlay(objectManager);'."\n";
	}
	*/
	
	if (isset($map->rightbuttonmagnifier) && (int)$map->rightbuttonmagnifier == 1) 
	{
		$scripttext .= 'map'.$mapDivSuffix.'.behaviors.enable(\'rightMouseButtonMagnifier\');' ."\n";
	} 
	else 
	{
		$scripttext .= 'if (map'.$mapDivSuffix.'.behaviors.isEnabled(\'rightMouseButtonMagnifier\'))' ."\n";
		$scripttext .= 'map'.$mapDivSuffix.'.behaviors.disable(\'rightMouseButtonMagnifier\');' ."\n";
	}


	if (isset($map->magnifier)) 
	{
		switch ((int)$map->magnifier)
		{
			case 0:
			break;
			case 1:
				$scripttext .= 'map'.$mapDivSuffix.'.behaviors.enable(\'leftMouseButtonMagnifier\');'."\n";
			break;
			case 2:
				$scripttext .= 'map'.$mapDivSuffix.'.behaviors.enable(\'ruler\');'."\n";
			break;
			default:
			break;
		}
	}

	if (isset($map->draggable) && (int)$map->draggable == 1) 
	{
		$scripttext .= 'map'.$mapDivSuffix.'.behaviors.enable(\'drag\');' ."\n";
	} 
	else 
	{
		$scripttext .= 'if (map'.$mapDivSuffix.'.behaviors.isEnabled(\'drag\'))' ."\n";
		$scripttext .= 'map'.$mapDivSuffix.'.behaviors.disable(\'drag\');' ."\n";
	}

	/*
	
	//Grid Coordinates		
	if (isset($map->gridcoordinates) && (int)$map->gridcoordinates == 1) 
	{
		$scripttext .= 'map'.$mapDivSuffix.'.addLayer(new YMaps.Layer(new YMaps.TileDataSource("http://lrs.maps.yandex.net/tiles/?l=grd&v=1.0&%c", true, false)));' ."\n";
	}
	*/
	
	

	//UserMarker - begin
	if ($allowUserMarker == 1)
	{
		$db = JFactory::getDBO();
		
		$scripttext .= 'if (ymaps.geolocation) {' ."\n";
		$scripttext .= '  var insertPlacemarkLocation = [ymaps.geolocation.longitude, ymaps.geolocation.latitude];' ."\n";
		$scripttext .= '}else {' ."\n";
		$scripttext .= '  var insertPlacemarkLocation = [30.3158, 59.9388];' ."\n";
		$scripttext .= '}' ."\n";

		$scripttext .= 'var insertPlacemark = new ymaps.Placemark(insertPlacemarkLocation);' ."\n";

		$scripttext .= 'insertPlacemark.options.set("draggable", true);' ."\n";

		$scripttext .= 'insertPlacemark.properties.set("balloonContentHeader", "'.JText::_( 'COM_ZHYANDEXMAP_MAP_USER_NEWMARKER' ).'");' ."\n";
		$scripttext .= 'insertPlacemark.properties.set("balloonContentBody", "'.JText::_( 'COM_ZHYANDEXMAP_MAP_USER_NEWMARKER_DESC' ).'");' ."\n";
		

		$scripttext .= 'map'.$mapDivSuffix.'.geoObjects.add(insertPlacemark);' ."\n";

		$query = $db->getQuery(true);
		
		$query->select('h.title as text, h.id as value ');
		$query->from('#__zhyandexmaps_markergroups as h');
		$query->leftJoin('#__categories as c ON h.catid=c.id');
		$query->where('1=1');
		// get all groups, because you can add marker and disable group
		//$query->where('h.published=1');
		$query->order('h.title');
		
		$db->setQuery($query);    

		if (!$db->query())
		{
			$scripttext .= 'alert("Error (Load Group List Item): " + "' . $db->escape($db->getErrorMsg()).'");';
		}
		else
		{
			$newMarkerGroupList = $db->loadObjectList();
		}
		

		$scripttext .= 'var contentInsertPlacemarkPart1 = \'<div id="contentInsertPlacemark">\' +' ."\n";
		$scripttext .= '\'<'.$placemarkTitleTag.' id="headContentInsertPlacemark" class="insertPlacemarkHead">'.
			'<img src="'.$imgpathUtils.'published'.(int)$map->usermarkerspublished.'.png" alt="" /> '.
			JText::_( 'COM_ZHYANDEXMAP_MAP_USER_NEWMARKER' ).'</'.$placemarkTitleTag.'>\'+' ."\n";
		$scripttext .= '\'<div id="bodyContentInsertPlacemark"  class="insertPlacemarkBody">\'+'."\n";
		//$scripttext .= '    \''.JText::_( 'COM_ZHYANDEXMAP_MAP_USER_LNG' ).' \'+insertPlacemarkLocation.getLng() + ' ."\n";
		//$scripttext .= '    \'<br />'.JText::_( 'COM_ZHYANDEXMAP_MAP_USER_LAT' ).' \'+insertPlacemarkLocation.getLat() + ' ."\n";
		$scripttext .= '    \'<form id="insertPlacemarkForm" action="'.JURI::current().'" method="post">\'+'."\n";

		// Begin Placemark Properties
		$scripttext .= '\'<div id="bodyInsertPlacemarkDivA"  class="bodyInsertProperties">\'+'."\n";
		$scripttext .= '\'<a id="bodyInsertPlacemarkA" href="javascript:showonlyone(\\\'Placemark\\\',\\\'\\\');" ><img src="'.$imgpathUtils.'collapse.png">'.JText::_( 'COM_ZHYANDEXMAP_MAP_USER_BASIC_PROPERTIES' ).'</a>\'+'."\n";
		$scripttext .= '\'</div>\'+'."\n";
		$scripttext .= '\'<div id="bodyInsertPlacemark"  class="bodyInsertPlacemarkProperties">\'+'."\n";
		$scripttext .= '    \''.JText::_( 'COM_ZHYANDEXMAP_MAP_USER_NAME' ).' \'+' ."\n";
		$scripttext .= '    \'<br />\'+' ."\n";
		$scripttext .= '    \'<input name="markername" type="text" maxlength="250" size="50" />\'+' ."\n";
		$scripttext .= '    \'<br />\'+' ."\n";
		$scripttext .= '    \''.JText::_( 'COM_ZHYANDEXMAP_MAP_USER_DESCRIPTION' ).' \'+' ."\n";
		$scripttext .= '    \'<br />\'+' ."\n";
		$scripttext .= '    \'<input name="markerdescription" type="text" maxlength="250" size="50" />\'+' ."\n";

		$scripttext .= '    \'<br />\'+' ."\n";
		$scripttext .= '    \''.JText::_( 'COM_ZHYANDEXMAP_MAPMARKER_DETAIL_HREFIMAGE_LABEL' ).' \'+' ."\n";
		$scripttext .= '    \'<br />\'+' ."\n";
		$scripttext .= '    \'<input name="markerhrefimage" type="text" maxlength="500" size="50" value="" />\'+' ."\n";
		$scripttext .= '    \'<br />\'+' ."\n";

		$scripttext .= '    \'<br />\';' ."\n";

		// icon type
		$scripttext .= 'var contentInsertPlacemarkIcon = "" +' ."\n";
		if (isset($map->usermarkersicon) && (int)$map->usermarkersicon == 1) 
		{
			$iconTypeJS = " onchange=\"javascript: ";
			$iconTypeJS .= " if (document.forms.insertPlacemarkForm.markerimage.options[selectedIndex].value!=\'\') ";
			$iconTypeJS .= " {document.markericonimage.src=\'".$imgpathIcons."\' + document.forms.insertPlacemarkForm.markerimage.options[selectedIndex].value.replace(/#/g,\'%23\') + \'.png\'}";
			$iconTypeJS .= " else ";
			$iconTypeJS .= " {document.markericonimage.src=\'\'}\"";
			
			$scripttext .= '    \''.JText::_( 'COM_ZHYANDEXMAP_MAP_USER_ICON_TYPE' ).' \'+' ."\n";
			$scripttext .= ' \'';
			$scripttext .= '<img name="markericonimage" src="" alt="" />';
			$scripttext .= '\'+' ."\n";
			$scripttext .= '    \'<br />\'+' ."\n";
			$scripttext .= ' \'';
			$scripttext .= str_replace('.png<', '<', 
								str_replace('.png"', '"', 
									str_replace('JOPTION_SELECT_IMAGE', JText::_('COM_ZHYANDEXMAP_MAP_USER_IMAGESELECT'),
										str_replace(array("\r", "\r\n", "\n"),'', JHTML::_('list.images',  'markerimage', $active =  "", $iconTypeJS, $directoryIcons, $extensions =  "png")))));
			$scripttext .= '\'+' ."\n";
			$scripttext .= '    \'<br />\';' ."\n";	

		}
		else
		{
			$scripttext .= '    \'<input name="markerimage" type="hidden" value="default#" />\'+' ."\n";	
		}
		$scripttext .= '    \'\';' ."\n";


		$scripttext .= 'var contentInsertPlacemarkPart2 = "" +' ."\n";
		
		$scripttext .= '    \'<br />\'+' ."\n";

		$scripttext .= '\'</div>\'+'."\n";
		// End Placemark Properties

		// Begin Placemark Group Properties
		if (isset($map->showbelong) && (int)$map->showbelong == 1)
		{
			$scripttext .= '\'<div id="bodyInsertPlacemarkGrpDivA"  class="bodyInsertProperties">\'+'."\n";
			$scripttext .= '\'<a id="bodyInsertPlacemarkGrpA" href="javascript:showonlyone(\\\'PlacemarkGroup\\\',\\\'\\\');" ><img src="'.$imgpathUtils.'expand.png">'.JText::_( 'COM_ZHYANDEXMAP_MAP_USER_BASIC_GROUP_PROPERTIES' ).'</a>\'+'."\n";
			$scripttext .= '\'</div>\'+'."\n";
			$scripttext .= '\'<div id="bodyInsertPlacemarkGrp"  class="bodyInsertPlacemarkGrpProperties">\'+'."\n";
			$scripttext .= '    \''.JText::_( 'COM_ZHYANDEXMAP_MAP_USER_GROUP' ).' \'+' ."\n";
			$scripttext .= '    \'<br />\'+' ."\n";
			
			$scripttext .= '    \' <select name="markergroup" > \'+' ."\n";
			$scripttext .= '    \' <option value="" selected="selected">'.JText::_( 'COM_ZHYANDEXMAP_MAPMARKER_FILTER_PLACEMARK_GROUP').'</option> \'+' ."\n";
			foreach ($newMarkerGroupList as $key => $newGrp) 
			{
				$scripttext .= '    \' <option value="'.$newGrp->value.'">'.$newGrp->text.'</option> \'+' ."\n";
			}
			$scripttext .= '    \' </select> \'+' ."\n";
			
			$scripttext .= '    \'<br />\'+' ."\n";

			$scripttext .= '    \''.JText::_( 'COM_ZHYANDEXMAP_MAP_USER_CATEGORY' ).' \'+' ."\n";
			$scripttext .= '    \'<br />\'+' ."\n";
			$scripttext .= '    \' <select name="markercatid" > \'+' ."\n";
			$scripttext .= '    \' <option value="" selected="selected">'.JText::_( 'COM_ZHYANDEXMAP_MAP_FILTER_CATEGORY').'</option> \'+' ."\n";
			$scripttext .= '    \''.str_replace(array("\r", "\r\n", "\n"),'', 
								   JHtml::_('select.options', JHtml::_('category.options', 'com_zhyandexmap'), 'value', 'text', '')) .
								   '\'+' ."\n";
			$scripttext .= '    \' </select> \'+' ."\n";
			$scripttext .= '    \'<br />\'+' ."\n";
			$scripttext .= '    \'<br />\'+' ."\n";
			$scripttext .= '\'</div>\'+'."\n";
		}
		// End Placemark Group Properties
		
		// Begin Contact Properties
		if (isset($map->usercontact) && (int)$map->usercontact == 1) 
		{

				$scripttext .= '\'<div id="bodyInsertContactDivA"  class="bodyInsertProperties">\'+'."\n";
				$scripttext .= '\'<a id="bodyInsertContactA" href="javascript:showonlyone(\\\'Contact\\\',\\\'\\\');" ><img src="'.$imgpathUtils.'expand.png">'.JText::_( 'COM_ZHYANDEXMAP_MAP_USER_CONTACT_PROPERTIES' ).'</a>\'+'."\n";
				$scripttext .= '\'</div>\'+'."\n";
				$scripttext .= '\'<div id="bodyInsertContact"  class="bodyInsertContactProperties">\'+'."\n";
				$scripttext .= '\'<img src="'.$imgpathUtils.'published'.(int)$map->usercontactpublished.'.png" alt="" /> \'+'."\n";
				$scripttext .= '    \''.JText::_( 'COM_ZHYANDEXMAP_MAP_USER_CONTACT_NAME' ).' \'+' ."\n";
				$scripttext .= '    \'<br />\'+' ."\n";
				$scripttext .= '    \'<input name="contactname" type="text" maxlength="250" size="50" />\'+' ."\n";
				$scripttext .= '    \'<br />\'+' ."\n";
				$scripttext .= '    \''.JText::_( 'COM_ZHYANDEXMAP_MAP_USER_CONTACT_POSITION' ).' \'+' ."\n";
				$scripttext .= '    \'<br />\'+' ."\n";
				$scripttext .= '    \'<input name="contactposition" type="text" maxlength="250" size="50" />\'+' ."\n";
				$scripttext .= '    \'<br />\'+' ."\n";
				$scripttext .= '    \''.JText::_( 'COM_ZHYANDEXMAP_MAP_USER_CONTACT_PHONE' ).' \'+' ."\n";
				$scripttext .= '    \'<br />\'+' ."\n";
				$scripttext .= '    \'<input name="contactphone" type="text" maxlength="250" size="50" />\'+' ."\n";
				$scripttext .= '    \'<br />\'+' ."\n";
				$scripttext .= '    \''.JText::_( 'COM_ZHYANDEXMAP_MAP_USER_CONTACT_MOBILE' ).' \'+' ."\n";
				$scripttext .= '    \'<br />\'+' ."\n";
				$scripttext .= '    \'<input name="contactmobile" type="text" maxlength="250" size="50" />\'+' ."\n";
				$scripttext .= '    \'<br />\'+' ."\n";
				$scripttext .= '    \''.JText::_( 'COM_ZHYANDEXMAP_MAP_USER_CONTACT_FAX' ).' \'+' ."\n";
				$scripttext .= '    \'<br />\'+' ."\n";
				$scripttext .= '    \'<input name="contactfax" type="text" maxlength="250" size="50" />\'+' ."\n";
				$scripttext .= '    \'<br />\'+' ."\n";
				$scripttext .= '    \'<input name="contactid" type="hidden" value="" />\'+' ."\n";
				$scripttext .= '\'</div>\'+'."\n";
				// Contact Address
				$scripttext .= '\'<div id="bodyInsertContactAdrDivA"  class="bodyInsertProperties">\'+'."\n";
				$scripttext .= '\'<a id="bodyInsertContactAdrA" href="javascript:showonlyone(\\\'ContactAddress\\\',\\\'\\\');" ><img src="'.$imgpathUtils.'expand.png">'.JText::_( 'COM_ZHYANDEXMAP_MAP_USER_CONTACT_ADDRESS_PROPERTIES' ).'</a>\'+'."\n";
				$scripttext .= '\'</div>\'+'."\n";
				$scripttext .= '\'<div id="bodyInsertContactAdr"  class="bodyInsertContactAdrProperties">\'+'."\n";
				$scripttext .= '    \''.JText::_( 'COM_ZHYANDEXMAP_MAP_USER_CONTACT_ADDRESS' ).' \'+' ."\n";
				$scripttext .= '    \'<br />\'+' ."\n";
				$scripttext .= '    \'<textarea name="contactaddress" cols="35" rows="4"></textarea>\'+' ."\n";
				$scripttext .= '    \'<br />\'+' ."\n";
				$scripttext .= '    \'<br />\'+' ."\n";
				$scripttext .= '\'</div>\'+'."\n";
		}
		// End Contact Properties
		$scripttext .= '\'\';'."\n";



		$scripttext .= 'insertPlacemark.events.add("drag", function (e) {' ."\n";

		$scripttext .= '    insertPlacemark.balloon.close();' ."\n";
		$scripttext .= '    insertPlacemarkLocation = insertPlacemark.geometry.getCoordinates();' ."\n";

		$scripttext .= '  contentInsertPlacemarkButtons = \'<div id="contentInsertPlacemarkButtons">\' +' ."\n";
		$scripttext .= '    \'<hr />\'+' ."\n";					
		$scripttext .= '    \'<input name="markerlat" type="hidden" value="\'+insertPlacemarkLocation[1] + \'" />\'+' ."\n";
		$scripttext .= '    \'<input name="markerlng" type="hidden" value="\'+insertPlacemarkLocation[0] + \'" />\'+' ."\n";
		$scripttext .= '    \'<input name="markerid" type="hidden" value="" />\'+' ."\n";
		$scripttext .= '    \'<input name="contactid" type="hidden" value="" />\'+' ."\n";
		$scripttext .= '    \'<input name="marker_action" type="hidden" value="insert" />\'+' ."\n";	
		$scripttext .= '    \'<input name="markersubmit" type="submit" value="'.JText::_( 'COM_ZHYANDEXMAP_MAP_USER_BUTTON_ADD' ).'" />\'+' ."\n";
		$scripttext .= '    \'</form>\'+' ."\n";		
		$scripttext .= '\'</div>\'+'."\n";
		$scripttext .= '\'</div>\';'."\n";
		
		$scripttext .= 'insertPlacemark.properties.set("balloonContentHeader", "");' ."\n";
		$scripttext .= 'insertPlacemark.properties.set("balloonContentBody", "");' ."\n";

		$scripttext .= '    insertPlacemark.properties.set("balloonContent", contentInsertPlacemarkPart1+';
		$scripttext .= 'contentInsertPlacemarkIcon+';
		$scripttext .= 'contentInsertPlacemarkPart2+';
		$scripttext .= 'contentInsertPlacemarkButtons);'."\n";

		$scripttext .= '});' ."\n";

		
		$scripttext .= 'map'.$mapDivSuffix.'.events.add("click", function (e) {' ."\n";
		$scripttext .= '    insertPlacemark.balloon.close();' ."\n";
		
                if ($mapVersion == "2.0")
                {         
                    $scripttext .= '    insertPlacemarkLocation = e.get(\'coordPosition\');' ."\n";
                }
                else
                {
                    $scripttext .= '    insertPlacemarkLocation = e.get(\'coords\');' ."\n";
                }                
                
		$scripttext .= '    insertPlacemark.geometry.setCoordinates(insertPlacemarkLocation);' ."\n";

		$scripttext .= '  contentInsertPlacemarkButtons = \'<div id="contentInsertPlacemarkButtons">\' +' ."\n";
		$scripttext .= '    \'<hr />\'+' ."\n";					
		$scripttext .= '    \'<input name="markerlat" type="hidden" value="\'+insertPlacemarkLocation[1] + \'" />\'+' ."\n";
		$scripttext .= '    \'<input name="markerlng" type="hidden" value="\'+insertPlacemarkLocation[0] + \'" />\'+' ."\n";
		$scripttext .= '    \'<input name="markerid" type="hidden" value="" />\'+' ."\n";
		$scripttext .= '    \'<input name="contactid" type="hidden" value="" />\'+' ."\n";
		$scripttext .= '    \'<input name="marker_action" type="hidden" value="insert" />\'+' ."\n";	
		$scripttext .= '    \'<input name="markersubmit" type="submit" value="'.JText::_( 'COM_ZHYANDEXMAP_MAP_USER_BUTTON_ADD' ).'" />\'+' ."\n";
		$scripttext .= '    \'</form>\'+' ."\n";		
		$scripttext .= '\'</div>\'+'."\n";
		$scripttext .= '\'</div>\';'."\n";
		
		$scripttext .= 'insertPlacemark.properties.set("balloonContentHeader", "");' ."\n";
		$scripttext .= 'insertPlacemark.properties.set("balloonContentBody", "");' ."\n";

		$scripttext .= '    insertPlacemark.properties.set("balloonContent", contentInsertPlacemarkPart1+';
		$scripttext .= 'contentInsertPlacemarkIcon+';
		$scripttext .= 'contentInsertPlacemarkPart2+';
		$scripttext .= 'contentInsertPlacemarkButtons);'."\n";
		
		$scripttext .= '});' ."\n";
		
	}
	// New Marker - End
        
		

	//Balloon	
	if (isset($map->balloon)) 
	{
		switch ($map->balloon) 
		{
			case 0:
			break;
			case 1:
				$scripttext .= 'map'.$mapDivSuffix.'.balloon.open(['.$map->longitude.', ' .$map->latitude.'], { contentBody: "'.htmlspecialchars(str_replace('\\', '/', $map->title), ENT_QUOTES, 'UTF-8').'"});' ."\n";
			break;
			case 2:
				$scripttext .= 'var placemark = new ymaps.Placemark(['.$map->longitude.', ' .$map->latitude.']);' ."\n";
				if ($map->preseticontype != "")
				{
					$scripttext .= 'placemark.options.set("preset", "'.$map->preseticontype.'");' ."\n";
				}
				else
				{
					$scripttext .= 'placemark.options.set("preset", "twirl#blueStretchyIcon");' ."\n";
				}
				$scripttext .= 'placemark.properties.set("balloonContentHeader", "' .htmlspecialchars(str_replace('\\', '/', $map->title), ENT_QUOTES, 'UTF-8').'");' ."\n";
				$scripttext .= 'placemark.properties.set("balloonContentBody", "' .htmlspecialchars(str_replace('\\', '/', $map->description), ENT_QUOTES, 'UTF-8').'");' ."\n";
				$scripttext .= 'map'.$mapDivSuffix.'.geoObjects.add(placemark);' ."\n";
				$scripttext .= 'placemark.balloon.open();' ."\n";
			break;
			case 3:
				$scripttext .= 'var placemark = new ymaps.Placemark(['.$map->longitude.', ' .$map->latitude.']);' ."\n";
				
				if ($map->preseticontype != "")
				{
					$scripttext .= 'placemark.options.set("preset", "'.$map->preseticontype.'");' ."\n";
				}
				else
				{
					$scripttext .= 'placemark.options.set("preset", "twirl#blueStretchyIcon");' ."\n";
				}
				$scripttext .= 'placemark.properties.set("balloonContentHeader", "' .htmlspecialchars(str_replace('\\', '/', $map->title), ENT_QUOTES, 'UTF-8').'");' ."\n";
				$scripttext .= 'placemark.properties.set("balloonContentBody", "' .htmlspecialchars(str_replace('\\', '/', $map->description), ENT_QUOTES, 'UTF-8').'");' ."\n";
				$scripttext .= 'placemark.properties.set("iconContent", "' .htmlspecialchars(str_replace('\\', '/', $map->title), ENT_QUOTES, 'UTF-8').'");' ."\n";
				$scripttext .= 'map'.$mapDivSuffix.'.geoObjects.add(placemark);' ."\n";
			break;
			default:
				$scripttext .= '' ."\n";
			break;
		}
	}


	if (isset($map->markerlistpos) && (int)$map->markerlistpos != 0) 
	{
		if ((int)$map->markerlistcontent < 100) 
		{
			$scripttext .= 'var markerUL = document.getElementById("YMapsMarkerUL'.$mapDivSuffix.'");'."\n";
			$scripttext .= 'if (!markerUL)'."\n";
			$scripttext .= '{'."\n";
			$scripttext .= ' alert("'.JText::_('COM_ZHYANDEXMAP_MAP_MARKERUL_NOTFIND').'");'."\n";
			$scripttext .= '}'."\n";
		}
		else
		{
			$scripttext .= 'var markerUL = document.getElementById("YMapsMarkerTABLEBODY'.$mapDivSuffix.'");'."\n";
			$scripttext .= 'if (!markerUL)'."\n";
			$scripttext .= '{'."\n";
			$scripttext .= ' alert("'.JText::_('COM_ZHYANDEXMAP_MAP_MARKERTABLE_NOTFIND').'");'."\n";
			$scripttext .= '}'."\n";
		}
		
	}
		
		
	
	// Markers
	$doAddToListCount = 0;
	
	if (isset($markers) && !empty($markers)) 
	{

		foreach ($markers as $key => $currentmarker) 
		{
				// Main IF Begin
				if ( 
					((($currentmarker->markergroup != 0)
					    && ((int)$currentmarker->published == 1)
						&& ((int)$currentmarker->publishedgroup == 1)) || ($allowUserMarker == 1)
					) || 
					((($currentmarker->markergroup == 0)
					    && ((int)$currentmarker->published == 1)) || ($allowUserMarker == 1)
					) 
				   )
				{
					$markername ='';
					$markername = 'marker'. $currentmarker->id;

					$scripttext .= 'var latlng'.$currentmarker->id.'= ['.$currentmarker->longitude.', ' .$currentmarker->latitude.'];' ."\n";
					
					$scripttext .= 'var '.$markername.'= new ymaps.Placemark(latlng'.$currentmarker->id.');'."\n";

					if ($externalmarkerlink == 1)
					{
						$scripttext .= 'PlacemarkByIDAdd('. $currentmarker->id.
						                               ', '.$currentmarker->latitude.
													   ', ' .$currentmarker->longitude.
													   ', marker'. $currentmarker->id.
													   ', latlng'. $currentmarker->id.');'."\n";
					}

					
					//$scripttext .= '  marker'. $currentmarker->id.'.properties.set("zhymRating", '.$currentmarker->rating_value.');' ."\n";							
					$scripttext .= '  marker'. $currentmarker->id.'.properties.set("zhymPlacemarkID", '.$currentmarker->id.');' ."\n";							
					$scripttext .= '  marker'. $currentmarker->id.'.properties.set("zhymContactAttrs", userContactAttrs);' ."\n";
					$scripttext .= '  marker'. $currentmarker->id.'.properties.set("zhymUserContact", "'.str_replace(';', ',', $map->usercontact).'");' ."\n";
					$scripttext .= '  marker'. $currentmarker->id.'.properties.set("zhymUserUser", "'.str_replace(';', ',', $map->useruser).'");' ."\n";
					$scripttext .= '  marker'. $currentmarker->id.'.properties.set("zhymOriginalPosition", latlng'.$currentmarker->id.');' ."\n";
                                        
                                        $scripttext .= '  marker'. $currentmarker->id.'.properties.set("zhymTitle", "'.str_replace('\\', '/', str_replace('"', '\'\'', $currentmarker->title)).'");' ."\n";	
                                        $scripttext .= '  marker'. $currentmarker->id.'.properties.set("zhymIncludeInList", '.$currentmarker->includeinlist.');' ."\n";
                                                        
					//$scripttext .= '  marker'. $currentmarker->id.'.properties.set("zhymInfowinContent", contentString'. $currentmarker->id.');' ."\n";	
					
					if (($featureSpider != 0)
					|| ((int)$placemarkSearch != 0))					
					{
						$scripttext .= '  zhymObjMgr'.$mapDivSuffix.'.allObjectsAddPlacemark('. $currentmarker->id.', marker'. $currentmarker->id.');'."\n";
					}


					
					if ((int)$currentmarker->actionbyclick == 1)
					{
						$scripttext .= $markername.'.options.set("hasBalloon", true);'."\n";
					}
					else
					{
						$scripttext .= $markername.'.options.set("hasBalloon", false);'."\n";
					}
					

					$scripttext .= $markername.'.properties.set("hintContent", \''.htmlspecialchars(str_replace('\\', '/', $currentmarker->title), ENT_QUOTES, 'UTF-8').'\');'."\n";
					
					if (((int)$currentmarker->overridemarkericon == 1)
					  && ((int)$currentmarker->publishedgroup == 1)
					)
					{
							$imgimg = $imgpathIcons.str_replace("#", "%23", $currentmarker->groupicontype).'.png';
							$imgimg4size = $imgpath4size.$currentmarker->groupicontype.'.png';

							list ($imgwidth, $imgheight) = getimagesize($imgimg4size);

                                                        if ($mapVersion != "2.0")
                                                        {
                                                            $scripttext .= $markername.'.options.set("iconLayout", \'default#image\');' ."\n";
                                                        }                                                        
							$scripttext .= $markername.'.options.set("iconImageHref", "'.$imgimg.'");' ."\n";
							$scripttext .= $markername.'.options.set("iconImageSize", ['.$imgwidth.','.$imgheight.']);' ."\n";
							if (substr($currentmarker->groupicontype, 0, 8) == "default#")
							{
								$offset_fix = 7;
							}
							else
							{
								$offset_fix = $imgwidth/2;
							}
							if (isset($currentmarker->groupiconofsetx) 
							 && isset($currentmarker->groupiconofsety) 
							// Write offset all time
							// && ((int)$currentmarker->groupiconofsetx !=0
							//  || (int)$currentmarker->groupiconofsety !=0)
							 )
							{
								// This is for compatibility
								$ofsX = (int)$currentmarker->groupiconofsetx - $offset_fix;
								$ofsY = (int)$currentmarker->groupiconofsety - $imgheight;
								$scripttext .= $markername.'.options.set("iconImageOffset", ['.$ofsX.','.$ofsY.']);' ."\n";
							}
					}
					else
					{
						if ((int)$currentmarker->showiconcontent == 0)
						{
							$imgimg = $imgpathIcons.str_replace("#", "%23", $currentmarker->icontype).'.png';
							$imgimg4size = $imgpath4size.$currentmarker->icontype.'.png';

							list ($imgwidth, $imgheight) = getimagesize($imgimg4size);

                                                        if ($mapVersion != "2.0")
                                                        {
                                                            $scripttext .= $markername.'.options.set("iconLayout", \'default#image\');' ."\n";
                                                        }
							$scripttext .= $markername.'.options.set("iconImageHref", "'.$imgimg.'");' ."\n";
							$scripttext .= $markername.'.options.set("iconImageSize", ['.$imgwidth.','.$imgheight.']);' ."\n";
							if (substr($currentmarker->icontype, 0, 8) == "default#")
							{
								$offset_fix = 7;
							}
							else
							{
								$offset_fix = $imgwidth/2;
							}
							if (isset($currentmarker->iconofsetx) 
							 && isset($currentmarker->iconofsety) 
							// Write offset all time
							// && ((int)$currentmarker->iconofsetx !=0
							//  || (int)$currentmarker->iconofsety !=0)
							 )
							{
								// This is for compatibility
								$ofsX = (int)$currentmarker->iconofsetx - $offset_fix;
								$ofsY = (int)$currentmarker->iconofsety - $imgheight;
								$scripttext .= $markername.'.options.set("iconImageOffset", ['.$ofsX.','.$ofsY.']);' ."\n";
							}
						}
						else
						{
							if ($currentmarker->preseticontype != "")
							{
								$scripttext .= $markername.'.options.set("preset", "'.$currentmarker->preseticontype.'");' ."\n";
							}
							else
							{
								$scripttext .= $markername.'.options.set("preset", "twirl#blueStretchyIcon");' ."\n";
							}
							
							if ((int)$currentmarker->showiconcontent == 1)
							{
								if ($currentmarker->presettitle != "")
								{
									$scripttext .= $markername.'.properties.set("iconContent", "' .htmlspecialchars(str_replace('\\', '/', $currentmarker->presettitle), ENT_QUOTES, 'UTF-8').'");' ."\n";
								}
								else
								{
									$scripttext .= $markername.'.properties.set("iconContent", "' .htmlspecialchars(str_replace('\\', '/', $currentmarker->title), ENT_QUOTES, 'UTF-8').'");' ."\n";
								}
							}
						}

					}
					

						

					if (($allowUserMarker == 0)
					 || (isset($currentmarker->userprotection) && (int)$currentmarker->userprotection == 1)
					 || ($currentUserID == 0)
					 || (isset($currentmarker->createdbyuser) 
						&& (((int)$currentmarker->createdbyuser != $currentUserID )
						   || ((int)$currentmarker->createdbyuser == 0)))
					 )
					{
						
						if (isset($map->useajax) && (int)$map->useajax != 0)
						{
							// do not create listeners, create by loop only in the end
							$scripttext .= '  ajaxmarkersLL'.$mapDivSuffix.'.push(marker'. $currentmarker->id.');'."\n";
						}
						else
						{
											
								$scripttext .= 'var contentStringHead'. $currentmarker->id.' = '.
											comZhYandexMapPlacemarksHelper::get_placemark_content_string_header(
												$mapDivSuffix,
												$currentmarker, $map->usercontact, $map->useruser,
												$userContactAttrs, $service_DoDirection,
												$imgpathIcons, $imgpathUtils, $directoryIcons, 
												$map->placemark_rating, $map->lang, $placemarkTitleTag, $map->showcreateinfo);
												
								
								
								
								$scripttext .= 'var contentStringHeadCluster'. $currentmarker->id.' = '.
											comZhYandexMapPlacemarksHelper::get_placemark_content_string_header_cluster(
												$mapDivSuffix,
												$currentmarker, $map->usercontact, $map->useruser,
												$userContactAttrs, $service_DoDirection,
												$imgpathIcons, $imgpathUtils, $directoryIcons, 
												$map->placemark_rating, $map->lang, $placemarkTitleTag, $map->showcreateinfo);
												
								

								$scripttext .= 'var contentStringBody'. $currentmarker->id.' = '.
								            comZhYandexMapPlacemarksHelper::get_placemark_content_string_body(
												$mapDivSuffix,
												$currentmarker, $map->usercontact, $map->useruser,
												$userContactAttrs, $service_DoDirection,
												$imgpathIcons, $imgpathUtils, $directoryIcons, 
												$map->placemark_rating, $map->lang, $placemarkTitleTag, $map->showcreateinfo);

								if ((isset($map->markercluster) && (int)$map->markercluster != 0))
								{
										$scripttext .= $markername.'.properties.set("clusterCaption", contentStringHeadCluster'. $currentmarker->id.');' ."\n";
								}
	
	
								// Action By Click - Begin							
								switch ((int)$currentmarker->actionbyclick)
								{
									// None
									case 0:
										if ((int)$currentmarker->zoombyclick != 100)
										{
											$scripttext .= $markername.'.events.add("click", function (e) {' ."\n";
											$scripttext .= '  map'.$mapDivSuffix.'.setCenter(latlng'. $currentmarker->id.');' ."\n";
											$scripttext .= '  map'.$mapDivSuffix.'.setZoom('.(int)$currentmarker->zoombyclick.');' ."\n";
											$scripttext .= '});' ."\n";
										}
									break;
									// Info
									case 1:
										// Moved out trigger, because cluster get info into its balloon
										$scripttext .= $markername.'.properties.set("balloonContentHeader", contentStringHead'. $currentmarker->id.');' ."\n";
										$scripttext .= $markername.'.properties.set("balloonContentBody", contentStringBody'. $currentmarker->id.');' ."\n";

										$scripttext .= $markername.'.events.add("click", function (e) {' ."\n";
										if ((int)$currentmarker->zoombyclick != 100)
										{
											$scripttext .= '  map'.$mapDivSuffix.'.setCenter(latlng'. $currentmarker->id.');' ."\n";
											$scripttext .= '  map'.$mapDivSuffix.'.setZoom('.(int)$currentmarker->zoombyclick.');' ."\n";
										}
										
										$scripttext .= '  });' ."\n";
									break;
									// Link
									case 2:
										if ($currentmarker->hrefsite != "")
										{
											$scripttext .= $markername.'.events.add("click", function (e) {' ."\n";
											if ((int)$currentmarker->zoombyclick != 100)
											{
												$scripttext .= '  map'.$mapDivSuffix.'.setCenter(latlng'. $currentmarker->id.');' ."\n";
												$scripttext .= '  map'.$mapDivSuffix.'.setZoom('.(int)$currentmarker->zoombyclick.');' ."\n";
											}
											$scripttext .= '  window.open("'.$currentmarker->hrefsite.'");' ."\n";
											$scripttext .= '  });' ."\n";
										}
										else
										{
											if ((int)$currentmarker->zoombyclick != 100)
											{
												$scripttext .= $markername.'.events.add("click", function (e) {' ."\n";
												$scripttext .= '  map'.$mapDivSuffix.'.setCenter(latlng'. $currentmarker->id.');' ."\n";
												$scripttext .= '  map'.$mapDivSuffix.'.setZoom('.(int)$currentmarker->zoombyclick.');' ."\n";
												$scripttext .= '  });' ."\n";
											}
										}
									break;
									// Link in self
									case 3:
										if ($currentmarker->hrefsite != "")
										{
											$scripttext .= $markername.'.events.add("click", function (e) {' ."\n";
											if ((int)$currentmarker->zoombyclick != 100)
											{
												$scripttext .= '  map'.$mapDivSuffix.'.setCenter(latlng'. $currentmarker->id.');' ."\n";
												$scripttext .= '  map'.$mapDivSuffix.'.setZoom('.(int)$currentmarker->zoombyclick.');' ."\n";
											}
											$scripttext .= '  window.location = "'.$currentmarker->hrefsite.'";' ."\n";
											$scripttext .= '  });' ."\n";
										}
										else
										{
											if ((int)$currentmarker->zoombyclick != 100)
											{
												$scripttext .= $markername.'.events.add("click", function (e) {' ."\n";
												$scripttext .= '  map'.$mapDivSuffix.'.setCenter(latlng'. $currentmarker->id.');' ."\n";
												$scripttext .= '  map'.$mapDivSuffix.'.setZoom('.(int)$currentmarker->zoombyclick.');' ."\n";
												$scripttext .= '  });' ."\n";
											}
										}
									break;
									default:
										$scripttext .= '' ."\n";
									break;
								}
								
								// Action By Click - End

												
						}
						

					}
					else
					{
						// Change UserMarker - begin
						$scripttext .= $markername.'.options.set("draggable", true);' ."\n";
						
						//$scripttext .= 'contentString'.$currentmarker->id.' = contentString'.$currentmarker->id.'+' ."\n";
						// replace content
						$scripttext .= 'var contentStringHeadCluster'. $currentmarker->id.' = \'<div id="placemarkContentCluster'. $currentmarker->id.'">\' +' ."\n";
						$scripttext .= '\'<span id="headContentCluster'. $currentmarker->id.'" class="placemarkHeadCluster">'.htmlspecialchars(str_replace('\\', '/', $currentmarker->title), ENT_QUOTES, 'UTF-8').'</span>\'+' ."\n";
						$scripttext .= '\'</div>\';'."\n";

						$scripttext .= 'contentStringPart1'.$currentmarker->id.' = "" +' ."\n";
						$scripttext .= '\'<div id="contentUpdatePlacemark">\'+'."\n";
						//$scripttext .= '    \'<br />\'+' ."\n";
						//$scripttext .= '    \''.JText::_( 'COM_ZHYANDEXMAP_MAP_USER_LNG' ).' \'+current.getLng() + ' ."\n";
						//$scripttext .= '    \'<br />'.JText::_( 'COM_ZHYANDEXMAP_MAP_USER_LAT' ).' \'+current.getLat() + ' ."\n";
						// Form Update
						$scripttext .= '    \'<form id="updatePlacemarkForm'.$currentmarker->id.'" action="'.JURI::current().'" method="post">\'+'."\n";
						$scripttext .= '    \''.'<img src="'.$imgpathUtils.'published'.(int)$currentmarker->published.'.png" alt="" />  \'+' ."\n";
						$scripttext .= '    \'<br />\'+' ."\n";

						// Begin Placemark Properties
						$scripttext .= '\'<div id="bodyInsertPlacemarkDivA'.$currentmarker->id.'"  class="bodyInsertProperties">\'+'."\n";
						$scripttext .= '\'<a id="bodyInsertPlacemarkA'.$currentmarker->id.'" href="javascript:showonlyone(\\\'Placemark\\\',\\\''.$currentmarker->id.'\\\');" ><img src="'.$imgpathUtils.'collapse.png">'.JText::_( 'COM_ZHYANDEXMAP_MAP_USER_BASIC_PROPERTIES' ).'</a>\'+'."\n";
						$scripttext .= '\'</div>\'+'."\n";
						$scripttext .= '\'<div id="bodyInsertPlacemark'.$currentmarker->id.'"  class="bodyInsertPlacemarkProperties">\'+'."\n";
							$scripttext .= '    \''.JText::_( 'COM_ZHYANDEXMAP_MAP_USER_NAME' ).' \'+' ."\n";
							$scripttext .= '    \'<br />\'+' ."\n";
							$scripttext .= '    \'<input name="markername" type="text" maxlength="250" size="50" value="'. htmlspecialchars($currentmarker->title, ENT_QUOTES, 'UTF-8').'" />\'+' ."\n";
							$scripttext .= '    \'<br />\'+' ."\n";
							//$scripttext .= '    \'<br />\'+' ."\n";
							$scripttext .= '    \''.JText::_( 'COM_ZHYANDEXMAP_MAP_USER_DESCRIPTION' ).' \'+' ."\n";
							$scripttext .= '    \'<br />\'+' ."\n";
							$scripttext .= '    \'<input name="markerdescription" type="text" maxlength="250" size="50" value="'. htmlspecialchars($currentmarker->description, ENT_QUOTES, 'UTF-8').'" />\'+' ."\n";

							$scripttext .= '    \'<br />\'+' ."\n";
							$scripttext .= '    \''.JText::_( 'COM_ZHYANDEXMAP_MAPMARKER_DETAIL_HREFIMAGE_LABEL' ).' \'+' ."\n";
							$scripttext .= '    \'<br />\'+' ."\n";
							$scripttext .= '    \'<input name="markerhrefimage" type="text" maxlength="500" size="50" value="'. htmlspecialchars($currentmarker->hrefimage, ENT_QUOTES, 'UTF-8').'" />\'+' ."\n";
							$scripttext .= '    \'<br />\'+' ."\n";

							$scripttext .= '    \'<br />\';' ."\n";

							// icon type
							
							$scripttext .= 'contentStringPart2'.$currentmarker->id.' = "" +' ."\n";
							$scripttext .= '    \'<br />\'+' ."\n";

						$scripttext .= '\'</div>\'+'."\n";
						// End Placemark Properties
						
						// Begin Placemark Group Properties
						if (isset($map->showbelong) && (int)$map->showbelong == 1)
						{
							$scripttext .= '\'<div id="bodyInsertPlacemarkGrpDivA'.$currentmarker->id.'"  class="bodyInsertProperties">\'+'."\n";
							$scripttext .= '\'<a id="bodyInsertPlacemarkGrpA'.$currentmarker->id.'" href="javascript:showonlyone(\\\'PlacemarkGroup\\\',\\\''.$currentmarker->id.'\\\');" ><img src="'.$imgpathUtils.'expand.png">'.JText::_( 'COM_ZHYANDEXMAP_MAP_USER_BASIC_GROUP_PROPERTIES' ).'</a>\'+'."\n";
							$scripttext .= '\'</div>\'+'."\n";
							$scripttext .= '\'<div id="bodyInsertPlacemarkGrp'.$currentmarker->id.'"  class="bodyInsertPlacemarkGrpProperties">\'+'."\n";
								$scripttext .= '    \''.JText::_( 'COM_ZHYANDEXMAP_MAP_USER_GROUP' ).' \'+' ."\n";
								$scripttext .= '    \'<br />\'+' ."\n";
								
								$scripttext .= '    \' <select name="markergroup" > \'+' ."\n";
								if ($currentmarker->markergroup == 0)
								{
									$scripttext .= '    \' <option value="" selected="selected">'.JText::_( 'COM_ZHYANDEXMAP_MAPMARKER_FILTER_PLACEMARK_GROUP').'</option> \'+' ."\n";
								}
								else
								{
									$scripttext .= '    \' <option value="">'.JText::_( 'COM_ZHYANDEXMAP_MAPMARKER_FILTER_PLACEMARK_GROUP').'</option> \'+' ."\n";
								}
								foreach ($newMarkerGroupList as $key => $newGrp) 
								{
									if ($currentmarker->markergroup == $newGrp->value)
									{
										$scripttext .= '    \' <option value="'.$newGrp->value.'" selected="selected">'.$newGrp->text.'</option> \'+' ."\n";
									}
									else
									{
										$scripttext .= '    \' <option value="'.$newGrp->value.'">'.$newGrp->text.'</option> \'+' ."\n";
									}
								}
								$scripttext .= '    \' </select> \'+' ."\n";
								$scripttext .= '    \'<br />\'+' ."\n";


							$scripttext .= '    \''.JText::_( 'COM_ZHYANDEXMAP_MAP_USER_CATEGORY' ).' \'+' ."\n";
							$scripttext .= '    \'<br />\'+' ."\n";
							$scripttext .= '    \' <select name="markercatid" > \'+' ."\n";
							$scripttext .= '    \' <option value="" selected="selected">'.JText::_( 'COM_ZHYANDEXMAP_MAP_FILTER_CATEGORY').'</option> \'+' ."\n";
							$scripttext .= '    \''.str_replace(array("\r", "\r\n", "\n"),'', 
												   JHtml::_('select.options', JHtml::_('category.options', 'com_zhyandexmap'), 'value', 'text', $currentmarker->catid)) .
												   '\'+' ."\n";
							$scripttext .= '    \' </select> \'+' ."\n";
							$scripttext .= '    \'<br />\'+' ."\n";

							$scripttext .= '    \'<br />\'+' ."\n";
							$scripttext .= '\'</div>\'+'."\n";
						}
						// End Placemark Group Properties

						// Begin Contact Properties
						if (isset($map->usercontact) && (int)$map->usercontact == 1) 
						{

								$scripttext .= '\'<div id="bodyInsertContactDivA'.$currentmarker->id.'"  class="bodyInsertProperties">\'+'."\n";
								$scripttext .= '\'<a id="bodyInsertContactA'.$currentmarker->id.'" href="javascript:showonlyone(\\\'Contact\\\',\\\''.$currentmarker->id.'\\\');" ><img src="'.$imgpathUtils.'expand.png">'.JText::_( 'COM_ZHYANDEXMAP_MAP_USER_CONTACT_PROPERTIES' ).'</a>\'+'."\n";
								$scripttext .= '\'</div>\'+'."\n";
								$scripttext .= '\'<div id="bodyInsertContact'.$currentmarker->id.'"  class="bodyInsertContactProperties">\'+'."\n";
								$scripttext .= '    \''.JText::_( 'COM_ZHYANDEXMAP_MAP_USER_CONTACT_NAME' ).' \'+' ."\n";
								$scripttext .= '    \'<br />\'+' ."\n";
								$scripttext .= '    \'<input name="contactname" type="text" maxlength="250" size="50" value="'. htmlspecialchars($currentmarker->contact_name, ENT_QUOTES, 'UTF-8').'" />\'+' ."\n";
								$scripttext .= '    \'<br />\'+' ."\n";
								$scripttext .= '    \''.JText::_( 'COM_ZHYANDEXMAP_MAP_USER_CONTACT_POSITION' ).' \'+' ."\n";
								$scripttext .= '    \'<br />\'+' ."\n";
								$scripttext .= '    \'<input name="contactposition" type="text" maxlength="250" size="50" value="'. htmlspecialchars($currentmarker->contact_position, ENT_QUOTES, 'UTF-8').'" />\'+' ."\n";
								$scripttext .= '    \'<br />\'+' ."\n";
								$scripttext .= '    \''.JText::_( 'COM_ZHYANDEXMAP_MAP_USER_CONTACT_PHONE' ).' \'+' ."\n";
								$scripttext .= '    \'<br />\'+' ."\n";
								$scripttext .= '    \'<input name="contactphone" type="text" maxlength="250" size="50" value="'. htmlspecialchars($currentmarker->contact_phone, ENT_QUOTES, 'UTF-8').'" />\'+' ."\n";
								$scripttext .= '    \'<br />\'+' ."\n";
								$scripttext .= '    \''.JText::_( 'COM_ZHYANDEXMAP_MAP_USER_CONTACT_MOBILE' ).' \'+' ."\n";
								$scripttext .= '    \'<br />\'+' ."\n";
								$scripttext .= '    \'<input name="contactmobile" type="text" maxlength="250" size="50" value="'. htmlspecialchars($currentmarker->contact_mobile, ENT_QUOTES, 'UTF-8').'" />\'+' ."\n";
								$scripttext .= '    \'<br />\'+' ."\n";
								$scripttext .= '    \''.JText::_( 'COM_ZHYANDEXMAP_MAP_USER_CONTACT_FAX' ).' \'+' ."\n";
								$scripttext .= '    \'<br />\'+' ."\n";
								$scripttext .= '    \'<input name="contactfax" type="text" maxlength="250" size="50" value="'. htmlspecialchars($currentmarker->contact_fax, ENT_QUOTES, 'UTF-8').'" />\'+' ."\n";
								$scripttext .= '\'</div>\'+'."\n";
								// Contact Address
								$scripttext .= '\'<div id="bodyInsertContactAdrDivA'.$currentmarker->id.'"  class="bodyInsertProperties">\'+'."\n";
								$scripttext .= '\'<a id="bodyInsertContactAdrA'.$currentmarker->id.'" href="javascript:showonlyone(\\\'ContactAddress\\\',\\\''.$currentmarker->id.'\\\');" ><img src="'.$imgpathUtils.'expand.png">'.JText::_( 'COM_ZHYANDEXMAP_MAP_USER_CONTACT_ADDRESS_PROPERTIES' ).'</a>\'+'."\n";
								$scripttext .= '\'</div>\'+'."\n";
								$scripttext .= '\'<div id="bodyInsertContactAdr'.$currentmarker->id.'"  class="bodyInsertContactAdrProperties">\'+'."\n";
								$scripttext .= '    \''.JText::_( 'COM_ZHYANDEXMAP_MAP_USER_CONTACT_ADDRESS' ).' \'+' ."\n";
								$scripttext .= '    \'<br />\'+' ."\n";
								$scripttext .= '    \'<textarea name="contactaddress" cols="35" rows="4" >'. str_replace("\n\n", "'+'\\n'+'", str_replace(array("\r", "\r\n", "\n"), "\n",htmlspecialchars($currentmarker->contact_address, ENT_QUOTES, 'UTF-8'))).'</textarea>\'+' ."\n";
								$scripttext .= '    \'<br />\'+' ."\n";
								$scripttext .= '    \'<br />\'+' ."\n";
								$scripttext .= '\'</div>\'+'."\n";
						}
						// End Contact Properties

						$scripttext .= '\'\';'."\n";
						
						
						
						$scripttext .= $markername.'.events.add("click", function (e) {' ."\n";

						$scripttext .= 'var contentStringButtons'.$currentmarker->id.' = "" +' ."\n";
						$scripttext .= '    \'<hr />\'+' ."\n";					
						$scripttext .= '    \'<input name="markerlat" type="hidden" value="\'+latlng'. $currentmarker->id.'[1] + \'" />\'+' ."\n";
						$scripttext .= '    \'<input name="markerlng" type="hidden" value="\'+latlng'. $currentmarker->id.'[0] + \'" />\'+' ."\n";
						$scripttext .= '    \'<input name="marker_action" type="hidden" value="update" />\'+' ."\n";
						$scripttext .= '    \'<input name="markerid" type="hidden" value="'.$currentmarker->id.'" />\'+' ."\n";
						$scripttext .= '    \'<input name="contactid" type="hidden" value="'.$currentmarker->contactid.'" />\'+' ."\n";
						$scripttext .= '    \'<input name="markersubmit" type="submit" value="'.JText::_( 'COM_ZHYANDEXMAP_MAP_USER_BUTTON_UPDATE' ).'" />\'+' ."\n";
						$scripttext .= '    \'</form>\'+' ."\n";		
						$scripttext .= '\'</div>\'+'."\n";
						// Form Delete
						$scripttext .= '\'<div id="contentDeletePlacemark">\'+'."\n";
						$scripttext .= '    \'<form id="deletePlacemarkForm'.$currentmarker->id.'" action="'.JURI::current().'" method="post">\'+'."\n";
						$scripttext .= '    \'<input name="marker_action" type="hidden" value="delete" />\'+' ."\n";
						$scripttext .= '    \'<input name="markerid" type="hidden" value="'.$currentmarker->id.'" />\'+' ."\n";
						$scripttext .= '    \'<input name="contactid" type="hidden" value="'.$currentmarker->contactid.'" />\'+' ."\n";
						$scripttext .= '    \'<input name="markersubmit" type="submit" value="'.JText::_( 'COM_ZHYANDEXMAP_MAP_USER_BUTTON_DELETE' ).'" />\'+' ."\n";
						$scripttext .= '    \'</form>\'+' ."\n";		
						$scripttext .= '\'</div>\';'."\n";

						$scripttext .= $markername.'.properties.set("balloonContent", contentStringPart1'.$currentmarker->id.'+';
						$scripttext .= 'contentInsertPlacemarkIcon.replace(/insertPlacemarkForm/g,"updatePlacemarkForm'. $currentmarker->id.'")';
						$scripttext .= '.replace(\'"markericonimage" src="\', \'"markericonimage" src="'.$imgpathIcons.str_replace("#", "%23", $currentmarker->icontype).'.png"\')';
						$scripttext .= '.replace(\'<option value="'.$currentmarker->icontype.'">'.$currentmarker->icontype.'</option>\', \'<option value="'.$currentmarker->icontype.'" selected="selected">'.$currentmarker->icontype.'</option>\')';
						if (isset($map->usermarkersicon) && (int)$map->usermarkersicon == 0) 
						{
							$scripttext .= '.replace(\'<input name="markerimage" type="hidden" value="default#" />\', \'<input name="markerimage" type="hidden" value="'.$currentmarker->icontype.'" />\')';	
						}

						$scripttext .= '+';
						$scripttext .= 'contentStringPart2'.$currentmarker->id.'+';
						$scripttext .= 'contentStringButtons'.$currentmarker->id;
						$scripttext .= ');' ."\n";

						$scripttext .= '});' ."\n";
						
						
						$scripttext .= $markername.'.events.add("drag", function (e) {' ."\n";
						$scripttext .= '    latlng'. $currentmarker->id.' = '.$markername.'.geometry.getCoordinates();' ."\n";
						$scripttext .= '});' ."\n";

						// Change UserMarker - end
					}
					
					// Placemark Content - End
					
					if ($zhymObjectManager != 0)
					{
						// fix for 19.02.2013
						//  if not managed placemarks (not enabled)
						if  ((isset($map->markergroupctlmarker) && (int)$map->markergroupctlmarker != 0))
						{
							$scripttext .= 'zhymObjMgr'.$mapDivSuffix.'.PlacemarkAdd('.$currentmarker->markergroup.', '. $currentmarker->id.', marker'. $currentmarker->id.', null);'."\n";
						}
						else
						{									
							// 22.08.2014 placemarks in clusters, therefore not only 0-cluster
							if ((isset($map->markercluster) && (int)$map->markercluster == 1))
							{
								if ((isset($map->markerclustergroup) && (int)$map->markerclustergroup == 1))
								{
									$scripttext .= 'zhymObjMgr'.$mapDivSuffix.'.PlacemarkAdd('.$currentmarker->markergroup.', '. $currentmarker->id.', marker'. $currentmarker->id.', null);'."\n";
								}
								else
								{
									$scripttext .= 'zhymObjMgr'.$mapDivSuffix.'.PlacemarkAdd(0, '. $currentmarker->id.', marker'. $currentmarker->id.', null);'."\n";
								}
							}
							else
							{
								if ((isset($map->markerclustergroup) && (int)$map->markerclustergroup == 1))
								{
									$scripttext .= 'zhymObjMgr'.$mapDivSuffix.'.PlacemarkAdd('.$currentmarker->markergroup.', '. $currentmarker->id.', marker'. $currentmarker->id.', null);'."\n";
								}
							}
							// /////
						}
					}


					if ($zhymObjectManager != 0)
					{
						// add placemarks on map only if we don't manage it
						if  ((isset($map->markergroupctlmarker) && (int)$map->markergroupctlmarker != 0))
						{
						}
						else
						{
							$scripttext .= 'map'.$mapDivSuffix.'.geoObjects.add('.$markername.');' ."\n";
						}
					}
					else
					{
						$scripttext .= 'map'.$mapDivSuffix.'.geoObjects.add('.$markername.');' ."\n";
					}
					
					if ($currentmarker->openbaloon == '1')
					{
						//$scripttext .= $markername.'.events.fire("click", new ymaps.Event({'."\n";
						//$scripttext .= 'target: '.$markername.','."\n";
						//$scripttext .= '}, true));' ."\n";
						// Action By Click - For Placemark Open Balloon Property - Begin	
						// Because there is a problem with Notify propagation

						switch ((int)$currentmarker->actionbyclick)
						{
							// None
							case 0:
								if ((int)$currentmarker->zoombyclick != 100)
								{
									$scripttext .= '  map'.$mapDivSuffix.'.setCenter(latlng'. $currentmarker->id.');' ."\n";
									$scripttext .= '  map'.$mapDivSuffix.'.setZoom('.(int)$currentmarker->zoombyclick.');' ."\n";
								}
							break;
							// Info
							case 1:
								if ((int)$currentmarker->zoombyclick != 100)
								{
									$scripttext .= '  map'.$mapDivSuffix.'.setCenter(latlng'. $currentmarker->id.');' ."\n";
									$scripttext .= '  map'.$mapDivSuffix.'.setZoom('.(int)$currentmarker->zoombyclick.');' ."\n";
								}
								// Open only if disabled editing
								if (($allowUserMarker == 0)
								 || (isset($currentmarker->userprotection) && (int)$currentmarker->userprotection == 1)
								 || ($currentUserID == 0)
								 || (isset($currentmarker->createdbyuser) 
									&& (((int)$currentmarker->createdbyuser != $currentUserID )
									   || ((int)$currentmarker->createdbyuser == 0)))
								 )
								{
									// I set it on previous level action by click
									//$scripttext .= $markername.'.properties.set("balloonContentHeader", contentStringHead'. $currentmarker->id.');' ."\n";
									//$scripttext .= $markername.'.properties.set("balloonContentBody", contentStringBody'. $currentmarker->id.');' ."\n";

									// if clusterer is enabled - do not display, because placemark is not on map yet
									if (isset($map->markercluster) && (int)$map->markercluster == 0)
									{
										$scripttext .= '    '.$markername.'.balloon.open();' ."\n";
									}
									
								}

								
							break;
							// Link
							case 2:
								if ($currentmarker->hrefsite != "")
								{
									if ((int)$currentmarker->zoombyclick != 100)
									{
										$scripttext .= '  map'.$mapDivSuffix.'.setCenter(latlng'. $currentmarker->id.');' ."\n";
										$scripttext .= '  map'.$mapDivSuffix.'.setZoom('.(int)$currentmarker->zoombyclick.');' ."\n";
									}
									$scripttext .= '  window.open("'.$currentmarker->hrefsite.'");' ."\n";
								}
								else
								{
									if ((int)$currentmarker->zoombyclick != 100)
									{
										$scripttext .= '  map'.$mapDivSuffix.'.setCenter(latlng'. $currentmarker->id.');' ."\n";
										$scripttext .= '  map'.$mapDivSuffix.'.setZoom('.(int)$currentmarker->zoombyclick.');' ."\n";
									}
								}
							break;
							// Link in self
							case 3:
								if ($currentmarker->hrefsite != "")
								{
									if ((int)$currentmarker->zoombyclick != 100)
									{
										$scripttext .= '  map'.$mapDivSuffix.'.setCenter(latlng'. $currentmarker->id.');' ."\n";
										$scripttext .= '  map'.$mapDivSuffix.'.setZoom('.(int)$currentmarker->zoombyclick.');' ."\n";
									}
									$scripttext .= '  window.location = "'.$currentmarker->hrefsite.'";' ."\n";
								}
								else
								{
									if ((int)$currentmarker->zoombyclick != 100)
									{
										$scripttext .= '  map'.$mapDivSuffix.'.setCenter(latlng'. $currentmarker->id.');' ."\n";
										$scripttext .= '  map'.$mapDivSuffix.'.setZoom('.(int)$currentmarker->zoombyclick.');' ."\n";
									}
								}
							break;
							default:
								$scripttext .= '' ."\n";
							break;
						}
						
						// Action By Click - For For Placemark Open Balloon Property - End
					}


						//
						// Generate list elements for each marker.
						if (isset($map->markerlistpos) && (int)$map->markerlistpos != 0) 
						{						
								if (isset($currentmarker->includeinlist))
								{
									$doAddToList = (int)$currentmarker->includeinlist;							 
								}
								else
								{
									$doAddToList = 1;
								}
							
							if ($doAddToList == 1)
							{
								$doAddToListCount += 1;
								$scripttext .= 'if (markerUL)'."\n";
								$scripttext .= '{'."\n";
								if ((int)$map->markerlistcontent < 100) 
								{								
										$scripttext .= ' var markerLI = document.createElement(\'li\');'."\n";
                                                                                $scripttext .= ' markerLI.id = "zhym_pmlist_item_'.$mapDivSuffix.'_'.$currentmarker->id.'";'."\n";
										$scripttext .= ' markerLI.className = "zhym-li-'.$markerlistcssstyle.'";'."\n";
										$scripttext .= ' var markerLIWrp = document.createElement(\'div\');'."\n";
										$scripttext .= ' markerLIWrp.className = "zhym-li-wrp-'.$markerlistcssstyle.'";'."\n";
										$scripttext .= ' var markerASelWrp = document.createElement(\'div\');'."\n";
										$scripttext .= ' markerASelWrp.className = "zhym-li-wrp-a-'.$markerlistcssstyle.'";'."\n";
										$scripttext .= ' var markerASel = document.createElement(\'a\');'."\n";
										$scripttext .= ' markerASel.className = "zhym-li-a-'.$markerlistcssstyle.'";'."\n";
                                                                                $scripttext .= ' markerASel.id = "zhym_pmlist_'.$mapDivSuffix.'_'.$currentmarker->id.'";'."\n";
										$scripttext .= ' markerASel.href = \'javascript:void(0);\';'."\n";
										if ((int)$map->markerlistcontent == 0) 
										{
											$scripttext .= ' markerASel.innerHTML = \'<div id="markerASel'. $currentmarker->id.'" class="zhym-0-li-'.$markerlistcssstyle.'">'.htmlspecialchars(str_replace('\\', '/', $currentmarker->title), ENT_QUOTES, 'UTF-8').'</div>\';'."\n";
										}
										else if ((int)$map->markerlistcontent == 1) 
										{
											$scripttext .= ' markerASel.innerHTML = \'<div id="markerASel'. $currentmarker->id.'" class="zhym-1-lit-'.$markerlistcssstyle.'">'.htmlspecialchars(str_replace('\\', '/', $currentmarker->title), ENT_QUOTES, 'UTF-8').'</div>\';'."\n";
											$scripttext .= ' var markerDSel = document.createElement(\'div\');'."\n";
											$scripttext .= ' markerDSel.className = "zhym-1-liw-'.$markerlistcssstyle.'";'."\n";
											$scripttext .= ' markerDSel.innerHTML = ';
											$scripttext .= ' \'<div id="markerDDesc'. $currentmarker->id.'" class="zhym-1-lid-'.$markerlistcssstyle.'">'.htmlspecialchars(str_replace('\\', '/', $currentmarker->description), ENT_QUOTES, 'UTF-8').'</div>\';'."\n";
										}
										else if ((int)$map->markerlistcontent == 2) 
										{
											$scripttext .= ' markerASel.innerHTML = ';
											$scripttext .= ' \'<div id="markerDWrp'. $currentmarker->id.'" class="zhym-2-liw-icon-'.$markerlistcssstyle.'">\'+'."\n";
											$scripttext .= ' \'<div id="markerDIcon'. $currentmarker->id.'" class="zhym-2-lii-icon-'.$markerlistcssstyle.'"><img src="';
											if ((int)$currentmarker->overridemarkericon == 0)
											{
													$scripttext .= $imgpathIcons.str_replace("#", "%23", $currentmarker->icontype);
											}
											else
											{
													$scripttext .= $imgpathIcons.str_replace("#", "%23", $currentmarker->groupicontype);
											}
											$scripttext .= '.png" alt="" /></div>\'+'."\n";
											$scripttext .= ' \'<div id="markerASel'. $currentmarker->id.'" class="zhym-2-lit-icon-'.$markerlistcssstyle.'">'.htmlspecialchars(str_replace('\\', '/', $currentmarker->title), ENT_QUOTES, 'UTF-8').'\'+'."\n";
											$scripttext .= ' \'</div></div>\';'."\n";
										}
										else if ((int)$map->markerlistcontent == 3) 
										{
											$scripttext .= ' markerASel.innerHTML = ';
											$scripttext .= ' \'<div id="markerDWrp'. $currentmarker->id.'" class="zhym-3-liw-icon-'.$markerlistcssstyle.'">\'+'."\n";
											$scripttext .= ' \'<div id="markerDIcon'. $currentmarker->id.'" class="zhym-3-lii-icon-'.$markerlistcssstyle.'"><img src="';
											if ((int)$currentmarker->overridemarkericon == 0)
											{
													$scripttext .= $imgpathIcons.str_replace("#", "%23", $currentmarker->icontype);
											}
											else
											{
													$scripttext .= $imgpathIcons.str_replace("#", "%23", $currentmarker->groupicontype);
											}
											$scripttext .= '.png" alt="" /></div>\'+'."\n";
											$scripttext .= ' \'<div id="markerASel'. $currentmarker->id.'" class="zhym-3-lit-icon-'.$markerlistcssstyle.'">'.htmlspecialchars(str_replace('\\', '/', $currentmarker->title), ENT_QUOTES, 'UTF-8').'</div>\';'."\n";
											$scripttext .= ' var markerDSel = document.createElement(\'div\');'."\n";
											$scripttext .= ' markerDSel.className = "zhym-3-liwd-icon-'.$markerlistcssstyle.'";'."\n";
											$scripttext .= ' markerDSel.innerHTML = ';
											$scripttext .= ' \'<div id="markerDDesc'. $currentmarker->id.'" class="zhym-3-lid-icon-'.$markerlistcssstyle.'">'.htmlspecialchars(str_replace('\\', '/', $currentmarker->description), ENT_QUOTES, 'UTF-8').'</div>\'+'."\n";
											$scripttext .= ' \'</div>\';'."\n";
										}
										else if ((int)$map->markerlistcontent == 4) 
										{
											$scripttext .= ' markerASel.innerHTML = ';
											$scripttext .= ' \'';									
											$scripttext .= '<table class="zhym-4-table-icon-'.$markerlistcssstyle.'">';
											$scripttext .= '<tbody>';
											$scripttext .= '<tr class="zhym-4-row-icon-'.$markerlistcssstyle.'">';
											$scripttext .= '<td rowspan=2 class="zhym-4-tdicon-icon-'.$markerlistcssstyle.'">';
											$scripttext .= '<img src="';
											if ((int)$currentmarker->overridemarkericon == 0)
											{
													$scripttext .= $imgpathIcons.str_replace("#", "%23", $currentmarker->icontype);
											}
											else
											{
													$scripttext .= $imgpathIcons.str_replace("#", "%23", $currentmarker->groupicontype);
											}
											$scripttext .= '.png" alt="" />';
											$scripttext .= '</td>';
											$scripttext .= '<td class="zhym-4-tdtitle-icon-'.$markerlistcssstyle.'">';
											$scripttext .= htmlspecialchars(str_replace('\\', '/', $currentmarker->title), ENT_QUOTES, 'UTF-8');
											$scripttext .= '</td>';
											$scripttext .= '</tr>';
											$scripttext .= '<tr>';
											$scripttext .= '<td class="zhym-4-tddesc-icon-'.$markerlistcssstyle.'">';
											$scripttext .= htmlspecialchars(str_replace('\\', '/', $currentmarker->description), ENT_QUOTES, 'UTF-8');
											$scripttext .= '</td>';
											$scripttext .= '</tr>';
											$scripttext .= '</tbody>';
											$scripttext .= '</table>';
											$scripttext .= ' \';'."\n";
										}
										else if ((int)$map->markerlistcontent == 11) 
										{
											$scripttext .= ' markerASel.innerHTML = ';
											$scripttext .= ' \'<div id="markerDWrp'. $currentmarker->id.'" class="zhym-11-liw-image-'.$markerlistcssstyle.'">\'+'."\n";
											$scripttext .= ' \'<div id="markerASel'. $currentmarker->id.'" class="zhym-11-lit-image-'.$markerlistcssstyle.'">'.htmlspecialchars(str_replace('\\', '/', $currentmarker->title), ENT_QUOTES, 'UTF-8').'</div>\'+'."\n";
											$scripttext .= ' \'<div id="markerDImage'. $currentmarker->id.'" class="zhym-11-lii-image-'.$markerlistcssstyle.'"><img src="'.$currentmarker->hrefimagethumbnail.'" alt="" />\'+'."\n";
											$scripttext .= ' \'</div></div>\';'."\n";
										}
										else if ((int)$map->markerlistcontent == 12) 
										{
											$scripttext .= ' markerASel.innerHTML = ';
											$scripttext .= ' \'<div id="markerDWrp'. $currentmarker->id.'" class="zhym-12-liw-image-'.$markerlistcssstyle.'">\'+'."\n";
											$scripttext .= ' \'<div id="markerASel'. $currentmarker->id.'" class="zhym-12-lit-image-'.$markerlistcssstyle.'">'.htmlspecialchars(str_replace('\\', '/', $currentmarker->title), ENT_QUOTES, 'UTF-8').'</div>\'+'."\n";
											$scripttext .= ' \'<div id="markerDImage'. $currentmarker->id.'" class="zhym-12-lii-image-'.$markerlistcssstyle.'"><img src="'.$currentmarker->hrefimagethumbnail.'" alt="" /></div>\';'."\n";
											$scripttext .= ' var markerDSel = document.createElement(\'div\');'."\n";
											$scripttext .= ' markerDSel.className = "zhym-12-liwd-image-'.$markerlistcssstyle.'";'."\n";
											$scripttext .= ' markerDSel.innerHTML = ';
											$scripttext .= ' \'<div id="markerDDesc'. $currentmarker->id.'" class="zhym-12-lid-image-'.$markerlistcssstyle.'">'.htmlspecialchars(str_replace('\\', '/', $currentmarker->description), ENT_QUOTES, 'UTF-8').'</div>\'+'."\n";
											$scripttext .= ' \'</div>\';'."\n";
										}
										else if ((int)$map->markerlistcontent == 13) 
										{
											$scripttext .= ' markerASel.innerHTML = ';
											$scripttext .= ' \'<div id="markerDWrp'. $currentmarker->id.'" class="zhym-13-liw-image-'.$markerlistcssstyle.'">\'+'."\n";
											$scripttext .= ' \'<div id="markerDImage'. $currentmarker->id.'" class="zhym-13-lii-image-'.$markerlistcssstyle.'"><img src="'.$currentmarker->hrefimagethumbnail.'" alt="" /></div>\'+'."\n";
											$scripttext .= ' \'<div id="markerASel'. $currentmarker->id.'" class="zhym-13-lit-image-'.$markerlistcssstyle.'">'.htmlspecialchars(str_replace('\\', '/', $currentmarker->title), ENT_QUOTES, 'UTF-8').'\'+'."\n";
											$scripttext .= ' \'</div></div>\';'."\n";
										}
										else if ((int)$map->markerlistcontent == 14) 
										{
											$scripttext .= ' markerASel.innerHTML = ';
											$scripttext .= ' \'<div id="markerDWrp'. $currentmarker->id.'" class="zhym-14-liw-image-'.$markerlistcssstyle.'">\'+'."\n";
											$scripttext .= ' \'<div id="markerDImage'. $currentmarker->id.'" class="zhym-14-lii-image-'.$markerlistcssstyle.'"><img src="'.$currentmarker->hrefimagethumbnail.'" alt="" /></div>\'+'."\n";
											$scripttext .= ' \'<div id="markerASel'. $currentmarker->id.'" class="zhym-14-lit-image-'.$markerlistcssstyle.'">'.htmlspecialchars(str_replace('\\', '/', $currentmarker->title), ENT_QUOTES, 'UTF-8').'</div>\';'."\n";
											$scripttext .= ' var markerDSel = document.createElement(\'div\');'."\n";
											$scripttext .= ' markerDSel.className = "zhym-14-liwd-image-'.$markerlistcssstyle.'";'."\n";
											$scripttext .= ' markerDSel.innerHTML = ';
											$scripttext .= ' \'<div id="markerDDesc'. $currentmarker->id.'" class="zhym-14-lid-image-'.$markerlistcssstyle.'">'.htmlspecialchars(str_replace('\\', '/', $currentmarker->description), ENT_QUOTES, 'UTF-8').'</div>\'+'."\n";
											$scripttext .= ' \'</div>\';'."\n";
										}
										else if ((int)$map->markerlistcontent == 15) 
										{
											$scripttext .= ' markerASel.innerHTML = ';
											$scripttext .= ' \'';									
											$scripttext .= '<table class="zhym-15-table-image-'.$markerlistcssstyle.'">';
											$scripttext .= '<tbody>';
											$scripttext .= '<tr class="zhym-15-row-image-'.$markerlistcssstyle.'">';
											$scripttext .= '<td rowspan=2 class="zhym-15-tdicon-image-'.$markerlistcssstyle.'">';
											$scripttext .= '<img src="'.$currentmarker->hrefimagethumbnail.'" alt="" />';
											$scripttext .= '</td>';
											$scripttext .= '<td class="zhym-15-tdtitle-image-'.$markerlistcssstyle.'">';
											$scripttext .= htmlspecialchars(str_replace('\\', '/', $currentmarker->title), ENT_QUOTES, 'UTF-8');
											$scripttext .= '</td>';
											$scripttext .= '</tr>';
											$scripttext .= '<tr>';
											$scripttext .= '<td class="zhym-15-tddesc-image-'.$markerlistcssstyle.'">';
											$scripttext .= htmlspecialchars(str_replace('\\', '/', $currentmarker->description), ENT_QUOTES, 'UTF-8');
											$scripttext .= '</td>';
											$scripttext .= '</tr>';
											$scripttext .= '</tbody>';
											$scripttext .= '</table>';
											$scripttext .= ' \';'."\n";
										}
										else
										{
											$scripttext .= ' markerASel.innerHTML = \'<div id="markerASel'. $currentmarker->id.'" class="zhym-0-li-'.$markerlistcssstyle.'">'.htmlspecialchars(str_replace('\\', '/', $currentmarker->title), ENT_QUOTES, 'UTF-8').'</div>\';'."\n";
										}


										if ((int)$map->markerlistaction == 0
											|| ($allowUserMarker == 1)) 
										{
											$scripttext .= ' markerASel.onclick = function(){ map'.$mapDivSuffix.'.setCenter(latlng'. $currentmarker->id.')};'."\n";
										}
										else if ((int)$map->markerlistaction == 1) 
										{
											$scripttext .= ' markerASel.onclick = function(){ ';

											switch ((int)$currentmarker->actionbyclick)
											{
												// None
												case 0:
													if ((int)$currentmarker->zoombyclick != 100)
													{
														$scripttext .= '  map'.$mapDivSuffix.'.setCenter(latlng'. $currentmarker->id.');' ."\n";
														$scripttext .= '  map'.$mapDivSuffix.'.setZoom('.(int)$currentmarker->zoombyclick.');' ."\n";
													}
												break;
												// Info
												case 1:
													if ((int)$currentmarker->zoombyclick != 100)
													{
														$scripttext .= '  map'.$mapDivSuffix.'.setCenter(latlng'. $currentmarker->id.');' ."\n";
														$scripttext .= '  map'.$mapDivSuffix.'.setZoom('.(int)$currentmarker->zoombyclick.');' ."\n";
													}
													// Open only if disabled editing
													if (($allowUserMarker == 0)
													 || (isset($currentmarker->userprotection) && (int)$currentmarker->userprotection == 1)
													 || ($currentUserID == 0)
													 || (isset($currentmarker->createdbyuser) 
														&& (((int)$currentmarker->createdbyuser != $currentUserID )
														   || ((int)$currentmarker->createdbyuser == 0)))
													 )
													{
														$scripttext .= $markername.'.properties.set("balloonContentHeader", contentStringHead'. $currentmarker->id.');' ."\n";
														$scripttext .= $markername.'.properties.set("balloonContentBody", contentStringBody'. $currentmarker->id.');' ."\n";

														$scripttext .= '    '.$markername.'.balloon.open();' ."\n";
													}
													
												break;
												// Link
												case 2:
													if ($currentmarker->hrefsite != "")
													{
														if ((int)$currentmarker->zoombyclick != 100)
														{
															$scripttext .= '  map'.$mapDivSuffix.'.setCenter(latlng'. $currentmarker->id.');' ."\n";
															$scripttext .= '  map'.$mapDivSuffix.'.setZoom('.(int)$currentmarker->zoombyclick.');' ."\n";
														}
														$scripttext .= '  window.open("'.$currentmarker->hrefsite.'");' ."\n";
													}
													else
													{
														if ((int)$currentmarker->zoombyclick != 100)
														{
															$scripttext .= '  map'.$mapDivSuffix.'.setCenter(latlng'. $currentmarker->id.');' ."\n";
															$scripttext .= '  map'.$mapDivSuffix.'.setZoom('.(int)$currentmarker->zoombyclick.');' ."\n";
														}
													}
												break;
												// Link in self
												case 3:
													if ($currentmarker->hrefsite != "")
													{
														if ((int)$currentmarker->zoombyclick != 100)
														{
															$scripttext .= '  map'.$mapDivSuffix.'.setCenter(latlng'. $currentmarker->id.');' ."\n";
															$scripttext .= '  map'.$mapDivSuffix.'.setZoom('.(int)$currentmarker->zoombyclick.');' ."\n";
														}
														$scripttext .= '  window.location = "'.$currentmarker->hrefsite.'";' ."\n";
													}
													else
													{
														if ((int)$currentmarker->zoombyclick != 100)
														{
															$scripttext .= '  map'.$mapDivSuffix.'.setCenter(latlng'. $currentmarker->id.');' ."\n";
															$scripttext .= '  map'.$mapDivSuffix.'.setZoom('.(int)$currentmarker->zoombyclick.');' ."\n";
														}
													}
												break;
												default:
													$scripttext .= '' ."\n";
												break;
											}
											
											// Action By Click - For PlacemarkList - End

											$scripttext .= '};'."\n";
										}
										else
										{
											$scripttext .= ' markerASel.onclick = function(){ map'.$mapDivSuffix.'.setCenter(latlng'. $currentmarker->id.');};'."\n";
										}

										$scripttext .= ' markerASelWrp.appendChild(markerASel);'."\n";
										$scripttext .= ' markerLIWrp.appendChild(markerASelWrp);'."\n";
										if ((int)$map->markerlistcontent == 1) 
										{
											$scripttext .= ' markerLIWrp.appendChild(markerDSel);'."\n";
										}
										else if ((int)$map->markerlistcontent == 3) 
										{
											$scripttext .= ' markerLIWrp.appendChild(markerDSel);'."\n";
										}
										else if ((int)$map->markerlistcontent == 12) 
										{
											$scripttext .= ' markerLIWrp.appendChild(markerDSel);'."\n";
										}
										else if ((int)$map->markerlistcontent == 14) 
										{
											$scripttext .= ' markerLIWrp.appendChild(markerDSel);'."\n";
										}
										
										
										$scripttext .= ' markerLI.appendChild(markerLIWrp);'."\n";
										$scripttext .= ' markerUL.appendChild(markerLI);'."\n";
								}
								else
								{
										$scripttext .= ' var markerLI = document.createElement(\'tr\');'."\n";
                                                                                $scripttext .= ' markerLI.id = "zhym_pmlist_item_'.$mapDivSuffix.'_'.$currentmarker->id.'";'."\n";
										$scripttext .= ' markerLI.className = "zhym-li-table-tr-'.$markerlistcssstyle.'";'."\n";
										$scripttext .= ' var markerLI_C1 = document.createElement(\'td\');'."\n";
										$scripttext .= ' markerLI_C1.className = "zhym-li-table-c1-'.$markerlistcssstyle.'";'."\n";
										$scripttext .= ' var markerASelWrp = document.createElement(\'div\');'."\n";
										$scripttext .= ' markerASelWrp.className = "zhym-li-table-a-wrp-'.$markerlistcssstyle.'";'."\n";
										$scripttext .= ' var markerASel = document.createElement(\'a\');'."\n";
										$scripttext .= ' markerASel.className = "zhym-li-table-a-'.$markerlistcssstyle.'";'."\n";
                                                                                $scripttext .= ' markerASel.id = "zhym_pmlist_'.$mapDivSuffix.'_'.$currentmarker->id.'";'."\n";
										$scripttext .= ' markerASel.href = \'javascript:void(0);\';'."\n";
										if ((int)$map->markerlistcontent == 101) 
										{
											$scripttext .= ' markerASel.innerHTML = \'<div id="markerASelTable'. $currentmarker->id.'" class="zhym-101-td-'.$markerlistcssstyle.'">'.htmlspecialchars(str_replace('\\', '/', $currentmarker->title), ENT_QUOTES, 'UTF-8').'</div>\';'."\n";
										}
										else if ((int)$map->markerlistcontent == 102) 
										{
											$scripttext .= ' markerASel.innerHTML = \'<div id="markerASelTable'. $currentmarker->id.'" class="zhym-102-td1-'.$markerlistcssstyle.'">'.htmlspecialchars(str_replace('\\', '/', $currentmarker->title), ENT_QUOTES, 'UTF-8').'</div>\';'."\n";

											$scripttext .= ' var markerLI_C2 = document.createElement(\'td\');'."\n";
											$scripttext .= ' markerLI_C2.className = "zhym-li-table-c2-'.$markerlistcssstyle.'";'."\n";
											$scripttext .= ' var markerDSel = document.createElement(\'div\');'."\n";
											$scripttext .= ' markerDSel.className = "zhym-li-table-desc-'.$markerlistcssstyle.'";'."\n";
											$scripttext .= ' markerDSel.innerHTML = ';
											$scripttext .= ' \'<div id="markerDDescTable'. $currentmarker->id.'" class="zhym-102-td2-'.$markerlistcssstyle.'">'.htmlspecialchars(str_replace('\\', '/', $currentmarker->description), ENT_QUOTES, 'UTF-8').'</div>\';'."\n";
										}
										
										if ((int)$map->markerlistaction == 0
											|| ($allowUserMarker == 1)) 
										{
											$scripttext .= ' markerASel.onclick = function(){ map'.$mapDivSuffix.'.setCenter(latlng'. $currentmarker->id.')};'."\n";
										}
										else if ((int)$map->markerlistaction == 1) 
										{
											$scripttext .= ' markerASel.onclick = function(){ ';
											
											switch ((int)$currentmarker->actionbyclick)
											{
												// None
												case 0:
													if ((int)$currentmarker->zoombyclick != 100)
													{
														$scripttext .= '  map'.$mapDivSuffix.'.setCenter(latlng'. $currentmarker->id.');' ."\n";
														$scripttext .= '  map'.$mapDivSuffix.'.setZoom('.(int)$currentmarker->zoombyclick.');' ."\n";
													}
												break;
												// Info
												case 1:
													if ((int)$currentmarker->zoombyclick != 100)
													{
														$scripttext .= '  map'.$mapDivSuffix.'.setCenter(latlng'. $currentmarker->id.');' ."\n";
														$scripttext .= '  map'.$mapDivSuffix.'.setZoom('.(int)$currentmarker->zoombyclick.');' ."\n";
													}
													$scripttext .= $markername.'.properties.set("balloonContentHeader", contentStringHead'. $currentmarker->id.');' ."\n";
													$scripttext .= $markername.'.properties.set("balloonContentBody", contentStringBody'. $currentmarker->id.');' ."\n";

													$scripttext .= '    '.$markername.'.balloon.open();' ."\n";
													
												break;
												// Link
												case 2:
													if ($currentmarker->hrefsite != "")
													{
														if ((int)$currentmarker->zoombyclick != 100)
														{
															$scripttext .= '  map'.$mapDivSuffix.'.setCenter(latlng'. $currentmarker->id.');' ."\n";
															$scripttext .= '  map'.$mapDivSuffix.'.setZoom('.(int)$currentmarker->zoombyclick.');' ."\n";
														}
														$scripttext .= '  window.open("'.$currentmarker->hrefsite.'");' ."\n";
													}
													else
													{
														if ((int)$currentmarker->zoombyclick != 100)
														{
															$scripttext .= '  map'.$mapDivSuffix.'.setCenter(latlng'. $currentmarker->id.');' ."\n";
															$scripttext .= '  map'.$mapDivSuffix.'.setZoom('.(int)$currentmarker->zoombyclick.');' ."\n";
														}
													}
												break;
												// Link in self
												case 3:
													if ($currentmarker->hrefsite != "")
													{
														if ((int)$currentmarker->zoombyclick != 100)
														{
															$scripttext .= '  map'.$mapDivSuffix.'.setCenter(latlng'. $currentmarker->id.');' ."\n";
															$scripttext .= '  map'.$mapDivSuffix.'.setZoom('.(int)$currentmarker->zoombyclick.');' ."\n";
														}
														$scripttext .= '  window.location = "'.$currentmarker->hrefsite.'";' ."\n";
													}
													else
													{
														if ((int)$currentmarker->zoombyclick != 100)
														{
															$scripttext .= '  map'.$mapDivSuffix.'.setCenter(latlng'. $currentmarker->id.');' ."\n";
															$scripttext .= '  map'.$mapDivSuffix.'.setZoom('.(int)$currentmarker->zoombyclick.');' ."\n";
														}
													}
												break;
												default:
													$scripttext .= '' ."\n";
												break;
											}
											
											// Action By Click - For PlacemarkList - End
											
											$scripttext .= '};'."\n";
										}
										else
										{
											$scripttext .= ' markerASel.onclick = function(){ map'.$mapDivSuffix.'.setCenter(latlng'. $currentmarker->id.');};'."\n";
										}

										$scripttext .= ' markerASelWrp.appendChild(markerASel);'."\n";
										$scripttext .= ' markerLI_C1.appendChild(markerASelWrp);'."\n";
										if ((int)$map->markerlistcontent == 102) 
										{
											$scripttext .= ' markerLI_C2.appendChild(markerDSel);'."\n";
										}
										
										
										$scripttext .= ' markerLI.appendChild(markerLI_C1);'."\n";
										if ((int)$map->markerlistcontent == 102) 
										{
											$scripttext .= ' markerLI.appendChild(markerLI_C2);'."\n";
										}
										$scripttext .= ' markerUL.appendChild(markerLI);'."\n";
								}
								$scripttext .= '}'."\n";
							}
						}
						// Generating Placemark List - End


						// Change Map center and set Center Placemark Action
						if ($currentPlacemarkCenter != "do not change")
						{
							if ((int)$currentPlacemarkCenter == $currentmarker->id)
							{
								$scripttext .= 'map'.$mapDivSuffix.'.setCenter(latlng'.(int)$currentPlacemarkCenter.');'."\n";
								$scripttext .= 'mapcenter'.$mapDivSuffix.' = latlng'.(int)$currentPlacemarkCenter.';'."\n";
							}
						}						
						
			}
			// Main IF End
				
		} 
		// End foreach
                
	}


	// Ajax Marker Listeners
	if (isset($map->useajax) && (int)$map->useajax != 0) 
	{
        //$scripttext .= 'alert("begin: '.$mapDivSuffix.'");' ."\n";
        $scripttext .= 'for (var i=0; i<ajaxmarkersLL'.$mapDivSuffix.'.length; i++)' ."\n";
        $scripttext .= '{' ."\n";
		//$scripttext .= '    alert("Call:"+ajaxmarkersLL'.$mapDivSuffix.'[i].get("zhymPlacemarkID"));' ."\n";
		if ((int)$map->useajax == 1)
		{
			$scripttext .= '  zhymObjMgr'.$mapDivSuffix.'.PlacemarkAddListeners("mootools", ajaxmarkersLL'.$mapDivSuffix.'[i]);' ."\n";
		}
		else
		{
			$scripttext .= '  zhymObjMgr'.$mapDivSuffix.'.PlacemarkAddListeners("jquery", ajaxmarkersLL'.$mapDivSuffix.'[i]);' ."\n";
		}
        $scripttext .= '}' ."\n";
        //scripttext .= 'alert("-end");' ."\n";
	}

	if ($placemarkSearch != 0)
	{
		$scripttext .= '  zhymObjMgr'.$mapDivSuffix.'.enablePlacemarkListSearch();'."\n";
	}
        
	if ($zhymObjectManager != 0)
	{
		if ($ajaxLoadObjects != 0)
		{
			$scripttext .= 'zhymObjMgr'.$mapDivSuffix.'.setPlacemarkLoadType('.$ajaxLoadObjectType.');' ."\n";

			if ($ajaxLoadObjectType == 2)
			{
				// When something changed
				$scripttext .= 'map'.$mapDivSuffix.'.events.add(\'boundschange\', function(event) {' ."\n";
				if ($ajaxLoadObjects == 1)
				{
					$scripttext .= '  zhymObjMgr'.$mapDivSuffix.'.GetPlacemarkAJAX("mootools");' ."\n";
				}
				else if ($ajaxLoadObjects == 2)
				{
					$scripttext .= '  zhymObjMgr'.$mapDivSuffix.'.GetPlacemarkAJAX("jquery");' ."\n";
				}
				$scripttext .= '});' ."\n";	
			}
		}
	}
	
	// Routers
	if (isset($routers) && !empty($routers)) 
	{
		$routepanelcount = 0;
		$routepaneltotalcount = 0;

		$routeHTMLdescription ='';
		
		//Begin for each Route
		foreach ($routers as $key => $currentrouter) 
		{
			$routername = 'route'. $currentrouter->id;
			$routererror = 'routeError'. $currentrouter->id;
			if ($currentrouter->route != "")
			{
				$scripttext .= 'ymaps.route(['.$currentrouter->route.'],'."\n";
					$scripttext .=  '  { ';
					if (isset($currentrouter->showtype) && (int)$currentrouter->showtype == 1)
					{
						$scripttext .=       ' mapStateAutoApply: false ';
					}
					else
					{
						$scripttext .=       ' mapStateAutoApply: true ';
					}
					if (isset($currentrouter->checktraffic) && (int)$currentrouter->checktraffic == 1)
					{
						$scripttext .=       ', avoidTrafficJams: true ';
					}
					else
					{
						$scripttext .=       ', avoidTrafficJams: false ';
					}
					$scripttext .= '  }).then('."\n";
					$scripttext .= '  function('.$routername.'){'."\n";
					$scripttext .= '     map'.$mapDivSuffix.'.geoObjects.add('.$routername.');'."\n";
					
					if (isset($currentrouter->hidewaypoints) && (int)$currentrouter->hidewaypoints == 1) 
					{
						$scripttext .= '     var points = '.$routername.'.getWayPoints();  '."\n";
						$scripttext .= '     points.options.set("visible", false);'."\n";
					}
				

					if (isset($currentrouter->showpanel) && (int)$currentrouter->showpanel == 1) 
					{
						$scripttext .= '     var segCounter = 0;'."\n";
						$scripttext .= '     var moveList = \'<table class="zhym-route-table">\';'."\n";
						$scripttext .= '         moveList += \'<tbody class="zhym-route-tablebody">\';'."\n";
						$scripttext .= '     for (var j = 0; j < '.$routername.'.getPaths().getLength(); j++) {'."\n";
						$scripttext .= '         moveList += \'<tr class="zhym-route-table-tr">\''."\n";
						$scripttext .= '         moveList += \'<td class="zhym-route-table-td-waypoint" colspan="2">\''."\n";
						$scripttext .= '         segCounter += 1;'."\n";
						$scripttext .= '         if (segCounter == 1)'."\n";
						$scripttext .= '         {'."\n";
						$scripttext .= '        	 moveList += segCounter + \' '.JText::_('COM_ZHYANDEXMAP_MAP_FIND_GEOCODING_START_POINT').'</br>\';'."\n";
						$scripttext .= '         }'."\n";
						$scripttext .= '         else'."\n";
						$scripttext .= '         {'."\n";
						$scripttext .= '         	moveList += segCounter + \' '.JText::_('COM_ZHYANDEXMAP_MAP_FIND_GEOCODING_WAY_POINT').'</br>\';'."\n";
						$scripttext .= '         }'."\n";
						$scripttext .= '         moveList += \'</td>\''."\n";
						$scripttext .= '         moveList += \'</tr>\''."\n";
						$scripttext .= '       var way = '.$routername.'.getPaths().get(j);'."\n";
						$scripttext .= '       var segments = way.getSegments();'."\n";
						$scripttext .= '       var segmentlength = 0.;'."\n";

						$scripttext .= '         moveList += \'<tr class="zhym-route-table-tr-step">\''."\n";
						$scripttext .= '         moveList += \'<td class="zhym-route-table-td"  colspan="2">\''."\n";
						
						$scripttext .= ' var total_km = way.getHumanLength();'."\n";
						if (isset($currentrouter->checktraffic) && (int)$currentrouter->checktraffic == 1)
						{
							$scripttext .= ' var total_time = way.getHumanJamsTime();'."\n";
						}
						else
						{
							$scripttext .= ' var total_time = way.getHumanTime();'."\n";
						}

						$scripttext .= '         moveList += \'';
						$scripttext .= JText::_('COM_ZHYANDEXMAP_MAPROUTER_DETAIL_SHOWPANEL_HDR_TOTAL_KM');
						$scripttext .= ' \'+ total_km + \' ';
						//$scripttext .= JText::_('COM_ZHYANDEXMAP_MAPROUTER_DETAIL_SHOWPANEL_HDR_KM');
						$scripttext .= ', ';
						$scripttext .= JText::_('COM_ZHYANDEXMAP_MAPROUTER_DETAIL_SHOWPANEL_HDR_TOTAL_TIME');
						if (isset($currentrouter->checktraffic) && (int)$currentrouter->checktraffic == 1)
						{
							$scripttext .= ' '.JText::_('COM_ZHYANDEXMAP_MAPROUTER_DETAIL_SHOWPANEL_HDR_JAM');
						}
						$scripttext .= ' \' + total_time;';
						//$scripttext .= JText::_('COM_ZHYANDEXMAP_MAPROUTER_DETAIL_SHOWPANEL_HDR_TIME');
						$scripttext .= '         moveList += \'</td>\''."\n";
						$scripttext .= '         moveList += \'</tr>\''."\n";
						
						$scripttext .= '       for (var i = 0; i < segments.length; i++) {'."\n";


						$scripttext .= '         moveList += \'<tr class="zhym-route-table-tr-step">\''."\n";
						$scripttext .= '         var street = segments[i].getStreet();'."\n";
						$scripttext .= '         moveList += \'<td class="zhym-route-table-td">\''."\n";
						$scripttext .= '         moveList += \''.JText::_('COM_ZHYANDEXMAP_MAP_FIND_GEOCODING_MOVE').' <b>\' + segments[i].getHumanAction() + \'</b>\'+(street ? \' '.JText::_('COM_ZHYANDEXMAP_MAP_FIND_GEOCODING_ON').' <b>\' + street + \'</b>\': \'\');'."\n";
						$scripttext .= '         moveList += \'</td>\''."\n";
						$scripttext .= '         moveList += \'<td class="zhym-route-table-td">\''."\n";
						$scripttext .= '         segmentlength = segments[i].getLength();'."\n";
						$scripttext .= '         if (segmentlength > 500)'."\n";
						$scripttext .= '         {'."\n";
						$scripttext .= '            segmentlength = segmentlength/1000.;'."\n";
						$scripttext .= '         	moveList += segmentlength.toFixed(1) + \' '.JText::_('COM_ZHYANDEXMAP_MAP_FIND_GEOCODING_KILOMETERS').'\';'."\n";
						$scripttext .= '         }'."\n";
						$scripttext .= '         else'."\n";
						$scripttext .= '         {'."\n";
						$scripttext .= '         	moveList += segmentlength.toFixed(0) + \' '.JText::_('COM_ZHYANDEXMAP_MAP_FIND_GEOCODING_METERS').'\';'."\n";
						$scripttext .= '         }'."\n";
						$scripttext .= '         moveList += \'</td>\''."\n";
						$scripttext .= '         moveList += \'</tr>\''."\n";
						$scripttext .= '       }'."\n";
						$scripttext .= '     }'."\n";
						$scripttext .= '         moveList += \'<tr class="zhym-route-table-tr">\''."\n";
						$scripttext .= '         moveList += \'<td class="zhym-route-table-td-waypoint" colspan="2">\''."\n";
						$scripttext .= '         segCounter += 1;'."\n";
						$scripttext .= '      moveList += segCounter + \' '.JText::_('COM_ZHYANDEXMAP_MAP_FIND_GEOCODING_END_POINT').'\';'."\n";
						$scripttext .= '         moveList += \'</td>\''."\n";
						$scripttext .= '         moveList += \'</tr>\''."\n";
						$scripttext .= '      moveList += \'</tbody>\';'."\n";
						$scripttext .= '      moveList += \'</table>\';'."\n";
						$scripttext .= '  document.getElementById("YMapsRoutePanel_Steps'.$mapDivSuffix.'").innerHTML = \'\'+moveList+\'\';' ."\n";


						$scripttext .= ' var total_km = '.$routername.'.getHumanLength();'."\n";
						if (isset($currentrouter->checktraffic) && (int)$currentrouter->checktraffic == 1)
						{
							$scripttext .= ' var total_time = '.$routername.'.getHumanJamsTime();'."\n";
						}
						else
						{
							$scripttext .= ' var total_time = '.$routername.'.getHumanTime();'."\n";
						}

						$scripttext .= '  document.getElementById("YMapsRoutePanel_Total'.$mapDivSuffix.'").innerHTML = "<p>';
						$scripttext .= JText::_('COM_ZHYANDEXMAP_MAPROUTER_DETAIL_SHOWPANEL_HDR_TOTAL_KM');
						$scripttext .= ' " + total_km + " ';
						//$scripttext .= JText::_('COM_ZHYANDEXMAP_MAPROUTER_DETAIL_SHOWPANEL_HDR_KM');
						$scripttext .= ', ';
						$scripttext .= JText::_('COM_ZHYANDEXMAP_MAPROUTER_DETAIL_SHOWPANEL_HDR_TOTAL_TIME');
						if (isset($currentrouter->checktraffic) && (int)$currentrouter->checktraffic == 1)
						{
							$scripttext .= ' '.JText::_('COM_ZHYANDEXMAP_MAPROUTER_DETAIL_SHOWPANEL_HDR_JAM');
						}
						$scripttext .= ' " + total_time + " ';
						//$scripttext .= JText::_('COM_ZHYANDEXMAP_MAPROUTER_DETAIL_SHOWPANEL_HDR_TIME');
						$scripttext .= '</p>";' ."\n";
						
					}
					

					$scripttext .= '  }, '."\n";
					$scripttext .= '  function('.$routererror.'){'."\n";
					$scripttext .= '     alert(\''.JText::_('COM_ZHYANDEXMAP_MAP_ERROR_GEOCODING').'\' + '.$routererror.'.message);'."\n";
					$scripttext .= '  }'."\n";
					$scripttext .= ');'."\n";
					

				if (isset($currentrouter->showpanel) && (int)$currentrouter->showpanel == 1) 
				{
					$routepanelcount++;
					if (isset($currentrouter->showpaneltotal) && (int)$currentrouter->showpaneltotal == 1) 
					{
						$routepaneltotalcount++;
					}
				}
				
			}
			
			
			
			
			if ($currentrouter->routebymarker != "")
			{
				$router2name = 'routeByMarker'. $currentrouter->id;
				$router2error = 'routeByMarkerError'. $currentrouter->id;
				
				$cs = explode(";", $currentrouter->routebymarker);
				$cs_total = count($cs)-1;
				$cs_idx = 0;
				$wp_list = '';
				$skipRouteCreation = 0;
				foreach($cs as $curroute)
				{	
					$currouteLatLng = comZhYandexMapPlacemarksHelper::get_placemark_coordinates($curroute);
					//$scripttext .= 'alert("'.$currouteLatLng.'");'."\n";

					if ($currouteLatLng != "")
					{
						if ($currouteLatLng == "geocode")
						{
							$scripttext .= 'alert(\''.JText::_('COM_ZHYANDEXMAP_MAPROUTER_FINDMARKER_ERROR_GEOCODE').' '.$curroute.'\');'."\n";
							$skipRouteCreation = 1;
						}
						else
						{
							if ($cs_idx == 0)
							{
								$wp_start = ' '.$currouteLatLng.''."\n";
							}
							else if ($cs_idx == $cs_total)
							{
								$wp_end = ', '.$currouteLatLng.' '."\n";
							}
							else
							{
								if ($wp_list == '')
								{
									$wp_list .= ', '.$currouteLatLng;
								}
								else
								{
									$wp_list .= ', '.$currouteLatLng;
								}
							}
						}
					}
					else
					{
						$scripttext .= 'alert(\''.JText::_('COM_ZHYANDEXMAP_MAPROUTER_FINDMARKER_ERROR_REASON').' '.$curroute.'\');'."\n";
						$skipRouteCreation = 1;
					}

					$cs_idx += 1;
				}

				if ($skipRouteCreation == 0)
				{
					$routeToDraw = $wp_start . $wp_list . $wp_end;
					
					$scripttext .= 'ymaps.route(['.$routeToDraw.'],'."\n";
					$scripttext .=       '{ ';
					//strokeColor: 
					//opacity:
					if (isset($currentrouter->showtype) && (int)$currentrouter->showtype == 1)
					{
						$scripttext .=       ' mapStateAutoApply: false ';
					}
					else
					{
						$scripttext .=       ' mapStateAutoApply: true ';
					}
					if (isset($currentrouter->checktraffic) && (int)$currentrouter->checktraffic == 1)
					{
						$scripttext .=       ', avoidTrafficJams: true ';
					}
					else
					{
						$scripttext .=       ', avoidTrafficJams: false ';
					}
					$scripttext .= '  }).then('."\n";
					$scripttext .= '  function('.$router2name.'){'."\n";
					$scripttext .= '     map'.$mapDivSuffix.'.geoObjects.add('.$router2name.');'."\n";

					if (isset($currentrouter->hidewaypoints) && (int)$currentrouter->hidewaypoints == 1) 
					{
						$scripttext .= '     var points = '.$router2name.'.getWayPoints();  '."\n";
						$scripttext .= '     points.options.set("visible", false);'."\n";
					}
					
					if (isset($currentrouter->showpanel) && (int)$currentrouter->showpanel == 1) 
					{
						$scripttext .= '     var segCounter = 0;'."\n";
						$scripttext .= '     var moveList = \'<table class="zhym-route-table">\';'."\n";
						$scripttext .= '         moveList += \'<tbody class="zhym-route-tablebody">\';'."\n";
						$scripttext .= '     for (var j = 0; j < '.$router2name.'.getPaths().getLength(); j++) {'."\n";
						$scripttext .= '         moveList += \'<tr class="zhym-route-table-tr">\''."\n";
						$scripttext .= '         moveList += \'<td class="zhym-route-table-td-waypoint" colspan="2">\''."\n";
						$scripttext .= '         segCounter += 1;'."\n";
						$scripttext .= '         if (segCounter == 1)'."\n";
						$scripttext .= '         {'."\n";
						$scripttext .= '        	 moveList += segCounter + \' '.JText::_('COM_ZHYANDEXMAP_MAP_FIND_GEOCODING_START_POINT').'</br>\';'."\n";
						$scripttext .= '         }'."\n";
						$scripttext .= '         else'."\n";
						$scripttext .= '         {'."\n";
						$scripttext .= '         	moveList += segCounter + \' '.JText::_('COM_ZHYANDEXMAP_MAP_FIND_GEOCODING_WAY_POINT').'</br>\';'."\n";
						$scripttext .= '         }'."\n";
						$scripttext .= '         moveList += \'</td>\''."\n";
						$scripttext .= '         moveList += \'</tr>\''."\n";
						$scripttext .= '       var way = '.$router2name.'.getPaths().get(j);'."\n";
						$scripttext .= '       var segments = way.getSegments();'."\n";
						$scripttext .= '       var segmentlength = 0.;'."\n";

						$scripttext .= '         moveList += \'<tr class="zhym-route-table-tr-step">\''."\n";
						$scripttext .= '         moveList += \'<td class="zhym-route-table-td"  colspan="2">\''."\n";
						
						$scripttext .= ' var total_km = way.getHumanLength();'."\n";
						if (isset($currentrouter->checktraffic) && (int)$currentrouter->checktraffic == 1)
						{
							$scripttext .= ' var total_time = way.getHumanJamsTime();'."\n";
						}
						else
						{
							$scripttext .= ' var total_time = way.getHumanTime();'."\n";
						}

						$scripttext .= '         moveList += \'';
						$scripttext .= JText::_('COM_ZHYANDEXMAP_MAPROUTER_DETAIL_SHOWPANEL_HDR_TOTAL_KM');
						$scripttext .= ' \'+ total_km + \' ';
						//$scripttext .= JText::_('COM_ZHYANDEXMAP_MAPROUTER_DETAIL_SHOWPANEL_HDR_KM');
						$scripttext .= ', ';
						$scripttext .= JText::_('COM_ZHYANDEXMAP_MAPROUTER_DETAIL_SHOWPANEL_HDR_TOTAL_TIME');
						if (isset($currentrouter->checktraffic) && (int)$currentrouter->checktraffic == 1)
						{
							$scripttext .= ' '.JText::_('COM_ZHYANDEXMAP_MAPROUTER_DETAIL_SHOWPANEL_HDR_JAM');
						}
						$scripttext .= ' \' + total_time;';
						//$scripttext .= JText::_('COM_ZHYANDEXMAP_MAPROUTER_DETAIL_SHOWPANEL_HDR_TIME');
						$scripttext .= '         moveList += \'</td>\''."\n";
						$scripttext .= '         moveList += \'</tr>\''."\n";
						
						$scripttext .= '       for (var i = 0; i < segments.length; i++) {'."\n";


						$scripttext .= '         moveList += \'<tr class="zhym-route-table-tr-step">\''."\n";
						$scripttext .= '         var street = segments[i].getStreet();'."\n";
						$scripttext .= '         moveList += \'<td class="zhym-route-table-td">\''."\n";
						$scripttext .= '         moveList += \''.JText::_('COM_ZHYANDEXMAP_MAP_FIND_GEOCODING_MOVE').' <b>\' + segments[i].getHumanAction() + \'</b>\'+(street ? \' '.JText::_('COM_ZHYANDEXMAP_MAP_FIND_GEOCODING_ON').' <b>\' + street + \'</b>\': \'\');'."\n";
						$scripttext .= '         moveList += \'</td>\''."\n";
						$scripttext .= '         moveList += \'<td class="zhym-route-table-td">\''."\n";
						$scripttext .= '         segmentlength = segments[i].getLength();'."\n";
						$scripttext .= '         if (segmentlength > 500)'."\n";
						$scripttext .= '         {'."\n";
						$scripttext .= '            segmentlength = segmentlength/1000.;'."\n";
						$scripttext .= '         	moveList += segmentlength.toFixed(1) + \' '.JText::_('COM_ZHYANDEXMAP_MAP_FIND_GEOCODING_KILOMETERS').'\';'."\n";
						$scripttext .= '         }'."\n";
						$scripttext .= '         else'."\n";
						$scripttext .= '         {'."\n";
						$scripttext .= '         	moveList += segmentlength.toFixed(0) + \' '.JText::_('COM_ZHYANDEXMAP_MAP_FIND_GEOCODING_METERS').'\';'."\n";
						$scripttext .= '         }'."\n";
						$scripttext .= '         moveList += \'</td>\''."\n";
						$scripttext .= '         moveList += \'</tr>\''."\n";
						$scripttext .= '       }'."\n";
						$scripttext .= '     }'."\n";
						$scripttext .= '         moveList += \'<tr class="zhym-route-table-tr">\''."\n";
						$scripttext .= '         moveList += \'<td class="zhym-route-table-td-waypoint" colspan="2">\''."\n";
						$scripttext .= '         segCounter += 1;'."\n";
						$scripttext .= '      moveList += segCounter + \' '.JText::_('COM_ZHYANDEXMAP_MAP_FIND_GEOCODING_END_POINT').'\';'."\n";
						$scripttext .= '         moveList += \'</td>\''."\n";
						$scripttext .= '         moveList += \'</tr>\''."\n";
						$scripttext .= '      moveList += \'</tbody>\';'."\n";
						$scripttext .= '      moveList += \'</table>\';'."\n";
						$scripttext .= '  document.getElementById("YMapsRoutePanel_Steps'.$mapDivSuffix.'").innerHTML = \'\'+moveList+\'\';' ."\n";


						$scripttext .= ' var total_km = '.$router2name.'.getHumanLength();'."\n";
						if (isset($currentrouter->checktraffic) && (int)$currentrouter->checktraffic == 1)
						{
							$scripttext .= ' var total_time = '.$router2name.'.getHumanJamsTime();'."\n";
						}
						else
						{
							$scripttext .= ' var total_time = '.$router2name.'.getHumanTime();'."\n";
						}

						$scripttext .= '  document.getElementById("YMapsRoutePanel_Total'.$mapDivSuffix.'").innerHTML = "<p>';
						$scripttext .= JText::_('COM_ZHYANDEXMAP_MAPROUTER_DETAIL_SHOWPANEL_HDR_TOTAL_KM');
						$scripttext .= ' " + total_km + " ';
						//$scripttext .= JText::_('COM_ZHYANDEXMAP_MAPROUTER_DETAIL_SHOWPANEL_HDR_KM');
						$scripttext .= ', ';
						$scripttext .= JText::_('COM_ZHYANDEXMAP_MAPROUTER_DETAIL_SHOWPANEL_HDR_TOTAL_TIME');
						if (isset($currentrouter->checktraffic) && (int)$currentrouter->checktraffic == 1)
						{
							$scripttext .= ' '.JText::_('COM_ZHYANDEXMAP_MAPROUTER_DETAIL_SHOWPANEL_HDR_JAM');
						}
						$scripttext .= ' " + total_time + " ';
						//$scripttext .= JText::_('COM_ZHYANDEXMAP_MAPROUTER_DETAIL_SHOWPANEL_HDR_TIME');
						$scripttext .= '</p>";' ."\n";
						
					}
					
					$scripttext .= '  }, '."\n";
					$scripttext .= '  function('.$router2error.'){'."\n";
					$scripttext .= '     alert(\''.JText::_('COM_ZHYANDEXMAP_MAP_ERROR_GEOCODING').'\' + '.$router2error.'.message);'."\n";
					$scripttext .= '  }'."\n";
					$scripttext .= ');'."\n";

					if (isset($currentrouter->showpanel) && (int)$currentrouter->showpanel == 1) 
					{
						$routepanelcount++;
						if (isset($currentrouter->showpaneltotal) && (int)$currentrouter->showpaneltotal == 1) 
						{
							$routepaneltotalcount++;
						}
					}

				}

			}
			
			
			
			if (isset($currentrouter->showdescription) && (int)$currentrouter->showdescription == 1) 
			{
				if ($currentrouter->description != "")
				{
					$routeHTMLdescription .= '<h2>';
					$routeHTMLdescription .= htmlspecialchars($currentrouter->description, ENT_QUOTES, 'UTF-8');
					$routeHTMLdescription .= '</h2>';
				}
				if ($currentrouter->descriptionhtml != "")
				{
					$routeHTMLdescription .= str_replace("'", "\'", str_replace(array("\r", "\r\n", "\n"), '', $currentrouter->descriptionhtml));
				}
			}

			if ($currentrouter->kmllayerymapsml != "")
			{
				$kml1 = 'YMapsML'.$routername;
				$scripttext .= 'ymaps.geoXml.load(\''.$currentrouter->kmllayerymapsml.'\').then(' ."\n";
				$scripttext .= '	function('.$kml1.') {' ."\n";
				$scripttext .= '		map'.$mapDivSuffix.'.geoObjects.add('.$kml1.'.geoObjects);' ."\n";
				$scripttext .= '		if ('.$kml1.'.mapState) ' ."\n";
				$scripttext .= '		{' ."\n";
				$scripttext .= '			'.$kml1.'.mapState.applyToMap(map);' ."\n";
				$scripttext .= '		}' ."\n";
				$scripttext .= '	},' ."\n";
				$scripttext .= '	function(error) {' ."\n";
				$scripttext .= '    alert(\''.JText::_('COM_ZHYANDEXMAP_MAP_ERROR_YMAPSML').'\' + error.message);' ."\n";
				$scripttext .= '	}' ."\n";
				$scripttext .= ');	' ."\n";
			}


			if ($currentrouter->kmllayerkml != "")
			{
				$kml2 = 'KML'.$routername;
				$scripttext .= 'ymaps.geoXml.load(\''.$currentrouter->kmllayerkml.'\').then(' ."\n";
				$scripttext .= '	function('.$kml2.') {' ."\n";
				$scripttext .= '		map'.$mapDivSuffix.'.geoObjects.add('.$kml2.'.geoObjects);' ."\n";
				$scripttext .= '		if ('.$kml2.'.mapState) ' ."\n";
				$scripttext .= '		{' ."\n";
				$scripttext .= '			'.$kml2.'.mapState.applyToMap(map);' ."\n";
				$scripttext .= '		}' ."\n";
				$scripttext .= '	},' ."\n";
				$scripttext .= '	function(error) {' ."\n";
				$scripttext .= '    alert(\''.JText::_('COM_ZHYANDEXMAP_MAP_ERROR_KML').'\' + error.message);' ."\n";
				$scripttext .= '	}' ."\n";
				$scripttext .= ');	' ."\n";
			}

			if ($currentrouter->kmllayergpx != "")
			{
				$kml3 = 'GPX'.$routername;
				$scripttext .= 'ymaps.geoXml.load(\''.$currentrouter->kmllayergpx.'\').then(' ."\n";
				$scripttext .= '	function('.$kml3.') {' ."\n";
				$scripttext .= '		map'.$mapDivSuffix.'.geoObjects.add('.$kml3.'.geoObjects);' ."\n";
				$scripttext .= '		if ('.$kml3.'.mapState) ' ."\n";
				$scripttext .= '		{' ."\n";
				$scripttext .= '			'.$kml3.'.mapState.applyToMap(map);' ."\n";
				$scripttext .= '		}' ."\n";
				$scripttext .= '	},' ."\n";
				$scripttext .= '	function(error) {' ."\n";
				$scripttext .= '    alert(\''.JText::_('COM_ZHYANDEXMAP_MAP_ERROR_GPX').'\' + error.message);' ."\n";
				$scripttext .= '	}' ."\n";
				$scripttext .= ');	' ."\n";
			}
			
			
		}
		// End for each Route
		
		if ($routepanelcount > 1 || $routepanelcount == 0 || $routepaneltotalcount == 0)
		{
			$scripttext .= 'var toHideRouteDiv = document.getElementById("YMapsRoutePanel_Total'.$mapDivSuffix.'");' ."\n";
			$scripttext .= 'toHideRouteDiv.style.display = "none";' ."\n";
			//$scripttext .= 'alert("Hide because > 1 or = 0");';
		}

		if ($routeHTMLdescription != "")
		{
			$scripttext .= '  document.getElementById("YMapsRoutePanel_Description'.$mapDivSuffix.'").innerHTML =  "<p>'. $routeHTMLdescription .'</p>";'."\n";
		}
		
		
	}
	// Routes End
	
	

	// Paths
	if (isset($paths) && !empty($paths)) 
	{
		foreach ($paths as $key => $currentpath) 
		{

			$scripttext .= 'var plProperties'.$currentpath->id.' = {'."\n";
			$scripttext .= ' hintContent: "'.htmlspecialchars(str_replace('\\', '/', $currentpath->title), ENT_QUOTES, 'UTF-8').'"' ."\n";			
			$scripttext .= '};'."\n";
		
			$scripttext .= ' var plOptions'.$currentpath->id.' = {'."\n";
			$scripttext .= ' strokeColor: \''.$currentpath->color.'\''."\n";
			if ($currentpath->opacity != "")
			{
				$scripttext .= ', strokeOpacity: \''.$currentpath->opacity.'\''."\n";
			}
			$scripttext .= ', strokeWidth: \''.$currentpath->width.'\''."\n";

			if ((int)$currentpath->objecttype == 1
			 || (int)$currentpath->objecttype == 2)
			{
				if ($currentpath->fillcolor != "")
				{
					$scripttext .= ', fillColor: \''.$currentpath->fillcolor.'\''."\n";
				}
				if ($currentpath->fillopacity != "")
				{
					$scripttext .= ', fillOpacity: \''.$currentpath->fillopacity.'\''."\n";
				}
				if ($currentpath->fillimageurl != "")
				{
					$scripttext .= ', fillImageHref: \''.$currentpath->fillimageurl.'\''."\n";
				}
			}
			
			if ((int)$currentpath->geodesic == 1)
			{
				$scripttext .= ', geodesic: true '."\n";
			}
			else
			{
				$scripttext .= ', geodesic: false '."\n";
			}
			$scripttext .= ' };'."\n";

			if ((int)$currentpath->actionbyclick == 1)
			{
				
				$scripttext .= 'var contentPathStringHead'. $currentpath->id.' = \'<div id="contentHeadPathContent'. $currentpath->id.'">\' +' ."\n";
				if (isset($currentpath->infowincontent) &&
					(((int)$currentpath->infowincontent == 0) ||
					 ((int)$currentpath->infowincontent == 1))
					)
				{
					$scripttext .= '\'<'.$placemarkTitleTag.' id="headPathContent'. $currentpath->id.'" class="pathHead">'.htmlspecialchars(str_replace('\\', '/', $currentpath->title), ENT_QUOTES, 'UTF-8').'</'.$placemarkTitleTag.'>\'+' ."\n";
				}
				$scripttext .= '\'</div>\';'."\n";
				
				$scripttext .= 'var contentPathStringBody'. $currentpath->id.' = \'<div id="contentBodyPathContent'. $currentpath->id.'"  class="pathBody">\'+'."\n";


						if (isset($currentpath->infowincontent) &&
							(((int)$currentpath->infowincontent == 0) ||
							 ((int)$currentpath->infowincontent == 2))
							)
						{
							$scripttext .= '\''.htmlspecialchars(str_replace('\\', '/', $currentpath->description), ENT_QUOTES, 'UTF-8').'\'+'."\n";
						}
						$scripttext .= '\''.str_replace("'", "\'", str_replace(array("\r", "\r\n", "\n"), '', $currentpath->descriptionhtml)).'\'+'."\n";

						
				$scripttext .= '\'</div>\';'."\n";
				
			}
			

			if ((int)$currentpath->objecttype == 0)
			{
			
				$scripttext .= ' var plGeometry'.$currentpath->id.' = ['."\n";
				$scripttext .= '['.str_replace(";","],[", $currentpath->path).']'."\n";
				$scripttext .= ' ];'."\n";
				
				$curpathname = 'pl'.$currentpath->id;
				
				$scripttext .= ' var '.$curpathname.' = new ymaps.Polyline(plGeometry'.$currentpath->id.', plProperties'.$currentpath->id.', plOptions'.$currentpath->id.');'."\n";

				if ((int)$currentpath->actionbyclick == 1)
				{
					$scripttext .= $curpathname.'.properties.set("balloonContentHeader", contentPathStringHead'. $currentpath->id.');' ."\n";
					$scripttext .= $curpathname.'.properties.set("balloonContentBody", contentPathStringBody'. $currentpath->id.');' ."\n";
				}
				
				$scripttext .= 'map'.$mapDivSuffix.'.geoObjects.add('.$curpathname.');'."\n";
			}
			else if ((int)$currentpath->objecttype == 1)
			{
				$scripttext .= ' var plGeometry'.$currentpath->id.' = ['."\n";
				$scripttext .= '[['.str_replace(";","],[", $currentpath->path).']]'."\n";
				$scripttext .= ' ,[]];'."\n";
				
				$curpathname = 'pl'.$currentpath->id;
				$scripttext .= ' var '.$curpathname.' = new ymaps.Polygon(plGeometry'.$currentpath->id.', plProperties'.$currentpath->id.', plOptions'.$currentpath->id.');'."\n";

				if ((int)$currentpath->actionbyclick == 1)
				{
					$scripttext .= $curpathname.'.properties.set("balloonContentHeader", contentPathStringHead'. $currentpath->id.');' ."\n";
					$scripttext .= $curpathname.'.properties.set("balloonContentBody", contentPathStringBody'. $currentpath->id.');' ."\n";
				}

				$scripttext .= 'map'.$mapDivSuffix.'.geoObjects.add('.$curpathname.');'."\n";
			}
			else if ((int)$currentpath->objecttype == 2)
			{
				if ($currentpath->radius != "")
				{
					$arrayPathCoords = explode(';', $currentpath->path);
					$arrayPathIndex = 0;
					foreach ($arrayPathCoords as $currentpathcoordinates) 
					{
						$arrayPathIndex += 1;
						$scripttext .= ' var plGeometry'.$currentpath->id.'_'.$arrayPathIndex.' = ['."\n";
						$scripttext .= '['.$currentpathcoordinates.']'."\n";
						$scripttext .= ', '.$currentpath->radius."\n";
						$scripttext .= ' ];'."\n";
						
						$curpathname = 'pl'.$currentpath->id.'_'.$arrayPathIndex;
						$scripttext .= ' var '.$curpathname.' = new ymaps.Circle(plGeometry'.$currentpath->id.'_'.$arrayPathIndex.', plProperties'.$currentpath->id.', plOptions'.$currentpath->id.');'."\n";

						if ((int)$currentpath->actionbyclick == 1)
						{
							$scripttext .= $curpathname.'.properties.set("balloonContentHeader", contentPathStringHead'. $currentpath->id.');' ."\n";
							$scripttext .= $curpathname.'.properties.set("balloonContentBody", contentPathStringBody'. $currentpath->id.');' ."\n";
						}
						
						$scripttext .= 'map'.$mapDivSuffix.'.geoObjects.add('.$curpathname.');'."\n";
					}
				}
			}
			
		}
	}

	
	$context_suffix = 'map'.$mapDivSuffix.'';

	if ($map->kmllayer != "")
	{
		$kml1 = 'YMapsML'.$context_suffix;
		$scripttext .= 'ymaps.geoXml.load(\''.$map->kmllayer.'\').then(' ."\n";
		$scripttext .= '	function('.$kml1.') {' ."\n";
		$scripttext .= '		map'.$mapDivSuffix.'.geoObjects.add('.$kml1.'.geoObjects);' ."\n";
		$scripttext .= '		if ('.$kml1.'.mapState) ' ."\n";
		$scripttext .= '		{' ."\n";
		$scripttext .= '			'.$kml1.'.mapState.applyToMap(map);' ."\n";
		$scripttext .= '		}' ."\n";
		$scripttext .= '	},' ."\n";
		$scripttext .= '	function(error) {' ."\n";
		$scripttext .= '    alert(\''.JText::_('COM_ZHYANDEXMAP_MAP_ERROR_YMAPSML').'\' + error.message);' ."\n";
		$scripttext .= '	}' ."\n";
		$scripttext .= ');	' ."\n";
	}

	if ($map->kmllayerkml != "")
	{
		$kml2 = 'KML'.$context_suffix;
		$scripttext .= 'ymaps.geoXml.load(\''.$map->kmllayerkml.'\').then(' ."\n";
		$scripttext .= '	function('.$kml2.') {' ."\n";
		$scripttext .= '		map'.$mapDivSuffix.'.geoObjects.add('.$kml2.'.geoObjects);' ."\n";
		$scripttext .= '		if ('.$kml2.'.mapState) ' ."\n";
		$scripttext .= '		{' ."\n";
		$scripttext .= '			'.$kml2.'.mapState.applyToMap(map);' ."\n";
		$scripttext .= '		}' ."\n";
		$scripttext .= '	},' ."\n";
		$scripttext .= '	function(error) {' ."\n";
		$scripttext .= '    alert(\''.JText::_('COM_ZHYANDEXMAP_MAP_ERROR_KML').'\' + error.message);' ."\n";
		$scripttext .= '	}' ."\n";
		$scripttext .= ');	' ."\n";
	}

	if ($map->kmllayergpx != "")
	{
		$kml3 = 'GPX'.$context_suffix;
		$scripttext .= 'ymaps.geoXml.load(\''.$map->kmllayergpx.'\').then(' ."\n";
		$scripttext .= '	function('.$kml3.') {' ."\n";
		$scripttext .= '		map'.$mapDivSuffix.'.geoObjects.add('.$kml3.'.geoObjects);' ."\n";
		$scripttext .= '		if ('.$kml3.'.mapState) ' ."\n";
		$scripttext .= '		{' ."\n";
		$scripttext .= '			'.$kml3.'.mapState.applyToMap(map);' ."\n";
		$scripttext .= '		}' ."\n";
		$scripttext .= '	},' ."\n";
		$scripttext .= '	function(error) {' ."\n";
		$scripttext .= '    alert(\''.JText::_('COM_ZHYANDEXMAP_MAP_ERROR_GPX').'\' + error.message);' ."\n";
		$scripttext .= '	}' ."\n";
		$scripttext .= ');	' ."\n";
	}
	
	
	if ((isset($map->autoposition) && (int)$map->autoposition == 1))
	{
			$scripttext .= 'findMyPosition'.$mapDivSuffix.'("Map");' ."\n";
	}

	
	// Do open list if preset to yes
	if (isset($map->markerlistpos) && (int)$map->markerlistpos != 0) 
	{
		if ((int)$map->markerlistpos == 111
		  ||(int)$map->markerlistpos == 112
		  ||(int)$map->markerlistpos == 121
		  ) 
		{
			// We don't have to do in any case when table or external
			// because it displayed		
		}
		else
		{
			if ((int)$map->markerlistbuttontype == 0)
			{
				// Open because for non-button
				$scripttext .= '	var toShowDiv = document.getElementById("YMapsMarkerList'.$mapDivSuffix.'");' ."\n";
				$scripttext .= '	toShowDiv.style.display = "block";' ."\n";
			}
			else
			{
				switch ($map->markerlistbuttontype) 
				{
					case 0:
						$scripttext .= '	var toShowDiv = document.getElementById("YMapsMarkerList'.$mapDivSuffix.'");' ."\n";
						$scripttext .= '	toShowDiv.style.display = "block";' ."\n";
					break;
					case 1:
						$scripttext .= '';
					break;
					case 2:
						$scripttext .= '';
					break;
					case 11:
						//$scripttext .= '	var toShowDiv = document.getElementById("YMapsMarkerList'.$mapDivSuffix.'");' ."\n";
						//$scripttext .= '	toShowDiv.style.display = "block";' ."\n";
						$scripttext .= 'btnPlacemarkList'.$mapDivSuffix.'.events.fire("click", new ymaps.Event({'."\n";
						$scripttext .= ' target: btnPlacemarkList'.$mapDivSuffix.''."\n";
						$scripttext .= '}, true));' ."\n";
						
					break;
					case 12:
						//$scripttext .= '	var toShowDiv = document.getElementById("YMapsMarkerList'.$mapDivSuffix.'");' ."\n";
						//$scripttext .= '	toShowDiv.style.display = "block";' ."\n";
						$scripttext .= 'btnPlacemarkList'.$mapDivSuffix.'.events.fire("click", new ymaps.Event({'."\n";
						$scripttext .= ' target: btnPlacemarkList'.$mapDivSuffix.''."\n";
						$scripttext .= '}, true));' ."\n";
					break;
					default:
						$scripttext .= '';
					break;
				}
			}
								
		}	
	}
	// Open Placemark List Presets
	
	
	if ($zhymObjectManager != 0)
	{
		if ((isset($map->markergroupcontrol) && (int)$map->markergroupcontrol != 0))
		{
			$scripttext .= 'zhymObjMgr'.$mapDivSuffix.'.enableObjectGroupManagement();' ."\n";
			
			if ((isset($map->markergrouptype) && (int)$map->markergrouptype == 1))
			{
				$scripttext .= 'zhymObjMgr'.$mapDivSuffix.'.setObjectGroupManagementType("OnlyOneActive");' ."\n";
			}
			

			if ((isset($map->markergroupctlmarker) && (int)$map->markergroupctlmarker != 0))
			{
				$scripttext .= 'zhymObjMgr'.$mapDivSuffix.'.enablePlacemarkGroupManagement();' ."\n";
			}
			if (isset($map->markergroupctlpath) 
			&& (((int)$map->markergroupctlpath == 1) || ((int)$map->markergroupctlpath == 3)))
			{
				$scripttext .= 'zhymObjMgr'.$mapDivSuffix.'.enablePathGroupManagement();' ."\n";
			}
			
			if (isset($map->markergroupctlpath) 
			&& (((int)$map->markergroupctlpath == 2) || ((int)$map->markergroupctlpath == 3)))
			{
				$scripttext .= 'zhymObjMgr'.$mapDivSuffix.'.enablePathXGroupManagement();' ."\n";
			}

		}

		
		if ((isset($map->markercluster) && (int)$map->markercluster == 1))
		{
			$scripttext .= 'zhymObjMgr'.$mapDivSuffix.'.enablePlacemarkClusterization();' ."\n";
			if ((isset($map->markerclustergroup) && (int)$map->markerclustergroup == 1))
			{
				$scripttext .= 'zhymObjMgr'.$mapDivSuffix.'.enablePlacemarkClusterizationByGroup();' ."\n";
			}
			if ((isset($map->clusterdisableclickzoom) && (int)$map->clusterdisableclickzoom == 1))
			{
				$scripttext .= 'zhymObjMgr'.$mapDivSuffix.'.disablePlacemarkClusterizationClickZoom();' ."\n";
			}
	
		}
	
		// First map display (init)
		if ($ajaxLoadObjects != 0)
		{
			if ($ajaxLoadObjectType == 1)
			{
				$scripttext .= 'zhymObjMgr'.$mapDivSuffix.'.setPlacemarkLoadType(2);' ."\n";
				if ($ajaxLoadObjects == 1)
				{
					$scripttext .= 'zhymObjMgr'.$mapDivSuffix.'.GetPlacemarkAJAX("mootools");' ."\n";
				}
				else if ($ajaxLoadObjects == 2)
				{
					$scripttext .= 'zhymObjMgr'.$mapDivSuffix.'.GetPlacemarkAJAX("jquery");' ."\n";
				}
				$scripttext .= 'zhymObjMgr'.$mapDivSuffix.'.setPlacemarkLoadType(0);' ."\n";
				if ($ajaxLoadObjects == 1)
				{
					$scripttext .= 'zhymObjMgr'.$mapDivSuffix.'.GetPlacemarkAJAX("mootools");' ."\n";
				}
				else if ($ajaxLoadObjects == 2)
				{
					$scripttext .= 'zhymObjMgr'.$mapDivSuffix.'.GetPlacemarkAJAX("jquery");' ."\n";
				}
				$scripttext .= 'zhymObjMgr'.$mapDivSuffix.'.setPlacemarkLoadType('.$ajaxLoadObjectType.');' ."\n";
			}
			else
			{
				$scripttext .= 'zhymObjMgr'.$mapDivSuffix.'.setPlacemarkLoadType('.$ajaxLoadObjectType.');' ."\n";
				if ($ajaxLoadObjects == 1)
				{
					$scripttext .= 'zhymObjMgr'.$mapDivSuffix.'.GetPlacemarkAJAX("mootools");' ."\n";
				}
				else if ($ajaxLoadObjects == 2)
				{
					$scripttext .= 'zhymObjMgr'.$mapDivSuffix.'.GetPlacemarkAJAX("jquery");' ."\n";
				}
			}
			
		}
		else
		{
			$scripttext .= 'zhymObjMgr'.$mapDivSuffix.'.InitializeByGroupState();'."\n";
		}
	
	}
	
	$scripttext .= 'var toShowLoading = document.getElementById("YMapsLoading'.$mapDivSuffix.'");'."\n";
	$scripttext .= '  toShowLoading.style.display = \'none\';'."\n";
	
	$scripttext .= '}'."\n";
	// End initialize function
	
//
	$scripttext .= 'function PlacemarkByIDShow'.$mapDivSuffix.'(p_id, p_action, p_zoom) {' ."\n";
	if ($externalmarkerlink == 1)
	{
		$scripttext .= '  if (p_zoom != undefined && p_zoom != "")' ."\n";
		$scripttext .= '  {' ."\n";
		$scripttext .= '  	map'.$mapDivSuffix.'.setZoom(p_zoom);' ."\n";
		$scripttext .= '  }' ."\n";

		$scripttext .= '  if( allPlacemarkArray'.$mapDivSuffix.'[p_id] === undefined ) ' ."\n";
		$scripttext .= '  {' ."\n";
		$scripttext .= '  	alert("Unable to find placemark with ID = " + p_id);' ."\n";
		$scripttext .= '  }' ."\n";
		$scripttext .= '  else' ."\n";
		$scripttext .= '  {' ."\n";
		$scripttext .= '    cur_action = p_action.toLowerCase().split(",");' ."\n";
		$scripttext .= '    for (i = 0; i < cur_action.length; i++) {' ."\n";
		$scripttext .= '      if (cur_action[i] == "click")' ."\n";
		$scripttext .= '      {' ."\n";
		$scripttext .= '		allPlacemarkArray'.$mapDivSuffix.'[p_id].markerobject.events.fire("click", new ymaps.Event({'."\n";
		$scripttext .= ' 			target: allPlacemarkArray'.$mapDivSuffix.'[p_id].markerobject'."\n";
		$scripttext .= '		}, true));' ."\n";
		$scripttext .= '      }' ."\n";
		$scripttext .= '      else if (cur_action[i] == "center")' ."\n";
		$scripttext .= '      {' ."\n";
		$scripttext .= '  	    map'.$mapDivSuffix.'.setCenter(allPlacemarkArray'.$mapDivSuffix.'[p_id].latlngobject);' ."\n";
		$scripttext .= '      }' ."\n";
		$scripttext .= '    }' ."\n";
		$scripttext .= '  }' ."\n";
	}
	else
	{
		$scripttext .= '  	alert("This feature is supported only when you enable it in map menu item property!");' ."\n";
	}
	$scripttext .= '}' ."\n";
	

	if ($externalmarkerlink == 1)
	{
		$scripttext .= 'function PlacemarkByID'.$mapDivSuffix.'(p_id, p_lat, p_lng, p_obj, p_ll) {' ."\n";
		$scripttext .= 'this.id = p_id;' ."\n";
		$scripttext .= 'this.lat = p_lat;' ."\n";
		$scripttext .= 'this.lng = p_lng;' ."\n";
		$scripttext .= 'this.markerobject = p_obj;' ."\n";
		$scripttext .= 'this.latlngobject = p_ll;' ."\n";
		$scripttext .= '}' ."\n";
		
		$scripttext .= 'function PlacemarkByIDAdd'.$mapDivSuffix.'(p_id, p_lat, p_lng, p_obj, p_ll) {' ."\n";
		$scripttext .= '	allPlacemarkArray'.$mapDivSuffix.'[p_id] = new PlacemarkByID'.$mapDivSuffix.'(p_id, p_lat, p_lng, p_obj, p_ll);' ."\n";
		$scripttext .= '}' ."\n";
	}
	
//
//
if ($compatiblemode == 1)
{
	// for IE under 8
	$scripttext .= '		function myHasClass(ele,cls) {' ."\n";
	$scripttext .= '		    var reg = new RegExp(\'(\\s|^)\'+cls+\'(\\s|$)\');' ."\n";
	$scripttext .= '			return ele.className.match(reg);' ."\n";
	$scripttext .= '		}' ."\n";

	$scripttext .= '		function myAddClass(ele,cls) {' ."\n";
	$scripttext .= '			if (!myHasClass(ele,cls)) {' ."\n";
	$scripttext .= '			   if (ele.className == "")' ."\n";
	$scripttext .= '		   	   {' ."\n";
	$scripttext .= '			    ele.className += cls;' ."\n";
	$scripttext .= '			   }' ."\n";
	$scripttext .= '			   else' ."\n";
	$scripttext .= '			   {' ."\n";
	$scripttext .= '			    ele.className += " "+cls;' ."\n";
	$scripttext .= '			   }' ."\n";
	$scripttext .= '		    }' ."\n";
	$scripttext .= '		 }' ."\n";
			  
	$scripttext .= '		function myRemoveClass(ele,cls) {' ."\n";
	$scripttext .= '			if (myHasClass(ele,cls)) {' ."\n";
	$scripttext .= '				var reg = new RegExp(\'(\\s|^)\'+cls+\'(\\s|$)\');' ."\n";
	$scripttext .= '				ele.className=ele.className.replace(reg,\' \');' ."\n";
	$scripttext .= '				ele.className=ele.className.replace(/\s+/g,\' \');' ."\n";
	$scripttext .= '				ele.className=ele.className.replace(/^\s|\s$/,\'\');' ."\n";
	$scripttext .= '				if (ele.className == " ")' ."\n";
	$scripttext .= '				{' ."\n";
	$scripttext .= '				  ele.className ="";' ."\n";
	$scripttext .= '				}' ."\n";
	$scripttext .= '			}' ."\n";
	$scripttext .= '		}' ."\n";
					 
	$scripttext .= '	    function myToggleClass(elem, cls){' ."\n";
	$scripttext .= '	        if(myHasClass(elem, cls)){' ."\n";
	$scripttext .= '	            myRemoveClass(elem, cls);' ."\n";
	$scripttext .= '	        } else  {' ."\n";
	$scripttext .= '	            myAddClass(elem, cls);' ."\n";
	$scripttext .= '	        }' ."\n";
	$scripttext .= '	    }' ."\n";
}
//
//


	if (isset($map->markergroupcontrol) && (int)$map->markergroupcontrol != 0) 
	{
		$scripttext .= 'function callToggleGroup'.$mapDivSuffix.'(groupid){   ' ."\n";
		$scripttext .= '  zhymObjMgr'.$mapDivSuffix.'.GroupStateToggle(groupid);' ."\n";
		$scripttext .= '}'."\n";
		
		$scripttext .= 'function callShowAllGroup'.$mapDivSuffix.'(){   ' ."\n";
		$scripttext .= '  zhymObjMgr'.$mapDivSuffix.'.GroupStateShowAll();' ."\n";
		$scripttext .= '}'."\n";

		$scripttext .= 'function callHideAllGroup'.$mapDivSuffix.'(){   ' ."\n";
		$scripttext .= '  zhymObjMgr'.$mapDivSuffix.'.GroupStateHideAll();' ."\n";
		$scripttext .= '}'."\n";
	}

	
	
	// Geo Position - Begin
	if ((isset($map->autoposition) && (int)$map->autoposition == 1)
	 || (isset($map->autopositioncontrol) && (int)$map->autopositioncontrol != 0))
	{
	    $scripttext .= 'function findMyPosition'.$mapDivSuffix.'(AutoPosition) {' ."\n";
	    if ($mapVersion == "2.0")
	    {
			$scripttext .= '     if (AutoPosition == "Button")' ."\n";
			$scripttext .= '     {' ."\n";
			$scripttext .= '        if (ymaps.geolocation) ' ."\n";
			$scripttext .= '        {' ."\n";
			$scripttext .= '        	p_center = [ymaps.geolocation.longitude, ymaps.geolocation.latitude];' ."\n";
			if (isset($map->findroute) && (int)$map->findroute == 1) 
			{
				$scripttext .= '    		getMyMapRoute'.$mapDivSuffix.'(p_center);' ."\n";
			}
			else
			{
				$scripttext .= '    		map'.$mapDivSuffix.'.setCenter(p_center);' ."\n";
			}
			//$scripttext .= '        	alert("Find");';
			$scripttext .= '        } ' ."\n";
			$scripttext .= '        else ' ."\n";
			$scripttext .= '        {' ."\n";
			//$scripttext .= '        	alert("Not found");';
			 $scripttext .= '    	}' ."\n";
			$scripttext .= '     }' ."\n";
			$scripttext .= '     else' ."\n";
			$scripttext .= '     {' ."\n";
			$scripttext .= '        if (ymaps.geolocation) ' ."\n";
			$scripttext .= '        {' ."\n";
			$scripttext .= '        	p_center = [ymaps.geolocation.longitude, ymaps.geolocation.latitude];' ."\n";
			$scripttext .= '    		map'.$mapDivSuffix.'.setCenter(p_center);' ."\n";
			//$scripttext .= '        	alert("Find");';
			$scripttext .= '        } ' ."\n";
			$scripttext .= '        else ' ."\n";
			$scripttext .= '        {' ."\n";
			//$scripttext .= '        	alert("Not found");';
			$scripttext .= '    	}' ."\n";
			$scripttext .= '     }' ."\n";
	    }
	    else
	    {
			$scripttext .= '     if (AutoPosition == "Button")' ."\n";
			$scripttext .= '     {' ."\n";
			$scripttext .= '        ymaps.geolocation.get({' ."\n";
                        $scripttext .= '            provider: \'yandex\',' ."\n";
                        $scripttext .= '            mapStateAutoApply: false' ."\n";
                        $scripttext .= '        }).then(function (res) ' ."\n";
			$scripttext .= '        {' ."\n";
                        $scripttext .= '                var mypos = res.geoObjects.get(0);' ."\n";      
                        $scripttext .= '        	p_center = mypos.geometry.getCoordinates();' ."\n";
			if (isset($map->findroute) && (int)$map->findroute == 1) 
			{
				$scripttext .= '    		getMyMapRoute'.$mapDivSuffix.'(p_center);' ."\n";
			}
			else
			{
				$scripttext .= '    		map'.$mapDivSuffix.'.setCenter(p_center);' ."\n";
			}
			//$scripttext .= '        	alert("Found");';
			$scripttext .= '        });' ."\n";
			$scripttext .= '     }' ."\n";
			$scripttext .= '     else' ."\n";
			$scripttext .= '     {' ."\n";
			$scripttext .= '        ymaps.geolocation.get({' ."\n";
                        $scripttext .= '            provider: \'yandex\',' ."\n";
                        $scripttext .= '            mapStateAutoApply: false' ."\n";
                        $scripttext .= '        }).then(function (res) ' ."\n";
			$scripttext .= '        {' ."\n";
                        $scripttext .= '                var mypos = res.geoObjects.get(0);' ."\n";      
                        $scripttext .= '        	p_center = mypos.geometry.getCoordinates();' ."\n";
			$scripttext .= '    		map'.$mapDivSuffix.'.setCenter(p_center);' ."\n";
			//$scripttext .= '        	alert("Found");';
			$scripttext .= '        });' ."\n";
			$scripttext .= '     }' ."\n";
	    }
	    $scripttext .= '}' ."\n";		

	}
	

// Find option Begin
	if (isset($map->findcontrol) && (int)$map->findcontrol == 1) 
	{
        $scripttext .= 'function showAddressByGeocoding'.$mapDivSuffix.'(value) {' ."\n";
        // Delete Previous Result
		$scripttext .= '  if (geoResult'.$mapDivSuffix.')' ."\n";
		$scripttext .= '  {' ."\n";
        $scripttext .= '    map'.$mapDivSuffix.'.geoObjects.remove(geoResult'.$mapDivSuffix.');' ."\n";
		$scripttext .= '  }' ."\n";

        // Geocoding
		$scripttext .= '   if ((map'.$mapDivSuffix.'.getType() == "yandex#publicMap") || (map'.$mapDivSuffix.'.getType() == "yandex#publicMapHybrid"))';
		$scripttext .= '   {';
        $scripttext .= '     var geocoderOpts = {results: 1, boundedBy: map'.$mapDivSuffix.'.getBounds(), provider:"yandex#publicMap"};' ."\n";
		$scripttext .= '   }';
		$scripttext .= '   else';
		$scripttext .= '   {';
        $scripttext .= '     var geocoderOpts = {results: 1, boundedBy: map'.$mapDivSuffix.'.getBounds()};' ."\n";
		$scripttext .= '   }';
        $scripttext .= '   ymaps.geocode(value, geocoderOpts).then(function (res) {' ."\n";
        // if find then add to map
        // set center map
		$scripttext .= '     cnt = res.geoObjects.getLength();'."\n";
        $scripttext .= '        if (cnt > 0) ' ."\n";
		$scripttext .= '		{' ."\n";
        $scripttext .= '            geoResult'.$mapDivSuffix.' = res.geoObjects.get(0);' ."\n";
		$scripttext .= '     		geoResult'.$mapDivSuffix.'.properties.set(\'balloonContentHeader\', \'\');'."\n";
		$scripttext .= '    		geoResult'.$mapDivSuffix.'.properties.set(\'balloonContentBody\', \'\');'."\n";
        $scripttext .= '            map'.$mapDivSuffix.'.geoObjects.add(geoResult'.$mapDivSuffix.');' ."\n";
        $scripttext .= '            map'.$mapDivSuffix.'.setCenter(geoResult'.$mapDivSuffix.'.geometry.getCoordinates());' ."\n";
		// add route
		if (isset($map->findroute) && (int)$map->findroute == 1) 
		{
			$scripttext .= '            getMyMapRoute'.$mapDivSuffix.'(geoResult'.$mapDivSuffix.'.geometry.getCoordinates()); '."\n";
		}
		// end add route
        $scripttext .= '        }' ."\n";
		$scripttext .= '		else ' ."\n";
		$scripttext .= '		{' ."\n";
        $scripttext .= '            alert("'.JText::_('COM_ZHYANDEXMAP_MAP_ERROR_FIND_GEOCODING').'");' ."\n";
        $scripttext .= '        }' ."\n";
        $scripttext .= '    },' ."\n";

        // Failure geocoding
        $scripttext .= '    function (err) {' ."\n";
        $scripttext .= '        alert("'.JText::_('COM_ZHYANDEXMAP_MAP_ERROR_FIND_GEOCODING_ERROR').'" + err.message);' ."\n";
        $scripttext .= '    });' ."\n";
        $scripttext .= '};' ."\n";
	}
// Find option End


// Add Map Route
		if (isset($map->findroute) && (int)$map->findroute == 1) 
		{
			$scripttext .= 'function getMyMapRoute'.$mapDivSuffix.'(curposition) {'."\n";
		
			$scripttext .= '  if (geoRoute'.$mapDivSuffix.')' ."\n";
			$scripttext .= '  {' ."\n";
			$scripttext .= '	map'.$mapDivSuffix.'.geoObjects.remove(geoRoute'.$mapDivSuffix.');' ."\n";
			$scripttext .= '  }' ."\n";
			
			$scripttext .= '  ymaps.route([curposition, mapcenter'.$mapDivSuffix.'],'."\n";
			$scripttext .= '       { mapStateAutoApply: true }'."\n";
			$scripttext .= '  ).then('."\n";
			$scripttext .= '  function(route){'."\n";
			$scripttext .= '    geoRoute'.$mapDivSuffix.' = route;'."\n";
			$scripttext .= '    var points = route.getWayPoints();'."\n";
            $scripttext .= '    points.options.set(\'preset\', \'twirl#blueStretchyIcon\');'."\n";
			$scripttext .= '    points.get(0).properties.set(\'iconContent\', \''.JText::_('COM_ZHYANDEXMAP_MAP_FIND_GEOCODING_START_POINT').'\');'."\n";
			$scripttext .= '    points.get(1).properties.set(\'iconContent\', \''.JText::_('COM_ZHYANDEXMAP_MAP_FIND_GEOCODING_END_POINT').'\');'."\n";
			// Clear for do not open balloon
			$scripttext .= '    points.get(0).properties.set(\'balloonContentHeader\', \'\');'."\n";
			$scripttext .= '    points.get(0).properties.set(\'balloonContentBody\', \'\');'."\n";
			$scripttext .= '    points.get(1).properties.set(\'balloonContentHeader\', \'\');'."\n";
			$scripttext .= '    points.get(1).properties.set(\'balloonContentBody\', \'\');'."\n";
			
			$scripttext .= '     map'.$mapDivSuffix.'.geoObjects.add(geoRoute'.$mapDivSuffix.');'."\n";
			$scripttext .= '  }, '."\n";
			$scripttext .= '  function(err){'."\n";
			$scripttext .= '     alert(\''.JText::_('COM_ZHYANDEXMAP_MAP_ERROR_GEOCODING').'\' + err.message);'."\n";
			$scripttext .= '  }'."\n";
			$scripttext .= ');'."\n";
			$scripttext .= '}'."\n";
		}


// Toggle for Insert Markers
if (isset($map->usermarkers) && (int)$map->usermarkers == 1) 
{
    if ($allowUserMarker == 1)
    {
			$scripttext .= 'function showonlyone(thename, theid) {'."\n";
			$scripttext .= '  var toHide;'."\n";
			$scripttext .= '  var toShow;'."\n";
			$scripttext .= '  var xPlacemarkA = document.getElementById("bodyInsertPlacemarkA"+theid);'."\n";
			if (isset($map->showbelong) && (int)$map->showbelong == 1)
			{
				$scripttext .= '  var xPlacemarkGrpA = document.getElementById("bodyInsertPlacemarkGrpA"+theid);'."\n";
			}
			if (isset($map->usercontact) && (int)$map->usercontact == 1)
			{
				$scripttext .= '  var xContactA = document.getElementById("bodyInsertContactA"+theid);'."\n";
				$scripttext .= '  var xContactAdrA = document.getElementById("bodyInsertContactAdrA"+theid);'."\n";
			}
			$scripttext .= '  if (thename == \'Contact\')'."\n";
			$scripttext .= '  {'."\n";
			$scripttext .= '    toHide = document.getElementById("bodyInsertPlacemark"+theid);'."\n";
			$scripttext .= '    toHide.style.display = \'none\';'."\n";
			$scripttext .= '    xPlacemarkA.innerHTML = \'<img src="'.$imgpathUtils.'expand.png">'.JText::_( 'COM_ZHYANDEXMAP_MAP_USER_BASIC_PROPERTIES' ).'\';'."\n";
			if (isset($map->usercontact) && (int)$map->usercontact == 1)
			{
				$scripttext .= '    toHide = document.getElementById("bodyInsertContactAdr"+theid);'."\n";
				$scripttext .= '    toHide.style.display = \'none\';'."\n";
				$scripttext .= '    xContactAdrA.innerHTML = \'<img src="'.$imgpathUtils.'expand.png">'.JText::_( 'COM_ZHYANDEXMAP_MAP_USER_CONTACT_ADDRESS_PROPERTIES' ).'\';'."\n";
				$scripttext .= '    toShow = document.getElementById("bodyInsertContact"+theid);'."\n";
				$scripttext .= '    toShow.style.display = \'block\';'."\n";
				$scripttext .= '    xContactA.innerHTML = \'<img src="'.$imgpathUtils.'collapse.png">'.JText::_( 'COM_ZHYANDEXMAP_MAP_USER_CONTACT_PROPERTIES' ).'\';'."\n";
			}
			if (isset($map->showbelong) && (int)$map->showbelong == 1)
			{
				$scripttext .= '    toHide = document.getElementById("bodyInsertPlacemarkGrp"+theid);'."\n";
				$scripttext .= '    toHide.style.display = \'none\';'."\n";
				$scripttext .= '    xPlacemarkGrpA.innerHTML = \'<img src="'.$imgpathUtils.'expand.png">'.JText::_( 'COM_ZHYANDEXMAP_MAP_USER_BASIC_GROUP_PROPERTIES' ).'\';'."\n";
			}
			$scripttext .= '  }'."\n";
			$scripttext .= '  else if (thename == \'Placemark\')'."\n";
			$scripttext .= '  {'."\n";
			$scripttext .= '    toShow = document.getElementById("bodyInsertPlacemark"+theid);'."\n";
			$scripttext .= '    toShow.style.display = \'block\';'."\n";
			$scripttext .= '    xPlacemarkA.innerHTML = \'<img src="'.$imgpathUtils.'collapse.png">'.JText::_( 'COM_ZHYANDEXMAP_MAP_USER_BASIC_PROPERTIES' ).'\';'."\n";
			if (isset($map->usercontact) && (int)$map->usercontact == 1)
			{
				$scripttext .= '    toHide = document.getElementById("bodyInsertContact"+theid);'."\n";
				$scripttext .= '    toHide.style.display = \'none\';'."\n";
				$scripttext .= '    xContactAdrA.innerHTML = \'<img src="'.$imgpathUtils.'expand.png">'.JText::_( 'COM_ZHYANDEXMAP_MAP_USER_CONTACT_ADDRESS_PROPERTIES' ).'\';'."\n";
				$scripttext .= '    toHide = document.getElementById("bodyInsertContactAdr"+theid);'."\n";
				$scripttext .= '    toHide.style.display = \'none\';'."\n";
				$scripttext .= '    xContactA.innerHTML = \'<img src="'.$imgpathUtils.'expand.png">'.JText::_( 'COM_ZHYANDEXMAP_MAP_USER_CONTACT_PROPERTIES' ).'\';'."\n";
			}
			if (isset($map->showbelong) && (int)$map->showbelong == 1)
			{
				$scripttext .= '    toHide = document.getElementById("bodyInsertPlacemarkGrp"+theid);'."\n";
				$scripttext .= '    toHide.style.display = \'none\';'."\n";
				$scripttext .= '    xPlacemarkGrpA.innerHTML = \'<img src="'.$imgpathUtils.'expand.png">'.JText::_( 'COM_ZHYANDEXMAP_MAP_USER_BASIC_GROUP_PROPERTIES' ).'\';'."\n";
			}
			$scripttext .= '  }'."\n";
			$scripttext .= '  else if (thename == \'PlacemarkGroup\')'."\n";
			$scripttext .= '  {'."\n";
			$scripttext .= '    toHide = document.getElementById("bodyInsertPlacemark"+theid);'."\n";
			$scripttext .= '    toHide.style.display = \'none\';'."\n";
			$scripttext .= '    xPlacemarkA.innerHTML = \'<img src="'.$imgpathUtils.'expand.png">'.JText::_( 'COM_ZHYANDEXMAP_MAP_USER_BASIC_PROPERTIES' ).'\';'."\n";
			if (isset($map->usercontact) && (int)$map->usercontact == 1)
			{
				$scripttext .= '    toHide = document.getElementById("bodyInsertContact"+theid);'."\n";
				$scripttext .= '    toHide.style.display = \'none\';'."\n";
				$scripttext .= '    xContactA.innerHTML = \'<img src="'.$imgpathUtils.'expand.png">'.JText::_( 'COM_ZHYANDEXMAP_MAP_USER_CONTACT_PROPERTIES' ).'\';'."\n";
				$scripttext .= '    toHide = document.getElementById("bodyInsertContactAdr"+theid);'."\n";
				$scripttext .= '    toHide.style.display = \'none\';'."\n";
				$scripttext .= '    xContactAdrA.innerHTML = \'<img src="'.$imgpathUtils.'expand.png">'.JText::_( 'COM_ZHYANDEXMAP_MAP_USER_CONTACT_ADDRESS_PROPERTIES' ).'\';'."\n";
			}
			if (isset($map->showbelong) && (int)$map->showbelong == 1)
			{
				$scripttext .= '    toShow = document.getElementById("bodyInsertPlacemarkGrp"+theid);'."\n";
				$scripttext .= '    toShow.style.display = \'block\';'."\n";
				$scripttext .= '    xPlacemarkGrpA.innerHTML = \'<img src="'.$imgpathUtils.'collapse.png">'.JText::_( 'COM_ZHYANDEXMAP_MAP_USER_BASIC_GROUP_PROPERTIES' ).'\';'."\n";
			}
			$scripttext .= '  }'."\n";
			$scripttext .= '  else if (thename == \'ContactAddress\')'."\n";
			$scripttext .= '  {'."\n";
			$scripttext .= '    toHide = document.getElementById("bodyInsertPlacemark"+theid);'."\n";
			$scripttext .= '    toHide.style.display = \'none\';'."\n";
			$scripttext .= '    xPlacemarkA.innerHTML = \'<img src="'.$imgpathUtils.'expand.png">'.JText::_( 'COM_ZHYANDEXMAP_MAP_USER_BASIC_PROPERTIES' ).'\';'."\n";
			if (isset($map->usercontact) && (int)$map->usercontact == 1)
			{
				$scripttext .= '    toHide = document.getElementById("bodyInsertContact"+theid);'."\n";
				$scripttext .= '    toHide.style.display = \'none\';'."\n";
				$scripttext .= '    toShow = document.getElementById("bodyInsertContactAdr"+theid);'."\n";
				$scripttext .= '    toShow.style.display = \'block\';'."\n";
			}
			if (isset($map->showbelong) && (int)$map->showbelong == 1)
			{
				$scripttext .= '    toHide = document.getElementById("bodyInsertPlacemarkGrp"+theid);'."\n";
				$scripttext .= '    toHide.style.display = \'none\';'."\n";
				$scripttext .= '    xPlacemarkGrpA.innerHTML = \'<img src="'.$imgpathUtils.'expand.png">'.JText::_( 'COM_ZHYANDEXMAP_MAP_USER_BASIC_GROUP_PROPERTIES' ).'\';'."\n";
			}
			if (isset($map->usercontact) && (int)$map->usercontact == 1)
			{
				$scripttext .= '    xContactA.innerHTML = \'<img src="'.$imgpathUtils.'expand.png">'.JText::_( 'COM_ZHYANDEXMAP_MAP_USER_CONTACT_PROPERTIES' ).'\';'."\n";
				$scripttext .= '    xContactAdrA.innerHTML = \'<img src="'.$imgpathUtils.'collapse.png">'.JText::_( 'COM_ZHYANDEXMAP_MAP_USER_CONTACT_ADDRESS_PROPERTIES' ).'\';'."\n";
			}
			$scripttext .= '  }'."\n";
			$scripttext .= '}'."\n";
    }   
}



$scripttextEnd .= '/*]]>*/</script>' ."\n";
// Script end

$document->addScript($scriptlink . $loadmodules);

if (isset($MapXdoLoad) && ((int)$MapXdoLoad == 0))
{
	if (isset($useObjectStructure) && (int)$useObjectStructure == 1)
	{
		$scripttextFull = $scripttextBegin . $scripttext. $scripttextEnd;
		$this->scripttext = $scripttextFull;
		$this->scripthead = $scripthead;
	}
	else
	{
	}
}
else
{
	$scripttextFull = $scripttextBegin . $scripttext. $scripttextEnd;
	echo $scripttextFull;
}

}
// end of main part


