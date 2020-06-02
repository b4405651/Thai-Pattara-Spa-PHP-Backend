<?php
/*------------------------------------------------------------------------
# com_zhyandexmap - Zh YandexMap Component
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
 * Zh YandexMap Helper
 */
abstract class comZhYandexMapPlacemarksHelper
{

	
	public static function get_userinfo_for_marker($userId, $showuser, $imgpathIcons, $imgpathUtils, $directoryIcons)
	{
		
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
								$cur_scripttext .= '\'<p class="placemarkBodyUser">'.JText::_('COM_ZHYANDEXMAP_MAP_USER_USER_NAME').' '.htmlspecialchars(str_replace('\\', '/', $cur_user_name), ENT_QUOTES, 'UTF-8').'</p>\'+'."\n";
							}
							if ($cur_user_address != "") 
							{
								$cur_scripttext .= '\'<p class="placemarkBodyUser">'.JText::_('COM_ZHYANDEXMAP_MAP_USER_USER_ADDRESS').' '.str_replace('<br /><br />', '<br />',str_replace(array("\r", "\r\n", "\n"), '<br />', htmlspecialchars(str_replace('\\', '/', $cur_user_address), ENT_QUOTES, 'UTF-8'))).'</p>\'+'."\n";
							}
							if ($cur_user_phone != "") 
							{
								$cur_scripttext .= '\'<p class="placemarkBodyUser">'.JText::_('COM_ZHYANDEXMAP_MAP_USER_USER_PHONE').' '.htmlspecialchars(str_replace('\\', '/', $cur_user_phone), ENT_QUOTES, 'UTF-8').'</p>\'+'."\n";
							}
						break;
						case 2:
							if ($cur_user_name != "") 
							{
								$cur_scripttext .= '\'<p class="placemarkBodyUser">'.htmlspecialchars(str_replace('\\', '/', $cur_user_name), ENT_QUOTES, 'UTF-8').'</p>\'+'."\n";
							}
							if ($cur_user_address != "") 
							{
								$cur_scripttext .= '\'<p class="placemarkBodyUser"><img src="'.$imgpathUtils.'address.png" alt="'.JText::_('COM_ZHYANDEXMAP_MAP_USER_USER_ADDRESS').'" />'.str_replace('<br /><br />', '<br />',str_replace(array("\r", "\r\n", "\n"), '<br />', htmlspecialchars(str_replace('\\', '/', $cur_user_address), ENT_QUOTES, 'UTF-8'))).'</p>\'+'."\n";
							}
							if ($cur_user_phone != "") 
							{
								$cur_scripttext .= '\'<p class="placemarkBodyUser"><img src="'.$imgpathUtils.'phone.png" alt="'.JText::_('COM_ZHYANDEXMAP_MAP_USER_USER_PHONE').'" />'.htmlspecialchars(str_replace('\\', '/', $cur_user_phone), ENT_QUOTES, 'UTF-8').'</p>\'+'."\n";
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
	
	public static function get_placemark_coordinates($markerId)
	{
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
					return '['.$myMarker->longitude.', ' .$myMarker->latitude.']';
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



	public static function get_placemark_content_string_header(
						$currentArticleId,
						$currentmarker, $usercontact, $useruser,
						$usercontactattributes, $service_DoDirection,
						$imgpathIcons, $imgpathUtils, $directoryIcons, $placemarkrating, $lang, $titleTag, $showCreateInfo)
	{

		if (isset($titleTag) && $titleTag != "")
		{
			if ($titleTag == "h2"
			 || $titleTag == "h3")
			{
				$currentTitleTag = $titleTag;
			}
			else
			{
				$currentTitleTag ='h2';
			}
		}
		else
		{
			$currentTitleTag ='h2';
		}
		
		$returnText = '';
		
		$returnText .= '\'<div id="placemarkContent'. $currentmarker->id.'">\' +' ."\n";
		if (isset($currentmarker->markercontent) &&
			(((int)$currentmarker->markercontent == 0) ||
			 ((int)$currentmarker->markercontent == 1))
			)
		{
			$returnText .= '\'<'.$currentTitleTag.' id="headContent'. $currentmarker->id.'" class="placemarkHead">'.htmlspecialchars(str_replace('\\', '/', $currentmarker->title), ENT_QUOTES, 'UTF-8').'</'.$currentTitleTag.'>\'+' ."\n";
		}
		$returnText .= '\'</div>\';'."\n";

		return $returnText;	

	}	

	public static function get_placemark_content_string_header_cluster(
						$currentArticleId,
						$currentmarker, $usercontact, $useruser,
						$usercontactattributes, $service_DoDirection,
						$imgpathIcons, $imgpathUtils, $directoryIcons, $placemarkrating, $lang, $titleTag, $showCreateInfo)
	{

		if (isset($titleTag) && $titleTag != "")
		{
			if ($titleTag == "h2"
			 || $titleTag == "h3")
			{
				$currentTitleTag = $titleTag;
			}
			else
			{
				$currentTitleTag ='h2';
			}
		}
		else
		{
			$currentTitleTag ='h2';
		}
		
		$returnText = '';
		
		$returnText .= '\'<div id="placemarkContentCluster'. $currentmarker->id.'">\' +' ."\n";
		$returnText .= '\'<span id="headContentCluster'. $currentmarker->id.'" class="placemarkHeadCluster">'.htmlspecialchars(str_replace('\\', '/', $currentmarker->title), ENT_QUOTES, 'UTF-8').'</span>\'+' ."\n";
		$returnText .= '\'</div>\';'."\n";

		return $returnText;	

	}	

	public static function get_placemark_content_string_body(
						$currentArticleId,
						$currentmarker, $usercontact, $useruser,
						$usercontactattributes, $service_DoDirection,
						$imgpathIcons, $imgpathUtils, $directoryIcons, $placemarkrating, $lang, $titleTag, $showCreateInfo)
	{

		$currentLanguage = JFactory::getLanguage();
		$currentLangTag = $currentLanguage->getTag();
		
		if (isset($titleTag) && $titleTag != "")
		{
			if ($titleTag == "h2"
			 || $titleTag == "h3")
			{
				$currentTitleTag = $titleTag;
			}
			else
			{
				$currentTitleTag ='h2';
			}
		}
		else
		{
			$currentTitleTag ='h2';
		}
		
		if (isset($lang) && $lang != "")
		{
			$currentLanguage->load('com_zhyandexmap', JPATH_SITE, $lang, true);	
			$currentLanguage->load('com_zhyandexmap', JPATH_COMPONENT, $lang, true);	
			$currentLanguage->load('com_zhyandexmap', JPATH_SITE . '/components/com_zhyandexmap' , $lang, true);	
		}
		else
		{
			$currentLanguage->load('com_zhyandexmap', JPATH_SITE, $currentLangTag, true);	
			$currentLanguage->load('com_zhyandexmap', JPATH_COMPONENT, $currentLangTag, true);		
			$currentLanguage->load('com_zhyandexmap', JPATH_SITE . '/components/com_zhyandexmap', $currentLangTag, true);		
		}
		
		$returnText = '';
		$userContactAttrs = explode(",", $usercontactattributes);
		
		for($i = 0; $i < count($userContactAttrs); $i++) 
		{
			$userContactAttrs[$i] = strtolower(trim($userContactAttrs[$i]));
		}	



			
			
			if (1==2)//(isset($currentmarker->showgps) &&
			   //  ((int)$currentmarker->showgps != 0)
			   //	)
			{
				$returnText .= '\'<div id="gpsContent'. $currentmarker->id.'"  class="placemarkGPS">\'+'."\n";
				$returnText .= '\'<p class="placemarkGPSLatitude">\'+Convert_Latitude_Decimal2DMS('.$currentmarker->latitude.')+\'</p>\'+'."\n";				
				$returnText .= '\'<p class="placemarkGPSLongitude">\'+Convert_Longitude_Decimal2DMS('.$currentmarker->longitude.')+\'</p>\'+'."\n";				
				$returnText .= '\'</div>\'+'."\n";
			}
			
			$returnText .= '\'<div id="bodyContent'. $currentmarker->id.'"  class="placemarkBody">\'+'."\n";

			if ($currentmarker->hrefimage!="")
			{
				$tmp_image_path = strtolower($currentmarker->hrefimage);
				if (substr($tmp_image_path,0,5) == "http:"
				|| substr($tmp_image_path,0,6) == "https:"
				|| substr($tmp_image_path,0,1) == "/"
				|| substr($tmp_image_path,0,1) == ".")
				{
					$tmp_image_path_add = "";
				}
				else
				{
					$tmp_image_path_add = "/";
				}
				$returnText .= '\'<img src="'.$tmp_image_path_add.$currentmarker->hrefimage.'" alt="" />\'+'."\n";
			}

			if (isset($currentmarker->markercontent) &&
				(((int)$currentmarker->markercontent == 0) ||
				 ((int)$currentmarker->markercontent == 2))
				)
			{
				$returnText .= '\''.htmlspecialchars(str_replace('\\', '/', $currentmarker->description), ENT_QUOTES, 'UTF-8').'\'+'."\n";
			}
			$returnText .= '\''.str_replace("'", "\'", str_replace(array("\r", "\r\n", "\n"), '', $currentmarker->descriptionhtml)).'\'+'."\n";

			//$returnText .= ' latlng'. $currentmarker->id. '.toString()+'."\n";

			// Contact info - begin
			if (isset($usercontact) && ((int)$usercontact != 0))
			{
				if (isset($currentmarker->showcontact) && ((int)$currentmarker->showcontact != 0))
				{
					switch ((int)$currentmarker->showcontact) 
					{
						case 1:
							for($i = 0; $i < count($userContactAttrs); $i++) 
							{
								if ($currentmarker->contact_name != ""
								&& $userContactAttrs[$i] == 'name') 
								{
									$returnText .= '\'<p class="placemarkBodyContact">'.JText::_('COM_ZHYANDEXMAP_MAP_USER_CONTACT_NAME').' '.htmlspecialchars(str_replace('\\', '/', $currentmarker->contact_name), ENT_QUOTES, 'UTF-8').'</p>\'+'."\n";
								}
								if ($currentmarker->contact_position != ""
								&& $userContactAttrs[$i] == 'position') 
								{
									$returnText .= '\'<p class="placemarkBodyContact">'.JText::_('COM_ZHYANDEXMAP_MAP_USER_CONTACT_POSITION').' '.htmlspecialchars(str_replace('\\', '/', $currentmarker->contact_position), ENT_QUOTES, 'UTF-8').'</p>\'+'."\n";
								}
								if ($currentmarker->contact_address != ""
								&& $userContactAttrs[$i] == 'address') 
								{
									$returnText .= '\'<p class="placemarkBodyContact">'.JText::_('COM_ZHYANDEXMAP_MAP_USER_CONTACT_ADDRESS').' '.str_replace('<br /><br />', '<br />',str_replace(array("\r", "\r\n", "\n"), '<br />', htmlspecialchars(str_replace('\\', '/', $currentmarker->contact_address), ENT_QUOTES, 'UTF-8'))).'</p>\'+'."\n";
								}
								
								if ($currentmarker->contact_suburb != ""
								&& $userContactAttrs[$i] == 'suburb') 
								{
									$returnText .= '\'<p class="placemarkBodyContact">'.JText::_('COM_ZHYANDEXMAP_MAP_USER_CONTACT_ADDRESS_SUBURB_SUBURB').' '.htmlspecialchars(str_replace('\\', '/', $currentmarker->contact_suburb), ENT_QUOTES, 'UTF-8').'</p>\'+'."\n";
								}
								if ($currentmarker->contact_suburb != ""
								&& $userContactAttrs[$i] == 'city') 
								{
									$returnText .= '\'<p class="placemarkBodyContact">'.JText::_('COM_ZHYANDEXMAP_MAP_USER_CONTACT_ADDRESS_SUBURB_CITY').' '.htmlspecialchars(str_replace('\\', '/', $currentmarker->contact_suburb), ENT_QUOTES, 'UTF-8').'</p>\'+'."\n";
								}
								if ($currentmarker->contact_state != ""
								&& $userContactAttrs[$i] == 'state') 
								{
									$returnText .= '\'<p class="placemarkBodyContact">'.JText::_('COM_ZHYANDEXMAP_MAP_USER_CONTACT_ADDRESS_STATE_STATE').' '.htmlspecialchars(str_replace('\\', '/', $currentmarker->contact_state), ENT_QUOTES, 'UTF-8').'</p>\'+'."\n";
								}
								if ($currentmarker->contact_state != ""
								&& $userContactAttrs[$i] == 'province') 
								{
									$returnText .= '\'<p class="placemarkBodyContact">'.JText::_('COM_ZHYANDEXMAP_MAP_USER_CONTACT_ADDRESS_STATE_PROVINCE').' '.htmlspecialchars(str_replace('\\', '/', $currentmarker->contact_state), ENT_QUOTES, 'UTF-8').'</p>\'+'."\n";
								}
								if ($currentmarker->contact_country != ""
								&& $userContactAttrs[$i] == 'country') 
								{
									$returnText .= '\'<p class="placemarkBodyContact">'.JText::_('COM_ZHYANDEXMAP_MAP_USER_CONTACT_ADDRESS_COUNTRY').' '.htmlspecialchars(str_replace('\\', '/', $currentmarker->contact_country), ENT_QUOTES, 'UTF-8').'</p>\'+'."\n";
								}
								if ($currentmarker->contact_postcode != ""
								&& $userContactAttrs[$i] == 'postcode') 
								{
									$returnText .= '\'<p class="placemarkBodyContact">'.JText::_('COM_ZHYANDEXMAP_MAP_USER_CONTACT_ADDRESS_POSTCODE_POSTAL').' '.htmlspecialchars(str_replace('\\', '/', $currentmarker->contact_postcode), ENT_QUOTES, 'UTF-8').'</p>\'+'."\n";
								}
								if ($currentmarker->contact_postcode != ""
								&& $userContactAttrs[$i] == 'zipcode') 
								{
									$returnText .= '\'<p class="placemarkBodyContact">'.JText::_('COM_ZHYANDEXMAP_MAP_USER_CONTACT_ADDRESS_POSTCODE_ZIP').' '.htmlspecialchars(str_replace('\\', '/', $currentmarker->contact_postcode), ENT_QUOTES, 'UTF-8').'</p>\'+'."\n";
								}
								
								if ($currentmarker->contact_phone != ""
								&& $userContactAttrs[$i] == 'phone') 
								{
									$returnText .= '\'<p class="placemarkBodyContact">'.JText::_('COM_ZHYANDEXMAP_MAP_USER_CONTACT_PHONE').' '.htmlspecialchars(str_replace('\\', '/', $currentmarker->contact_phone), ENT_QUOTES, 'UTF-8').'</p>\'+'."\n";
								}
								if ($currentmarker->contact_mobile != ""
								&& $userContactAttrs[$i] == 'mobile') 
								{
									$returnText .= '\'<p class="placemarkBodyContact">'.JText::_('COM_ZHYANDEXMAP_MAP_USER_CONTACT_MOBILE').' '.htmlspecialchars(str_replace('\\', '/', $currentmarker->contact_mobile), ENT_QUOTES, 'UTF-8').'</p>\'+'."\n";
								}
								if ($currentmarker->contact_fax != ""
								&& $userContactAttrs[$i] == 'fax') 
								{
									$returnText .= '\'<p class="placemarkBodyContact">'.JText::_('COM_ZHYANDEXMAP_MAP_USER_CONTACT_FAX').' '.htmlspecialchars(str_replace('\\', '/', $currentmarker->contact_fax), ENT_QUOTES, 'UTF-8').'</p>\'+'."\n";
								}
								
								if ($currentmarker->contact_email != ""
								&& $userContactAttrs[$i] == 'email') 
								{
									$returnText .= '\'<p class="placemarkBodyContact">'.JText::_('COM_ZHYANDEXMAP_MAP_USER_CONTACT_EMAIL').' '.str_replace('@','&#64;',htmlspecialchars(str_replace('\\', '/', $currentmarker->contact_email), ENT_QUOTES, 'UTF-8')).'</p>\'+'."\n";
								}

								if ($currentmarker->contact_webpage != ""
								&& $userContactAttrs[$i] == 'website') 
								{
									$returnText .= '\'<p class="placemarkBodyContact">'.JText::_('COM_ZHYANDEXMAP_MAP_USER_CONTACT_WEBSITE').' '.'<a href="'.htmlspecialchars(str_replace('\\', '/', $currentmarker->contact_webpage), ENT_QUOTES, 'UTF-8').'" target="_blank">'.htmlspecialchars(str_replace('\\', '/', $currentmarker->contact_webpage), ENT_QUOTES, 'UTF-8').'</a> '.'</p>\'+'."\n";
								}
								
							}			

						break;
						case 2:
							for($i = 0; $i < count($userContactAttrs); $i++) 
							{
								if ($currentmarker->contact_name != ""
								&& $userContactAttrs[$i] == 'name') 
								{
									$returnText .= '\'<p class="placemarkBodyContact">'.htmlspecialchars(str_replace('\\', '/', $currentmarker->contact_name), ENT_QUOTES, 'UTF-8').'</p>\'+'."\n";
								}
								if ($currentmarker->contact_position != ""
								&& $userContactAttrs[$i] == 'position') 
								{
									$returnText .= '\'<p class="placemarkBodyContact">'.htmlspecialchars(str_replace('\\', '/', $currentmarker->contact_position), ENT_QUOTES, 'UTF-8').'</p>\'+'."\n";
								}
								if ($currentmarker->contact_address != ""
								&& $userContactAttrs[$i] == 'address') 
								{
									$returnText .= '\'<p class="placemarkBodyContact"><img src="'.$imgpathUtils.'address.png" alt="'.JText::_('COM_ZHYANDEXMAP_MAP_USER_CONTACT_ADDRESS').'" />'.str_replace('<br /><br />', '<br />',str_replace(array("\r", "\r\n", "\n"), '<br />', htmlspecialchars(str_replace('\\', '/', $currentmarker->contact_address), ENT_QUOTES, 'UTF-8'))).'</p>\'+'."\n";
								}

								if ($currentmarker->contact_suburb != ""
								&& $userContactAttrs[$i] == 'suburb') 
								{
									$returnText .= '\'<p class="placemarkBodyContact">'.htmlspecialchars(str_replace('\\', '/', $currentmarker->contact_suburb), ENT_QUOTES, 'UTF-8').'</p>\'+'."\n";
								}
								if ($currentmarker->contact_suburb != ""
								&& $userContactAttrs[$i] == 'city') 
								{
									$returnText .= '\'<p class="placemarkBodyContact">'.htmlspecialchars(str_replace('\\', '/', $currentmarker->contact_suburb), ENT_QUOTES, 'UTF-8').'</p>\'+'."\n";
								}
								if ($currentmarker->contact_state != ""
								&& $userContactAttrs[$i] == 'state') 
								{
									$returnText .= '\'<p class="placemarkBodyContact">'.htmlspecialchars(str_replace('\\', '/', $currentmarker->contact_state), ENT_QUOTES, 'UTF-8').'</p>\'+'."\n";
								}
								if ($currentmarker->contact_state != ""
								&& $userContactAttrs[$i] == 'province') 
								{
									$returnText .= '\'<p class="placemarkBodyContact">'.htmlspecialchars(str_replace('\\', '/', $currentmarker->contact_state), ENT_QUOTES, 'UTF-8').'</p>\'+'."\n";
								}
								if ($currentmarker->contact_country != ""
								&& $userContactAttrs[$i] == 'country') 
								{
									$returnText .= '\'<p class="placemarkBodyContact">'.htmlspecialchars(str_replace('\\', '/', $currentmarker->contact_country), ENT_QUOTES, 'UTF-8').'</p>\'+'."\n";
								}
								if ($currentmarker->contact_postcode != ""
								&& $userContactAttrs[$i] == 'postcode') 
								{
									$returnText .= '\'<p class="placemarkBodyContact">'.htmlspecialchars(str_replace('\\', '/', $currentmarker->contact_postcode), ENT_QUOTES, 'UTF-8').'</p>\'+'."\n";
								}
								if ($currentmarker->contact_postcode != ""
								&& $userContactAttrs[$i] == 'zipcode') 
								{
									$returnText .= '\'<p class="placemarkBodyContact">'.htmlspecialchars(str_replace('\\', '/', $currentmarker->contact_postcode), ENT_QUOTES, 'UTF-8').'</p>\'+'."\n";
								}
								
								if ($currentmarker->contact_phone != ""
								&& $userContactAttrs[$i] == 'phone') 
								{
									$returnText .= '\'<p class="placemarkBodyContact"><img src="'.$imgpathUtils.'phone.png" alt="'.JText::_('COM_ZHYANDEXMAP_MAP_USER_CONTACT_PHONE').'" />'.htmlspecialchars(str_replace('\\', '/', $currentmarker->contact_phone), ENT_QUOTES, 'UTF-8').'</p>\'+'."\n";
								}
								if ($currentmarker->contact_mobile != ""
								&& $userContactAttrs[$i] == 'mobile') 
								{
									$returnText .= '\'<p class="placemarkBodyContact"><img src="'.$imgpathUtils.'mobile.png" alt="'.JText::_('COM_ZHYANDEXMAP_MAP_USER_CONTACT_MOBILE').'" />'.htmlspecialchars(str_replace('\\', '/', $currentmarker->contact_mobile), ENT_QUOTES, 'UTF-8').'</p>\'+'."\n";
								}
								if ($currentmarker->contact_fax != ""
								&& $userContactAttrs[$i] == 'fax') 
								{
									$returnText .= '\'<p class="placemarkBodyContact"><img src="'.$imgpathUtils.'fax.png" alt="'.JText::_('COM_ZHYANDEXMAP_MAP_USER_CONTACT_FAX').'" />'.htmlspecialchars(str_replace('\\', '/', $currentmarker->contact_fax), ENT_QUOTES, 'UTF-8').'</p>\'+'."\n";
								}
								
								if ($currentmarker->contact_email != ""
								&& $userContactAttrs[$i] == 'email') 
								{
									$returnText .= '\'<p class="placemarkBodyContact"><img src="'.$imgpathUtils.'email.png" alt="'.JText::_('COM_ZHYANDEXMAP_MAP_USER_CONTACT_EMAIL').'" />'.str_replace('@','&#64;',htmlspecialchars(str_replace('\\', '/', $currentmarker->contact_email), ENT_QUOTES, 'UTF-8')).'</p>\'+'."\n";
								}

								if ($currentmarker->contact_webpage != ""
								&& $userContactAttrs[$i] == 'website') 
								{
									$returnText .= '\'<p class="placemarkBodyContact"><img src="'.$imgpathUtils.'website.png" alt="'.JText::_('COM_ZHYANDEXMAP_MAP_USER_CONTACT_WEBSITE').'" />'.'<a href="'.htmlspecialchars(str_replace('\\', '/', $currentmarker->contact_webpage), ENT_QUOTES, 'UTF-8').'" target="_blank">'.htmlspecialchars(str_replace('\\', '/', $currentmarker->contact_webpage), ENT_QUOTES, 'UTF-8').'</a> '.'</p>\'+'."\n";
								}
								
							}
						break;
						case 3:
							for($i = 0; $i < count($userContactAttrs); $i++) 
							{
								if ($currentmarker->contact_name != ""
								&& $userContactAttrs[$i] == 'name') 
								{
									$returnText .= '\'<p class="placemarkBodyContact">'.htmlspecialchars(str_replace('\\', '/', $currentmarker->contact_name), ENT_QUOTES, 'UTF-8').'</p>\'+'."\n";
								}
								if ($currentmarker->contact_position != ""
								&& $userContactAttrs[$i] == 'position') 
								{
									$returnText .= '\'<p class="placemarkBodyContact">'.htmlspecialchars(str_replace('\\', '/', $currentmarker->contact_position), ENT_QUOTES, 'UTF-8').'</p>\'+'."\n";
								}
								if ($currentmarker->contact_address != ""
								&& $userContactAttrs[$i] == 'address') 
								{
									$returnText .= '\'<p class="placemarkBodyContact">'.str_replace('<br /><br />', '<br />',str_replace(array("\r", "\r\n", "\n"), '<br />', htmlspecialchars(str_replace('\\', '/', $currentmarker->contact_address), ENT_QUOTES, 'UTF-8'))).'</p>\'+'."\n";
								}

								if ($currentmarker->contact_suburb != ""
								&& $userContactAttrs[$i] == 'suburb') 
								{
									$returnText .= '\'<p class="placemarkBodyContact">'.htmlspecialchars(str_replace('\\', '/', $currentmarker->contact_suburb), ENT_QUOTES, 'UTF-8').'</p>\'+'."\n";
								}
								if ($currentmarker->contact_suburb != ""
								&& $userContactAttrs[$i] == 'city') 
								{
									$returnText .= '\'<p class="placemarkBodyContact">'.htmlspecialchars(str_replace('\\', '/', $currentmarker->contact_suburb), ENT_QUOTES, 'UTF-8').'</p>\'+'."\n";
								}
								if ($currentmarker->contact_state != ""
								&& $userContactAttrs[$i] == 'state') 
								{
									$returnText .= '\'<p class="placemarkBodyContact">'.htmlspecialchars(str_replace('\\', '/', $currentmarker->contact_state), ENT_QUOTES, 'UTF-8').'</p>\'+'."\n";
								}
								if ($currentmarker->contact_state != ""
								&& $userContactAttrs[$i] == 'province') 
								{
									$returnText .= '\'<p class="placemarkBodyContact">'.htmlspecialchars(str_replace('\\', '/', $currentmarker->contact_state), ENT_QUOTES, 'UTF-8').'</p>\'+'."\n";
								}
								if ($currentmarker->contact_country != ""
								&& $userContactAttrs[$i] == 'country') 
								{
									$returnText .= '\'<p class="placemarkBodyContact">'.htmlspecialchars(str_replace('\\', '/', $currentmarker->contact_country), ENT_QUOTES, 'UTF-8').'</p>\'+'."\n";
								}
								if ($currentmarker->contact_postcode != ""
								&& $userContactAttrs[$i] == 'postcode') 
								{
									$returnText .= '\'<p class="placemarkBodyContact">'.htmlspecialchars(str_replace('\\', '/', $currentmarker->contact_postcode), ENT_QUOTES, 'UTF-8').'</p>\'+'."\n";
								}
								if ($currentmarker->contact_postcode != ""
								&& $userContactAttrs[$i] == 'zipcode') 
								{
									$returnText .= '\'<p class="placemarkBodyContact">'.htmlspecialchars(str_replace('\\', '/', $currentmarker->contact_postcode), ENT_QUOTES, 'UTF-8').'</p>\'+'."\n";
								}
								
								if ($currentmarker->contact_phone != ""
								&& $userContactAttrs[$i] == 'phone') 
								{
									$returnText .= '\'<p class="placemarkBodyContact">'.htmlspecialchars(str_replace('\\', '/', $currentmarker->contact_phone), ENT_QUOTES, 'UTF-8').'</p>\'+'."\n";
								}
								if ($currentmarker->contact_mobile != ""
								&& $userContactAttrs[$i] == 'mobile') 
								{
									$returnText .= '\'<p class="placemarkBodyContact">'.htmlspecialchars(str_replace('\\', '/', $currentmarker->contact_mobile), ENT_QUOTES, 'UTF-8').'</p>\'+'."\n";
								}
								if ($currentmarker->contact_fax != ""
								&& $userContactAttrs[$i] == 'fax') 
								{
									$returnText .= '\'<p class="placemarkBodyContact">'.htmlspecialchars(str_replace('\\', '/', $currentmarker->contact_fax), ENT_QUOTES, 'UTF-8').'</p>\'+'."\n";
								}
								
								if ($currentmarker->contact_email != ""
								&& $userContactAttrs[$i] == 'email') 
								{
									$returnText .= '\'<p class="placemarkBodyContact">'.str_replace('@','&#64;',htmlspecialchars(str_replace('\\', '/', $currentmarker->contact_email), ENT_QUOTES, 'UTF-8')).'</p>\'+'."\n";
								}

								if ($currentmarker->contact_webpage != ""
								&& $userContactAttrs[$i] == 'website') 
								{
									$returnText .= '\'<p class="placemarkBodyContact">'.'<a href="'.htmlspecialchars(str_replace('\\', '/', $currentmarker->contact_webpage), ENT_QUOTES, 'UTF-8').'" target="_blank">'.htmlspecialchars(str_replace('\\', '/', $currentmarker->contact_webpage), ENT_QUOTES, 'UTF-8').'</a> '.'</p>\'+'."\n";
								}
								
							}
						break;
						default:
							for($i = 0; $i < count($userContactAttrs); $i++) 
							{
								if ($currentmarker->contact_name != ""
								&& $userContactAttrs[$i] == 'name') 
								{
									$returnText .= '\'<p class="placemarkBodyContact">'.htmlspecialchars(str_replace('\\', '/', $currentmarker->contact_name), ENT_QUOTES, 'UTF-8').'</p>\'+'."\n";
								}
								if ($currentmarker->contact_position != ""
								&& $userContactAttrs[$i] == 'position') 
								{
									$returnText .= '\'<p class="placemarkBodyContact">'.htmlspecialchars(str_replace('\\', '/', $currentmarker->contact_position), ENT_QUOTES, 'UTF-8').'</p>\'+'."\n";
								}
								if ($currentmarker->contact_address != ""
								&& $userContactAttrs[$i] == 'address') 
								{
									$returnText .= '\'<p class="placemarkBodyContact">'.str_replace('<br /><br />', '<br />',str_replace(array("\r", "\r\n", "\n"), '<br />', htmlspecialchars(str_replace('\\', '/', $currentmarker->contact_address), ENT_QUOTES, 'UTF-8'))).'</p>\'+'."\n";
								}
								if ($currentmarker->contact_suburb != ""
								&& $userContactAttrs[$i] == 'suburb') 
								{
									$returnText .= '\'<p class="placemarkBodyContact">'.htmlspecialchars(str_replace('\\', '/', $currentmarker->contact_suburb), ENT_QUOTES, 'UTF-8').'</p>\'+'."\n";
								}
								if ($currentmarker->contact_suburb != ""
								&& $userContactAttrs[$i] == 'city') 
								{
									$returnText .= '\'<p class="placemarkBodyContact">'.htmlspecialchars(str_replace('\\', '/', $currentmarker->contact_suburb), ENT_QUOTES, 'UTF-8').'</p>\'+'."\n";
								}
								if ($currentmarker->contact_state != ""
								&& $userContactAttrs[$i] == 'state') 
								{
									$returnText .= '\'<p class="placemarkBodyContact">'.htmlspecialchars(str_replace('\\', '/', $currentmarker->contact_state), ENT_QUOTES, 'UTF-8').'</p>\'+'."\n";
								}
								if ($currentmarker->contact_state != ""
								&& $userContactAttrs[$i] == 'province') 
								{
									$returnText .= '\'<p class="placemarkBodyContact">'.htmlspecialchars(str_replace('\\', '/', $currentmarker->contact_state), ENT_QUOTES, 'UTF-8').'</p>\'+'."\n";
								}
								if ($currentmarker->contact_country != ""
								&& $userContactAttrs[$i] == 'country') 
								{
									$returnText .= '\'<p class="placemarkBodyContact">'.htmlspecialchars(str_replace('\\', '/', $currentmarker->contact_country), ENT_QUOTES, 'UTF-8').'</p>\'+'."\n";
								}
								if ($currentmarker->contact_postcode != ""
								&& $userContactAttrs[$i] == 'postcode') 
								{
									$returnText .= '\'<p class="placemarkBodyContact">'.htmlspecialchars(str_replace('\\', '/', $currentmarker->contact_postcode), ENT_QUOTES, 'UTF-8').'</p>\'+'."\n";
								}
								if ($currentmarker->contact_postcode != ""
								&& $userContactAttrs[$i] == 'zipcode') 
								{
									$returnText .= '\'<p class="placemarkBodyContact">'.htmlspecialchars(str_replace('\\', '/', $currentmarker->contact_postcode), ENT_QUOTES, 'UTF-8').'</p>\'+'."\n";
								}
								if ($currentmarker->contact_phone != ""
								&& $userContactAttrs[$i] == 'phone') 
								{
									$returnText .= '\'<p class="placemarkBodyContact">'.htmlspecialchars(str_replace('\\', '/', $currentmarker->contact_phone), ENT_QUOTES, 'UTF-8').'</p>\'+'."\n";
								}
								if ($currentmarker->contact_mobile != ""
								&& $userContactAttrs[$i] == 'mobile') 
								{
									$returnText .= '\'<p class="placemarkBodyContact">'.htmlspecialchars(str_replace('\\', '/', $currentmarker->contact_mobile), ENT_QUOTES, 'UTF-8').'</p>\'+'."\n";
								}
								if ($currentmarker->contact_fax != ""
								&& $userContactAttrs[$i] == 'fax') 
								{
									$returnText .= '\'<p class="placemarkBodyContact">'.htmlspecialchars(str_replace('\\', '/', $currentmarker->contact_fax), ENT_QUOTES, 'UTF-8').'</p>\'+'."\n";
								}
								
								if ($currentmarker->contact_email != ""
								&& $userContactAttrs[$i] == 'email') 
								{
									$returnText .= '\'<p class="placemarkBodyContact">'.str_replace('@','&#64;',htmlspecialchars(str_replace('\\', '/', $currentmarker->contact_email), ENT_QUOTES, 'UTF-8')).'</p>\'+'."\n";
								}

								if ($currentmarker->contact_webpage != ""
								&& $userContactAttrs[$i] == 'website') 
								{
									$returnText .= '\'<p class="placemarkBodyContact">'.'<a href="'.htmlspecialchars(str_replace('\\', '/', $currentmarker->contact_webpage), ENT_QUOTES, 'UTF-8').'" target="_blank">'.htmlspecialchars(str_replace('\\', '/', $currentmarker->contact_webpage), ENT_QUOTES, 'UTF-8').'</a> '.'</p>\'+'."\n";
								}
								
							}
						break;										
					}
				}
			}
			// Contact info - end
			// User info - begin
			if (isset($useruser) && ((int)$useruser != 0))
			{
				$returnText .= comZhYandexMapPlacemarksHelper::get_userinfo_for_marker(
														$currentmarker->createdbyuser, $currentmarker->showuser,
														$imgpathIcons, $imgpathUtils, $directoryIcons);
			}
			// User info - end
			
			if ($currentmarker->hrefsite!="")
			{
				$returnText .= '\'<p><a class="placemarkHREF" href="'.$currentmarker->hrefsite.'" target="_blank">';
				if ($currentmarker->hrefsitename != "")
				{
					$returnText .= htmlspecialchars($currentmarker->hrefsitename, ENT_QUOTES, 'UTF-8');
				}
				else
				{
					$returnText .= $currentmarker->hrefsite;
				}
				$returnText .= '</a></p>\'+'."\n";
			}

			$returnText .= '\'</div>\''."\n";
		
		return $returnText;	
	}	
	
	
}
