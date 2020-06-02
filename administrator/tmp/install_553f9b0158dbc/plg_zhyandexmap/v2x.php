<?php
/*------------------------------------------------------------------------
# plg_zhyandexmap - Zh YandexMap Plugin
# ------------------------------------------------------------------------
# author    Dmitry Zhuk
# copyright Copyright (C) 2011 zhuk.cc. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
# Websites: http://zhuk.cc
# Technical Support Forum: http://forum.zhuk.cc/
-------------------------------------------------------------------------*/
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

	// Change translation language and load translation
	if (isset($map->lang) && $map->lang != "")
	{
		$currentLanguage = JFactory::getLanguage();
		$currentLangTag = $currentLanguage->getTag();
		$currentLanguage->setLanguage($map->lang); 		
		JPlugin::loadLanguage();
		$currentLanguage->setLanguage($currentLangTag); 		
	}


	$fullWidth = 0;
	$fullHeight = 0;

	// Init vars
	$loadmodules='';
	$loadmodules_pmap = 0;
	$scripthead ='';
	$scripttext = '';

	$divmapheader ="";
	$divmapfooter ="";
	
	$credits ='';
	
	$document	= JFactory::getDocument();

	
	// Init vars

	if ($map->headerhtml != "")
	{
	        $divmapheader .= '<div id="YMapInfoHeader">'.$map->headerhtml.'</div>';
	        if (isset($map->headersep) && (int)$map->headersep == 1) 
	        {
	            $divmapheader .= '<hr id="mapHeaderLine" />';
	        }
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
	
	if (isset($map->findcontrol) && (int)$map->findcontrol == 1) 
	{
		switch ((int)$map->findpos) 
		{
			
			case 0:
				$divmapheader .= '<div id="YMapFindAddress">'."\n";
				$divmapheader .= '<form action="#" onsubmit="showAddressByGeocoding'.$currentArticleId.'(this.findAddressField'.$currentArticleId.'.value);return false;">'."\n";
				//$divmapheader .= '<p>'."\n";
				$divmapheader .= '<input id="findAddressField'.$currentArticleId.'" type="text" value=""';
				if (isset($map->findwidth) && (int)$map->findwidth != 0)
				{
					$divmapheader .= ' size="'.(int)$map->findwidth.'"';
				}
				$divmapheader .=' />';
				$divmapheader .= '<input id="findAddressButton'.$currentArticleId.'" type="submit" value="';
				if (isset($map->findroute) && (int)$map->findroute == 1) 
				{
					$divmapheader .= JText::_( 'PLG_ZHYANDEXMAP_MAP_DOFINDBUTTONROUTE' );
				}
				else
				{
					$divmapheader .= JText::_( 'PLG_ZHYANDEXMAP_MAP_DOFINDBUTTON' );
				}
				$divmapheader .= '" />'."\n";
				//$divmapheader .= '</p>'."\n";
				$divmapheader .= '</form>'."\n";
				$divmapheader .= '</div>'."\n";
			break;
			case 101:
				$divmapheader .= '<div id="YMapFindAddress">'."\n";
				$divmapheader .= '<form action="#" onsubmit="showAddressByGeocoding'.$currentArticleId.'(this.findAddressField'.$currentArticleId.'.value);return false;">'."\n";
				//$divmapheader .= '<p>'."\n";
				$divmapheader .= '<input id="findAddressField'.$currentArticleId.'" type="text" value=""';
				if (isset($map->findwidth) && (int)$map->findwidth != 0)
				{
					$divmapheader .= ' size="'.(int)$map->findwidth.'"';
				}
				$divmapheader .=' />';
				$divmapheader .= '<input id="findAddressButton'.$currentArticleId.'" type="submit" value="';
				if (isset($map->findroute) && (int)$map->findroute == 1) 
				{
					$divmapheader .= JText::_( 'PLG_ZHYANDEXMAP_MAP_DOFINDBUTTONROUTE' );
				}
				else
				{
					$divmapheader .= JText::_( 'PLG_ZHYANDEXMAP_MAP_DOFINDBUTTON' );
				}
				$divmapheader .= '" />'."\n";
				//$divmapheader .= '</p>'."\n";
				$divmapheader .= '</form>'."\n";
				$divmapheader .= '</div>'."\n";
			break;
			case 102:
				$divmapfooter .= '<div id="YMapFindAddress">'."\n";
				$divmapfooter .= '<form action="#" onsubmit="showAddressByGeocoding'.$currentArticleId.'(this.findAddressField'.$currentArticleId.'.value);return false;">'."\n";
				//$divmapfooter .= '<p>'."\n";
				$divmapfooter .= '<input id="findAddressField'.$currentArticleId.'" type="text" value=""';
				if (isset($map->findwidth) && (int)$map->findwidth != 0)
				{
					$divmapfooter .= ' size="'.(int)$map->findwidth.'"';
				}

				$divmapfooter .=' />';
				$divmapfooter .= '<input id="findAddressButton'.$currentArticleId.'" type="submit" value="';
				if (isset($map->findroute) && (int)$map->findroute == 1) 
				{
					$divmapfooter .= JText::_( 'PLG_ZHYANDEXMAP_MAP_DOFINDBUTTONROUTE' );
				}
				else
				{
					$divmapfooter .= JText::_( 'PLG_ZHYANDEXMAP_MAP_DOFINDBUTTON' );
				}
				$divmapfooter .= '" />'."\n";
				//$divmapfooter .= '</p>'."\n";
				$divmapfooter .= '</form>'."\n";
				$divmapfooter .= '</div>'."\n";
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
			$divmapheader .='<div id="geoLocation'.$currentArticleId.'">';
			$divmapheader .= '  <button id="geoLocationButton'.$currentArticleId.'" onclick="findMyPosition'.$currentArticleId.'(\'Button\');return false;">';

			switch ((int)$map->autopositionbutton) 
			{
				case 1:
					$divmapheader .= '<img src="'.$imgpathUtils.'geolocation.png" alt="'.JText::_('PLG_ZHYANDEXMAP_MAP_AUTOPOSITIONBUTTON').'" style="vertical-align: middle">';
				break;
				case 2:
					$divmapheader .= JText::_('PLG_ZHYANDEXMAP_MAP_AUTOPOSITIONBUTTON');
				break;
				case 3:
					$divmapheader .= '<img src="'.$imgpathUtils.'geolocation.png" alt="'.JText::_('PLG_ZHYANDEXMAP_MAP_AUTOPOSITIONBUTTON').'" style="vertical-align: middle">';
					$divmapheader .= JText::_('PLG_ZHYANDEXMAP_MAP_AUTOPOSITIONBUTTON');
				break;
				default:
					$divmapheader .= '<img src="'.$imgpathUtils.'geolocation.png" alt="'.JText::_('PLG_ZHYANDEXMAP_MAP_AUTOPOSITIONBUTTON').'" style="vertical-align: middle">';
				break;
			}
			
			$divmapheader .= '</button>';
			$divmapheader .='</div>'."\n";
		break;
		case 101:
			$divmapheader .='<div id="geoLocation'.$currentArticleId.'">';
			$divmapheader .= '  <button id="geoLocationButton'.$currentArticleId.'" onclick="findMyPosition'.$currentArticleId.'(\'Button\');return false;">';

			switch ((int)$map->autopositionbutton) 
			{
				case 1:
					$divmapheader .= '<img src="'.$imgpathUtils.'geolocation.png" alt="'.JText::_('PLG_ZHYANDEXMAP_MAP_AUTOPOSITIONBUTTON').'" style="vertical-align: middle">';
				break;
				case 2:
					$divmapheader .= JText::_('PLG_ZHYANDEXMAP_MAP_AUTOPOSITIONBUTTON');
				break;
				case 3:
					$divmapheader .= '<img src="'.$imgpathUtils.'geolocation.png" alt="'.JText::_('PLG_ZHYANDEXMAP_MAP_AUTOPOSITIONBUTTON').'" style="vertical-align: middle">';
					$divmapheader .= JText::_('PLG_ZHYANDEXMAP_MAP_AUTOPOSITIONBUTTON');
				break;
				default:
					$divmapheader .= '<img src="'.$imgpathUtils.'geolocation.png" alt="'.JText::_('PLG_ZHYANDEXMAP_MAP_AUTOPOSITIONBUTTON').'" style="vertical-align: middle">';
				break;
			}
			
			$divmapheader .= '</button>';
			$divmapheader .='</div>'."\n";
		break;
		case 102:
			$divmapfooter .='<div id="geoLocation'.$currentArticleId.'">';
			$divmapfooter .= '  <button id="geoLocationButton'.$currentArticleId.'" onclick="findMyPosition'.$currentArticleId.'(\'Button\');return false;">';

			switch ((int)$map->autopositionbutton) 
			{
				case 1:
					$divmapfooter .= '<img src="'.$imgpathUtils.'geolocation.png" alt="'.JText::_('PLG_ZHYANDEXMAP_MAP_AUTOPOSITIONBUTTON').'" style="vertical-align: middle">';
				break;
				case 2:
					$divmapfooter .= JText::_('PLG_ZHYANDEXMAP_MAP_AUTOPOSITIONBUTTON');
				break;
				case 3:
					$divmapfooter .= '<img src="'.$imgpathUtils.'geolocation.png" alt="'.JText::_('PLG_ZHYANDEXMAP_MAP_AUTOPOSITIONBUTTON').'" style="vertical-align: middle">';
					$divmapfooter .= JText::_('PLG_ZHYANDEXMAP_MAP_AUTOPOSITIONBUTTON');
				break;
				default:
					$divmapfooter .= '<img src="'.$imgpathUtils.'geolocation.png" alt="'.JText::_('PLG_ZHYANDEXMAP_MAP_AUTOPOSITIONBUTTON').'" style="vertical-align: middle">';
				break;
			}
			
			$divmapfooter .= '</button>';
			$divmapfooter .='</div>'."\n";
		break;
		default:
		break;
	}

	
}
	
	if ($map->footerhtml != "")
	{
        	if (isset($map->footersep) && (int)$map->footersep == 1) 
	        {
        	    $divmapfooter .= '<hr id="mapFooterLine" />';
	        }
	       $divmapfooter .= '<div id="YMapInfoFooter">'.$map->footerhtml.'</div>';
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


	$divwrapmapstyle = '';
	
	if ($fullWidth == 1)
	{
		$divwrapmapstyle .= 'width:100%;';
	}
	if ($fullHeight == 1)
	{
		$divwrapmapstyle .= 'height:100%;';
	}
	if ($divwrapmapstyle != "")
	{
		$divwrapmapstyle = 'style="'.$divwrapmapstyle.'"';
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
	
	
	// adding markerlist (div)
	if (isset($map->markerlistpos) && (int)$map->markerlistpos != 0) 
	{

		if ($this->compatiblemodersf == 0)
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
				$divMarkerlistHeight = $map->height;
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
			$markerlisttag = '<ul id="YMapsMarkerUL'.$currentArticleId.'" class="zhym-ul-'.$markerlistcssstyle.'"></ul>';
		}
		else 
		{
			$markerlisttag =  '<table id="YMapsMarkerTABLE'.$currentArticleId.'" class="zhym-ul-table-'.$markerlistcssstyle.'" ';
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
			$markerlisttag .= '<tbody id="YMapsMarkerTABLEBODY'.$currentArticleId.'" class="zhym-ul-tablebody-'.$markerlistcssstyle.'">';
			$markerlisttag .= '</tbody>';
			$markerlisttag .= '</table>';
		}
		
		switch ((int)$map->markerlistpos) 
		{
			case 0:
				// None
			break;
			case 1:
				$scripthead .= '<div id="YMMapWrapper'.$currentArticleId.'" '.$divwrapmapstyle.' class="zhym-wrap-'.$markerlistcssstyle.'">';
				$scripthead .='<div id="YMapsMarkerList'.$currentArticleId.'" class="zhym-list-'.$markerlistcssstyle.'" style="'.$markerlistAddStyle.' display: none; float: left; padding: 0; margin: 5px; width:'.$divMarkerlistWidth.'; height:'.$divMarkerlistHeight.';">'.$markerlisttag.'</div>';
			break;
			case 2:
				$scripthead .= '<div id="YMMapWrapper'.$currentArticleId.'" '.$divwrapmapstyle.' class="zhym-wrap-'.$markerlistcssstyle.'">';
				$scripthead .='<div id="YMapsMarkerList'.$currentArticleId.'" class="zhym-list-'.$markerlistcssstyle.'" style="'.$markerlistAddStyle.' display: none; float: left; padding: 0; margin: 5px; width:'.$divMarkerlistWidth.'; height:'.$divMarkerlistHeight.';">'.$markerlisttag.'</div>';
			break;
			case 3:
				$scripthead .= '<div id="YMMapWrapper'.$currentArticleId.'" '.$divwrapmapstyle.' class="zhym-wrap-'.$markerlistcssstyle.'">';
				$scripthead .='<div id="YMapsMarkerList'.$currentArticleId.'" class="zhym-list-'.$markerlistcssstyle.'" style="'.$markerlistAddStyle.' display: none; float: left; padding: 0; margin: 5px; width:'.$divMarkerlistWidth.'; height:'.$divMarkerlistHeight.';">'.$markerlisttag.'</div>';
			break;
			case 4:
				$scripthead .= '<div id="YMMapWrapper'.$currentArticleId.'" '.$divwrapmapstyle.' class="zhym-wrap-'.$markerlistcssstyle.'">';
				$scripthead .='<div id="YMapsMarkerList'.$currentArticleId.'" class="zhym-list-'.$markerlistcssstyle.'" style="'.$markerlistAddStyle.' display: none; float: left; padding: 0; margin: 5px; width:'.$divMarkerlistWidth.'; height:'.$divMarkerlistHeight.';">'.$markerlisttag.'</div>';
			break;
			case 5:
				$scripthead .= '<div id="YMMapWrapper'.$currentArticleId.'" '.$divwrapmapstyle.' class="zhym-wrap-'.$markerlistcssstyle.'">';
				$scripthead .='<div id="YMapsMarkerList'.$currentArticleId.'" class="zhym-list-'.$markerlistcssstyle.'" style="'.$markerlistAddStyle.' display: none; float: left; padding: 0; margin: 5px; width:'.$divMarkerlistWidth.'; height:'.$divMarkerlistHeight.';">'.$markerlisttag.'</div>';
			break;
			case 6:
				$scripthead .= '<div id="YMMapWrapper'.$currentArticleId.'" '.$divwrapmapstyle.' class="zhym-wrap-'.$markerlistcssstyle.'">';
				$scripthead .='<div id="YMapsMarkerList'.$currentArticleId.'" class="zhym-list-'.$markerlistcssstyle.'" style="'.$markerlistAddStyle.' display: none; float: left; padding: 0; margin: 5px; width:'.$divMarkerlistWidth.'; height:'.$divMarkerlistHeight.';">'.$markerlisttag.'</div>';
			break;
			case 7:
				$scripthead .= '<div id="YMMapWrapper'.$currentArticleId.'" '.$divwrapmapstyle.' class="zhym-wrap-'.$markerlistcssstyle.'">';
				$scripthead .='<div id="YMapsMarkerList'.$currentArticleId.'" class="zhym-list-'.$markerlistcssstyle.'" style="'.$markerlistAddStyle.' display: none; float: left; padding: 0; margin: 5px; width:'.$divMarkerlistWidth.'; height:'.$divMarkerlistHeight.';">'.$markerlisttag.'</div>';
			break;
			case 8:
				$scripthead .= '<div id="YMMapWrapper'.$currentArticleId.'" '.$divwrapmapstyle.' class="zhym-wrap-'.$markerlistcssstyle.'">';
				$scripthead .='<div id="YMapsMarkerList'.$currentArticleId.'" class="zhym-list-'.$markerlistcssstyle.'" style="'.$markerlistAddStyle.' display: none; float: left; padding: 0; margin: 5px; width:'.$divMarkerlistWidth.'; height:'.$divMarkerlistHeight.';">'.$markerlisttag.'</div>';
			break;
			case 9:
				$scripthead .= '<div id="YMMapWrapper'.$currentArticleId.'" '.$divwrapmapstyle.' class="zhym-wrap-'.$markerlistcssstyle.'">';
				$scripthead .='<div id="YMapsMarkerList'.$currentArticleId.'" class="zhym-list-'.$markerlistcssstyle.'" style="'.$markerlistAddStyle.' display: none; float: left; padding: 0; margin: 5px; width:'.$divMarkerlistWidth.'; height:'.$divMarkerlistHeight.';">'.$markerlisttag.'</div>';
			break;
			case 10:
				$scripthead .= '<div id="YMMapWrapper'.$currentArticleId.'" '.$divwrapmapstyle.' class="zhym-wrap-'.$markerlistcssstyle.'">';
				$scripthead .='<div id="YMapsMarkerList'.$currentArticleId.'" class="zhym-list-'.$markerlistcssstyle.'" style="'.$markerlistAddStyle.' display: none; float: left; padding: 0; margin: 5px; width:'.$divMarkerlistWidth.'; height:'.$divMarkerlistHeight.';">'.$markerlisttag.'</div>';
			break;
			case 11:
				$scripthead .= '<div id="YMMapWrapper'.$currentArticleId.'" '.$divwrapmapstyle.' class="zhym-wrap-'.$markerlistcssstyle.'">';
				$scripthead .='<div id="YMapsMarkerList'.$currentArticleId.'" class="zhym-list-'.$markerlistcssstyle.'" style="'.$markerlistAddStyle.' display: none; float: left; padding: 0; margin: 5px; width:'.$divMarkerlistWidth.'; height:'.$divMarkerlistHeight.';">'.$markerlisttag.'</div>';
			break;
			case 12:
				$scripthead .= '<div id="YMMapWrapper'.$currentArticleId.'" '.$divwrapmapstyle.' class="zhym-wrap-'.$markerlistcssstyle.'">';
				$scripthead .='<div id="YMapsMarkerList'.$currentArticleId.'" class="zhym-list-'.$markerlistcssstyle.'" style="'.$markerlistAddStyle.' display: none; float: left; padding: 0; margin: 5px; width:'.$divMarkerlistWidth.'; height:'.$divMarkerlistHeight.';">'.$markerlisttag.'</div>';
			break;
			case 111:
				if ($fullWidth == 1) 
				{
					$scripthead .= '<table id="YMMapTable'.$currentArticleId.'" class="zhym-table-'.$markerlistcssstyle.'" style="width:100%;" >';
				}
				else
				{
					$scripthead .= '<table id="YMMapTable'.$currentArticleId.'" class="zhym-table-'.$markerlistcssstyle.'" >';
				}
				$scripthead .= '<tbody>';
				$scripthead .= '<tr>';
				$scripthead .= '<td style="width:'.$divMarkerlistWidth.'">';
				$scripthead .='<div id="YMapsMarkerList'.$currentArticleId.'" class="zhym-list-'.$markerlistcssstyle.'" style="'.$markerlistAddStyle.' float: left; padding: 0; margin: 0 10px 0 0; width:'.$divMarkerlistWidth.'; height:'.$divMarkerlistHeight.';">'.$markerlisttag.'</div>';
				$scripthead .= '</td>';
				$scripthead .= '<td>';
			break;
			case 112:
				if ($fullWidth == 1) 
				{
					$scripthead .= '<table id="YMMapTable'.$currentArticleId.'" class="zhym-table-'.$markerlistcssstyle.'" style="width:100%;" >';
				}
				else
				{
					$scripthead .= '<table id="YMMapTable'.$currentArticleId.'" class="zhym-table-'.$markerlistcssstyle.'" >';
				}
				$scripthead .= '<tbody>';
				$scripthead .= '<tr>';
				$scripthead .= '<td>';
			break;
			case 113:
				$scripthead .= '<div id="YMMapWrapper'.$currentArticleId.'" '.$divwrapmapstyle.' class="zhym-wrap-'.$markerlistcssstyle.'" >';
				$scripthead .='<div id="YMapsMarkerList'.$currentArticleId.'" class="zhym-list-'.$markerlistcssstyle.'" style="'.$markerlistAddStyle.' display: none; float: left; padding: 0; margin: 0; width:'.$divMarkerlistWidth.'; height:'.$divMarkerlistHeight.';">'.$markerlisttag.'</div>';
			break;
			case 114:
				$scripthead .= '<div id="YMMapWrapper'.$currentArticleId.'" '.$divwrapmapstyle.' class="zhym-wrap-'.$markerlistcssstyle.'" >';
			break;
			case 121:
			break;
			default:
			break;
		}

		
	}
	// End of placemark list, but not all
	
	
	$mapDivTag = 'YMapsID'.'_'.$currentArticleId;
	$mapInitTag = $currentArticleId;
	
	$mapDivCSSClassName = "";
	if (isset($map->cssclassname) && ($map->cssclassname != ""))
	{
		$mapDivCSSClassName = ' class="'.$map->cssclassname.'"';
	}
	
	if ($fullWidth == 1) 
	{
		if ($fullHeight == 1) 
		{
			$scripthead .= '<div id="'.$mapDivTag.'" '.$mapDivCSSClassName.' style="margin:0;padding:0;width:100%;height:100%;"></div>';
		}else{
			$scripthead .= '<div id="'.$mapDivTag.'" '.$mapDivCSSClassName.' style="margin:0;padding:0;width:100%;height:'.$currentMapHeightValue.'px;"></div>';
		}		

	}
	else
	{
		if ($fullHeight == 1) 
		{
			$scripthead .=  '<div id="'.$mapDivTag.'" '.$mapDivCSSClassName.' style="margin:0;padding:0;width:'.$currentMapWidthValue.'px;height:100%;"></div>';			
		}
		else
		{
			$scripthead .=  '<div id="'.$mapDivTag.'" '.$mapDivCSSClassName.' style="margin:0;padding:0;width:'.$currentMapWidthValue.'px;height:'.$currentMapHeightValue.'px;"></div>';			
		}		
	                      
	}		


	
	// adding markerlist (close div) - begin
	if (isset($map->markerlistpos) && (int)$map->markerlistpos != 0) 
	{
		
		switch ((int)$map->markerlistpos) 
		{
			case 0:
				// None
			break;
			case 1:
				$scripthead .='</div>';
			break;
			case 2:
				$scripthead .='</div>';
			break;
			case 3:
				$scripthead .='</div>';
			break;
			case 4:
				$scripthead .='</div>';
			break;
			case 5:
				$scripthead .='</div>';
			break;
			case 6:
				$scripthead .='</div>';
			break;
			case 7:
				$scripthead .='</div>';
			break;
			case 8:
				$scripthead .='</div>';
			break;
			case 9:
				$scripthead .='</div>';
			break;
			case 10:
				$scripthead .='</div>';
			break;
			case 11:
				$scripthead .='</div>';
			break;
			case 12:
				$scripthead .='</div>';
			break;
			case 111:
				$scripthead .= '</td>';
				$scripthead .= '</tr>';
				$scripthead .= '</tbody>';
				$scripthead .='</table>';
			break;
			case 112:
				$scripthead .= '</td>';
				$scripthead .= '<td style="width:'.$divMarkerlistWidth.'">';
				$scripthead .='<div id="YMapsMarkerList'.$currentArticleId.'" class="zhym-list-'.$markerlistcssstyle.'" style="'.$markerlistAddStyle.' float: left; padding: 0; margin: 0 0 0 10px; width:'.$divMarkerlistWidth.'; height:'.$divMarkerlistHeight.';">'.$markerlisttag.'</div>';
				$scripthead .= '</td>';
				$scripthead .= '</tr>';
				$scripthead .= '</tbody>';
				$scripthead .='</table>';
			break;
			case 113:
				$scripthead .='</div>';
			break;
			case 114:
				$scripthead .='<div id="YMapsMarkerList'.$currentArticleId.'" class="zhym-list-'.$markerlistcssstyle.'" style="'.$markerlistAddStyle.' display: none; float: left; padding: 0; margin: 0; width:'.$divMarkerlistWidth.'; height:'.$divMarkerlistHeight.';">'.$markerlisttag.'</div>';
				$scripthead .='</div>';
			break;
			case 121:
			break;
			default:
			break;
		}


	}
	// adding markerlist (close div) - end
	
	
	$scripthead .= '<div id="YMapsCredit'.$currentArticleId.'" class="zhym-credit"></div>';
	
	$divmap4route = '<div id="YMapsMainRoutePanel'.$currentArticleId.'"><div id="YMapsMainRoutePanel_Total'.$currentArticleId.'"></div></div>';
	$divmap4route .= '<div id="YMapsRoutePanel'.$currentArticleId.'"><div id="YMapsRoutePanel_Description'.$currentArticleId.'"></div><div id="YMapsRoutePanel_Total'.$currentArticleId.'"></div><div id="YMapsRoutePanel_Steps'.$currentArticleId.'"></div></div>';
		

	$divwrapmapstyle = '';

	
	if ($fullWidth == 1)
	{
		$divwrapmapstyle .= 'width:100%;';
	}
	if ($fullHeight == 1)
	{
		$divwrapmapstyle .= 'height:100%;';
	}

	$divmapHider = '';
	if ($hiddenContainer != "")
	{
		$divwrapmapstyle .= 'display:none;';
		$divmapHider .= '<a id="zhym-visibility'.$currentArticleId.'" class="zhym-map-visibility" href="#" onclick="containerShowHide'.$currentArticleId.'(); return false;">'.$hiddenContainer.'</a>';
	}
	
	if ($divwrapmapstyle != "")
	{
		$divwrapmapstyle = 'style="'.$divwrapmapstyle.'"';
	}
	

	
	$scripthead = $divmapHider.'<div id="YMWrap'.$mapDivTag.'" '.$divwrapmapstyle.' >'.$divmapheader.$scripthead.$divmapfooter. $divmap4route.'</div>';

	if (!isset($map))
	{
		$this->scripthead = $scripthead;
		$this->scripttext = 'Map with ID='.$id.' or Marker ID='.$placemarkId.' or Group='.$groupIdMapId.' or Category='.$categoryIdMapId.' not found';
		return true;
	}


	//Script begin
	$scripttext = '<script type="text/javascript" >/*<![CDATA[*/' ."\n";

	$scripttext .= 'var map'.$currentArticleId.', mapcenter'.$currentArticleId.', geoResult'.$currentArticleId.', geoRoute'.$currentArticleId.';' ."\n";
	$scripttext .= 'var searchControl'.$currentArticleId.', searchControlPMAP'.$currentArticleId.';' ."\n";

	$scripttext .= 'var container'.$currentArticleId.';' ."\n";

    // MarkerGroups
	if (isset($map->markercluster) && (int)$map->markercluster == 1)
	{
			   $scripttext .= 'var clustermarkers0 = [];' ."\n";
			   $scripttext .= 'var markerCluster0;' ."\n";

				if (isset($markergroups) && !empty($markergroups)) 
			    {
					foreach ($markergroups as $key => $currentmarkergroup) 
					{
							$scripttext .= 'var clustermarkers'.$currentmarkergroup->id.' = [];' ."\n";
							$scripttext .= 'var markerCluster'.$currentmarkergroup->id.';' ."\n";
					}
			    }
	}                                                              
	
	
	$scripttext .= 'ymaps.ready(initialize'.$currentArticleId.');' ."\n";

	$scripttext .= 'function initialize'.$currentArticleId.' () {' ."\n";

		
		$scripttext .= '    mapcenter'.$currentArticleId.' = ['.$map->longitude.', ' .$map->latitude.'];' ."\n";

		// $scripttext .= '	container'.$currentArticleId.' = YMaps.jQuery("#YMWrap'.$mapDivTag.'");' ."\n";
        $scripttext .= '    map'.$currentArticleId.' = new ymaps.Map("'.$mapDivTag.'", {' ."\n";
		$scripttext .= '    center: mapcenter'.$currentArticleId.',' ."\n";
		$scripttext .= '    zoom: '.(int)$map->zoom .''."\n";
		$scripttext .= '    });' ."\n";
		//$scripttext .= '    map'.$currentArticleId.' = new YMaps.Map(container'.$currentArticleId.'[0]);' ."\n";

		$scripttext .= 'geoResult'.$currentArticleId.' = new ymaps.GeoObjectCollection();'."\n";

        $scripttext .= '    map'.$currentArticleId.'.setCenter(mapcenter'.$currentArticleId.');' ."\n";
		
		if ($this->compatiblemodersf == 0)
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
		
		if (
			((isset($map->markercluster) && (int)$map->markercluster == 0))
			 //&&(isset($map->markergroupcontrol) && (int)$map->markergroupcontrol == 0) 
		   )
		{
		   if (isset($markergroups) && !empty($markergroups)) 
		   {
				foreach ($markergroups as $key => $currentmarkergroup) 
				{
					$markergroupname = 'markergroup'. $currentmarkergroup->id;

					$imgimg = $imgpathIcons.str_replace("#", "%23", $currentmarkergroup->icontype).'.png';
					$imgimg4size = $imgpath4size.$currentmarkergroup->icontype.'.png';

					list ($imgwidth, $imgheight) = getimagesize($imgimg4size);

					// For clusterer collection doesn't need yet :(
					
					if ((isset($map->markercluster) && (int)$map->markercluster == 0)
						&& (int)$currentmarkergroup->overridemarkericon == 1
						&& (int)$currentmarkergroup->published == 1)
					{
						$scripttext .= ' var '.$markergroupname.' = new ymaps.GeoObjectCollection();'."\n";
						
						$imgimg = $imgpathIcons.str_replace("#", "%23", $currentmarkergroup->icontype).'.png';
						$imgimg4size = $imgpath4size.$currentmarkergroup->icontype.'.png';

						list ($imgwidth, $imgheight) = getimagesize($imgimg4size);

						$scripttext .= $markergroupname.'.options.set("iconImageHref", "'.$imgimg.'");' ."\n";
						$scripttext .= $markergroupname.'.options.set("iconImageSize", ['.$imgwidth.','.$imgheight.']);' ."\n";
						if (substr($currentmarkergroup->icontype, 0, 8) == "default#")
						{
							$offset_fix = 7;
						}
						else
						{
							$offset_fix = $imgwidth/2;
						}
						
						if (isset($currentmarkergroup->iconofsetx) 
						 && isset($currentmarkergroup->iconofsety) 
						// Write offset all time
						// && ((int)$currentmarkergroup->iconofsetx !=0
						//  || (int)$currentmarkergroup->iconofsety !=0)
						 )
						{
							// This is for compatibility
							$ofsX = (int)$currentmarkergroup->iconofsetx - $offset_fix;
							$ofsY = (int)$currentmarkergroup->iconofsety - $imgheight;
							$scripttext .= $markergroupname.'.options.set("iconImageOffset", ['.$ofsX.','.$ofsY.']);' ."\n";
						}
						
						$scripttext .= 'map'.$currentArticleId.'.geoObjects.add('.$markergroupname.');' ."\n";
						
					}
					
				}
		   }
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
		
		$scripttext .= 'map'.$currentArticleId.'.geoObjects.add(markerCluster0);' ."\n";
		

        if ((isset($map->markerclustergroup) && (int)$map->markerclustergroup == 1))
		{
			if ($compatiblemodersf == 0)
			{
				$imgpath4size = JPATH_ADMINISTRATOR .'/components/com_zhyandexmap/assets/icons/';
			}
			else
			{
				$imgpath4size = JPATH_SITE .'/components/com_zhyandexmap/assets/icons/';
			}

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
					$scripttext .= 'map'.$currentArticleId.'.geoObjects.add(markerCluster'.$currentmarkergroup->id.');' ."\n";
				}
			}

		}
		
	}

	   
	//Double Click Zoom
	if (isset($map->doubleclickzoom) && (int)$map->doubleclickzoom == 1) 
	{
		$scripttext .= 'map'.$currentArticleId.'.behaviors.enable(\'dblClickZoom\');' ."\n";
	} 
	else 
	{
		$scripttext .= 'if (map'.$currentArticleId.'.behaviors.isEnabled(\'dblClickZoom\'))' ."\n";
		$scripttext .= 'map'.$currentArticleId.'.behaviors.disable(\'dblClickZoom\');' ."\n";
	}


	//Scroll Wheel Zoom		
	if (isset($map->scrollwheelzoom) && (int)$map->scrollwheelzoom == 1) 
	{
		$scripttext .= 'map'.$currentArticleId.'.behaviors.enable(\'scrollZoom\');' ."\n";
	} 
	else 
	{
		$scripttext .= 'if (map'.$currentArticleId.'.behaviors.isEnabled(\'scrollZoom\'))' ."\n";
		$scripttext .= 'map'.$currentArticleId.'.behaviors.disable(\'scrollZoom\');' ."\n";
	}
		

	//Zoom Control
	if (isset($map->zoomcontrol)) 
	{
                $ctrlPosition = "";
                $ctrlPositionFullText ="";
                
                if (isset($map->zoomcontrolpos)) 
                {
                    switch ($map->zoomcontrolpos)
                    {
                        case 1:
                            // TOP_LEFT
							$ctrlPosition = "{ top: ".(int)$map->zoomcontrolofsy.", left: ".(int)$map->zoomcontrolofsx."}";
                        break;
                        case 2:
                            // TOP_RIGHT
							$ctrlPosition = "{ top: ".(int)$map->zoomcontrolofsy.", right: ".(int)$map->zoomcontrolofsx."}";
                        break;
                        case 3:
                            // BOTTOM_RIGHT
							$ctrlPosition = "{ bottom: ".(int)$map->zoomcontrolofsy.", right: ".(int)$map->zoomcontrolofsx."}";
                        break;
                        case 4:
                            // BOTTOM_LEFT
							$ctrlPosition = "{ bottom: ".(int)$map->zoomcontrolofsy.", left: ".(int)$map->zoomcontrolofsx."}";
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
            
		switch ($map->zoomcontrol) 
		{
			case 1:
				$scripttext .= 'map'.$currentArticleId.'.controls.add(new ymaps.control.ZoomControl()'.$ctrlPositionFullText.');' ."\n";
			break;
			case 2:
				$scripttext .= 'map'.$currentArticleId.'.controls.add(new ymaps.control.SmallZoomControl()'.$ctrlPositionFullText.');' ."\n";
			break;
			default:
				$scripttext .= '' ."\n";
			break;
		}
	}
	

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
		$scripttext .= 'map'.$currentArticleId.'.controls.add(scaleline'.$ctrlPositionFullText.');' ."\n";
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
		$credits .= 'OSM '.JText::_('PLG_ZHYANDEXMAP_MAP_POWEREDBY').': ';
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
					
					$scripttext .= '});' ."\n";
					
					$scripttext .= 'customMapLayer'.$currentmaptype->id.'.getTileUrl = '.$currentmaptype->gettileurl ."\n";

					$scripttext .= 'customMapType'.$currentmaptype->id.' = function () { return customMapLayer'.$currentmaptype->id.';';
					$scripttext .= '};' ."\n";
					
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
		
		if (isset($map->maptypecontrolpos)) 
		{
			switch ($map->maptypecontrolpos)
			{
				case 1:
					// TOP_LEFT
					$ctrlPosition = "{ top: ".(int)$map->maptypecontrolofsy.", left: ".(int)$map->maptypecontrolofsx."}";
				break;
				case 2:
					// TOP_RIGHT
					$ctrlPosition = "{ top: ".(int)$map->maptypecontrolofsy.", right: ".(int)$map->maptypecontrolofsx."}";
				break;
				case 3:
					// BOTTOM_RIGHT
					$ctrlPosition = "{ bottom: ".(int)$map->maptypecontrolofsy.", right: ".(int)$map->maptypecontrolofsx."}";
				break;
				case 4:
					// BOTTOM_LEFT
					$ctrlPosition = "{ bottom: ".(int)$map->maptypecontrolofsy.", left: ".(int)$map->maptypecontrolofsx."}";
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
								
		$scripttext .= 'map'.$currentArticleId.'.controls.add(new ymaps.control.TypeSelector(['.$ctrlMapType.'])'.$ctrlPositionFullText.');' ."\n";
	}

	// Map type
	// Change $map->maptype to $currentMapType
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

		switch ($currentMapTypeValue) 
		{
			
			case 1:
				$scripttext .= 'map'.$currentArticleId.'.setType("yandex#map");' ."\n";
			break;
			case 2:
				$scripttext .= 'map'.$currentArticleId.'.setType("yandex#satellite");' ."\n";
			break;
			case 3:
				$scripttext .= 'map'.$currentArticleId.'.setType("yandex#hybrid");' ."\n";
			break;
			case 4:
				$scripttext .= 'map'.$currentArticleId.'.setType("yandex#publicMap");' ."\n";
			break;
			case 5:
				$scripttext .= 'map'.$currentArticleId.'.setType("yandex#publicMapHybrid");' ."\n";
			break;
			case 6:
				if ((int)$map->openstreet == 1)
				{
					$scripttext .= 'map'.$currentArticleId.'.setType("osmMapType");' ."\n";
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
								$scripttext .= ' map'.$currentArticleId.'.setType(\'customMapType'.$currentmaptype->id.'\');' ."\n";
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
	

	
	if (isset($currentZoom))
	{
	  if ($currentZoom == "do not change")  
	  {
	  }
	  else
	  {
		if ((int)$currentZoom != 200)
		{
			$scripttext .= '    map'.$currentArticleId.'.setZoom('.(int)$currentZoom.');' ."\n";
		}
		else
		{
			//$scripttext .= '    map'.$currentArticleId.'.setZoom(map'.$currentArticleId.'.options.get("maxZoom"));' ."\n";
			$scripttext .= '  map'.$currentArticleId.'.zoomRange.get(map'.$currentArticleId.'.getCenter()).then(function (range) {' ."\n";
			$scripttext .= '    map'.$currentArticleId.'.setZoom(range[1]);' ."\n";
			$scripttext .= '});' ."\n";
		}
	  }
	}
	else
	{
		// Because that we set map type
		if ((int)$map->zoom != 200)
		{
			$scripttext .= '    map'.$currentArticleId.'.setZoom('.(int)$map->zoom.');' ."\n";
		}
		else
		{
			$scripttext .= '  map'.$currentArticleId.'.zoomRange.get(map'.$currentArticleId.'.getCenter()).then(function (range) {' ."\n";
			$scripttext .= '    map'.$currentArticleId.'.setZoom(range[1]);' ."\n";
			$scripttext .= '});' ."\n";
		}
	}

	$scripttext .= '    map'.$currentArticleId.'.options.set("minZoom",'.(int)$map->minzoom.');' ."\n";
	if ((int)$map->maxzoom != 200)
	{
		$scripttext .= '    map'.$currentArticleId.'.options.set("maxZoom", '.(int)$map->maxzoom.');' ."\n";
	}
	
	// When changed maptype max zoom level can be other
	$scripttext .= 'map'.$currentArticleId.'.events.add("typechange", function (e) {' ."\n";
	//$scripttext .= '     alert("Change Type!");' ."\n";
	$scripttext .= '  map'.$currentArticleId.'.zoomRange.get(map'.$currentArticleId.'.getCenter()).then(function (range) {' ."\n";
	//$scripttext .= '  alert("range"+range[1]);';
	
	$scripttext .= '  if (map'.$currentArticleId.'.getZoom() > range[1] )' ."\n";
	$scripttext .= '  {	' ."\n";
	//$scripttext .= '     alert("Change Zoom!");' ."\n";
	$scripttext .= '    map'.$currentArticleId.'.setZoom(range[1]);' ."\n";
	$scripttext .= '  }' ."\n";
	$scripttext .= '});' ."\n";
	$scripttext .= '});' ."\n";

	if (isset($map->mapbounds) && $map->mapbounds != "")
	{
		$mapBoundsArray = explode(";", str_replace(',',';',$map->mapbounds));
		if (count($mapBoundsArray) != 4)
		{
			$scripttext .= 'alert("'.JText::_('PLG_ZHYANDEXMAP_MAP_ERROR_MAPBOUNDS').'");'."\n";
		}
		else
		{
			$scripttext .= '   var allowedBounds'.$currentArticleId.' = [' ."\n";
			$scripttext .= '	 ['.$mapBoundsArray[0].', '.$mapBoundsArray[1].'],' ."\n";
			$scripttext .= '	 ['.$mapBoundsArray[2].', '.$mapBoundsArray[3].']];' ."\n";
			
			// Listen for the event
			$scripttext .= '  map'.$currentArticleId.'.events.add("boundschange", function() {' ."\n";

			// Out of bounds - Move the map back within the bounds
			$scripttext .= '	 var c = map'.$currentArticleId.'.getCenter(),' ."\n";
			$scripttext .= '		 y = c[0],' ."\n";
			$scripttext .= '		 x = c[1],' ."\n";
			$scripttext .= '		 maxY = allowedBounds'.$currentArticleId.'[1][0],' ."\n";
			$scripttext .= '		 maxX = allowedBounds'.$currentArticleId.'[1][1],' ."\n";
			$scripttext .= '		 minY = allowedBounds'.$currentArticleId.'[0][0],' ."\n";
			$scripttext .= '		 minX = allowedBounds'.$currentArticleId.'[0][1];' ."\n";

			$scripttext .= '	 if (maxX < minX)' ."\n";
			$scripttext .= '	{' ."\n";
			$scripttext .= '	  	minX = allowedBounds'.$currentArticleId.'[1][1];' ."\n";
			$scripttext .= '	  	maxX = allowedBounds'.$currentArticleId.'[0][1];' ."\n";
			$scripttext .= '	}' ."\n";
			$scripttext .= '	 if (maxY < minY)' ."\n";
			$scripttext .= '	{' ."\n";
			$scripttext .= '	  	minY = allowedBounds'.$currentArticleId.'[1][0];' ."\n";
			$scripttext .= '		maxY = allowedBounds'.$currentArticleId.'[0][0];' ."\n";
			$scripttext .= '	}' ."\n";
			$scripttext .= '	 if ((x <= maxX && x >= minX) && (y <= maxY && y >= minY)) return;' ."\n";
			
			$scripttext .= '	 if (x < minX) x = minX;' ."\n";
			$scripttext .= '	 if (x > maxX) x = maxX;' ."\n";
			$scripttext .= '	 if (y < minY) y = minY;' ."\n";
			$scripttext .= '	 if (y > maxY) y = maxY;' ."\n";

			$scripttext .= '	 var newCenter = [];' ."\n";
			$scripttext .= '	    newCenter.push(y);' ."\n";
			$scripttext .= '	    newCenter.push(x);' ."\n";
			$scripttext .= '	 map'.$currentArticleId.'.setCenter(newCenter);' ."\n";
			$scripttext .= '   });' ."\n";
		}
	}	
	
	//MiniMap
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

            $scripttext .= 'minimap'.$currentArticleId.' = new ymaps.control.MiniMap();' ."\n";
			
			if ((int)$map->minimap == 1)
			{
				$scripttext .= 'minimap'.$currentArticleId.'.expand();' ."\n";
			}
			else
			{
				$scripttext .= 'minimap'.$currentArticleId.'.collapse();' ."\n";
			}

			if ($scripttextMiniMap != "")
			{
				$scripttext .= 'minimap'.$currentArticleId.'.setType("'.$scripttextMiniMap.'");' ."\n";
			}

			
            $scripttext .= 'map'.$currentArticleId.'.controls.add(minimap'.$currentArticleId.$ctrlPositionFullText.');' ."\n";
	}
	
		
	//Toolbar
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

                $scripttext .= 'var toolbar'.$currentArticleId.' = new ymaps.control.MapTools();' ."\n";
                $scripttext .= 'map'.$currentArticleId.'.controls.add(toolbar'.$currentArticleId.$ctrlPositionFullText.');' ."\n";
				

				
				
			if (isset($map->autopositioncontrol) && (int)$map->autopositioncontrol == 1) 
			{
					switch ((int)$map->autopositionbutton) 
					{
					case 1:
						$scripttext .= 'var btnGeoPosition'.$currentArticleId.' = new ymaps.control.Button({ data: { image: "'.$imgpathUtils.'geolocation.png", content: "", title: "'.JText::_('PLG_ZHYANDEXMAP_MAP_AUTOPOSITIONBUTTON').'"}});' ."\n";
					break;
					case 2:
						$scripttext .= 'var btnGeoPosition'.$currentArticleId.' = new ymaps.control.Button({ data: { content: "'.JText::_('PLG_ZHYANDEXMAP_MAP_AUTOPOSITIONBUTTON').'", title: "'.JText::_('PLG_ZHYANDEXMAP_MAP_AUTOPOSITIONBUTTON').'"}});' ."\n";
					break;
					case 3:
						$scripttext .= 'var btnGeoPosition'.$currentArticleId.' = new ymaps.control.Button({ data: { image: "'.$imgpathUtils.'geolocation.png", content: "'.JText::_('PLG_ZHYANDEXMAP_MAP_AUTOPOSITIONBUTTON').'", title: "'.JText::_('PLG_ZHYANDEXMAP_MAP_AUTOPOSITIONBUTTON').'"}});' ."\n";
					break;
					default:
						$scripttext .= 'var btnGeoPosition'.$currentArticleId.' = new ymaps.control.Button({ data: { image: "'.$imgpathUtils.'geolocation.png", content: "", title: "'.JText::_('PLG_ZHYANDEXMAP_MAP_AUTOPOSITIONBUTTON').'"}});' ."\n";
					break;
					}

					$scripttext .= 'btnGeoPosition'.$currentArticleId.'.events.add("click", function (e) {' ."\n";
					$scripttext .= '	findMyPosition'.$currentArticleId.'("Button");' ."\n";
					$scripttext .= '}, toolbar'.$currentArticleId.');' ."\n";
					$scripttext .= 'toolbar'.$currentArticleId.'.add(btnGeoPosition'.$currentArticleId.');' ."\n";

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
								$btnPlacemarkListOptions ='btnPlacemarkList'.$currentArticleId.'.select();' ;
							break;
							case 12:
								$btnPlacemarkListOptions ='btnPlacemarkList'.$currentArticleId.'.select();' ;
							break;
							default:
								$btnPlacemarkListOptions ="" ;
							break;
						}		
						
						switch ((int)$map->markerlistbuttontype) 
						{
							case 1:
								$scripttext .= 'var btnPlacemarkList'.$currentArticleId.' = new ymaps.control.Button({ data: { image: "'.$imgpathUtils.'star.png", content: "", title: "'.JText::_('PLG_ZHYANDEXMAP_MAP_PLACEMARKLIST').'"}});' ."\n";
							break;
							case 2:
								$scripttext .= 'var btnPlacemarkList'.$currentArticleId.' = new ymaps.control.Button({ data: { content: "'.JText::_('PLG_ZHYANDEXMAP_MAP_PLACEMARKLIST').'", title: "'.JText::_('PLG_ZHYANDEXMAP_MAP_PLACEMARKLIST').'"}});' ."\n";
							break;
							case 11:
								$scripttext .= 'var btnPlacemarkList'.$currentArticleId.' = new ymaps.control.Button({ data: { image: "'.$imgpathUtils.'star.png", content: "", title: "'.JText::_('PLG_ZHYANDEXMAP_MAP_PLACEMARKLIST').'"}});' ."\n";
							break;
							case 2:
								$scripttext .= 'var btnPlacemarkList'.$currentArticleId.' = new ymaps.control.Button({ data: { content: "'.JText::_('PLG_ZHYANDEXMAP_MAP_PLACEMARKLIST').'", title: "'.JText::_('PLG_ZHYANDEXMAP_MAP_PLACEMARKLIST').'"}});' ."\n";
							default:
								$scripttext .= 'var btnPlacemarkList'.$currentArticleId.' = new ymaps.control.Button({ data: { image: "'.$imgpathUtils.'star.png", content: "", title: "'.JText::_('PLG_ZHYANDEXMAP_MAP_PLACEMARKLIST').'"}});' ."\n";
							break;
						}

						$scripttext .= 'btnPlacemarkList'.$currentArticleId.'.events.add("select", function (e) {' ."\n";
						$scripttext .= '		var toHideDiv = document.getElementById("YMapsMarkerList'.$currentArticleId.'");' ."\n";
						$scripttext .= '		toHideDiv.style.display = "block";' ."\n";
						$scripttext .= '}, toolbar'.$currentArticleId.');' ."\n";

						$scripttext .= 'btnPlacemarkList'.$currentArticleId.'.events.add("deselect", function (e) {' ."\n";
						$scripttext .= '		var toHideDiv = document.getElementById("YMapsMarkerList'.$currentArticleId.'");' ."\n";
						$scripttext .= '		toHideDiv.style.display = "none";' ."\n";
						$scripttext .= '}, toolbar'.$currentArticleId.');' ."\n";

						

						
						$scripttext .= $btnPlacemarkListOptions;
						
						$scripttext .= 'toolbar'.$currentArticleId.'.add(btnPlacemarkList'.$currentArticleId.');' ."\n";
					
				}
			}
		
		}
		
			
				
	}
	

	if (isset($this->licenseinfo) && (int)$this->licenseinfo != 0) 
	{
	
		if ((int)$this->licenseinfo == 102 // Map-License (into credits)
		  ) 
		{
			// Do not create button when L-M, M-L or external
			if ($credits != '')
			{
				$credits .= '<br />';
			}
			$credits .= ''.JText::_('PLG_ZHYANDEXMAP_MAP_POWEREDBY').': ';
			$credits .= '<a href="http://www.zhuk.cc/" target="_blank" alt="'.JText::_('PLG_ZHYANDEXMAP_MAP_POWEREDBY').'">zhuk.cc</a>';
		}
		else
		{
		}
	}
	
	if ($credits != '')
	{
		$scripttext .= '  document.getElementById("YMapsCredit'.$currentArticleId.'").innerHTML = \''.$credits.'\';'."\n";
	}
	
	//Search
	if (isset($map->search) && (int)$map->search == 1) 
	{
                $ctrlPosition = "";
                $ctrlPositionFullText ="";
                
                if (isset($map->searchpos)) 
                {
                    switch ($map->searchpos)
                    {
                        case 1:
                            // TOP_LEFT
							$ctrlPosition = "{ top: ".(int)$map->searchofsy.", left: ".(int)$map->searchofsx."}";
                        break;
                        case 2:
                            // TOP_RIGHT
							$ctrlPosition = "{ top: ".(int)$map->searchofsy.", right: ".(int)$map->searchofsx."}";
                        break;
                        case 3:
                            // BOTTOM_RIGHT
							$ctrlPosition = "{ bottom: ".(int)$map->searchofsy.", right: ".(int)$map->searchofsx."}";
                        break;
                        case 4:
                            // BOTTOM_LEFT
							$ctrlPosition = "{ bottom: ".(int)$map->searchofsy.", left: ".(int)$map->searchofsx."}";
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


                $scripttext .= 'searchControl'.$currentArticleId.' = new ymaps.control.SearchControl();' ."\n";
                $scripttext .= 'searchControlPMAP'.$currentArticleId.' = new ymaps.control.SearchControl({provider: "yandex#publicMap"});' ."\n";
				$scripttext .= '   if ((map'.$currentArticleId.'.getType() == "yandex#publicMap") || (map'.$currentArticleId.'.getType() == "yandex#publicMapHybrid"))';
				$scripttext .= '   {';
				//$scripttext .= '	  alert("PMAP");' ."\n";
                $scripttext .= '	  map'.$currentArticleId.'.controls.add(searchControlPMAP'.$currentArticleId.$ctrlPositionFullText.');' ."\n";
				$scripttext .= '   }';
				$scripttext .= '   else';
				$scripttext .= '   {';
				//$scripttext .= '	  alert("MAP");' ."\n";
                $scripttext .= '	  map'.$currentArticleId.'.controls.add(searchControl'.$currentArticleId.$ctrlPositionFullText.');' ."\n";
				$scripttext .= '   }';
				

				$scripttext .= 'map'.$currentArticleId.'.events.add("typechange", function (e) {' ."\n";
				$scripttext .= '   if ((map'.$currentArticleId.'.getType() == "yandex#publicMap") || (map'.$currentArticleId.'.getType() == "yandex#publicMapHybrid"))';
				$scripttext .= '   {';
				//$scripttext .= '	  alert("PMAP");' ."\n";
				$scripttext .= '	  map'.$currentArticleId.'.controls.remove(searchControl'.$currentArticleId.');' ."\n";
				$scripttext .= '	  map'.$currentArticleId.'.controls.add(searchControlPMAP'.$currentArticleId.$ctrlPositionFullText.');' ."\n";
				$scripttext .= '   }';
				$scripttext .= '   else';
				$scripttext .= '   {';
				//$scripttext .= '	  alert("Map");' ."\n";
				$scripttext .= '	  map'.$currentArticleId.'.controls.remove(searchControlPMAP'.$currentArticleId.');' ."\n";
				$scripttext .= '	  map'.$currentArticleId.'.controls.add(searchControl'.$currentArticleId.$ctrlPositionFullText.');' ."\n";
				$scripttext .= '   }';
				$scripttext .= '});' ."\n";
				
	}
	

	//Traffic
	if (isset($map->traffic) && (int)$map->traffic == 1) 
	{
                $ctrlPosition = "";
                $ctrlPositionFullText ="";
                
                if (isset($map->trafficpos)) 
                {
                    switch ($map->trafficpos)
                    {
                        case 1:
                            // TOP_LEFT
							$ctrlPosition = "{ top: ".(int)$map->trafficofsy.", left: ".(int)$map->trafficofsx."}";
                        break;
                        case 2:
                            // TOP_RIGHT
							$ctrlPosition = "{ top: ".(int)$map->trafficofsy.", right: ".(int)$map->trafficofsx."}";
                        break;
                        case 3:
                            // BOTTOM_RIGHT
							$ctrlPosition = "{ bottom: ".(int)$map->trafficofsy.", right: ".(int)$map->trafficofsx."}";
                        break;
                        case 4:
                            // BOTTOM_LEFT
							$ctrlPosition = "{ bottom: ".(int)$map->trafficofsy.", left: ".(int)$map->trafficofsx."}";
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

				if (isset($map->trafficprovider) && (int)$map->trafficprovider == 1) 
				{
					$trafficProvider = 'providerKey: \'traffic#archive\'';
				}
				else
				{
					$trafficProvider = 'providerKey: \'traffic#actual\'';
				}
				
				if (isset($map->trafficlayer) && (int)$map->trafficlayer == 1) 
				{
					$scripttext .= 'map'.$currentArticleId.'.controls.add(new ymaps.control.TrafficControl({'.$trafficProvider.', shown: true})'.$ctrlPositionFullText.');' ."\n";
				}
				else
				{
					$scripttext .= 'map'.$currentArticleId.'.controls.add(new ymaps.control.TrafficControl({'.$trafficProvider.'})'.$ctrlPositionFullText.');' ."\n";
				}
	}

	if (isset($map->rightbuttonmagnifier) && (int)$map->rightbuttonmagnifier == 1) 
	{
		$scripttext .= 'map'.$currentArticleId.'.behaviors.enable(\'rightMouseButtonMagnifier\');' ."\n";
	} 
	else 
	{
		$scripttext .= 'if (map'.$currentArticleId.'.behaviors.isEnabled(\'rightMouseButtonMagnifier\'))' ."\n";
		$scripttext .= 'map'.$currentArticleId.'.behaviors.disable(\'rightMouseButtonMagnifier\');' ."\n";
	}


	if (isset($map->magnifier)) 
	{
		switch ((int)$map->magnifier)
		{
			case 0:
			break;
			case 1:
				$scripttext .= 'map'.$currentArticleId.'.behaviors.enable(\'leftMouseButtonMagnifier\');'."\n";
			break;
			case 2:
				$scripttext .= 'map'.$currentArticleId.'.behaviors.enable(\'ruler\');'."\n";
			break;
			default:
			break;
		}
	}

	if (isset($map->draggable) && (int)$map->draggable == 1) 
	{
		$scripttext .= 'map'.$currentArticleId.'.behaviors.enable(\'drag\');' ."\n";
	} 
	else 
	{
		$scripttext .= 'if (map'.$currentArticleId.'.behaviors.isEnabled(\'drag\'))' ."\n";
		$scripttext .= 'map'.$currentArticleId.'.behaviors.disable(\'drag\');' ."\n";
	}

	/*
	//Grid Coordinates		
	if (isset($map->gridcoordinates) && (int)$map->gridcoordinates == 1) 
	{
		$scripttext .= 'map'.$currentArticleId.'.addLayer(new YMaps.Layer(new YMaps.TileDataSource("http://lrs.maps.yandex.net/tiles/?l=grd&v=1.0&%c", true, false)));' ."\n";
	}
	*/
	
	/*
	if (isset($map->markermanager) && (int)$map->markermanager == 1) 
	{
            $scripttext .= 'var objectManager = new YMaps.ObjectManager();'."\n";
            $scripttext .= 'map'.$currentArticleId.'.addOverlay(objectManager);'."\n";
    }

	*/
	
	//Balloon	
	if (isset($map->balloon)) 
	{
		switch ($map->balloon) 
		{
			case 0:
			break;
			case 1:
				$scripttext .= 'map'.$currentArticleId.'.balloon.open(['.$map->longitude.', ' .$map->latitude.'], { contentBody: "'.htmlspecialchars(str_replace('\\', '/', $map->title), ENT_QUOTES, 'UTF-8').'"});' ."\n";
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
				$scripttext .= 'map'.$currentArticleId.'.geoObjects.add(placemark);' ."\n";
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
				$scripttext .= 'map'.$currentArticleId.'.geoObjects.add(placemark);' ."\n";
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
			$scripttext .= 'var markerUL = document.getElementById("YMapsMarkerUL'.$currentArticleId.'");'."\n";
			$scripttext .= 'if (!markerUL)'."\n";
			$scripttext .= '{'."\n";
			$scripttext .= ' alert("'.JText::_('PLG_ZHYANDEXMAP_MAP_MARKERUL_NOTFIND').'");'."\n";
			$scripttext .= '}'."\n";
		}
		else
		{
			$scripttext .= 'var markerUL = document.getElementById("YMapsMarkerTABLEBODY'.$currentArticleId.'");'."\n";
			$scripttext .= 'if (!markerUL)'."\n";
			$scripttext .= '{'."\n";
			$scripttext .= ' alert("'.JText::_('PLG_ZHYANDEXMAP_MAP_MARKERTABLE_NOTFIND').'");'."\n";
			$scripttext .= '}'."\n";
		}
		
	}
	
	
	// Markers
	$doAddToListCount = 0;

	if (isset($markers) && !empty($markers)) 
	{
	
		foreach ($markers as $key => $currentmarker) 
		{
			if ( 
				((($currentmarker->markergroup != 0)
					&& ((int)$currentmarker->published == 1)
					&& ((int)$currentmarker->publishedgroup == 1)) 
				) || 
				((($currentmarker->markergroup == 0)
					&& ((int)$currentmarker->published == 1)) 
				) 
			   )
			{
				$markername ='';
				$markername = 'placemark'. $currentmarker->id;

				$scripttext .= 'var latlng'.$currentmarker->id.'= ['.$currentmarker->longitude.', ' .$currentmarker->latitude.'];' ."\n";
				$scripttext .= 'var '.$markername.'= new ymaps.Placemark(latlng'.$currentmarker->id.');' ."\n";

				if ((int)$currentmarker->actionbyclick == 1)
				{
					$scripttext .= $markername.'.options.set("hasBalloon", true);'."\n";
				}
				else
				{
					$scripttext .= $markername.'.options.set("hasBalloon", false);'."\n";
				}

				$scripttext .= $markername.'.properties.set("hintContent", \''.htmlspecialchars(str_replace('\\', '/', $currentmarker->title), ENT_QUOTES, 'UTF-8').'\');'."\n";
				
				if (isset($currentCenter))
				{
				  if ($currentCenter == "placemark")  
				  {
					$scripttext .= '  map'.$currentArticleId.'.setCenter(latlng'. $currentmarker->id.');' ."\n";
				  }
				}

				if ((isset($map->markercluster) && (int)$map->markercluster == 0)
				  &&(isset($map->markergroupcontrol) && (int)$map->markergroupcontrol == 0)
				  && ((int)$currentmarker->overridemarkericon == 1)
				  && ((int)$currentmarker->publishedgroup == 1)
				   )
				{
					// later when pushing into arrays
				}
				else
				{
					if (((int)$currentmarker->overridemarkericon == 1)
					  && ((int)$currentmarker->publishedgroup == 1)
					)
					{
							$imgimg = $imgpathIcons.str_replace("#", "%23", $currentmarker->groupicontype).'.png';
							$imgimg4size = $imgpath4size.$currentmarker->groupicontype.'.png';

							list ($imgwidth, $imgheight) = getimagesize($imgimg4size);

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
				}

				
				$scripttext .= 'var contentStringHead'. $currentmarker->id.' = \'<div id="placemarkContent'. $currentmarker->id.'">\' +' ."\n";
				if (isset($currentmarker->markercontent) &&
					(((int)$currentmarker->markercontent == 0) ||
					 ((int)$currentmarker->markercontent == 1))
					)
				{
					$scripttext .= '\'<h1 id="headContent'. $currentmarker->id.'" class="placemarkHead">'.htmlspecialchars(str_replace('\\', '/', $currentmarker->title), ENT_QUOTES, 'UTF-8').'</h1>\'+' ."\n";
				}
				$scripttext .= '\'</div>\';'."\n";
				
				$scripttext .= 'var contentStringHeadCluster'. $currentmarker->id.' = \'<div id="placemarkContentCluster'. $currentmarker->id.'">\' +' ."\n";
				$scripttext .= '\'<span id="headContentCluster'. $currentmarker->id.'" class="placemarkHeadCluster">'.htmlspecialchars(str_replace('\\', '/', $currentmarker->title), ENT_QUOTES, 'UTF-8').'</span>\'+' ."\n";
				$scripttext .= '\'</div>\';'."\n";

				$scripttext .= 'var contentStringBody'. $currentmarker->id.' = \'<div id="bodyContent'. $currentmarker->id.'"  class="placemarkBody">\'+'."\n";

						if ($currentmarker->hrefimage!="")
						{
							 $scripttext .= '\'<img src="'.$currentmarker->hrefimage.'" alt="" />\'+'."\n";
						}

						if (isset($currentmarker->markercontent) &&
							(((int)$currentmarker->markercontent == 0) ||
							 ((int)$currentmarker->markercontent == 2))
							)
						{
							$scripttext .= '\''.htmlspecialchars(str_replace('\\', '/', $currentmarker->description), ENT_QUOTES, 'UTF-8').'\'+'."\n";
						}
						$scripttext .= '\''.str_replace("'", "\'", str_replace(array("\r", "\r\n", "\n"), '', $currentmarker->descriptionhtml)).'\'+'."\n";

						//$scripttext .= ' latlng'. $currentmarker->id. '.toString()+'."\n";

						// Contact info - begin
						if (isset($map->usercontact) && ((int)$map->usercontact != 0))
						{
							if (isset($currentmarker->showcontact) && ((int)$currentmarker->showcontact != 0))
							{
								switch ((int)$currentmarker->showcontact) 
								{
									case 1:
										if ($currentmarker->contact_name != "") 
										{
											$scripttext .= '\'<p class="placemarkBodyContact">'.JText::_('PLG_ZHYANDEXMAP_MAP_USER_CONTACT_NAME').' '.htmlspecialchars(str_replace('\\', '/', $currentmarker->contact_name), ENT_QUOTES, 'UTF-8').'</p>\'+'."\n";
										}
										if ($currentmarker->contact_position != "") 
										{
											$scripttext .= '\'<p class="placemarkBodyContact">'.JText::_('PLG_ZHYANDEXMAP_MAP_USER_CONTACT_POSITION').' '.htmlspecialchars(str_replace('\\', '/', $currentmarker->contact_position), ENT_QUOTES, 'UTF-8').'</p>\'+'."\n";
										}
										if ($currentmarker->contact_address != "") 
										{
											$scripttext .= '\'<p class="placemarkBodyContact">'.JText::_('PLG_ZHYANDEXMAP_MAP_USER_CONTACT_ADDRESS').' '.str_replace('<br /><br />', '<br />', str_replace(array("\r", "\r\n", "\n"), '<br />', htmlspecialchars(str_replace('\\', '/', $currentmarker->contact_address), ENT_QUOTES, 'UTF-8'))).'</p>\'+'."\n";
										}
										if ($currentmarker->contact_phone != "") 
										{
											$scripttext .= '\'<p class="placemarkBodyContact">'.JText::_('PLG_ZHYANDEXMAP_MAP_USER_CONTACT_PHONE').' '.htmlspecialchars(str_replace('\\', '/', $currentmarker->contact_phone), ENT_QUOTES, 'UTF-8').'</p>\'+'."\n";
										}
										if ($currentmarker->contact_mobile != "") 
										{
											$scripttext .= '\'<p class="placemarkBodyContact">'.JText::_('PLG_ZHYANDEXMAP_MAP_USER_CONTACT_MOBILE').' '.htmlspecialchars(str_replace('\\', '/', $currentmarker->contact_mobile), ENT_QUOTES, 'UTF-8').'</p>\'+'."\n";
										}
										if ($currentmarker->contact_fax != "") 
										{
											$scripttext .= '\'<p class="placemarkBodyContact">'.JText::_('PLG_ZHYANDEXMAP_MAP_USER_CONTACT_FAX').' '.htmlspecialchars(str_replace('\\', '/', $currentmarker->contact_fax), ENT_QUOTES, 'UTF-8').'</p>\'+'."\n";
										}
									break;
									case 2:
										if ($currentmarker->contact_name != "") 
										{
											$scripttext .= '\'<p class="placemarkBodyContact">'.htmlspecialchars(str_replace('\\', '/', $currentmarker->contact_name), ENT_QUOTES, 'UTF-8').'</p>\'+'."\n";
										}
										if ($currentmarker->contact_position != "") 
										{
											$scripttext .= '\'<p class="placemarkBodyContact">'.htmlspecialchars(str_replace('\\', '/', $currentmarker->contact_position), ENT_QUOTES, 'UTF-8').'</p>\'+'."\n";
										}
										if ($currentmarker->contact_address != "") 
										{
											$scripttext .= '\'<p class="placemarkBodyContact"><img src="'.$imgpathUtils.'address.png" alt="'.JText::_('PLG_ZHYANDEXMAP_MAP_USER_CONTACT_ADDRESS').'" />'.str_replace('<br /><br />', '<br />', str_replace(array("\r", "\r\n", "\n"), '<br />', htmlspecialchars(str_replace('\\', '/', $currentmarker->contact_address), ENT_QUOTES, 'UTF-8'))).'</p>\'+'."\n";
										}
										if ($currentmarker->contact_phone != "") 
										{
											$scripttext .= '\'<p class="placemarkBodyContact"><img src="'.$imgpathUtils.'phone.png" alt="'.JText::_('PLG_ZHYANDEXMAP_MAP_USER_CONTACT_PHONE').'" />'.htmlspecialchars(str_replace('\\', '/', $currentmarker->contact_phone), ENT_QUOTES, 'UTF-8').'</p>\'+'."\n";
										}
										if ($currentmarker->contact_mobile != "") 
										{
											$scripttext .= '\'<p class="placemarkBodyContact"><img src="'.$imgpathUtils.'mobile.png" alt="'.JText::_('PLG_ZHYANDEXMAP_MAP_USER_CONTACT_MOBILE').'" />'.htmlspecialchars(str_replace('\\', '/', $currentmarker->contact_mobile), ENT_QUOTES, 'UTF-8').'</p>\'+'."\n";
										}
										if ($currentmarker->contact_fax != "") 
										{
											$scripttext .= '\'<p class="placemarkBodyContact"><img src="'.$imgpathUtils.'fax.png" alt="'.JText::_('PLG_ZHYANDEXMAP_MAP_USER_CONTACT_FAX').'" />'.htmlspecialchars(str_replace('\\', '/', $currentmarker->contact_fax), ENT_QUOTES, 'UTF-8').'</p>\'+'."\n";
										}
									break;
									case 3:
										if ($currentmarker->contact_name != "") 
										{
											$scripttext .= '\'<p class="placemarkBodyContact">'.htmlspecialchars(str_replace('\\', '/', $currentmarker->contact_name), ENT_QUOTES, 'UTF-8').'</p>\'+'."\n";
										}
										if ($currentmarker->contact_position != "") 
										{
											$scripttext .= '\'<p class="placemarkBodyContact">'.htmlspecialchars(str_replace('\\', '/', $currentmarker->contact_position), ENT_QUOTES, 'UTF-8').'</p>\'+'."\n";
										}
										if ($currentmarker->contact_address != "") 
										{
											$scripttext .= '\'<p class="placemarkBodyContact">'.str_replace('<br /><br />', '<br />', str_replace(array("\r", "\r\n", "\n"), '<br />', htmlspecialchars(str_replace('\\', '/', $currentmarker->contact_address), ENT_QUOTES, 'UTF-8'))).'</p>\'+'."\n";
										}
										if ($currentmarker->contact_phone != "") 
										{
											$scripttext .= '\'<p class="placemarkBodyContact">'.htmlspecialchars(str_replace('\\', '/', $currentmarker->contact_phone), ENT_QUOTES, 'UTF-8').'</p>\'+'."\n";
										}
										if ($currentmarker->contact_mobile != "") 
										{
											$scripttext .= '\'<p class="placemarkBodyContact">'.htmlspecialchars(str_replace('\\', '/', $currentmarker->contact_mobile), ENT_QUOTES, 'UTF-8').'</p>\'+'."\n";
										}
										if ($currentmarker->contact_fax != "") 
										{
											$scripttext .= '\'<p class="placemarkBodyContact">'.htmlspecialchars(str_replace('\\', '/', $currentmarker->contact_fax), ENT_QUOTES, 'UTF-8').'</p>\'+'."\n";
										}
									break;
									default:
										if ($currentmarker->contact_name != "") 
										{
											$scripttext .= '\'<p class="placemarkBodyContact">'.htmlspecialchars(str_replace('\\', '/', $currentmarker->contact_name), ENT_QUOTES, 'UTF-8').'</p>\'+'."\n";
										}
										if ($currentmarker->contact_position != "") 
										{
											$scripttext .= '\'<p class="placemarkBodyContact">'.htmlspecialchars(str_replace('\\', '/', $currentmarker->contact_position), ENT_QUOTES, 'UTF-8').'</p>\'+'."\n";
										}
										if ($currentmarker->contact_address != "") 
										{
											$scripttext .= '\'<p class="placemarkBodyContact">'.str_replace('<br /><br />', '<br />', str_replace(array("\r", "\r\n", "\n"), '<br />', htmlspecialchars(str_replace('\\', '/', $currentmarker->contact_address), ENT_QUOTES, 'UTF-8'))).'</p>\'+'."\n";
										}
										if ($currentmarker->contact_phone != "") 
										{
											$scripttext .= '\'<p class="placemarkBodyContact">'.htmlspecialchars(str_replace('\\', '/', $currentmarker->contact_phone), ENT_QUOTES, 'UTF-8').'</p>\'+'."\n";
										}
										if ($currentmarker->contact_mobile != "") 
										{
											$scripttext .= '\'<p class="placemarkBodyContact">'.htmlspecialchars(str_replace('\\', '/', $currentmarker->contact_mobile), ENT_QUOTES, 'UTF-8').'</p>\'+'."\n";
										}
										if ($currentmarker->contact_fax != "") 
										{
											$scripttext .= '\'<p class="placemarkBodyContact">'.htmlspecialchars(str_replace('\\', '/', $currentmarker->contact_fax), ENT_QUOTES, 'UTF-8').'</p>\'+'."\n";
										}
									break;										
								}
							}
						}
						// Contact info - end
						// User info - begin
						if (isset($map->useruser) && ((int)$map->useruser != 0))
						{
							$scripttext .= $this->get_userinfo_for_marker($currentmarker->createdbyuser, $currentmarker->showuser, 
																	$imgpathIcons, $imgpathUtils, $directoryIcons);
						}
						if ($currentmarker->hrefsite!="")
						{
								$scripttext .= '\'<p><a class="placemarkHREF" href="'.$currentmarker->hrefsite.'" target="_blank">';
								if ($currentmarker->hrefsitename != "")
								{
									$scripttext .= htmlspecialchars($currentmarker->hrefsitename, ENT_QUOTES, 'UTF-8');
								}
								else
								{
									$scripttext .= $currentmarker->hrefsite;
								}
						
								$scripttext .= '</a></p>\'+'."\n";
						}

						
				$scripttext .= '\'</div>\';'."\n";
				
				// Action By Click - Begin							
				switch ((int)$currentmarker->actionbyclick)
				{
					// None
					case 0:
						if ((int)$currentmarker->zoombyclick != 100)
						{
							$scripttext .= $markername.'.events.add("click", function (e) {' ."\n";
							$scripttext .= '  map'.$currentArticleId.'.setCenter(latlng'. $currentmarker->id.');' ."\n";
							$scripttext .= '  map'.$currentArticleId.'.setZoom('.(int)$currentmarker->zoombyclick.');' ."\n";
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
							$scripttext .= '  map'.$currentArticleId.'.setCenter(latlng'. $currentmarker->id.');' ."\n";
							$scripttext .= '  map'.$currentArticleId.'.setZoom('.(int)$currentmarker->zoombyclick.');' ."\n";
						}

						// In this API there is no need to fire
						//
						//$scripttext .= '    YMaps.Events.notify('.$markername.', '.$markername.'.Events.BalloonOpen);' ."\n";
						/*
						$scripttext .= $markername.'.events.fire("", new ymaps.Event('."\n";
						$scripttext .= ''.$markername.','."\n";
						$scripttext .= ' true));' ."\n";
						*/
						//$scripttext .= '    '.$markername.'.balloon.open();' ."\n";
						
						$scripttext .= '  });' ."\n";
					break;
					// Link
					case 2:
						if ($currentmarker->hrefsite != "")
						{
							$scripttext .= $markername.'.events.add("click", function (e) {' ."\n";
							if ((int)$currentmarker->zoombyclick != 100)
							{
								$scripttext .= '  map'.$currentArticleId.'.setCenter(latlng'. $currentmarker->id.');' ."\n";
								$scripttext .= '  map'.$currentArticleId.'.setZoom('.(int)$currentmarker->zoombyclick.');' ."\n";
							}
							$scripttext .= '  window.open("'.$currentmarker->hrefsite.'");' ."\n";
							$scripttext .= '  });' ."\n";
						}
						else
						{
							if ((int)$currentmarker->zoombyclick != 100)
							{
								$scripttext .= $markername.'.events.add("click", function (e) {' ."\n";
								$scripttext .= '  map'.$currentArticleId.'.setCenter(latlng'. $currentmarker->id.');' ."\n";
								$scripttext .= '  map'.$currentArticleId.'.setZoom('.(int)$currentmarker->zoombyclick.');' ."\n";
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
								$scripttext .= '  map'.$currentArticleId.'.setCenter(latlng'. $currentmarker->id.');' ."\n";
								$scripttext .= '  map'.$currentArticleId.'.setZoom('.(int)$currentmarker->zoombyclick.');' ."\n";
							}
							$scripttext .= '  window.location = "'.$currentmarker->hrefsite.'";' ."\n";
							$scripttext .= '  });' ."\n";
						}
						else
						{
							if ((int)$currentmarker->zoombyclick != 100)
							{
								$scripttext .= $markername.'.events.add("click", function (e) {' ."\n";
								$scripttext .= '  map'.$currentArticleId.'.setCenter(latlng'. $currentmarker->id.');' ."\n";
								$scripttext .= '  map'.$currentArticleId.'.setZoom('.(int)$currentmarker->zoombyclick.');' ."\n";
								$scripttext .= '  });' ."\n";
							}
						}
					break;
					default:
						$scripttext .= '' ."\n";
					break;
				}
											
				// Action By Click - End

							
				if (isset($map->markercluster) && (int)$map->markercluster == 1)
				{
					if ((isset($map->markerclustergroup) && (int)$map->markerclustergroup == 1))
					{
						$scripttext .= 'clustermarkers'.$currentmarker->markergroup.'.push(placemark'. $currentmarker->id.');' ."\n";
					}
					else
					{
						$scripttext .= 'clustermarkers0.push(placemark'. $currentmarker->id.');' ."\n";
					}
				}
				
				if ((isset($map->markercluster) && ((int)$map->markercluster != 0))
				)
				{
					$scripttext .= $markername.'.properties.set("clusterCaption", contentStringHeadCluster'. $currentmarker->id.');' ."\n";
				}
				else
				{
					if ((isset($map->markercluster) && (int)$map->markercluster == 0)
					  && ((int)$currentmarker->overridemarkericon == 1)
					  && ((int)$currentmarker->publishedgroup == 1)
					   )
					{
						$markergroupname = 'markergroup'. $currentmarker->markergroup;
						$scripttext .= $markergroupname.'.add('.$markername.');'."\n";
					}
					else
					{
						$scripttext .= 'map'.$currentArticleId.'.geoObjects.add('.$markername.');' ."\n";
					}
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
									$scripttext .= '  map'.$currentArticleId.'.setCenter(latlng'. $currentmarker->id.');' ."\n";
									$scripttext .= '  map'.$currentArticleId.'.setZoom('.(int)$currentmarker->zoombyclick.');' ."\n";
								}
							break;
							// Info
							case 1:
								if ((int)$currentmarker->zoombyclick != 100)
								{
									$scripttext .= '  map'.$currentArticleId.'.setCenter(latlng'. $currentmarker->id.');' ."\n";
									$scripttext .= '  map'.$currentArticleId.'.setZoom('.(int)$currentmarker->zoombyclick.');' ."\n";
								}
								// I set it on previous level action by click
								//$scripttext .= $markername.'.properties.set("balloonContentHeader", contentStringHead'. $currentmarker->id.');' ."\n";
								//$scripttext .= $markername.'.properties.set("balloonContentBody", contentStringBody'. $currentmarker->id.');' ."\n";

								// if clusterer is enabled - do not display, because placemark is not on map yet
								if (isset($map->markercluster) && (int)$map->markercluster == 0)
								{
									$scripttext .= '    '.$markername.'.balloon.open();' ."\n";
								}								
							break;
							// Link
							case 2:
								if ($currentmarker->hrefsite != "")
								{
									if ((int)$currentmarker->zoombyclick != 100)
									{
										$scripttext .= '  map'.$currentArticleId.'.setCenter(latlng'. $currentmarker->id.');' ."\n";
										$scripttext .= '  map'.$currentArticleId.'.setZoom('.(int)$currentmarker->zoombyclick.');' ."\n";
									}
									$scripttext .= '  window.open("'.$currentmarker->hrefsite.'");' ."\n";
								}
								else
								{
									if ((int)$currentmarker->zoombyclick != 100)
									{
										$scripttext .= '  map'.$currentArticleId.'.setCenter(latlng'. $currentmarker->id.');' ."\n";
										$scripttext .= '  map'.$currentArticleId.'.setZoom('.(int)$currentmarker->zoombyclick.');' ."\n";
									}
								}
							break;
							// Link in self
							case 3:
								if ($currentmarker->hrefsite != "")
								{
									if ((int)$currentmarker->zoombyclick != 100)
									{
										$scripttext .= '  map'.$currentArticleId.'.setCenter(latlng'. $currentmarker->id.');' ."\n";
										$scripttext .= '  map'.$currentArticleId.'.setZoom('.(int)$currentmarker->zoombyclick.');' ."\n";
									}
									$scripttext .= '  window.location = "'.$currentmarker->hrefsite.'";' ."\n";
								}
								else
								{
									if ((int)$currentmarker->zoombyclick != 100)
									{
										$scripttext .= '  map'.$currentArticleId.'.setCenter(latlng'. $currentmarker->id.');' ."\n";
										$scripttext .= '  map'.$currentArticleId.'.setZoom('.(int)$currentmarker->zoombyclick.');' ."\n";
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
											$scripttext .= ' markerLI.className = "zhym-li-'.$markerlistcssstyle.'";'."\n";
											$scripttext .= ' var markerLIWrp = document.createElement(\'div\');'."\n";
											$scripttext .= ' markerLIWrp.className = "zhym-li-wrp-'.$markerlistcssstyle.'";'."\n";
											$scripttext .= ' var markerASelWrp = document.createElement(\'div\');'."\n";
											$scripttext .= ' markerASelWrp.className = "zhym-li-wrp-a-'.$markerlistcssstyle.'";'."\n";
											$scripttext .= ' var markerASel = document.createElement(\'a\');'."\n";
											$scripttext .= ' markerASel.className = "zhym-li-a-'.$markerlistcssstyle.'";'."\n";
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


											if ((int)$map->markerlistaction == 0) 
											{
												$scripttext .= ' markerASel.onclick = function(){ map'.$currentArticleId.'.setCenter(latlng'. $currentmarker->id.')};'."\n";
											}
											else if ((int)$map->markerlistaction == 1) 
											{
												$scripttext .= ' markerASel.onclick = function(){ ';
												// $scripttext .= 'YMaps.Events.notify('.$markername.', '.$markername.'.Events.Click);';
												// Action By Click - For PlacemarkList - Begin	
												// Because there is a problem with Notify propagation
												
												switch ((int)$currentmarker->actionbyclick)
												{
													// None
													case 0:
														if ((int)$currentmarker->zoombyclick != 100)
														{
															$scripttext .= '  map'.$currentArticleId.'.setCenter(latlng'. $currentmarker->id.');' ."\n";
															$scripttext .= '  map'.$currentArticleId.'.setZoom('.(int)$currentmarker->zoombyclick.');' ."\n";
														}
													break;
													// Info
													case 1:
														if ((int)$currentmarker->zoombyclick != 100)
														{
															$scripttext .= '  map'.$currentArticleId.'.setCenter(latlng'. $currentmarker->id.');' ."\n";
															$scripttext .= '  map'.$currentArticleId.'.setZoom('.(int)$currentmarker->zoombyclick.');' ."\n";
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
																$scripttext .= '  map'.$currentArticleId.'.setCenter(latlng'. $currentmarker->id.');' ."\n";
																$scripttext .= '  map'.$currentArticleId.'.setZoom('.(int)$currentmarker->zoombyclick.');' ."\n";
															}
															$scripttext .= '  window.open("'.$currentmarker->hrefsite.'");' ."\n";
														}
														else
														{
															if ((int)$currentmarker->zoombyclick != 100)
															{
																$scripttext .= '  map'.$currentArticleId.'.setCenter(latlng'. $currentmarker->id.');' ."\n";
																$scripttext .= '  map'.$currentArticleId.'.setZoom('.(int)$currentmarker->zoombyclick.');' ."\n";
															}
														}
													break;
													// Link in self
													case 3:
														if ($currentmarker->hrefsite != "")
														{
															if ((int)$currentmarker->zoombyclick != 100)
															{
																$scripttext .= '  map'.$currentArticleId.'.setCenter(latlng'. $currentmarker->id.');' ."\n";
																$scripttext .= '  map'.$currentArticleId.'.setZoom('.(int)$currentmarker->zoombyclick.');' ."\n";
															}
															$scripttext .= '  window.location = "'.$currentmarker->hrefsite.'";' ."\n";
														}
														else
														{
															if ((int)$currentmarker->zoombyclick != 100)
															{
																$scripttext .= '  map'.$currentArticleId.'.setCenter(latlng'. $currentmarker->id.');' ."\n";
																$scripttext .= '  map'.$currentArticleId.'.setZoom('.(int)$currentmarker->zoombyclick.');' ."\n";
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
												$scripttext .= ' markerASel.onclick = function(){ map'.$currentArticleId.'.setCenter(latlng'. $currentmarker->id.');};'."\n";
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
											$scripttext .= ' markerLI.className = "zhym-li-table-tr-'.$markerlistcssstyle.'";'."\n";
											$scripttext .= ' var markerLI_C1 = document.createElement(\'td\');'."\n";
											$scripttext .= ' markerLI_C1.className = "zhym-li-table-c1-'.$markerlistcssstyle.'";'."\n";
											$scripttext .= ' var markerASelWrp = document.createElement(\'div\');'."\n";
											$scripttext .= ' markerASelWrp.className = "zhym-li-table-a-wrp-'.$markerlistcssstyle.'";'."\n";
											$scripttext .= ' var markerASel = document.createElement(\'a\');'."\n";
											$scripttext .= ' markerASel.className = "zhym-li-table-a-'.$markerlistcssstyle.'";'."\n";
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
											
											if ((int)$map->markerlistaction == 0) 
											{
												$scripttext .= ' markerASel.onclick = function(){ map'.$currentArticleId.'.setCenter(latlng'. $currentmarker->id.')};'."\n";
											}
											else if ((int)$map->markerlistaction == 1) 
											{
												$scripttext .= ' markerASel.onclick = function(){ ';
												// $scripttext .= 'YMaps.Events.notify('.$markername.', '.$markername.'.Events.Click);';
												// Action By Click - For PlacemarkList - Begin	
												// Because there is a problem with Notify propagation
												
												switch ((int)$currentmarker->actionbyclick)
												{
													// None
													case 0:
														if ((int)$currentmarker->zoombyclick != 100)
														{
															$scripttext .= '  map'.$currentArticleId.'.setCenter(latlng'. $currentmarker->id.');' ."\n";
															$scripttext .= '  map'.$currentArticleId.'.setZoom('.(int)$currentmarker->zoombyclick.');' ."\n";
														}
													break;
													// Info
													case 1:
														if ((int)$currentmarker->zoombyclick != 100)
														{
															$scripttext .= '  map'.$currentArticleId.'.setCenter(latlng'. $currentmarker->id.');' ."\n";
															$scripttext .= '  map'.$currentArticleId.'.setZoom('.(int)$currentmarker->zoombyclick.');' ."\n";
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
																$scripttext .= '  map'.$currentArticleId.'.setCenter(latlng'. $currentmarker->id.');' ."\n";
																$scripttext .= '  map'.$currentArticleId.'.setZoom('.(int)$currentmarker->zoombyclick.');' ."\n";
															}
															$scripttext .= '  window.open("'.$currentmarker->hrefsite.'");' ."\n";
														}
														else
														{
															if ((int)$currentmarker->zoombyclick != 100)
															{
																$scripttext .= '  map'.$currentArticleId.'.setCenter(latlng'. $currentmarker->id.');' ."\n";
																$scripttext .= '  map'.$currentArticleId.'.setZoom('.(int)$currentmarker->zoombyclick.');' ."\n";
															}
														}
													break;
													// Link in self
													case 3:
														if ($currentmarker->hrefsite != "")
														{
															if ((int)$currentmarker->zoombyclick != 100)
															{
																$scripttext .= '  map'.$currentArticleId.'.setCenter(latlng'. $currentmarker->id.');' ."\n";
																$scripttext .= '  map'.$currentArticleId.'.setZoom('.(int)$currentmarker->zoombyclick.');' ."\n";
															}
															$scripttext .= '  window.location = "'.$currentmarker->hrefsite.'";' ."\n";
														}
														else
														{
															if ((int)$currentmarker->zoombyclick != 100)
															{
																$scripttext .= '  map'.$currentArticleId.'.setCenter(latlng'. $currentmarker->id.');' ."\n";
																$scripttext .= '  map'.$currentArticleId.'.setZoom('.(int)$currentmarker->zoombyclick.');' ."\n";
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
												$scripttext .= ' markerASel.onclick = function(){ map'.$currentArticleId.'.setCenter(latlng'. $currentmarker->id.');};'."\n";
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
					
				
			}
		}
	}

	 if ((isset($map->markercluster) && (int)$map->markercluster == 1))
	 {      
		$scripttext .= 'markerCluster0.add(clustermarkers0);' ."\n";
		 
		if ((isset($map->markerclustergroup) && (int)$map->markerclustergroup == 1))
		{
			if (isset($markergroups) && !empty($markergroups)) 
			{
				foreach ($markergroups as $key => $currentmarkergroup) 
				{
					if (((int)$currentmarkergroup->published == 1))
					{
						//if ((int)$currentmarkergroup->activeincluster == 1)
						//{
								$scripttext .= 'callClusterFill'.$currentArticleId.'('.$currentmarkergroup->id.');' ."\n";
						//}
					}
				}
			}
		}
		else
		{
			if (isset($markergroups) && !empty($markergroups)) 
			{
				foreach ($markergroups as $key => $currentmarkergroup) 
				{
					if (((int)$currentmarkergroup->published == 1))
					{
						//if ((int)$currentmarkergroup->activeincluster == 1)
						//{
								$scripttext .= 'markerCluster0.add(clustermarkers'.$currentmarkergroup->id.');' ."\n";
						//}
					}
				}
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
			$routername ='';
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
					$scripttext .= '     map'.$currentArticleId.'.geoObjects.add('.$routername.');'."\n";

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
						$scripttext .= '        	 moveList += segCounter + \' '.JText::_('PLG_ZHYANDEXMAP_MAP_FIND_GEOCODING_START_POINT').'</br>\';'."\n";
						$scripttext .= '         }'."\n";
						$scripttext .= '         else'."\n";
						$scripttext .= '         {'."\n";
						$scripttext .= '         	moveList += segCounter + \' '.JText::_('PLG_ZHYANDEXMAP_MAP_FIND_GEOCODING_WAY_POINT').'</br>\';'."\n";
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
						$scripttext .= JText::_('PLG_ZHYANDEXMAP_MAPROUTER_DETAIL_SHOWPANEL_HDR_TOTAL_KM');
						$scripttext .= ' \'+ total_km + \' ';
						//$scripttext .= JText::_('PLG_ZHYANDEXMAP_MAPROUTER_DETAIL_SHOWPANEL_HDR_KM');
						$scripttext .= ', ';
						$scripttext .= JText::_('PLG_ZHYANDEXMAP_MAPROUTER_DETAIL_SHOWPANEL_HDR_TOTAL_TIME');
						if (isset($currentrouter->checktraffic) && (int)$currentrouter->checktraffic == 1)
						{
							$scripttext .= ' '.JText::_('PLG_ZHYANDEXMAP_MAPROUTER_DETAIL_SHOWPANEL_HDR_JAM');
						}
						$scripttext .= ' \' + total_time;';
						//$scripttext .= JText::_('PLG_ZHYANDEXMAP_MAPROUTER_DETAIL_SHOWPANEL_HDR_TIME');
						$scripttext .= '         moveList += \'</td>\''."\n";
						$scripttext .= '         moveList += \'</tr>\''."\n";
						
						$scripttext .= '       for (var i = 0; i < segments.length; i++) {'."\n";


						$scripttext .= '         moveList += \'<tr class="zhym-route-table-tr-step">\''."\n";
						$scripttext .= '         var street = segments[i].getStreet();'."\n";
						$scripttext .= '         moveList += \'<td class="zhym-route-table-td">\''."\n";
						$scripttext .= '         moveList += \''.JText::_('PLG_ZHYANDEXMAP_MAP_FIND_GEOCODING_MOVE').' <b>\' + segments[i].getHumanAction() + \'</b>\'+(street ? \' '.JText::_('PLG_ZHYANDEXMAP_MAP_FIND_GEOCODING_ON').' <b>\' + street + \'</b>\': \'\');'."\n";
						$scripttext .= '         moveList += \'</td>\''."\n";
						$scripttext .= '         moveList += \'<td class="zhym-route-table-td">\''."\n";
						$scripttext .= '         segmentlength = segments[i].getLength();'."\n";
						$scripttext .= '         if (segmentlength > 500)'."\n";
						$scripttext .= '         {'."\n";
						$scripttext .= '            segmentlength = segmentlength/1000.;'."\n";
						$scripttext .= '         	moveList += segmentlength.toFixed(1) + \' '.JText::_('PLG_ZHYANDEXMAP_MAP_FIND_GEOCODING_KILOMETERS').'\';'."\n";
						$scripttext .= '         }'."\n";
						$scripttext .= '         else'."\n";
						$scripttext .= '         {'."\n";
						$scripttext .= '         	moveList += segmentlength.toFixed(0) + \' '.JText::_('PLG_ZHYANDEXMAP_MAP_FIND_GEOCODING_METERS').'\';'."\n";
						$scripttext .= '         }'."\n";
						$scripttext .= '         moveList += \'</td>\''."\n";
						$scripttext .= '         moveList += \'</tr>\''."\n";
						$scripttext .= '       }'."\n";
						$scripttext .= '     }'."\n";
						$scripttext .= '         moveList += \'<tr class="zhym-route-table-tr">\''."\n";
						$scripttext .= '         moveList += \'<td class="zhym-route-table-td-waypoint" colspan="2">\''."\n";
						$scripttext .= '         segCounter += 1;'."\n";
						$scripttext .= '      moveList += segCounter + \' '.JText::_('PLG_ZHYANDEXMAP_MAP_FIND_GEOCODING_END_POINT').'\';'."\n";
						$scripttext .= '         moveList += \'</td>\''."\n";
						$scripttext .= '         moveList += \'</tr>\''."\n";
						$scripttext .= '      moveList += \'</tbody>\';'."\n";
						$scripttext .= '      moveList += \'</table>\';'."\n";
						$scripttext .= '  document.getElementById("YMapsRoutePanel_Steps'.$currentArticleId.'").innerHTML = \'\'+moveList+\'\';' ."\n";


						$scripttext .= ' var total_km = '.$routername.'.getHumanLength();'."\n";
						if (isset($currentrouter->checktraffic) && (int)$currentrouter->checktraffic == 1)
						{
							$scripttext .= ' var total_time = '.$routername.'.getHumanJamsTime();'."\n";
						}
						else
						{
							$scripttext .= ' var total_time = '.$routername.'.getHumanTime();'."\n";
						}

						$scripttext .= '  document.getElementById("YMapsRoutePanel_Total'.$currentArticleId.'").innerHTML = "<p>';
						$scripttext .= JText::_('PLG_ZHYANDEXMAP_MAPROUTER_DETAIL_SHOWPANEL_HDR_TOTAL_KM');
						$scripttext .= ' " + total_km + " ';
						//$scripttext .= JText::_('PLG_ZHYANDEXMAP_MAPROUTER_DETAIL_SHOWPANEL_HDR_KM');
						$scripttext .= ', ';
						$scripttext .= JText::_('PLG_ZHYANDEXMAP_MAPROUTER_DETAIL_SHOWPANEL_HDR_TOTAL_TIME');
						if (isset($currentrouter->checktraffic) && (int)$currentrouter->checktraffic == 1)
						{
							$scripttext .= ' '.JText::_('PLG_ZHYANDEXMAP_MAPROUTER_DETAIL_SHOWPANEL_HDR_JAM');
						}
						$scripttext .= ' " + total_time + " ';
						//$scripttext .= JText::_('PLG_ZHYANDEXMAP_MAPROUTER_DETAIL_SHOWPANEL_HDR_TIME');
						$scripttext .= '</p>";' ."\n";
						
					}

					$scripttext .= '  }, '."\n";
					$scripttext .= '  function('.$routererror.'){'."\n";
					$scripttext .= '     alert(\''.JText::_('PLG_ZHYANDEXMAP_MAP_ERROR_GEOCODING').'\' + '.$routererror.'.message);'."\n";
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
				$router2name ='';
				$router2name = 'routeByMarker'. $currentrouter->id;
				$router2error = 'routeByMarkerError'. $currentrouter->id;
				
				$cs = explode(";", $currentrouter->routebymarker);
				$cs_total = count($cs)-1;
				$cs_idx = 0;
				$wp_list = '';
				$skipRouteCreation = 0;
				foreach($cs as $curroute)
				{	
					$currouteLatLng = $this->parse_route_by_markers($curroute);
					//$scripttext .= 'alert("'.$currouteLatLng.'");'."\n";

					if ($currouteLatLng != "")
					{
						if ($currouteLatLng == "geocode")
						{
							$scripttext .= 'alert(\''.JText::_('PLG_ZHYANDEXMAP_MAPROUTER_FINDMARKER_ERROR_GEOCODE').' '.$curroute.'\');'."\n";
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
						$scripttext .= 'alert(\''.JText::_('PLG_ZHYANDEXMAP_MAPROUTER_FINDMARKER_ERROR_REASON').' '.$curroute.'\');'."\n";
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
					$scripttext .= '     map'.$currentArticleId.'.geoObjects.add('.$router2name.');'."\n";

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
						$scripttext .= '        	 moveList += segCounter + \' '.JText::_('PLG_ZHYANDEXMAP_MAP_FIND_GEOCODING_START_POINT').'</br>\';'."\n";
						$scripttext .= '         }'."\n";
						$scripttext .= '         else'."\n";
						$scripttext .= '         {'."\n";
						$scripttext .= '         	moveList += segCounter + \' '.JText::_('PLG_ZHYANDEXMAP_MAP_FIND_GEOCODING_WAY_POINT').'</br>\';'."\n";
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
						$scripttext .= JText::_('PLG_ZHYANDEXMAP_MAPROUTER_DETAIL_SHOWPANEL_HDR_TOTAL_KM');
						$scripttext .= ' \'+ total_km + \' ';
						//$scripttext .= JText::_('PLG_ZHYANDEXMAP_MAPROUTER_DETAIL_SHOWPANEL_HDR_KM');
						$scripttext .= ', ';
						$scripttext .= JText::_('PLG_ZHYANDEXMAP_MAPROUTER_DETAIL_SHOWPANEL_HDR_TOTAL_TIME');
						if (isset($currentrouter->checktraffic) && (int)$currentrouter->checktraffic == 1)
						{
							$scripttext .= ' '.JText::_('PLG_ZHYANDEXMAP_MAPROUTER_DETAIL_SHOWPANEL_HDR_JAM');
						}
						$scripttext .= ' \' + total_time;';
						//$scripttext .= JText::_('PLG_ZHYANDEXMAP_MAPROUTER_DETAIL_SHOWPANEL_HDR_TIME');
						$scripttext .= '         moveList += \'</td>\''."\n";
						$scripttext .= '         moveList += \'</tr>\''."\n";
						
						$scripttext .= '       for (var i = 0; i < segments.length; i++) {'."\n";


						$scripttext .= '         moveList += \'<tr class="zhym-route-table-tr-step">\''."\n";
						$scripttext .= '         var street = segments[i].getStreet();'."\n";
						$scripttext .= '         moveList += \'<td class="zhym-route-table-td">\''."\n";
						$scripttext .= '         moveList += \''.JText::_('PLG_ZHYANDEXMAP_MAP_FIND_GEOCODING_MOVE').' <b>\' + segments[i].getHumanAction() + \'</b>\'+(street ? \' '.JText::_('PLG_ZHYANDEXMAP_MAP_FIND_GEOCODING_ON').' <b>\' + street + \'</b>\': \'\');'."\n";
						$scripttext .= '         moveList += \'</td>\''."\n";
						$scripttext .= '         moveList += \'<td class="zhym-route-table-td">\''."\n";
						$scripttext .= '         segmentlength = segments[i].getLength();'."\n";
						$scripttext .= '         if (segmentlength > 500)'."\n";
						$scripttext .= '         {'."\n";
						$scripttext .= '            segmentlength = segmentlength/1000.;'."\n";
						$scripttext .= '         	moveList += segmentlength.toFixed(1) + \' '.JText::_('PLG_ZHYANDEXMAP_MAP_FIND_GEOCODING_KILOMETERS').'\';'."\n";
						$scripttext .= '         }'."\n";
						$scripttext .= '         else'."\n";
						$scripttext .= '         {'."\n";
						$scripttext .= '         	moveList += segmentlength.toFixed(0) + \' '.JText::_('PLG_ZHYANDEXMAP_MAP_FIND_GEOCODING_METERS').'\';'."\n";
						$scripttext .= '         }'."\n";
						$scripttext .= '         moveList += \'</td>\''."\n";
						$scripttext .= '         moveList += \'</tr>\''."\n";
						$scripttext .= '       }'."\n";
						$scripttext .= '     }'."\n";
						$scripttext .= '         moveList += \'<tr class="zhym-route-table-tr">\''."\n";
						$scripttext .= '         moveList += \'<td class="zhym-route-table-td-waypoint" colspan="2">\''."\n";
						$scripttext .= '         segCounter += 1;'."\n";
						$scripttext .= '      moveList += segCounter + \' '.JText::_('PLG_ZHYANDEXMAP_MAP_FIND_GEOCODING_END_POINT').'\';'."\n";
						$scripttext .= '         moveList += \'</td>\''."\n";
						$scripttext .= '         moveList += \'</tr>\''."\n";
						$scripttext .= '      moveList += \'</tbody>\';'."\n";
						$scripttext .= '      moveList += \'</table>\';'."\n";
						$scripttext .= '  document.getElementById("YMapsRoutePanel_Steps'.$currentArticleId.'").innerHTML = \'\'+moveList+\'\';' ."\n";


						$scripttext .= ' var total_km = '.$router2name.'.getHumanLength();'."\n";
						if (isset($currentrouter->checktraffic) && (int)$currentrouter->checktraffic == 1)
						{
							$scripttext .= ' var total_time = '.$router2name.'.getHumanJamsTime();'."\n";
						}
						else
						{
							$scripttext .= ' var total_time = '.$router2name.'.getHumanTime();'."\n";
						}

						$scripttext .= '  document.getElementById("YMapsRoutePanel_Total'.$currentArticleId.'").innerHTML = "<p>';
						$scripttext .= JText::_('PLG_ZHYANDEXMAP_MAPROUTER_DETAIL_SHOWPANEL_HDR_TOTAL_KM');
						$scripttext .= ' " + total_km + " ';
						//$scripttext .= JText::_('PLG_ZHYANDEXMAP_MAPROUTER_DETAIL_SHOWPANEL_HDR_KM');
						$scripttext .= ', ';
						$scripttext .= JText::_('PLG_ZHYANDEXMAP_MAPROUTER_DETAIL_SHOWPANEL_HDR_TOTAL_TIME');
						if (isset($currentrouter->checktraffic) && (int)$currentrouter->checktraffic == 1)
						{
							$scripttext .= ' '.JText::_('PLG_ZHYANDEXMAP_MAPROUTER_DETAIL_SHOWPANEL_HDR_JAM');
						}
						$scripttext .= ' " + total_time + " ';
						//$scripttext .= JText::_('PLG_ZHYANDEXMAP_MAPROUTER_DETAIL_SHOWPANEL_HDR_TIME');
						$scripttext .= '</p>";' ."\n";

					}

					$scripttext .= '  }, '."\n";
					$scripttext .= '  function('.$router2error.'){'."\n";
					$scripttext .= '     alert(\''.JText::_('PLG_ZHYANDEXMAP_MAP_ERROR_GEOCODING').'\' + '.$router2error.'.message);'."\n";
					$scripttext .= '  }'."\n";
					$scripttext .= ');'."\n";
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
				$scripttext .= '		map'.$currentArticleId.'.geoObjects.add('.$kml1.'.geoObjects);' ."\n";
				$scripttext .= '		if ('.$kml1.'.mapState) ' ."\n";
				$scripttext .= '		{' ."\n";
				$scripttext .= '			'.$kml1.'.mapState.applyToMap(map'.$currentArticleId.');' ."\n";
				$scripttext .= '		}' ."\n";
				$scripttext .= '	},' ."\n";
				$scripttext .= '	function(error) {' ."\n";
				$scripttext .= '    alert(\''.JText::_('PLG_ZHYANDEXMAP_MAP_ERROR_YMAPSML').'\' + error.message);' ."\n";
				$scripttext .= '	}' ."\n";
				$scripttext .= ');	' ."\n";
				
			}

			if ($currentrouter->kmllayerkml != "")
			{
				$kml2 = 'KML'.$routername;
				$scripttext .= 'ymaps.geoXml.load(\''.$currentrouter->kmllayerkml.'\').then(' ."\n";
				$scripttext .= '	function('.$kml2.') {' ."\n";
				$scripttext .= '		map'.$currentArticleId.'.geoObjects.add('.$kml2.'.geoObjects);' ."\n";
				$scripttext .= '		if ('.$kml2.'.mapState) ' ."\n";
				$scripttext .= '		{' ."\n";
				$scripttext .= '			'.$kml2.'.mapState.applyToMap(map'.$currentArticleId.');' ."\n";
				$scripttext .= '		}' ."\n";
				$scripttext .= '	},' ."\n";
				$scripttext .= '	function(error) {' ."\n";
				$scripttext .= '    alert(\''.JText::_('PLG_ZHYANDEXMAP_MAP_ERROR_KML').'\' + error.message);' ."\n";
				$scripttext .= '	}' ."\n";
				$scripttext .= ');	' ."\n";
			}

			if ($currentrouter->kmllayergpx != "")
			{
				$kml3 = 'GPX'.$routername;
				$scripttext .= 'ymaps.geoXml.load(\''.$currentrouter->kmllayergpx.'\').then(' ."\n";
				$scripttext .= '	function('.$kml3.') {' ."\n";
				$scripttext .= '		map'.$currentArticleId.'.geoObjects.add('.$kml3.'.geoObjects);' ."\n";
				$scripttext .= '		if ('.$kml3.'.mapState) ' ."\n";
				$scripttext .= '		{' ."\n";
				$scripttext .= '			'.$kml3.'.mapState.applyToMap(map'.$currentArticleId.');' ."\n";
				$scripttext .= '		}' ."\n";
				$scripttext .= '	},' ."\n";
				$scripttext .= '	function(error) {' ."\n";
				$scripttext .= '    alert(\''.JText::_('PLG_ZHYANDEXMAP_MAP_ERROR_GPX').'\' + error.message);' ."\n";
				$scripttext .= '	}' ."\n";
				$scripttext .= ');	' ."\n";
			}
			
		}
		// End for each Route
		
		if ($routepanelcount > 1 || $routepanelcount == 0 || $routepaneltotalcount == 0)
		{
			$scripttext .= 'var toHideRouteDiv = document.getElementById("YMapsRoutePanel_Total'.$currentArticleId.'");' ."\n";
			$scripttext .= 'toHideRouteDiv.style.display = "none";' ."\n";
			//$scripttext .= 'alert("Hide because > 1 or = 0");';
		}

		if ($routeHTMLdescription != "")
		{
			$scripttext .= '  document.getElementById("YMapsRoutePanel_Description'. $currentArticleId.'").innerHTML =  "<p>'. $routeHTMLdescription .'</p>";'."\n";
		}
		
	}


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
					$scripttext .= '\'<h1 id="headPathContent'. $currentpath->id.'" class="pathHead">'.htmlspecialchars(str_replace('\\', '/', $currentpath->title), ENT_QUOTES, 'UTF-8').'</h1>\'+' ."\n";
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
				
				$scripttext .= 'map'.$currentArticleId.'.geoObjects.add('.$curpathname.');'."\n";
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

				$scripttext .= 'map'.$currentArticleId.'.geoObjects.add('.$curpathname.');'."\n";
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
						
						$scripttext .= 'map'.$currentArticleId.'.geoObjects.add('.$curpathname.');'."\n";
					}
				}
			}
			
		}
	}


	$context_suffix = 'map';

	if ($map->kmllayer != "")
	{
		$kml1 = 'YMapsML'.$context_suffix;
		$scripttext .= 'ymaps.geoXml.load(\''.$map->kmllayer.'\').then(' ."\n";
		$scripttext .= '	function('.$kml1.') {' ."\n";
		$scripttext .= '		map'.$currentArticleId.'.geoObjects.add('.$kml1.'.geoObjects);' ."\n";
		$scripttext .= '		if ('.$kml1.'.mapState) ' ."\n";
		$scripttext .= '		{' ."\n";
		$scripttext .= '			'.$kml1.'.mapState.applyToMap(map'.$currentArticleId.');' ."\n";
		$scripttext .= '		}' ."\n";
		$scripttext .= '	},' ."\n";
		$scripttext .= '	function(error) {' ."\n";
		$scripttext .= '    alert(\''.JText::_('PLG_ZHYANDEXMAP_MAP_ERROR_YMAPSML').'\' + error.message);' ."\n";
		$scripttext .= '	}' ."\n";
		$scripttext .= ');	' ."\n";
		
	}

	if ($map->kmllayerkml != "")
	{
		$kml2 = 'KML'.$context_suffix;
		$scripttext .= 'ymaps.geoXml.load(\''.$map->kmllayerkml.'\').then(' ."\n";
		$scripttext .= '	function('.$kml2.') {' ."\n";
		$scripttext .= '		map'.$currentArticleId.'.geoObjects.add('.$kml2.'.geoObjects);' ."\n";
		$scripttext .= '		if ('.$kml2.'.mapState) ' ."\n";
		$scripttext .= '		{' ."\n";
		$scripttext .= '			'.$kml2.'.mapState.applyToMap(map'.$currentArticleId.');' ."\n";
		$scripttext .= '		}' ."\n";
		$scripttext .= '	},' ."\n";
		$scripttext .= '	function(error) {' ."\n";
		$scripttext .= '    alert(\''.JText::_('PLG_ZHYANDEXMAP_MAP_ERROR_KML').'\' + error.message);' ."\n";
		$scripttext .= '	}' ."\n";
		$scripttext .= ');	' ."\n";
	}

	if ($map->kmllayergpx != "")
	{
		$kml3 = 'GPX'.$context_suffix;
		$scripttext .= 'ymaps.geoXml.load(\''.$map->kmllayergpx.'\').then(' ."\n";
		$scripttext .= '	function('.$kml3.') {' ."\n";
		$scripttext .= '		map'.$currentArticleId.'.geoObjects.add('.$kml3.'.geoObjects);' ."\n";
		$scripttext .= '		if ('.$kml3.'.mapState) ' ."\n";
		$scripttext .= '		{' ."\n";
		$scripttext .= '			'.$kml3.'.mapState.applyToMap(map'.$currentArticleId.');' ."\n";
		$scripttext .= '		}' ."\n";
		$scripttext .= '	},' ."\n";
		$scripttext .= '	function(error) {' ."\n";
		$scripttext .= '    alert(\''.JText::_('PLG_ZHYANDEXMAP_MAP_ERROR_GPX').'\' + error.message);' ."\n";
		$scripttext .= '	}' ."\n";
		$scripttext .= ');	' ."\n";
	}

	if ((isset($map->autoposition) && (int)$map->autoposition == 1))
	{
			$scripttext .= 'findMyPosition'.$currentArticleId.'("Map");' ."\n";
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
				$scripttext .= '	var toShowDiv = document.getElementById("YMapsMarkerList'.$currentArticleId.'");' ."\n";
				$scripttext .= '	toShowDiv.style.display = "block";' ."\n";
			}
			else
			{
				switch ($map->markerlistbuttontype) 
				{
					case 0:
						$scripttext .= '	var toShowDiv = document.getElementById("YMapsMarkerList'.$currentArticleId.'");' ."\n";
						$scripttext .= '	toShowDiv.style.display = "block";' ."\n";
					break;
					case 1:
						$scripttext .= '';
					break;
					case 2:
						$scripttext .= '';
					break;
					case 11:
						$scripttext .= 'btnPlacemarkList'.$currentArticleId.'.events.fire("click", new ymaps.Event({'."\n";
						$scripttext .= ' target: btnPlacemarkList'."\n";
						$scripttext .= '}, true));' ."\n";
						
					break;
					case 12:
						$scripttext .= 'btnPlacemarkList'.$currentArticleId.'.events.fire("click", new ymaps.Event({'."\n";
						$scripttext .= ' target: btnPlacemarkList'."\n";
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

	
	$scripttext .= '};' ."\n";
// End initialize jquery function


	// Geo Position - Begin
	if ((isset($map->autoposition) && (int)$map->autoposition == 1)
	 || (isset($map->autopositioncontrol) && (int)$map->autopositioncontrol != 0))
	{
			$scripttext .= 'function findMyPosition'.$currentArticleId.'(AutoPosition) {' ."\n";
			$scripttext .= '     if (AutoPosition == "Button")' ."\n";
			$scripttext .= '     {' ."\n";
        	$scripttext .= '        if (ymaps.geolocation) ' ."\n";
			$scripttext .= '        {' ."\n";
	        $scripttext .= '        	p_center = [ymaps.geolocation.longitude, ymaps.geolocation.latitude];' ."\n";
			if (isset($map->findroute) && (int)$map->findroute == 1) 
			{
				$scripttext .= '    		getMyMapRoute'.$currentArticleId.'(p_center);' ."\n";
			}
			else
			{
				$scripttext .= '    		map'.$currentArticleId.'.setCenter(p_center);' ."\n";
			}
			//$scripttext .= '        	alert("Find");';
        	$scripttext .= '        } ' ."\n";
			$scripttext .= '        else ' ."\n";
			$scripttext .= '        {' ."\n";
			//$scripttext .= '        	alert("Not find");';
	        $scripttext .= '    	}' ."\n";
			$scripttext .= '     }' ."\n";
			$scripttext .= '     else' ."\n";
			$scripttext .= '     {' ."\n";
        	$scripttext .= '        if (ymaps.geolocation) ' ."\n";
			$scripttext .= '        {' ."\n";
	        $scripttext .= '        	p_center = [ymaps.geolocation.longitude, ymaps.geolocation.latitude];' ."\n";
	        $scripttext .= '    		map'.$currentArticleId.'.setCenter(p_center);' ."\n";
			//$scripttext .= '        	alert("Find");';
        	$scripttext .= '        } ' ."\n";
			$scripttext .= '        else ' ."\n";
			$scripttext .= '        {' ."\n";
			//$scripttext .= '        	alert("Not find");';
	        $scripttext .= '    	}' ."\n";
			$scripttext .= '     }' ."\n";
			$scripttext .= '}' ."\n";
	}
	
	// Find option Begin
	if (isset($map->findcontrol) && (int)$map->findcontrol == 1) 
	{
			$scripttext .= 'function showAddressByGeocoding'.$currentArticleId.'(value) {' ."\n";
        // Delete Previous Result
		$scripttext .= '  if (geoResult'.$currentArticleId.')' ."\n";
		$scripttext .= '  {' ."\n";
        $scripttext .= '    map'.$currentArticleId.'.geoObjects.remove(geoResult'.$currentArticleId.');' ."\n";
		$scripttext .= '  }' ."\n";

        // Geocoding
		$scripttext .= '   if ((map'.$currentArticleId.'.getType() == "yandex#publicMap") || (map'.$currentArticleId.'.getType() == "yandex#publicMapHybrid"))';
		$scripttext .= '   {';
        $scripttext .= '     var geocoderOpts'.$currentArticleId.' = {results: 1, boundedBy: map'.$currentArticleId.'.getBounds(), provider:"yandex#publicMap"};' ."\n";
		$scripttext .= '   }';
		$scripttext .= '   else';
		$scripttext .= '   {';
        $scripttext .= '     var geocoderOpts'.$currentArticleId.' = {results: 1, boundedBy: map'.$currentArticleId.'.getBounds()};' ."\n";
		$scripttext .= '   }';
        $scripttext .= '   ymaps.geocode(value, geocoderOpts'.$currentArticleId.').then(function (res) {' ."\n";
        // if find then add to map
        // set center map
		$scripttext .= '     cnt = res.geoObjects.getLength();'."\n";
        $scripttext .= '        if (cnt > 0) ' ."\n";
		$scripttext .= '		{' ."\n";
        $scripttext .= '            geoResult'.$currentArticleId.' = res.geoObjects.get(0);' ."\n";
		$scripttext .= '     		geoResult'.$currentArticleId.'.properties.set(\'balloonContentHeader\', \'\');'."\n";
		$scripttext .= '    		geoResult'.$currentArticleId.'.properties.set(\'balloonContentBody\', \'\');'."\n";
        $scripttext .= '            map'.$currentArticleId.'.geoObjects.add(geoResult'.$currentArticleId.');' ."\n";
        $scripttext .= '            map'.$currentArticleId.'.setCenter(geoResult'.$currentArticleId.'.geometry.getCoordinates());' ."\n";
		// add route
		if (isset($map->findroute) && (int)$map->findroute == 1) 
		{
			$scripttext .= '            getMyMapRoute'.$currentArticleId.'(geoResult'.$currentArticleId.'.geometry.getCoordinates()); '."\n";
		}
		// end add route
        $scripttext .= '        }' ."\n";
		$scripttext .= '		else ' ."\n";
		$scripttext .= '		{' ."\n";
        $scripttext .= '            alert("'.JText::_('PLG_ZHYANDEXMAP_MAP_ERROR_FIND_GEOCODING').'");' ."\n";
        $scripttext .= '        }' ."\n";
        $scripttext .= '    },' ."\n";

        // Failure geocoding
        $scripttext .= '    function (err) {' ."\n";
        $scripttext .= '        alert("'.JText::_('PLG_ZHYANDEXMAP_MAP_ERROR_FIND_GEOCODING_ERROR').'" + err.message);' ."\n";
        $scripttext .= '    });' ."\n";
        $scripttext .= '};' ."\n";
	}
	// Find option End

	// Hidden Container
	if ($hiddenContainer != "")
	{
		$scripttext .= 'function containerShowHide'.$currentArticleId.'() {' ."\n";

		$scripttext .= '  var toShowDiv = document.getElementById("YMWrap'.$mapDivTag.'");' ."\n";
		$scripttext .= '  if (toShowDiv.style.display == "block" )' ."\n";
		$scripttext .= '  {' ."\n";
		$scripttext .= '	toShowDiv.style.display = "none";' ."\n";
		$scripttext .= '  }' ."\n";
		$scripttext .= '  else' ."\n";
		$scripttext .= '  {' ."\n";
		$scripttext .= '	toShowDiv.style.display = "block";' ."\n";
		$scripttext .= '  }' ."\n";
		$scripttext .= '  map'.$currentArticleId.'.container.fitToViewport(); ' ."\n";
		$scripttext .= '  return false;' ."\n";
		$scripttext .= '};' ."\n";
	}

	
		if (isset($map->findroute) && (int)$map->findroute == 1) 
		{
		
			$scripttext .= 'function getMyMapRoute'.$currentArticleId.'(curposition) {'."\n";
			$scripttext .= '  if (geoRoute'.$currentArticleId.')' ."\n";
			$scripttext .= '  {' ."\n";
			$scripttext .= '	map'.$currentArticleId.'.geoObjects.remove(geoRoute'.$currentArticleId.');' ."\n";
			$scripttext .= '  }' ."\n";
			
			$scripttext .= '  ymaps.route([curposition, mapcenter'.$currentArticleId.'],'."\n";
			$scripttext .= '       { mapStateAutoApply: true }'."\n";
			$scripttext .= '  ).then('."\n";
			$scripttext .= '  function(route){'."\n";
			$scripttext .= '    geoRoute'.$currentArticleId.' = route;'."\n";
			$scripttext .= '    var points'.$currentArticleId.' = route.getWayPoints();'."\n";
            $scripttext .= '    points'.$currentArticleId.'.options.set(\'preset\', \'twirl#blueStretchyIcon\');'."\n";
			$scripttext .= '    points'.$currentArticleId.'.get(0).properties.set(\'iconContent\', \''.JText::_('PLG_ZHYANDEXMAP_MAP_FIND_GEOCODING_START_POINT').'\');'."\n";
			$scripttext .= '    points'.$currentArticleId.'.get(1).properties.set(\'iconContent\', \''.JText::_('PLG_ZHYANDEXMAP_MAP_FIND_GEOCODING_END_POINT').'\');'."\n";
			// Clear for do not open balloon
			$scripttext .= '    points'.$currentArticleId.'.get(0).properties.set(\'balloonContentHeader\', \'\');'."\n";
			$scripttext .= '    points'.$currentArticleId.'.get(0).properties.set(\'balloonContentBody\', \'\');'."\n";
			$scripttext .= '    points'.$currentArticleId.'.get(1).properties.set(\'balloonContentHeader\', \'\');'."\n";
			$scripttext .= '    points'.$currentArticleId.'.get(1).properties.set(\'balloonContentBody\', \'\');'."\n";
			
			$scripttext .= '     map'.$currentArticleId.'.geoObjects.add(geoRoute'.$currentArticleId.');'."\n";
			$scripttext .= '  }, '."\n";
			$scripttext .= '  function(err){'."\n";
			$scripttext .= '     alert(\''.JText::_('PLG_ZHYANDEXMAP_MAP_ERROR_GEOCODING').'\' + err.message);'."\n";
			$scripttext .= '  }'."\n";
			$scripttext .= ');'."\n";
			$scripttext .= '}'."\n";
		}

// For Marker Cluster Support Functions - begin
if (
    ((isset($map->markercluster) && (int)$map->markercluster == 1))            
    ||
    ((isset($map->markerclustergroup) && (int)$map->markerclustergroup == 1))
   )
{          
    if ((isset($map->markercluster) && (int)$map->markercluster == 1))
    {

        if ((isset($map->markerclustergroup) && (int)$map->markerclustergroup == 1))
		{
			$scripttext .= 'function callClusterFill'.$currentArticleId.'(clusterid){   ' ."\n";

				if (isset($markergroups) && !empty($markergroups)) 
				{

                    foreach ($markergroups as $key => $currentmarkergroup) 
                    {
                        $scripttext .= 'if ('.$currentmarkergroup->id.' == clusterid)' ."\n";
                        $scripttext .= '{'."\n";

                        if ((int)$map->clusterzoom == 0)
                        {
                            if ((int)$currentmarkergroup->overridegroupicon == 1)
                            {
                                $scripttext .= 'markerCluster'.$currentmarkergroup->id.'.add(clustermarkers'.$currentmarkergroup->id.');' ."\n";
                            }
                            else
                            {
                                $scripttext .= 'markerCluster'.$currentmarkergroup->id.'.add(clustermarkers'.$currentmarkergroup->id.');' ."\n";
                            }
                        }
                        else
                        {
                            if ((int)$currentmarkergroup->overridegroupicon == 1)
                            {
                                $scripttext .= 'markerCluster'.$currentmarkergroup->id.'.add(clustermarkers'.$currentmarkergroup->id.');' ."\n";
                            }
                            else
                            {
                                $scripttext .= 'markerCluster'.$currentmarkergroup->id.'.add(clustermarkers'.$currentmarkergroup->id.');' ."\n";
                            }
                        }
						
                        $scripttext .= '}'."\n";
                    }
				}
			$scripttext .= '};' ."\n";


		}
		else
		{
		}
     }
     else
     {
     }

}
// For Marker Cluster Support Functions - end
		
	$scripttext .= '/*]]>*/</script>' ."\n";
	// Script end


	$this->scripttext = $scripttext;
	$this->scripthead = $scripthead;
	if ($loadmodules != "")
	{
		$this->loadmodules = $loadmodules;
	}

