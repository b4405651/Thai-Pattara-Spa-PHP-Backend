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
	$divmap4route .= '<div id="YMapsRoutePanel'.$currentArticleId.'"><div id="YMapsRoutePanel_Description'.$currentArticleId.'"></div><div id="YMapsRoutePanel_Total'.$currentArticleId.'"></div></div>';
		

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
		$divmapHider .= '<a id="zhym-visibility'.$currentArticleId.'" class="zhym-map-visibility" href="#">'.$hiddenContainer.'</a>';
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
	
	$scripttext .= 'YMaps.jQuery(function () {' ."\n";
		
		$scripttext .= '	container'.$currentArticleId.' = YMaps.jQuery("#YMWrap'.$mapDivTag.'");' ."\n";
        $scripttext .= '    map'.$currentArticleId.' = new YMaps.Map(document.getElementById("'.$mapDivTag.'"));' ."\n";
		//$scripttext .= '    map'.$currentArticleId.' = new YMaps.Map(container'.$currentArticleId.'[0]);' ."\n";


		$scripttext .= '    mapcenter'.$currentArticleId.' = new YMaps.GeoPoint( '.$map->longitude.', ' .$map->latitude.');' ."\n";
        $scripttext .= '    map'.$currentArticleId.'.setCenter(mapcenter'.$currentArticleId.');' ."\n";
		
		if ($hiddenContainer != "")
		{
			$scripttext .= '         YMaps.jQuery("#zhym-visibility'.$currentArticleId.'").bind(\'click\', function () {' ."\n";
			$scripttext .= '            container'.$currentArticleId.'.css(\'display\', (container'.$currentArticleId.'.css(\'display\') == \'none\') ? \'\' : \'none\');' ."\n";
			$scripttext .= '            map'.$currentArticleId.'.redraw(); ' ."\n";
			$scripttext .= '            return false;' ."\n";
			$scripttext .= '        });' ."\n";
		}


	//Double Click Zoom
	if (isset($map->doubleclickzoom) && (int)$map->doubleclickzoom == 1) 
	{
		$scripttext .= 'map'.$currentArticleId.'.enableDblClickZoom();' ."\n";
	} 
	else 
	{
		$scripttext .= 'map'.$currentArticleId.'.disableDblClickZoom();' ."\n";
	}


	//Scroll Wheel Zoom		
	if (isset($map->scrollwheelzoom) && (int)$map->scrollwheelzoom == 1) 
	{
		$scripttext .= 'map'.$currentArticleId.'.enableScrollZoom();' ."\n";
	} else {
		$scripttext .= 'map'.$currentArticleId.'.disableScrollZoom();' ."\n";
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
                            $ctrlPosition = "YMaps.ControlPosition.TOP_LEFT";
                        break;
                        case 2:
                            $ctrlPosition = "YMaps.ControlPosition.TOP_RIGHT";
                        break;
                        case 3:
                            $ctrlPosition = "YMaps.ControlPosition.BOTTOM_RIGHT";
                        break;
                        case 4:
                            $ctrlPosition = "YMaps.ControlPosition.BOTTOM_LEFT";
                        break;
                        default:
                            $ctrlPosition = "";
                        break;
                    }
                    if ($ctrlPosition != "")
                    {
                        $ctrlPositionFullText = ', new YMaps.ControlPosition('.
                                                    $ctrlPosition.
                                                    ', new YMaps.Point('.
                                                        (int)$map->zoomcontrolofsx.','.
                                                        (int)$map->zoomcontrolofsy.'))';
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
				$scripttext .= 'map'.$currentArticleId.'.addControl(new YMaps.Zoom()'.$ctrlPositionFullText.');' ."\n";
			break;
			case 2:
				$scripttext .= 'map'.$currentArticleId.'.addControl(new YMaps.SmallZoom()'.$ctrlPositionFullText.');' ."\n";
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
                            $ctrlPosition = "YMaps.ControlPosition.TOP_LEFT";
                        break;
                        case 2:
                            $ctrlPosition = "YMaps.ControlPosition.TOP_RIGHT";
                        break;
                        case 3:
                            $ctrlPosition = "YMaps.ControlPosition.BOTTOM_RIGHT";
                        break;
                        case 4:
                            $ctrlPosition = "YMaps.ControlPosition.BOTTOM_LEFT";
                        break;
                        default:
                            $ctrlPosition = "";
                        break;
                    }
                    if ($ctrlPosition != "")
                    {
                        $ctrlPositionFullText = ', new YMaps.ControlPosition('.
                                                    $ctrlPosition.
                                                    ', new YMaps.Point('.
                                                        (int)$map->scalecontrolofsx.','.
                                                        (int)$map->scalecontrolofsy.'))';
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
            
		$scripttext .= 'map'.$currentArticleId.'.addControl(new YMaps.ScaleLine()'.$ctrlPositionFullText.');' ."\n";
	}

        if (isset($map->maptypecontrol) && (int)$map->maptypecontrol == 1) 
	{
                $ctrlPosition = "";
                $ctrlPositionFullText ="";
                
                if (isset($map->maptypecontrolpos)) 
                {
                    switch ($map->maptypecontrolpos)
                    {
                        case 1:
                            $ctrlPosition = "YMaps.ControlPosition.TOP_LEFT";
                        break;
                        case 2:
                            $ctrlPosition = "YMaps.ControlPosition.TOP_RIGHT";
                        break;
                        case 3:
                            $ctrlPosition = "YMaps.ControlPosition.BOTTOM_RIGHT";
                        break;
                        case 4:
                            $ctrlPosition = "YMaps.ControlPosition.BOTTOM_LEFT";
                        break;
                        default:
                            $ctrlPosition = "";
                        break;
                    }
                    if ($ctrlPosition != "")
                    {
                        $ctrlPositionFullText = ', new YMaps.ControlPosition('.
                                                    $ctrlPosition.
                                                    ', new YMaps.Point('.
                                                        (int)$map->maptypecontrolofsx.','.
                                                        (int)$map->maptypecontrolofsy.'))';
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
            
		$scripttext .= 'map'.$currentArticleId.'.addControl(new YMaps.TypeControl()'.$ctrlPositionFullText.');' ."\n";
	}

	if (isset($map->pmaptypecontrol) && (int)$map->pmaptypecontrol == 1) 
	{
                $ctrlPosition = "";
                $ctrlPositionFullText ="";
                
                if (isset($map->pmaptypecontrolpos)) 
                {
                    switch ($map->pmaptypecontrolpos)
                    {
                        case 1:
                            $ctrlPosition = "YMaps.ControlPosition.TOP_LEFT";
                        break;
                        case 2:
                            $ctrlPosition = "YMaps.ControlPosition.TOP_RIGHT";
                        break;
                        case 3:
                            $ctrlPosition = "YMaps.ControlPosition.BOTTOM_RIGHT";
                        break;
                        case 4:
                            $ctrlPosition = "YMaps.ControlPosition.BOTTOM_LEFT";
                        break;
                        default:
                            $ctrlPosition = "";
                        break;
                    }
                    if ($ctrlPosition != "")
                    {
                        $ctrlPositionFullText = ', new YMaps.ControlPosition('.
                                                    $ctrlPosition.
                                                    ', new YMaps.Point('.
                                                        (int)$map->pmaptypecontrolofsx.','.
                                                        (int)$map->pmaptypecontrolofsy.'))';
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

                $scripttext .= 'map'.$currentArticleId.'.addControl(new YMaps.TypeControl([YMaps.MapType.PMAP, YMaps.MapType.PHYBRID])'.$ctrlPositionFullText.');' ."\n";
                if (isset($map->maptype) && ($loadmodules_pmap == 0))
                {
                    if ($loadmodules =='') 
                    {
                            $loadmodules = '&amp;modules=pmap';
							$loadmodules_pmap = 1;
                    } 
                    else 
                    {
                            $loadmodules .= '~pmap';
							$loadmodules_pmap = 1;
                    }
                }
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
				$scripttext .= 'map'.$currentArticleId.'.setType(YMaps.MapType.MAP);' ."\n";
			break;
			case 2:
				$scripttext .= 'map'.$currentArticleId.'.setType(YMaps.MapType.SATELLITE);' ."\n";
			break;
			case 3:
				$scripttext .= 'map'.$currentArticleId.'.setType(YMaps.MapType.HYBRID);' ."\n";
			break;
			case 4:
				$scripttext .= 'map'.$currentArticleId.'.setType(YMaps.MapType.PMAP);' ."\n";
				if ($loadmodules_pmap == 0)
				{
					if ($loadmodules =='') 
					{
							$loadmodules = '&amp;modules=pmap';
							$loadmodules_pmap = 1;
					} 
					else 
					{
							$loadmodules .= '~pmap';
							$loadmodules_pmap = 1;
					}
				}
			break;
			case 5:
				$scripttext .= 'map'.$currentArticleId.'.setType(YMaps.MapType.PHYBRID);' ."\n";
				if ($loadmodules_pmap == 0)
				{
					if ($loadmodules =='') 
					{
							$loadmodules = '&amp;modules=pmap';
							$loadmodules_pmap = 1;
					} 
					else 
					{
							$loadmodules .= '~pmap';
							$loadmodules_pmap = 1;
					}
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
					$scripttextMiniMap = 'YMaps.MapType.MAP';
				break;
				case 2:
					$scripttextMiniMap = 'YMaps.MapType.SATELLITE';
				break;
				case 3:
					$scripttextMiniMap = 'YMaps.MapType.HYBRID';
				break;
				case 4:
					$scripttextMiniMap = 'YMaps.MapType.PMAP';
					if ($loadmodules_pmap == 0)
					{
						if ($loadmodules =='') 
						{
								$loadmodules = '&amp;modules=pmap';
								$loadmodules_pmap = 1;
						} 
						else 
						{
								$loadmodules .= '~pmap';
								$loadmodules_pmap = 1;
						}
					}
				break;
				case 5:
					$scripttextMiniMap = 'YMaps.MapType.PHYBRID';
					if ($loadmodules_pmap == 0)
					{
						if ($loadmodules =='') 
						{
								$loadmodules = '&amp;modules=pmap';
								$loadmodules_pmap = 1;
						} 
						else 
						{
								$loadmodules .= '~pmap';
								$loadmodules_pmap = 1;
						}
					}
				break;
				default:
					$scripttextMiniMap = '' ."\n";
				break;
			}
		}
	}
	

	
	if (isset($currentZoom))
	{
	  if ($currentZoom == "do not change")  
	  {
			// Because that we set map type
			if ((int)$map->zoom != 200)
			{
				$scripttext .= '    map'.$currentArticleId.'.setZoom('.(int)$map->zoom.');' ."\n";
			}
			else
			{
				$scripttext .= '    map'.$currentArticleId.'.setZoom(map'.$currentArticleId.'.getMaxZoom());' ."\n";
			}
	  }
	  else
	  {
		if ($currentZoom != "200")  
		{
				$scripttext .= '    map'.$currentArticleId.'.setZoom('.(int)$currentZoom.');' ."\n";
		}
		else
		{
				$scripttext .= '    map'.$currentArticleId.'.setZoom(map'.$currentArticleId.'.getMaxZoom());' ."\n";
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
			$scripttext .= '    map'.$currentArticleId.'.setZoom(map'.$currentArticleId.'.getMaxZoom());' ."\n";
		}
	}
	
	$scripttext .= '    map'.$currentArticleId.'.setMinZoom('.(int)$map->minzoom.');' ."\n";
	if ((int)$map->maxzoom != 200)
	{
		$scripttext .= '    map'.$currentArticleId.'.setMaxZoom('.(int)$map->maxzoom.');' ."\n";
	}
	// When changed maptype max zoom level can be other
	$scripttext .= 'YMaps.Events.observe(map'.$currentArticleId.', map'.$currentArticleId.'.Events.ZoomRangeChange, function (map, mEvent) {' ."\n";
	$scripttext .= '  if (map'.$currentArticleId.'.getZoom() > map'.$currentArticleId.'.getMaxZoom())' ."\n";
	$scripttext .= '  {	' ."\n";
	$scripttext .= '    map'.$currentArticleId.'.setZoom(map'.$currentArticleId.'.getMaxZoom());' ."\n";
	$scripttext .= '  }' ."\n";
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
			
			$scripttext .= '   var allowedBounds = new YMaps.GeoBounds(' ."\n";
			$scripttext .= '	 new YMaps.GeoPoint('.$mapBoundsArray[0].', '.$mapBoundsArray[1].'), ' ."\n";
			$scripttext .= '	 new YMaps.GeoPoint('.$mapBoundsArray[2].', '.$mapBoundsArray[3].'));' ."\n";

			// Listen for the event
			// dragend
			// bounds_changed
			$scripttext .= '  YMaps.Events.observe(map'.$currentArticleId.', map'.$currentArticleId.'.Events.BoundsChange, function() {' ."\n";
			$scripttext .= '	 if (allowedBounds.contains(map'.$currentArticleId.'.getCenter())) return;' ."\n";

			// Out of bounds - Move the map back within the bounds
			$scripttext .= '	 var c = map'.$currentArticleId.'.getCenter(),' ."\n";
			$scripttext .= '		 y = c.getLng(),' ."\n";
			$scripttext .= '		 x = c.getLat(),' ."\n";
			$scripttext .= '		 maxY = allowedBounds.getRightTop().getLng(),' ."\n";
			$scripttext .= '		 maxX = allowedBounds.getRightTop().getLat(),' ."\n";
			$scripttext .= '		 minY = allowedBounds.getLeftBottom().getLng(),' ."\n";
			$scripttext .= '		 minX = allowedBounds.getLeftBottom().getLat();' ."\n";

			$scripttext .= '	 if (x < minX) x = minX;' ."\n";
			$scripttext .= '	 if (x > maxX) x = maxX;' ."\n";
			$scripttext .= '	 if (y < minY) y = minY;' ."\n";
			$scripttext .= '	 if (y > maxY) y = maxY;' ."\n";

			$scripttext .= '	 map'.$currentArticleId.'.setCenter(new YMaps.GeoPoint(y, x));' ."\n";
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
                            $ctrlPosition = "YMaps.ControlPosition.TOP_LEFT";
                        break;
                        case 2:
                            $ctrlPosition = "YMaps.ControlPosition.TOP_RIGHT";
                        break;
                        case 3:
                            $ctrlPosition = "YMaps.ControlPosition.BOTTOM_RIGHT";
                        break;
                        case 4:
                            $ctrlPosition = "YMaps.ControlPosition.BOTTOM_LEFT";
                        break;
                        default:
                            $ctrlPosition = "";
                        break;
                    }
                    if ($ctrlPosition != "")
                    {
                        $ctrlPositionFullText = ', new YMaps.ControlPosition('.
                                                    $ctrlPosition.
                                                    ', new YMaps.Point('.
                                                        (int)$map->minimapofsx.','.
                                                        (int)$map->minimapofsy.'))';
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

            $scripttext .= 'minimap'.$currentArticleId.' = new YMaps.MiniMap();' ."\n";
			
			if ((int)$map->minimap == 1)
			{
				$scripttext .= 'minimap'.$currentArticleId.'.setVisible(true);' ."\n";
			}
			else
			{
				$scripttext .= 'minimap'.$currentArticleId.'.setVisible(false);' ."\n";
			}
			
			if ($scripttextMiniMap != "")
			{
				$scripttext .= 'minimap'.$currentArticleId.'.setType('.$scripttextMiniMap.');' ."\n";
			}
            $scripttext .= 'map'.$currentArticleId.'.addControl(minimap'.$currentArticleId.''.$ctrlPositionFullText.');' ."\n";
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
                            $ctrlPosition = "YMaps.ControlPosition.TOP_LEFT";
                        break;
                        case 2:
                            $ctrlPosition = "YMaps.ControlPosition.TOP_RIGHT";
                        break;
                        case 3:
                            $ctrlPosition = "YMaps.ControlPosition.BOTTOM_RIGHT";
                        break;
                        case 4:
                            $ctrlPosition = "YMaps.ControlPosition.BOTTOM_LEFT";
                        break;
                        default:
                            $ctrlPosition = "";
                        break;
                    }
                    if ($ctrlPosition != "")
                    {
                        $ctrlPositionFullText = ', new YMaps.ControlPosition('.
                                                    $ctrlPosition.
                                                    ', new YMaps.Point('.
                                                        (int)$map->toolbarofsx.','.
                                                        (int)$map->toolbarofsy.'))';
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

                $scripttext .= 'var toolbar'.$currentArticleId.' = new YMaps.ToolBar();' ."\n";
                $scripttext .= 'map'.$currentArticleId.'.addControl(toolbar'.$currentArticleId.$ctrlPositionFullText.');' ."\n";
				

				
				
			if (isset($map->autopositioncontrol) && (int)$map->autopositioncontrol == 1) 
			{
					switch ((int)$map->autopositionbutton) 
					{
						case 1:
							$scripttext .= 'var btnGeoPosition'.$currentArticleId.' = new YMaps.ToolBarButton({ icon: "'.$imgpathUtils.'geolocation.png", caption: "", hint: "'.JText::_('PLG_ZHYANDEXMAP_MAP_AUTOPOSITIONBUTTON').'"});' ."\n";
						break;
						case 2:
							$scripttext .= 'var btnGeoPosition'.$currentArticleId.' = new YMaps.ToolBarButton({ caption: "'.JText::_('PLG_ZHYANDEXMAP_MAP_AUTOPOSITIONBUTTON').'", hint: "'.JText::_('PLG_ZHYANDEXMAP_MAP_AUTOPOSITIONBUTTON').'"});' ."\n";
						break;
						case 3:
							$scripttext .= 'var btnGeoPosition'.$currentArticleId.' = new YMaps.ToolBarButton({ icon: "'.$imgpathUtils.'geolocation.png", caption: "'.JText::_('PLG_ZHYANDEXMAP_MAP_AUTOPOSITIONBUTTON').'", hint: "'.JText::_('PLG_ZHYANDEXMAP_MAP_AUTOPOSITIONBUTTON').'"});' ."\n";
						break;
						default:
							$scripttext .= 'var btnGeoPosition'.$currentArticleId.' = new YMaps.ToolBarButton({ icon: "'.$imgpathUtils.'geolocation.png", caption: "", hint: "'.JText::_('PLG_ZHYANDEXMAP_MAP_AUTOPOSITIONBUTTON').'"});' ."\n";
						break;
					}

					$scripttext .= 'YMaps.Events.observe(btnGeoPosition'.$currentArticleId.', btnGeoPosition'.$currentArticleId.'.Events.Click, function () {' ."\n";
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
								$btnPlacemarkListOptions =", {selected:true}" ;
							break;
							case 12:
								$btnPlacemarkListOptions =", {selected:true}" ;
							break;
							default:
								$btnPlacemarkListOptions ="" ;
							break;
						}		
						
						switch ((int)$map->markerlistbuttontype) 
						{
							case 1:
								$scripttext .= 'var btnPlacemarkList'.$currentArticleId.' = new YMaps.ToolBarToggleButton({ icon: "'.$imgpathUtils.'star.png", caption: "", hint: "'.JText::_('PLG_ZHYANDEXMAP_MAP_PLACEMARKLIST').'"}'.$btnPlacemarkListOptions.');' ."\n";
							break;
							case 2:
								$scripttext .= 'var btnPlacemarkList'.$currentArticleId.' = new YMaps.ToolBarToggleButton({ caption: "'.JText::_('PLG_ZHYANDEXMAP_MAP_PLACEMARKLIST').'", hint: "'.JText::_('PLG_ZHYANDEXMAP_MAP_PLACEMARKLIST').'"}'.$btnPlacemarkListOptions.');' ."\n";
							break;
							case 11:
								$scripttext .= 'var btnPlacemarkList'.$currentArticleId.' = new YMaps.ToolBarToggleButton({ icon: "'.$imgpathUtils.'star.png", caption: "", hint: "'.JText::_('PLG_ZHYANDEXMAP_MAP_PLACEMARKLIST').'"}'.$btnPlacemarkListOptions.');' ."\n";
							break;
							case 2:
								$scripttext .= 'var btnPlacemarkList'.$currentArticleId.' = new YMaps.ToolBarToggleButton({ caption: "'.JText::_('PLG_ZHYANDEXMAP_MAP_PLACEMARKLIST').'", hint: "'.JText::_('PLG_ZHYANDEXMAP_MAP_PLACEMARKLIST').'"}'.$btnPlacemarkListOptions.');' ."\n";
							default:
								$scripttext .= 'var btnPlacemarkList'.$currentArticleId.' = new YMaps.ToolBarToggleButton({ icon: "'.$imgpathUtils.'star.png", caption: "", hint: "'.JText::_('PLG_ZHYANDEXMAP_MAP_PLACEMARKLIST').'"}'.$btnPlacemarkListOptions.');' ."\n";
							break;
						}

						$scripttext .= 'YMaps.Events.observe(btnPlacemarkList'.$currentArticleId.', btnPlacemarkList'.$currentArticleId.'.Events.Select, function () {' ."\n";
						$scripttext .= '		var toHideDiv = document.getElementById("YMapsMarkerList'.$currentArticleId.'");' ."\n";
						$scripttext .= '		toHideDiv.style.display = "block";' ."\n";
						$scripttext .= '}, toolbar);' ."\n";
						
						$scripttext .= 'YMaps.Events.observe(btnPlacemarkList'.$currentArticleId.', btnPlacemarkList'.$currentArticleId.'.Events.Deselect, function () {' ."\n";
						$scripttext .= '		var toHideDiv = document.getElementById("YMapsMarkerList'.$currentArticleId.'");' ."\n";
						$scripttext .= '		toHideDiv.style.display = "none";' ."\n";
						$scripttext .= '}, toolbar);' ."\n";

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
                            $ctrlPosition = "YMaps.ControlPosition.TOP_LEFT";
                        break;
                        case 2:
                            $ctrlPosition = "YMaps.ControlPosition.TOP_RIGHT";
                        break;
                        case 3:
                            $ctrlPosition = "YMaps.ControlPosition.BOTTOM_RIGHT";
                        break;
                        case 4:
                            $ctrlPosition = "YMaps.ControlPosition.BOTTOM_LEFT";
                        break;
                        default:
                            $ctrlPosition = "";
                        break;
                    }
                    if ($ctrlPosition != "")
                    {
                        $ctrlPositionFullText = ', new YMaps.ControlPosition('.
                                                    $ctrlPosition.
                                                    ', new YMaps.Point('.
                                                        (int)$map->searchofsx.','.
                                                        (int)$map->searchofsy.'))';
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


                $scripttext .= 'searchControl'.$currentArticleId.' = new YMaps.SearchControl();' ."\n";
                $scripttext .= 'searchControlPMAP'.$currentArticleId.' = new YMaps.SearchControl({geocodeOptions: {geocodeProvider: "yandex#pmap"}});' ."\n";
				$scripttext .= '   if ((map'.$currentArticleId.'.getType() == YMaps.MapType.PMAP) || (map'.$currentArticleId.'.getType() == YMaps.MapType.PHYBRID))';
				$scripttext .= '   {';
				//$scripttext .= '	  alert("PMAP");' ."\n";
                $scripttext .= '	  map'.$currentArticleId.'.addControl(searchControlPMAP'.$currentArticleId.''.$ctrlPositionFullText.');' ."\n";
				$scripttext .= '   }';
				$scripttext .= '   else';
				$scripttext .= '   {';
				//$scripttext .= '	  alert("MAP");' ."\n";
                $scripttext .= '	  map'.$currentArticleId.'.addControl(searchControl'.$currentArticleId.''.$ctrlPositionFullText.');' ."\n";
				$scripttext .= '   }';
				

				$scripttext .= 'YMaps.Events.observe(map'.$currentArticleId.', map'.$currentArticleId.'.Events.TypeChange, function (map, mEvent) {' ."\n";
				$scripttext .= '   if ((map'.$currentArticleId.'.getType() == YMaps.MapType.PMAP) || (map'.$currentArticleId.'.getType() == YMaps.MapType.PHYBRID))';
				$scripttext .= '   {';
				//$scripttext .= '	  alert("PMAP");' ."\n";
				$scripttext .= '	  map'.$currentArticleId.'.removeControl(searchControl'.$currentArticleId.');' ."\n";
				$scripttext .= '	  map'.$currentArticleId.'.addControl(searchControlPMAP'.$currentArticleId.''.$ctrlPositionFullText.');' ."\n";
				$scripttext .= '   }';
				$scripttext .= '   else';
				$scripttext .= '   {';
				//$scripttext .= '	  alert("Map");' ."\n";
				$scripttext .= '	  map'.$currentArticleId.'.removeControl(searchControlPMAP'.$currentArticleId.');' ."\n";
				$scripttext .= '	  map'.$currentArticleId.'.addControl(searchControl'.$currentArticleId.''.$ctrlPositionFullText.');' ."\n";
				$scripttext .= '   }';
				$scripttext .= '});' ."\n";
				
	}
	

	//Traffic
	if (isset($map->traffic) && (int)$map->traffic == 1) 
	{
		if ($loadmodules =='') 
		{
			$loadmodules = '&amp;modules=traffic';
		} 
		else 
		{
			$loadmodules .= '~traffic';
		}
                $ctrlPosition = "";
                $ctrlPositionFullText ="";
                
                if (isset($map->trafficpos)) 
                {
                    switch ($map->trafficpos)
                    {
                        case 1:
                            $ctrlPosition = "YMaps.ControlPosition.TOP_LEFT";
                        break;
                        case 2:
                            $ctrlPosition = "YMaps.ControlPosition.TOP_RIGHT";
                        break;
                        case 3:
                            $ctrlPosition = "YMaps.ControlPosition.BOTTOM_RIGHT";
                        break;
                        case 4:
                            $ctrlPosition = "YMaps.ControlPosition.BOTTOM_LEFT";
                        break;
                        default:
                            $ctrlPosition = "";
                        break;
                    }
                    if ($ctrlPosition != "")
                    {
                        $ctrlPositionFullText = ', new YMaps.ControlPosition('.
                                                    $ctrlPosition.
                                                    ', new YMaps.Point('.
                                                        (int)$map->trafficofsx.','.
                                                        (int)$map->trafficofsy.'))';
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

				if (isset($map->trafficlayer) && (int)$map->trafficlayer == 1) 
				{
					$scripttext .= 'map'.$currentArticleId.'.addControl(new YMaps.Traffic.Control({}, {shown: true})'.$ctrlPositionFullText.');' ."\n";
				}
				else
				{
					$scripttext .= 'map'.$currentArticleId.'.addControl(new YMaps.Traffic.Control()'.$ctrlPositionFullText.');' ."\n";
				}
	}

	if (isset($map->rightbuttonmagnifier) && (int)$map->rightbuttonmagnifier == 1) 
	{
		$scripttext .= 'map'.$currentArticleId.'.enableRightButtonMagnifier();'."\n";
	}
	else
	{
		$scripttext .= 'map'.$currentArticleId.'.disableRightButtonMagnifier();'."\n";
	}

	if (isset($map->magnifier)) 
	{
		switch ((int)$map->magnifier)
		{
			case 0:
			break;
			case 1:
				$scripttext .= 'map'.$currentArticleId.'.enableMagnifier();'."\n";
			break;
			case 2:
				$scripttext .= 'map'.$currentArticleId.'.enableRuler();'."\n";
			break;
			default:
			break;
		}
	}

	if (isset($map->draggable) && (int)$map->draggable == 1) 
	{
		$scripttext .= 'map'.$currentArticleId.'.enableDragging();'."\n";
	}
	else
	{
		$scripttext .= 'map'.$currentArticleId.'.disableDragging();'."\n";
	}

	//Grid Coordinates		
	if (isset($map->gridcoordinates) && (int)$map->gridcoordinates == 1) 
	{
		$scripttext .= 'map'.$currentArticleId.'.addLayer(new YMaps.Layer(new YMaps.TileDataSource("http://lrs.maps.yandex.net/tiles/?l=grd&v=1.0&%c", true, false)));' ."\n";
	}
	
	if (isset($map->markermanager) && (int)$map->markermanager == 1) 
	{
            $scripttext .= 'var objectManager = new YMaps.ObjectManager();'."\n";
            $scripttext .= 'map'.$currentArticleId.'.addOverlay(objectManager);'."\n";
        }

	//Balloon	
	if (isset($map->balloon)) 
	{
		switch ($map->balloon) 
		{
			case 0:
			break;
			case 1:
				$scripttext .= 'map'.$currentArticleId.'.openBalloon(new YMaps.GeoPoint('.$map->longitude.', ' .$map->latitude.'), "'.htmlspecialchars(str_replace('\\', '/', $map->title), ENT_QUOTES, 'UTF-8').'");' ."\n";
			break;
			case 2:
				$scripttext .= 'var placemark = new YMaps.Placemark(new YMaps.GeoPoint('.$map->longitude.', ' .$map->latitude.'));' ."\n";
				$scripttext .= 'placemark.name = "' .htmlspecialchars(str_replace('\\', '/', $map->title), ENT_QUOTES, 'UTF-8').'";' ."\n";
				$scripttext .= 'placemark.description = "' .htmlspecialchars(str_replace('\\', '/', $map->description), ENT_QUOTES, 'UTF-8').'";' ."\n";
				$scripttext .= 'map'.$currentArticleId.'.addOverlay(placemark);' ."\n";
				$scripttext .= 'placemark.openBalloon();' ."\n";
			break;
			case 3:
				$scripttext .= 'var placemark = new YMaps.Placemark(new YMaps.GeoPoint('.$map->longitude.', ' .$map->latitude.'));' ."\n";
				$scripttext .= 'placemark.name = "' .htmlspecialchars(str_replace('\\', '/', $map->title), ENT_QUOTES, 'UTF-8').'";' ."\n";
				$scripttext .= 'placemark.description = "' .htmlspecialchars(str_replace('\\', '/', $map->description), ENT_QUOTES, 'UTF-8').'";' ."\n";
				$scripttext .= 'map'.$currentArticleId.'.addOverlay(placemark);' ."\n";
				$scripttext .= 'placemark.setIconContent("' .htmlspecialchars(str_replace('\\', '/', $map->title), ENT_QUOTES, 'UTF-8').'");' ."\n";
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

		if ($this->compatiblemodersf == 0)
		{
			$imgpath4size = JPATH_ADMINISTRATOR .'/components/com_zhyandexmap/assets/icons/';
		}
		else
		{
			$imgpath4size = JPATH_SITE .'/components/com_zhyandexmap/assets/icons/';
		}

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

				if ((int)$currentmarker->overridemarkericon == 0)
				{
					$imgimg = $imgpathIcons.str_replace("#", "%23", $currentmarker->icontype).'.png';
					$imgimg4size = $imgpath4size.$currentmarker->icontype.'.png';

					list ($imgwidth, $imgheight) = getimagesize($imgimg4size);

					$scripttext .= 'var s'. $currentmarker->id.' = new YMaps.Style();' ."\n";
					$scripttext .= 's'. $currentmarker->id.'.iconStyle = new YMaps.IconStyle();' ."\n";
					$scripttext .= 's'. $currentmarker->id.'.iconStyle.href ="'.$imgimg.'";' ."\n";
					$scripttext .= 's'. $currentmarker->id.'.iconStyle.size = new YMaps.Point('.$imgwidth.','.$imgheight.');' ."\n";
					if (isset($currentmarker->iconofsetx) 
					 && isset($currentmarker->iconofsety) 
					 && ((int)$currentmarker->iconofsetx !=0
					  || (int)$currentmarker->iconofsety !=0)
					 )
					{
						$scripttext .= 's'. $currentmarker->id.'.iconStyle.offset = new YMaps.Point('.(int)$currentmarker->iconofsetx.','.(int)$currentmarker->iconofsety.');' ."\n";
					}
				}
				else
				{
					$imgimg = $imgpathIcons.str_replace("#", "%23", $currentmarker->groupicontype).'.png';
					$imgimg4size = $imgpath4size.$currentmarker->groupicontype.'.png';

					list ($imgwidth, $imgheight) = getimagesize($imgimg4size);

					$scripttext .= 'var s'. $currentmarker->id.' = new YMaps.Style();' ."\n";
					$scripttext .= 's'. $currentmarker->id.'.iconStyle = new YMaps.IconStyle();' ."\n";
					$scripttext .= 's'. $currentmarker->id.'.iconStyle.href ="'.$imgimg.'";' ."\n";
					$scripttext .= 's'. $currentmarker->id.'.iconStyle.size = new YMaps.Point('.$imgwidth.','.$imgheight.');' ."\n";
					/* there is no offset in group
					if (isset($currentmarker->iconofsetx) 
					 && isset($currentmarker->iconofsety) 
					 && ((int)$currentmarker->iconofsetx !=0
					  || (int)$currentmarker->iconofsety !=0)
					 )
					{
						$scripttext .= 's'. $currentmarker->id.'.iconStyle.offset = new YMaps.Point('.(int)$currentmarker->iconofsetx.','.(int)$currentmarker->iconofsety.');' ."\n";
					}
					*/
				}

				$scripttext .= 'var latlng'.$currentmarker->id.'= new YMaps.GeoPoint('.$currentmarker->longitude.', ' .$currentmarker->latitude.');' ."\n";
				$scripttext .= 'var '.$markername.'= new YMaps.Placemark(latlng'.$currentmarker->id.', {' ."\n";
				if ((int)$currentmarker->actionbyclick == 1)
				{
					$scripttext .= ' hasBalloon:true, ';
				}
				else
				{
					$scripttext .= ' hasBalloon:false, ';
				}
				$scripttext .= ' style: s'. $currentmarker->id.' });' ."\n";

				if (isset($currentCenter))
				{
				  if ($currentCenter == "placemark")  
				  {
					$scripttext .= '  map'.$currentArticleId.'.setCenter(latlng'. $currentmarker->id.');' ."\n";
				  }
				}

				$scripttext .= 'var contentString'. $currentmarker->id.' = \'<div id="placemarkContent'. $currentmarker->id.'">\' +' ."\n";
				if (isset($currentmarker->markercontent) &&
					(((int)$currentmarker->markercontent == 0) ||
					 ((int)$currentmarker->markercontent == 1))
					)
				{
					$scripttext .= '\'<h1 id="headContent'. $currentmarker->id.'" class="placemarkHead">'.htmlspecialchars(str_replace('\\', '/', $currentmarker->title), ENT_QUOTES, 'UTF-8').'</h1>\'+' ."\n";
				}
				$scripttext .= '\'<div id="bodyContent'. $currentmarker->id.'"  class="placemarkBody">\'+'."\n";

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
												$scripttext .= '\'<p class="placemarkBodyContact">'.JText::_('PLG_ZHYANDEXMAP_MAP_USER_CONTACT_ADDRESS').' '.str_replace('<br /><br />', '<br />',str_replace(array("\r", "\r\n", "\n"), '<br />', htmlspecialchars(str_replace('\\', '/', $currentmarker->contact_address), ENT_QUOTES, 'UTF-8'))).'</p>\'+'."\n";
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
												$scripttext .= '\'<p class="placemarkBodyContact"><img src="'.$imgpathUtils.'address.png" alt="'.JText::_('PLG_ZHYANDEXMAP_MAP_USER_CONTACT_ADDRESS').'" />'.str_replace('<br /><br />', '<br />',str_replace(array("\r", "\r\n", "\n"), '<br />', htmlspecialchars(str_replace('\\', '/', $currentmarker->contact_address), ENT_QUOTES, 'UTF-8'))).'</p>\'+'."\n";
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
												$scripttext .= '\'<p class="placemarkBodyContact">'.str_replace('<br /><br />', '<br />',str_replace(array("\r", "\r\n", "\n"), '<br />', htmlspecialchars(str_replace('\\', '/', $currentmarker->contact_address), ENT_QUOTES, 'UTF-8'))).'</p>\'+'."\n";
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
												$scripttext .= '\'<p class="placemarkBodyContact">'.str_replace('<br /><br />', '<br />',str_replace(array("\r", "\r\n", "\n"), '<br />', htmlspecialchars(str_replace('\\', '/', $currentmarker->contact_address), ENT_QUOTES, 'UTF-8'))).'</p>\'+'."\n";
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
								$scripttext .= $this->get_userinfo_for_marker($currentmarker->createdbyuser, $currentmarker->showuser);
							}
							// User info - end
							
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

							$scripttext .= '\'</div>\'+'."\n";
					$scripttext .= '\'</div>\';'."\n";

					// Action By Click - Begin							
					switch ((int)$currentmarker->actionbyclick)
					{
						// None
						case 0:
							if ((int)$currentmarker->zoombyclick != 100)
							{
								$scripttext .= 'YMaps.Events.observe('.$markername.', '.$markername.'.Events.Click, function (obj) {' ."\n";
								$scripttext .= '  map'.$currentArticleId.'.setCenter(latlng'. $currentmarker->id.');' ."\n";
								$scripttext .= '  map'.$currentArticleId.'.setZoom('.(int)$currentmarker->zoombyclick.');' ."\n";
								$scripttext .= '});' ."\n";
							}
						break;
						// Info
						case 1:
							$scripttext .= 'YMaps.Events.observe('.$markername.', '.$markername.'.Events.Click, function (obj) {' ."\n";
							if ((int)$currentmarker->zoombyclick != 100)
							{
								$scripttext .= '  map'.$currentArticleId.'.setCenter(latlng'. $currentmarker->id.');' ."\n";
								$scripttext .= '  map'.$currentArticleId.'.setZoom('.(int)$currentmarker->zoombyclick.');' ."\n";
							}
							$scripttext .= $markername.'.setBalloonContent(contentString'. $currentmarker->id.');' ."\n";

							$scripttext .= '    YMaps.Events.notify('.$markername.', '.$markername.'.Events.BalloonOpen);' ."\n";
							$scripttext .= '  });' ."\n";
						break;
						// Link
						case 2:
							if ($currentmarker->hrefsite != "")
							{
								$scripttext .= 'YMaps.Events.observe('.$markername.', '.$markername.'.Events.Click, function (obj) {' ."\n";
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
									$scripttext .= 'YMaps.Events.observe('.$markername.', '.$markername.'.Events.Click, function (obj) {' ."\n";
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
								$scripttext .= 'YMaps.Events.observe('.$markername.', '.$markername.'.Events.Click, function (obj) {' ."\n";
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
									$scripttext .= 'YMaps.Events.observe('.$markername.', '.$markername.'.Events.Click, function (obj) {' ."\n";
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

							
				$scripttext .= $markername.'.name = "' .htmlspecialchars(str_replace('\\', '/', $currentmarker->title), ENT_QUOTES, 'UTF-8').'";' ."\n";
				$scripttext .= $markername.'.description = "' .htmlspecialchars(str_replace('\\', '/',$currentmarker->description), ENT_QUOTES, 'UTF-8').'";' ."\n";

				//$scripttext .= $markername.'.setBalloonContent(contentString'. $currentmarker->id.');' ."\n";

				if (isset($currentmarker->showiconcontent) && ((int)$currentmarker->showiconcontent == 1))
				{			
					$scripttext .= $markername.'.setIconContent("'.htmlspecialchars(str_replace('\\', '/', $currentmarker->title), ENT_QUOTES, 'UTF-8').'");' ."\n";
				}

							if (isset($map->markermanager) && (int)$map->markermanager == 1) 
							{                            
								if (($currentmarker->markergroup != 0) &&
									((int)$currentmarker->markermanagerminzoom != 0) &&
									((int)$currentmarker->markermanagermaxzoom != 0) 
								   )
								{
								   $scripttext .= 'objectManager.add('.$markername.','.$currentmarker->markermanagerminzoom.','.$currentmarker->markermanagermaxzoom.');' ."\n";
								}
								else
								{
								   $scripttext .= 'objectManager.add('.$markername.', 1, 17);' ."\n";
								}
							}
							else
							{
								$scripttext .= 'map'.$currentArticleId.'.addOverlay('.$markername.');' ."\n";
							}

				if ($currentmarker->openbaloon == '1')
				{
							// $scripttext .= 'YMaps.Events.notify('.$markername.', '.$markername.'.Events.Click);';
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
									$scripttext .= $markername.'.setBalloonContent(contentString'. $currentmarker->id.');' ."\n";

									//$scripttext .= '    YMaps.Events.notify('.$markername.', '.$markername.'.Events.BalloonOpen);' ."\n";
									$scripttext .= '    '.$markername.'.openBalloon();' ."\n";
									
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
							
							// Action By Click - For Placemark Open Balloon Property - End
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
												$scripttext .= ' \'<div id="markerDIcon'. $currentmarker->id.'" class="zhym-2-lii-icon-'.$markerlistcssstyle.'"><img src="'.$imgpathIcons.str_replace("#", "%23", $currentmarker->icontype).'.png" alt="" /></div>\'+'."\n";
												$scripttext .= ' \'<div id="markerASel'. $currentmarker->id.'" class="zhym-2-lit-icon-'.$markerlistcssstyle.'">'.htmlspecialchars(str_replace('\\', '/', $currentmarker->title), ENT_QUOTES, 'UTF-8').'\'+'."\n";
												$scripttext .= ' \'</div></div>\';'."\n";
											}
											else if ((int)$map->markerlistcontent == 3) 
											{
												$scripttext .= ' markerASel.innerHTML = ';
												$scripttext .= ' \'<div id="markerDWrp'. $currentmarker->id.'" class="zhym-3-liw-icon-'.$markerlistcssstyle.'">\'+'."\n";
												$scripttext .= ' \'<div id="markerDIcon'. $currentmarker->id.'" class="zhym-3-lii-icon-'.$markerlistcssstyle.'"><img src="'.$imgpathIcons.str_replace("#", "%23", $currentmarker->icontype).'.png" alt="" /></div>\'+'."\n";
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
												$scripttext .= '<img src="'.$imgpathIcons.str_replace("#", "%23", $currentmarker->icontype).'.png" alt="" />';
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
														$scripttext .= $markername.'.setBalloonContent(contentString'. $currentmarker->id.');' ."\n";

														//$scripttext .= '    YMaps.Events.notify('.$markername.', '.$markername.'.Events.BalloonOpen);' ."\n";
														$scripttext .= '    '.$markername.'.openBalloon();' ."\n";
														
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
														$scripttext .= $markername.'.setBalloonContent(contentString'. $currentmarker->id.');' ."\n";

														//$scripttext .= '    YMaps.Events.notify('.$markername.', '.$markername.'.Events.BalloonOpen);' ."\n";
														$scripttext .= '    '.$markername.'.openBalloon();' ."\n";
														
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
			if ($currentrouter->route != "")
			{
			
				$scripttext .= ' var '.$routername.' = new YMaps.Router(['.$currentrouter->route.'],[],'."\n";
				$scripttext .=       '{ ';
				if (isset($currentrouter->showtype) && (int)$currentrouter->showtype == 1)
				{
					$scripttext .=       ' viewAutoApply: false ';
				}
				else
				{
					$scripttext .=       ' viewAutoApply: true ';
				}
				if (isset($currentrouter->checktraffic) && (int)$currentrouter->checktraffic == 1)
				{
					$scripttext .=       ', avoidTrafficJams: true ';
				}
				else
				{
					$scripttext .=       ', avoidTrafficJams: false ';
				}
				$scripttext .=       '});'."\n";
				$scripttext .=       'map'.$currentArticleId.'.addOverlay('.$routername.');'."\n";

				$scripttext .=       'YMaps.Events.observe('.$routername.', '.$routername.'.Events.GeocodeError, function (link, number) {'."\n";
				$scripttext .=       '   alert(\''.JText::_('PLG_ZHYANDEXMAP_MAP_ERROR_GEOCODING').'\' + number);'."\n";
				//$scripttext .=       '   alert(\'Error while geocoding of point #  \' + number);'."\n";
				$scripttext .=       '}); '."\n";
				$scripttext .=       'YMaps.Events.observe('.$routername.', '.$routername.'.Events.RouteError, function (link, number) {'."\n";
				$scripttext .=       '   alert(\''.JText::_('PLG_ZHYANDEXMAP_MAP_ERROR_UNABLE_DRAW_ROUTE').'\' + number);'."\n";
				//$scripttext .=       '   alert(\'Unable to draw route to point # \' + number);'."\n";
				$scripttext .=       '}); '."\n";
				
				if (isset($currentrouter->showpanel) && (int)$currentrouter->showpanel == 1) 
				{
					$routepanelcount++;
					if (isset($currentrouter->showpaneltotal) && (int)$currentrouter->showpaneltotal == 1) 
					{
						$routepaneltotalcount++;
					}
				}

				if (isset($currentrouter->showpanel) && (int)$currentrouter->showpanel == 1) 
				{
					$scripttext .= 'YMaps.Events.observe('.$routername.', '.$routername.'.Events.Success, function () {'."\n";
					$scripttext .= ' var total_km = '.$routername.'.getDistance();'."\n";
					$scripttext .= ' var total_time = '.$routername.'.getDuration();'."\n";
					$scripttext .= ' total_km = total_km / 1000.;'."\n";
					$scripttext .= ' total_time = total_time / 60.;'."\n";
					$scripttext .= ' total_km = total_km.toFixed(1);'."\n";
					$scripttext .= ' total_time = total_time.toFixed(1);'."\n";

					$scripttext .= '  document.getElementById("YMapsRoutePanel_Total'.$currentArticleId.'").innerHTML = "<p>';
					$scripttext .= JText::_('PLG_ZHYANDEXMAP_MAPROUTER_DETAIL_SHOWPANEL_HDR_TOTAL_KM');
					$scripttext .= ' " + total_km + " ';
					$scripttext .= JText::_('PLG_ZHYANDEXMAP_MAPROUTER_DETAIL_SHOWPANEL_HDR_KM');
					$scripttext .= ', ';
					$scripttext .= JText::_('PLG_ZHYANDEXMAP_MAPROUTER_DETAIL_SHOWPANEL_HDR_TOTAL_TIME');
					if (isset($currentrouter->checktraffic) && (int)$currentrouter->checktraffic == 1)
					{
						$scripttext .= ' '.JText::_('PLG_ZHYANDEXMAP_MAPROUTER_DETAIL_SHOWPANEL_HDR_JAM');
					}					
					$scripttext .= ' " + total_time + " ';
					$scripttext .= JText::_('PLG_ZHYANDEXMAP_MAPROUTER_DETAIL_SHOWPANEL_HDR_TIME');
					$scripttext .= '</p>";' ."\n";
					$scripttext .= '}); '."\n";
				}
				
			}
			
			if ($currentrouter->routebymarker != "")
			{
				$router2name ='';
				$router2name = 'routeByMarker'. $currentrouter->id;
				
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
								$wp_start .= ' '.$currouteLatLng.''."\n";
							}
							else if ($cs_idx == $cs_total)
							{
								$wp_end .= ', '.$currouteLatLng.' '."\n";
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
					
					$scripttext .= ' var '.$router2name.' = new YMaps.Router(['.$routeToDraw.'],[],'."\n";
					$scripttext .=       '{ ';
					if (isset($currentrouter->showtype) && (int)$currentrouter->showtype == 1)
					{
						$scripttext .=       ' viewAutoApply: false ';
					}
					else
					{
						$scripttext .=       ' viewAutoApply: true ';
					}
					if (isset($currentrouter->checktraffic) && (int)$currentrouter->checktraffic == 1)
					{
						$scripttext .=       ', avoidTrafficJams: true ';
					}
					else
					{
						$scripttext .=       ', avoidTrafficJams: false ';
					}
					$scripttext .=       '});'."\n";
					$scripttext .=       'map'.$currentArticleId.'.addOverlay('.$router2name.');'."\n";
					$scripttext .=       'YMaps.Events.observe('.$router2name.', '.$router2name.'.Events.GeocodeError, function (link, number) {'."\n";
					$scripttext .=       '   alert(\''.JText::_('PLG_ZHYANDEXMAP_MAP_ERROR_GEOCODING').'\' + number);'."\n";
					$scripttext .=       '}); '."\n";
					$scripttext .=       'YMaps.Events.observe('.$router2name.', '.$router2name.'.Events.RouteError, function (number) {'."\n";
					$scripttext .=       '   alert(\''.JText::_('PLG_ZHYANDEXMAP_MAP_ERROR_UNABLE_DRAW_ROUTE').'\' + number);'."\n";
					$scripttext .=       '}); '."\n";

					if (isset($currentrouter->showpanel) && (int)$currentrouter->showpanel == 1) 
					{
						$routepanelcount++;
						if (isset($currentrouter->showpaneltotal) && (int)$currentrouter->showpaneltotal == 1) 
						{
							$routepaneltotalcount++;
						}
					}

					if (isset($currentrouter->showpanel) && (int)$currentrouter->showpanel == 1) 
					{
						$scripttext .= 'YMaps.Events.observe('.$router2name.', '.$router2name.'.Events.Success, function () {'."\n";
						$scripttext .= ' var total_km = '.$router2name.'.getDistance();'."\n";
						$scripttext .= ' var total_time = '.$router2name.'.getDuration();'."\n";
						$scripttext .= ' total_km = total_km / 1000.;'."\n";
						$scripttext .= ' total_time = total_time / 60.;'."\n";
						$scripttext .= ' total_km = total_km.toFixed(1);'."\n";
						$scripttext .= ' total_time = total_time.toFixed(1);'."\n";

						$scripttext .= '  document.getElementById("YMapsRoutePanel_Total'.$currentArticleId.'").innerHTML = "<p>';
						$scripttext .= JText::_('PLG_ZHYANDEXMAP_MAPROUTER_DETAIL_SHOWPANEL_HDR_TOTAL_KM');
						$scripttext .= ' " + total_km + " ';
						$scripttext .= JText::_('PLG_ZHYANDEXMAP_MAPROUTER_DETAIL_SHOWPANEL_HDR_KM');
						$scripttext .= ', ';
						$scripttext .= JText::_('PLG_ZHYANDEXMAP_MAPROUTER_DETAIL_SHOWPANEL_HDR_TOTAL_TIME');
						if (isset($currentrouter->checktraffic) && (int)$currentrouter->checktraffic == 1)
						{
							$scripttext .= ' '.JText::_('PLG_ZHYANDEXMAP_MAPROUTER_DETAIL_SHOWPANEL_HDR_JAM');
						}					
						$scripttext .= ' " + total_time + " ';
						$scripttext .= JText::_('PLG_ZHYANDEXMAP_MAPROUTER_DETAIL_SHOWPANEL_HDR_TIME');
						$scripttext .= '</p>";' ."\n";
						$scripttext .= '}); '."\n";
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
				$scripttext .= 'var '.$kml1.' = new YMaps.YMapsML("'.$currentrouter->kmllayerymapsml.'");' ."\n";
				$scripttext .= 'map'.$currentArticleId.'.addOverlay('.$kml1.');' ."\n";

				$scripttext .= 'YMaps.Events.observe('.$kml1.', '.$kml1.'.Events.Fault, function ('.$kml1.', error) {' ."\n";
				$scripttext .= '    alert(\''.JText::_('PLG_ZHYANDEXMAP_MAP_ERROR_YMAPSML').'\' + error);' ."\n";
				//$scripttext .= '    alert("Error: " + error);' ."\n";
				$scripttext .= '});' ."\n";
				
			}

			if ($currentrouter->kmllayerkml != "")
			{
				$kml2 = 'KML'.$routername;
				$scripttext .= 'var '.$kml2.' = new YMaps.KML("'.$currentrouter->kmllayerkml.'");' ."\n";
				$scripttext .= 'map'.$currentArticleId.'.addOverlay('.$kml2.');' ."\n";
				
				$scripttext .= 'YMaps.Events.observe('.$kml2.', '.$kml2.'.Events.Fault, function ('.$kml2.', error) {' ."\n";
                $scripttext .= '	alert(\''.JText::_('PLG_ZHYANDEXMAP_MAP_ERROR_KML').'\' + error);' ."\n";
				//$scripttext .= '    alert("Error: " + error);' ."\n";
				$scripttext .= '});' ."\n";
			}

			if ($currentrouter->kmllayergpx != "")
			{
				$kml3 = 'GPX'.$routername;
				$scripttext .= 'var '.$kml3.' = new YMaps.GPX("'.$currentrouter->kmllayergpx.'");' ."\n";
				$scripttext .= 'map'.$currentArticleId.'.addOverlay('.$kml3.');' ."\n";

				$scripttext .= 'YMaps.Events.observe('.$kml3.', '.$kml3.'.Events.Fault, function ('.$kml3.', error) {' ."\n";
				$scripttext .= '    alert(\''.JText::_('PLG_ZHYANDEXMAP_MAP_ERROR_GPX').'\' + error);' ."\n";
				//$scripttext .= '    alert("Error: " + error);' ."\n";
				$scripttext .= '});' ."\n";
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
			if ((int)$currentpath->objecttype == 0)
			{		
				$scripttext .= ' var spth'.$currentpath->id.' = new YMaps.Style();'."\n";
				$scripttext .= ' spth'.$currentpath->id.'.lineStyle = new YMaps.LineStyle();'."\n";
				$scripttext .= ' spth'.$currentpath->id.'.lineStyle.strokeColor = \''.$currentpath->color.'\';'."\n";
				$scripttext .= ' spth'.$currentpath->id.'.lineStyle.strokeWidth = \''.$currentpath->width.'\';'."\n";
				$scripttext .= ' YMaps.Styles.add("custom#PolyLine'.$currentpath->id.'", spth'.$currentpath->id.');'."\n";

				$scripttext .= ' var pl'.$currentpath->id.' = new YMaps.Polyline([ '."\n";
				$scripttext .=' new YMaps.GeoPoint('.str_replace(";","), new YMaps.GeoPoint(", $currentpath->path).') '."\n";
				$scripttext .= ' ]); '."\n";

				$scripttext .= 'pl'.$currentpath->id.'.setStyle("custom#PolyLine'.$currentpath->id.'");'."\n";
				$scripttext .= 'pl'.$currentpath->id.'.setBalloonContent("'.htmlspecialchars(str_replace('\\', '/', $currentpath->title), ENT_QUOTES, 'UTF-8').'");' ."\n";
				$scripttext .= 'map'.$currentArticleId.'.addOverlay(pl'.$currentpath->id.');'."\n";
			}
		}
	}



	$context_suffix = 'map';

	if ($map->kmllayer != "")
	{
		$kml1 = 'YMapsML'.$context_suffix;
		$scripttext .= 'var '.$kml1.' = new YMaps.YMapsML("'.$map->kmllayer.'");' ."\n";
		$scripttext .= 'map'.$currentArticleId.'.addOverlay('.$kml1.');' ."\n";

		$scripttext .= 'YMaps.Events.observe('.$kml1.', '.$kml1.'.Events.Fault, function ('.$kml1.', error) {' ."\n";
		$scripttext .= '    alert(\''.JText::_('PLG_ZHYANDEXMAP_MAP_ERROR_YMAPSML').'\' + error);' ."\n";
		//$scripttext .= '    alert("Error: " + error);' ."\n";
		$scripttext .= '});' ."\n";
		
	}

	if ($map->kmllayerkml != "")
	{
		$kml2 = 'KML'.$context_suffix;
		$scripttext .= 'var '.$kml2.' = new YMaps.KML("'.$map->kmllayerkml.'");' ."\n";
		$scripttext .= 'map'.$currentArticleId.'.addOverlay('.$kml2.');' ."\n";
		
		$scripttext .= 'YMaps.Events.observe('.$kml2.', '.$kml2.'.Events.Fault, function ('.$kml2.', error) {' ."\n";
		$scripttext .= '	alert(\''.JText::_('PLG_ZHYANDEXMAP_MAP_ERROR_KML').'\' + error);' ."\n";
		//$scripttext .= '    alert("Error: " + error);' ."\n";
		$scripttext .= '});' ."\n";
	}

	if ($map->kmllayergpx != "")
	{
		$kml3 = 'GPX'.$context_suffix;
		$scripttext .= 'var '.$kml3.' = new YMaps.GPX("'.$map->kmllayergpx.'");' ."\n";
		$scripttext .= 'map'.$currentArticleId.'.addOverlay('.$kml3.');' ."\n";

		$scripttext .= 'YMaps.Events.observe('.$kml3.', '.$kml3.'.Events.Fault, function ('.$kml3.', error) {' ."\n";
		$scripttext .= '    alert(\''.JText::_('PLG_ZHYANDEXMAP_MAP_ERROR_GPX').'\' + error);' ."\n";
		//$scripttext .= '    alert("Error: " + error);' ."\n";
		$scripttext .= '});' ."\n";
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
						//$scripttext .= '	var toShowDiv = document.getElementById("YMapsMarkerList'.$currentArticleId.'");' ."\n";
						//$scripttext .= '	toShowDiv.style.display = "block";' ."\n";
						$scripttext .= '    YMaps.Events.notify(btnPlacemarkList'.$currentArticleId.', btnPlacemarkList'.$currentArticleId.'.Events.Select);' ."\n";
						
					break;
					case 12:
						//$scripttext .= '	var toShowDiv = document.getElementById("YMapsMarkerList");' ."\n";
						//$scripttext .= '	toShowDiv.style.display = "block";' ."\n";
						$scripttext .= '    YMaps.Events.notify(btnPlacemarkList'.$currentArticleId.', btnPlacemarkList'.$currentArticleId.'.Events.Select);' ."\n";
					break;
					default:
						$scripttext .= '';
					break;
				}
			}
								
		}	
	}
	// Open Placemark List Presets

	
	$scripttext .= '});' ."\n";
// End initialize jquery function


	// Geo Position - Begin
	if ((isset($map->autoposition) && (int)$map->autoposition == 1)
	 || (isset($map->autopositioncontrol) && (int)$map->autopositioncontrol != 0))
	{
			$scripttext .= 'function findMyPosition'.$currentArticleId.'(AutoPosition) {' ."\n";
			$scripttext .= '     if (AutoPosition == "Button")' ."\n";
			$scripttext .= '     {' ."\n";
        	$scripttext .= '        if (YMaps.location) ' ."\n";
			$scripttext .= '        {' ."\n";
	        $scripttext .= '        	p_center = new YMaps.GeoPoint(YMaps.location.longitude, YMaps.location.latitude);' ."\n";
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
        	$scripttext .= '        if (YMaps.location) ' ."\n";
			$scripttext .= '        {' ."\n";
	        $scripttext .= '        	p_center = new YMaps.GeoPoint(YMaps.location.longitude, YMaps.location.latitude);' ."\n";
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
			$scripttext .= '    map'.$currentArticleId.'.removeOverlay(geoResult'.$currentArticleId.');' ."\n";

			// Geocoding
			$scripttext .= '   if ((map'.$currentArticleId.'.getType() == YMaps.MapType.PMAP) || (map'.$currentArticleId.'.getType() == YMaps.MapType.PHYBRID))';
			$scripttext .= '   {';
			$scripttext .= '     var geocoder'.$currentArticleId.' = new YMaps.Geocoder(value, {results: 1, boundedBy: map'.$currentArticleId.'.getBounds(), geocodeProvider:"yandex#pmap"});' ."\n";
			$scripttext .= '   }';
			$scripttext .= '   else';
			$scripttext .= '   {';
			$scripttext .= '     var geocoder'.$currentArticleId.' = new YMaps.Geocoder(value, {results: 1, boundedBy: map'.$currentArticleId.'.getBounds()});' ."\n";
			$scripttext .= '   }';

			// Success geocoding
			$scripttext .= '   YMaps.Events.observe(geocoder'.$currentArticleId.', geocoder'.$currentArticleId.'.Events.Load, function () {' ."\n";
			// if find then add to map
			// set center map
			$scripttext .= '        if (this.length()) ' ."\n";
			$scripttext .= '		{' ."\n";
			$scripttext .= '            geoResult'.$currentArticleId.' = this.get(0);' ."\n";
			$scripttext .= '            map'.$currentArticleId.'.addOverlay(geoResult'.$currentArticleId.');' ."\n";
			$scripttext .= '            map'.$currentArticleId.'.setBounds(geoResult'.$currentArticleId.'.getBounds());' ."\n";
			// add route
			if (isset($map->findroute) && (int)$map->findroute == 1) 
			{			
				$scripttext .= '            getMyMapRoute'.$currentArticleId.'(geoResult'.$currentArticleId.'.getGeoPoint()); '."\n";
			}
			// end add route
			$scripttext .= '        }' ."\n";
			$scripttext .= '		else ' ."\n";
			$scripttext .= '		{' ."\n";
			$scripttext .= '            alert("'.JText::_('PLG_ZHYANDEXMAP_MAP_ERROR_FIND_GEOCODING').'");' ."\n";
			$scripttext .= '        }' ."\n";
			$scripttext .= '    });' ."\n";

			// Failure geocoding
			$scripttext .= '    YMaps.Events.observe(geocoder'.$currentArticleId.', geocoder'.$currentArticleId.'.Events.Fault, function (geocoder'.$currentArticleId.', error) {' ."\n";
			$scripttext .= '        alert("'.JText::_('PLG_ZHYANDEXMAP_MAP_ERROR_FIND_GEOCODING_ERROR').'" + error);' ."\n";
			$scripttext .= '    })' ."\n";
			$scripttext .= '};' ."\n";
	}
	// Find option End


		if (isset($map->findroute) && (int)$map->findroute == 1) 
		{
		
			$scripttext .= 'function getMyMapRoute'.$currentArticleId.'(curposition) {'."\n";
			$scripttext .= '       map'.$currentArticleId.'.removeOverlay(geoRoute'.$currentArticleId.');' ."\n";
			$scripttext .= '       geoRoute'.$currentArticleId.' = new YMaps.Router([curposition, mapcenter'.$currentArticleId.'],[],'."\n";
			$scripttext .= '       { viewAutoApply: true });'."\n";
			$scripttext .= '       map'.$currentArticleId.'.addOverlay(geoRoute'.$currentArticleId.');'."\n";
			
			$scripttext .= '       YMaps.Events.observe(geoRoute'.$currentArticleId.', geoRoute'.$currentArticleId.'.Events.Success, function () {'."\n";
			$scripttext .= '       		geoRoute'.$currentArticleId.'.getWayPoint(0).setIconContent(\''.JText::_('PLG_ZHYANDEXMAP_MAP_FIND_GEOCODING_START_POINT').'\');'."\n";
			
			$scripttext .= '       		geoRoute'.$currentArticleId.'.getWayPoint(1).setIconContent(\''.JText::_('PLG_ZHYANDEXMAP_MAP_FIND_GEOCODING_END_POINT').'\');'."\n";
			$scripttext .= '       		geoRoute'.$currentArticleId.'.getWayPoint(1).setBalloonContent(\''.JText::_('PLG_ZHYANDEXMAP_MAP_FIND_GEOCODING_END_POINT').'\');'."\n";
			$scripttext .= '       });'."\n";	
			
			$scripttext .= '       YMaps.Events.observe(geoRoute'.$currentArticleId.', geoRoute'.$currentArticleId.'.Events.GeocodeError, function (link, number) {'."\n";
			$scripttext .= '         alert(\''.JText::_('PLG_ZHYANDEXMAP_MAP_ERROR_GEOCODING').'\' + number);'."\n";
			$scripttext .= '       }); '."\n";
			
			$scripttext .= '       YMaps.Events.observe(geoRoute'.$currentArticleId.', geoRoute'.$currentArticleId.'.Events.RouteError, function (link, number) {'."\n";
			$scripttext .= '          alert(\''.JText::_('PLG_ZHYANDEXMAP_MAP_ERROR_UNABLE_DRAW_ROUTE').'\' + number);'."\n";
			$scripttext .= '       }); '."\n";
			$scripttext .= '}'."\n";
		}
	
	$scripttext .= '/*]]>*/</script>' ."\n";
	// Script end


	$this->scripttext = $scripttext;
	$this->scripthead = $scripthead;
	if ($loadmodules != "")
	{
		$this->loadmodules = $loadmodules;
	}

