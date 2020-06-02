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

use Joomla\Utilities\ArrayHelper;

// import Joomla controller library
jimport('joomla.application.component.controller');


require_once JPATH_SITE . '/components/com_zhyandexmap/helpers/placemarks.php';

/**
 * Zh YandexMap Component Controller
 */
class ZhYandexMapController extends JControllerLegacy
{
	

	public function getPlacemarkDetails() {

		$id = JRequest::getVar('id') ;
		$usercontactattributes = JRequest::getVar('contactattrs');
		$usercontact = JRequest::getVar('usercontact');
		$useruser = JRequest::getVar('useruser');
		$service_DoDirection = JRequest::getVar('servicedirection');
		$imgpathIcons = JRequest::getVar('iconicon');
		$imgpathUtils = JRequest::getVar('iconutil');
		$directoryIcons = JRequest::getVar('icondir');
		$currentArticleId = JRequest::getVar('articleid');
		$placemarkrating = JRequest::getVar('placemarkrating');
		$placemarkTitleTag = JRequest::getVar('placemarktitletag');
		$showcreateinfo = JRequest::getVar('showcreateinfo');
		
		$lang = JRequest::getVar('language');

		
		$db = JFactory::getDBO();

		$query = $db->getQuery(true);

  
		// Create some addition filters - Begin
		$addWhereClause = '';
		$addWhereClause .= ' and h.id = '. $id;
		
		if ((int)$usercontact == 1)
		{
			$query->select('h.*, '.
				' c.title as category, g.icontype as groupicontype, g.overridemarkericon as overridemarkericon, g.published as publishedgroup, g.markermanagerminzoom as markermanagerminzoom, g.markermanagermaxzoom as markermanagermaxzoom, '.
				' g.iconofsetx groupiconofsetx, g.iconofsety groupiconofsety,'.
				' cn.name as contact_name, cn.address as contact_address, cn.con_position as contact_position, cn.telephone as contact_phone, cn.mobile as contact_mobile, cn.fax as contact_fax ')
				->from('#__zhyandexmaps_markers as h')
				->leftJoin('#__categories as c ON h.catid=c.id')
				->leftJoin('#__zhyandexmaps_markergroups as g ON h.markergroup=g.id')
				->leftJoin('#__contact_details as cn ON h.contactid=cn.id')
				->where('1=1' . $addWhereClause);
		}
		else
		{
			$query->select('h.*, '.
				' c.title as category, g.icontype as groupicontype, g.overridemarkericon as overridemarkericon, g.published as publishedgroup, g.markermanagerminzoom as markermanagerminzoom, g.markermanagermaxzoom as markermanagermaxzoom, '.
				' g.iconofsetx groupiconofsetx, g.iconofsety groupiconofsety')
				->from('#__zhyandexmaps_markers as h')
				->leftJoin('#__categories as c ON h.catid=c.id')
				->leftJoin('#__zhyandexmaps_markergroups as g ON h.markergroup=g.id')
				->where('1=1' . $addWhereClause);
		}
		
		$db->setQuery($query);        
		
		$marker = $db->loadObject();
		
		
		if (isset($marker))
		{
			$responseVar = array( 'id'=>$id
								, 'dataexists'=>1
								, 'actionbyclick'=>$marker->actionbyclick
								, 'zoombyclick'=>$marker->zoombyclick
			//, 'usercontactattributes'=>$usercontactattributes
			//, 'usercontact'=>$usercontact
			//, 'useruser'=>$useruser
			//, 'service_DoDirection'=> $service_DoDirection
			//,'i'=>$imgpathIcons
			//,'u'=>$imgpathUtils
			//,'d'=>$directoryIcons
								);
			if ($marker->actionbyclick == 1)
			{
				//$responseVar['titleplacemark'] = htmlspecialchars(str_replace('\\', '/', $marker->title), ENT_QUOTES, 'UTF-8');
				$responseVar['contentstringheader'] = comZhYandexMapPlacemarksHelper::get_placemark_content_string_header(
											$currentArticleId,
											$marker, $usercontact, $useruser,
											$usercontactattributes, $service_DoDirection,
											$imgpathIcons, $imgpathUtils, $directoryIcons, 
											$placemarkrating, $lang, $placemarkTitleTag, $showcreateinfo);
				$responseVar['contentstringbody'] = comZhYandexMapPlacemarksHelper::get_placemark_content_string_body(
											$currentArticleId,
											$marker, $usercontact, $useruser,
											$usercontactattributes, $service_DoDirection,
											$imgpathIcons, $imgpathUtils, $directoryIcons, 
											$placemarkrating, $lang, $placemarkTitleTag, $showcreateinfo);
				$responseVar['contentstringheadercluster'] = comZhYandexMapPlacemarksHelper::get_placemark_content_string_header_cluster(
											$currentArticleId,
											$marker, $usercontact, $useruser,
											$usercontactattributes, $service_DoDirection,
											$imgpathIcons, $imgpathUtils, $directoryIcons, 
											$placemarkrating, $lang, $placemarkTitleTag, $showcreateinfo);
											
			}

			if ($marker->actionbyclick == 2 
				|| $marker->actionbyclick == 3)
			{
				$responseVar['hrefsite'] = $marker->hrefsite;
			}
			
		}
		else
		{
			$responseVar = array('id'=>$id
								,'dataexists'=>0
								);
		}
		echo (json_encode($responseVar));
		

	}
	
	
	public function getAJAXPlacemarkList() {

		$db = JFactory::getDBO();
		$query = $db->getQuery(true);

		$x1 = JRequest::getVar('x1');
		$x2 = JRequest::getVar('x2');
		$y1 = JRequest::getVar('y1');
		$y2 = JRequest::getVar('y2');
		
		$placemarkloadtype = JRequest::getVar('placemarkloadtype');
		
		$mapid = JRequest::getVar('mapid');
		$placemarklistid = str_replace(';',',', JRequest::getVar('placemarklistid'));
		$explacemarklistid = str_replace(';',',', JRequest::getVar('explacemarklistid'));
		$grouplistid = str_replace(';',',', JRequest::getVar('grouplistid'));
		$categorylistid = str_replace(';',',', JRequest::getVar('categorylistid'));
		$mf = JRequest::getVar('usermarkersfilter');
		
		$id = $mapid;


		
		// Create some addition filters - Begin
		$addWhereClause = '';

			
			if ($placemarklistid == ""
			 && $grouplistid == ""
			 && $categorylistid == "")
			{
				$addWhereClause .= ' and h.mapid='.(int)$id;

				if ($explacemarklistid != "")
				{
					$tmp_expl_ids = explode(',', str_replace(';',',', $explacemarklistid));                                       
                                        $tmp_expl_ids = ArrayHelper::toInteger($tmp_expl_ids);
                                        $tmp_expl_ids = implode(',', $tmp_expl_ids);                                        
					
					if (strpos($tmp_expl_ids, ','))
					{
						$addWhereClause .= ' and h.id NOT IN ('.$tmp_expl_ids.')';
					}
					else
					{
						$addWhereClause .= ' and h.id != '.(int)$tmp_expl_ids;
					}
				}
				
			}
			else
			{
				if ($placemarklistid != "")
				{
					$tmp_pl_ids = explode(',', str_replace(';',',', $placemarklistid));                                       
                                        $tmp_pl_ids = ArrayHelper::toInteger($tmp_pl_ids);
                                        $tmp_pl_ids = implode(',', $tmp_pl_ids);
                                        
					if (strpos($tmp_pl_ids, ','))
					{
						$addWhereClause .= ' and h.id IN ('.$tmp_pl_ids.')';
					}
					else
					{
						$addWhereClause .= ' and h.id = '.(int)$tmp_pl_ids;
					}
				}
				if ($explacemarklistid != "")
				{
					$tmp_expl_ids = explode(',', str_replace(';',',', $explacemarklistid));                                       
                                        $tmp_expl_ids = ArrayHelper::toInteger($tmp_expl_ids);
                                        $tmp_expl_ids = implode(',', $tmp_expl_ids);
					
					if (strpos($tmp_expl_ids, ','))
					{
						$addWhereClause .= ' and h.id NOT IN ('.$tmp_expl_ids.')';
					}
					else
					{
						$addWhereClause .= ' and h.id != '.(int)$tmp_expl_ids;
					}
				}
				if ($grouplistid != "")
				{
					$tmp_grp_ids = explode(',', str_replace(';',',', $grouplistid));                                       
                                        $tmp_grp_ids = ArrayHelper::toInteger($tmp_grp_ids);
                                        $tmp_grp_ids = implode(',', $tmp_grp_ids);
					
					if (strpos($tmp_grp_ids, ','))
					{
						$addWhereClause .= ' and h.markergroup IN ('.$tmp_grp_ids.')';
					}
					else
					{
						$addWhereClause .= ' and h.markergroup = '.(int)$tmp_grp_ids;
					}
				}
				if ($categorylistid != "")
				{
					$tmp_cat_ids = explode(',', str_replace(';',',', $categorylistid));                                       
                                        $tmp_cat_ids = ArrayHelper::toInteger($tmp_cat_ids);
                                        $tmp_cat_ids = implode(',', $tmp_cat_ids);
					
					if (strpos($tmp_cat_ids, ','))
					{
						$addWhereClause .= ' and h.catid IN ('.$tmp_cat_ids.')';
					}
					else
					{
						$addWhereClause .= ' and h.catid = '.(int)$tmp_cat_ids;
					}
				}
			}
			
			// You can not enter markers

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
				case 2:
					$currentUser = JFactory::getUser();
					$currentUserGroups = implode(',', $currentUser->getAuthorisedViewLevels());
					$addWhereClause .= ' and h.published=1';
					$addWhereClause .= ' and h.access IN (' . $currentUserGroups . ')';
				break;
				default:
					$addWhereClause .= ' and h.published=1';
				break;					
			}
			
			// Create some addition filters - End


			if ($placemarkloadtype == "2")
			{
				$addWhereClause .= ' and h.longitude >= '.$x1;
				$addWhereClause .= ' and h.longitude <= '.$x2;
				$addWhereClause .= ' and h.latitude >= '.$y1;
				$addWhereClause .= ' and h.latitude <= '.$y2;
			}

			$query->select('h.id'
				//',g.published as publishedgroup '
				)
				->from('#__zhyandexmaps_markers as h')
				->leftJoin('#__zhyandexmaps_markergroups as g ON h.markergroup=g.id')
				->where('1=1' . $addWhereClause)
			;

			$nullDate = $db->Quote($db->getNullDate());
			$nowDate = $db->Quote(JFactory::getDate()->toSQL());
			
			$query->where('(h.publish_up = ' . $nullDate . ' OR h.publish_up <= ' . $nowDate . ')');
			$query->where('(h.publish_down = ' . $nullDate . ' OR h.publish_down >= ' . $nowDate . ')');
			
            $db->setQuery($query);        
			
			// Markers
			if (!$markers = $db->loadObjectList()) 
			{
				$responseVar = array('cnt'=>0
									,'dataexists'=>0
									);
			}
			else
			{
				$responseVar = array( 'cnt'=>count($markers)
									, 'dataexists'=>1
									, 'markers'=> $markers 
									);
			}
			
		
		echo (json_encode($responseVar));
		

	}
	
	public function getAJAXPlacemarks() {

		
		$ajaxarray = JRequest::getVar('ajaxarray');

		// from placemark,because need to show in cluster info 
		$getgetdetails = JRequest::getVar('getdetails');
		if ($getgetdetails == "1")
		{
			$usercontactattributes = JRequest::getVar('contactattrs');
			$usercontact = JRequest::getVar('usercontact');
			$useruser = JRequest::getVar('useruser');
			$service_DoDirection = JRequest::getVar('servicedirection');
			$imgpathIcons = JRequest::getVar('iconicon');
			$imgpathUtils = JRequest::getVar('iconutil');
			$directoryIcons = JRequest::getVar('icondir');
			$currentArticleId = JRequest::getVar('articleid');
			$placemarkrating = JRequest::getVar('placemarkrating');
			$placemarkTitleTag = JRequest::getVar('placemarktitletag');
			$showcreateinfo = JRequest::getVar('showcreateinfo');	
			$lang = JRequest::getVar('language');
		}
		// 
		
		if (count($ajaxarray) > 0)
		{
                        $ajaxarray = ArrayHelper::toInteger($ajaxarray);
			$placemarklist = implode(",", $ajaxarray);
                        
				
	
			$db = JFactory::getDBO();

            $query = $db->getQuery(true);

      
			// Create some addition filters - Begin
			$addWhereClause = '';

			$addWhereClause .= ' and h.id IN ('.$placemarklist.')';

			$query->select('h.*, '.
				' g.icontype as groupicontype, g.overridemarkericon as overridemarkericon, g.published as publishedgroup, g.markermanagerminzoom as markermanagerminzoom, g.markermanagermaxzoom as markermanagermaxzoom, '.
				' g.iconofsetx groupiconofsetx, g.iconofsety groupiconofsety')
				->from('#__zhyandexmaps_markers as h')
				->leftJoin('#__zhyandexmaps_markergroups as g ON h.markergroup=g.id')
				->where('1=1' . $addWhereClause)
				->order('h.title');
			
            $db->setQuery($query);        
			
			// Markers
			if (!$markers = $db->loadObjectList()) 
			{
				$responseVar = array('cnt'=>0
									,'dataexists'=>0
									);
			}
			else
			{
				if ($getgetdetails == "1")
				{
					foreach ($markers as $key => $marker) 
					{
						if ($marker->actionbyclick == 1)
						{
							$marker->xxx_getdetails = 1;
							
							$marker->xxx_contentstringheader = comZhYandexMapPlacemarksHelper::get_placemark_content_string_header(
														$currentArticleId,
														$marker, $usercontact, $useruser,
														$usercontactattributes, $service_DoDirection,
														$imgpathIcons, $imgpathUtils, $directoryIcons, 
														$placemarkrating, $lang, $placemarkTitleTag, $showcreateinfo);
							$marker->xxx_contentstringbody = comZhYandexMapPlacemarksHelper::get_placemark_content_string_body(
														$currentArticleId,
														$marker, $usercontact, $useruser,
														$usercontactattributes, $service_DoDirection,
														$imgpathIcons, $imgpathUtils, $directoryIcons, 
														$placemarkrating, $lang, $placemarkTitleTag, $showcreateinfo);
							$marker->xxx_contentstringheadercluster = comZhYandexMapPlacemarksHelper::get_placemark_content_string_header_cluster(
														$currentArticleId,
														$marker, $usercontact, $useruser,
														$usercontactattributes, $service_DoDirection,
														$imgpathIcons, $imgpathUtils, $directoryIcons, 
														$placemarkrating, $lang, $placemarkTitleTag, $showcreateinfo);
						}
													
					}
				}
				else
				{
					foreach ($markers as $key => $marker) 
					{
						$marker->xxx_getdetails = 0;
					}
				}					
				
		
				$responseVar = array( 'cnt'=>count($markers)
									, 'dataexists'=>1
									, 'markers'=> $markers 
									// it doesn't need for production
									// , 'ajaxarray'=>$placemarklist
									);
			}
			
		}
		else
		{
			$responseVar = array('cnt'=>0
								,'dataexists'=>0
								);
		}

		echo (json_encode($responseVar));
		

	}
		
	
}
