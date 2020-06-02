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

jimport( 'joomla.plugin.plugin' );

JLoader::register('comZhYandexMapData', JPATH_SITE.'/components/com_zhyandexmap/helpers/map_data.php'); 

class plgContentPlg_ZhYandexMap extends JPlugin
{	
	
	var $loadmodules;

	var $scriptlink;
	var $scripthead;
	var $scripttext;
	
	var $compatiblemodersf;
	var $compatiblemode;
	var $httpsprotocol;
	var $placemarktitletag;
	
	var $apikey;
	
	var $licenseinfo;

	// ***** Init Section Begin ***********************************
	var $MapXsuffix = "ZhYMPLG";

	var $use_object_manager = 0;

	var $current_custom_js_path;			

	// ***** Init Section End *************************************		


	public function onContentPrepare($context, &$article, &$params, $limitstart)
	{

		$parameterDefaultLine = ';;;;;;;;;;;;;;;;;;;;';

		$app = JFactory::getApplication();
		
		$comparams = JComponentHelper::getParams( 'com_zhyandexmap' );
		
		$this->compatiblemodersf = $comparams->get( 'map_compatiblemode_rsf');
		$this->compatiblemode = $comparams->get( 'map_compatiblemode');
		$this->placemarktitletag = $comparams->get('placemarktitletag');
		
		if ($this->compatiblemodersf == 0)
		{
			$imgpathLightbox = JURI::root() .'administrator/components/com_zhyandexmap/assets/lightbox/';
		}
		else
		{
			$imgpathLightbox = JURI::root() .'components/com_zhyandexmap/assets/lightbox/';
		}
		
		$this->licenseinfo = $comparams->get('licenseinfo');
		if ($this->licenseinfo == "")
		{
		  $this->licenseinfo = 102;
		}	
		
		$this->apikey = $comparams->get( 'map_key');

		$this->componentApiVersion = $comparams->get( 'map_api_version');
		
		if ($this->componentApiVersion == "")
		{
			$this->componentApiVersion = '2.x';
		}
		$this->httpsprotocol = $comparams->get( 'httpsprotocol');
		
		$this->urlProtocol = "http";
		if ($this->httpsprotocol != "")
		{
			if ((int)$this->httpsprotocol == 1)
			{
				$this->urlProtocol = 'https';
			}
		}		
		

		$this->current_custom_js_path = JURI::root() .'components/com_zhyandexmap/assets/js/';
		$current_custom_js_path = $this->current_custom_js_path;	
		
		$this->useObjectStructure = 1;	
			
		$document = JFactory::getDocument();

		// Load plugin language
		JPlugin::loadLanguage();

		
		require_once JPATH_SITE . '/plugins/content/plg_zhyandexmap/helpers/placemarks.php';		
		
		$regexLght		= '/({zhyandexmap-lightbox:\s*)(.*?)(})/is';
		$matchesLght 		= array();
		$count_matches_Lght	= preg_match_all($regexLght, $article->text, $matchesLght, PREG_PATTERN_ORDER | PREG_OFFSET_CAPTURE);

		$regexMrk		= '/({zhyandexmap-marker:\s*)(.*?)(})/is';
		$matchesMrk 		= array();
		$count_matches_Mrk	= preg_match_all($regexMrk, $article->text, $matchesMrk, PREG_PATTERN_ORDER | PREG_OFFSET_CAPTURE);

		$regexMrList		= '/({zhyandexmap-markerlist:\s*)(.*?)(})/is';
		$matchesMrList 		= array();
		$count_matches_MrList	= preg_match_all($regexMrList, $article->text, $matchesMrList, PREG_PATTERN_ORDER | PREG_OFFSET_CAPTURE);
		
		$regexGrp		= '/({zhyandexmap-group:\s*)(.*?)(})/is';
		$matchesGrp 		= array();
		$count_matches_Grp	= preg_match_all($regexGrp, $article->text, $matchesGrp, PREG_PATTERN_ORDER | PREG_OFFSET_CAPTURE);

		$regexCategory		= '/({zhyandexmap-category:\s*)(.*?)(})/is';
		$matchesCategory 		= array();
		$count_matches_Category	= preg_match_all($regexCategory, $article->text, $matchesCategory, PREG_PATTERN_ORDER | PREG_OFFSET_CAPTURE);
		
		$regexMap		= '/({zhyandexmap:\s*)(.*?)(})/is';
		$matchesMap 		= array();
		$count_matches_Map	= preg_match_all($regexMap, $article->text, $matchesMap, PREG_PATTERN_ORDER | PREG_OFFSET_CAPTURE);

		if (($count_matches_Map > 0) ||
		    ($count_matches_Mrk > 0) ||
		    ($count_matches_MrList > 0) ||
		    ($count_matches_Grp > 0) ||
		    ($count_matches_Category > 0)||
		    ($count_matches_Lght > 0))
		{

			// Begin loop for Map
			for($i = 0; $i < $count_matches_Map; $i++) 
			{
			  //$article->text .= "\n" .'<br />-1-'. $matches[0][$i][0];
 			  //$article->text .= "\n" .'<br />-2-'. $matches[1][$i][0];
			  //$article->text .= "\n" .'<br />-3-'. $matches[2][$i][0];
			  //$article->text .= "\n" .'<br />-4-'. $matches[3][$i][0];
			    if (property_exists($article, "id"))
				{
					$cur_article_id = $article->id;
				}
				else
				{
					$cur_article_id ="";
				}

				$compoundID = str_replace('#','_', str_replace('.', '_', $context.'#'.$cur_article_id .'#'.$i));
				$pars = explode(";", $matchesMap[2][$i][0].$parameterDefaultLine);
				$basicID = $pars[0];
				$compoundID .= '_'.$basicID.'_'.'map';

				if ($this->getMap($matchesMap[2][$i][0], $compoundID, "0", "0", "0", "0", "0", "0"))
				{
					$patternsMap = '/'.$matchesMap[0][$i][0].'/';
					if ($this->scriptlink !="") {
					    $document->addScript($this->scriptlink . $this->loadmodules);
					}
					$replacementsMap = $this->scripthead . $this->scripttext; //'call='.$i ;
					$article->text = preg_replace($patternsMap, $replacementsMap, $article->text, 1);
				}
			}
			// End loop for Map

			// Begin loop for Marker
			for($i = 0; $i < $count_matches_Mrk; $i++) 
			{
			    if (property_exists($article, "id"))
				{
					$cur_article_id = $article->id;
				}
				else
				{
					$cur_article_id ="";
				}

				$compoundID = str_replace('#','_', str_replace('.', '_', $context.'#'.$cur_article_id .'#'.$i));
				$pars = explode(";", $matchesMrk[2][$i][0].$parameterDefaultLine);
				$basicID = $pars[0];
				$compoundID .= '_'.$basicID.'_'.'mrk';

				if ($this->getMap("0", $compoundID, $matchesMrk[2][$i][0], "0", "0", "0", "0", "0"))
				{
					$patternsMrk = '/'.$matchesMrk[0][$i][0].'/';
					if ($this->scriptlink !="") {
					    $document->addScript($this->scriptlink . $this->loadmodules);
					}
					$replacementsMrk = $this->scripthead . $this->scripttext; 
					$article->text = preg_replace($patternsMrk, $replacementsMrk, $article->text, 1);
				}
			}
			// End loop for Marker
			
			// Begin loop for Group
			for($i = 0; $i < $count_matches_Grp; $i++) 
			{
			    if (property_exists($article, "id"))
				{
					$cur_article_id = $article->id;
				}
				else
				{
					$cur_article_id ="";
				}

				$compoundID = str_replace('#','_', str_replace('.', '_', $context.'#'.$cur_article_id .'#'.$i));
				$pars = explode(";", $matchesGrp[2][$i][0].$parameterDefaultLine);
				$basicID = 0; //$pars[0]; -- this is list now
				$compoundID .= '_'.$basicID.'_'.'grp';

				if ($this->getMap("0", $compoundID, "0", $matchesGrp[2][$i][0], "0", "0", "0", "0"))
				{
					$patternsGrp = '/'.$matchesGrp[0][$i][0].'/';
					if ($this->scriptlink !="") {
					    $document->addScript($this->scriptlink . $this->loadmodules);
					}
					$replacementsGrp = $this->scripthead . $this->scripttext; 
					$article->text = preg_replace($patternsGrp, $replacementsGrp, $article->text, 1);
				}
			}
			// End loop for Group
			
			// Begin loop for Category
			for($i = 0; $i < $count_matches_Category; $i++) 
			{
			    if (property_exists($article, "id"))
				{
					$cur_article_id = $article->id;
				}
				else
				{
					$cur_article_id ="";
				}

				$compoundID = str_replace('#','_', str_replace('.', '_', $context.'#'.$cur_article_id .'#'.$i));
				$pars = explode(";", $matchesCategory[2][$i][0].$parameterDefaultLine);
				$basicID = 0; //$pars[0]; -- this is list now
				$compoundID .= '_'.$basicID.'_'.'cat';

				if ($this->getMap("0", $compoundID, "0", "0", $matchesCategory[2][$i][0], "0", "0", "0"))
				{
					$patternsCategory = '/'.$matchesCategory[0][$i][0].'/';
					if ($this->scriptlink !="") {
					    $document->addScript($this->scriptlink . $this->loadmodules);
					}
					$replacementsCategory = $this->scripthead . $this->scripttext; 
					$article->text = preg_replace($patternsCategory, $replacementsCategory, $article->text, 1);
				}
			}
			// End loop for Category

			// Begin loop for MarkerList
			for($i = 0; $i < $count_matches_MrList; $i++) 
			{
			    if (property_exists($article, "id"))
				{
					$cur_article_id = $article->id;
				}
				else
				{
					$cur_article_id ="";
				}

				$compoundID = str_replace('#','_', str_replace('.', '_', $context.'#'.$cur_article_id .'#'.$i));
				$pars = explode(";", $matchesMrList[2][$i][0].$parameterDefaultLine);
				$basicID = 0; //$pars[0] - this is a placemark list;
				$compoundID .= '_'.$basicID.'_'.'mrlist';

				if ($this->getMap("0", $compoundID, "0", "0", "0", $matchesMrList[2][$i][0], "0", "0"))
				{
					$patternsMrList = '/'.$matchesMrList[0][$i][0].'/';
					if ($this->scriptlink !="") {
					    $document->addScript($this->scriptlink . $this->loadmodules);
					}
					$replacementsMrList = $this->scripthead . $this->scripttext; 
					$article->text = preg_replace($patternsMrList, $replacementsMrList, $article->text, 1);
				}
			}
			// End loop for MarkerList
			
			// Begin loop for Lightbox
			for($i = 0; $i < $count_matches_Lght; $i++) 
			{

				$pars = explode(";", $matchesLght[2][$i][0].$parameterDefaultLine);
				$mapid = $pars[0];
				$popupTitle = htmlspecialchars($pars[1], ENT_QUOTES, 'UTF-8');
				$mapwidth = $pars[2];
				$mapheight = $pars[3];
				$mapimage = $pars[4];
				$placemarkListIds = $pars[5];
				
			
				JHTML::_('behavior.modal', 'a.zhym-modal-button');

				if ((!isset($mapwidth)) || (isset($mapwidth) && (int)$mapwidth < 1)) 
				{
					$popupWidth = 700;
				}
				else
				{
					$popupWidth = (int)$mapwidth;
				}
				
				if ((!isset($mapheight)) || (isset($mapheight) && (int)$mapheight < 1)) 
				{
					$popupHeight = 500;
				}
				else
				{
					$popupHeight = (int)$mapheight;
				}
				if ((!isset($popupTitle) || (isset($popupTitle) && $popupTitle ==""))
				 && (!isset($mapimage) || (isset($mapimage) && $mapimage =="")))
				{
					$popupTitle = JText::_('PLG_ZHYANDEXMAP_MAP_LIGHTBOX_SHOW_MAP');
					//$popupTitle = 'Показать карту';
				}
				
				if (isset($mapimage) && $mapimage !="")
				{
					$popupImage = '<img src="'.$imgpathLightbox.$mapimage.'" alt="" />';
				}
				else
				{
					$popupImage = '';
				}

				if (isset($mapid) && (int)$mapid != 0)
				{
					$popupOptions = "{handler: 'iframe', size: {x: ".$popupWidth.", y: ".$popupHeight."} }";
					$popupCall = JRoute::_('index.php?option=com_zhyandexmap&amp;view=zhyandexmap&amp;tmpl=component&amp;id='.(int)$mapid.'&amp;placemarklistid='.$placemarkListIds);

					$replacementsLght = '<a class="zhym-modal-button" title="'.$popupTitle.'" href="'.$popupCall.'" rel="'.$popupOptions.'">'.$popupImage.$popupTitle.'</a>';
					
					$patternsLght = '/'.$matchesLght[0][$i][0].'/';
					
					$article->text = preg_replace($patternsLght, $replacementsLght, $article->text, 1);
				}
				
			}
			// End loop for Lightbox
                        
		}



		return true;

	}

	function getMap($mapIdWithPars, 
			$currentArticleId, 
			$placemarkIdWithPars, 
			$groupIdWithPars, 
			$categoryIdWithPars, 
			$placemarkListWithPars, 
			$routeIdWithPars, 
			$pathIdWithPars)
	{      
		$parameterDefaultLine = ';;;;;;;;;;;;;;;;;;;;';

		if ($this->compatiblemodersf == 0)
		{
			$imgpathIcons = JURI::root() .'administrator/components/com_zhyandexmap/assets/icons/';
			$imgpathUtils = JURI::root() .'administrator/components/com_zhyandexmap/assets/utils/';
			$imgpathLightbox = JURI::root() .'administrator/components/com_zhyandexmap/assets/lightbox/';
		}
		else
		{
			$imgpathIcons = JURI::root() .'components/com_zhyandexmap/assets/icons/';
			$imgpathUtils = JURI::root() .'components/com_zhyandexmap/assets/utils/';
			$imgpathLightbox = JURI::root() .'components/com_zhyandexmap/assets/lightbox/';
		}
		

		// Value in (placemark, map)
		$currentCenter = "map";
		$currentPlacemarkCenter = "do not change";
		// Value in (1.., do not change)
		$currentZoom = "do not change";

		// Map Type Value
		$currentMapType ="do not change";
		$currentMapTypeValue ="";

		// Size Value 
		$currentMapWidth ="do not change";
		$currentMapHeight ="do not change";
		
		$hiddenContainer ='';
		
		if (($mapIdWithPars == "0") &&
		    ($placemarkIdWithPars == "0") &&
		    ($routeIdWithPars == "0") &&
		    ($pathIdWithPars == "0") &&			
		    ($placemarkListWithPars == "0") &&
		    ($groupIdWithPars == "0") &&
		    ($categoryIdWithPars =="0")
		    )
		{
			return false;
		}

		$app = JFactory::getApplication();
		$db = JFactory::getDBO();

		
		$addFilterWhere = "";
	
        if ($mapIdWithPars !="0")
        {

			$pars = explode(";", $mapIdWithPars.$parameterDefaultLine);
			$mapId = $pars[0];
			$hiddenContainer = $pars[1];

			if ((int)$mapId == 0)
			{
				return false;
			}
			else
			{
				$placemarklistid = "";
				$explacemarklistid = "";
				$grouplistid = "";
				$categorylistid = "";

				$centerplacemarkid = "";
				$centerplacemarkaction = "";
				$centerplacemarkactionid = "";
				$externalmarkerlink = "";
				

				$map = comZhYandexMapData::getMap((int)$mapId);

				if (isset($map) && (int)$map->id != 0)
				{

					// under construction ///////////////////////
				    
					$map->markerspinner = 0;
					$map->placemark_rating = 0;
					$map->markergroupctlpath = 0;
					$map->markergroupctlmarker = 1;
					$map->markergrouptype = 0;
					$map->showcreateinfo = 0;
					$map->markerorder = 0;
					$map->markergrouporder = 0;
					
					// //////////////////////////////////////////
					
					$usermarkersfilter = "";
					
					// addition parameters
					if ($usermarkersfilter == "")
					{
						$usermarkersfilter = (int)$map->usermarkersfilter;
					}
					else
					{
						$usermarkersfilter = (int)$usermarkersfilter;
					}
								
					if ($map->useajaxobject == 0)
					{
						$markers = comZhYandexMapData::getMarkers($map->id, $placemarklistid, $explacemarklistid, $grouplistid, $categorylistid, 
												    $map->usermarkers, $usermarkersfilter, $map->usercontact, $map->markerorder);
					}
					else
					{
						unset($markers);
					}
					
					$paths = comZhYandexMapData::getPaths($map->id, "","","","");
					$routers = comZhYandexMapData::getRouters($map->id, "","","","");
					$maptypes = comZhYandexMapData::getMapTypes();
					
					$markergroups = comZhYandexMapData::getMarkerGroups($map->id, $placemarklistid, $explacemarklistid, $grouplistid, $categorylistid, $map->markergrouporder);
					$mgrgrouplist = comZhYandexMapData::getMarkerGroupsManage($map->id, 
												    $placemarklistid, $explacemarklistid, $grouplistid, $categorylistid, 
												    $map->markergrouporder, $map->markergroupctlmarker, $map->markergroupctlpath, 
												    "","","","");
				}
				else
				{
					return false;
				}
		
			}
        }
        else if ($placemarkIdWithPars !="0") 
        {
			$pars = explode(";", $placemarkIdWithPars.$parameterDefaultLine);
			$placemarkId = $pars[0];
			$placemarkCenter = $pars[1];
			$placemarkZoom = $pars[2];
			$placemarkMapType = $pars[3];
			$placemarkMapWidth = $pars[4];
			$placemarkMapHeight = $pars[5];
			$hiddenContainer = $pars[6];

			if ($placemarkCenter != "")
			{
				switch ($placemarkCenter)
				{
					case "map":
					  $currentCenter = "map";
					break;
					case "placemark":
					  $currentCenter = "placemark";
					    
					    $mapCenterLatLng = comZhYandexMapData::getMarkerCoordinatesLatLngObject((int)$placemarkId);
					    if ($mapCenterLatLng != "")
					    {
						    $currentPlacemarkCenter = (int)$placemarkId;

						    if ($mapCenterLatLng == "geocode")
						    {
							    $currentCenter = "map";
						    }
						    else
						    {
							    $currentCenter = $mapCenterLatLng;
						    }
					    }
					    else
					    {
						    $currentCenter = "map";
					    }
					break;
					default:
					  $currentCenter = "map";
					break;
				}
				
			}

			if ($placemarkZoom != "")
			{
				  $currentZoom = plgZhYandexMapPlacemarksHelper::parseZoom($placemarkZoom);
			}

			if ($placemarkMapType != "")
			{
			  $currentMapType = plgZhYandexMapPlacemarksHelper::parseMapType($placemarkMapType);
			}

			if ($placemarkMapWidth != "")
			{
				$currentMapWidth = $placemarkMapWidth;
			}
			
			if ($placemarkMapHeight != "")
			{
				$currentMapHeight = $placemarkMapHeight;
			}
			
			if ((int)$placemarkId == 0)
			{
				return false;
			}
			else
			{
	
				$query = $db->getQuery(true);
				$query->select('h.*')
					->from('#__zhyandexmaps_maps as h')
					->leftJoin('#__zhyandexmaps_markers as m ON h.id=m.mapid')
					->where('m.id = '.(int)$placemarkId);

				$nullDate = $db->Quote($db->getNullDate());
				$nowDate = $db->Quote(JFactory::getDate()->toSQL());
				$query->where('(m.publish_up = ' . $nullDate . ' OR m.publish_up <= ' . $nowDate . ')');
				$query->where('(m.publish_down = ' . $nullDate . ' OR m.publish_down >= ' . $nowDate . ')');
					
				$db->setQuery($query);        
				$map = $db->loadObject();
				
								$placemarklistid = (int) $placemarkId;
				$explacemarklistid = "";
				$grouplistid = "";
				$categorylistid = "";

				// it will be recalculated later -- begin
				$centerplacemarkid = "";
				$centerplacemarkaction = "";
				$centerplacemarkactionid = "";
				// it will be recalculated later -- end
				$externalmarkerlink = "";
				
				if (isset($map) && (int)$map->id != 0)
				{
					// under construction ///////////////////////
				    
					$map->markerspinner = 0;
					$map->placemark_rating = 0;
					$map->markergroupctlpath = 0;
					$map->markergroupctlmarker = 1;
					$map->markergrouptype = 0;
					$map->showcreateinfo = 0;
					$map->markerorder = 0;
					$map->markergrouporder = 0;
					
					// //////////////////////////////////////////
					// 				    
					// 13.11.2014 - disable placemark list
					$map->markerlistpos = 0;
					// 12.08.2015 - disable group management
					$map->markergroupcontrol = 0;
					
					$usermarkersfilter = "";

					// addition parameters
					if ($usermarkersfilter == "")
					{
						$usermarkersfilter = (int)$map->usermarkersfilter;
					}
					else
					{
						$usermarkersfilter = (int)$usermarkersfilter;
					}

					if ($map->useajaxobject == 0)
					{
						$markers = comZhYandexMapData::getMarkers($map->id, $placemarklistid, $explacemarklistid, $grouplistid, $categorylistid, 
												    $map->usermarkers, $usermarkersfilter, $map->usercontact, $map->markerorder);
					}
					else
					{
						unset($markers);
					}
					// change comments to unset
					unset($paths);
					//$paths = comZhYandexMapData::getPaths($map->id, "","","","");
					unset($routers);
					//$routers = comZhYandexMapData::getRouters($map->id, "","","","");
					$maptypes = comZhYandexMapData::getMapTypes();

					$markergroups = comZhYandexMapData::getMarkerGroups($map->id, $placemarklistid, $explacemarklistid, $grouplistid, $categorylistid, $map->markergrouporder);
					$mgrgrouplist = comZhYandexMapData::getMarkerGroupsManage($map->id, 
																			  $placemarklistid, $explacemarklistid, $grouplistid, $categorylistid, 
																			  $map->markergrouporder, $map->markergroupctlmarker, $map->markergroupctlpath, 
																			  "","","","");
				}
				else
				{
					return false;
				}
			}
        }
		else if ($placemarkListWithPars !="0")
		{
			$pars = explode(";", $placemarkListWithPars.$parameterDefaultLine);
			$placemarkListIds = $pars[0];
			$mapId = $pars[1];
			$placemarkListZoom = $pars[2];
			$placemarkListMapType = $pars[3];
			$placemarkListMapWidth = $pars[4];
			$placemarkListMapHeight = $pars[5];
			$hiddenContainer = $pars[6];

			if ($placemarkListZoom != "")
			{
				  $currentZoom = plgZhYandexMapPlacemarksHelper::parseZoom($placemarkListZoom);
			}

			if ($placemarkListMapType != "")
			{
			  $currentMapType = plgZhYandexMapPlacemarksHelper::parseMapType($placemarkListMapType);
			}

			if ($placemarkListMapWidth != "")
			{
				$currentMapWidth = $placemarkListMapWidth;
			}
			
			if ($placemarkListMapHeight != "")
			{
				$currentMapHeight = $placemarkListMapHeight;
			}
			
			if (((int)$mapId == 0) || ($placemarkListIds == ""))
			{
				return false;
			}
			else
			{
				$placemarklistid = $placemarkListIds;
				$explacemarklistid = "";
				$grouplistid = "";
				$categorylistid = "";

				// it will be recalculated later -- begin
				$centerplacemarkid = "";
				$centerplacemarkaction = "";
				$centerplacemarkactionid = "";
				// it will be recalculated later -- end
				$externalmarkerlink = "";

				$map = comZhYandexMapData::getMap((int)$mapId);
													
				if (isset($map) && (int)$map->id != 0)
				{
										
				    // under construction ///////////////////////

				    $map->markerspinner = 0;
				    $map->placemark_rating = 0;
				    $map->markergroupctlpath = 0;
				    $map->markergroupctlmarker = 1;
				    $map->markergrouptype = 0;
				    $map->showcreateinfo = 0;
				    $map->markerorder = 0;
				    $map->markergrouporder = 0;

				    // //////////////////////////////////////////
				    
				    $usermarkersfilter = "";

					// addition parameters
					if ($usermarkersfilter == "")
					{
						$usermarkersfilter = (int)$map->usermarkersfilter;
					}
					else
					{
						$usermarkersfilter = (int)$usermarkersfilter;
					}

					if ($map->useajaxobject == 0)
					{
						$markers = comZhYandexMapData::getMarkers($map->id, $placemarklistid, $explacemarklistid, $grouplistid, $categorylistid, 
												    $map->usermarkers, $usermarkersfilter, $map->usercontact, $map->markerorder);
					}
					else
					{
						unset($markers);
					}					
					$paths = comZhYandexMapData::getPaths($map->id, "","","","");
					$routers = comZhYandexMapData::getRouters($map->id, "","","","");
					$maptypes = comZhYandexMapData::getMapTypes();
					
					$markergroups = comZhYandexMapData::getMarkerGroups($map->id, $placemarklistid, $explacemarklistid, $grouplistid, $categorylistid, $map->markergrouporder);
					$mgrgrouplist = comZhYandexMapData::getMarkerGroupsManage($map->id, 
																			  $placemarklistid, $explacemarklistid, $grouplistid, $categorylistid, 
																			  $map->markergrouporder, $map->markergroupctlmarker, $map->markergroupctlpath, 
																			  "","","","");
				}
				else
				{
					return false;
				}
				
			}
		}
		else if ($groupIdWithPars !="0")
		{
			$pars = explode(";", $groupIdWithPars.$parameterDefaultLine);
			$groupId = $pars[0];
			$mapId = $pars[1];
			$groupZoom = $pars[2];
			$groupMapType = $pars[3];
			$groupMapWidth = $pars[4];
			$groupMapHeight = $pars[5];
			$hiddenContainer = $pars[6];

			if ($groupZoom != "")
			{
				  $currentZoom = plgZhYandexMapPlacemarksHelper::parseZoom($groupZoom);
			}

			if ($groupMapType != "")
			{
			  $currentMapType = plgZhYandexMapPlacemarksHelper::parseMapType($groupMapType);
			}
			if ($groupMapWidth != "")
			{
				$currentMapWidth = $groupMapWidth;
			}
			
			if ($groupMapHeight != "")
			{
				$currentMapHeight = $groupMapHeight;
			}
			
			if ((int)$mapId == 0)
			{
				return false;
			}
			else
			{
				    
				$placemarklistid = "";
				$explacemarklistid = "";
				$grouplistid = $groupId;
				$categorylistid = "";

				// it will be recalculated later -- begin
				$centerplacemarkid = "";
				$centerplacemarkaction = "";
				$centerplacemarkactionid = "";
				// it will be recalculated later -- end
				$externalmarkerlink = "";

				$map = comZhYandexMapData::getMap((int)$mapId);
						
				if (isset($map) && (int)$map->id != 0)
				{


					// under construction ///////////////////////

					$map->markerspinner = 0;
					$map->placemark_rating = 0;
					$map->markergroupctlpath = 0;
					$map->markergroupctlmarker = 1;
					$map->markergrouptype = 0;
					$map->showcreateinfo = 0;
					$map->markerorder = 0;
					$map->markergrouporder = 0;

					// //////////////////////////////////////////		
					
					$usermarkersfilter = "";

					// addition parameters
					if ($usermarkersfilter == "")
					{
						$usermarkersfilter = (int)$map->usermarkersfilter;
					}
					else
					{
						$usermarkersfilter = (int)$usermarkersfilter;
					}

					if ($map->useajaxobject == 0)
					{
						$markers = comZhYandexMapData::getMarkers($map->id, $placemarklistid, $explacemarklistid, $grouplistid, $categorylistid, 
												    $map->usermarkers, $usermarkersfilter, $map->usercontact, $map->markerorder);
					}
					else
					{
						unset($markers);
					}					
					$paths = comZhYandexMapData::getPaths($map->id, "","","","");
					$routers = comZhYandexMapData::getRouters($map->id, "","","","");
					$maptypes = comZhYandexMapData::getMapTypes();
					
					$markergroups = comZhYandexMapData::getMarkerGroups($map->id, $placemarklistid, $explacemarklistid, $grouplistid, $categorylistid, $map->markergrouporder);
					$mgrgrouplist = comZhYandexMapData::getMarkerGroupsManage($map->id, 
																			  $placemarklistid, $explacemarklistid, $grouplistid, $categorylistid, 
																			  $map->markergrouporder, $map->markergroupctlmarker, $map->markergroupctlpath, 
																			  "","","","");
				}
				else
				{
					return false;
				}
				
			}
		}
		else if ($categoryIdWithPars !="0")
		{
			$pars = explode(";", $categoryIdWithPars.$parameterDefaultLine);
			$categoryId = $pars[0];
			$mapId = $pars[1];
			$categoryZoom = $pars[2];
			$categoryMapType = $pars[3];
			$categoryMapWidth = $pars[4];
			$categoryMapHeight = $pars[5];
			$hiddenContainer = $pars[6];


			if ($categoryZoom != "")
			{
				  $currentZoom = plgZhYandexMapPlacemarksHelper::parseZoom($categoryZoom);
			}

			if ($categoryMapType != "")
			{
			  $currentMapType = plgZhYandexMapPlacemarksHelper::parseMapType($categoryMapType);
			}

			if ($categoryMapWidth != "")
			{
				$currentMapWidth = $categoryMapWidth;
			}
			
			if ($categoryMapHeight != "")
			{
				$currentMapHeight = $categoryMapHeight;
			}
			
			if ((int)$mapId == 0)
			{
				return false;
			}
			else
			{
				$placemarklistid = "";
				$explacemarklistid = "";
				$grouplistid = "";
				$categorylistid = $categoryId;

				// it will be recalculated later -- begin
				$centerplacemarkid = "";
				$centerplacemarkaction = "";
				$centerplacemarkactionid = "";
				// it will be recalculated later -- end
				$externalmarkerlink = "";

				$map = comZhYandexMapData::getMap((int)$mapId);
				
				if (isset($map) && (int)$map->id != 0)
				{
					
					// under construction ///////////////////////

					$map->markerspinner = 0;
					$map->placemark_rating = 0;
					$map->markergroupctlpath = 0;
					$map->markergroupctlmarker = 1;
					$map->markergrouptype = 0;
					$map->showcreateinfo = 0;
					$map->markerorder = 0;
					$map->markergrouporder = 0;

					// //////////////////////////////////////////	
					
					$usermarkersfilter = "";

					// addition parameters
					if ($usermarkersfilter == "")
					{
						$usermarkersfilter = (int)$map->usermarkersfilter;
					}
					else
					{
						$usermarkersfilter = (int)$usermarkersfilter;
					}
															
					if ($map->useajaxobject == 0)
					{
						$markers = comZhYandexMapData::getMarkers($map->id, $placemarklistid, $explacemarklistid, $grouplistid, $categorylistid, 
												    $map->usermarkers, $usermarkersfilter, $map->usercontact, $map->markerorder);
					}
					else
					{
						unset($markers);
					}
					$paths = comZhYandexMapData::getPaths($map->id, "","","","");
					$routers = comZhYandexMapData::getRouters($map->id, "","","","");
					$maptypes = comZhYandexMapData::getMapTypes();
					
					$markergroups = comZhYandexMapData::getMarkerGroups($map->id, $placemarklistid, $explacemarklistid, $grouplistid, $categorylistid, $map->markergrouporder);
					$mgrgrouplist = comZhYandexMapData::getMarkerGroupsManage($map->id, 
																			  $placemarklistid, $explacemarklistid, $grouplistid, $categorylistid, 
																			  $map->markergrouporder, $map->markergroupctlmarker, $map->markergroupctlpath, 
																			  "","","","");
				}
				else
				{
					return false;
				}
				
			}
		}
		else 
		{
			return false;
		}
				

				
		if ($this->componentApiVersion == '2.x')
		{
		    $scriptlink = "";
		}
		else
		{
			$mapVersion = "1.1";
			$scriptlink	= $urlProtocol.'://api-maps.yandex.ru/'.$mapVersion.'/index.xml?key='. $this->apikey ;
		}
		$this->scriptlink = $scriptlink;
				
		if ($this->componentApiVersion == '2.x'
		    || $this->componentApiVersion == '2.0.x'
		    || $this->componentApiVersion == '2.1.x'
			)
		{
		    
		    // Change translation language and load translation
		    if (isset($map->lang) && $map->lang != "")
		    {
			    $currentLanguage = JFactory::getLanguage();
			    $currentLangTag = $currentLanguage->getTag();
			    $currentLanguage->setLanguage($map->lang); 		
			    JPlugin::loadLanguage();

			    $currentLanguage->setLanguage($currentLangTag); 		
		    }

		    $MapXArticleId = $currentArticleId;
		    $MapXdoLoad = 0;
		    $MapXsuffix = $this->MapXsuffix;
	
		    $placemarkTitleTag = $this->placemarktitletag;

		    $use_object_manager = $this->use_object_manager;
		    
		    $useObjectStructure = $this->useObjectStructure;

		    $current_custom_js_path = $this->current_custom_js_path;
		    

		    // -- -- extending ------------------------------------------
		    // class suffix, for example for module use
		    $cssClassSuffix = "";

		    // -- -- -- component options - begin -----------------------
		    switch ($this->componentApiVersion)
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


		    $compatiblemode = $this->compatiblemode;
		    $compatiblemodersf = $this->compatiblemodersf;

		    $licenseinfo = $this->licenseinfo;

		    $apikey = $this->apikey;

		    $urlProtocol = $this->urlProtocol;

		    if (($currentPlacemarkCenter != "") && ($currentPlacemarkCenter != "do not change"))
		    {
			    $centerplacemarkid = $currentPlacemarkCenter;
		    }
		    else
		    {
			    $centerplacemarkid = "";
		    }
		    
		    if (($currentZoom != "") && ($currentZoom != "do not change"))
		    {
			    $mapzoom = $currentZoom;
		    }
		    else
		    {
			    $mapzoom = "";
		    }

	
		    if (($currentMapWidth != "") && ($currentMapWidth != "do not change"))
		    {
			    $mapMapWidth = $currentMapWidth;
		    }
		    else
		    {
			    $mapMapWidth = "";
		    }

		    if (($currentMapHeight != "") && ($currentMapHeight != "do not change"))
		    {
			    $mapMapHeight = $currentMapHeight;
		    }
		    else
		    {
			    $mapMapHeight = "";
		    }
			    
		    // -- -- -- component options - end -------------------------
		    


		    // ***** Settings End ***************************************		    
		    require(JPATH_SITE.'/components/com_zhyandexmap/views/zhyandexmap/tmpl/v2x_display_map_data.php');

		    $use_object_manager = $this->use_object_manager;
		    

		    require(JPATH_SITE.'/components/com_zhyandexmap/views/zhyandexmap/tmpl/v2x_display_script.php');
		}
		else
		{
			require(JPATH_SITE.'/plugins/content/plg_zhyandexmap/v1x.php');
		}
		

		
	return true;
	}



}
