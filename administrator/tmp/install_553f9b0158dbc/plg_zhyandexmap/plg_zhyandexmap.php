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

jimport( 'joomla.plugin.plugin' );

class plgContentPlg_ZhYandexMap extends JPlugin
{	
	
	var $loadmodules;

	var $scriptlink;
	var $scripthead;
	var $scripttext;
	
	var $compatiblemodersf;
	
	var $licenseinfo;


	function parse_route_by_markers($markerId)
	{

		$app = JFactory::getApplication();

        $comparams = JComponentHelper::getParams( 'com_zhyandexmap' );

		$componentApiVersion = $comparams->get( 'map_api_version');
		
		if ($componentApiVersion == "")
		{
			$componentApiVersion = '2.x';
		}
	
		if ((int)$markerId != 0)
		{
			$dbMrk = JFactory::getDBO();

			$queryMrk = $dbMrk->getQuery(true);
			$queryMrk->select('h.*')
				->from('#__zhyandexmaps_markers as h')
				->where('h.id = '.(int) $markerId);
			$dbMrk->setQuery($queryMrk);        
			$myMarker = $dbMrk->loadObject();
			
			if (isset($myMarker))
			{
				if ($myMarker->latitude != "" && $myMarker->longitude != "")
				{
					if ($componentApiVersion == '2.x')
					{
						return '['.$myMarker->longitude.', ' .$myMarker->latitude.']';
					}
					else
					{
						return 'new YMaps.GeoPoint('.$myMarker->longitude.', ' .$myMarker->latitude.')';
					}
				}
				else
				{
					return 'geocode';
				}
			}
			else
			{
				return '';
			}
		
		}

	}



	function get_userinfo_for_marker($userId, $showuser)
	{

		if ($this->compatiblemodersf == 0)
		{
			$imgpathIcons = JURI::root() .'administrator/components/com_zhyandexmap/assets/icons/';
			$imgpathUtils = JURI::root() .'administrator/components/com_zhyandexmap/assets/utils/';
		}
		else
		{
			$imgpathIcons = JURI::root() .'components/com_zhyandexmap/assets/icons/';
			$imgpathUtils = JURI::root() .'components/com_zhyandexmap/assets/utils/';
		}
		
		if ((int)$userId != 0)
		{
			$cur_user_name = '';
			$cur_user_address = '';
			$cur_user_phone = '';
			
			$dbUsr = JFactory::getDBO();
			$queryUsr = $dbUsr->getQuery(true);
			
			$queryUsr->select('p.*, h.name as profile_username')
				->from('#__users as h')
				->leftJoin('#__user_profiles as p ON p.user_id=h.id')
				->where('h.id = '.(int)$userId);

			$dbUsr->setQuery($queryUsr);        
			$myUsr = $dbUsr->loadObjectList();
			
			if (isset($myUsr))
			{
				
				foreach ($myUsr as $key => $currentUsers) 
				{
					$cur_user_name = $currentUsers->profile_username;

					if ($currentUsers->profile_key == 'profile.address1')
					{
						$cur_user_address = $currentUsers->profile_value;
					}
					else if ($currentUsers->profile_key == 'profile.phone')
					{
						$cur_user_phone = $currentUsers->profile_value;
					}
					
					
				}
				
				$cur_scripttext = '';
				
				if (isset($showuser) && ((int)$showuser != 0))
				{
					switch ((int)$showuser) 
					{
						case 1:
							if ($cur_user_name != "") 
							{
								$cur_scripttext .= '\'<p class="placemarkBodyUser">'.JText::_('PLG_ZHYANDEXMAP_MAP_USER_USER_NAME').' '.htmlspecialchars(str_replace('\\', '/', $cur_user_name), ENT_QUOTES, 'UTF-8').'</p>\'+'."\n";
							}
							if ($cur_user_address != "") 
							{
								$cur_scripttext .= '\'<p class="placemarkBodyUser">'.JText::_('PLG_ZHYANDEXMAP_MAP_USER_USER_ADDRESS').' '.str_replace('<br /><br />', '<br />',str_replace(array("\r", "\r\n", "\n"), '<br />', htmlspecialchars(str_replace('\\', '/', $cur_user_address), ENT_QUOTES, 'UTF-8'))).'</p>\'+'."\n";
							}
							if ($cur_user_phone != "") 
							{
								$cur_scripttext .= '\'<p class="placemarkBodyUser">'.JText::_('PLG_ZHYANDEXMAP_MAP_USER_USER_PHONE').' '.htmlspecialchars(str_replace('\\', '/', $cur_user_phone), ENT_QUOTES, 'UTF-8').'</p>\'+'."\n";
							}
						break;
						case 2:
							if ($cur_user_name != "") 
							{
								$cur_scripttext .= '\'<p class="placemarkBodyUser">'.htmlspecialchars(str_replace('\\', '/', $cur_user_name), ENT_QUOTES, 'UTF-8').'</p>\'+'."\n";
							}
							if ($cur_user_address != "") 
							{
								$cur_scripttext .= '\'<p class="placemarkBodyUser"><img src="'.$imgpathUtils.'address.png" alt="'.JText::_('PLG_ZHYANDEXMAP_MAP_USER_USER_ADDRESS').'" />'.str_replace('<br /><br />', '<br />',str_replace(array("\r", "\r\n", "\n"), '<br />', htmlspecialchars(str_replace('\\', '/', $cur_user_address), ENT_QUOTES, 'UTF-8'))).'</p>\'+'."\n";
							}
							if ($cur_user_phone != "") 
							{
								$cur_scripttext .= '\'<p class="placemarkBodyUser"><img src="'.$imgpathUtils.'phone.png" alt="'.JText::_('PLG_ZHYANDEXMAP_MAP_USER_USER_PHONE').'" />'.htmlspecialchars(str_replace('\\', '/', $cur_user_phone), ENT_QUOTES, 'UTF-8').'</p>\'+'."\n";
							}
						break;
						case 3:
							if ($cur_user_name != "") 
							{
								$cur_scripttext .= '\'<p class="placemarkBodyUser">'.htmlspecialchars(str_replace('\\', '/', $cur_user_name), ENT_QUOTES, 'UTF-8').'</p>\'+'."\n";
							}
							if ($cur_user_address != "") 
							{
								$cur_scripttext .= '\'<p class="placemarkBodyUser">'.str_replace('<br /><br />', '<br />',str_replace(array("\r", "\r\n", "\n"), '<br />', htmlspecialchars(str_replace('\\', '/', $cur_user_address), ENT_QUOTES, 'UTF-8'))).'</p>\'+'."\n";
							}
							if ($cur_user_phone != "") 
							{
								$cur_scripttext .= '\'<p class="placemarkBodyUser">'.htmlspecialchars(str_replace('\\', '/', $cur_user_phone), ENT_QUOTES, 'UTF-8').'</p>\'+'."\n";
							}
						break;
						default:
							if ($cur_user_name != "") 
							{
								$cur_scripttext .= '\'<p class="placemarkBodyUser">'.htmlspecialchars(str_replace('\\', '/', $cur_user_name), ENT_QUOTES, 'UTF-8').'</p>\'+'."\n";
							}
							if ($cur_user_address != "") 
							{
								$cur_scripttext .= '\'<p class="placemarkBodyUser">'.str_replace('<br /><br />', '<br />',str_replace(array("\r", "\r\n", "\n"), '<br />', htmlspecialchars(str_replace('\\', '/', $cur_user_address), ENT_QUOTES, 'UTF-8'))).'</p>\'+'."\n";
							}
							if ($cur_user_phone != "") 
							{
								$cur_scripttext .= '\'<p class="placemarkBodyUser">'.htmlspecialchars(str_replace('\\', '/', $cur_user_phone), ENT_QUOTES, 'UTF-8').'</p>\'+'."\n";
							}
						break;										
					}
				}
				
				return $cur_scripttext;
			}
			else
			{
				return '';
			}	
		}
		else
		{
			return '';
		}	
	}
	
	public function onContentPrepare($context, &$article, &$params, $limitstart)
	{

		$parameterDefaultLine = ';;;;;;;;;;;;;;;;;;;;';

		$app = JFactory::getApplication();
		
        $comparams = JComponentHelper::getParams( 'com_zhyandexmap' );
		
		$this->compatiblemodersf = $comparams->get( 'map_compatiblemode_rsf');
		
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

		$document	= JFactory::getDocument();

		// Load plugin language
		JPlugin::loadLanguage();
		
		
		if ($this->compatiblemodersf == 0)
		{
			$document->addStyleSheet(JURI::root() .'administrator/components/com_zhyandexmap/assets/css/common.css');
		}
		else
		{
			$document->addStyleSheet(JURI::root() .'components/com_zhyandexmap/assets/css/common.css');
		}		
		
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

				if ($this->getMap($matchesMap[2][$i][0], $compoundID, "0", "0", "0", "0"))
				{
					$patternsMap = '/'.$matchesMap[0][$i][0].'/';
					$document->addScript($this->scriptlink . $this->loadmodules);
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

				if ($this->getMap("0", $compoundID, $matchesMrk[2][$i][0], "0", "0", "0"))
				{
					$patternsMrk = '/'.$matchesMrk[0][$i][0].'/';
					$document->addScript($this->scriptlink . $this->loadmodules);
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

				if ($this->getMap("0", $compoundID, "0", $matchesGrp[2][$i][0], "0", "0"))
				{
					$patternsGrp = '/'.$matchesGrp[0][$i][0].'/';
					$document->addScript($this->scriptlink . $this->loadmodules);
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

				if ($this->getMap("0", $compoundID, "0", "0", $matchesCategory[2][$i][0], "0"))
				{
					$patternsCategory = '/'.$matchesCategory[0][$i][0].'/';
					$document->addScript($this->scriptlink . $this->loadmodules);
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

				if ($this->getMap("0", $compoundID, "0", "0", "0", $matchesMrList[2][$i][0]))
				{
					$patternsMrList = '/'.$matchesMrList[0][$i][0].'/';
					$document->addScript($this->scriptlink . $this->loadmodules);
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

	function getAddPlacemarkWhere($mf)
	{
		$addWhereClause = "";

		switch ((int)$mf)
		{
			case 0:
				$addWhereClause .= ' and h.published=1';
			break;
			case 1:
				$currentUser = JFactory::getUser();
				$addWhereClause .= ' and h.published=1';
				$addWhereClause .= ' and h.createdbyuser='.(int)$currentUser->id;
			break;
			default:
				$addWhereClause .= ' and h.published=1';
			break;					
		}
		
		return $addWhereClause;
	}
	
	function getMap($mapIdWithPars, $currentArticleId, $placemarkIdWithPars, $groupIdWithPars, $categoryIdWithPars, $placemarkListWithPars)
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
			($placemarkListWithPars == "0") &&
			($groupIdWithPars == "0") &&
			($categoryIdWithPars =="0")
			)
		{
			return false;
		}

		$app = JFactory::getApplication();

        $comparams = JComponentHelper::getParams( 'com_zhyandexmap' );

		$apikey = $comparams->get( 'map_key');

		$componentApiVersion = $comparams->get( 'map_api_version');
		
		if ($componentApiVersion == "")
		{
			$componentApiVersion = '2.x';
		}
		
		$urlProtocol = "http";
		$curHTTPProtocol = $comparams->get( 'httpsprotocol');
		if ($curHTTPProtocol != "")
		{
			if ((int)$curHTTPProtocol == 1)
			{
				$urlProtocol = 'https';
			}
		}		
		
		$db = JFactory::getDBO();
		$addFilterWhere = "";
	
        if ($mapIdWithPars !="0")
        {

			$pars = explode(";", $mapIdWithPars.$parameterDefaultLine);
			$id = $pars[0];
			$hiddenContainer = $pars[1];

			if ((int)$id == 0)
			{
				return false;
			}
			else
			{
				$query = $db->getQuery(true);
				$query->select('h.*')
					->from('#__zhyandexmaps_maps as h')
					->where('h.id = '.(int)$id);
				$db->setQuery($query);        
				$map = $db->loadObject();

				$addFilterWhere = $this->getAddPlacemarkWhere($map->usermarkersfilter);

				if ((int)$map->usercontact == 1)
				{			
					$query = $db->getQuery(true);
					$query->select('h.*, '.
					' c.title as category, g.icontype as groupicontype,  g.overridemarkericon as overridemarkericon, g.published as publishedgroup, g.markermanagerminzoom as markermanagerminzoom, g.markermanagermaxzoom as markermanagermaxzoom,'.
					' cn.name as contact_name, cn.address as contact_address, cn.con_position as contact_position, cn.telephone as contact_phone, cn.mobile as contact_mobile, cn.fax as contact_fax ')
						->from('#__zhyandexmaps_markers as h')
						->leftJoin('#__categories as c ON h.catid=c.id')
						->leftJoin('#__zhyandexmaps_markergroups as g ON h.markergroup=g.id')
						->leftJoin('#__contact_details as cn ON h.contactid=cn.id')
						->where('h.mapid = '.(int)$id . ' '.$addFilterWhere)
						->order('h.title');

					$nullDate = $db->Quote($db->getNullDate());
					$nowDate = $db->Quote(JFactory::getDate()->toSQL());
					$query->where('(h.publish_up = ' . $nullDate . ' OR h.publish_up <= ' . $nowDate . ')');
					$query->where('(h.publish_down = ' . $nullDate . ' OR h.publish_down >= ' . $nowDate . ')');

					$db->setQuery($query);        
					$markers = $db->loadObjectList();
				}
				else
				{
					$query = $db->getQuery(true);
					$query->select('h.*, c.title as category, g.icontype as groupicontype, g.overridemarkericon as overridemarkericon, g.published as publishedgroup, g.markermanagerminzoom as markermanagerminzoom, g.markermanagermaxzoom as markermanagermaxzoom')
						->from('#__zhyandexmaps_markers as h')
						->leftJoin('#__categories as c ON h.catid=c.id')
						->leftJoin('#__zhyandexmaps_markergroups as g ON h.markergroup=g.id')
						->where('h.mapid = '.(int)$id . ' '.$addFilterWhere)
						->order('h.title');

					$nullDate = $db->Quote($db->getNullDate());
					$nowDate = $db->Quote(JFactory::getDate()->toSQL());
					$query->where('(h.publish_up = ' . $nullDate . ' OR h.publish_up <= ' . $nowDate . ')');
					$query->where('(h.publish_down = ' . $nullDate . ' OR h.publish_down >= ' . $nowDate . ')');

					$db->setQuery($query);        
					$markers = $db->loadObjectList();
				}
				
				$query = $db->getQuery(true);
				$query->select('h.*')
					->from('#__zhyandexmaps_routers as h')
					->where('h.published=1 and h.mapid = '.(int)$id);
				$db->setQuery($query);        
				$routers = $db->loadObjectList();

				$query = $db->getQuery(true);
				$query->select('h.*')
					->from('#__zhyandexmaps_paths as h')
					->where('h.published=1 and h.mapid = '.(int)$id);
				$db->setQuery($query);        
				$paths = $db->loadObjectList();

				// select all groups !!!
				$query = $db->getQuery(true);
				$query->select('h.*')
					->from('#__zhyandexmaps_markergroups as h')
					->where('1=1');
				$db->setQuery($query);        
				$markergroups = $db->loadObjectList();

				// Map Types
				$query = $db->getQuery(true);
				$query->select('h.*, c.title as category ')
					->from('#__zhyandexmaps_maptypes as h')
					->leftJoin('#__categories as c ON h.catid=c.id')
					->where('h.published=1');
				$db->setQuery($query);        
				$maptypes = $db->loadObjectList();
				
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
					break;
					default:
					  $currentCenter = "map";
					break;
				}
				
			}

			if ($placemarkZoom != "")
			{
				switch ($placemarkZoom)
				{
					case "1":
					  $currentZoom = "1";
					break;
					case "2":
					  $currentZoom = "2";
					break;
					case "3":
					  $currentZoom = "3";
					break;
					case "4":
					  $currentZoom = "4";
					break;
					case "5":
					  $currentZoom = "5";
					break;
					case "6":
					  $currentZoom = "6";
					break;
					case "7":
					  $currentZoom = "7";
					break;
					case "8":
					  $currentZoom = "8";
					break;
					case "9":
					  $currentZoom = "9";
					break;
					case "10":
					  $currentZoom = "10";
					break;
					case "11":
					  $currentZoom = "11";
					break;
					case "12":
					  $currentZoom = "12";
					break;
					case "13":
					  $currentZoom = "13";
					break;
					case "14":
					  $currentZoom = "14";
					break;
					case "15":
					  $currentZoom = "15";
					break;
					case "16":
					  $currentZoom = "16";
					break;
					case "17":
					  $currentZoom = "17";
					break;
					case "max available":
					  $currentZoom = "200";
					break;
					default:
						$currentZoom = "do not change";
					break;
				}
				
			}

			if ($placemarkMapType != "")
			{
				switch ($placemarkMapType)
				{
					case "MAP":
					  $currentMapType = "1";
					break;
					case "SATELLITE":
					  $currentMapType = "2";
					break;
					case "HYBRID":
					  $currentMapType = "3";
					break;
					case "PMAP":
					  $currentMapType = "4";
					break;
					case "PHYBRID":
					  $currentMapType = "5";
					break;
					default:
					  $currentMapType = "do not change";
					break;
				}
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
				
				$addFilterWhere = $this->getAddPlacemarkWhere($map->usermarkersfilter);
				
				if ((int)$map->usercontact == 1)
				{			
					$query = $db->getQuery(true);
					$query->select('h.*, '.
					' c.title as category, g.icontype as groupicontype, g.overridemarkericon as overridemarkericon, g.published as publishedgroup, g.markermanagerminzoom as markermanagerminzoom, g.markermanagermaxzoom as markermanagermaxzoom,'.
					' cn.name as contact_name, cn.address as contact_address, cn.con_position as contact_position, cn.telephone as contact_phone, cn.mobile as contact_mobile, cn.fax as contact_fax ')
						->from('#__zhyandexmaps_markers as h')
						->leftJoin('#__categories as c ON h.catid=c.id')
						->leftJoin('#__zhyandexmaps_markergroups as g ON h.markergroup=g.id')
						->leftJoin('#__contact_details as cn ON h.contactid=cn.id')
						->where('h.id = '.$placemarkId. ' '.$addFilterWhere);

					$nullDate = $db->Quote($db->getNullDate());
					$nowDate = $db->Quote(JFactory::getDate()->toSQL());
					$query->where('(h.publish_up = ' . $nullDate . ' OR h.publish_up <= ' . $nowDate . ')');
					$query->where('(h.publish_down = ' . $nullDate . ' OR h.publish_down >= ' . $nowDate . ')');
						
					$db->setQuery($query);        
					$markers = $db->loadObjectList();
				}
				else
				{
					$query = $db->getQuery(true);
					$query->select('h.*, '.
					' c.title as category, g.icontype as groupicontype, g.overridemarkericon as overridemarkericon, g.published as publishedgroup, g.markermanagerminzoom as markermanagerminzoom, g.markermanagermaxzoom as markermanagermaxzoom')
						->from('#__zhyandexmaps_markers as h')
						->leftJoin('#__categories as c ON h.catid=c.id')
						->leftJoin('#__zhyandexmaps_markergroups as g ON h.markergroup=g.id')
						->where('h.id = '.$placemarkId. ' '.$addFilterWhere);

					$nullDate = $db->Quote($db->getNullDate());
					$nowDate = $db->Quote(JFactory::getDate()->toSQL());
					$query->where('(h.publish_up = ' . $nullDate . ' OR h.publish_up <= ' . $nowDate . ')');
					$query->where('(h.publish_down = ' . $nullDate . ' OR h.publish_down >= ' . $nowDate . ')');
						
					$db->setQuery($query);        
					$markers = $db->loadObjectList();
				}
				
				// select all groups !!!
				$query = $db->getQuery(true);
				$query->select('h.*')
					->from('#__zhyandexmaps_markergroups as h')
					->where('1=1');
				$db->setQuery($query);        
				$markergroups = $db->loadObjectList();

				// Map Types
				$query = $db->getQuery(true);
				$query->select('h.*, c.title as category ')
					->from('#__zhyandexmaps_maptypes as h')
					->leftJoin('#__categories as c ON h.catid=c.id')
					->where('h.published=1');
				$db->setQuery($query);        
				$maptypes = $db->loadObjectList();
				
			}
        }
		else if ($placemarkListWithPars !="0")
		{
			$pars = explode(";", $placemarkListWithPars.$parameterDefaultLine);
			$placemarkListIds = $pars[0];
			$mapId = $pars[1];
			$placemarkZoom = $pars[2];
			$placemarkMapType = $pars[3];
			$placemarkMapWidth = $pars[4];
			$placemarkMapHeight = $pars[5];
			$hiddenContainer = $pars[6];

			if ($placemarkZoom != "")
			{
				switch ($placemarkZoom)
				{
					case "1":
					  $currentZoom = "1";
					break;
					case "2":
					  $currentZoom = "2";
					break;
					case "3":
					  $currentZoom = "3";
					break;
					case "4":
					  $currentZoom = "4";
					break;
					case "5":
					  $currentZoom = "5";
					break;
					case "6":
					  $currentZoom = "6";
					break;
					case "7":
					  $currentZoom = "7";
					break;
					case "8":
					  $currentZoom = "8";
					break;
					case "9":
					  $currentZoom = "9";
					break;
					case "10":
					  $currentZoom = "10";
					break;
					case "11":
					  $currentZoom = "11";
					break;
					case "12":
					  $currentZoom = "12";
					break;
					case "13":
					  $currentZoom = "13";
					break;
					case "14":
					  $currentZoom = "14";
					break;
					case "15":
					  $currentZoom = "15";
					break;
					case "16":
					  $currentZoom = "16";
					break;
					case "17":
					  $currentZoom = "17";
					break;
					case "max available":
					  $currentZoom = "200";
					break;
					default:
						$currentZoom = "do not change";
					break;
				}
				
			}

			if ($placemarkMapType != "")
			{
				switch ($placemarkMapType)
				{
					case "MAP":
					  $currentMapType = "1";
					break;
					case "SATELLITE":
					  $currentMapType = "2";
					break;
					case "HYBRID":
					  $currentMapType = "3";
					break;
					case "PMAP":
					  $currentMapType = "4";
					break;
					case "PHYBRID":
					  $currentMapType = "5";
					break;
					default:
					  $currentMapType = "do not change";
					break;
				}
			}

			if ($placemarkMapWidth != "")
			{
				$currentMapWidth = $placemarkMapWidth;
			}
			
			if ($placemarkMapHeight != "")
			{
				$currentMapHeight = $placemarkMapHeight;
			}
			
			if (((int)$mapId == 0) || ($placemarkListIds == ""))
			{
				return false;
			}
			else
			{
				$query = $db->getQuery(true);
				$query->select('h.*')
					->from('#__zhyandexmaps_maps as h')
					->where('h.id = '.(int)$mapId);
				$db->setQuery($query);        
				$map = $db->loadObject();

				$addFilterWhere = $this->getAddPlacemarkWhere($map->usermarkersfilter);

				if ((int)$map->usercontact == 1)
				{			
					$query = $db->getQuery(true);
					$query->select('h.*, '.
					' c.title as category, g.icontype as groupicontype, g.overridemarkericon as overridemarkericon, g.published as publishedgroup, g.markermanagerminzoom as markermanagerminzoom, g.markermanagermaxzoom as markermanagermaxzoom,'.
					' cn.name as contact_name, cn.address as contact_address, cn.con_position as contact_position, cn.telephone as contact_phone, cn.mobile as contact_mobile, cn.fax as contact_fax ')
						->from('#__zhyandexmaps_markers as h')
						->leftJoin('#__categories as c ON h.catid=c.id')
						->leftJoin('#__zhyandexmaps_markergroups as g ON h.markergroup=g.id')
						->leftJoin('#__contact_details as cn ON h.contactid=cn.id')
						->where('h.id IN ('.$placemarkListIds. ') '.$addFilterWhere)
						->order('h.title');

					$nullDate = $db->Quote($db->getNullDate());
					$nowDate = $db->Quote(JFactory::getDate()->toSQL());
					$query->where('(h.publish_up = ' . $nullDate . ' OR h.publish_up <= ' . $nowDate . ')');
					$query->where('(h.publish_down = ' . $nullDate . ' OR h.publish_down >= ' . $nowDate . ')');

					$db->setQuery($query);        
					$markers = $db->loadObjectList();
				}
				else
				{
					$query = $db->getQuery(true);
					$query->select('h.*, c.title as category, g.icontype as groupicontype, g.overridemarkericon as overridemarkericon, g.published as publishedgroup, g.markermanagerminzoom as markermanagerminzoom, g.markermanagermaxzoom as markermanagermaxzoom')
						->from('#__zhyandexmaps_markers as h')
						->leftJoin('#__categories as c ON h.catid=c.id')
						->leftJoin('#__zhyandexmaps_markergroups as g ON h.markergroup=g.id')
						->where('h.id IN ('.$placemarkListIds. ') '.$addFilterWhere)
						->order('h.title');

					$nullDate = $db->Quote($db->getNullDate());
					$nowDate = $db->Quote(JFactory::getDate()->toSQL());
					$query->where('(h.publish_up = ' . $nullDate . ' OR h.publish_up <= ' . $nowDate . ')');
					$query->where('(h.publish_down = ' . $nullDate . ' OR h.publish_down >= ' . $nowDate . ')');

					$db->setQuery($query);        
					$markers = $db->loadObjectList();
				}								

				$query = $db->getQuery(true);
				$query->select('h.*')
					->from('#__zhyandexmaps_routers as h')
					->where('h.published=1 and h.mapid = '.(int)$mapId);
				$db->setQuery($query);        
				$routers = $db->loadObjectList();

				$query = $db->getQuery(true);
				$query->select('h.*')
					->from('#__zhyandexmaps_paths as h')
					->where('h.published=1 and h.mapid = '.(int)$mapId);
				$db->setQuery($query);        
				$paths = $db->loadObjectList();
				
				// select all groups !!!
				$query = $db->getQuery(true);
				$query->select('h.*')
					->from('#__zhyandexmaps_markergroups as h')
					->where('1=1');
				$db->setQuery($query);        
				$markergroups = $db->loadObjectList();

				// Map Types
				$query = $db->getQuery(true);
				$query->select('h.*, c.title as category ')
					->from('#__zhyandexmaps_maptypes as h')
					->leftJoin('#__categories as c ON h.catid=c.id')
					->where('h.published=1');
				$db->setQuery($query);        
				$maptypes = $db->loadObjectList();
				
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
				switch ($groupZoom)
				{
					case "1":
					  $currentZoom = "1";
					break;
					case "2":
					  $currentZoom = "2";
					break;
					case "3":
					  $currentZoom = "3";
					break;
					case "4":
					  $currentZoom = "4";
					break;
					case "5":
					  $currentZoom = "5";
					break;
					case "6":
					  $currentZoom = "6";
					break;
					case "7":
					  $currentZoom = "7";
					break;
					case "8":
					  $currentZoom = "8";
					break;
					case "9":
					  $currentZoom = "9";
					break;
					case "10":
					  $currentZoom = "10";
					break;
					case "11":
					  $currentZoom = "11";
					break;
					case "12":
					  $currentZoom = "12";
					break;
					case "13":
					  $currentZoom = "13";
					break;
					case "14":
					  $currentZoom = "14";
					break;
					case "15":
					  $currentZoom = "15";
					break;
					case "16":
					  $currentZoom = "16";
					break;
					case "17":
					  $currentZoom = "17";
					break;
					case "max available":
					  $currentZoom = "200";
					break;
					default:
						$currentZoom = "do not change";
					break;
				}
				
			}

			if ($groupMapType != "")
			{
				switch ($groupMapType)
				{
					case "MAP":
					  $currentMapType = "1";
					break;
					case "SATELLITE":
					  $currentMapType = "2";
					break;
					case "HYBRID":
					  $currentMapType = "3";
					break;
					case "PMAP":
					  $currentMapType = "4";
					break;
					case "PHYBRID":
					  $currentMapType = "5";
					break;
					default:
					  $currentMapType = "do not change";
					break;
				}
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
				$query = $db->getQuery(true);
				$query->select('h.*')
					->from('#__zhyandexmaps_maps as h')
					->where('h.id = '.(int)$mapId);
				$db->setQuery($query);        
				$map = $db->loadObject();

				$addFilterWhere = $this->getAddPlacemarkWhere($map->usermarkersfilter);

				if (strpos($groupId, ','))
				{
					$mainFilter = 'h.markergroup in ('.$groupId.')';
				}
				else
				{
					$mainFilter = 'h.markergroup = '.(int)$groupId;
				}

				if ((int)$map->usercontact == 1)
				{			
					$query = $db->getQuery(true);
					$query->select('h.*, '.
					' c.title as category, g.icontype as groupicontype, g.overridemarkericon as overridemarkericon, g.published as publishedgroup, g.markermanagerminzoom as markermanagerminzoom, g.markermanagermaxzoom as markermanagermaxzoom,'.
					' cn.name as contact_name, cn.address as contact_address, cn.con_position as contact_position, cn.telephone as contact_phone, cn.mobile as contact_mobile, cn.fax as contact_fax ')
						->from('#__zhyandexmaps_markers as h')
						->leftJoin('#__categories as c ON h.catid=c.id')
						->leftJoin('#__zhyandexmaps_markergroups as g ON h.markergroup=g.id')
						->leftJoin('#__contact_details as cn ON h.contactid=cn.id')
						->where($mainFilter. ' '.$addFilterWhere)
						->order('h.title');

					$nullDate = $db->Quote($db->getNullDate());
					$nowDate = $db->Quote(JFactory::getDate()->toSQL());
					$query->where('(h.publish_up = ' . $nullDate . ' OR h.publish_up <= ' . $nowDate . ')');
					$query->where('(h.publish_down = ' . $nullDate . ' OR h.publish_down >= ' . $nowDate . ')');

					$db->setQuery($query);        
					$markers = $db->loadObjectList();
				}
				else
				{
					$query = $db->getQuery(true);
					$query->select('h.*, c.title as category, g.icontype as groupicontype, g.overridemarkericon as overridemarkericon, g.published as publishedgroup, g.markermanagerminzoom as markermanagerminzoom, g.markermanagermaxzoom as markermanagermaxzoom')
						->from('#__zhyandexmaps_markers as h')
						->leftJoin('#__categories as c ON h.catid=c.id')
						->leftJoin('#__zhyandexmaps_markergroups as g ON h.markergroup=g.id')
						->where($mainFilter. ' '.$addFilterWhere)
						->order('h.title');

					$nullDate = $db->Quote($db->getNullDate());
					$nowDate = $db->Quote(JFactory::getDate()->toSQL());
					$query->where('(h.publish_up = ' . $nullDate . ' OR h.publish_up <= ' . $nowDate . ')');
					$query->where('(h.publish_down = ' . $nullDate . ' OR h.publish_down >= ' . $nowDate . ')');

					$db->setQuery($query);        
					$markers = $db->loadObjectList();
				}								

				$query = $db->getQuery(true);
				$query->select('h.*')
					->from('#__zhyandexmaps_routers as h')
					->where('h.published=1 and h.mapid = '.(int)$mapId);
				$db->setQuery($query);        
				$routers = $db->loadObjectList();

				$query = $db->getQuery(true);
				$query->select('h.*')
					->from('#__zhyandexmaps_paths as h')
					->where('h.published=1 and h.mapid = '.(int)$mapId);
				$db->setQuery($query);        
				$paths = $db->loadObjectList();
				
				// select all groups !!!
				$query = $db->getQuery(true);
				$query->select('h.*')
					->from('#__zhyandexmaps_markergroups as h')
					->where('1=1');
				$db->setQuery($query);        
				$markergroups = $db->loadObjectList();

				// Map Types
				$query = $db->getQuery(true);
				$query->select('h.*, c.title as category ')
					->from('#__zhyandexmaps_maptypes as h')
					->leftJoin('#__categories as c ON h.catid=c.id')
					->where('h.published=1');
				$db->setQuery($query);        
				$maptypes = $db->loadObjectList();
				
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
				switch ($categoryZoom)
				{
					case "1":
					  $currentZoom = "1";
					break;
					case "2":
					  $currentZoom = "2";
					break;
					case "3":
					  $currentZoom = "3";
					break;
					case "4":
					  $currentZoom = "4";
					break;
					case "5":
					  $currentZoom = "5";
					break;
					case "6":
					  $currentZoom = "6";
					break;
					case "7":
					  $currentZoom = "7";
					break;
					case "8":
					  $currentZoom = "8";
					break;
					case "9":
					  $currentZoom = "9";
					break;
					case "10":
					  $currentZoom = "10";
					break;
					case "11":
					  $currentZoom = "11";
					break;
					case "12":
					  $currentZoom = "12";
					break;
					case "13":
					  $currentZoom = "13";
					break;
					case "14":
					  $currentZoom = "14";
					break;
					case "15":
					  $currentZoom = "15";
					break;
					case "16":
					  $currentZoom = "16";
					break;
					case "17":
					  $currentZoom = "17";
					break;
					case "max available":
					  $currentZoom = "200";
					break;
					default:
						$currentZoom = "do not change";
					break;
				}
				
			}

			if ($categoryMapType != "")
			{
				switch ($categoryMapType)
				{
					case "MAP":
					  $currentMapType = "1";
					break;
					case "SATELLITE":
					  $currentMapType = "2";
					break;
					case "HYBRID":
					  $currentMapType = "3";
					break;
					case "PMAP":
					  $currentMapType = "4";
					break;
					case "PHYBRID":
					  $currentMapType = "5";
					break;
					default:
					  $currentMapType = "do not change";
					break;
				}
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
				$query = $db->getQuery(true);
				$query->select('h.*')
					->from('#__zhyandexmaps_maps as h')
					->where('h.id = '.(int)$mapId);
				$db->setQuery($query);        
				$map = $db->loadObject();

				$addFilterWhere = $this->getAddPlacemarkWhere($map->usermarkersfilter);
				if (strpos($categoryId, ','))
				{
					$mainFilter = 'h.catid in ('.$categoryId.')';
				}
				else
				{
					$mainFilter = 'h.catid = '.(int)$categoryId;
				}

				if ((int)$map->usercontact == 1)
				{			
					$query = $db->getQuery(true);
					$query->select('h.*, '.
					' c.title as category, g.icontype as groupicontype, g.overridemarkericon as overridemarkericon, g.published as publishedgroup, g.markermanagerminzoom as markermanagerminzoom, g.markermanagermaxzoom as markermanagermaxzoom,'.
					' cn.name as contact_name, cn.address as contact_address, cn.con_position as contact_position, cn.telephone as contact_phone, cn.mobile as contact_mobile, cn.fax as contact_fax ')
						->from('#__zhyandexmaps_markers as h')
						->leftJoin('#__categories as c ON h.catid=c.id')
						->leftJoin('#__zhyandexmaps_markergroups as g ON h.markergroup=g.id')
						->leftJoin('#__contact_details as cn ON h.contactid=cn.id')
						->where($mainFilter. ' '.$addFilterWhere)
						->order('h.title');

					$nullDate = $db->Quote($db->getNullDate());
					$nowDate = $db->Quote(JFactory::getDate()->toSQL());
					$query->where('(h.publish_up = ' . $nullDate . ' OR h.publish_up <= ' . $nowDate . ')');
					$query->where('(h.publish_down = ' . $nullDate . ' OR h.publish_down >= ' . $nowDate . ')');

					$db->setQuery($query);        
					$markers = $db->loadObjectList();
				}
				else
				{
					$query = $db->getQuery(true);
					$query->select('h.*, c.title as category, g.icontype as groupicontype, g.overridemarkericon as overridemarkericon, g.published as publishedgroup, g.markermanagerminzoom as markermanagerminzoom, g.markermanagermaxzoom as markermanagermaxzoom')
						->from('#__zhyandexmaps_markers as h')
						->leftJoin('#__categories as c ON h.catid=c.id')
						->leftJoin('#__zhyandexmaps_markergroups as g ON h.markergroup=g.id')
						->where($mainFilter. ' '.$addFilterWhere)
						->order('h.title');

					$nullDate = $db->Quote($db->getNullDate());
					$nowDate = $db->Quote(JFactory::getDate()->toSQL());
					$query->where('(h.publish_up = ' . $nullDate . ' OR h.publish_up <= ' . $nowDate . ')');
					$query->where('(h.publish_down = ' . $nullDate . ' OR h.publish_down >= ' . $nowDate . ')');

					$db->setQuery($query);        
					$markers = $db->loadObjectList();
				}
			
				$query = $db->getQuery(true);
				$query->select('h.*')
					->from('#__zhyandexmaps_routers as h')
					->where($mainFilter.' and h.published=1 and h.mapid = '.(int)$mapId);
				$db->setQuery($query);        
				$routers = $db->loadObjectList();

				$query = $db->getQuery(true);
				$query->select('h.*')
					->from('#__zhyandexmaps_paths as h')
					->where($mainFilter.' and h.published=1 and h.mapid = '.(int)$mapId);
				$db->setQuery($query);        
				$paths = $db->loadObjectList();
				
				// select all groups !!!
				$query = $db->getQuery(true);
				$query->select('h.*')
					->from('#__zhyandexmaps_markergroups as h')
					->where('1=1');
				$db->setQuery($query);        
				$markergroups = $db->loadObjectList();
				
				// Map Types
				$query = $db->getQuery(true);
				$query->select('h.*, c.title as category ')
					->from('#__zhyandexmaps_maptypes as h')
					->leftJoin('#__categories as c ON h.catid=c.id')
					->where('h.published=1');
				$db->setQuery($query);        
				$maptypes = $db->loadObjectList();
				
			}
		}
		else 
		{
			return false;
		}
				
		if (isset($map->lang) && ($map->lang != ""))
		{
			$apilang = $map->lang;
		}
		else
		{
			$apilang = 'ru-RU';
		}
				
		if ($componentApiVersion == '2.x')
		{
			$mapVersion = "2.0";
			$scriptlink	= $urlProtocol.'://api-maps.yandex.ru/'.$mapVersion.'/?coordorder=longlat&amp;load=package.full&amp;lang='.$apilang;
		}
		else
		{
			$mapVersion = "1.1";
			$scriptlink	= $urlProtocol.'://api-maps.yandex.ru/'.$mapVersion.'/index.xml?key='. $apikey ;
		}
		$this->scriptlink = $scriptlink;
				
		if ($componentApiVersion == '2.x')
		{
			require(JPATH_SITE.'/plugins/content/plg_zhyandexmap/v2x.php');
		}
		else
		{
			require(JPATH_SITE.'/plugins/content/plg_zhyandexmap/v1x.php');
		}
		

		
	return true;
	}



}
